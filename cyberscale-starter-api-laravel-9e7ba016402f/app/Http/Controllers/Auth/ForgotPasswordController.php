<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Http\Request;

class ForgotPasswordController extends Controller
{
    use SendsPasswordResetEmails;

    // The show form for email input
    public function showLinkRequestForm()
    {
        return view('auth.passwords.email');
    }

    // Handle sending reset link email
    public function sendResetLinkEmail(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ]);

        return $this->sendResetLinkEmail($request);
    }
}
