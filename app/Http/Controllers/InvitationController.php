<?php

namespace App\Http\Controllers;

use App\Models\Invitation;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class InvitationController extends Controller
{
    public function show($token)
    {
        $invitation = Invitation::where('token', $token)->firstOrFail();

        if ($invitation->used) {
            abort(403, 'Invitation déjà utilisée');
        }

        if ($invitation->expires_at) {
            $expire = new \Carbon\Carbon($invitation->expires_at);
            if ($expire->isPast()) {
                abort(403, 'Invitation expirée');
            }
        }

        return view('invitation.accept', compact('invitation'));
    }

    public function store(Request $request, $token)
    {
        $invitation = Invitation::where('token', $token)->firstOrFail();

        if ($invitation->used) {
            abort(403);
        }

        $request->validate([
            'name' => 'required|string|min:2',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
        ]);

        $user = User::findOrFail($invitation->user_id);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password)
        ]);

        // marquer invitation utilisée
        $invitation->update([
            'used' => true
        ]);

        Auth::login($user);

        return redirect()->route('dashboard');
    }
}
