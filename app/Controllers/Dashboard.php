<?php

namespace App\Controllers;

use App\Controllers\BaseController;

class Dashboard extends BaseController
{
    public function index()
    {
        $data = [
            'title' => 'Dashboard',
            'session_data' => session()->get()
        ];
        return view('dashboard', $data);
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/');
        }

        $data = [
            'title' => 'Dashboard',
            'isi' => 'Selamat Datang di Sistem Informasi Klinik'
        ];

        return view('dashboard', $data);
    }
}