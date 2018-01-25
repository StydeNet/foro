@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <h1>
                {{ $category->exists ? 'Posts de '.$category->name : 'Posts' }}
            </h1>
        </div>
    </div>
    <div class="row">
        <div class="col-md-2">
            <h4>Filtros</h4>
            {!! Menu::make(trans('menu.filters'), 'nav filters') !!}
            <h4>Categorías</h4>
            {!! Menu::make($categoryItems, 'nav categories') !!}
        </div>
        <div class="col-md-10">

            @if( $posts->count() == 0 )
                <div class="alert alert-info" role="alert"><strong>No hay Posts</strong> para la búsqueda realizada</div>
            @else
                @each('posts.item', $posts, 'post')
            @endif

            {{ $posts->render() }}
        </div>
    </div>
@endsection
