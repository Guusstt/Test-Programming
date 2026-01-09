<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\UserModel;

class Auth extends BaseController
{
    public function index()
    {
        if (session()->get('isLoggedIn')) {
            return redirect()->to('/dashboard');
        }

        return view('login');
    }

    public function loginProcess()
    {
        $session = session();
        $model = new UserModel();

        $username = $this->request->getVar('username');
        $password = $this->request->getVar('password');

        $user = $model->where('username', $username)->first();

        if ($user) {

            if (password_verify($password, $user['password'])) {

                $ses_data = [
                    'id_user' => $user['id'],
                    'username' => $user['username'],
                    'nama' => $user['nama_lengkap'],
                    'role' => $user['role'],
                    'isLoggedIn' => TRUE
                ];
                $session->set($ses_data);

                return redirect()->to('/dashboard');
            } else {
                return redirect()->to('/')->with('msg', 'Password salah!');
            }
        } else {
            return redirect()->to('/')->with('msg', 'Username tidak ditemukan!');
        }
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to('/');
    }

    public function checkSession()
    {
        echo "<h2>Session Debug Info</h2>";
        echo "<pre>";
        print_r(session()->get());
        echo "</pre>";

        echo "<h3>Is Logged In?</h3>";
        echo session()->get('isLoggedIn') ? 'YES' : 'NO';

        echo "<h3>Session ID</h3>";
        echo session_id();
    }
}