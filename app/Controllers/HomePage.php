<?php

namespace App\Controllers;

class HomePage extends BaseController
{
    public function home(): string
    {
        return view('pages/home/home_page');
    }

    public function topup(): string
    {
        return view('pages/home/topup_page');
    }

    public function transaksi(): string
    {
        return view('pages/home/transaksi_page');
    }

    public function transaksiID(string $id): string
    {
        $data = ['id_servis' => $id];
        return view('pages/home/transaksi_page', $data);
    }

    public function history(): string
    {
        return view('pages/home/transaction/history_page');
    }
}
