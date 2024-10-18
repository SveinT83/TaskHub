<?php

namespace tronderdata\kbartickles\Http\Livewire;

use Livewire\Component;
use tronderdata\kbartickles\Models\Article;

class ArticleList extends Component
{
    public $articles;

    public function mount()
    {
        $this->articles = Article::all();
    }

    public function render()
    {
        return view('kbartickles::livewire.article-list')
        ->layout('layouts.app');
    }
}
