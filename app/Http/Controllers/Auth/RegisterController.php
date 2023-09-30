<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class RegisterController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $request->validate([
            'username' => ['required', 'min:6', 'max:18'],
            'name' => ['required', 'string', 'min:3', 'max:30'],
            'email' => ['required', 'email', 'min:12', 'max:30'],
            'password' => ['required', 'min:6', 'max:30', 'confirmed'],
        ]);

        if (!$request) {
            return ValidationException::withMessages(['error' => 'Please fill in correctly']);
        }

        $request['password'] = bcrypt($request['password']);

        User::create($request->all());

        return response()->json(['success' => "You're registered now !"]);
    }
}
