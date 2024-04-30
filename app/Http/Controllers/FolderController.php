<?php

namespace App\Http\Controllers;

use App\Folder;
use App\Http\Requests\CreateFolder;
use App\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FolderController extends Controller
{
  public function showCreateForm()
  {
    // showCreateFormメソッドが実行されたら
    // folders > create.blade.phpを表示する
    return view('folders/create');
  }

  // 引数のRequestクラスを使用すると
  // ユーザーが入力した値を受け取ることができる
  // PHPでいうところの$_POSTの役割

  // 【重要】Requestのままだとバリデーションが使用できないので、
  // 代わりにCreateFolderクラスを使用している
  // バリデーションを司るクラスは、FormRequest
  public function create(CreateFolder $request)
  {
    // フォルダモデルのインスタンスを作成する
    // $folder = モデルのFolder = foldersテーブル になる
    $folder = new Folder();

    // タイトルに入力値を代入する
    // $folder = モデルのFolder = foldersテーブル
    // $folder->title：foldersテーブルのtitle列
    // $request->title：ユーザーが入力したname属性title
    $folder->title = $request->title;

    // インスタンスの状態をデータベースに書き込む
    // $folder->save();

    // TODO:4/12解説
    // ログインしているユーザーに紐づけて保存
    // folders()は、Userモデルのfoldersメソッドを使用している
    Auth::user()->folders()->save($folder);

    // リダイレクト = 画面の移動 = 画面の遷移
    // redirectを使用して、tasks > index.blade.phpへ遷移
    // その際、作成したフォルダを選択した状態で遷移する
    return redirect()->route('tasks.index', [
      'folder' => $folder->id,
    ]);
  }
}