<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'username' => 'admin',
                'password' => password_hash('12345', PASSWORD_BCRYPT),
                'nama_lengkap' => 'Super Admin',
                'role' => 'superadmin',
            ],
            [
                'username' => 'admisi',
                'password' => password_hash('67890', PASSWORD_BCRYPT),
                'nama_lengkap' => 'Petugas Admisi',
                'role' => 'admisi',
            ],
            [
                'username' => 'perawat',
                'password' => password_hash('22334', PASSWORD_BCRYPT),
                'nama_lengkap' => 'Perawat Jaga',
                'role' => 'perawat',
            ]
        ];

        $this->db->table('users')->insertBatch($data);
    }
}