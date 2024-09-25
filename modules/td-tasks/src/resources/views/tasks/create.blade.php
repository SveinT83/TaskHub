<!-- -------------------------------------------------------------------------------------------------- -->
<!-- Extend the layout -->
<!-- -------------------------------------------------------------------------------------------------- -->
@extends('layouts.app')

<!-- -------------------------------------------------------------------------------------------------- -->
<!-- Content -->
<!-- -------------------------------------------------------------------------------------------------- -->
@section('content')
<div class="container">
    <h1>Opprett ny oppgave</h1>

    <!-- ------------------------------------------------- -->
    <!-- FORM START -->
    <!-- ------------------------------------------------- -->
    <form action="{{ route('tasks.store') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="title">Tittel</label>
            <input type="text" class="form-control" id="title" name="title" required>
        </div>
        
        <div class="form-group">
            <label for="description">Beskrivelse</label>
            <textarea class="form-control" id="description" name="description"></textarea>
        </div>

        <div class="form-group">
            <label for="task_wall_id">Oppgave vegg</label>
            <select class="form-control" id="task_wall_id" name="task_wall_id">
                @foreach($walls as $wall)
                    <option value="{{ $wall->id }}">{{ $wall->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label for="due_date">Frist</label>
            <input type="date" class="form-control" id="due_date" name="due_date">
        </div>

        <button type="submit" class="btn btn-primary">Opprett</button>
    </form>
    <!-- ------------------------------------------------- -->
    <!-- FORM END -->
    <!-- ------------------------------------------------- -->
</div>
@endsection
