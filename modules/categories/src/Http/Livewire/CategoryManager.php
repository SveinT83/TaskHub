<?php

namespace tronderdata\categories\Http\Livewire;

use Livewire\Component;
use tronderdata\categories\Models\Category;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class CategoryManager extends Component
{
    public $newCategory;
    public $showForm = false;
    public $description;
    public $parent_id;
    public $categories;
    public $isEditMode = false;
    public $categoryId;
    public $slug;
    public $status;

    // ---------------------------------------------------------------------------------------------------------------------------------------------------
    // FUNCTION RENDER
    // Renders the Livewire component
    //
    // ---------------------------------------------------------------------------------------------------------------------------------------------------
    public function render()
    {
        // -------------------------------------------------
        // Get all categories with their children sorted by order and id
        // -------------------------------------------------
        $this->categories = Category::whereNull('parent_id')->with('children')->orderBy('order')->orderBy('id')->get();

        //dd($this->categories);

        // -------------------------------------------------
        // Return view
        // -------------------------------------------------
        return view('categories::livewire.categoryManager', ['categories' => $this->categories]);
    }

    // ---------------------------------------------------------------------------------------------------------------------------------------------------
    // FUNCTION ADD CATEGORY
    // Adds a new category to the database
    //
    // ---------------------------------------------------------------------------------------------------------------------------------------------------
    public function addCategory()
    {
        // Valider input
        $this->validate([
            'newCategory' => 'required|string|max:255',
            'description' => 'nullable|string',
            'parent_id' => 'nullable|exists:categories,id',
        ]);

        // Feilsøk: Sjekk verdien av newCategory før generering av slug
        if (!$this->newCategory) {
            session()->flash('error', 'Category name is required.');
            return;
        }

        // Generer slug automatisk
        $slug = Str::slug($this->newCategory);
        if (empty($slug)) {
            session()->flash('error', 'Failed to generate slug.');
            return;
        }

        // Opprett ny kategori
        $category = Category::create([
            'name' => $this->newCategory,
            'description' => $this->description,
            'parent_id' => $this->parent_id,  // Dette feltet kan være null
            'slug' => $slug,
            'created_by' => Auth::id(),
        ]);

        if (!$category) {
            session()->flash('error', 'Failed to create category.');
            return;
        }

        // Tilbakestill feltene
        $this->newCategory = '';
        $this->description = '';
        $this->parent_id = null;

        // Oppdater kategorilisten
        $this->categories = Category::all();

        // Lukk skjemaet og send suksessmelding
        $this->showForm = false;
        session()->flash('success', 'Category added successfully!');
    }

    // Funksjon for å slette en kategori
    public function deleteCategory($categoryId)
    {
        $category = Category::find($categoryId);

        if ($category) {
            $category->delete();
        }

        // Oppdater kategorilisten etter sletting
        $this->categories = Category::all();
    }

    // Metode for å legge til eller oppdatere kategori
    public function saveCategory()
    {
        $this->validate([
            'newCategory' => 'required|string|max:255',
            'description' => 'nullable|string',
            'parent_id' => 'nullable|exists:categories,id',
            'slug' => 'required_if:isEditMode,true|string|max:255|unique:categories,slug,' . $this->categoryId,
            'status' => 'required_if:isEditMode,true|in:active,inactive',
        ]);

        if ($this->isEditMode) {
            // Oppdater eksisterende kategori
            $category = Category::find($this->categoryId);
            $category->update([
                'name' => $this->newCategory,
                'description' => $this->description,
                'parent_id' => $this->parent_id,
                'slug' => $this->slug,
                'status' => $this->status,
                'updated_by' => Auth::id(),
            ]);
        } else {

            // Generer slug automatisk
            $slug = Str::slug($this->newCategory);
            if (empty($slug)) {
                session()->flash('error', 'Failed to generate slug.');
                return;
            }

            // Opprett ny kategori
            Category::create([
                'name' => $this->newCategory,
                'description' => $this->description,
                'parent_id' => $this->parent_id,
                'slug' => Str::slug($this->newCategory),
                'status' => 'active',
                'created_by' => Auth::id(),
                'updated_by' => Auth::id(),
            ]);
        }

        // Tilbakestill skjemaet
        $this->resetForm();

        // Lukk skjemaet
        $this->showForm = false;

        // Send suksessmelding
        session()->flash('success', $this->isEditMode ? 'Category updated successfully!' : 'Category added successfully!');
    }

    // Metode for å tilbakestille skjemaet
    public function resetForm()
    {
        $this->newCategory = '';
        $this->description = '';
        $this->parent_id = null;
        $this->isEditMode = false;
        $this->categoryId = null;
    }

    // Metode for å sette skjemaet i redigeringsmodus
    public function editCategory($id)
    {
        $category = Category::find($id);
        $this->newCategory = $category->name;
        $this->description = $category->description;
        $this->parent_id = $category->parent_id;
        $this->slug = $category->slug;
        $this->status = $category->status;
        $this->isEditMode = true;
        $this->categoryId = $id;
        $this->showForm = true;
    }
}
