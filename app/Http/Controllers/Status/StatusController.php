<?php

namespace App\Http\Controllers\Status;

use App\Models\User;
use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Models\Like;
use App\Models\Status;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class StatusController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $statuses = Status::withCount('likesCount')->withCount('dislikesCount')->latest()->get();
        return $statuses;
    }
    public function store(Request $request)
    {
        auth()->user()->statuses()->create([
            'identifier' => Str::slug(Str::random(32)),
            'body' => $request->body,
        ]);
        return response()->json(['message' => 'Status has been created!']);
    }

    public function likes(Request $request)
    {
        $status = Status::where('identifier', $request->identifier)->pluck('id')->first();
        $data = ['status_id' => $status, 'user_id' => auth()->user()->id];
        $dataLike = ['status_id' => $status, 'user_id' => auth()->user()->id, 'type' => $request->type];
        $like = Like::where($data)->first();

        if (isset($like) && $like->type == 0 && $request->type == null) {
            $like->delete();
            return response()->json(['message' => 'Action has been given!']);
        } else if (isset($like) && $like->type == 1 && $request->type != null) {
            $like->delete();
            return response()->json(['message' => 'Action has been given!']);
        } else if (!isset($like)) {
            Like::create($dataLike);
            return response()->json(['message' => 'Action has been given!']);
        } else {
            $like->update($dataLike);
            return response()->json(['message' => 'Action has been given!']);
        }
    }

    public function comment(Request $request)
    {
        $request->validate([
            'body' => ['required']
        ]);

        $status = Status::where('identifier', $request->identifier)->pluck('id');
        Comment::create([
            'body' => $request->body,
            'user_id' => auth()->user()->id,
            'status_id' => $status[0],
        ]);

        return response()->json(['success' => 'Comment has been created']);
    }
}
