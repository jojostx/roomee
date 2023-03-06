<?php

namespace App\Http\Exceptions;

use Illuminate\Auth\AuthenticationException;

class InvalidVerificationLinkException extends AuthenticationException
{
}