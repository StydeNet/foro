<?php

namespace App\Http\Composers;

use App\Category;
use Illuminate\View\View;
use Illuminate\Support\Facades\Route;

class PostSidebarComposer
{
    protected $listRoutes = ['posts.index', 'posts.completed', 'posts.pending'];

    public function compose(View $view)
    {
        $view->categoryItems = $this->getCategoryItems();

        $view->filters = trans('menu.filters');
    }

    protected function getCategoryItems()
    {
        $routeName = Route::getCurrentRoute()->getName();

        if (!in_array($routeName, $this->listRoutes)) {
            $routeName = 'posts.index';
        }

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
}
