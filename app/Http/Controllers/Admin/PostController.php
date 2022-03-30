<?php

namespace App\Http\Controllers\Admin;

use App\User;
use App\Models\Post;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use App\Models\Category;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Post $post)
    {
        $users = User::all();
        $categories = Category::all();

        $posts = Post::orderBy('updated_at', 'DESC')->paginate(10);
        return view('admin.posts.index', compact('posts', 'categories', 'users',));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $post = new Post();
        $categories = Category::all();
        return view('admin.posts.create', compact('post', 'categories'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|unique:posts|max:50|min:5',
            'content' => 'required|string|min:5',
            'image' => 'nullable|url',
            'category_id' => 'nullable|exists:categories,id'
        ], [
            'required' => 'Il campo :attribute è obbligatorio.',
            'content.min' => 'Contenuto troppo corto.',
            'title.min' => 'Titolo troppo corto.',
            'title.max' => 'Titolo troppo lungo.',
            'url' => 'Non hai inserito un url corretto.',
            'title.unique' => "$request->title esiste già.",
            'category_id' => 'Categoria non valida.'
        ]);

        $data = $request->all();
        $post = new Post();

        $post->fill($data);
        $post->slug = Str::slug($request->title, '-');

        if (array_key_exists('is_published', $data)) {
            $post['is_published'] = true;
        }

        $post->save();

        return redirect()->route('admin.posts.show', $post->id)->with('message', "$post->title aggiunto con successo!")->with('type', 'success');
    }

    /**
     * Display the specified resource.
     *
     * @param  Post $post
     * @return \Illuminate\Http\Response
     */
    public function show(Post $post)
    {
        $categories = Category::all();
        $users = User::all();
        return view('admin.posts.show', compact('post', 'categories', 'users'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  Post $post
     * @return \Illuminate\Http\Response
     */
    public function edit(Post $post)
    {
        $categories = Category::all();
        $users = User::all();
        return view('admin.posts.edit', compact('post', 'categories', 'users'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  Post $post
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Post $post)
    {
        $request->validate([
            'title' => ['required', 'string', Rule::unique('posts')->ignore($post->id), 'max:50', 'min:5'],
            'content' => 'required|string|min:5',
            'image' => 'nullable|url',
            'category_id' => 'nullable|exists:categories,id'
        ], [
            'required' => 'Il campo :attribute è obbligatorio.',
            'content.min' => 'Contenuto troppo corto.',
            'title.min' => 'Titolo troppo corto.',
            'title.max' => 'Titolo troppo lungo.',
            'url' => 'Non hai inserito un url corretto.',
            'title.unique' => "$request->title esiste già.",
            'category_id' => 'Categoria non valida.'
        ]);

        $data = $request->all();

        $data['is_published'] = array_key_exists('is_published', $data) ? 1 : 0;

        $data['slug'] = Str::slug($request->title, '-');
        $post->update($data);

        return redirect()->route('admin.posts.show', $post->id)->with('message', "$post->title aggiornato con successo!")->with('type', 'success');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Post $post
     * @return \Illuminate\Http\Response
     */
    public function destroy(Post $post)
    {
        $post->delete();

        return redirect()->route('admin.posts.index')->with('message', "$post->title eliminato con successo!")->with('type', 'danger');
    }

    /**
     * Toggle the published state of posts.
     *
     * @param  Post $post
     * @return \Illuminate\Http\Response
     */
    public function toggle(Post $post)
    {
        $post->is_published = !$post->is_published;
        $published = $post->is_published ? 'pubblicato' : 'rimosso dalla pubblicazione';
        $post->save();

        return redirect()->route('admin.posts.index')->with('message', "$post->title $published con successo!")->with('type', 'success');
    }
}
