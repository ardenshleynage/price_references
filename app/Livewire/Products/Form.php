<?php

namespace App\Livewire\Products;

use App\Models\Branches;
use App\Models\Categories;
use App\Models\Products;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class Form extends Component
{
    public string $mode = 'create';

    public ?int $productId = null;

    public string $product_name = '';

    public string $single_price = '';

    public ?string $detailed_price = null;

    public ?string $post_scriptum = null;

    public ?string $branch_id = null;

    public ?string $category_id = null;

    public bool $showDetailedPrice = false;

    public int $userRole;
    /**
     * @return array<string,string>
     */
    protected function rules(): array
    {
        $unique = 'unique:products,product_name';

        if ($this->mode === 'edit' && $this->productId) {
            $unique .= ',' . $this->productId;
        }

        return [
            'product_name' => 'required|string|max:255|min:2|' . $unique,
            'single_price' => 'required|numeric|min:0',
            'detailed_price' => 'nullable|string|max:255',
            'post_scriptum' => 'nullable|string|max:1000',
            'branch_id' => 'required|exists:branches,id',
            'category_id' => 'required|exists:categories,id',
        ];
    }

    protected $messages = [
        'product_name.required' => 'Le nom du produit est obligatoire.',
        'product_name.string' => 'Le nom du produit doit être une chaîne de caractères.',
        'product_name.max' => 'Le nom du produit doit contenir au maximum 255 caractères.',
        'product_name.min' => 'Le nom du produit doit contenir au moins 2 caractères.',
        'product_name.unique' => 'Ce produit existe déjà.',
        'single_price.required' => 'Le prix unitaire est obligatoire.',
        'single_price.numeric' => 'Le prix unitaire doit être un nombre.',
        'single_price.min' => 'Le prix unitaire doit être positif.',
        'detailed_price.string' => 'Le prix détaillé doit être une chaîne de caractères.',
        'detailed_price.max' => 'Le prix détaillé doit contenir au maximum 255 caractères.',
        'post_scriptum.string' => 'Les informations complémentaires doivent être une chaîne de caractères.',
        'post_scriptum.max' => 'Les informations complémentaires doivent contenir au maximum 1000 caractères.',
        'branch_id.required' => 'La branche est obligatoire.',
        'branch_id.exists' => 'La branche sélectionnée n\'existe pas.',
        'category_id.required' => 'La catégorie est obligatoire.',
        'category_id.exists' => 'La catégorie sélectionnée n\'existe pas.',
    ];

    public function mount(?int $productId = null, int $userRole = 3): void
    {
        $this->userRole = $userRole;

        if ($productId) {
            $this->mode = 'edit';
            $this->productId = $productId;
            $product = Products::findOrFail($productId);
            $this->product_name = $product->product_name;
            $this->single_price = (string) $product->single_price;
            $this->detailed_price = $product->detailed_price;
            $this->post_scriptum = $product->post_scriptum;
            $this->branch_id = (string) $product->branch_id;
            $this->category_id = (string) $product->category_id;
            $this->showDetailedPrice = ! is_null($product->detailed_price);
        }
    }

    public function toggleDetailedPrice(): void
    {
        $this->showDetailedPrice = ! $this->showDetailedPrice;

        if (! $this->showDetailedPrice) {
            $this->detailed_price = null;
        }
    }

    public function save(): void
    {
        $this->validate();

        $data = [
            'product_name' => $this->product_name,
            'single_price' => $this->single_price,
            'detailed_price' => $this->detailed_price,
            'post_scriptum' => $this->post_scriptum,
            'branch_id' => $this->branch_id,
            'category_id' => $this->category_id,
        ];

        if ($this->mode === 'create') {
            $data['status'] = 1;
            Products::create($data);
            $this->dispatch('product-saved', message: 'Produit créé avec succès !');
        } else {
            Products::findOrFail($this->productId)->update($data);
            $this->dispatch('product-saved', message: 'Produit modifié avec succès !');
        }
    }

    public function render(): View
    {
        if ($this->userRole === 1) {
            $branches = Branches::orderBy('branche_name')->get();
            $categories = Categories::orderBy('category_name')->get();
        } elseif ($this->userRole === 2) {
            $branches = Branches::whereIn('status', [1, 2, 3])->orderBy('branche_name')->get();
            $categories = Categories::whereIn('status', [1, 2, 3])->orderBy('category_name')->get();
        } else {
            $branches = Branches::where('status', 1)->orderBy('branche_name')->get();
            $categories = Categories::where('status', 1)->orderBy('category_name')->get();
        }

        return view('livewire.products.form', [
            'branches' => $branches,
            'categories' => $categories,
        ]);
    }
}
