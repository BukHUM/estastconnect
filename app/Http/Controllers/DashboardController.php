<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // Redirect to admin dashboard
        return redirect()->route('admin.dashboard');
    }
}
