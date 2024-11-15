<?php

namespace tronderdata\kbartickles\Http\Livewire;

use Livewire\Component;
use tronderdata\kbartickles\Models\Article;

class ArticleList extends Component
{
    public $articles;



    // ---------------------------------------------------------------------------------------------------------------------------------------------------
    // FUNCTION MOUNT
    // Mount function is used to initialize the component with the given data. In this case, we are initializing the component with the article data
    //
    // ---------------------------------------------------------------------------------------------------------------------------------------------------
    public function mount()
    {

        // -------------------------------------------------
        // Get all articles with category
        // -------------------------------------------------
        $this->articles = Article::with('category')->get();
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
        // Return the view with the articles
        // -------------------------------------------------
        return view('kbartickles::livewire.article-list')
        ->layout('layouts.app');
    }
}
