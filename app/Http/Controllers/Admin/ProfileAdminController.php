<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProfileAdminController extends Controller
{
    public function index()
    {
        $current_user = auth()->user();
        return view('admin.admin-profile', compact('current_user'));
    }

    public function update(Request $request)
    {
        $this->validate($request, [
            'first_name' => 'required|string|min:3|max:100',
            'last_name' => 'required|string|min:3|max:100',
        ], [
            'first_name.required' => 'Nama Pertama Harus Diisi',
            'last_name.required' => 'Nama Terakhir Harus Diisi',
        ]);

        $current_user = auth()->user();

        $current_user->first_name = $request->first_name;
        $current_user->last_name = $request->last_name;
        $current_user->email = $request->email;
        $current_user->save();

        session()->flash('index', [
            'status' => 'success',
            'message' => 'Profil berhasil di ubah.',
        ]);

        return redirect()->route('profile.indexadmin');
    }
}
