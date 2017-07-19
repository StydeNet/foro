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
            ->when(auth()->check(), function ($q) {
                $q->with(['userVote']);
            })
            ->fromCategory($category)
            ->scopes($this->getRouteScope($request))
            ->orderBy($orderColumn, $orderDirection)
            ->paginate()
            ->appends($request->intersect(['orden']));

        return view('posts.index', compact('posts', 'category'));
    }

    protected function getRouteScope(Request $request)
    {
        $scopes = [
            'posts.mine' => ['byUser' => [$request->user()]],
            'posts.pending' => ['pending'],
            'posts.completed' => ['completed']
        ];

        return $scopes[$request->route()->getName()] ?? [];
    }

    protected function getListOrder($order)
    {
        $orders = [
            'recientes' => ['created_at', 'desc'],
            'antiguos' => ['created_at', 'asc'],
        ];

        return $orders[$order] ?? ['created_at', 'desc'];
    }
}
