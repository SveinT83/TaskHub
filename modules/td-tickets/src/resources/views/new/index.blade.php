@extends('layouts.app')

<!-- ------------------------------------------------- -->
<!-- Header -->
<!-- ------------------------------------------------- -->
@section('pageHeader')
    <h1>Create new ticket</h1>
@endsection

@section('content')
<div class="container mt-3">

    <!-- ------------------------------------------------- -->
    <!-- Alert -->
    <!-- ------------------------------------------------- -->
    @if ($errors->any())
        <div class="alert alert-danger">
            <strong>Oops!</strong> There were some problems with your submission..<br><br>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- ------------------------------------------------- -->
    <!-- Card - START -->
    <!-- ------------------------------------------------- -->
    <div class="card">
        <div class="card-body">

            <!-- ------------------------------------------------- -->
            <!-- Form: create ticket -->
            <!-- ------------------------------------------------- -->
            <form action="{{ route('tickets.store') }}" method="POST">
                @csrf

                <!-- ------------------------------------------------- -->
                <!-- Row - Select client and user -->
                <!-- ------------------------------------------------- -->
                <div class="row">

                    <!-- ------------------------------------------------- -->
                    <!-- Klient Select -->
                    <!-- ------------------------------------------------- -->
                    <div class="col-md-6 mt-2">
                        <label for="client_id" class="form-label fw-bold">Client</label>
                        <select class="form-select" id="client_id" name="client_id" required>
                            <option value="">Select Client</option>
                            @foreach($clients as $client)
                                <option value="{{ $client->id }}" {{ old('client_id') == $client->id ? 'selected' : '' }}>
                                    {{ $client->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- ------------------------------------------------- -->
                    <!-- Bruker Select -->
                    <!-- ------------------------------------------------- -->
                    <div class="col-md-6 mt-2">
                        <label for="user_id" class="form-label fw-bold">User:</label>
                        <select class="form-select" id="user_id" name="user_id" required>
                            <option value="">Select User</option>
                            <!-- Brukere vil bli lastet inn dynamisk -->
                        </select>
                    </div>

                </div>

                <!-- ------------------------------------------------- -->
                <!-- Ticket Title/Subject -->
                <!-- ------------------------------------------------- -->
                <div class="row mt-3">
                    <div class="col-md-12">
                        <label for="title" class="form-label fw-bold">Title</label>
                        <input type="text" class="form-control" id="title" name="title" value="{{ old('title') }}" required>
                    </div>
                </div>

                <!-- Beskrivelse Textarea -->
                <div class="row mt-3">
                    <div class="col-md-12">
                        <label for="description" class="form-label fw-bold">Description</label>
                        <textarea class="form-control" id="description" name="description" rows="5" required>{{ old('description') }}</textarea>
                    </div>
                </div>

                <!-- ------------------------------------------------- -->
                <!-- Row - Category, Que and priority -->
                <!-- ------------------------------------------------- -->
                <div class="row mt-3">

                    <!-- ------------------------------------------------- -->
                    <!-- Ticket Category Select -->
                    <!-- ------------------------------------------------- -->
                    <div class="col-md-3 mt-2">
                        <label for="ticket_category_id" class="form-label fw-bold">Ticket Category</label>
                        <select class="form-select" id="ticket_category_id" name="ticket_category_id" required>
                            <option value="general">General</option>
                            @foreach($ticketCategories as $category)
                                <option value="{{ $category->id }}" {{ old('ticket_category_id') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- ------------------------------------------------- -->
                    <!-- Que Select -->
                    <!-- ------------------------------------------------- -->
                    <div class="col-md-3 mt-2">
                        <label for="queue_id" class="form-label fw-bold">Que</label>
                        <select class="form-select" id="queue_id" name="queue_id" required>
                            <option value="">Select que</option>
                            @foreach($queues as $queue)
                                <option value="{{ $queue->id }}" {{ old('queue_id') == $queue->id ? 'selected' : '' }}>
                                    {{ $queue->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- ------------------------------------------------- -->
                    <!-- Priority Select -->
                    <!-- ------------------------------------------------- -->
                    <div class="col-md-3 mt-2">
                        <label for="priority_id" class="form-label fw-bold">Priority:</label>
                        <select class="form-select" id="priority_id" name="priority_id" required>
                            @foreach($ticketPriorities as $priority)
                                <option value="{{ $priority->id }}" {{ old('priority_id') == $priority->id ? 'selected' : '' }}>
                                    {{ $priority->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- ------------------------------------------------- -->
                    <!-- Due Input -->
                    <!-- ------------------------------------------------- -->
                    <div class="col-md-3 mt-2">
                        <label for="due_date" class="form-label fw-bold">Due date</label>
                        <input type="date" class="form-control" id="due_date" name="due_date" value="{{ old('due_date') }}">
                    </div>

                </div>

                <!-- ------------------------------------------------- -->
                <!-- Submit Button -->
                <!-- ------------------------------------------------- -->
                <div class="row mt-3">
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary">Create Ticket</button>
                    </div>
                </div>
            </form>
        
        <!-- ------------------------------------------------- -->
        <!-- Card - END -->
        <!-- ------------------------------------------------- -->
        </div>
    </div>

<!-- ------------------------------------------------- -->
<!-- Container - END -->
<!-- ------------------------------------------------- -->
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const clientSelect = document.getElementById('client_id');
        const userSelect = document.getElementById('user_id');

        clientSelect.addEventListener('change', function () {
            const client_id = this.value;

            // Tøm brukerselect hvis ingen klient er valgt
            if (!client_id) {
                userSelect.innerHTML = '<option value="">Velg Bruker</option>';
                return;
            }

            // Hent brukere via AJAX fra interne ruter
            fetch(`/tickets/clients/${client_id}/users`)

                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    let options = '<option value="">Velg Bruker</option>';
                    data.forEach(user => {
                        options += `<option value="${user.id}">${user.first_name} ${user.last_name} (${user.email})</option>`;
                    });
                    userSelect.innerHTML = options;
                })
                .catch(error => {
                    console.error('Error fetching users:', error);
                    userSelect.innerHTML = '<option value="">Ingen brukere funnet</option>';
                });
        });

        // Trigger change event hvis en klient er valgt ved innlasting
        @if(old('client_id'))
            clientSelect.dispatchEvent(new Event('change'));
        @endif
    });
</script>
@endsection
