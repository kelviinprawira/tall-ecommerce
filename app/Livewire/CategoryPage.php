<?php

namespace App\Livewire;

use App\Models\Category;
use Livewire\Attributes\Title;
use Livewire\Component;

class CategoryPage extends Component
{
    #[Title('Category - GEGE STORE')]
    public function render()
    {
        $categories = Category::where('is_active', 1)->get();
        return view('livewire.category-page', compact('categories'));
    }
}
