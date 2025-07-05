<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

use App\Models\UserModel;
use App\Models\DiskonModel;
use CodeIgniter\Controller; // Ini tidak perlu jika Anda extends BaseController
use CodeIgniter\I18n\Time;

class AuthController extends BaseController
{
    protected $user;
    function __construct()
    {
        helper('form');
        $this->user = new UserModel();
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
                        // --- MULAI MODIFIKASI DI SINI ---
                        $sessionData = [
                            'username' => $dataUser['username'],
                            'role' => $dataUser['role'],
                            'isLoggedIn' => TRUE
                        ];

                        // Inisialisasi DiskonModel
                        $diskonModel = new DiskonModel();
                        // Dapatkan tanggal hari ini
                        $today = Time::today('Asia/Jakarta')->toDateString();
                        // Cari diskon untuk hari ini
                        $diskonHariIni = $diskonModel->getDiskonByTanggal($today);

                        if ($diskonHariIni) {
                            // Jika diskon ditemukan, simpan nominalnya ke session
                            $sessionData['diskon_nominal'] = $diskonHariIni['nominal'];
                        } else {
                            // Jika tidak ada diskon, set nominal_diskon ke 0 atau nilai default lainnya
                            $sessionData['diskon_nominal'] = 0;
                        }

                        // Set semua data session
                        session()->set($sessionData);
                        // --- AKHIR MODIFIKASI ---

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
        return redirect()->to('login');
    }
}