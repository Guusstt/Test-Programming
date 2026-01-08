<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\KunjunganModel;
use App\Models\PendaftaranModel;

class Kunjungan extends BaseController
{
    protected $kunjunganModel;
    protected $pendaftaranModel;

    public function __construct()
    {
        $this->kunjunganModel = new KunjunganModel();
        $this->pendaftaranModel = new PendaftaranModel();
    }

    public function index()
    {
        $data = [
            'title' => 'Data Kunjungan Pasien'
        ];
        return view('kunjungan/index_spa', $data);
    }

    public function getData()
    {
        $data = $this->kunjunganModel
            ->select('kunjungan.*, pendaftaran.noregistrasi, pasien.nama as nama_pasien, pasien.norm')
            ->join('pendaftaran', 'pendaftaran.id = kunjungan.pendaftaranpasienid')
            ->join('pasien', 'pasien.id = pendaftaran.pasienid')
            ->orderBy('kunjungan.id', 'DESC')
            ->findAll();

        return $this->response->setJSON(['data' => $data]);
    }

    public function getPendaftaranList()
    {
        $data = $this->pendaftaranModel
            ->select('pendaftaran.id, pendaftaran.noregistrasi, pasien.nama')
            ->join('pasien', 'pasien.id = pendaftaran.pasienid')
            ->orderBy('pendaftaran.id', 'DESC')
            ->findAll();

        return $this->response->setJSON($data);
    }

    public function getOne($id)
    {
        $data = $this->kunjunganModel->find($id);
        return $this->response->setJSON($data);
    }
    public function save()
    {
        if (
            !$this->validate([
                'pendaftaranpasienid' => 'required',
                'jeniskunjungan' => 'required',
                'tglkunjungan' => 'required'
            ])
        ) {
            return $this->response->setJSON([
                'status' => 'error',
                'errors' => \Config\Services::validation()->getErrors()
            ]);
        }

        $id = $this->request->getPost('id');

        $data = [
            'pendaftaranpasienid' => $this->request->getPost('pendaftaranpasienid'),
            'jeniskunjungan' => $this->request->getPost('jeniskunjungan'),
            'tglkunjungan' => $this->request->getPost('tglkunjungan'),
        ];

        if ($id) {
            $this->kunjunganModel->update($id, $data);
            $message = 'Data kunjungan berhasil diperbarui';
        } else {
            $this->kunjunganModel->save($data);
            $message = 'Kunjungan baru berhasil didaftarkan';
        }

        return $this->response->setJSON(['status' => 'success', 'message' => $message]);
    }

    public function deleteAjax($id)
    {
        $this->kunjunganModel->delete($id);
        return $this->response->setJSON(['status' => 'success', 'message' => 'Data kunjungan berhasil dihapus']);
    }
}