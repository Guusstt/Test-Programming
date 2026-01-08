<?= $this->extend('layout/main'); ?>
<?= $this->section('content'); ?>

<div class="d-flex justify-content-between mb-3">
    <h3>Data Kunjungan Pasien</h3>
    <?php if (session()->get('role') != 'perawat'): ?>
        <button class="btn btn-primary" id="btnTambah">
            <i class="fas fa-plus"></i> Tambah Kunjungan
        </button>
    <?php endif; ?>
</div>

<div class="card">
    <div class="card-body">
        <table id="tableKunjungan" class="table table-bordered table-striped table-hover w-100">
            <thead class="table-dark">
                <tr>
                    <th>No</th>
                    <th>Tanggal</th>
                    <th>No Registrasi</th>
                    <th>Pasien</th>
                    <th>Jenis Kunjungan</th>
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
                <h5 class="modal-title" id="modalTitle">Tambah Data Kunjungan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <form id="formKunjungan">
                <div class="modal-body">
                    <input type="hidden" name="id" id="id">

                    <div class="mb-3">
                        <label class="form-label">
                            Data Pendaftaran <span class="text-danger">*</span>
                        </label>
                        <select name="pendaftaranpasienid" id="pendaftaranpasienid" class="form-select">
                            <option value="">-- Pilih Data --</option>
                        </select>
                        <small class="text-muted">Cari berdasarkan No Registrasi / Nama</small>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">
                            Jenis Kunjungan <span class="text-danger">*</span>
                        </label>
                        <select name="jeniskunjungan" id="jeniskunjungan" class="form-select">
                            <option value="">-- Pilih Jenis --</option>
                            <option value="Rawat Jalan">Rawat Jalan</option>
                            <option value="UGD">UGD</option>
                            <option value="Konsultasi">Konsultasi</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">
                            Tanggal Kunjungan <span class="text-danger">*</span>
                        </label>
                        <input type="date" name="tglkunjungan" id="tglkunjungan" class="form-control">
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        Batal
                    </button>
                    <button type="submit" class="btn btn-primary">
                        Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="modalDetail" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Detail Kunjungan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body" id="printArea">
                <div class="text-center mb-3">
                    <h4>BUKTI KUNJUNGAN PASIEN</h4>
                    <hr>
                </div>

                <table class="table table-bordered">
                    <tr>
                        <th width="40%">No Registrasi</th>
                        <td id="detailNoReg"></td>
                    </tr>
                    <tr>
                        <th>Nama Pasien</th>
                        <td id="detailNama"></td>
                    </tr>
                    <tr>
                        <th>No RM</th>
                        <td id="detailNorm"></td>
                    </tr>
                    <tr>
                        <th>Jenis Kunjungan</th>
                        <td id="detailJenis"></td>
                    </tr>
                    <tr>
                        <th>Tanggal Kunjungan</th>
                        <td id="detailTgl"></td>
                    </tr>
                </table>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    Tutup
                </button>
                <button type="button" class="btn btn-primary" onclick="printDetail()">
                    <i class="fas fa-print"></i> Cetak
                </button>
            </div>
        </div>
    </div>
</div>

<!-- LIB -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    $(document).ready(function () {

        const userRole = '<?= session()->get('role') ?>';

        const table = $('#tableKunjungan').DataTable({
            ajax: {
                url: '/kunjungan/getData',
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
                { data: 'jeniskunjungan' },
                {
                    data: null,
                    render: d => {
                        let btn = `
                        <button class="btn btn-info btn-sm btnDetail"
                            data-noreg="${d.noregistrasi}"
                            data-nama="${d.nama_pasien}"
                            data-norm="${d.norm}"
                            data-jenis="${d.jeniskunjungan}"
                            data-tgl="${d.tglkunjungan}">
                            <i class="fas fa-eye"></i> Detail
                        </button>
                    `;
                        if (userRole !== 'perawat') {
                            btn += `
                            <button class="btn btn-warning btn-sm btnEdit" data-id="${d.id}">
                                <i class="fas fa-edit"></i> Edit
                            </button>
                            <button class="btn btn-danger btn-sm btnHapus" data-id="${d.id}">
                                <i class="fas fa-trash"></i> Hapus
                            </button>
                        `;
                        }
                        return btn;
                    }
                }
            ],
            language: {
                url: "<?= base_url('assets/datatables/id.json') ?>"
            }

        });

        function loadPendaftaran(selected = null) {
            $.getJSON('/kunjungan/getPendaftaranList', function (res) {
                let opt = '<option value="">-- Pilih Data --</option>';
                res.forEach(v => {
                    opt += `<option value="${v.id}" ${v.id == selected ? 'selected' : ''}>
                    ${v.noregistrasi} - ${v.nama}
                </option>`;
                });
                $('#pendaftaranpasienid').html(opt);
            });
        }

        $('#btnTambah').click(function () {
            $('#modalTitle').text('Tambah Data Kunjungan');
            $('#formKunjungan')[0].reset();
            $('#id').val('');
            $('#tglkunjungan').val(new Date().toISOString().split('T')[0]);
            loadPendaftaran();
            $('#modalForm').modal('show');
        });

        $(document).on('click', '.btnEdit', function () {
            const id = $(this).data('id');
            $.getJSON(`/kunjungan/getOne/${id}`, function (d) {
                $('#modalTitle').text('Edit Data Kunjungan');
                $('#id').val(d.id);
                $('#jeniskunjungan').val(d.jeniskunjungan);
                $('#tglkunjungan').val(d.tglkunjungan);
                loadPendaftaran(d.pendaftaranpasienid);
                $('#modalForm').modal('show');
            });
        });

        $('#formKunjungan').submit(function (e) {
            e.preventDefault();

            if (!$('#pendaftaranpasienid').val() || !$('#jeniskunjungan').val() || !$('#tglkunjungan').val()) {
                Swal.fire('Validasi Gagal', 'Semua field wajib diisi!', 'error');
                return;
            }

            $.post('/kunjungan/save', $(this).serialize(), function (res) {
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
                title: 'Konfirmasi',
                text: 'Yakin ingin menghapus data ini?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya, Hapus!'
            }).then(r => {
                if (r.isConfirmed) {
                    $.ajax({
                        url: `/kunjungan/deleteAjax/${id}`,
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
            $('#detailNoReg').text($(this).data('noreg'));
            $('#detailNama').text($(this).data('nama'));
            $('#detailNorm').text($(this).data('norm'));
            $('#detailJenis').text($(this).data('jenis'));
            $('#detailTgl').text($(this).data('tgl'));
            $('#modalDetail').modal('show');
        });

    });

    function printDetail() {
        const c = document.getElementById('printArea').innerHTML;
        document.body.innerHTML = c;
        window.print();
        location.reload();
    }
</script>

<?= $this->endSection(); ?>