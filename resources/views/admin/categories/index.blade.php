@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12">
                <header>
                    <h1 class="text-center">Categorie del blog</h1>

                    @if (session('message'))
                        <div class="container alert alert-{{ session('type') }} text-center" role="alert">
                            <p>{{ session('message') }}</p>
                        </div>
                    @endif
                </header>
                <div class="add-categories d-flex justify-content-end mb-4">
                    <a class="btn btn-sm btn-info" href="{{ route('admin.categories.create') }}"><i
                            class="fa-solid fa-plus"></span></i></a>
                </div>
                <table class="table col-12 m-auto">
                    <thead>
                        <tr class="">
                            <th scope="col">#</th>
                            <th scope="col">Nome</th>
                            <th scope="col">Colore</th>
                            <th scope="col">Creato il</th>
                            <th scope="col">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($categories as $category)
                            <tr>
                                <th scope="row">{{ $category->id }}</th>
                                <td>{{ $category->label }}</td>
                                <td>
                                    @if (isset($category))
                                        <p class="badge display-1 badge-pill badge-{{ $category->color }}">
                                            <span class="h6">{{ $category->color }}</span>
                                        </p>
                                    @else
                                        -
                                    @endif
                                </td>
                                <td class="d-flex align-items-center justify-content-center">
                                    <a class="btn btn-sm btn-dark mr-2"
                                        href="{{ route('admin.categories.show', $category->id) }}"><i
                                            class="fa-regular fa-file-lines"></i></a>
                                    <a class="btn btn-sm btn-dark mr-2"
                                        href="{{ route('admin.categories.edit', $category->id) }}"><i
                                            class="text-white fa-solid fa-pen-to-square"></i></a>

                                    <form action="{{ route('admin.categories.destroy', $category->id) }}" method="POST"
                                        class="delete-form" data-name="{{ $category->title }}">
                                        @csrf
                                        @method('DELETE')
                                        <button class="fw-bold btn btn-sm btn-dark" type="submit"><i
                                                class="text-white fa-solid fa-trash"></i></button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center">
                                    <h3>Non ci sono categorie</h3>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
                <div class="add-categories d-flex justify-content-end mb-4">
                    {{-- <form action="{{ route('admin.categories.truncate') }}" method="POST" class="delete-form"
                        data-name="tutte le categorie">
                        @csrf
                        @method('DELETE')
                        <button class="fw-bold btn btn-sm btn-danger" type="submit"><i
                                class="text-white fa-solid fa-trash"></i> Elimina tutto</button>
                    </form> --}}
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="{{ asset('js/delete-confirm.js') }}" defer></script>
@endsection