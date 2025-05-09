<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Task;
use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Fetch tasks from the database and pass them to the view
        $tasks = Task::orderBy('created_at', 'desc')->get();
        return Inertia::render('Tasks', [
            'tasks' => $tasks
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // This can be used to show a form if needed
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTaskRequest $request)
    {
        // Validate the request data to make sure required fields are provided
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'Assigned By' => 'required|string|max:255',
            'Assigned To' => 'required|string|max:255',
            'Priority' => 'required|string|max:255',
            'Status' => 'required|string|max:255',
            'Due Date' => 'required|date',
        ]);

        // Create the task with the validated data
        Task::create([
            'title' => $validated['title'],
            'Assigned By' => $validated['Assigned By'],
            'Assigned To' => $validated['Assigned To'],
            'Priority' => $validated['Priority'],
            'Status' => $validated['Status'],
            'Due Date' => $validated['Due Date'],
            'completed' => false,  // Default value for 'completed'
        ]);

        // Redirect to the task index page
        return redirect()->route('tasks.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Task $task)
    {
        // This can be used to display the details of a specific task
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Task $task)
    {
        // This can be used to show an edit form if needed
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTaskRequest $request, Task $task)
    {
        // Validate the incoming request for task update
        $validated = $request->validate([
            'title' => 'nullable|string|max:255',
            'Assigned By' => 'nullable|string|max:255',
            'Assigned To' => 'nullable|string|max:255',
            'Priority' => 'nullable|string|max:255',
            'Status' => 'nullable|string|max:255',
            'Due Date' => 'nullable|date',
            'completed' => 'nullable|boolean',  // Optional: You can update the task completion status
        ]);

        // Update the task with the validated data
        $task->update(array_filter($validated));  // array_filter removes any null values

        // Redirect to the task index page
        return redirect()->route('tasks.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Task $task)
    {
        // Delete the task from the database
        $task->delete();

        // Redirect to the task index page
        return redirect()->route('tasks.index');
    }
}
