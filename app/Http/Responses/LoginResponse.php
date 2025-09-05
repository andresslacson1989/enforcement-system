<?php

namespace App\Http\Responses;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Fortify\Contracts\LoginResponse as LoginResponseContract;
use Symfony\Component\HttpFoundation\Response;

class LoginResponse implements LoginResponseContract
{
    /**
     * Create an HTTP response that represents the object.
     *
     * @param  Request  $request
     * @return Response
     */
    public function toResponse($request): JsonResponse|Response
    {
        $user = Auth::user();

        // First, check if the user's profile is complete.
        if (! $user->isProfileComplete()) {
            // If not, redirect them to a dedicated page to complete their profile.
            return redirect()->route('profile.completion.form')
                ->with('warning', 'Welcome! Please complete your profile information to continue.');
        }

        // Default redirect to the home page if no specific role matches
        return $request->wantsJson()
          ? new JsonResponse(['two_factor' => false])
          : redirect()->route('my-profile');
    }
}
