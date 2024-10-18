<?php

namespace tronderdata\kbartickles\Http\Livewire;

use Livewire\Component;
use tronderdata\kbartickles\Models\Article;

class ArticleView extends Component
{
    public $article;
    public $articleId;

    public function mount($articleId)
    {
        $this->articleId = $articleId;
        $this->article = Article::findOrFail($articleId);
    }

    public function render()
    {
        return view('kbartickles::livewire.article-view')
            ->layout('layouts.app');
    }
}
