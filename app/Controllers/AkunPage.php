<?php

namespace App\Controllers;

class AkunPage extends BaseController
{
    public function akun(): string
    {
        return view('pages/akun/akun_page');
    }
}
