<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Http\Request;

class ArticleController extends Controller
{
    public function index(Request $request)
    {
        $query = Article::with('author')->published();

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }
        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        $articles = $query->orderByDesc('published_at')->paginate(9);
        $categories = ['kesehatan_umum', 'gizi', 'olahraga', 'mental_health', 'tips_dokter', 'penyakit', 'ibu_anak'];
        $featured = Article::published()->orderByDesc('views')->first();

        return view('articles.index', compact('articles', 'categories', 'featured'));
    }

    public function show(Article $article)
    {
        if (!$article->is_published) abort(404);
        $article->increment('views');
        $article->load('author');
        $related = Article::published()
            ->where('category', $article->category)
            ->where('id', '!=', $article->id)
            ->take(3)->get();
        return view('articles.show', compact('article', 'related'));
    }
}
