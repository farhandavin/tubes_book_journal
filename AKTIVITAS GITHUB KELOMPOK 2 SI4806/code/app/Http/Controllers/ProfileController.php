<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage; // Import Storage Facade
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Menampilkan form profil user (beserta riwayat sesi).
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
            'sessions' => $sessions,
        ]);
    }

    /**
     * Memperbarui informasi profil (termasuk Foto Profil).
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();
        
        // Isi data dari request yang sudah divalidasi
        $user->fill($request->validated());

        // LOGIKA FOTO PROFIL
        if ($request->hasFile('profile_photo')) {
            // 1. Hapus foto lama dari storage jika ada
            if ($user->profile_photo) {
                Storage::disk('public')->delete($user->profile_photo);
            }
            
            // 2. Simpan foto baru ke folder 'profile_photos' di disk public
            $path = $request->file('profile_photo')->store('profile_photos', 'public');
            
            // 3. Simpan path-nya ke database
            $user->profile_photo = $path;
        }

        // Reset verifikasi email jika email diubah
        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Menghapus akun user.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        // Opsional: Hapus foto profil dari storage saat akun dihapus
        if ($user->profile_photo) {
            Storage::disk('public')->delete($user->profile_photo);
        }

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}