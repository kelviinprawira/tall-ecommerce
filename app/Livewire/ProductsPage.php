<?php

namespace App\Livewire;

use App\Helpers\CartManagement;
use App\Livewire\Partials\Navbar;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

#[Title('Products - GEGE STORE')]
class ProductsPage extends Component
{
    use WithPagination;
    use LivewireAlert;

    #[Url]
    public $selectedCategory = [];
    #[Url]
    public $selectedBrand = [];

    #[Url]
    public $featured;
    #[Url]
    public $on_sale;
    #[Url]
    public $price_range = 100000;

    #[Url]
    public $sort = 'latest';

    public function addToCart($product_id)
    {
        $total_count = CartManagement::addItemToCart($product_id);
        $this->dispatch('update-cart-count', total_count: $total_count)->to(Navbar::class);
        $this->alert('success', 'Product added to cart succesfully', [
            'position' => 'top-end',
            'timer' => 3000,
            'toast' => true,
        ]);
    }

    public function render()
    {
        $productsQuery = Product::where('is_active', 1);
        if (!empty($this->selectedCategory)) {
            $productsQuery = Product::whereIn('category_id', $this->selectedCategory);
        }
        if (!empty($this->selectedBrand)) {
            $productsQuery = Product::whereIn('brand_id', $this->selectedBrand);
        }

        if ($this->featured) {
            $productsQuery->where('is_featured', 1);
        }
        if ($this->on_sale) {
            $productsQuery->where('on_sale', 1);
        }
        if ($this->price_range) {
            $productsQuery->whereBetween('price', [0, $this->price_range]);
        }
        if ($this->sort == 'latest') {
            $productsQuery->latest();
        }
        if ($this->sort == 'price') {
            $productsQuery->orderBy('price', 'asc');
        }
        $brands = Brand::where('is_active', 1)->get(['id', 'name', 'slug']);
        $categories = Category::where('is_active', 1)->get(['id', 'name', 'slug']);
        return view('livewire.products-page', [
            'products' => $productsQuery->paginate(6),
            'categories' => $categories,
            'brands' => $brands
        ]);
    }
}
