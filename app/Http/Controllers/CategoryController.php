<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function show(string $slug, Request $request)
    {
        $category = Category::where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();

        $articles = $category->publishedArticles()
            ->with('category')
            ->latest('published_at')
            ->paginate(12);

        return view('categories.show', compact('category', 'articles'));
    }
}
