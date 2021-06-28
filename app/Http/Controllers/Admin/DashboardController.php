<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $men_learning = \DB::table('users')->where('usertype', '=', 'user')->count();
        $women_learning = \DB::table('users')->where('usertype', '=', 'admin')->count();

        $form = \DB::table('forms')->count();

        $statusopen = \DB::table('forms')->where('status', '=', 'open')->count();
        $statusdraft = \DB::table('forms')->where('status', '=', 'draft')->count();
        $statuspending = \DB::table('forms')->where('status', '=', 'pending')->count();
        $statusclosed = \DB::table('forms')->where('status', '=', 'closed')->count();

        return view('admin.dashboard', compact('men_learning', 'women_learning', 'form', 'statusopen', 'statusdraft', 'statuspending', 'statusclosed'));
    }

    public function chart()
    {
        $result = \DB::table('users')->where('usertype', '=', 'user')->count();
        return response()->json($result);
    }
}
