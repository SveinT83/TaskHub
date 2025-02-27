@extends('layouts.app')

@section('pageHeader')
    <h1>Create Wall</h1>
@endsection

@section('content')
    <form class="card" action="{{ route('walls.store') }}" method="POST" id="createWallForm">
        @csrf

        <div class="card-body">
            <!-- ------------------------------------------------- -->
            <!-- Select a template if exists -->
            <!-- ------------------------------------------------- -->
            <div class="mb-3">
                <label for="template_id" class="form-label">Select a Template (Optional)</label>
                <select name="template_id" id="template_id" class="form-select">
                    <option value="">-- Select a Template (optional) --</option>
                    @foreach($templates as $template)
                        <option value="{{ $template->id }}">{{ $template->name }}</option>
                    @endforeach
                </select>
            </div>

            <!-- ------------------------------------------------- -->
            <!-- Wall Name -->
            <!-- ------------------------------------------------- -->
            <div class="mb-3">
                <label for="name" class="form-label">Wall Name</label>
                <input type="text" name="name" class="form-control" placeholder="Enter wall name" required>
            </div>

            <!-- ------------------------------------------------- -->
            <!-- Description -->
            <!-- ------------------------------------------------- -->
            <div class="mb-3">
                <label for="description" class="form-label">Description</label>
                <textarea name="description" class="form-control" placeholder="Enter description (optional)"></textarea>
            </div>

            <!-- ------------------------------------------------- -->
            <!-- Show a checkbox to create as template if the user has privileges -->
            <!-- ------------------------------------------------- -->
            <div class="mb-3 form-check">
                <input type="checkbox" name="template" class="form-check-input" id="template" @if($canCreateTemplate) @else disabled @endif>
                <label for="template" class="form-check-label">Create as Template</label>
            </div>

            <button type="submit" class="btn btn-primary">Create Wall</button>
        </div>
    </form>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const templateSelect = document.getElementById('template_id');
            const nameInput = document.querySelector('input[name="name"]');
            const descriptionInput = document.querySelector('textarea[name="description"]');
            const templateCheckbox = document.getElementById('template');

            // When a template is selected, update the wall name and description fields
            templateSelect.addEventListener('change', function() {
                const selectedTemplateId = this.value;

                if (selectedTemplateId) {
                    // Fetch the selected template's data (name and description)
                    fetch(`/api/templates/${selectedTemplateId}`)
                        .then(response => response.json())
                        .then(data => {
                            // Populate the wall name and description from the selected template
                            nameInput.value = data.name;
                            descriptionInput.value = data.description;

                            // Disable the "Create as Template" checkbox
                            templateCheckbox.disabled = true;
                        });
                } else {
                    // Clear the name and description fields and enable the template checkbox
                    nameInput.value = '';
                    descriptionInput.value = '';
                    templateCheckbox.disabled = false;
                }
            });
        });
    </script>
@endsection
