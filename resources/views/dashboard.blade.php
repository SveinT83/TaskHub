@extends('layouts.app')

@section('title', 'Dashboard')

@section('pageHeader')
    <div class="d-flex justify-content-between align-items-center">
        <h1>Dashboard</h1>
        @auth
            @can('admin.configurations.widgets.configure')
                <a href="{{ route('admin.configurations.widgets.configure', ['route' => 'dashboard']) }}" 
                   class="btn btn-outline-primary btn-sm">
                    <i class="fas fa-cog me-2"></i>Configure Widgets
                </a>
            @endcan
        @endauth
    </div>
@endsection

@section('content')
    <div class="container-fluid">
        
        <!-- Header Widgets -->
        @if(isset($renderedWidgets['header']) && count($renderedWidgets['header']) > 0)
            <div class="row mb-4">
                @foreach($renderedWidgets['header'] as $widgetData)
                    <x-widget 
                        :size="$widgetData['size']"
                        :title="$widgetData['widget']->name"
                        :widget="$widgetData['widget']"
                        :configurable="$widgetData['widget']->is_configurable">
                        {!! $widgetData['html'] !!}
                    </x-widget>
                @endforeach
            </div>
        @endif

        <div class="row">
            <!-- Sidebar Left -->
            @if(isset($renderedWidgets['sidebar-left']) && count($renderedWidgets['sidebar-left']) > 0)
                <div class="col-lg-3 col-md-12 mb-4">
                    @foreach($renderedWidgets['sidebar-left'] as $widgetData)
                        <x-widget 
                            size="full"
                            :title="$widgetData['widget']->name"
                            :widget="$widgetData['widget']"
                            :configurable="$widgetData['widget']->is_configurable"
                            bodyClass="p-2">
                            {!! $widgetData['html'] !!}
                        </x-widget>
                    @endforeach
                </div>
            @endif

            <!-- Main Content Area -->
            <div class="{{ isset($renderedWidgets['sidebar-left']) || isset($renderedWidgets['sidebar-right']) ? 'col-lg-6' : 'col-12' }}">

                <!-- Main Top Widgets -->
                @if(isset($renderedWidgets['main-top']) && count($renderedWidgets['main-top']) > 0)
                    <div class="row mb-4">
                        @foreach($renderedWidgets['main-top'] as $widgetData)
                            <x-widget 
                                :size="$widgetData['size']"
                                :title="$widgetData['widget']->name"
                                :widget="$widgetData['widget']"
                                :configurable="$widgetData['widget']->is_configurable">
                                {!! $widgetData['html'] !!}
                            </x-widget>
                        @endforeach
                    </div>
                @endif

                <!-- Main Content Widgets -->
                @if(isset($renderedWidgets['main-content']) && count($renderedWidgets['main-content']) > 0)
                    <div class="row mb-4">
                        @foreach($renderedWidgets['main-content'] as $widgetData)
                            <x-widget 
                                :size="$widgetData['size']"
                                :title="$widgetData['widget']->name"
                                :widget="$widgetData['widget']"
                                :configurable="$widgetData['widget']->is_configurable">
                                {!! $widgetData['html'] !!}
                            </x-widget>
                        @endforeach
                    </div>
                @else
                    <!-- Fallback hvis ingen widgets er konfigurert -->
                    <div class="row">
                        <div class="col-12">
                            <x-widget title="Velkommen til TaskHub" size="full">
                                <div class="text-center py-5">
                                    <i class="fas fa-tachometer-alt fa-4x text-primary mb-3"></i>
                                    <h4>Velkommen til ditt dashboard!</h4>
                                    <p class="text-muted">
                                        @auth
                                            @can('admin.configurations.widgets.configure')
                                                Configure widgets to customize your dashboard.
                                            @else
                                                Contact administrator to configure widgets.
                                            @endcan
                                        @else
                                            Logg inn for å se personalisert innhold.
                                        @endauth
                                    </p>
                                    @auth
                                        @can('admin.configurations.widgets.configure')
                                            <a href="{{ route('admin.configurations.widgets.configure', ['route' => 'dashboard']) }}" 
                                               class="btn btn-primary">
                                                <i class="fas fa-cog me-2"></i>Configure Dashboard
                                            </a>
                                        @endcan
                                    @endauth
                                </div>
                            </x-widget>
                        </div>
                    </div>
                @endif

                <!-- Main Bottom Widgets -->
                @if(isset($renderedWidgets['main-bottom']) && count($renderedWidgets['main-bottom']) > 0)
                    <div class="row mb-4">
                        @foreach($renderedWidgets['main-bottom'] as $widgetData)
                            <x-widget 
                                :size="$widgetData['size']"
                                :title="$widgetData['widget']->name"
                                :widget="$widgetData['widget']"
                                :configurable="$widgetData['widget']->is_configurable">
                                {!! $widgetData['html'] !!}
                            </x-widget>
                        @endforeach
                    </div>
                @endif
            </div>

            <!-- Sidebar Right -->
            @if(isset($renderedWidgets['sidebar-right']) && count($renderedWidgets['sidebar-right']) > 0)
                <div class="col-lg-3 col-md-12 mb-4">
                    @foreach($renderedWidgets['sidebar-right'] as $widgetData)
                        <x-widget 
                            size="full"
                            :title="$widgetData['widget']->name"
                            :widget="$widgetData['widget']"
                            :configurable="$widgetData['widget']->is_configurable"
                            bodyClass="p-2">
                            {!! $widgetData['html'] !!}
                        </x-widget>
                    @endforeach
                </div>
            @endif
        </div>

        <!-- Footer Widgets -->
        @if(isset($renderedWidgets['footer']) && count($renderedWidgets['footer']) > 0)
            <div class="row mt-4">
                @foreach($renderedWidgets['footer'] as $widgetData)
                    <x-widget 
                        :size="$widgetData['size']"
                        :title="$widgetData['widget']->name"
                        :widget="$widgetData['widget']"
                        :configurable="$widgetData['widget']->is_configurable">
                        {!! $widgetData['html'] !!}
                    </x-widget>
                @endforeach
            </div>
        @endif
    </div>

    @push('scripts')
    <script>
        function configureWidget(widgetId) {
            window.location.href = `{{ route('admin.configurations.widgets.configure') }}?widget=${widgetId}`;
        }
    </script>
    @endpush
@endsection