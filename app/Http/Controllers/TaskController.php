<?php
//タスクコントローラー、チュートリアル４編集


namespace App\Http\Controllers;

use App\Folder;
use App\Task;
use App\Http\Requests\CreateTask;
use App\Http\Requests\EditTask;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
  // // ルーティングの{id}部分のデータが引数「int $id」に渡っている
  // // 引数を指定する際、データ型を指定する必要がある
  // public function index(int $id)
  // {
  //   // foldersテーブルの全てのデータを取得
  //   // ORMを使用するためには、モデルを作成する必要がある
  //   // $folders = Folder::all();

  //   // TODO:4/12解説
  //   // ログインしているユーザーのフォルダを取得する
  //   // folders()は、Userモデルのfoldersメソッドを使用している
  //   $folders = Auth::user()->folders()->get();

  //   // 選ばれたフォルダを取得する
  //   // findメソッドを使用して、引数のidと合致するデータを取得している
  //   $current_folder = Folder::find($id);

  //   // 選ばれたフォルダに紐づくタスクを取得する
  //   // ▼whereメソッド
  //   // 第一引数：tasksテーブルのfolder_id列
  //   // 第二引数：現在選択されているfoldersテーブルのid
  //   // getメソッド：データを取得する
  //   // $tasks = Task::where('folder_id', $current_folder->id)->get();

  //   // TODO:4/15解説
  //   // $tasksの以下のプログラムの上に記載する必要がある
  //   // abort関数：400,500番台のエラーを表示したい時に使用する
  //   // デメリット：複数のファイルに同じような記載をする必要がある
  //   // abort関数が実行されるとそれ以降の処理は実行されない
  //   if (is_null($current_folder)) {
  //     abort(404);
  //   }

  //   // 選択されているフォルダーに紐づくタスクを取得する
  //   // $current_folderは、モデルのFolderクラスと同義
  //   // $current_folderには、選択されているフォルダーが格納されている
  //   $tasks = $current_folder->tasks()->get();

  //   // 取得してきたデータをviewへ渡している
  //   // 'データの名前' => 渡すデータ
  //   // view側では、データの名前の部分を使用する
  //   // ▼view関数の引数
  //   // 第一引数：データを渡したいviewファイル
  //   // 第二引数：渡したいデータ
  //   return view('tasks/index', [
  //     'folders' => $folders,
  //     'current_folder_id' => $current_folder->id,
  //     'tasks' => $tasks,
  //   ]);
  // }

  // TODO:4/15解説
  // ルーティングの{folder}と引数の$folderが紐づいている
  //▼　《条件》左と右が正しくない時　
  //左：ログインしてるユーザー
  //右：見ようとしているフォルダを作ったユーザー
  //つまり、作っていないフォルダを見ようとした時、403エラー
  public function index(Folder $folder)
  {
    if (Auth::user()->id !== $folder->user_id) {
      abort(403);
    }
    // ユーザーのフォルダを取得する
    $folders = Auth::user()->folders()->get();

    // 選ばれたフォルダに紐づくタスクを取得する
    $tasks = $folder->tasks()->get();

    //　▼current_folder_idの$folder->idが変更
    return view('tasks/index', [
      'folders' => $folders,
      'current_folder_id' => $folder->id,
      'tasks' => $tasks,
    ]);
  }

  /**
   * 6章
   * GET /folders/{id}/tasks/create
   */
  // 引数の「int $id」で、選択中のfolderのidを受け取っている
  // ユーザーが入力したものではないので、「int $id」で受け取れる
  // ユーザーが入力したものだったら、
  // 「CreateFolder $request」で受け取る
  // public function showCreateForm(int $id)
  // {
  //   // tasks > create.blade.phpを表示するのと
  //   // tasks > create.blade.phpへ選択中のfolderのidを渡している
  //   return view('tasks/create', [
  //     'folder_id' => $id
  //   ]);
  // }

  // TODO:4/15解説
  //〈５章〉folder作成一覧
  public function showCreateForm(Folder $folder)
  {
    return view('tasks/create', [
      'folder_id' => $folder->id,
    ]);
  }

  /**
   * 6章
   * POST /folders/{id}/tasks/create
   */
  // 「int $id」 : ルーティングの{id}と紐づいていて、選択中のフォルダID
  // 「CreateTask $request」 : ユーザーの入力値を取得とバリデーションチェック
  // public function create(2, title => 'test' due_date => '2024/04/09')
  // public function create(int $id, CreateTask $request)
  // {
  //   // ユーザーが選択しているフォルダを取得
  //   $current_folder = Folder::find($id);

  //   // モデルのTaskをインスタンス化
  //   $task = new Task();
  //   // ユーザーが入力したタスクをtasksテーブルの各列に代入
  //   $task->title = $request->title;
  //   $task->due_date = $request->due_date;

  //   // ユーザーが選択しているフォルダに紐づく形で、タスクを保存
  //   $current_folder->tasks()->save($task);

  //   return redirect()->route('tasks.index', [
  //     'id' => $current_folder->id,
  //   ]);
  // }

  // TODO:4/15解説
  // createメソッド：フォルダ作成ページ
  public function create(Folder $folder, CreateTask $request)
  {


    //インスタンス化
    //$taskにname属性と紐づけている
    $task = new Task();
    $task->title = $request->title;
    $task->due_date = $request->due_date;

    //タスクを保存している
    $folder->tasks()->save($task);

    return redirect()->route('tasks.index', [
      'folder' => $folder->id,
    ]);
  }

  /**
   * GET /folders/{id}/tasks/{task_id}/edit
   */
  // 「int $id」:ルーティングの{id}と紐づいていて、選択中のフォルダID
  // 「int $task_id」:ルーティングの{task_id}と紐づいていて、選択中のタスクID
  // $task_idには、ユーザーが編集したいタスクIDが渡ってきている
  // public function showEditForm(int $id, int $task_id)
  // {
  //   // モデルとテーブルは、単数系と複数形の関係で紐づいている
  //   // findメソッドを使用して、引数のtask_idとTasksテーブルで合致するデータを取得している
  //   // つまり、ユーザーが選択中のタスクになる
  //   $task = Task::find($task_id);

  //   // tasks > edit.blade.phpへ画面を遷移し、
  //   // ユーザーが選択中のタスクのデータを渡している
  //   return view('tasks/edit', [
  //     'task' => $task,
  //   ]);
  // }

  // TODO:4/15解説
  public function showEditForm(Folder $folder, Task $task)
  {
    return view('tasks/edit', [
      'task' => $task,
    ]);
  }

  /**
   * TODO：4/9に解説
   * POST /folders/{id}/tasks/{task_id}/edit
   * 「use App\Http\Requests\EditTask;」が必要
   */
  // ▼「int $id」
  // ルーティングの{id}部分で、フォルダーに関するID
  // ▼「int $task_id」
  // ルーティングの{task_id}部分で、タスクに関するID
  // ▼「EditTask $request」
  // ユーザーが入力したデータを受け取り、バリデーションチェックを行う
  // public function edit(int $id, int $task_id, EditTask $request)
  // {
  //   // php artisan make:request TestTest
  //   // dd($id, $task_id, $request);
  //   // 1
  //   // 編集ボタンをクリックした、タスクの情報が取得される
  //   $task = Task::find($task_id);

  //   // 2
  //   // 左：編集ボタンをクリックした、タスクの列
  //   // 右：ユーザーが入力したデータ

  //   // $taskの中には、モデルのTaskが入っており、
  //   // モデルとテーブルは、単数系と複数形の関係で紐づいている
  //   // つまり、$taskはtasksテーブルということにもなる
  //   // 引数$requestには、ユーザーが入力したものが格納されている
  //   // ->title などの部分は、edit.blade.phpのname属性と紐づいている
  //   $task->title = $request->title;
  //   $task->status = $request->status;
  //   $task->due_date = $request->due_date;
  //   // 代入したデータをTasksテーブルに保存する
  //   $task->save();

  //   // 3
  //   // データの保存が完了したら、リダイレクトをする
  //   // tasks > index.blade.phpへ遷移
  //   // その際、編集するタスクに紐づくフォルダを開いた状態に
  //   // したいので、データを渡している
  //   return redirect()->route('tasks.index', [
  //     'id' => $task->folder_id,
  //   ]);
  // }

  // TODO:4/15解説

  //foldersテーブルのidとtasksテーブルのfolder_idが紐付いていない
  //その結果、404エラーをユーザーに表示する
  public function edit(Folder $folder, Task $task, EditTask $request)
  {
    if ($folder->id !== $task->folder_id) {
        abort(404);
    }

    // $task->title = $request->title;
    // $task->status = $request->status;
    // $task->due_date = $request->due_date;
    // $task->save();

    // return redirect()->route('tasks.index', [
    //   'folder' => $task->folder_id,
    // ]);
  }

}