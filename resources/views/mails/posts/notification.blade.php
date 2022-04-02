<h1>Complimenti hai creato il post!</h1>

<h2>Titolo del post: {{ $post->title }}</h2>

@if ($post->image)
    <img class="img-fluid" src="{{ asset("storage/$post->image") }}" alt="{{ $post->slug }}">
@endif

<address>Da: {{ $post->author->name }}</address>
<span>Pubblicato alle: {{ $post->getFormattedDate('created_at') }}</span> <br>

<span><strong>Categoria: </strong> {{ $post->category->label }}</span>
<p>Tags:</p>
@if ($post->tags)
    <ul>
        @foreach ($post->tags as $tag)
            <li>{{ $tag->label }}</li>
        @endforeach
    </ul>
@else
    <p>Nessun tag</p>
@endif

@if ($post->is_published == 1)
    <p>Post pubblicato.</p>
@else
    <p>Non ancora pubblicato.</p>
@endif
