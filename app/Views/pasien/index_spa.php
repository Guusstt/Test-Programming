<?= $this->extend('layout/main'); ?>

<?= $this->section('content'); ?>

<div class="d-flex justify-content-between mb-3">
    <h3>Data Pasien</h3>
    <div>
        <button class="btn btn-primary" id="btnTambah">
            <i class="fas fa-plus"></i> Tambah Pasien
        </button>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <table id="tablePasien" class="table table-bordered table-striped table-hover">
            <thead class="table-dark">
                <tr>
                    <th>No</th>
                    <th>No RM</th>
                    <th>Nama Pasien</th>
                    <th>Alamat</th>
                    <th width="200px">Aksi</th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>
</div>

<div class="modal fade" id="modalForm" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTitle">Tambah Data Pasien</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="formPasien">
                <div class="modal-body">
                    <input type="hidden" name="id" id="id">

                    <div class="mb-3">
                        <label class="form-label">No. Rekam Medis (NoRM) <span class="text-danger">*</span></label>
                        <input type="text" name="norm" id="norm" class="form-control" placeholder="Contoh: RM-001">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                        <input type="text" name="nama" id="nama" class="form-control">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Alamat</label>
                        <textarea name="alamat" id="alamat" class="form-control" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="modalDetail" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Detail Pasien</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="printArea">
                <table class="table table-bordered">
                    <tr>
                        <th width="40%">No. Rekam Medis</th>
                        <td id="detailNorm"></td>
                    </tr>
                    <tr>
                        <th>Nama Lengkap</th>
                        <td id="detailNama"></td>
                    </tr>
                    <tr>
                        <th>Alamat</th>
                        <td id="detailAlamat"></td>
                    </tr>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                <button type="button" class="btn btn-primary" onclick="printDetail()">
                    <i class="fas fa-print"></i> Cetak
                </button>
            </div>
        </div>
    </div>
</div>

<link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    $(document).ready(function () {
        let table;

        function initDataTable() {
            table = $('#tablePasien').DataTable({
                ajax: {
                    url: '/pasien/getData',
                    dataSrc: 'data'
                },
                columns: [
                    {
                        data: null,
                        render: function (data, type, row, meta) {
                            return meta.row + 1;
                        }
                    },
                    { data: 'norm' },
                    { data: 'nama' },
                    { data: 'alamat' },
                    {
                        data: null,
                        render: function (data) {
                            return `
                            <button class="btn btn-info btn-sm btnDetail" data-id="${data.id}">
                                <i class="fas fa-eye"></i> Detail
                            </button>
                            <button class="btn btn-warning btn-sm btnEdit" data-id="${data.id}">
                                <i class="fas fa-edit"></i> Edit
                            </button>
                            <button class="btn btn-danger btn-sm btnHapus" data-id="${data.id}">
                                <i class="fas fa-trash"></i> Hapus
                            </button>
                        `;
                        }
                    }
                ],
                language: {
                    url: "<?= base_url('assets/datatables/id.json') ?>"
                }

            });
        }

        initDataTable();

        $('#btnTambah').click(function () {
            $('#modalTitle').text('Tambah Data Pasien');
            $('#formPasien')[0].reset();
            $('#id').val('');
            $('#modalForm').modal('show');
        });

        $(document).on('click', '.btnEdit', function () {
            const id = $(this).data('id');
            $.ajax({
                url: `/pasien/getOne/${id}`,
                type: 'GET',
                dataType: 'json',
                success: function (data) {
                    $('#modalTitle').text('Edit Data Pasien');
                    $('#id').val(data.id);
                    $('#norm').val(data.norm);
                    $('#nama').val(data.nama);
                    $('#alamat').val(data.alamat);
                    $('#modalForm').modal('show');
                }
            });
        });

        $('#formPasien').submit(function (e) {
            e.preventDefault();

            if (!$('#nama').val() || !$('#norm').val()) {
                Swal.fire({
                    icon: 'error',
                    title: 'Validasi Gagal',
                    text: 'Nama dan No RM wajib diisi!',
                });
                return false;
            }

            $.ajax({
                url: '/pasien/save',
                type: 'POST',
                data: $(this).serialize(),
                dataType: 'json',
                success: function (response) {
                    if (response.status === 'success') {
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil',
                            text: response.message,
                            timer: 2000
                        });
                        $('#modalForm').modal('hide');
                        table.ajax.reload();
                    } else {
                        let errorMsg = '';
                        for (let key in response.errors) {
                            errorMsg += response.errors[key] + '\n';
                        }
                        Swal.fire({
                            icon: 'error',
                            title: 'Validasi Gagal',
                            text: errorMsg,
                        });
                    }
                },
                error: function () {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Terjadi kesalahan saat menyimpan data',
                    });
                }
            });
        });

        $(document).on('click', '.btnHapus', function () {
            const id = $(this).data('id');
            Swal.fire({
                title: 'Konfirmasi',
                text: "Yakin ingin menghapus data ini?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: `/pasien/deleteAjax/${id}`,
                        type: 'DELETE',
                        dataType: 'json',
                        success: function (response) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Terhapus!',
                                text: response.message,
                                timer: 2000
                            });
                            table.ajax.reload();
                        },
                        error: function () {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: 'Gagal menghapus data',
                            });
                        }
                    });
                }
            });
        });

        $('#btnImport').click(function () {
            Swal.fire({
                title: 'Import Data Dummy',
                text: "Import 10 data pasien dari JSONPlaceholder?",
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Ya, Import!',
                cancelButtonText: 'Batal',
                showLoaderOnConfirm: true,
                preConfirm: () => {
                    return $.ajax({
                        url: '/pasien/import',
                        type: 'POST',
                        dataType: 'json'
                    });
                },
                allowOutsideClick: () => !Swal.isLoading()
            }).then((result) => {
                if (result.isConfirmed) {
                    if (result.value.status === 'success') {
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil!',
                            text: result.value.message,
                        });
                        table.ajax.reload();
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal Import',
                            text: result.value.message,
                        });
                    }
                }
            });
        });

        $(document).on('click', '.btnDetail', function () {
            const id = $(this).data('id');
            $.ajax({
                url: `/pasien/detail/${id}`,
                type: 'GET',
                dataType: 'json',
                success: function (data) {
                    $('#detailNorm').text(data.norm);
                    $('#detailNama').text(data.nama);
                    $('#detailAlamat').text(data.alamat || '-');
                    $('#modalDetail').modal('show');
                }
            });
        });
    });

    function printDetail() {
        const printContents = document.getElementById('printArea').innerHTML;
        const originalContents = document.body.innerHTML;
        document.body.innerHTML = printContents;
        window.print();
        document.body.innerHTML = originalContents;
        location.reload();
    }
</script>

<?= $this->endSection(); ?>