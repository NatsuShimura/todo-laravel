<?php
//フォルダテーブルのseeder

//use文：名前空間の拡張機能
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FoldersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //〈８章〉追記
        //usersテーブル、最初のデータを取得
        //firstメソッド：ユーザーを一行だけ取得して、
        //その ID を user_id の値に指定しています。
        $user = DB::table('users')->first();

        $titles = [
            'プライベート',
            '仕事',
            '旅行'
        ];

        foreach ($titles as $title) {
            DB::table('folders')->insert([
                'title' => $title,
                //〈８章〉追記
                'user_id' => $user->id,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
        }
    }
}
