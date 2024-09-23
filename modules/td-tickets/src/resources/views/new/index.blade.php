@extends('layouts.app')

<!-- ------------------------------------------------- -->
<!-- Header -->
<!-- ------------------------------------------------- -->
@section('pageHeader')
    <h1>Opprett Ny Ticket</h1>
@endsection

@section('content')
<div class="container mt-3">

    <!-- ------------------------------------------------- -->
    <!-- Alert -->
    <!-- ------------------------------------------------- -->
    @if ($errors->any())
        <div class="alert alert-danger">
            <strong>Oops!</strong> Det var noen problemer med innsendelsen din.<br><br>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- ------------------------------------------------- -->
    <!-- Form: create ticket -->
    <!-- ------------------------------------------------- -->
    <form action="{{ route('tickets.store') }}" method="POST">
        @csrf

        <!-- Klient Select -->
        <div class="mb-3">
            <label for="client_id" class="form-label">Klient</label>
            <select class="form-select" id="client_id" name="client_id" required>
                <option value="">Velg Klient</option>
                @foreach($clients as $client)
                    <option value="{{ $client->id }}" {{ old('client_id') == $client->id ? 'selected' : '' }}>
                        {{ $client->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <!-- Bruker Select -->
        <div class="mb-3">
            <label for="user_id" class="form-label">Bruker</label>
            <select class="form-select" id="user_id" name="user_id" required>
                <option value="">Velg Bruker</option>
                <!-- Brukere vil bli lastet inn dynamisk -->
            </select>
        </div>

        <!-- Ticket Category Select -->
        <div class="mb-3">
            <label for="ticket_category_id" class="form-label">Ticket Kategori</label>
            <select class="form-select" id="ticket_category_id" name="ticket_category_id" required>
                <option value="">Velg Kategori</option>
                @foreach($ticketCategories as $category)
                    <option value="{{ $category->id }}" {{ old('ticket_category_id') == $category->id ? 'selected' : '' }}>
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <!-- Kø Select -->
        <div class="mb-3">
            <label for="queue_id" class="form-label">Kø</label>
            <select class="form-select" id="queue_id" name="queue_id" required>
                <option value="">Velg Kø</option>
                @foreach($queues as $queue)
                    <option value="{{ $queue->id }}" {{ old('queue_id') == $queue->id ? 'selected' : '' }}>
                        {{ $queue->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <!-- Tittel Input -->
        <div class="mb-3">
            <label for="title" class="form-label">Tittel</label>
            <input type="text" class="form-control" id="title" name="title" value="{{ old('title') }}" required>
        </div>

        <!-- Beskrivelse Textarea -->
        <div class="mb-3">
            <label for="description" class="form-label">Beskrivelse</label>
            <textarea class="form-control" id="description" name="description" rows="5" required>{{ old('description') }}</textarea>
        </div>

        <!-- Prioritet Select -->
        <div class="mb-3">
            <label for="priority" class="form-label">Prioritet</label>
            <select class="form-select" id="priority" name="priority" required>
                <option value="">Velg Prioritet</option>
                <option value="low" {{ old('priority') == 'low' ? 'selected' : '' }}>Lav</option>
                <option value="normal" {{ old('priority') == 'normal' ? 'selected' : '' }}>Normal</option>
                <option value="high" {{ old('priority') == 'high' ? 'selected' : '' }}>Høy</option>
            </select>
        </div>

        <!-- Frist Input -->
        <div class="mb-3">
            <label for="due_date" class="form-label">Frist</label>
            <input type="date" class="form-control" id="due_date" name="due_date" value="{{ old('due_date') }}">
        </div>

        <!-- Submit Button -->
        <button type="submit" class="btn btn-primary">Opprett Ticket</button>
    </form>
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
