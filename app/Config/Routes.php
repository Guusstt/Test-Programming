<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */

$routes->get('/', 'Auth::index');
$routes->post('/auth/loginProcess', 'Auth::loginProcess');
$routes->get('/logout', 'Auth::logout');
$routes->get('/check-session', 'Auth::checkSession');


$routes->group('', ['filter' => 'auth'], function ($routes) {

    $routes->get('dashboard', 'Dashboard::index', ['filter' => 'role:superadmin,admisi,perawat']);

    $routes->group('pasien', ['filter' => 'role:superadmin,admisi'], function ($routes) {
        $routes->get('/', 'Pasien::index');
        $routes->get('loadData', 'Pasien::loadData');
        $routes->get('getData', 'Pasien::getData');
        $routes->get('getOne/(:num)', 'Pasien::getOne/$1');
        $routes->get('detail/(:num)', 'Pasien::detail/$1');
        $routes->post('save', 'Pasien::save');
        $routes->delete('deleteAjax/(:num)', 'Pasien::deleteAjax/$1');
        $routes->post('import', 'Pasien::import');

        $routes->get('trash', 'Pasien::trash');
        $routes->get('getTrashData', 'Pasien::getTrashData');
        $routes->post('restore/(:num)', 'Pasien::restore/$1');
        $routes->delete('permanentDelete/(:num)', 'Pasien::permanentDelete/$1');
        $routes->delete('purgeDeleted', 'Pasien::purgeDeleted');
    });

    $routes->group('pendaftaran', function ($routes) {
        $routes->group('', ['filter' => 'role:superadmin,admisi,perawat'], function ($routes) {
            $routes->get('/', 'Pendaftaran::index');
            $routes->get('getData', 'Pendaftaran::getData');
            $routes->get('detail/(:num)', 'Pendaftaran::detail/$1');
        });

        $routes->group('', ['filter' => 'role:superadmin,admisi'], function ($routes) {
            $routes->get('getPasien', 'Pendaftaran::getPasien');
            $routes->get('getOne/(:num)', 'Pendaftaran::getOne/$1');
            $routes->post('save', 'Pendaftaran::save');
            $routes->delete('deleteAjax/(:num)', 'Pendaftaran::deleteAjax/$1');
        });
    });

    $routes->group('kunjungan', function ($routes) {
        $routes->group('', ['filter' => 'role:superadmin,admisi,perawat'], function ($routes) {
            $routes->get('/', 'Kunjungan::index');
            $routes->get('getData', 'Kunjungan::getData');
        });

        $routes->group('', ['filter' => 'role:superadmin,admisi'], function ($routes) {
            $routes->get('getPendaftaranList', 'Kunjungan::getPendaftaranList');
            $routes->get('getOne/(:num)', 'Kunjungan::getOne/$1');
            $routes->post('save', 'Kunjungan::save');
            $routes->delete('deleteAjax/(:num)', 'Kunjungan::deleteAjax/$1');
        });
    });

    $routes->group('asesmen', ['filter' => 'role:superadmin,perawat'], function ($routes) {
        $routes->get('/', 'Asesmen::index');
        $routes->get('getData', 'Asesmen::getData');
        $routes->get('getKunjunganList', 'Asesmen::getKunjunganList');
        $routes->get('getOne/(:num)', 'Asesmen::getOne/$1');
        $routes->post('save', 'Asesmen::save');
        $routes->delete('deleteAjax/(:num)', 'Asesmen::deleteAjax/$1');
    });

});