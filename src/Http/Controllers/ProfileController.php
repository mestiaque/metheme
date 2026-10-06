<?php

namespace ME\Http\Controllers;

use Illuminate\View\View;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;
use ME\Http\Requests\ProfileUpdateRequest;

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
            // The photo is not a column any more (me_media "avatar"), so keep it out of fill()
            $validated = $request->safe()->except('profile_image');

            if (isset($validated['phone'])) {
                $request->user()->phone = $validated['phone'];
            }

            $request->user()->fill($validated);


            if ($request->user()->isDirty('email')) {
                $request->user()->email_verified_at = null;
            }

            $request->user()->save();

            if ($request->hasFile('profile_image')) {
                $request->user()->replaceMedia($request->file('profile_image'), 'avatar');
            }

            return Redirect::route('profile.edit')->with('success', __('me::me.Profile updated'));
        } catch (\Exception $e) {
            return redirect()->route('profile.edit')->withErrors($e->getMessage())->withInput();
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
