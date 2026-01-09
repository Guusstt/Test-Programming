<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\PasienModel;

class Pasien extends BaseController
{
    protected $pasienModel;

    public function __construct()
    {
        $this->pasienModel = new PasienModel();
    }

    public function index()
    {
        $data = [
            'pasien' => []
        ];
        return view('pasien/index_spa', $data);
    }

    public function trash()
    {
        $data = [
            'pasien' => $this->pasienModel->onlyDeleted()->orderBy('id', 'DESC')->findAll()
        ];
        return view('pasien/trash', $data);
    }
    public function loadData()
    {
        $data = $this->pasienModel->orderBy('id', 'DESC')->findAll();

        return $this->response->setJSON([
            'data' => $data
        ]);
    }
    public function getData()
    {
        $client = \Config\Services::curlrequest();

        try {
            $response = $client->request('GET', 'https://jsonplaceholder.typicode.com/users');
            $users = json_decode($response->getBody());

            foreach ($users as $user) {
                $this->pasienModel->save([
                    'nama' => $user->name,
                    'norm' => 'RM-' . rand(1000, 9999),
                    'alamat' => $user->address->street . ', ' . $user->address->city
                ]);
            }

            $data = $this->pasienModel->orderBy('id', 'DESC')->findAll();

            return $this->response->setJSON([
                'status' => 'success',
                'data' => $data,
                'message' => 'Data dummy berhasil ditambahkan'
            ]);
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'status' => 'error',
                'data' => [],
                'message' => 'Gagal mengambil data dummy'
            ]);
        }
    }

    public function getTrashData()
    {
        $data = $this->pasienModel->onlyDeleted()->orderBy('id', 'DESC')->findAll();
        return $this->response->setJSON([
            'data' => $data
        ]);
    }

    public function getOne($id)
    {
        $data = $this->pasienModel->find($id);
        return $this->response->setJSON($data);
    }

    public function save()
    {
        if (
            !$this->validate([
                'nama' => 'required',
                'norm' => 'required'
            ])
        ) {
            return $this->response->setJSON([
                'status' => 'error',
                'errors' => \Config\Services::validation()->getErrors()
            ]);
        }

        $id = $this->request->getPost('id');
        $data = [
            'nama' => $this->request->getPost('nama'),
            'norm' => $this->request->getPost('norm'),
            'alamat' => $this->request->getPost('alamat'),
        ];

        if ($id) {
            $this->pasienModel->update($id, $data);
            $message = 'Data berhasil diupdate';
        } else {
            $this->pasienModel->save($data);
            $message = 'Data berhasil ditambahkan';
        }

        return $this->response->setJSON(['status' => 'success', 'message' => $message]);
    }

    public function deleteAjax($id)
    {
        $this->pasienModel->delete($id);
        return $this->response->setJSON([
            'status' => 'success',
            'message' => 'Data berhasil dihapus'
        ]);
    }

    public function restore($id)
    {
        $this->pasienModel->update($id, ['deleted_at' => null]);
        return $this->response->setJSON([
            'status' => 'success',
            'message' => 'Data berhasil dipulihkan'
        ]);
    }

    public function permanentDelete($id)
    {
        $this->pasienModel->delete($id, true);
        return $this->response->setJSON([
            'status' => 'success',
            'message' => 'Data berhasil dihapus permanen'
        ]);
    }

    public function purgeDeleted()
    {
        $this->pasienModel->purgeDeleted();
        return $this->response->setJSON([
            'status' => 'success',
            'message' => 'Semua data yang dihapus telah dibersihkan permanen'
        ]);
    }

    public function detail($id)
    {
        $data = $this->pasienModel->find($id);
        return $this->response->setJSON($data);
    }
}