<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\AsesmenModel;
use App\Models\KunjunganModel;

class Asesmen extends BaseController
{
    protected $asesmenModel;
    protected $kunjunganModel;

    public function __construct()
    {
        $this->asesmenModel = new AsesmenModel();
        $this->kunjunganModel = new KunjunganModel();
    }

    public function index()
    {
        $data = [
            'title' => 'Rekam Medis (Asesmen)'
        ];
        return view('asesmen/index_spa', $data);
    }

    public function getData()
    {
        $data = $this->asesmenModel
            ->select('
                asesmen.*, 
                kunjungan.tglkunjungan, 
                pendaftaran.noregistrasi, 
                pasien.nama as nama_pasien, 
                pasien.norm, 
                pasien.alamat
            ')
            ->join('kunjungan', 'kunjungan.id = asesmen.kunjunganid')
            ->join('pendaftaran', 'pendaftaran.id = kunjungan.pendaftaranpasienid')
            ->join('pasien', 'pasien.id = pendaftaran.pasienid')
            ->orderBy('asesmen.id', 'DESC')
            ->findAll();

        return $this->response->setJSON(['data' => $data]);
    }

    public function getKunjunganList()
    {
        $data = $this->kunjunganModel
            ->select('
            kunjungan.id, 
            kunjungan.tglkunjungan, 
            pendaftaran.noregistrasi, 
            pasien.nama, 
            pasien.norm
        ')
            ->join('pendaftaran', 'pendaftaran.id = kunjungan.pendaftaranpasienid')
            ->join('pasien', 'pasien.id = pendaftaran.pasienid')
            ->orderBy('kunjungan.id', 'DESC')
            ->findAll();

        return $this->response->setJSON($data);
    }

    public function getOne($id)
    {
        $data = $this->asesmenModel->find($id);
        return $this->response->setJSON($data);
    }

    public function save()
    {
        if (
            !$this->validate([
                'kunjunganid' => 'required',
                'keluhan_utama' => 'required',
            ])
        ) {
            return $this->response->setJSON([
                'status' => 'error',
                'errors' => \Config\Services::validation()->getErrors()
            ]);
        }

        $id = $this->request->getPost('id');

        $data = [
            'kunjunganid' => $this->request->getPost('kunjunganid'),
            'keluhan_utama' => $this->request->getPost('keluhan_utama'),
            'keluhan_tambahan' => $this->request->getPost('keluhan_tambahan'),
        ];

        if ($id) {
            $this->asesmenModel->update($id, $data);
            $msg = 'Data asesmen berhasil diperbarui';
        } else {
            $this->asesmenModel->save($data);
            $msg = 'Data asesmen berhasil disimpan';
        }

        return $this->response->setJSON(['status' => 'success', 'message' => $msg]);
    }

    public function deleteAjax($id)
    {
        $this->asesmenModel->delete($id);
        return $this->response->setJSON(['status' => 'success', 'message' => 'Data berhasil dihapus']);
    }
}