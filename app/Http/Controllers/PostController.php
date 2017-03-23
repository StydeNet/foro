<?php

namespace App\Http\Controllers;

use App\{Post, Category};
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function index(Category $category = null, Request $request)
    {
        $routeName = $request->route()->getName();

        $posts = Post::query()
            ->scopes($this->getListScopes($category, $routeName))
            ->latest()
            ->paginate();

        $categoryItems = $this->getCategoryItems($routeName);

        return view('posts.index', compact('posts', 'category', 'categoryItems'));
    }
    
    public function show(Post $post, $slug)
    {
        if ($post->slug != $slug) {
            return redirect($post->url, 301);
        }

        return view('posts.show', compact('post'));
    }

    protected function getCategoryItems(string $routeName)
    {
        return Category::query()
            ->orderBy('name')
            ->get()
            ->map(function ($category) use ($routeName) {
                return [
                    'title' => $category->name,
                    'full_url' => route($routeName, $category)
                ];
            })
            ->toArray();
    }

    protected function getListScopes(Category $category, string $routeName)
    {
        $scopes = [];

        if ($category->exists) {
            $scopes['category'] = [$category];
        }

        if ($routeName == 'posts.pending') {
            $scopes[] = 'pending';
        }

        if ($routeName == 'posts.completed') {
            $scopes[] = 'completed';
        }

        return $scopes;
    }
}
