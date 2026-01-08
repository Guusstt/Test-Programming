<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\PendaftaranModel;
use App\Models\PasienModel;

class Pendaftaran extends BaseController
{
    protected $pendaftaranModel;
    protected $pasienModel;

    public function __construct()
    {
        $this->pendaftaranModel = new PendaftaranModel();
        $this->pasienModel = new PasienModel();
    }

    public function index()
    {
        $data = [
            'title' => 'Data Pendaftaran'
        ];
        return view('pendaftaran/index_spa', $data);
    }

    public function getData()
    {
        $data = $this->pendaftaranModel
            ->select('pendaftaran.*, pasien.nama, pasien.norm')
            ->join('pasien', 'pasien.id = pendaftaran.pasienid')
            ->orderBy('pendaftaran.id', 'DESC')
            ->findAll();

        return $this->response->setJSON(['data' => $data]);
    }

    public function getPasien()
    {
        $data = $this->pasienModel->findAll();
        return $this->response->setJSON($data);
    }

    public function getOne($id)
    {
        $data = $this->pendaftaranModel->find($id);
        return $this->response->setJSON($data);
    }
    public function save()
    {
        if (
            !$this->validate([
                'pasienid' => 'required',
                'tglregistrasi' => 'required'
            ])
        ) {
            return $this->response->setJSON([
                'status' => 'error',
                'errors' => \Config\Services::validation()->getErrors()
            ]);
        }

        $id = $this->request->getPost('id');

        if ($id) {
            $this->pendaftaranModel->update($id, [
                'pasienid' => $this->request->getPost('pasienid'),
                'tglregistrasi' => $this->request->getPost('tglregistrasi'),
            ]);
            $message = 'Data pendaftaran berhasil diperbarui';
        } else {
            $noReg = 'REG-' . date('Ymd-His');

            $this->pendaftaranModel->save([
                'pasienid' => $this->request->getPost('pasienid'),
                'tglregistrasi' => $this->request->getPost('tglregistrasi'),
                'noregistrasi' => $noReg
            ]);
            $message = 'Pendaftaran berhasil! No Reg: ' . $noReg;
        }

        return $this->response->setJSON(['status' => 'success', 'message' => $message]);
    }

    public function deleteAjax($id)
    {
        $this->pendaftaranModel->delete($id);
        return $this->response->setJSON(['status' => 'success', 'message' => 'Pendaftaran dibatalkan']);
    }

    public function detail($id)
    {
        $data = $this->pendaftaranModel
            ->select('pendaftaran.*, pasien.nama, pasien.norm, pasien.alamat')
            ->join('pasien', 'pasien.id = pendaftaran.pasienid')
            ->where('pendaftaran.id', $id)
            ->first();

        return $this->response->setJSON($data);
    }

    public function create()
    {
        $data = [
            'title' => 'Form Pendaftaran Baru',
            'pasien' => $this->pasienModel->findAll()
        ];
        return view('pendaftaran/create', $data);
    }

    public function store()
    {
        if (
            !$this->validate([
                'pasienid' => 'required',
                'tglregistrasi' => 'required'
            ])
        ) {
            return redirect()->back()->withInput();
        }

        $noReg = 'REG-' . date('Ymd-His');

        $this->pendaftaranModel->save([
            'pasienid' => $this->request->getPost('pasienid'),
            'tglregistrasi' => $this->request->getPost('tglregistrasi'),
            'noregistrasi' => $noReg
        ]);

        return redirect()->to('/pendaftaran')->with('msg', 'Pendaftaran Berhasil dibuat. No Reg: ' . $noReg);
    }

    public function edit($id)
    {
        $dataPendaftaran = $this->pendaftaranModel->find($id);

        if (empty($dataPendaftaran)) {
            return redirect()->to('/pendaftaran')->with('msg', 'Data tidak ditemukan');
        }

        $dataPasien = $this->pasienModel->findAll();

        $data = [
            'title' => 'Edit Data Pendaftaran',
            'pendaftaran' => $dataPendaftaran,
            'pasien' => $dataPasien
        ];

        return view('pendaftaran/edit', $data);
    }

    public function update($id)
    {
        if (
            !$this->validate([
                'pasienid' => 'required',
                'tglregistrasi' => 'required'
            ])
        ) {
            return redirect()->back()->withInput();
        }

        $this->pendaftaranModel->update($id, [
            'pasienid' => $this->request->getPost('pasienid'),
            'tglregistrasi' => $this->request->getPost('tglregistrasi'),
        ]);

        return redirect()->to('/pendaftaran')->with('msg', 'Data pendaftaran berhasil diperbarui');
    }

    public function delete($id)
    {
        $this->pendaftaranModel->delete($id);
        return redirect()->to('/pendaftaran')->with('msg', 'Data dihapus');
    }
}