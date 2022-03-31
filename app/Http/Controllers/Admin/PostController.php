<?php

namespace App\Http\Controllers\Admin;

use App\User;
use App\Models\Tag;
use App\Models\Post;
use App\Models\Category;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;


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
        $tags = Tag::all();
        $categories = Category::all();
        $posts = Post::orderBy('updated_at', 'DESC')->paginate(10);
        return view('admin.posts.index', compact('posts', 'categories', 'users', 'tags'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $post = new Post();
        $tags = Tag::orderBy('label', 'ASC')->get();
        $categories = Category::all();
        return view('admin.posts.create', compact('post', 'categories', 'tags'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // dd($request->all());
        $request->validate([
            'title' => 'required|string|unique:posts|max:50|min:5',
            'content' => 'required|string|min:5',
            'image' => 'nullable|url',
            'category_id' => 'nullable|exists:categories,id',
            'tags' => 'nullable|exists:tags,id'
        ], [
            'required' => 'Il campo :attribute è obbligatorio.',
            'content.min' => 'Contenuto troppo corto.',
            'title.min' => 'Titolo troppo corto.',
            'title.max' => 'Titolo troppo lungo.',
            'url' => 'Non hai inserito un url corretto.',
            'title.unique' => "$request->title esiste già.",
            'category_id.exists' => 'Categoria non valida.',
            'tags.exists' => 'Non hai inserito un tag corretto.'
        ]);

        $data = $request->all();
        $post = new Post();

        $post->fill($data);
        $post->slug = Str::slug($request->title, '-');

        $post->user_id = Auth::id();

        if (array_key_exists('is_published', $data)) {
            $post['is_published'] = true;
        }

        $post->save();

        if (array_key_exists('tags', $data)) {
            $post->tags()->attach($data['tags']);
        }

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
        $tags = Tag::all();
        $users = User::all();
        $categories = Category::all();
        return view('admin.posts.show', compact('post', 'categories', 'users', 'tags'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  Post $post
     * @return \Illuminate\Http\Response
     */
    public function edit(Post $post)
    {
        $users = User::all();
        $tags = Tag::orderBy('label', 'ASC')->get();

        // creo una variabile dove prendo tutti i tag che sono nel post
        // con pluck recupero gli id e lo trasformo in array perchè il form si aspetta un array tags[]
        $post_tags_ids = $post->tags->pluck('id')->toArray();

        $categories = Category::all();
        return view('admin.posts.edit', compact('tags', 'post', 'categories', 'users', 'post_tags_ids'));
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
            'category_id' => 'nullable|exists:categories,id',
            'tags' => 'nullable|exists:tags,id'
        ], [
            'required' => 'Il campo :attribute è obbligatorio.',
            'content.min' => 'Contenuto troppo corto.',
            'title.min' => 'Titolo troppo corto.',
            'title.max' => 'Titolo troppo lungo.',
            'url' => 'Non hai inserito un url corretto.',
            'title.unique' => "$request->title esiste già.",
            'category_id.exists' => 'Categoria non valida.',
            'tags.exists' => 'Non hai inserito un tag corretto.',
        ]);

        $data = $request->all();

        $data['is_published'] = array_key_exists('is_published', $data) ? 1 : 0;

        $data['slug'] = Str::slug($request->title, '-');
        $post->update($data);

        // devo fare in modo che in update salvi i tag cambiati
        if (!array_key_exists('tags', $data)) $post->tags()->detach();
        else $post->tags()->sync($data['tags']);

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

        return redirect()->route('admin.posts.index')->with('message', "$post->title eliminato con successo!")->with('type', 'success');
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

    /**
     * Remove all resources from storage.
     *
     * 
     * @return \Illuminate\Http\Response
     */
    public function destroyAll(Request $request)
    {
        $num_posts = Post::count();
        DB::table('posts')->delete();

        return redirect()->route('admin.posts.index')->with('message', "$num_posts post eliminati con successo!")->with('type', 'success');
    }
}
