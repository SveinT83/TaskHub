<?php

namespace tronderdata\TdDocumentation\Http\Livewire;

use Livewire\Component;

class DocumentationView extends Component
{
    public $docId;

    public function mount($id)
    {
        $this->docId = $id;
    }

    public function render()
    {
        return view('td-documentation::documentation-view', ['docId' => $this->docId]);
    }
}
