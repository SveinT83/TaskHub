<?php

namespace App\Http\Widget\Controllers;

use App\Models\Widget;
use Illuminate\Http\Request;

class WidgetController extends Controller
{
    public function index()
    {
        $widgets = Widget::all();
        return view('widgets.index', compact('widgets'));
    }

    public function create()
    {
        return view('widgets.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'view_path' => 'required|string|max:255',
            'module' => 'required|string|max:255',
            'controller' => 'required|string|max:255',
        ]);

        Widget::create($request->all());

        return redirect()->route('widgets.index');
    }

    public function edit(Widget $widget)
    {
        return view('widgets.edit', compact('widget'));
    }

    public function update(Request $request, Widget $widget)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'view_path' => 'required|string|max:255',
            'module' => 'required|string|max:255',
        ]);

        $widget->update($request->all());

        return redirect()->route('widgets.index');
    }

    public function destroy(Widget $widget)
    {
        $widget->delete();

        return redirect()->route('widgets.index');
    }
}
