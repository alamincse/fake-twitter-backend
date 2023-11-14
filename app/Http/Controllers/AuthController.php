<?php

namespace App\Http\Controllers;

use Inertia\Response;
use Inertia\Inertia;

class AuthController extends Controller
{
    public function login(): Response
    {
        return Inertia::render('Login');
    }

    public function register(): Response
    {
        return Inertia::render('Register');
    }

    public function dashboard(): Response
    {
        return Inertia::render('Dashboard');
    }
}
