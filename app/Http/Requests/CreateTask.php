<?php
//《６章》バリデーション

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateTask extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */

    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */

    /**
     * create.blade.phpとtitleが紐付いている
     * required：入力必須
     * max:100：文字制限100文字
     * date:日本の形で入力
     * after_or_equal:today:今日を含んだ未来日のみ
     */

     //【重要】titleとdue_dateは、ユーザーが入力するname属性と紐付いている
    public function rules()
    {
        return [
            // ｜：ルールを区切る
            'title' => 'required|max:100',
            'due_date' => 'required|date|after_or_equal:today',
        ];
    }

    //name属性、attributesメソッドで日本語に変換
    //titleとdue_date、ユーザーが入力するname属性と紐付いている
    public function attributes()
    {
        return [
            'title' => 'タイトル',
            'due_date' => '期限日',
        ];
    }

    //バリデーションメッセージは事前に提供されている
    //作成される前から使用している
    public function messages()
    {
        return [
            'due_date.after_or_equal' => ':attribute には今日以降の日付を入力してください。',
        ];
    }
}
