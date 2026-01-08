<?= $this->extend('layout/main'); ?>
<?= $this->section('content'); ?>

<div class="d-flex justify-content-between mb-3">
    <h3>Data Asesmen Pasien</h3>
    <button class="btn btn-primary" id="btnTambah">
        <i class="fas fa-plus"></i> Tambah Asesmen
    </button>
</div>

<div class="card">
    <div class="card-body">
        <table id="tableAsesmen" class="table table-bordered table-striped table-hover w-100">
            <thead class="table-dark">
                <tr>
                    <th>No</th>
                    <th>Tanggal</th>
                    <th>No Registrasi</th>
                    <th>Pasien</th>
                    <th>Keluhan Utama</th>
                    <th width="220">Aksi</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>
</div>

<div class="modal fade" id="modalForm" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTitle">Tambah Data Asesmen</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="formAsesmen">
                <div class="modal-body">
                    <input type="hidden" name="id" id="id">

                    <div class="mb-3">
                        <label class="form-label">Data Kunjungan <span class="text-danger">*</span></label>
                        <select name="kunjunganid" id="kunjunganid" class="form-select">
                            <option value="">-- Pilih Kunjungan --</option>
                        </select>
                        <small class="text-muted">Pasien yang sudah terdaftar di Kunjungan</small>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Keluhan Utama <span class="text-danger">*</span></label>
                        <textarea name="keluhan_utama" id="keluhan_utama" class="form-control" rows="3"></textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Keluhan Tambahan</label>
                        <textarea name="keluhan_tambahan" id="keluhan_tambahan" class="form-control"
                            rows="3"></textarea>
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
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Detail Asesmen</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="printArea">
                <div class="text-center mb-4">
                    <h3>LEMBAR ASESMEN PASIEN</h3>
                    <hr>
                </div>

                <div class="row mb-3">
                    <div class="col-6">
                        <table class="table table-borderless table-sm">
                            <tr>
                                <td width="130px">No. Registrasi</td>
                                <td>: <strong id="detNoReg"></strong></td>
                            </tr>
                            <tr>
                                <td>Tgl. Periksa</td>
                                <td>: <span id="detTgl"></span></td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-6">
                        <table class="table table-borderless table-sm">
                            <tr>
                                <td width="100px">No. RM</td>
                                <td>: <strong id="detNorm"></strong></td>
                            </tr>
                            <tr>
                                <td>Nama Pasien</td>
                                <td>: <span id="detNama"></span></td>
                            </tr>
                        </table>
                    </div>
                </div>

                <div class="card mb-3">
                    <div class="card-header bg-light"><strong>Anamnesa (Keluhan)</strong></div>
                    <div class="card-body">
                        <p class="mb-1"><strong>Keluhan Utama:</strong> <span id="detUtama"></span></p>
                        <p class="mb-0"><strong>Keluhan Tambahan:</strong> <span id="detTambahan"></span></p>
                    </div>
                </div>

                <div class="mt-5 text-end">
                    <p>Pemeriksa,</p>
                    <br><br><br>
                    <p>( ..................................... )</p>
                </div>
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
        // Kita tidak perlu memfilter JS berdasarkan role lagi karena Perawat boleh CRUD

        const table = $('#tableAsesmen').DataTable({
            ajax: {
                url: '/asesmen/getData',
                dataSrc: 'data'
            },
            columns: [
                { data: null, render: (d, t, r, m) => m.row + 1 },
                { data: 'tglkunjungan' },
                { data: 'noregistrasi', render: d => `<span class="badge bg-primary">${d}</span>` },
                {
                    data: null,
                    render: d => `<b>${d.nama_pasien}</b><br><small class="text-muted">${d.norm}</small>`
                },
                { data: 'keluhan_utama' },
                {
                    data: null,
                    render: d => {
                        // REVISI: Pembatasan if (userRole !== 'perawat') DIHAPUS
                        // Agar tombol Edit dan Hapus muncul untuk semua yang punya akses halaman ini
                        return `
                        <button class="btn btn-info btn-sm btnDetail"
                            data-noreg="${d.noregistrasi}"
                            data-tgl="${d.tglkunjungan}"
                            data-nama="${d.nama_pasien}"
                            data-norm="${d.norm}"
                            data-utama="${d.keluhan_utama}"
                            data-tambahan="${d.keluhan_tambahan ?? '-'}"
                        >
                            <i class="fas fa-eye"></i> Detail
                        </button>
                        <button class="btn btn-warning btn-sm btnEdit" data-id="${d.id}">
                            <i class="fas fa-edit"></i> Edit
                        </button>
                        <button class="btn btn-danger btn-sm btnHapus" data-id="${d.id}">
                            <i class="fas fa-trash"></i> Hapus
                        </button>
                        `;
                    }
                }
            ],
            order: [[0, 'desc']],
            language: {
                url: "<?= base_url('assets/datatables/id.json') ?>"
            }

        });

        function loadKunjungan(selected = null) {
            $.getJSON('/asesmen/getKunjunganList', function (res) {
                let opt = '<option value="">-- Pilih Kunjungan --</option>';
                res.forEach(v => {
                    opt += `<option value="${v.id}" ${v.id == selected ? 'selected' : ''}>
                        ${v.noregistrasi} - ${v.nama}
                    </option>`;
                });
                $('#kunjunganid').html(opt);
            });
        }

        $('#btnTambah').click(function () {
            $('#modalTitle').text('Tambah Data Asesmen');
            $('#formAsesmen')[0].reset();
            $('#id').val('');
            loadKunjungan();
            $('#modalForm').modal('show');
        });

        $(document).on('click', '.btnEdit', function () {
            const id = $(this).data('id');
            $.getJSON(`/asesmen/getOne/${id}`, function (d) {
                $('#modalTitle').text('Edit Data Asesmen');
                $('#id').val(d.id);
                $('#keluhan_utama').val(d.keluhan_utama);
                $('#keluhan_tambahan').val(d.keluhan_tambahan);

                loadKunjungan(d.kunjunganid);
                $('#modalForm').modal('show');
            });
        });

        $('#formAsesmen').submit(function (e) {
            e.preventDefault();
            if (!$('#kunjunganid').val() || !$('#keluhan_utama').val()) {
                Swal.fire('Validasi Gagal', 'Data Kunjungan dan Keluhan Utama wajib diisi!', 'error');
                return;
            }
            $.post('/asesmen/save', $(this).serialize(), function (res) {
                if (res.status === 'success') {
                    Swal.fire('Berhasil', res.message, 'success');
                    $('#modalForm').modal('hide');
                    table.ajax.reload();
                } else {
                    Swal.fire('Gagal', Object.values(res.errors).join('<br>'), 'error');
                }
            }, 'json');
        });

        $(document).on('click', '.btnHapus', function () {
            const id = $(this).data('id');
            Swal.fire({
                title: 'Konfirmasi Hapus',
                text: 'Data rekam medis ini akan dihapus permanen!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                confirmButtonText: 'Ya, Hapus!'
            }).then(r => {
                if (r.isConfirmed) {
                    $.ajax({
                        url: `/asesmen/deleteAjax/${id}`,
                        type: 'DELETE',
                        success: () => {
                            Swal.fire('Terhapus', 'Data berhasil dihapus', 'success');
                            table.ajax.reload();
                        }
                    });
                }
            });
        });

        $(document).on('click', '.btnDetail', function () {
            $('#detNoReg').text($(this).data('noreg'));
            $('#detTgl').text($(this).data('tgl'));
            $('#detNama').text($(this).data('nama'));
            $('#detNorm').text($(this).data('norm'));

            $('#detUtama').text($(this).data('utama'));
            $('#detTambahan').text($(this).data('tambahan'));

            $('#modalDetail').modal('show');
        });
    });

    function printDetail() {
        const content = document.getElementById('printArea').innerHTML;
        const original = document.body.innerHTML;
        document.body.innerHTML = content;
        window.print();
        document.body.innerHTML = original;
        location.reload();
    }
</script>

<?= $this->endSection(); ?>