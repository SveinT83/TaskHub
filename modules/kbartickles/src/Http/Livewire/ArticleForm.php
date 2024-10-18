<?php

namespace tronderdata\kbartickles\Http\Livewire;

use Livewire\Component;
use tronderdata\kbartickles\Models\Article;
use Illuminate\Support\Str;

class ArticleForm extends Component
{

    // -------------------------------------------------
    // Define the public properties
    // -------------------------------------------------
    public $title;
    public $slug;
    public $content;
    public $category_id;
    public $status;
    public $articleId;
    public $isEditMode = false;
    public $categories = []; // Legg til en egenskap for kategorier



    // ---------------------------------------------------------------------------------------------------------------------------------------------------
    // FUNCTION MOUNT
    // Mount function is used to initialize the component with the given data. In this case, we are initializing the component with the article data
    // if the articleId is provided.
    //
    // ---------------------------------------------------------------------------------------------------------------------------------------------------
    public function mount($articleId = null)
    {

        // -------------------------------------------------
        // If articleId is provided, then we are in edit mode
        // -------------------------------------------------
        if ($articleId) {
            $this->isEditMode = true;
            $article = Article::findOrFail($articleId);
            $this->articleId = $article->id;
            $this->title = $article->title;
            $this->slug = $article->slug;
            $this->content = $article->content;
            $this->category_id = $article->category_id;
            $this->status = $article->status;
        }

        // -------------------------------------------------
        // Get all categories if the category model exists
        // -------------------------------------------------
        if (class_exists('\tronderdata\categories\Models\Category')) {
            $this->categories = \tronderdata\categories\Models\Category::all();
        }
    }



    // ---------------------------------------------------------------------------------------------------------------------------------------------------
    // FUNCTION SAVE
    // Save function is used to save the article data. If the articleId is provided, it will update the article data,
    // otherwise it will create a new article.
    //
    // ---------------------------------------------------------------------------------------------------------------------------------------------------
    public function save()
    {
        // -------------------------------------------------
        // Generate slug from the title
        // -------------------------------------------------
        $this->slug = Str::slug($this->title);

        // -------------------------------------------------
        // Validate the data
        // -------------------------------------------------
        $data = $this->validate([
            'title' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:articles,slug,' . $this->articleId,
            'content' => 'required|string',
            'category_id' => 'nullable|integer',
            'status' => 'required|string|in:draft,published,archived',
        ]);

        // -------------------------------------------------
        // If the category model exists, then validate the category_id
        // -------------------------------------------------
        if ($this->isEditMode) {
            // Finn artikkelen og oppdater, hvis den eksisterer
            $article = Article::find($this->articleId);
            if ($article) {
                $article->update($data);
            }

        // -------------------------------------------------
        // Else create a new article
        // -------------------------------------------------
        } else {

            // -------------------------------------------------
            // Create a new article
            // -------------------------------------------------
            Article::create($data);
        }

        // -------------------------------------------------
        // Redirect to the article list page with success message
        // -------------------------------------------------
        return redirect()->route('kb.index')->with('success', 'Article saved successfully!');
    }



    // ---------------------------------------------------------------------------------------------------------------------------------------------------
    // FUNCTION RENDER
    // Render function is used to render the component view. In this case, we are rendering the article form view.
    //
    // ---------------------------------------------------------------------------------------------------------------------------------------------------
    public function render()
    {

        // -------------------------------------------------
        // Return the article form view
        // -------------------------------------------------
        return view('kbartickles::livewire.article-form')
            ->layout('layouts.app');
    }
}
