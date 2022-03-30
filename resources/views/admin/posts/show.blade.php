@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            @if (session('message'))
                <div class="container alert alert-{{ session('type') }} text-center" role="alert">
                    <p>{{ session('message') }}</p>
                </div>
            @endif
            <div class="d-flex justify-content-end align-items-center col-12 pt-3">
                <a class="btn btn-sm btn-info ml-auto" href="{{ route('admin.posts.index') }}">Indietro <i
                        class="fa-solid fa-arrow-left"></i></a>
            </div>
            <div class="col-12 d-flex flex-row justify-content-between align-items-center pt-5">
                <div class="col-2">
                    <img class="img-fluid" src="{{ $post->image }}" alt="{{ $post->slug }}">
                </div>
                <div class="col-10">
                    <h2 class="mb-4">{{ $post->title }}</h2>
                    <p> {{ $post->content }} </p>
                </div>
            </div>
            <div class="col-12 d-flex flex-row justify-content-between align-items-center pt-5">
                <div>
                    @if (isset($post->category))
                        <span class="badge badge-pill badge-{{ $post->category->color }}">
                            {{ $post->category->label }}
                        </span>
                    @else
                        -
                    @endif <br>
                    <span>Creato il: {{ $post->created_at }}</span> <br>
                    <span> Ultimo aggiornamento: {{ $post->updated_at }}</span>
                </div>

                <a class="btn btn-sm btn-dark ml-auto mr-2" href="{{ route('admin.posts.edit', $post->id) }}"><i
                        class="text-white fa-solid fa-pen-to-square"></i></a>

                <form action="{{ route('admin.posts.destroy', $post->id) }}" method="POST" class="delete-form"
                    data-name="{{ $post->title }}">
                    @csrf
                    @method('DELETE')

                    <button class="fw-bold btn btn-sm btn-dark" type="submit"><i
                            class="text-white fa-solid fa-trash"></i></button>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="{{ asset('js/delete-confirm.js') }}" defer></script>
@endsection
