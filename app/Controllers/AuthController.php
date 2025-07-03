<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\DiskonModel; // <--- TAMBAHKAN INI
use CodeIgniter\I18n\Time; // <--- TAMBAHKAN INI

use App\Models\UserModel;

class AuthController extends BaseController
{
    protected $user;
    protected $diskonModel; // <--- TAMBAHKAN INI

    function __construct()
    {
        helper('form');
        $this->user= new UserModel();
        $this->diskonModel = new DiskonModel(); // <--- TAMBAHKAN INI
    }
public function login()
{
    if ($this->request->getPost()) {
        $rules = [
            'username' => 'required|min_length[6]',
            'password' => 'required|min_length[7]|numeric',
        ];

        if ($this->validate($rules)) {
            $username = $this->request->getVar('username');
            $password = $this->request->getVar('password');

            $dataUser = $this->user->where(['username' => $username])->first(); //pasw 1234567

            if ($dataUser) {
                if (password_verify($password, $dataUser['password'])) {
                    session()->set([
                        'username' => $dataUser['username'],
                        'role' => $dataUser['role'],
                        'isLoggedIn' => TRUE
                    ]);

                      // --- BAGIAN YANG DITAMBAHKAN/DIMODIFIKASI: Pencarian Diskon ---
                        $currentDate = Time::today()->toDateString(); // Dapatkan tanggal hari ini (YYYY-MM-DD)

                        // Cari diskon yang tanggalnya sama dengan hari ini
                        $activeDiscount = $this->diskonModel->where('tanggal', $currentDate)->first();

                        if ($activeDiscount) {
                            // Simpan nominal diskon ke session
                            session()->set('active_discount_amount', $activeDiscount['nominal']);
                            // Tidak ada 'nama_diskon' di tabel Anda, jadi tidak bisa disimpan
                        } else {
                            // Jika tidak ada diskon aktif untuk hari ini, hapus dari session
                            session()->remove('active_discount_amount');
                        }
                        // --- AKHIR BAGIAN YANG DITAMBAHKAN/DIMODIFIKASI ---

                    return redirect()->to(base_url('/'));
                } else {
                    session()->setFlashdata('failed', 'Kombinasi Username & Password Salah');
                    return redirect()->back();
                }
            } else {
                session()->setFlashdata('failed', 'Username Tidak Ditemukan');
                return redirect()->back();
            }
        } else {
            session()->setFlashdata('failed', $this->validator->listErrors());
            return redirect()->back();
        }
    }

    return view('v_login');
}
public function logout()
{
    session()->destroy();
    session()->remove('active_discount_amount');
    session()->remove('active_discount_name');
    return redirect()->to('login');
}
}

