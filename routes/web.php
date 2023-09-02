<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Profile\PhotoController;
use App\Http\Controllers\TicketController;
use Illuminate\Support\Facades\Route;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use OpenAI\Laravel\Facades\OpenAI;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {

   // $user = User::find(1);
   return view('welcome');
// $user = DB::insert('insert into users (name, email, password, satus) value(?, ?, ?, ?)',[
//     'User2',
//      'user2@gmail.com',
//     'password',
//     '0'
// ]);

// $user= DB::table('users')->insert([
//     'name' => 'User3',
//     'email' => 'user3@gmail.com',
//     'password' => 'password', 
//     'satus' => 0  
// ]);
// $user = User::create([
//     'name' => 'User2',
//     'email' => 'user2@gmail.com',
//     'password' => 'password',
//     'status' =>  0
// ]);

//dd($user->name);
});



Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::patch('/profile/photo', [PhotoController::class, 'update'])->name('profile.photo');
});

require __DIR__.'/auth.php';

// Route::get('/openai', function(){
    

//     $result = OpenAI::completions()->create([
//         'model' => 'text-davinci-003',
//         'prompt' => 'PHP is',
//     ]);
    
//     echo $result['choices'][0]['text']; // an open-source, widely-used, server-side scripting language.
// });
//--LOGIN WITH GITHUB ACCOUNT-----
Route::post('/auth/redirect', function () {
    return Socialite::driver('github')->redirect();
})->name('login.github');
 
Route::get('/auth/callback', function () {
    $user = Socialite::driver('github')->user();
 
    $user = User::firstOrCreate(['email'=> $user->email], [
        'name' => $user->name,
        'password' => 'password',
    ]);
    Auth::login($user);
    return redirect('/dashboard');

});


Route::middleware('auth')->group(function(){
    //Route::get('/ticket/create', [TicketController::class, 'create'])->name('ticket.create');
   // Route::post('/ticket/store', [TicketController::class, 'store'])->name('ticket.store');
   // WE CAN USE RESOUCE TO CALL TicketController resource

   Route::resource('ticket', TicketController::class);
});



