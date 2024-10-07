<?php
namespace tronderdata\TdTask\Http\Controllers\Api;

use tronderdata\TdTask\Models\Task;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class TaskApiController extends Controller
{
    // Hent alle oppgaver
    public function index()
    {
        $tasks = Task::all();
        return response()->json($tasks);
    }

    // Hent en spesifikk oppgave
    public function show($id)
    {
        $task = Task::findOrFail($id);
        return response()->json($task);
    }

    // Opprett en ny oppgave
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'due_date' => 'nullable|date',
            'status_id' => 'nullable|exists:task_statuses,id',
            'created_by' => 'required|exists:users,id',
        ]);

        $task = Task::create($validatedData);

        return response()->json(['message' => 'Task created successfully', 'task' => $task], 201);
    }

    // Oppdater en oppgave
    public function update(Request $request, $id)
    {
        $task = Task::findOrFail($id);
        $task->update($request->all());

        return response()->json(['message' => 'Task updated successfully', 'task' => $task]);
    }

    // Slett en oppgave
    public function destroy($id)
    {
        $task = Task::findOrFail($id);
        $task->delete();

        return response()->json(['message' => 'Task deleted successfully']);
    }
}
