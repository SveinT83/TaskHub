@extends('layouts.app')

@section('pageHeader')

    <!-- ------------------------------------------------- -->
    <!-- Ticket Header -->
    <!-- ------------------------------------------------- -->
    <div class="row">
        <h1 class="col-md-2">#{{ $ticket->status->id }} - {{ $ticket->title }}</h1>
        <p class="col-md-2"><strong>Status:</strong> {{ $ticket->status->name }}</p>
        <p class="col-md-2"><strong>Client:</strong> {{ $ticket->client->name ?? 'N/A' }}</p>
        <p class="col-md-2"><strong>Assigned:</strong> {{ $ticket->assignedUser->name ?? 'Unassignet' }}</p>
        <p class="col-md-2"><strong>Created:</strong> {{ $ticket->created_at->format('Y-m-d H:i') }}</p>
        <p class="col-md-2"><strong>Due date:</strong> {{ $ticket->due_date ? $ticket->due_date->format('Y-m-d') : 'Ingen' }}</p>
    </div>

    @if ($errors->any())
      <div class="alert alert-danger">
          <ul>
              @foreach ($errors->all() as $error)
                  <li>{{ $error }}</li>
              @endforeach
          </ul>
      </div>
  @endif
@endsection

@section('content')
<div class="container-fluid mt-3">



    <!-- -------------------------------------------------------------------------------------------------- -->
    <!-- Section's -->
    <!-- -------------------------------------------------------------------------------------------------- -->
    <div class="row">

        <!-- ------------------------------------------------- -->
        <!-- Main section -->
        <!-- ------------------------------------------------- -->
        <div class="col-md-9">
            
            <!-- ------------------------------------------------- -->
            <!-- Ticket Body -->
            <!-- ------------------------------------------------- -->
            <div class="card mt-3">
                <div class="card-header">
                    <h2>Description:</h2>
                </div>
                <div class="card-body">
                    <div class="mt-2">{{ $ticket->description }}</div>
                </div>
            </div>

            <!-- ------------------------------------------------- -->
            <!-- Corresponding -->
            <!-- ------------------------------------------------- -->
            @include('tdtickets::partials.ticketCorresponding')
          
            <!-- ------------------------------------------------- -->
            <!-- Ticket Action -->
            <!-- ------------------------------------------------- -->
            @include('tdtickets::partials.ticketAction')

        </div>
        <div class="col-md-3">

            <!-- ------------------------------------------------- -->
            <!-- Side section -->
            <!-- All Content here, can be in they own file in they own module's -->
            <!-- ------------------------------------------------- -->
            @include('tdtickets::partials.ticketSideActions')

        </div>
    </div>
</div>    
@endsection
