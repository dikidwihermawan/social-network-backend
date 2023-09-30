<?php

namespace App\Http\Controllers\Users;

use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::whereNot('id', auth()->user()->id)->get(['id', 'username', 'name']);
        return auth()->user()->hasFollows($users);
    }

    public function following(Request $request)
    {

        $user = User::where('username', $request->username)->first();
        $auth = auth()->user();

        if (!$auth->hasFollow($user)) {
            $auth->follow($user);
            return response()->json(['success' => "You're following " . $user->name]);
        } else {
            $auth->unfollow($user);
            return response()->json(['success' => "You're unfollowing " . $user->name]);
        }
    }


    public function profile(Request $request)
    {
        return $request;
    }
}
