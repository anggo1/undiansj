<?php

$this->load->view('admin/komponen/header');
$this->load->view('admin/komponen/sidebar');
$this->load->view('admin/komponen/navbar');

?>

<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Page Heading -->
    <h1 class="h3 mb-2 text-gray-800">Data Tamu</h1>
    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <div class="col-sm-12 row">
                <div class="col-sm-3">
                    <h6 class="m-0 font-weight-bold text-primary">Data Tamu</h6>
                </div>
                <div class="col-sm-9" align="right">
                    <!-- Tombol Cetak Baru -->
                    <button type="button" class="btn btn-warning" data-toggle="modal" data-target="#modalCetakUndian"><i class="fa fa-print"></i> Cetak Kupon</button>
                    <a href="<?= base_url('Admin/export') ?>" class="btn btn-primary"><i class="fa fa-file-excel"></i> Export Excel</a>
                    <a href="#" class="btn btn-success" data-toggle="modal" data-target="#exampleModal"><i
                            class="fa fa-plus-circle"></i> Import Data (Excel)</a>
                    <a href="" class="btn btn-danger" data-toggle="modal" data-target="#exampleModaldelete"><i
                            class="fa fa-trash"></i> Reset Semua Data</a>
                </div>
            </div>
        </div>
        <div class="card-body">
            <?= $this->session->flashdata('success'); ?>
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Kupon</th>
                            <th>NIK</th>
                            <th>Nama</th>
                            <th>Departemen</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no=1; foreach ($tamu as $t) { ?>
                        <tr>
                            <td><?= $no++?></td>
                            <td><?= $t->id?></td>
                            <td><?= $t->nik?></td>
                            <td><?= $t->name?></td>
                            <td><?= $t->department?></td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>
<!-- /.container-fluid -->
<!-- Modal Cetak Peserta Undian Berdasarkan NIK / Nama -->
<div class="modal fade" id="modalCetakUndian" tabindex="-1" aria-labelledby="modalCetakLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalCetakLabel"><i class="fa fa-print"></i> Cetak Kupon Peserta Undian</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="inputCariTamu" class="font-weight-bold">Masukkan NIK atau Nama Peserta:</label>
                    <input type="text" class="form-control" id="inputCariTamu" placeholder="Contoh: 6801013 atau Andi" autocomplete="off">
                    <small class="form-text text-muted">Sistem akan otomatis mencocokkan data berdasarkan nomor NIK atau nama yang Anda ketik.</small>
                </div>
                
                <!-- Hasil List Data Berdasarkan Pencarian -->
                <div id="areaListKupon" style="display:none; margin-top: 15px;">
                    <h6 class="font-weight-bold text-secondary">Daftar Kupon Ditemukan:</h6>
                    <div class="table-responsive">
                        <table class="table table-sm table-bordered bg-light">
                            <thead class="thead-dark">
                                <tr>
                                    <th>No. Kupon</th>
                                    <th>NIK</th>
                                    <th>Nama</th>
                                    <th>Departemen</th>
                                </tr>
                            </thead>
                            <tbody id="listKuponBody">
                                <!-- Data list akan di-generate otomatis oleh jQuery -->
                            </tbody>
                        </table>
                    </div>
                </div>
                
                <div id="alertError" class="alert alert-danger" style="display:none; margin-top: 15px;">
                    <i class="fa fa-exclamation-triangle"></i> Data NIK atau Nama tidak ditemukan dalam data master!
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                <button type="button" class="btn btn-primary" id="btnProsesCetak" disabled><i class="fa fa-print"></i> Cetak Semua Kupon</button>
            </div>
        </div>
    </div>
</div>



<!-- Modal Import -->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Import Data Tamu ( Excel )</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <?= form_open_multipart('excel/import');?>
                <div class="form-group">
                    <label for="exampleInputFile">File Excel</label>
                    <input type="file" class="form-control" id="exampleInputFile" name="excel">
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-primary">Import</button>
                <? form_close();?>
            </div>
        </div>
    </div>
</div>

<!-- Modal Delete -->
<div class="modal fade" id="exampleModaldelete" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Reset Data Tamu</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

                <h3>Apakah ingin mereset semua data tamu ?</h3>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                <a href="<?= base_url()?>excel/reset" class="btn btn-danger">Ya</a>

            </div>
        </div>
    </div>
</div>

<?php $this->load->view('admin/komponen/footer'); ?>

<!-- Script Tambahan Untuk Logika Pencarian & Cetak Kupon -->
<!-- Script Tambahan Yang Diperbaiki Untuk Pencarian Lintas Halaman DataTables -->
<script>
$(document).ready(function() {
    // Event pengetikan pada kolom input pencarian di modal
    $('#inputCariTamu').on('keyup change', function() {
        var keyword = $(this).val().trim().toLowerCase();
        
        if (keyword === "") {
            $('#areaListKupon').hide();
            $('#listKuponBody').empty();
            $('#alertError').hide();
            $('#btnProsesCetak').attr('disabled', true);
            return;
        }

        var listKuponHTML = "";
        var ditemukan = false;
        
        // Ambil instance DataTables
        var table = $('#dataTable').DataTable();
        
        // Scan data internal cache memori DataTables (Pasti tembus walau beda halaman pagination)
        table.rows().every(function(rowIdx, tableLoop, rowLoop) {
            var dataBaris = this.data(); 
            
            // Penjelasan isi array index DataTables:
            // dataBaris[0] = No / No Kupon
            // dataBaris[1] = Kupon
            // dataBaris[2] = NIK
            // dataBaris[3] = Nama
            // dataBaris[4] = Departemen
            
            // Hilangkan spasi dan ubah ke text mentah untuk memastikan tidak terganggu tag HTML
            var cellNoKupon = $('<div>').html(dataBaris[1]).text().trim();
            var cellNik     = $('<div>').html(dataBaris[2]).text().trim();
            var cellNama    = $('<div>').html(dataBaris[3]).text().trim();
            var cellDept    = $('<div>').html(dataBaris[4]).text().trim();
            
            // Validasi pencarian (Mencocokkan NIK atau Nama secara presisi/parsial)
            if (cellNik.toLowerCase().includes(keyword) || cellNama.toLowerCase().includes(keyword)) {
                
                // Tambahkan baris baru ke dalam list tabel modal
                listKuponHTML += `<tr>
                    <td class="font-weight-bold text-primary target-no-kupon">${cellNoKupon}</td>
                    <td class="target-nik">${cellNik}</td>
                    <td class="target-nama">${cellNama}</td>
                    <td class="target-dept">${cellDept}</td>
                </tr>`;
                
                ditemukan = true;
            }
        });

        if (ditemukan) {
            $('#listKuponBody').html(listKuponHTML);
            $('#areaListKupon').show();
            $('#alertError').hide();
            $('#btnProsesCetak').removeAttr('disabled');
        } else {
            $('#areaListKupon').hide();
            $('#listKuponBody').empty();
            $('#alertError').show();
            $('#btnProsesCetak').attr('disabled', true);
        }
    });

    // Reset isi modal saat modal ditutup
    $('#modalCetakUndian').on('hidden.bs.modal', function () {
        $('#inputCariTamu').val('');
        $('#areaListKupon').hide();
        $('#listKuponBody').empty();
        $('#alertError').hide();
        $('#btnProsesCetak').attr('disabled', true);
    });

    // Aksi Cetak Jendela Window Browser (Tampilan Gabung dengan Kotak Nomor & Total Kupon)
    $('#btnProsesCetak').click(function() {
        var w = window.open('', '_blank', 'width=750,height=600');
        
        // Ambil data profil dari baris pertama list di modal
        var firstRow = $('#listKuponBody tr').first();
        var nik = firstRow.find('.target-nik').text();
        var nama = firstRow.find('.target-nama').text();
        var dept = firstRow.find('.target-dept').text();
        
        // Hitung total kupon dan kumpulkan HTML kotak-kotaknya
        var totalKupon = $('#listKuponBody tr').length;
        var htmlKotakKupon = '';
        
        $('#listKuponBody tr').each(function() {
            var noKupon = $(this).find('.target-no-kupon').text();
            htmlKotakKupon += `<div class="kupon-box-item">${noKupon}</div>`;
        });

        w.document.write('<html><head><title>Cetak Kupon Undian</title>');
        w.document.write('<style>');
        w.document.write('body { font-family: Arial, sans-serif; padding: 20px; color: #000; }');
        w.document.write('.kupon-card { border: 3px dashed #000; padding: 25px; border-radius: 4px; position: relative; }');
        w.document.write('.kupon-header { text-align: center; border-bottom: 2px solid #000; padding-bottom: 8px; margin-bottom: 15px; }');
        w.document.write('table { width: 100%; border-collapse: collapse; font-size: 16px; margin-bottom: 15px; }');
        w.document.write('td { padding: 6px 0; vertical-align: top; }');
        w.document.write('.badge-total { position: absolute; top: 25px; right: 25px; background: #007bff; color: #fff; padding: 5px 12px; font-weight: bold; border-radius: 20px; font-size: 14px; }');
        w.document.write('.kupon-grid-container { display: flex; flex-wrap: wrap; gap: 8px; margin-top: 5px; }');
        w.document.write('.kupon-box-item { background-color: #f2f2f2; border: 2px solid #333; padding: 8px 15px; font-size: 18px; font-weight: bold; color: #111; border-radius: 4px; min-width: 50px; text-align: center; box-shadow: 1px 1px 2px rgba(0,0,0,0.1); }');
        w.document.write('.kupon-footer { margin-top: 25px; text-align: center; font-size: 11px; font-style: italic; color: #555; border-top: 1px dashed #ccc; padding-top: 8px; }');
        w.document.write('</style></head><body>');
        
        var templateKupon = `
            <div class="kupon-card">
                <div>Total: ${totalKupon} Kupon</div>
                <div class="kupon-header">
                    <h2 style="margin: 0; font-weight: bold; letter-spacing: 1px;">KUPON UNDIAN DOORPRIZE SINARJAYA GROUP</h2>
                    <small>DAFTAR HAK UNDIAN RESMI PESERTA</small>
                </div>
                <table>
                    <tr>
                        <td width="25%"><strong>NIK</strong></td>
                        <td width="3%">:</td>
                        <td>${nik}</td>
                    </tr>
                    <tr>
                        <td><strong>Nama Peserta</strong></td>
                        <td>:</td>
                        <td><strong>${nama}</strong></td>
                    </tr>
                    <tr>
                        <td><strong>Departemen</strong></td>
                        <td>:</td>
                        <td>${dept}</td>
                    </tr>
                    <tr>
                        <td><strong>Nomor Undian</strong></td>
                        <td>:</td>
                        <td>
                            <div class="kupon-grid-container">
                                ${htmlKotakKupon}
                            </div>
                        </td>
                    </tr>
                </table>
                <div>
                    * Lembar ini sah digunakan sebagai bukti klaim pemenang untuk seluruh nomor undian yang tertera di atas.
                </div>
            </div>
        `;
        
        w.document.write(templateKupon);
        w.document.write('</body></html>');
        w.document.close();
        w.focus();
        
        setTimeout(function () {
            w.print();
            w.close();
        }, 500);
    });
});
</script>
