<!-- filepath: /var/Projects/TaskHub/Dev/modules/td-equipment/src/Resources/views/widgets/equipmentsListWidget.blade.php -->

    <!-- -------------------------------------------------------------------------------------------------- -->
    <!-- Card - Equipment -->
    <!-- -------------------------------------------------------------------------------------------------- -->
    <x-card-secondary title="Equipment Certification Status">

        <p>Total Equipment: {{ $totalEquipment }}</p>

        <div class="row justify-content-center text-center">
            <div class="col-md-2 m-1 border">
                <p>To be certified this month:</p>
                <p>{{ $certifyThisMonth }}</p>
            </div>
            <div class="col-md-2 m-1 border">
                <p>To be certified next month:</p>
                <p>{{ $certifyNextMonth }}</p>
            </div>
            <div class="col-md-2 m-1 border">
                <p>To be certified later this year:</p>
                <p>{{ $certifyLaterThisYear }}</p>
            </div>
            <div class="col-md-2 m-1 border">
                <p>Certified earlier this year:</p>
                <p>{{ $certifiedEarlierThisYear }}</p>
            </div>
        </div>
            
        <div class="row mt-1">
            <a href="{{ url('equipment') }}" class="btn btn-primary">View All Equipment</a>
        </div>

    <!-- -------------------------------------------------------------------------------------------------- -->
    <!-- End Equipment Card -->
    <!-- -------------------------------------------------------------------------------------------------- -->
    </x-card-secondary>