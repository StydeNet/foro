@extends('layouts/app')

@section('content')
    <div class="row">
        <div class="col-md-10">
            <h1>Perfil de {{ $user->name }}</h1>
            <p>{{ "@$user->username" }}</p>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <h2>Posts</h2>
            @each('posts.item', $posts, 'post')

            {{ $posts->render() }}
        </div>
    </div>
@endsection
