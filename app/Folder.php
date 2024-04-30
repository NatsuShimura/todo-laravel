<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

//継承先Modelクラスの設定を読み取る
class Folder extends Model
{
    public function tasks()
    {
        //App内のリレーション
        return $this->hasMany('App\Task');
    }
}
