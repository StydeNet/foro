@extends('layouts/app')

@section('content')
    <h1>{{ $post->title }}</h1>

    <p>{{ $post->content }}</p>

    <p>{{ $post->user->name }}</p>

    <h4>Comentarios</h4>

    {!! Form::open(['route' => ['comments.store', $post], 'method' => 'POST']) !!}

        {!! Field::textarea('comment') !!}

        <button type="submit">
            Publicar comentario
        </button>

    {!! Form::close() !!}

    @foreach($comments as $comment)
        <article class="{{ $comment->answer ? 'answer' : '' }}">
            <div class="comment-author">
				{{$comment->user->name}}
			</div>
            {{ $comment->comment }}
            {!! Form::open(['route' => ['comments.accept', $comment], 'method' => 'POST']) !!}
            <button type="submit">Aceptar respuesta</button>
            {!! Form::close() !!}
        </article>
    @endforeach

    {{ $comments->render() }}
@endsection
