<?php

namespace App\Controllers;

class LandingPage extends BaseController
{
    public function login(): string
    {
        return view('pages/landing/login_page');
    }

    public function register(): string
    {
        return view('pages/landing/register_page');
    }
}
