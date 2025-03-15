<?php

namespace App\Http\Controllers;

use App\Models\Testing;
use Illuminate\Http\Request;
use Inertia\Inertia;

class EmailDashboard extends Controller
{
    public function index()
    {
        $sales=Testing::all();
        return Inertia::render('User/emaildashboard',compact('sales'));
    }
}
