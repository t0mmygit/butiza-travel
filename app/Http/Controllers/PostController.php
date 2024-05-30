<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required',
            'content' => 'required|string' 
        ]);

        $post = Post::create($validated);
        $request->user()->posts()->associate($post);

        return redirect(route('community'));
    }
}
