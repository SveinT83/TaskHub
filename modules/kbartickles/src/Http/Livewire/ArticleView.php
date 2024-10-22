<?php

namespace tronderdata\kbartickles\Http\Livewire;

use Livewire\Component;
use tronderdata\kbartickles\Models\Article;

class ArticleView extends Component
{
    public $article;
    public $articleId;



    // ---------------------------------------------------------------------------------------------------------------------------------------------------
    // FUNCTION MOUNT
    // Mount function is used to initialize the component with the given data. In this case, we are initializing the component with the article data
    //
    // ---------------------------------------------------------------------------------------------------------------------------------------------------
    public function mount($articleId)
    {

        // -------------------------------------------------
        // Get the article with the given id
        // -------------------------------------------------
        $this->articleId = $articleId;
        $this->article = Article::findOrFail($articleId);
    }



    // ---------------------------------------------------------------------------------------------------------------------------------------------------
    // FUNCTION RENDER
    // Render function is used to render the component on the view
    //
    // ---------------------------------------------------------------------------------------------------------------------------------------------------
    public function render()
    {

        // -------------------------------------------------
        // check if the user has access to view the articles
        // -------------------------------------------------
        if (!auth()->user()->can('kb.view') && !auth()->user()->can('kb.admin')) {

            // -------------------------------------------------
            // Return 403 response if the user does not have access
            // -------------------------------------------------
            abort(403);
        }


        // -------------------------------------------------
        // Return the view with the article
        // -------------------------------------------------
        return view('kbartickles::livewire.article-view')
            ->layout('layouts.app');
    }
}
