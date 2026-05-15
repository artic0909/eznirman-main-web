<?php

namespace App\Http\Controllers\Accounts;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Display the accounts dashboard.
     */
    public function index()
    {
        return view('account.dashboard.index');
    }
}
