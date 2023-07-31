<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    // Upl;oading Files
    
    public function uploadFile(Request $request)
{
    // Validate the file input (if needed)
    $request->validate([
        'file' => 'required|file|mimes:pdf,docx,doc|max:10240', // Adjust the validation rules as needed
    ]);

    // Check if a file is present in the request
    if ($request->hasFile('file')) {
        // Store the uploaded file in a publicly accessible directory (e.g., 'uploads')
        $file = $request->file('file');
        $fileName = $file->getClientOriginalName(); // Use the original file name or generate a unique name if needed
        $file->storeAs('uploads', $fileName, 'public');

        // Return the file name and path to the front-end
        return response()->json([
            'file_name' => $fileName,
            'file_path' => asset('storage/uploads/' . $fileName),
        ]);
    }

    // If no file is present in the request, return an error response
    return response()->json(['error' => 'No file uploaded'], 400);
}

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Task::all();
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'description' => 'required',
            'status' => 'required'
        ]);

        return Task::create($request->all());
    }

    /**
     * Display the specified resource.
     */
    public function show(Task $task)
    {
        return $task;
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Task $task)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Task $task)
    {
        $request->validate([
            'title' => 'required',
            'description' => 'required',
            'status' => 'required'
        ]);

        $task->update($request->all());

        return $task;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Task $task)
    {
        $task->delete();

        return response()->json(['message' => 'Task removed successfully']);
    }
}
