<?php

namespace ME\Http\Controllers;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use ME\Models\Role;
use ME\Models\User;
use Illuminate\Support\Facades\Hash;
use ME\Http\Controllers\Controller;
use ME\Http\Middleware\AuthorizationMiddleware;
use ME\Services\RoleHierarchy;
use Illuminate\Validation\Rule;

class UserController extends Controller
{

    public function __construct(private RoleHierarchy $hierarchy)
    {
        $this->middleware('authorization:me_user.view')->only(['index', 'show']);
        $this->middleware('authorization:me_user.create')->only(['create', 'store']);
        $this->middleware('authorization:me_user.edit')->only(['edit', 'update', 'toggleActive']);
        $this->middleware('authorization:me_user.delete')->only('destroy');
    }

    public function index(Request $request)
    {
        $users = User::with('roles')
            ->filter($request->only(['name', 'email', 'role']))
            ->latest()
            ->paginate(get_setting('pagination', 10))
            ->withQueryString();
        $roles = Role::orderBy('name')->get();

        // Users on this page the logged-in user may edit / deactivate / delete (hierarchy)
        $manageableUserIds = $users->getCollection()
            ->filter(fn ($u) => $this->hierarchy->canManageUser(auth()->user(), $u))
            ->pluck('id')->all();

        return view('me::users.index', compact('users', 'roles', 'manageableUserIds'));
    }

    /**
     * Roles the logged-in user may assign (roles below their own).
     */
    private function assignableRoles()
    {
        return Role::whereIn('id', $this->hierarchy->manageableRoleIds(auth()->user()))->orderBy('name')->get();
    }

    private function roleRule(): array
    {
        return ['required', 'integer', Rule::in($this->hierarchy->manageableRoleIds(auth()->user()))];
    }

    private function authorizeManage(User $user): void
    {
        abort_unless($this->hierarchy->canManageUser(auth()->user(), $user), 403, __('me::me.user_not_manageable'));
    }

    public function create()
    {
        $roles = $this->assignableRoles();
        return view('me::users.create', compact('roles'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone' => 'nullable|string|max:20',
            'password' => 'required|confirmed|min:8',
            'role' => $this->roleRule(),
            'is_active' => 'nullable|boolean',
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
            'is_active' => $request->has('is_active') ? 1 : 0,
        ];

        // Handle profile image upload
        if ($request->hasFile('profile_image')) {
            $image = $request->file('profile_image');
            $imageName = Str::uuid() . '.' . $image->getClientOriginalExtension();
            $imagePath = storage_path('app/public/images/profile_images');

            // Ensure the directory exists
            if (!file_exists($imagePath)) {
                mkdir($imagePath, 0755, true);
            }

            $image->move($imagePath, $imageName);
            $data['profile_image'] = $imageName;
        }

        $user = User::create($data);

        // Assign role
        if ($request->has('role')) {
            $user->roles()->sync([$request->role]);
        }

        return redirect()->route('users.index')->with('success', __('me::me.User created successfully'));
    }

    public function show(User $user)
    {
        $user->load('roles');
        return view('me::users.show', compact('user'));
    }

    public function edit(User $user)
    {
        $this->authorizeManage($user);
        $roles = $this->assignableRoles();
        $userRoles = $user->roles->pluck('id')->toArray();
        return view('me::users.edit', compact('user', 'roles', 'userRoles'));
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $this->authorizeManage($user);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:20',
            'password' => 'nullable|confirmed|min:8',
            'role' => $this->roleRule(),
            'is_active' => 'nullable|boolean',
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        // For security, don't allow users to deactivate their own account
        if ($user->id !== auth()->id()) {
            $data['is_active'] = $request->has('is_active') ? 1 : 0;
        }

        // Handle profile image upload
        if ($request->hasFile('profile_image')) {
            // Unlink old image if it exists
            if ($user->profile_image && file_exists(storage_path('app/public/images/profile_images/' . $user->profile_image))) {
                unlink(storage_path('app/public/images/profile_images/' . $user->profile_image));
            }

            $image = $request->file('profile_image');
            $imageName = Str::uuid() . '.' . $image->getClientOriginalExtension();
            $imagePath = storage_path('app/public/images/profile_images');

            // Ensure the directory exists
            if (!file_exists($imagePath)) {
                mkdir($imagePath, 0755, true);
            }

            $image->move($imagePath, $imageName);
            $data['profile_image'] = $imageName;
        }

        $user->update($data);

        // Sync role
        if ($request->has('role')) {
            $user->roles()->sync([$request->role]);
        } else {
            $user->roles()->detach();
        }

        return redirect()->route('users.index')->with('success', __('me::me.User updated successfully'));
    }

    public function toggleActive(User $user)
    {
        $this->authorizeManage($user);

        // Prevent deactivating your own account
        if ($user->id === auth()->id()) {
            return redirect()->route('users.index')
                ->with('error', 'You cannot deactivate your own account');
        }

        $user->is_active = !$user->is_active;
        $user->save();

        $status = $user->is_active ? 'activated' : 'deactivated';
        return redirect()->route('users.index')
            ->with('success', "User {$status} successfully");
    }

    public function destroy(User $user)
    {
        $this->authorizeManage($user);

        // Prevent deleting yourself
        if ($user->id === auth()->id()) {
            return redirect()->route('users.index')
                ->with('error', 'You cannot delete your own account');
        }

        $user->delete();

        return redirect()->route('users.index')
            ->with('success', 'User deleted successfully');
    }
}
