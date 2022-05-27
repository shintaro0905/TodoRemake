<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;
use App\Models\Folder;
use App\Http\Requests\CreateFolder; // ★ 追加
use Illuminate\Support\Facades\Auth;

class FolderController extends Controller
{
    public function create()
    {
        return view('folders/create');
    }

    public function createFolder(CreateFolder $request)
    {
    // フォルダモデルのインスタンスを作成する
        $folder = new Folder();
    // タイトルに入力値を代入する
        $folder->title = $request->title;
    // インスタンスの状態をデータベースに書き込む
        // $folder->save();

    // ★ ユーザーに紐づけて保存
        Auth::user()->folders()->save($folder);

        return redirect()->route('tasks.index', [
        'folder' => $folder->id,
        ]);
    }
}
