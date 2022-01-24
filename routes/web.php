<?php

use App\Models\{Course, User, Preference, Lesson, Module, Permission};
use Illuminate\Support\Facades\Route;

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

Route::get('/', function () {
    return view('welcome');
});

Route::get('/one-to-one', function(){
   // Pega primeiro usuario $user = User::first();
    $user = User::with('preference')->find(2);

    $data = [
         'background-color' => '#000',
    ];

    if ($user->preference) {
        $user->preference->update($data);
    } else {
        //$user->preference()->create($data);
        $preference = new Preference($data);
        $user->preference()->save($preference);
    }

    $user->refresh();
    var_dump($user->preference);

    $user->preference->delete();

    $user->refresh();
    dd($user->preference()->get());
});

Route::get('/one-to-many', function(){
    //$course  =Course::create(['name' => 'Curso de Laravel']);
    $course = Course::with('modules.lessons')->first();

    echo $course->name;
    echo '<br/>';
    foreach ($course->modules as $module) {
        echo $module->name;
        echo '<br/>';
        foreach ($module->lessons as $lesson) {
            if ($lesson->name != null) {
                echo $lesson->name;
            } else {
                echo 'Nenhuma aula cadastrada';
            }

        }
    }

    dd($course);

    $data = [
        'name' => 'Modulo X2'
    ];
    $course->modules()->create($data);

    $modules= $course->modules;


    dd($modules);
});

Route::get('/many-to-many', function(){
    //$permission = Permission::create(['name' => 'menu 04']);
    //$permission = Permission::all();
    //dd($permission);
    $user = User::with('permissions')->find(3);

    //$permission = Permission::find(2);
    //$user->permissions()->save($permission);

  /* $user->permissions()->saveMany([
        Permission::find(4),
        Permission::find(5),
        Permission::find(7),
    ]);*/

    //$user->permissions()->sync([2]);

    //$user->permissions()->attach([1]); //adiciona registro
    $user->permissions()->detach([4]); //deleta registro

    $user->refresh();

    dd($user->permissions);
});
