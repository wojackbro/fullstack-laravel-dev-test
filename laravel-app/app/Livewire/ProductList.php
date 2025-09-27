<?php

namespace App\Livewire;

use App\Models\Product;
use Livewire\Component;
use Livewire\WithPagination;

class ProductList extends Component
{
    use WithPagination;

    public $sortBy = "created_at";
    public $sortDirection = "desc";
    public $search = "";
    public $perPage = 25;

    protected $queryString = [
        "sortBy" => ["except" => "created_at"],
        "sortDirection" => ["except" => "desc"],
        "search" => ["except" => ""],
    ];

    public function sortBy($field)
    {
        if ($this->sortBy === $field) {
            $this->sortDirection = $this->sortDirection === "asc" ? "desc" : "asc";
        } else {
            $this->sortBy = $field;
            $this->sortDirection = "asc";
        }
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $products = Product::with("images")
            ->when($this->search, function ($query) {
                $query->where("title", "like", "%" . $this->search . "%")
                    ->orWhere("description", "like", "%" . $this->search . "%")
                    ->orWhere("category", "like", "%" . $this->search . "%");
            })
            ->orderBy($this->sortBy, $this->sortDirection)
            ->paginate($this->perPage);

        return view("livewire.product-list", [
            "products" => $products,
        ])->layout("layouts.app", [
            "title" => "Products - Laravel Product App"
        ]);
    }
}
