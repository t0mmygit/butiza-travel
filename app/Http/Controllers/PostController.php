<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        $user = $request->user();

        $validated = $request->validate([
            'user_id' => 'required',
            'content' => 'required|string' 
        ]);

        $user->posts()->create($validated);

        return redirect(route('community'));
    }

    public function destroy(Post $post): RedirectResponse
    {
        $this->authorize('delete', $post);
       
        $post->delete();

        return redirect(route('community'));
    }
}
