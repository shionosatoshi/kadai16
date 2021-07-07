<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Profile;

use App\ProfileHistory;

use Carbon\Carbon;

class ProfileController extends Controller
{
    //
    public function add()
    {
        return view('admin.profile.create');
    }

    public function create(Request $request)
    {
         $this->validate($request, Profile::$rules);
    $profile =  new Profile;
    $form = $request->all();

      unset($form['_token']);
      // フォームから送信されてきたimageを削除する
      $profile->fill($form);
      $profile->save();
        return redirect('admin/profile/create');
    }

    public function edit(Request $request)
    {
         $profile = Profile::find($request->id);
      if (empty($profile)) {
        abort(404);    
      }
        return view('admin.profile.edit',['profile'=>$profile]);
    }

    public function update(Request $request)
    {
      $this->validate($request, Profile::$rules);
      // News Modelからデータを取得する
      $profile = Profile::find($request->id);
      // 送信されてきたフォームデータを格納する
      $form = $request->all();
     
    

        unset($form['_token']);
        $profile->fill($form)->save();

        // 以下を追記
        $history = new ProfileHistory;
        $history->profile_id = $profile->id;
        $history->edited_at = Carbon::now();
        $history->save();

        return redirect('admin/profile/edit?id='.$profile->id);
    }
}