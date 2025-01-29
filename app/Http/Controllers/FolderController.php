<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Folder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Requests\CreateFolder;
use App\Http\Requests\EditFolder;

class FolderController extends Controller
{
    public function showCreateForm()
    {
        try {
            $user = Auth::user();
            $user->folders;

            return view('folders/create');
        } catch (\Throwable $e) {
            Log::error('Error FolderController in showCreateForm: ' . $e->getMessage());
        }
    }

    public function create(CreateFolder $request)
    {
        try {
            $folder = new Folder();
            $folder->title = $request->title;
            $user = Auth::user();
            $user->folders()->save($folder);

            return redirect()->route('tasks.index', [
                'folder' => $folder->id,
            ]);
        } catch (\Exception $e) {
            Log::error('Error FolderController in create: ' . $e->getMessage());
        }

    }

    public function showEditForm(Folder $folder)
    {
        try {
            $user = Auth::user();
            $folder = $user->folders()->findOrFail($folder->id);

            return view('folders/edit', [
                'folder_id' => $folder->id,
                'folder_title' => $folder->title,
            ]);
        } catch (\Throwable $e) {
            Log::error('Error FolderController in showEditForm: ' . $e->getMessage());
        }

    }

    public function edit(Folder $folder, EditFolder $request)
    {
        try {
            $user = Auth::user();
            $folder = $user->folders()->findOrFail($folder->id);
            $folder->title = $request->title;
            $folder->save();

            return redirect()->route('tasks.index', [
                'folder' => $folder->id,
            ]);
        } catch (\Throwable $e) {
            Log::error('Error FolderController in edit: ' . $e->getMessage());
        }
    }

    public function showDeleteForm(Folder $folder)
    {
        try {
            $user = Auth::user();
            $folder = $user->folders()->findOrFail($folder->id);

            return view('folders/delete', [
                'folder_id' => $folder->id,
                'folder_title' => $folder->title,
            ]);
        } catch (\Throwable $e) {
            Log::error('Error in showDeleteForm: ' . $e->getMessage());
        }
    }

    public function delete(Folder $folder)
    {
        try {
            $user = Auth::user();
            $folder = $user->folders()->findOrFail($folder->id);

            $folder = DB::transaction(function () use ($folder) {
                if ($folder)
                    throw new \Exception('500');
                $folder->tasks()->delete();
                $folder->delete();
                return $folder;
            });
            $folder = Folder::first();

            return redirect()->route('tasks.index', [
                'folder' => $folder->id
            ]);
        } catch (\Throwable $e) {
            Log::error('Error FolderController in delete: ' . $e->getMessage());
        }

        $folder->tasks()->delete();
        $folder->delete();

        $folder = Folder::first();

        return redirect()->route('tasks.index', [
            'folder' => $folder->id,
        ]);
    }
}
