@extends('layouts.app')

@section('pageHeader')
    <h1>Tickets</h1>
@endsection

@section('content')
<div class="container-fluid mt-3">

    <!-- ------------------------------------------------- -->
    <!-- Filtreringsskjema -->
    <!-- ------------------------------------------------- -->
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <form class="row justify-content-center" method="GET" action="{{ route('tickets.index') }}">
                        <!-- Søkefelt -->
                        <input type="text" class="col-md-2 m-1" name="search" placeholder="Søk..." value="{{ request('search') }}">

                        <!-- Filtrering etter status -->
                        <select name="status" class="col-md-2 m-1">
                            <option value="">Alle statuser</option>
                            @foreach($statuses as $status)
                                <option value="{{ $status->name }}" {{ request('status') == $status->name ? 'selected' : '' }}>{{ $status->name }}</option>
                            @endforeach
                        </select>

                        <!-- Sortering -->
                        <select name="sort_by" class="col-md-2 m-1">
                            <option value="created_at" {{ request('sort_by') == 'created_at' ? 'selected' : '' }}>Opprettelsesdato</option>
                            <option value="due_date" {{ request('sort_by') == 'due_date' ? 'selected' : '' }}>Forfallsdato</option>
                            <!-- Legg til flere sorteringsalternativer -->
                        </select>

                        <select name="sort_order" class="col-md-2 m-1">
                            <option value="asc" {{ request('sort_order') == 'asc' ? 'selected' : '' }}>Stigende</option>
                            <option value="desc" {{ request('sort_order') == 'desc' ? 'selected' : '' }}>Synkende</option>
                        </select>

                        <div class="col-md-2 m-1">
                            <button class="btn btn-primary" type="submit">Filtrer</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- ------------------------------------------------- -->
    <!-- Ticket-listen -->
    <!-- ------------------------------------------------- -->
    <div class="card mt-3">
        <div class="card-body">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th scope="col">Tittel</th>
                        <th scope="col">Status</th>
                        <th scope="col">Kunde</th>
                        <th scope="col">Tildelt</th>
                        <th scope="col">Opprettet</th>
                        <th scope="col">Forfallsdato</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($tickets as $ticket)
                    <tr>
                        <th scope="col"><a href="{{ route('tickets.show', $ticket->id) }}">{{ $ticket->title }}</a></th>
                        <td>{{ $ticket->status->name }}</td>
                        <td>{{ $ticket->client->name ?? 'N/A' }}</td>
                        <td>{{ $ticket->assignedUser->name ?? 'Ikke tildelt' }}</td>
                        <td>{{ $ticket->created_at->format('Y-m-d') }}</td>
                        <td>{{ $ticket->due_date ? $ticket->due_date->format('Y-m-d') : 'Ingen' }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Paginering -->
    {{ $tickets->links() }}
</div>
@endsection
