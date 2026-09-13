<?php

namespace App\Http\Controllers;

use App\Events\MessageSent;
use App\Models\Conversation;
use App\Models\User;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    public function index()
    {
        return view('messages.index');
    }


    public function store(Request $request, User $user)
    {
        $data = request()->validate([
            'message' => 'required', 'string',
        ]);

        abort_if(auth()->check() && auth()->user()->id === (int) $data['user_id'], 403);


        [$userOne, $userTwo] = collect(
            auth()->user(),
            $user->id,
        )->sort()->values()->all();

        $conversation = Conversation::query()->firstOrCreate([
            'user_one_id' => $userOne->id,
            'user_two_id' => $userTwo->id,
        ]);
        $message = $conversation->messages()->create([
            'user_id' => auth()->id(),
            'content' => $data['content'],
        ]);
        broadcast(new MessageSent(
            $message,
            $user->id
        ));
        return response()->json(['message' => $message->load('user') ]);
    }
}
