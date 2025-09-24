<?php

namespace App\Http\Controllers;

use Inertia\Inertia;

class DriverAppController extends Controller
{
    public function index()
    {
        return Inertia::render('Driver/DriverApp');
    }

    public function login()
    {
        return Inertia::render('Driver/Login');
    }

    public function register()
    {
        return Inertia::render('Driver/Register');
    }

    public function pendingApproval()
    {
        return Inertia::render('Driver/PendingApproval');
    }

    public function offline()
    {
        return Inertia::render('Driver/Offline');
    }
}
