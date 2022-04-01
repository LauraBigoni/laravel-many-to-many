@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="d-flex justify-content-end align-items-center col-12 pt-3">
                <a class="btn btn-sm btn-info ml-auto" href="{{ route('admin.categories.index') }}">Indietro <i
                        class="fa-solid fa-arrow-left"></i></a>
            </div>
            <h1 class="pb-5"> Post che parlano di <span
                    class="badge badge-pill badge-{{ $category->color }}">{{ $category->label }}</span>
            </h1>
            <div class="d-flex flex-row flex-wrap justify-content-between align-items-start">

                @foreach ($category->posts as $post)
                    <div class="col-12">
                        <a class="text-decoration-none"
                            href="{{ route('admin.posts.show', $post->id) }}">	
                            &#903; {{ $post->title }}</a>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
    </div>
@endsection
