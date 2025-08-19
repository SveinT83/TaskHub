<?php

namespace App\Livewire\Admin\Configurations\Meta;

use Livewire\Component;
use App\Models\Configurations\Meta\MetaData;
use App\Models\Configurations\Meta\MetaField;

class Index extends Component
{
    public $model;
    public $id;
    public $fields;
    public $values = [];

    public function mount($model, $id)
    {
        $this->model = $model;
        $this->id = $id;
        $this->fields = MetaField::where('module', $this->getModule())->get();
        $this->values = MetaData::where('parent_type', $model)->where('parent_id', $id)->pluck('value', 'key')->toArray();
    }

    public function save()
    {
        foreach ($this->fields as $field) {
            $value = $this->values[$field->key] ?? $field->default_value;
            \App\Services\MetaDataService::save($this->getModelInstance(), $field->key, $value);
        }
        session()->flash('success', 'Metadata updated!');
    }

    public function getModelInstance()
    {
        return (new $this->model)::findOrFail($this->id);
    }

    public function getModule()
    {
        return $this->getModelInstance()->module ?? 'core';
    }

    public function render()
    {
        return view('livewire.admin.configurations.meta.table', [
            'fields' => $this->fields,
            'values' => $this->values,
        ])->layout('layouts.app');
    }
}
