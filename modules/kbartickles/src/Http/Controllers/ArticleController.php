<?php

namespace tronderdata\kbartickles\Http\Controllers;

use App\Http\Controllers\Controller;

class ArticleController extends Controller
{
    public function index()
    {
        // Returner visningen som viser Livewire-komponenten for å liste artikler
        return view('kbartickles::index');
    }

    public function create()
    {
        // Returner visningen som viser Livewire-komponenten for å lage en ny artikkel
        return view('kbartickles::create', [
            'livewireComponent' => 'article-form'
        ]);
    }

    public function show($id)
    {
        // Returner visningen som viser Livewire-komponenten for å vise en spesifikk artikkel
        return view('kbartickles::show', ['articleId' => $id]);
    }
}
