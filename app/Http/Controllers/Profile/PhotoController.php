<?php

namespace App\Http\Controllers\Profile;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Auth\UpdatePhotoRequest;
use Illuminate\Support\Facades\Storage;

class PhotoController extends Controller
{
    public function update(UpdatePhotoRequest $request) 
    {
      $path= Storage::disk('public')->put('users-photo', $request->file('photo'));
      //dd($path);
        //$path = $request->file('photo')->store('users-photo', 'public');
       
        if($old_photo=auth()->user()->photo){
            Storage::disk('public')->delete($old_photo);
      
             }


      auth()->user()->update(['photo' => $path]);

       
     // dd(auth()->user()->photo);
    return redirect(route('profile.edit'))->with('message','Photo is changed.');
    }
}
