<?php

namespace App\Controllers;

use App\Models\DiskonModel;
use CodeIgniter\I18n\Time;
use CodeIgniter\HTTP\ResponseInterface; // Pastikan ini di-import

class DiskonController extends BaseController
{
    protected $diskonModel; // Menggunakan nama properti yang lebih deskriptif

    function __construct()
    {
        helper('number');
        helper('form'); // Pastikan helper form dimuat
        $this->diskonModel = new DiskonModel(); // Menggunakan nama properti yang lebih deskriptif
    }

    // Fungsi pembantu untuk memeriksa role admin
    protected function checkAdminRole()
    {
        if (session()->get('role') !== 'admin') {
            session()->setFlashdata('failed', 'Anda tidak memiliki akses ke halaman ini.');
            return redirect()->to(base_url('/'));
        }
        return true; // Lanjutkan jika admin
    }

    public function index()
    {
        // Memeriksa role admin
        if (!$this->checkAdminRole()) {
            return $this->response->redirect(base_url('/'));
        }

        $data['diskon'] = $this->diskonModel->findAll();
        $data['title'] = 'Manajemen Diskon'; // Tambahkan judul halaman
        return view('v_diskon', $data);
    }

    // PERBAIKAN: Mengubah nama metode dari 'create' menjadi 'store'
    // dan mengembalikan logika validasi serta penanganan flashdata
    public function store()
    {
        // Memeriksa role admin
        if (!$this->checkAdminRole()) {
            return $this->response->redirect(base_url('/'));
        }

        // Menggunakan validasi dari DiskonModel
        if (!$this->diskonModel->validate($this->request->getPost())) {
            // Jika validasi gagal, kembalikan ke halaman sebelumnya dengan input dan error
            // Menggunakan 'validation_add' agar modal tambah yang terbuka
            return redirect()->back()->withInput()->with('validation_add', $this->diskonModel->validator);
        }

        $tanggalDiskon = $this->request->getPost('tanggal');
        $nominalDiskon = $this->request->getPost('nominal');

        $this->diskonModel->insert([
            'tanggal'    => $tanggalDiskon,
            'nominal'    => $nominalDiskon,
            'created_at' => Time::now(config('App')->appTimezone),
            'updated_at' => Time::now(config('App')->appTimezone), // Tambahkan updated_at
        ]);

        $tanggalSekarang = Time::now(config('App')->appTimezone)->toDateString();
        if ($tanggalDiskon == $tanggalSekarang) {
            session()->set('active_discount_amount', $nominalDiskon); // Menggunakan 'active_discount_amount'
        }

        session()->setFlashdata('success', 'Data Berhasil Ditambah');
        return redirect('diskon'); // Menggunakan redirect helper
    }

    // PERBAIKAN: Mengubah nama metode dari 'edit' menjadi 'update'
    public function update($id = null) // Menggunakan $id sebagai parameter
    {
        // Memeriksa role admin
        if (!$this->checkAdminRole()) {
            return $this->response->redirect(base_url('/'));
        }

        $diskonData = $this->diskonModel->find($id); // Menggunakan diskomModel
        if (!$diskonData) {
            session()->setFlashdata('failed', 'Data diskon tidak ditemukan.');
            return redirect()->to(base_url('diskon'));
        }

        // Aturan validasi untuk update (tanggal tidak divalidasi karena readonly)
        $rules = [
            'nominal' => [
                'rules' => 'required|numeric|greater_than[0]',
                'errors' => [
                    'required' => 'Nominal diskon harus diisi.',
                    'numeric' => 'Nominal diskon harus berupa angka.',
                    'greater_than' => 'Nominal diskon harus lebih besar dari 0.'
                ]
            ],
        ];

        // Melakukan validasi input
        if (!$this->validate($rules)) {
            // Jika validasi gagal, kembalikan ke halaman sebelumnya dengan input dan error
            // Menyimpan ID diskon ke session agar modal yang benar bisa dibuka kembali
            return redirect()->back()->withInput()->with('validation', $this->validator)->with('validation_id', $id);
        }

        $this->diskonModel->update($id, [ // Menggunakan diskomModel
            'nominal' => $this->request->getPost('nominal'),
            'updated_at' => Time::now(config('App')->appTimezone),
        ]);

        $tanggalDiskon = $diskonData['tanggal'];
        $tanggalSekarang = Time::now(config('App')->appTimezone)->toDateString();

        if ($tanggalDiskon == $tanggalSekarang) {
            session()->set('active_discount_amount', $this->request->getPost('nominal')); // Menggunakan 'active_discount_amount'
        }

        session()->setFlashdata('success', 'Data Berhasil Diubah');
        return redirect('diskon'); // Menggunakan redirect helper
    }

    // PERBAIKAN: Mengubah nama metode dari 'delete' menjadi 'delete' (sudah benar)
    public function delete($id = null) // Menggunakan $id sebagai parameter
    {
        // Memeriksa role admin
        if (!$this->checkAdminRole()) {
            return $this->response->redirect(base_url('/'));
        }

        $diskonData = $this->diskonModel->find($id); // Menggunakan diskomModel

        if (!$diskonData) {
            session()->setFlashdata('failed', 'Data diskon tidak ditemukan.');
            return redirect()->to(base_url('diskon'));
        }
        
        $this->diskonModel->delete($id); // Menggunakan diskomModel

        if ($diskonData) {
            $tanggalDiskon = $diskonData['tanggal'];
            $tanggalSekarang = Time::now(config('App')->appTimezone)->toDateString();

            if ($tanggalDiskon == $tanggalSekarang) {
                session()->set('active_discount_amount', 0); // Menggunakan 'active_discount_amount'
            }
        }
        
        session()->setFlashdata('success', 'Data Berhasil Dihapus');
        return redirect('diskon'); // Menggunakan redirect helper
    }
}
