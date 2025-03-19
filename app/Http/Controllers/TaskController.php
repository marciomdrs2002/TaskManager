<?php

namespace App\Http\Controllers;

use App\Repositories\TaskRepository;
use App\Http\Resources\TaskResource;
use App\Models\Task;

class TaskController extends BaseController
{
    public function __construct(TaskRepository $repository)
    {
        parent::__construct($repository);
    }
    
    protected function getModelName(): string
    {
        return 'tasks';
    }
    
    protected function getResourceClass(): string
    {
        return TaskResource::class;
    }
    
    protected function getViewPrefix(): string
    {
        return 'task';
    }
    
    protected function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'completed' => 'boolean',
            'deadline' => 'nullable|date|after:today',
            'assignee_id' => 'nullable|exists:users,id',
            'priority' => 'required|integer|min:1',
        ];
    }

    public function completeTask(Task $task)
    {
        $task->completed = true;
        $task->save();
        
        return redirect()->back()->with('success', 'Task completed successfully');
    }
}