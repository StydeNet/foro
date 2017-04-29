@extends('layouts/app')

@section('content')
    <h1>{{ $post->title }}</h1>

    <p>{{ $post->content }}</p>

    <p>Author: {{ $post->user->name }}</p>

    <h4>Comentarios</h4>

    {!! Form::open(['route' => ['comments.store', $post], 'method' => 'POST']) !!}

        {!! Field::textarea('comment') !!}

        <div class="form-group">
            <div class="col-md-6 col-md-offset-4">
                <button type="submit" class="btn btn-primary">
                    Publicar Comentario
                </button>
            </div>
        </div>

    {!! Form::close() !!}

    @foreach($comments as $comment)
        <article class="{{ $comment->answer ? 'answer' : '' }}">
            <div class="well well-sm">
                <p><strong>{{ $comment->user->name }}</strong></p>

                <p>{{ $comment->comment }}</p>

                {!! Form::open(['route' => ['comments.accept', $comment], 'method' => 'POST']) !!}
                    <button type="submit">Aceptar Respuesta</button>
                {!! Form::close() !!}
            </div>

            <p class="date-t">
                <span class="glyphicon glyphicon-time"></span>
                {{ $comment->created_at->format('d/m/Y h:ia') }}
            </p>
        </article>
    @endforeach

    {{ $comments->render() }}
@endsection
