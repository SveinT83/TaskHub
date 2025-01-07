<?php

namespace tronderdata\kbartickles\Http\Controllers;

use App\Http\Controllers\Controller;
use tronderdata\kbartickles\Models\Article;

class ArticleController extends Controller
{
    public function index()
    {
        // Returner visningen som viser Livewire-komponenten for å liste artikler
        return view('kbartickles::index');
    }

    public function create($articleId = null)
    {
        // Returner visningen som viser Livewire-komponenten for å lage en ny artikkel
        return view('kbartickles::create', ['articleId' => $articleId]);
    }

    public function show($id)
    {
        // Returner visningen som viser Livewire-komponenten for å vise en spesifikk artikkel
        return view('kbartickles::show', ['articleId' => $id]);
    }

    public function destroy($id)
    {
        $article = Article::findOrFail($id);
        $article->delete();

        return redirect()->route('kb.index')->with('success', 'Article deleted successfully!');
    }
}
