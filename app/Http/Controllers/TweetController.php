<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tweet;

class TweetController extends Controller
{
    //  View all tweets (feed)
public function index()
{
    $user = auth()->user(); 

    $tweets = Tweet::with(['user', 'likes'])->latest()->get();

    $tweets = $tweets->map(function ($tweet) use ($user) {
        $tweet->likes_count = $tweet->likes->count();

        $tweet->is_liked = $user
            ? $tweet->likes->where('user_id', $user->id)->isNotEmpty()
            : false;

        unset($tweet->likes); 
        return $tweet;
    });

    return response()->json($tweets);
}



    public function store(Request $request)
    {
        $validated = $request->validate([
            'content' => 'required|string|max:280',
        ]);

        $tweet = Tweet::create([
            'user_id' => auth()->id(),
            'content' => $validated['content'],
        ]);

        return response()->json([
            'message' => 'Tweet posted successfully!',
            'tweet' => $tweet
        ]);
    }
}
