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

    // Kjøres når komponenten renderes
    public function render()
    {
        // Henter alle kategorier for å vise i Livewire-komponenten
        $this->categories = Category::all();

        return view('categories::livewire.categoryManager', ['categories' => $this->categories]);
    }

    // Funksjon for å legge til ny kategori
    public function addCategory()
    {
        // Validerer input
        $this->validate([
            'newCategory' => 'required|string|max:255',
            'description' => 'nullable|string',
            'parent_id' => 'nullable|exists:categories,id',
        ]);

        // Generer slug automatisk
        $slug = Str::slug($this->newCategory);

        // Oppretter ny kategori
        Category::create([
            'name' => $this->newCategory,
            'description' => $this->description,
            'parent_id' => $this->parent_id,
            'slug' => $slug, // Pass på at slug blir satt her
            'created_by' => Auth::id(),
        ]);

        // Tilbakestiller feltene
        $this->newCategory = '';
        $this->description = '';
        $this->parent_id = null;

        // Oppdaterer kategorilisten
        $this->categories = Category::all();
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
}
