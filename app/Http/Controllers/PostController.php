<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class PostController extends Controller
{
    public function index()
    {
        // Eager loading: with('user') -> hindari N+1
        $posts = Post::with('user')->latest()->paginate(10);

        return view('posts.index', compact('posts'));
    }

    public function create()
    {
        return view('posts.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate($this->rules());

        // user_id diambil dari user yang login, BUKAN dari input form
        $request->user()->posts()->create($validated);

        return redirect()->route('posts.index')->with('success', 'Post berhasil dibuat!');
    }

    public function edit(Post $post)
    {
        Gate::authorize('update', $post); // PostPolicy@update -> 403 jika tidak berhak

        return view('posts.edit', compact('post'));
    }

    public function update(Request $request, Post $post)
    {
        Gate::authorize('update', $post);

        $post->update($request->validate($this->rules()));

        return redirect()->route('posts.index')->with('success', 'Post berhasil diperbarui!');
    }

    public function destroy(Post $post)
    {
        Gate::authorize('delete', $post); // PostPolicy@delete

        $post->delete();

        return redirect()->route('posts.index')->with('success', 'Post berhasil dihapus!');
    }

    private function rules(): array
    {
        return [
            'title' => 'required|max:200',
            'body'  => 'required|min:10',
        ];
    }
}
