<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\CreateTask;
use App\Http\Requests\EditTask;
use Illuminate\Support\Facades\Auth;
use App\Models\Folder;
use App\Models\Task;

class TaskController extends Controller
{
    public function index(Folder $folder)
    {
        $user = auth()->user();
        $folders = $user->folders()->get();
        $tasks = $folder->tasks()->get();

        return view('tasks/index', [
            'folders' => $folders,
            'folder_id' => $folder->id,
            'tasks' => $tasks
        ]);
    }
    public function showCreateForm(Folder $folder)
    {
        $user = Auth::user();
        $folder = $user->folders()->findOrFail($folder->id);

        return view('tasks/create', [
            'folder_id' => $folder->id,
        ]);
    }

    public function create(Folder $folder, CreateTask $request)
    {
        $user = Auth::user();
        $folder = $user->folders()->findOrFail($folder->id);

        $task = new Task();
        $task->title = $request->title;
        $task->due_date = $request->due_date;
        $folder->tasks()->save($task);

        return redirect()->route('tasks.index', [
            'folder' => $folder->id,
        ]);
    }

    public function showEditForm(Folder $folder, Task $task)
    {
        $this->checkRelation($folder,$task);
        $user = Auth::user();
        $folder = $user->folders()->findOrFail($folder->id);
        $task = $folder->tasks()->findOrFail($task->id);

        return view('tasks/edit', [
            'task' => $task,
        ]);
    }
    public function edit(Folder $folder, Task $task, EditTask $request)
    {
        $this->checkRelation($folder,$task);
        $user = Auth::user();
        $folder = $user->folders()->findOrFail($folder->id);
        $task = $folder->tasks()->findOrFail($task->id);

        $task->title = $request->title;
        $task->status = $request->status;
        $task->due_date = $request->due_date;
        $task->save();

        return redirect()->route('tasks.index', [
            'folder' => $task->folder_id,
        ]);
    }

    public function showDeleteForm(Folder $folder, Task $task)
    {
        $this->checkRelation($folder,$task);
        $user = Auth::user();
        $folder = $user->folders()->findOrFail($folder->id);
        $task = $folder->tasks()->findOrFail($task->id);

        return view('tasks/delete', [
            'task' => $task,
        ]);
    }

    public function delete(Folder $folder, Task $task)
    {
        $this->checkRelation($folder,$task);
        $user = Auth::user();
        $folder = $user->folders()->findOrFail($folder->id);
        $task = $folder->tasks()->findOrFail( $task->id );

        $task->delete();

        return redirect()->route('tasks.index', [
            'folder' => $task->folder_id
        ]);
    }

    public function checkRelation(Folder $folder,Task $task)
    {
        if($folder->id !== $task->folder_id)
        {
            abort(404);
        }
    }
}
