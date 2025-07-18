<?php

namespace App\Livewire\Widgets;

use App\Services\WidgetManager;
use Illuminate\View\View;
use Livewire\Component;
use Illuminate\Support\Facades\Log;

class PositionRenderer extends Component
{
    public $position;
    public $route;
    public $options = [];
    public $widgetContent = '';

    protected $widgetManager;

    public function boot(WidgetManager $widgetManager)
    {
        $this->widgetManager = $widgetManager;
    }

    public function mount($position, $route = null)
    {
        $this->position = $position;
        $this->route = $route ?: \Route::currentRouteName();
        
        try {
            $this->widgetContent = $this->widgetManager->render($this->position, $this->route, $this->options);
        } catch (\Throwable $e) {
            Log::error("Widget error for {$position}: " . $e->getMessage(), [
                'route' => $this->route,
                'trace' => $e->getTraceAsString()
            ]);
            $this->widgetContent = '<div class="alert alert-danger">Widget Error: Unable to load widget content.</div>';
        }
    }

    public function render(): View
    {
        return view('livewire.widgets.position-renderer', [
            'renderedContent' => $this->widgetContent
        ]);
    }
}