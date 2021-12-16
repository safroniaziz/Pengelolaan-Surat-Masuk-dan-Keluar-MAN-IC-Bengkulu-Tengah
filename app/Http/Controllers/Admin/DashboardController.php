<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use PDO;

class DashboardController extends Controller
{
    public function dashboard(){
        return view('admin/dashboard');
    }
}
