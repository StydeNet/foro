<?php

namespace App\Http\Controllers;

use App\{Post, Category};
use Illuminate\Http\Request;

class ListPostController extends Controller
{
    public function __invoke(Category $category = null, Request $request)
    {
        list($orderColumn, $orderDirection) = $this->getListOrder($request->get('orden'));

        $posts = Post::query()
            ->with(['user', 'category'])
            ->scopes($this->getListScopes($category, $request))
            ->orderBy($orderColumn, $orderDirection)
            ->paginate()
            ->appends($request->intersect(['orden']));

        return view('posts.index', compact('posts', 'category'));
    }

    protected function getListScopes(Category $category, Request $request)
    {
        $scopes = [];

        $routeName = $request->route()->getName();

        if ($category->exists) {
            $scopes['category'] = [$category];
        }

        if ($routeName == 'posts.mine') {
            $scopes['byUser'] = [$request->user()];
        }

        if ($routeName == 'posts.pending') {
            $scopes[] = 'pending';
        }

        if ($routeName == 'posts.completed') {
            $scopes[] = 'completed';
        }

        return $scopes;
    }

    protected function getListOrder($order)
    {
        if ($order == 'recientes') {
            return ['created_at', 'desc'];
        }

        if ($order == 'antiguos') {
            return ['created_at', 'asc'];
        }

        return ['created_at', 'desc'];
    }
}
