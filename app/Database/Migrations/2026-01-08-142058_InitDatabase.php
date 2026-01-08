<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class InitDatabase extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => ['type' => 'INT', 'constraint' => 11, 'auto_increment' => true],
            'username' => ['type' => 'VARCHAR', 'constraint' => 100, 'unique' => true],
            'password' => ['type' => 'VARCHAR', 'constraint' => 255],
            'nama_lengkap' => ['type' => 'VARCHAR', 'constraint' => 150],
            'role' => ['type' => 'ENUM', 'constraint' => ['superadmin', 'admisi', 'perawat']],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('users');

        $this->forge->addField([
            'id' => ['type' => 'INT', 'constraint' => 11, 'auto_increment' => true],
            'nama' => ['type' => 'VARCHAR', 'constraint' => 255],
            'norm' => ['type' => 'VARCHAR', 'constraint' => 50],
            'alamat' => ['type' => 'TEXT'],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('pasien');

        $this->forge->addField([
            'id' => ['type' => 'INT', 'constraint' => 11, 'auto_increment' => true],
            'pasienid' => ['type' => 'INT', 'constraint' => 11],
            'noregistrasi' => ['type' => 'VARCHAR', 'constraint' => 50],
            'tglregistrasi' => ['type' => 'DATE'],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('pasienid', 'pasien', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('pendaftaran');

        $this->forge->addField([
            'id' => ['type' => 'INT', 'constraint' => 11, 'auto_increment' => true],
            'pendaftaranpasienid' => ['type' => 'INT', 'constraint' => 11],
            'jeniskunjungan' => ['type' => 'VARCHAR', 'constraint' => 100],
            'tglkunjungan' => ['type' => 'DATE'],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('pendaftaranpasienid', 'pendaftaran', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('kunjungan');

        $this->forge->addField([
            'id' => ['type' => 'INT', 'constraint' => 11, 'auto_increment' => true],
            'kunjunganid' => ['type' => 'INT', 'constraint' => 11],
            'keluhan_utama' => ['type' => 'TEXT'],
            'keluhan_tambahan' => ['type' => 'TEXT', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('kunjunganid', 'kunjungan', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('asesmen');
    }

    public function down()
    {
        $this->forge->dropTable('asesmen');
        $this->forge->dropTable('kunjungan');
        $this->forge->dropTable('pendaftaran');
        $this->forge->dropTable('pasien');
        $this->forge->dropTable('users');
    }
}