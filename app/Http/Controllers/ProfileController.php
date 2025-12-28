<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB; // Tambahkan ini
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        // Ambil data sesi user yang sedang login dari database
        $sessions = DB::table('sessions')
            ->where('user_id', $request->user()->id)
            ->orderBy('last_activity', 'desc')
            ->get()
            ->transform(function ($session) {
                // Logika untuk mempercantik nama Agent (Browser/OS)
                $agent = $session->user_agent;
                $browser = 'Unknown';
                $platform = 'Unknown';

                // Deteksi Platform/OS
                if (preg_match('/windows|win32/i', $agent)) $platform = 'Windows';
                elseif (preg_match('/macintosh|mac os x/i', $agent)) $platform = 'Mac';
                elseif (preg_match('/linux/i', $agent)) $platform = 'Linux';
                elseif (preg_match('/android/i', $agent)) $platform = 'Android';
                elseif (preg_match('/iphone/i', $agent)) $platform = 'iPhone';

                // Deteksi Browser
                if (preg_match('/chrome/i', $agent)) $browser = 'Chrome';
                elseif (preg_match('/firefox/i', $agent)) $browser = 'Firefox';
                elseif (preg_match('/safari/i', $agent)) $browser = 'Safari';
                elseif (preg_match('/edge/i', $agent)) $browser = 'Edge';

                $session->device_name = "$platform - $browser";
                
                // Cek apakah ini sesi yang sedang dipakai sekarang
                $session->is_current_device = ($session->id === session()->getId());
                
                return $session;
            });

        return view('profile.edit', [
            'user' => $request->user(),
            'sessions' => $sessions, // Data sesi dikirim ke sini
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
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