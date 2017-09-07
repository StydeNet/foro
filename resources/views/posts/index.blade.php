@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <h1>
                {{ optional($category)->exists ? 'Posts de '.$category->name : 'Posts' }}
            </h1>
        </div>
    </div>
    <div class="row">
        @include('posts.sidebar')
        <div class="col-md-10">
            {!! Alert::render() !!}

            {!! Form::open(['method' => 'get', 'class' => 'form form-inline']) !!}
                {!! Form::select(
                    'orden',
                    trans('options.posts-order'),
                    request('orden'),
                    ['class' => 'form-control']
                ) !!}
                <button type="submit" class="btn btn-default">Ordenar</button>
            {!! Form::close() !!}

            @each('posts.item', $posts, 'post')

            {{ $posts->render() }}
        </div>
    </div>
@endsection
