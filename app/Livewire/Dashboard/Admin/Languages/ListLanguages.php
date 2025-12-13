<?php

namespace App\Livewire\Dashboard\Admin\Languages;

use App\Models\Language;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class ListLanguages extends Component
{
    use WithPagination;

    public $search = '';
    
    // Modal State
    public $isModalOpen = false;
    public $editingId = null;
    
    // Form Fields
    public $name = '';
    public $locale = '';

    protected function rules() 
    {
        return [
            'name' => 'required|min:2|unique:languages,name,' . $this->editingId,
            'locale' => 'required|min:2|max:5|unique:languages,locale,' . $this->editingId,
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
        $query = Language::query();

        if ($this->search) {
            $query->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('locale', 'like', '%' . $this->search . '%');
        }

        $languages = $query->orderBy('name', 'asc')->paginate(10);

        return view('livewire.dashboard.admin.languages.list-languages', [
            'languages' => $languages
        ])->layout('layouts.dashboard');
    }

    public function create()
    {
        $this->resetInputFields();
        $this->isModalOpen = true;
    }

    public function edit($id)
    {
        $language = Language::findOrFail($id);
        
        $this->editingId = $id;
        $this->name = $language->name;
        $this->locale = $language->locale;
        
        $this->isModalOpen = true;
    }

    public function store()
    {
        $this->validate();

        $data = [
            'name' => $this->name,
            'locale' => $this->locale,
        ];

        Language::updateOrCreate(['id' => $this->editingId], $data);

        session()->flash('success', $this->editingId ? 'Language updated successfully.' : 'Language created successfully.');
        
        $this->closeModal();
        $this->resetInputFields();
    }

    public function delete($id)
    {
        $language = Language::findOrFail($id);
        $language->delete();
        session()->flash('success', 'Language deleted successfully.');
    }

    public function closeModal()
    {
        $this->isModalOpen = false;
        $this->resetInputFields();
    }

    private function resetInputFields()
    {
        $this->name = '';
        $this->locale = '';
        $this->editingId = null;
    }
}
