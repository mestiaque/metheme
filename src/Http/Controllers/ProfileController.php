<?php

namespace Encodex\Metheme\Http\Controllers;

use Illuminate\View\View;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;
use Encodex\Metheme\Http\Requests\ProfileUpdateRequest;

class ProfileController extends Controller
{
    public function edit(Request $request): View
    {
        return view('me::profile.edit', [
            'user' => $request->user(),
        ]);
    }

    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        try {
            $validated = $request->validated();
            $data = [];

            if ($request->hasFile('profile_image')) {
                $image = $request->file('profile_image');
                $imageName = Str::uuid() . '.' . $image->getClientOriginalExtension();
                $imagePath = storage_path('app/public/images/profile_images');

                // Ensure the directory exists
                if (!file_exists($imagePath)) {
                    mkdir($imagePath, 0755, true);
                }
                // dd($imagePath, $imageName);
                $image->move($imagePath, $imageName);
                $data['profile_image'] = $imageName;
            }

            if (isset($validated['phone'])) {
                $request->user()->phone = $validated['phone'];
            }

            $request->user()->fill($validated);

            if (isset($data['profile_image'])) {
                $request->user()->profile_image = $data['profile_image'];
            }

            if ($request->user()->isDirty('email')) {
                $request->user()->email_verified_at = null;
            }

            $request->user()->save();

            return Redirect::route('encodex.profile.edit')->with('success', __('Profile updated'));
        } catch (\Exception $e) {
            return redirect()->route('encodex.profile.edit')->withErrors($e->getMessage())->withInput();
        }
    }

    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
