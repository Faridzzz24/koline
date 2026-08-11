<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class ArticleController extends Controller
{
    public function index()
    {
        $articles = Article::with('author')->orderByDesc('created_at')->paginate(15);
        return view('admin.articles.index', compact('articles'));
    }
    public function create() { return view('admin.articles.create'); }
    public function store(Request $request)
    {
        $request->validate(['title' => 'required', 'content' => 'required', 'category' => 'required']);
        Article::create([
            'author_id' => Auth::id(),
            'title' => $request->title,
            'slug' => Str::slug($request->title),
            'excerpt' => $request->excerpt ?? Str::limit(strip_tags($request->content), 150),
            'content' => $request->content,
            'category' => $request->category,
            'is_published' => $request->boolean('is_published'),
            'published_at' => $request->boolean('is_published') ? now() : null,
        ]);
        return redirect()->route('admin.artikel.index')->with('success', 'Artikel berhasil ditambahkan.');
    }
    public function edit(Article $artikel) { return view('admin.articles.edit', compact('artikel')); }
    public function update(Request $request, Article $artikel)
    {
        $artikel->update(array_merge($request->all(), [
            'is_published' => $request->boolean('is_published'),
            'published_at' => $request->boolean('is_published') ? ($artikel->published_at ?? now()) : null,
        ]));
        return redirect()->route('admin.artikel.index')->with('success', 'Artikel diperbarui.');
    }
    public function destroy(Article $artikel)
    {
        $artikel->delete();
        return redirect()->route('admin.artikel.index')->with('success', 'Artikel dihapus.');
    }
    public function show(Article $artikel) { return view('admin.articles.show', compact('artikel')); }
}
