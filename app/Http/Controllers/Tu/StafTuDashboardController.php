<?php

namespace App\Http\Controllers\Tu;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class StafTuDashboardController extends Controller
{
    public function dashboard(){
        return view('staf_tu.dashboard');
    }
}
