<?php

namespace App\Http\Controllers\admin\machinery;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class MachineryController extends Controller
{
    public function machineCategoryView()
    {
        return view('admin.machinery.machine-category');
    }

    public function addMachineryView()
    {
        return view('admin.machinery.add-machinery');
    }

    public function transferMachineryView()
    {
        return view('admin.machinery.transfer-machinery');
    }
}
