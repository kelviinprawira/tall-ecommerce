<?php

namespace App\Livewire;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

class ProductsPage extends Component
{
    use WithPagination;

    #[Title('Products - GEGE STORE')]
    public function render()
    {
        $products = Product::where('is_active', 1)->paginate(6);
        $brands = Brand::where('is_active', 1)->get(['id', 'name', 'slug']);
        $categories = Category::where('is_active', 1)->get(['id', 'name', 'slug']);
        return view('livewire.products-page', compact('products', 'brands', 'categories'));
    }
}
