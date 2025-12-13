<?php

namespace App\Livewire\Dashboard\Admin\Stock;

use App\Models\Stock;
use App\Models\Brand;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class ListStocks extends Component
{
    use WithPagination;

    public $search = '';
    
    // Modal State
    public $isModalOpen = false;
    public $editingId = null;
    
    // Form Fields
    public $name = '';
    public $quantity = 0;
    public $brand_id = '';
    
    // Data
    public $brands = [];

    protected function rules() 
    {
        return [
            'name' => 'required|min:2',
            'quantity' => 'required|integer|min:0',
            'brand_id' => 'nullable|exists:brands,id',
        ];
    }

    public function mount()
    {
        if (Auth::user()->role_id !== 1) {
            abort(403, __('Unauthorized'));
        }
    }

    public function render()
    {
        $query = Stock::query()->with('brands');

        if ($this->search) {
             $query->where(function ($q) {
                $q->where('name', 'like', '%' . $this->search . '%');
            });
        }

        $stocks = $query->orderBy('name', 'asc')->paginate(10);
        
        return view('livewire.dashboard.admin.stock.list-stocks', [
            'stocks' => $stocks
        ])->layout('layouts.dashboard');
    }

    public function create()
    {
        $this->resetInputFields();
        $this->brands = Brand::all();
        $this->isModalOpen = true;
    }

    public function edit($id)
    {
        $stock = Stock::findOrFail($id);
        
        $this->editingId = $id;
        $this->name = $stock->name;
        $this->quantity = $stock->quantity;
        $this->brand_id = $stock->brand_id;
        
        $this->brands = Brand::all();
        $this->isModalOpen = true;
    }

    public function store()
    {
        $this->validate();

        $data = [
            'name' => $this->name,
            'quantity' => $this->quantity,
            'brand_id' => $this->brand_id ?: null,
        ];

        Stock::updateOrCreate(['id' => $this->editingId], $data);

        session()->flash('success', $this->editingId ? 'Stock updated successfully.' : 'Stock created successfully.');
        
        $this->closeModal();
        $this->resetInputFields();
    }

    public function delete($id)
    {
        $stock = Stock::findOrFail($id);
        $stock->delete();
        session()->flash('success', 'Stock deleted successfully.');
    }

    public function closeModal()
    {
        $this->isModalOpen = false;
        $this->resetInputFields();
    }

    private function resetInputFields()
    {
        $this->name = '';
        $this->quantity = 0;
        $this->brand_id = '';
        $this->editingId = null;
    }
}
