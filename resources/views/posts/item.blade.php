<article>
    <h4><a href="{{ $post->url }}">{{ $post->title }}</a></h4>

    @include('posts.publisher', $post)

    {{ $post->vote_component }}

    <hr>
</article>
