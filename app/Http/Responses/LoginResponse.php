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
    public function toResponse($request)
    {
        $user = Auth::user();

        // First, check if the user's profile is complete.
        if (! $user->isProfileComplete()) {
            // If not, redirect them to a dedicated page to complete their profile.
            return redirect()->route('profile.completion.form')
                ->with('warning', 'Welcome! Please complete your profile information to continue.');
        }

        // Check if the user is a `root`
        if ($user->hasRole('root')) {
            return redirect()->route('form-library');
        }

        // Check if the user is a `hr manager`
        if ($user->hasRole('hr manager')) {
            return redirect()->route('personnel');
        }

        // Check if the user is a `detachment commander`
        if ($user->hasRole('assigned officer')) {

            return redirect()->route('detachment-profile', ['id' => $user->detachment_id]);
        }

        // Check if the user is a `security guard`
        if ($user->hasRole('security guard')) {
            return redirect()->route('profile', ['id' => $user->id]);
        }

        // Default redirect to the home page if no specific role matches
        return $request->wantsJson()
          ? new JsonResponse(['two_factor' => false])
          : redirect()->route('profile');
    }
}
