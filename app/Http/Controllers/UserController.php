<?php

namespace App\Http\Controllers;

use App\Form;
use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use View;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::where('usertype', 'user')->get();
        return \view('admin.userlist')->with('users', $users);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return \view('admin.userlist-create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $student = $request->validate([
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|email:rfc,dns',
            'password' => 'required',
        ]);

        $student = new User;
        $student->first_name = $request->first_name;
        $student->last_name = $request->last_name;
        $student->email = $request->email;
        $student->password = Hash::make($request->password);
        $student->usertype = "user";

        $student->save();

        return \redirect('/UserList')->with('status', 'Pengguna Berhasil Dibuat');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $users = User::findOrFail($id);

        // SELECT u.id, f.user_id, concat(u.first_name,' ',u.last_name), u.email, u.usertype, u.created_at,f.title, f.description,f.status,f.created_at FROM forms f join users u on f.user_id=u.id
        // \dump($persons);
        return \view('admin.userlist-detail')->with('users', $users);
    }

    public function detail($id)
    {
        $users = User::findOrFail($id);
        $persons = Form::join('users', function ($join) use ($users, $id) {
            $join->on('forms.user_id', '=', "users.id")
                ->where('users.usertype', "=", 'user')
                ->where('forms.user_id', '=', $id);
        })->get();

        // \dump($users);
        return \view('admin.userlist-detail-form')->with('users', $persons);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $users = User::find($id);
        return view('admin.userlist-edit')->with('user', $users);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $user = $request->validate([
            'email' => 'required|email:rfc,dns',
            'first_name' => 'required',
            'last_name' => 'required',
            'usertype' => 'required',
        ]);

        $user = User::findOrFail($id);
        $user->email = $request->email;
        $user->first_name = $request->first_name;
        $user->last_name = $request->last_name;
        $user->usertype = $request->usertype;

        $user->update();

        return \redirect('/UserList')->with('status', 'Data Berhasil di Edit');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = User::find($id);
        $user->delete();
        return \redirect('/UserList')->with('status', 'Data Berhasil di Hapus');

    }
}
