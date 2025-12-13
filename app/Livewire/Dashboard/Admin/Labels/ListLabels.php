<?php

namespace App\Livewire\Dashboard\Admin\Labels;

use App\Models\Label;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class ListLabels extends Component
{
    use WithPagination;

    public $search = '';
    
    // Modal State
    public $isModalOpen = false;
    public $editingId = null;
    
    // Form Fields
    public $name = '';
    public $color = '#000000'; // Default black

    protected function rules() 
    {
        return [
            'name' => 'required|min:2|unique:labels,name,' . $this->editingId,
            'color' => 'required|regex:/^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$/',
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
        $query = Label::query()->withCount(['tickets', 'orders']);

        if ($this->search) {
            $query->where('name', 'like', '%' . $this->search . '%');
        }

        $labels = $query->orderBy('name', 'asc')->paginate(10);

        return view('livewire.dashboard.admin.labels.list-labels', [
            'labels' => $labels
        ])->layout('layouts.dashboard');
    }

    public function create()
    {
        $this->resetInputFields();
        $this->isModalOpen = true;
    }

    public function edit($id)
    {
        $label = Label::findOrFail($id);
        
        $this->editingId = $id;
        $this->name = $label->name;
        $this->color = $label->color;
        
        $this->isModalOpen = true;
    }

    public function store()
    {
        $this->validate();

        $data = [
            'name' => $this->name,
            'color' => $this->color,
        ];

        Label::updateOrCreate(['id' => $this->editingId], $data);

        session()->flash('success', $this->editingId ? 'Label updated successfully.' : 'Label created successfully.');
        
        $this->closeModal();
        $this->resetInputFields();
    }

    public function delete($id)
    {
        $label = Label::findOrFail($id);
        $label->delete();
        session()->flash('success', 'Label deleted successfully.');
    }

    public function closeModal()
    {
        $this->isModalOpen = false;
        $this->resetInputFields();
    }

    private function resetInputFields()
    {
        $this->name = '';
        $this->color = '#000000';
        $this->editingId = null;
    }
}
