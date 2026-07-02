<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function index(Request $request)
    {
        $query = $request->validate(['q' => 'required|string|max:200'])['q'];

        $articles = Article::published()
            ->where(function ($q) use ($query) {
                $q->where('title', 'LIKE', "%{$query}%")
                  ->orWhere('content', 'LIKE', "%{$query}%")
                  ->orWhere('excerpt', 'LIKE', "%{$query}%")
                  ->orWhere('seo_keywords', 'LIKE', "%{$query}%");
            })
            ->with('category')
            ->latest('published_at')
            ->paginate(20);

        return view('search', compact('articles', 'query'));
    }

    public function suggest(Request $request)
    {
        $query = $request->validate(['q' => 'required|string|max:200'])['q'];

        $articles = Article::published()
            ->where(function ($q) use ($query) {
                $q->where('title', 'LIKE', "%{$query}%")
                  ->orWhere('excerpt', 'LIKE', "%{$query}%");
            })
            ->with('category:id,name,slug')
            ->latest('published_at')
            ->take(8)
            ->get(['id', 'title', 'slug', 'category_id', 'published_at', 'featured_image']);

        return response()->json($articles);
    }
}
