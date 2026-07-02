<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function index(Request $request)
    {
        $query = $request->validate(['q' => 'required|string|max:200'])['q'];

        $articles = $this->search($query, 20);

        return view('search', compact('articles', 'query'));
    }

    public function suggest(Request $request)
    {
        $query = $request->validate(['q' => 'required|string|max:200'])['q'];

        $articles = $this->search($query, 8, suggest: true);

        return response()->json($articles);
    }

    protected function search(string $query, int $limit, bool $suggest = false)
    {
        $base = Article::published()->with($suggest ? 'category:id,name,slug' : 'category');

        try {
            $result = (clone $base)
                ->whereFullText(['title', 'excerpt', 'seo_keywords'], $query)
                ->latest('published_at');

            if ($suggest) {
                return $result->take($limit)->get(['id', 'title', 'slug', 'category_id', 'published_at', 'featured_image']);
            }

            return $result->paginate($limit);
        } catch (\Exception) {
            // FULLTEXT index not yet available — fall back to LIKE
            $result = (clone $base)
                ->where(function ($q) use ($query) {
                    $q->where('title', 'LIKE', "%{$query}%")
                      ->orWhere('excerpt', 'LIKE', "%{$query}%")
                      ->orWhere('seo_keywords', 'LIKE', "%{$query}%");
                })
                ->latest('published_at');

            if ($suggest) {
                return $result->take($limit)->get(['id', 'title', 'slug', 'category_id', 'published_at', 'featured_image']);
            }

            return $result->paginate($limit);
        }
    }
}
