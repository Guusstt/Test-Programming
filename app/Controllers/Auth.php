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

        $username = $this->request->getPost('username');
        $password = $this->request->getPost('password');

        $data = $model->where('username', $username)->first();

        if ($data) {
            $pass = $data['password'];
            if (password_verify($password, $pass)) {
                $ses_data = [
                    'id' => $data['id'],
                    'username' => $data['username'],
                    'role' => $data['role'],
                    'nama' => $data['nama_lengkap'],
                    'isLoggedIn' => TRUE
                ];
                $session->set($ses_data);
                return redirect()->to('/dashboard');
            } else {
                $session->setFlashdata('msg', 'Password Salah');
                return redirect()->to('/');
            }
        } else {
            $session->setFlashdata('msg', 'Username tidak ditemukan');
            return redirect()->to('/');
        }
    }

    public function logout()
    {
        $session = session();
        $session->destroy();
        return redirect()->to('/');
    }
}