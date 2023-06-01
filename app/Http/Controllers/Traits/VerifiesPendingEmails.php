<?php

namespace App\Http\Controllers\Traits;

use App\Http\Exceptions\InvalidVerificationLinkException;
use App\Models\PendingUserEmail;
use Illuminate\Support\Facades\Auth;

trait VerifiesPendingEmails
{
    /**
     * Mark the user's new email address as verified.
     *
     * @param  string $token
     *
     * @throws \App\Http\Exceptions\Http\InvalidVerificationLinkException
     */
    public function verify(string $token)
    {
        $user = PendingUserEmail::query()
            ->where('token', $token)
            ->firstOr(['*'], function () {
                throw new InvalidVerificationLinkException(
                    __('The verification link is not valid anymore.')
                );
            })->tap(function (PendingUserEmail $pendingUserEmail) {
                $pendingUserEmail->activate();
            })->user;

        Auth::guard()->login($user, false);

        return $this->authenticated();
    }

    protected function authenticated()
    {
        return redirect(route('dashboard'))->with('verified', true);
    }
}
