<?php

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

// Route::redirect('/', )->name('home');
Auth::routes();

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->name('admin');

    Route::get('profileadmin', 'Admin\ProfileAdminController@index')->name('profile.indexadmin');
    Route::put('profileadmin', 'Admin\ProfileAdminController@update')->name('profile.updateadmin');

    Route::get('dashboard', 'Admin\DashboardController@index');

    Route::resource('/UserList', 'UserController');
    Route::resource('/AdminList', 'Admin\AdminListController');
    Route::get('/UserList/{id}/detail', 'UserController@detail');
});

Route::namespace ('Form')->group(function () {
    Route::get('forms/{form}/view', 'FormController@viewForm')->name('forms.view');
    Route::post('forms/{form}/responses', 'ResponseController@store')->name('forms.responses.store');
});

Route::namespace ('Auth')->group(function () {
    Route::get('register', 'RegisterController@showRegistrationForm')->name('register.show');
    Route::post('register', 'RegisterController@register')->name('register');

    Route::get('login', 'LoginController@showLoginForm')->name('login');
    Route::post('login', 'LoginController@login');

    Route::post('logout', 'LoginController@logout')->name('logout');
});

Route::get('profile', 'ProfileController@index')->name('profile.index');
Route::put('profile', 'ProfileController@update')->name('profile.update');

Route::middleware(['auth', 'verified'])->namespace('Form')->group(function () {
    Route::get('forms', 'FormController@index')->name('forms.index');
    Route::get('forms/create', 'FormController@create')->name('forms.create');
    Route::post('forms', 'FormController@store')->name('forms.store');
    Route::get('forms/{form}', 'FormController@show')->name('forms.show');
    Route::get('forms/{form}/edit', 'FormController@edit')->name('forms.edit');
    Route::put('forms/{form}', 'FormController@update')->name('forms.update');
    Route::delete('forms/{form}', 'FormController@destroy')->name('forms.destroy');

    Route::post('forms/{form}/draft', 'FormController@draftForm')->name('forms.draft');
    Route::get('forms/{form}/preview', 'FormController@previewForm')->name('forms.preview');
    Route::post('forms/{form}/open', 'FormController@openFormForResponse')->name('forms.open');
    Route::post('forms/{form}/close', 'FormController@closeFormToResponse')->name('forms.close');

    Route::post('forms/{form}/form-availability', 'FormAvailabilityController@save')->name('form.availability.save');
    Route::delete('forms/{form}/form-availability/reset', 'FormAvailabilityController@reset')->name('form.availability.reset');

    Route::post('forms/{form}/fields/add', 'FieldController@store')->name('forms.fields.store');
    Route::post('forms/{form}/fields/delete', 'FieldController@destroy')->name('forms.fields.destroy');

    Route::get('forms/{form}/responses', 'ResponseController@index')->name('forms.responses.index');
    Route::get('forms/{form}/responses/download', 'ResponseController@eksporExcel')->name('forms.response.export');
    Route::delete('forms/{form}/responses', 'ResponseController@destroyAll')->name('forms.responses.destroy.all');
    Route::delete('forms/{form}/responses/{response}', 'ResponseController@destroy')->name('forms.responses.destroy.single');

    Route::post('forms/{form}/collaborators', 'CollaboratorController@store')->name('form.collaborators.store');
    Route::delete('forms/{form}/collaborators/{collaborator}', 'CollaboratorController@destroy')->name('form.collaborator.destroy');
});
