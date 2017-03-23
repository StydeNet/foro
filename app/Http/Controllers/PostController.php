<?php

namespace App\Http\Controllers;

use App\Post;
use App\Category;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function index(Category $category = null, Request $request)
    {
        $posts = Post::orderBy('created_at', 'DESC')
            ->scopes($this->getListScopes($category, $request))
            ->paginate();

        $categoryItems = $this->getCategoryItems($request);

        return view('posts.index', compact('posts', 'category', 'categoryItems'));
    }
    
    public function show(Post $post, $slug)
    {
        if ($post->slug != $slug) {
            return redirect($post->url, 301);
        }

        return view('posts.show', compact('post'));
    }

    protected function getCategoryItems(Request $request)
    {
        return Category::orderBy('name')->get()->map(function ($category) use ($request) {
            return [
                'title' => $category->name,
                'full_url' => route($request->route()->getName(), $category)
            ];
        })->toArray();
    }

    protected function getListScopes(Category $category, Request $request)
    {
        $scopes = [];

        if ($category->exists) {
            $scopes['category'] = [$category];
        }

        $routeName = $request->route()->getName();

        if ($routeName == 'posts.pending') {
            $scopes[] = 'pending';
        }

        if ($routeName == 'posts.completed') {
            $scopes[] = 'completed';
        }

        return $scopes;
    }
}
