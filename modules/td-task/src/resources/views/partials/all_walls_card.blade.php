<div class="card">
    <div class="card-header">
        <h2>Walls</h2>
    </div>
    <div class="card-body">
        @foreach($walls as $wall)
            <a class="row border p-1" href="{{ route('walls.show', $wall->id) }}">
                <!-- Wall Name -->
                <b class="col-md-3">{{ $wall->name }}</b>

                <!-- Wall Description -->
                <p class="col-md-5">{{ $wall->description }}</p>

                <!-- Created by (user who created the wall) -->
                @if(optional($wall->creator)->name)
                    <p class="col-md-2 bi bi-person-check-fill"> Created by: {{ $wall->creator->name }}</p>
                @else
                    <p class="col-md-2 bi bi-person-slash"> Unassigned</p>
                @endif

                <!-- Created at -->
                <p class="col-md-2 bi bi-calendar-fill"> {{ $wall->created_at->format('Y-m-d') }}</p>
            </a>
        @endforeach
    </div>
    <div class="card-footer">
        <a href="{{ route('walls.create') }}" class="btn btn-outline-primary btn-sm bi bi-plus"> Add Wall</a>
    </div>
</div>
