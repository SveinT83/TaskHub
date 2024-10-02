<div class="card mt-3">
    <div class="card-header text-bg-primary">
        <h2>Groups</h2>
    </div>
    <div class="card-body bg-body-tertiary">
        @foreach($walls as $wall)
            <a class="card mb-2" href="{{ route('walls.show', $wall->id) }}">
                
                <!-- Wall Name -->
                <div class="card-header bg-secondary-subtle">
                    <h3>{{ $wall->name }}</h3>
                </div>

                <div class="card-body">
                    <div class="row">
                        <!-- Wall Description -->
                        <p class="col-md-12">{{ $wall->description }}</p>
                    </div>
                </div>

                <div class="card-footer">
                    <div class="row">
                        <!-- Created by (user who created the wall) -->
                        @if(optional($wall->creator)->name)
                            <div class="col-md-3">
                                <p class="bi bi-person-check-fill" style="font-size: 10px;"> Created by: <i>{{ $wall->creator->name }}</i></p>
                            </div>
                        @else
                            <p class="col-md-3 bi bi-person-slash" style="font-size: 10px;""> Unassigned</p>
                        @endif

                        <!-- Created at -->
                        <p class="col-md-3 bi bi-calendar-fill" style="font-size: 10px;""> {{ $wall->created_at->format('Y-m-d') }}</p>
                    </div>
                </div>

            </a>
        @endforeach
    </div>
    <div class="card-footer">
        <a href="{{ route('tasks.create') }}" class="btn btn-outline-primary btn-sm bi bi-plus"> add</a>
    </div>
</div>