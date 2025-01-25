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
    public function index(int $id)
    {
        $folders = Folder::all();

        $folder = Folder::find($id);

        $tasks = $folder->tasks()->get();

        return view('tasks/index', [
            'folders' => $folders,
            'folder_id' => $folder->id,
            'tasks' => $tasks
        ]);
    }
    public function showCreateForm(int $id)
    {
        $user = Auth::user();
        $folder = $user->folders()->findOrFail($id);

        return view('tasks/create', [
            'folder_id' => $id
        ]);
    }

    public function create(int $id, CreateTask $request)
    {
        $user = Auth::user();
        $folder = $user->folders()->findOrFail($id);

        $task = new Task();
        $task->title = $request->title;
        $task->due_date = $request->due_date;
        $folder->tasks()->save($task);

        return redirect()->route('tasks.index', [
            'id' => $folder->id,
        ]);
    }

    public function showEditForm(int $id, int $task_id)
    {
        $user = Auth::user();
        $folder = $user->folders()->findOrFail($id);
        $task = $folder->find($task_id);

        return view('tasks/edit', [
            'task' => $task,
        ]);
    }
    public function edit(int $id, int $task_id, EditTask $request)
    {
        $user = Auth::user();
        $folder = $user->folders()->findOrFail($id);
        $task = $folder->find($task_id);

        $task->title = $request->title;
        $task->status = $request->status;
        $task->due_date = $request->due_date;
        $task->save();

        return redirect()->route('tasks.index', [
            'id' => $task->folder_id,
        ]);
    }

    public function showDeleteForm(int $id, int $task_id)
    {
        $task = Task::find($task_id);

        return view('tasks/delete', [
            'task' => $task,
        ]);
    }

    public function delete(int $id, int $task_id)
    {
        $task = Task::find($task_id);

        $task->delete();

        return redirect()->route('tasks.index', [
            'id' => $id
        ]);
    }
}
