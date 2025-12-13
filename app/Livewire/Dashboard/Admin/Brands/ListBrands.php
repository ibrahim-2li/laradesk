<?php

namespace App\Livewire\Dashboard\Admin\Brands;

use App\Models\Brand;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class ListBrands extends Component
{
    use WithPagination;

    public $search = '';
    
    // Modal State
    public $isModalOpen = false;
    public $editingId = null;
    
    // Form Fields
    public $name = '';

    protected function rules() 
    {
        return [
            'name' => 'required|min:2|unique:brands,name,' . $this->editingId,
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
        $query = Brand::query();

        if ($this->search) {
            $query->where('name', 'like', '%' . $this->search . '%');
        }

        $brands = $query->orderBy('name', 'asc')->paginate(10);

        return view('livewire.dashboard.admin.brands.list-brands', [
            'brands' => $brands
        ])->layout('layouts.dashboard');
    }

    public function create()
    {
        $this->resetInputFields();
        $this->isModalOpen = true;
    }

    public function edit($id)
    {
        $brand = Brand::findOrFail($id);
        
        $this->editingId = $id;
        $this->name = $brand->name;
        
        $this->isModalOpen = true;
    }

    public function store()
    {
        $this->validate();

        $data = [
            'name' => $this->name,
        ];

        Brand::updateOrCreate(['id' => $this->editingId], $data);

        session()->flash('success', $this->editingId ? 'Brand updated successfully.' : 'Brand created successfully.');
        
        $this->closeModal();
        $this->resetInputFields();
    }

    public function delete($id)
    {
        $brand = Brand::findOrFail($id);
        $brand->delete();
        session()->flash('success', 'Brand deleted successfully.');
    }

    public function closeModal()
    {
        $this->isModalOpen = false;
        $this->resetInputFields();
    }

    private function resetInputFields()
    {
        $this->name = '';
        $this->editingId = null;
    }
}
