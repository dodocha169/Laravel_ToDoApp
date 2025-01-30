<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\CreateTask;
use App\Http\Requests\EditTask;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\Folder;
use App\Models\Task;

class TaskController extends Controller
{
    public function index(Folder $folder)
    {
        try {
            $user = auth()->user();
            $folders = $user->folders()->get();
            $tasks = $folder->tasks()->get();

            return view
            ('tasks/index', [
                    'folders' => $folders,
                    'folder_id' => $folder->id,
                    'tasks' => $tasks
                ]);
        } catch (\Throwable $e) {
            Log::error
            ('Error TaskController in index: ' . $e->getMessage());
        }

    }
    public function showCreateForm(Folder $folder)
    {
        try {
            $user = Auth::user();
            $folder
                = $user->folders()->findOrFail($folder->id);

            return view
            ('tasks/create', [
                    'folder_id' => $folder->id,
                ]);
        } catch (\Throwable $e) {
            Log::error
            ('Error TaskController in showCreateForm: ' . $e->getMessage());
        }

    }

    public function create(Folder $folder, CreateTask $request)
    {
        try {
            $user = Auth::user();
            $folder
                = $user->folders()->findOrFail($folder->id);
            $task = new Task();
            $task->title = $request->title;
            $task->due_date = $request->due_date;
            $folder->tasks()->save($task);

            return redirect()->route
            ('tasks.index', [
                    'folder' => $folder->id,
                ]);
        } catch (\Throwable $e) {
            Log::error
            ('Error TaskController in create: ' . $e->getMessage());
        }

    }

    public function showEditForm(Folder $folder, Task $task)
    {
        try {
            $this->checkRelation($folder, $task);
            $user = Auth::user();
            $folder
                = $user->folders()->findOrFail($folder->id);
            $task
                = $folder->tasks()->findOrFail($task->id);

            return view
            ('tasks/edit', [
                    'task' => $task,
                ]);
        } catch (\Throwable $e) {
            Log::error
            ('Error TaskController in showEditForm: ' . $e->getMessage());
        }

    }
    public function edit(Folder $folder, Task $task, EditTask $request)
    {
        try {
            $this->checkRelation
            ($folder, $task);
            $user = Auth::user();
            $folder
                = $user->folders()->findOrFail($folder->id);
            $task
                = $folder->tasks()->findOrFail($task->id);

            $task->title = $request->title;
            $task->status = $request->status;
            $task->due_date = $request->due_date;
            $task->save();

            return redirect()->route
            ('tasks.index', [
                    'folder' => $task->folder_id,
                ]);
        } catch (\Throwable $e) {
            Log::error
            ('Error TaskController in edit: ' . $e->getMessage());
        }
    }

    public function showDeleteForm(Folder $folder, Task $task)
    {
        try {
            $this->checkRelation
            ($folder, $task);
            $user = Auth::user();
            $folder
                = $user->folders()->findOrFail($folder->id);
            $task
                = $folder->tasks()->findOrFail($task->id);

            return view
            ('tasks/delete', [
                    'task' => $task,
                ]);
        } catch (\Throwable $e) {
            Log::error
            ('Error TaskController in showDeleteForm: ' . $e->getMessage());
        }
    }

    public function delete(Folder $folder, Task $task)
    {
        try {
            $this->checkRelation
            ($folder, $task);
            $user = Auth::user();
            $folder
                = $user->folders()->findOrFail($folder->id);
            $task
                = $folder->tasks()->findOrFail($task->id);

            $task->delete();

            return redirect()->route
            ('tasks.index', [
                    'folder' => $task->folder_id
                ]);
        } catch (\Throwable $e) {
            Log::error
            ('Error TaskController in delete: ' . $e->getMessage());
        }

    }

    public function checkRelation(Folder $folder, Task $task)
    {
        if ($folder->id !== $task->folder_id) {
            abort(404);
        }
    }
}
