<?= $this->extend('layout/main'); ?>

<?= $this->section('content'); ?>

<div class="d-flex justify-content-between mb-3">
    <h3>Riwayat Pendaftaran</h3>
    <?php if (session()->get('role') != 'perawat'): ?>
        <button class="btn btn-primary" id="btnDaftar">
            <i class="fas fa-plus"></i> Daftar Baru
        </button>
    <?php endif; ?>
</div>

<div class="card">
    <div class="card-body">
        <table id="tablePendaftaran" class="table table-bordered table-striped table-hover">
            <thead class="table-dark">
                <tr>
                    <th>No Reg</th>
                    <th>Tgl Daftar</th>
                    <th>No RM</th>
                    <th>Nama Pasien</th>
                    <th width="220px">Aksi</th>
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
                <h5 class="modal-title" id="modalTitle">Registrasi Pasien Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="formPendaftaran">
                <div class="modal-body">
                    <input type="hidden" name="id" id="id">

                    <div class="mb-3" id="noregContainer" style="display:none;">
                        <label class="form-label">Nomor Registrasi</label>
                        <input type="text" id="noregistrasi" class="form-control" readonly disabled>
                        <small class="text-muted">Nomor registrasi tidak dapat diubah</small>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Pilih Pasien <span class="text-danger">*</span></label>
                        <select name="pasienid" id="pasienid" class="form-select">
                            <option value="">-- Pilih Nama Pasien --</option>
                        </select>
                        <small class="text-muted">Pasien belum ada? <a href="/pasien" target="_blank">Input Pasien Baru
                                dulu</a></small>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Tanggal Registrasi <span class="text-danger">*</span></label>
                        <input type="date" name="tglregistrasi" id="tglregistrasi" class="form-control">
                    </div>

                    <div class="alert alert-info" id="infoGenerate" style="display:none;">
                        <small><i class="fas fa-info-circle"></i> Nomor Registrasi akan digenerate otomatis oleh
                            sistem.</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Proses Pendaftaran</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="modalDetail" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Detail Pendaftaran</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="printArea">
                <div class="text-center mb-3">
                    <h4>BUKTI PENDAFTARAN PASIEN</h4>
                </div>
                <table class="table table-bordered">
                    <tr>
                        <th width="40%">No. Registrasi</th>
                        <td id="detailNoReg"></td>
                    </tr>
                    <tr>
                        <th>Tanggal Registrasi</th>
                        <td id="detailTglReg"></td>
                    </tr>
                    <tr>
                        <th>No. Rekam Medis</th>
                        <td id="detailNorm"></td>
                    </tr>
                    <tr>
                        <th>Nama Pasien</th>
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
        const userRole = '<?= session()->get('role') ?>';

        function initDataTable() {
            table = $('#tablePendaftaran').DataTable({
                ajax: {
                    url: '/pendaftaran/getData',
                    dataSrc: 'data'
                },
                columns: [
                    {
                        data: 'noregistrasi',
                        render: function (data) {
                            return '<b>' + data + '</b>';
                        }
                    },
                    { data: 'tglregistrasi' },
                    {
                        data: 'norm',
                        render: function (data) {
                            return '<span class="badge bg-secondary">' + data + '</span>';
                        }
                    },
                    { data: 'nama' },
                    {
                        data: null,
                        render: function (data) {
                            let buttons = `
                            <button class="btn btn-info btn-sm btnDetail" data-id="${data.id}">
                                <i class="fas fa-eye"></i> Detail
                            </button>
                        `;

                            if (userRole !== 'perawat') {
                                buttons += `
                                <button class="btn btn-warning btn-sm btnEdit" data-id="${data.id}">
                                    <i class="fas fa-edit"></i> Edit
                                </button>
                                <button class="btn btn-danger btn-sm btnBatal" data-id="${data.id}">
                                    <i class="fas fa-times"></i> Batal
                                </button>
                            `;
                            }

                            return buttons;
                        }
                    }
                ],
                language: {
                    url: "<?= base_url('assets/datatables/id.json') ?>"
                },

                order: [[0, 'desc']]
            });
        }

        initDataTable();

        function loadPasien() {
            $.ajax({
                url: '/pendaftaran/getPasien',
                type: 'GET',
                dataType: 'json',
                success: function (data) {
                    let options = '<option value="">-- Pilih Nama Pasien --</option>';
                    data.forEach(function (pasien) {
                        options += `<option value="${pasien.id}">${pasien.norm} - ${pasien.nama}</option>`;
                    });
                    $('#pasienid').html(options);
                }
            });
        }

        $('#btnDaftar').click(function () {
            $('#modalTitle').text('Registrasi Pasien Baru');
            $('#formPendaftaran')[0].reset();
            $('#id').val('');
            $('#noregContainer').hide();
            $('#infoGenerate').show();
            $('#tglregistrasi').val(new Date().toISOString().split('T')[0]);
            loadPasien();
            $('#modalForm').modal('show');
        });

        $(document).on('click', '.btnEdit', function () {
            const id = $(this).data('id');

            $.ajax({
                url: `/pendaftaran/getOne/${id}`,
                type: 'GET',
                dataType: 'json',
                success: function (data) {
                    $('#modalTitle').text('Edit Data Pendaftaran');
                    $('#id').val(data.id);
                    $('#noregistrasi').val(data.noregistrasi);
                    $('#noregContainer').show();
                    $('#infoGenerate').hide();
                    $('#pasienid').val(data.pasienid);
                    $('#tglregistrasi').val(data.tglregistrasi);

                    loadPasien();
                    setTimeout(function () {
                        $('#pasienid').val(data.pasienid);
                    }, 200);

                    $('#modalForm').modal('show');
                }
            });
        });

        $('#formPendaftaran').submit(function (e) {
            e.preventDefault();

            if (!$('#pasienid').val() || !$('#tglregistrasi').val()) {
                Swal.fire({
                    icon: 'error',
                    title: 'Validasi Gagal',
                    text: 'Pasien dan Tanggal Registrasi wajib diisi!',
                });
                return false;
            }

            $.ajax({
                url: '/pendaftaran/save',
                type: 'POST',
                data: $(this).serialize(),
                dataType: 'json',
                success: function (response) {
                    if (response.status === 'success') {
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil',
                            text: response.message,
                            timer: 3000
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

        $(document).on('click', '.btnBatal', function () {
            const id = $(this).data('id');
            Swal.fire({
                title: 'Konfirmasi',
                text: "Yakin ingin membatalkan pendaftaran ini?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, Batalkan!',
                cancelButtonText: 'Tidak'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: `/pendaftaran/deleteAjax/${id}`,
                        type: 'DELETE',
                        dataType: 'json',
                        success: function (response) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Dibatalkan!',
                                text: response.message,
                                timer: 2000
                            });
                            table.ajax.reload();
                        },
                        error: function () {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: 'Gagal membatalkan pendaftaran',
                            });
                        }
                    });
                }
            });
        });

        $(document).on('click', '.btnDetail', function () {
            const id = $(this).data('id');
            $.ajax({
                url: `/pendaftaran/detail/${id}`,
                type: 'GET',
                dataType: 'json',
                success: function (data) {
                    $('#detailNoReg').text(data.noregistrasi);
                    $('#detailTglReg').text(data.tglregistrasi);
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