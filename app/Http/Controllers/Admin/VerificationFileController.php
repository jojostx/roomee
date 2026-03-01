<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Response;

class VerificationFileController extends Controller
{
    public function show(Request $request, User $user, string $type): Response
    {
        abort_unless($request->hasValidSignature(), 401);
        abort_unless($request->user()?->isAdmin(), 403);

        $path = match ($type) {
            'identity' => $user->identity_document_path,
            'selfie' => $user->selfie_path,
            default => null,
        };

        abort_if(blank($path), 404);
        abort_unless(Storage::disk('kyc_private')->exists($path), 404);

        try {
            return Storage::disk('kyc_private')->response($path, basename($path), [
                'Content-Disposition' => 'inline; filename="' . basename($path) . '"',
            ]);
        } catch (FileNotFoundException $exception) {
            abort(404);
        }
    }
}

