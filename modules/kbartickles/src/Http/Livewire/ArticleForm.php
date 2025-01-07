<?php

namespace tronderdata\kbartickles\Http\Livewire;

use Livewire\Component;
use tronderdata\kbartickles\Models\Article;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

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
        // If articleId is not provided, try to get it from the request query parameters
        // -------------------------------------------------
        if (!$articleId) {
            $articleId = request()->query('articleId');
        }

        // -------------------------------------------------
        // If articleId is provided, then we are in edit mode
        // -------------------------------------------------
        if ($articleId) {
            $this->isEditMode = true;
            $article = Article::find($articleId);

            if ($article) {
                $this->articleId = $article->id;
                $this->title = $article->title;
                $this->slug = $article->slug;
                $this->content = $article->content;
                $this->category_id = $article->category_id;
                $this->status = $article->status;
                
            } else {
                // Hvis artikkelen ikke finnes, omdiriger tilbake til listen
                return redirect()->route('kb.index')->with('error', 'Article not found.');
            }
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
        $rules = [
            'title' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:articles,slug,' . $this->articleId,
            'content' => 'required|string',
            'status' => 'required|string|in:draft,published,archived',
        ];
    
        if (class_exists('\tronderdata\categories\Models\Category')) {
            $rules['category_id'] = 'nullable|exists:categories,id';
        }
    
        $data = $this->validate($rules);
    
        // -------------------------------------------------
        // Add the user_id to the data
        // -------------------------------------------------
        $data['user_id'] = auth()->id();
    
        // -------------------------------------------------
        // If the article exists, update it
        // -------------------------------------------------
        if ($this->isEditMode) {
            $article = Article::find($this->articleId);
            if ($article) {
                $article->update($data);
            }
        } else {
            // -------------------------------------------------
            // Else create a new article
            // -------------------------------------------------
            $article = Article::create($data);
            $this->articleId = $article->id;
        }
    
        // -------------------------------------------------
        // Redirect to the article list page with success message
        // -------------------------------------------------
        return redirect()->route('kb.show', $this->articleId)->with('success', 'Article saved successfully!');
    }
    
    

    // ---------------------------------------------------------------------------------------------------------------------------------------------------
    // FUNCTION RENDER
    // Render function is used to render the component view. In this case, we are rendering the article form view.
    //
    // ---------------------------------------------------------------------------------------------------------------------------------------------------
    public function render()
    {

        // -------------------------------------------------
        // check if the user has access to view the articles
        // -------------------------------------------------
        if (!auth()->user()->can('kb.edit') && !auth()->user()->can('kb.create') && !auth()->user()->can('kb.admin')) {

            // -------------------------------------------------
            // Return 403 response if the user does not have access
            // -------------------------------------------------
            abort(403);
        }

        // -------------------------------------------------
        // Return the article form view
        // -------------------------------------------------
        return view('kbartickles::livewire.article-form')
            ->layout('layouts.app');
    }
}
