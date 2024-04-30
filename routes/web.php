<?php
//ルートグループはネストすることができる
//認証ミドルウェアを適用してから、必要なルートに対しては認可ミドルウェアを適用

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Route::get('/', function () {
//   return view('welcome');
// });

// TODO:4/12解説
// ログインしているユーザーのみルーティングにアクセスできる
// ログインしていないユーザーはルーティングにアクセスできない
// ログインしていないユーザーは、ログイン画面にリダイレクトされる
Route::group(['middleware' => 'auth'], function() {

  // TODO:4/10解説
  // ▼8章
  // Topページにアクセスしたとき
  Route::get('/', 'HomeController@index')->name('home');

  // ▼5章
  // フォルダ作成ページにアクセスしたとき
  Route::get('/folders/create', 'FolderController@showCreateForm')
  ->name('folders.create');

  // フォルダ作成ページからPOST送信したとき
  Route::post('/folders/create', 'FolderController@create');

  //〈１０章〉
  //【重要】can：ポリシーの認可処理を適用する
  //ログイン中のユーザーが作成していないフォルダにアクセス
  //ログインしようとすると認可が働く
  //その結果、作成していないフォルダにはアクセスできない
  Route::group(['middleware' => 'can:view,folder'], function () {

    // ▼3,4章
    // タスク一覧ページのルーティング
    // /folders/{id}/tasksにアクセスしたら、
    // TaskControllerクラスのindexメソッドを実行する
    // nameメソッドは、viewの中で短い記述でルーティングにアクセスするため
    // Route::get('/folders/{id}/tasks', 'TaskController@index')
    //   ->name('tasks.index');

    // TODO:4/15解説
    //〈１０章〉{id}を{folder}に変更、バインディング機能
    //【重要】モデル名と同義にする
    Route::get('/folders/{folder}/tasks', 'TaskController@index')
    ->name('tasks.index');

    // ▼6章
    // タスク作成ページにアクセスしたとき
    // Route::get('/folders/{id}/tasks/create', 'TaskController@showCreateForm')
    //   ->name('tasks.create');

    // // タスク作成ページからPOST送信したとき
    // Route::post('/folders/{id}/tasks/create', 'TaskController@create');

    Route::get('/folders/{folder}/tasks/create', 'TaskController@showCreateForm')->name('tasks.create');
    Route::post('/folders/{folder}/tasks/create', 'TaskController@create');

    // ▼7章
    // タスク編集ページにアクセスした時
    // Route::get('/folders/{id}/tasks/{task_id}/edit', 'TaskController@showEditForm')
    //   ->name('tasks.edit');

    // // タスク編集ページからPOST送信した時
    // Route::post('/folders/{id}/tasks/{task_id}/edit', 'TaskController@edit');

    Route::get('/folders/{folder}/tasks/{task}/edit', 'TaskController@showEditForm')->name('tasks.edit');
    Route::post('/folders/{folder}/tasks/{task}/edit', 'TaskController@edit');
  });


});

// TODO:4/10解説
// 会員登録機能を作成するためのルーティング
// 会員登録以外にも色々なルーティングが詰まっている
Auth::routes();




