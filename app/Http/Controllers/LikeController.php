<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Like;
use App\Models\Tweet;

class LikeController extends Controller
{
    // Toggle like/unlike on a tweet
    public function toggleLike($id)
    {
        $tweet = Tweet::findOrFail($id);

        $existingLike = Like::where('tweet_id', $tweet->id)
                            ->where('user_id', auth()->id())
                            ->first();

        if ($existingLike) {
            $existingLike->delete();
            return response()->json(['message' => 'Tweet unliked']);
        } else {
            Like::create([
                'tweet_id' => $tweet->id,
                'user_id' => auth()->id()
            ]);
            return response()->json(['message' => 'Tweet liked']);
        }
    }
}
