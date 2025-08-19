<?php

namespace App\Livewire\Admin\Configurations\Meta;

use Livewire\Component;
use App\Models\Configurations\Meta\MetaField;

class FieldManager extends Component
{
    public $fields;
    public $showForm = false;
    public $editingField = false;
    public $form = [
        'key' => '',
        'label' => '',
        'description' => '',
        'type' => 'string',
        'rules' => '',
        'default_value' => null,
        'options' => null,
        'module' => '',
    ];

    public function mount()
    {
        $this->fields = MetaField::all();
    }

    public function create()
    {
        $this->resetForm();
        $this->editingField = false;
        $this->showForm = true;
    }

    public function edit($key)
    {
        $field = MetaField::where('key', $key)->first();
        if ($field) {
            $this->form = [
                'key' => $field->key,
                'label' => $field->label,
                'description' => $field->description,
                'type' => $field->type,
                'rules' => $field->rules,
                'default_value' => $field->default_value,
                'options' => $field->options,
                'module' => $field->module,
            ];
            $this->editingField = true;
            $this->showForm = true;
        }
    }

    public function save()
    {
        $this->validate([
            'form.key' => 'required|string|max:255',
            'form.label' => 'required|string|max:255',
            'form.type' => 'required|in:string,int,boolean,json,select',
            'form.module' => 'required|string|max:255',
        ]);

        $data = $this->form;
        
        MetaField::updateOrCreate(['key' => $data['key']], $data);
        
        $this->fields = MetaField::all();
        $this->showForm = false;
        $this->resetForm();
        
        session()->flash('success', 'Field saved successfully!');
    }

    public function delete($key)
    {
        MetaField::where('key', $key)->delete();
        $this->fields = MetaField::all();
        session()->flash('success', 'Field deleted successfully!');
    }

    public function resetForm()
    {
        $this->form = [
            'key' => '',
            'label' => '',
            'description' => '',
            'type' => 'string',
            'rules' => '',
            'default_value' => null,
            'options' => null,
            'module' => '',
        ];
    }

    public function render()
    {
        return view('livewire.admin.configurations.meta.field-manager', [
            'fields' => $this->fields,
            'form' => $this->form,
        ]);
    }
}
