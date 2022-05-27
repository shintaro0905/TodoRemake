<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Folder;
use App\Models\Task;
use App\Http\Requests\CreateTask;
use App\Http\Requests\EditTask;
// ★ Authクラスをインポートする
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    public function index(Folder $folder)
    {
        
        // ユーザーのフォルダを取得する
        $folders = Auth::user()->folders()->get();

        //選ばれたフォルダに紐づくタスクを取得する
        $tasks = $folder->tasks()->get();

        return view('tasks/index', [
            'folders' => $folders,
            'current_folder_id' => $folder->id,
            'tasks' => $tasks,
            ]);
    }

    /**
 * GET /folders/{id}/tasks/create
 */
    public function showCreateForm(Folder $folder)
    {
        return view('tasks/create', [
        'folder_id' => $folder->id,
        ]);
    }

    public function create(Folder $folder, CreateTask $request)
    {
        $task = new Task();
        $task->title = $request->title;
        $task->due_date = $request->due_date;

        $folder->tasks()->save($task);

        return redirect()->route('tasks.index', [
            'folder' => $folder->id,
        ]);
    }

/**
 * GET /folders/{id}/tasks/{task_id}/edit
 */
    public function showEditForm(Folder $folder, Task $task)
    {
            $this->checkRelation($folder, $task);
        if ($folder->id !== $task->folder_id) {
            abort(404);
        }
        return view('tasks/edit', [
            'task' => $task,
        ]);
    }

    public function edit(Folder $folder, Task $task, EditTask $request)
    {
        $this->checkRelation($folder, $task);
        if ($folder->id !== $task->folder_id) {
            abort(404);
        }
        $task->title = $request->title;
        $task->status = $request->status;
        $task->due_date = $request->due_date;
        $task->save();

        return redirect()->route('tasks.index', [
            'folder' => $task->folder_id,
        ]);
    }

        /**
     * フォルダとタスクの関連性があるか調べる
     * @param Folder $folder
     * @param Task $task
     */
    private function checkRelation(Folder $folder, Task $task)
    {
        if ($folder->id !== $task->folder_id) {
            abort(404);
        }
    }
}
