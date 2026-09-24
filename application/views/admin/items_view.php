<!-- Tampilan Halaman Utama SB Admin -->
<?php

$this->load->view('admin/komponen/header');
$this->load->view('admin/komponen/sidebar');
$this->load->view('admin/komponen/navbar');

?>
       
<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">Manajemen Hadiah</h1>

    <div class="row">
        <!-- Kolom Kiri: Form Tambah Hadiah -->
        <div class="col-lg-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Tambah Hadiah Baru</h6>
                </div>
                <div class="card-body">
                    <form id="itemForm">
                        <div class="form-group">
                            <label for="itemName">Nama Hadiah</label>
                            <input type="text" id="itemName" name="item_name" class="form-control" maxlength="100" placeholder="Contoh: Laptop" required>
                        </div>
                        <div class="form-group">
                            <label for="itemColor">Warna</label>
                            <input type="color" id="itemColor" name="color" class="form-control" value="#8b5cf6" required>
                        </div>
                        <div class="form-group">
                            <label for="itemStock">Stok</label>
                            <input type="number" id="itemStock" name="stock" class="form-control" value="1" min="0" required>
                        </div>
                        <button type="submit" class="btn btn-primary btn-block" id="addBtn">+ Tambah Hadiah</button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Kolom Kanan: Daftar Hadiah dengan DataTables -->
        <div class="col-lg-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Daftar Hadiah</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" id="dataTableHadiah" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Warna</th>
                                    <th>Nama Hadiah</th>
                                    <th>Stok</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody id="itemTable">
                                <?php if (!empty($items)): foreach ($items as $index => $item): 
                                    $stock = (int)$item->stock; 
                                    $used = isset($item->total_terundi) ? (int)$item->total_terundi : 0; 
                                ?>
                                <tr data-item-id="<?= (int)$item->id ?>"
                                    data-item-name="<?= htmlspecialchars($item->item_name, ENT_QUOTES, 'UTF-8') ?>"
                                    data-item-color="<?= htmlspecialchars($item->color, ENT_QUOTES, 'UTF-8') ?>"
                                    data-item-stock="<?= $stock ?>">
                                    <td><?= $index + 1 ?></td>
                                    <td>
                                        <div style="width: 25px; height: 25px; border-radius: 4px; background-color: <?= htmlspecialchars($item->color, ENT_QUOTES, 'UTF-8') ?>"></div>
                                    </td>
                                    <td class="item-name"><?= htmlspecialchars($item->item_name, ENT_QUOTES, 'UTF-8') ?></td>
                                    <td>
                                        <span class="badge badge-<?= $stock > 0 ? 'success' : 'danger' ?> stock-label">
                                            <?= $stock ?>
                                        </span>
                                    </td>
                                    <td>
                                        <button type="button" class="btn btn-sm btn-warning" data-action="edit">✏️ Edit</button>
                                        <button type="button" class="btn btn-sm btn-danger" data-action="delete" <?= $used > 0 ? 'disabled' : '' ?>>🗑 Hapus</button>
                                    </td>
                                </tr>
                                <?php endforeach; endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Edit (Bootstrap 4 - SB Admin Standard) -->
<div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editModalLabel">Edit Hadiah</h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <form id="editItemForm">
                <div class="modal-body">
                    <input type="hidden" id="editItemId" name="item_id">
                    <div class="form-group">
                        <label for="editItemName">Nama Hadiah</label>
                        <input type="text" id="editItemName" name="item_name" class="form-control" maxlength="100" required>
                    </div>
                    <div class="form-group">
                        <label for="editItemColor">Warna</label>
                        <input type="color" id="editItemColor" name="color" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="editItemStock">Stok</label>
                        <input type="number" id="editItemStock" name="stock" class="form-control" min="0" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Batal</button>
                    <button class="btn btn-primary" type="submit" id="saveEditBtn">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php $this->load->view('admin/komponen/footer'); ?>
<!-- JavaScript Integrasi DataTables + Bootstrap 4 (jQuery bawaan SB Admin) -->
<script>
$(document).ready(function() {
    // 1. Inisialisasi DataTables SB Admin
    const tableHadiah = $('#dataTableHadiah').DataTable();

    const itemForm = document.getElementById('itemForm'),
          addBtn = document.getElementById('addBtn'),
          editForm = document.getElementById('editItemForm'),
          saveEditBtn = document.getElementById('saveEditBtn');

    // Fungsi Escape DOM Text (Anti-Error Escape Sequence)
    const esc = (str) => {
        if (!str) return '';
        const div = document.createElement('div');
        div.appendChild(document.createTextNode(str));
        return div.innerHTML;
    };

    // Fungsi nomor otomatis DataTables
    function renumber() {
        tableHadiah.column(0, {search:'applied', order:'applied'}).nodes().each(function (cell, i) {
            cell.innerHTML = i + 1;
        });
    }

    // 2. Event Delegation Tombol Edit & Delete
    $(document).on('click', 'button[data-action]', function(e) {
        const button = $(this);
        const row = button.closest('tr');
        const action = button.data('action');

        if (action === 'edit') {
            $('#editItemId').val(row.attr('data-item-id'));
            $('#editItemName').val(row.attr('data-item-name'));
            $('#editItemColor').val(row.attr('data-item-color') || '#8b5cf6');
            $('#editItemStock').val(row.attr('data-item-stock'));
            
            // Tampilkan Modal SB Admin (Bootstrap 4)
            $('#editModal').modal('show');
        } 
        
        else if (action === 'delete') {
            // MANIS: Mengganti confirm() bawaan dengan SweetAlert2 Konfirmasi Hapus
            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: "Hadiah ini akan dihapus secara permanen!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#e74a3b', // Warna tombol hapus SB Admin
                cancelButtonColor: '#858796',  // Warna tombol batal SB Admin
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    const itemId = row.attr('data-item-id');
                    const formData = new FormData();
                    formData.append('item_id', itemId);

                    button.prop('disabled', true);
                    
                    fetch('<?= site_url('Admin/add_item') ?>'.replace('add_item', 'delete_item'), { 
                        method: 'POST', 
                        body: formData 
                    })
                    .then(res => res.json())
                    .then(data => {
                        if(data.status === 'success') {
                            tableHadiah.row(row).remove().draw(false);
                            renumber();
                            
                            // MANIS: Alert sukses hapus
                            Swal.fire({
                                icon: 'success',
                                title: 'Terhapus!',
                                text: 'Hadiah berhasil dihapus.',
                                timer: 1500,
                                showConfirmButton: false
                            });
                        } else {
                            Swal.fire('Gagal!', data.message || 'Gagal menghapus data.', 'error');
                            button.prop('disabled', false);
                        }
                    })
                    .catch(err => {
                        Swal.fire('Error!', 'Terjadi kesalahan jaringan.', 'error');
                        button.prop('disabled', false);
                    });
                }
            });
        }
    });

    // 3. Submit Form Tambah
    $(itemForm).on('submit', function(e) {
        e.preventDefault();
        const form = this;
        const btn = $(addBtn);
        
        btn.prop('disabled', true).text('Menambahkan...');

        fetch('<?= site_url('Admin/add_item') ?>', { method: 'POST', body: new FormData(form) })
            .then(res => res.json())
            .then(data => {
                if (data.status === 'success') {
                    const stock = Number(data.stock);
                    const badgeClass = stock > 0 ? 'success' : 'danger';
                    
                    const newRow = tableHadiah.row.add([
                        '', 
                        `<div style="width: 25px; height: 25px; border-radius: 4px; background-color: ${esc(data.color)}"></div>`,
                        esc(data.item_name),
                        `<span class="badge badge-${badgeClass} stock-label">${stock}</span>`,
                        `<button type="button" class="btn btn-sm btn-warning" data-action="edit">✏️ Edit</button> <button type="button" class="btn btn-sm btn-danger" data-action="delete">🗑 Hapus</button>`
                    ]).draw(false).node();

                    $(newRow).attr('data-item-id', data.item_id)
                             .attr('data-item-name', data.item_name)
                             .attr('data-item-color', data.color)
                             .attr('data-item-stock', stock);
                    
                    form.reset();
                    $('#itemColor').val('#8b5cf6');
                    $('#itemStock').val(1);
                    
                    renumber();
                    tableHadiah.draw(false);

                    // MANIS: Toast sukses tambah (Muncul kecil otomatis hilang dalam 2 detik)
                    Swal.fire({
                        icon: 'success',
                        title: 'Hadiah Berhasil Ditambahkan',
                        timer: 2000,
                        showConfirmButton: false
                    });
                } else {
                    Swal.fire('Gagal!', data.message, 'error');
                }
            })
            .catch(err => Swal.fire('Error!', 'Gagal memproses data.', 'error'))
            .finally(() => btn.prop('disabled', false).text('+ Tambah Hadiah'));
    });

    // 4. Submit Form Edit
    $(editForm).on('submit', function(e) {
        e.preventDefault();
        const form = this;
        const btn = $(saveEditBtn);
        const formData = new FormData(form);
        
        btn.prop('disabled', true).text('Menyimpan...');

        fetch('<?= site_url('Admin/add_item') ?>'.replace('add_item', 'edit_item'), { 
            method: 'POST', 
            body: formData 
        })
        .then(res => res.json())
        .then(data => {
            if (data.status === 'success') {
                const idTarget = formData.get('item_id');
                const row = $(`tr[data-item-id="${idTarget}"]`);
                
                if (row.length) {
                    const stockVal = Number(formData.get('stock'));
                    const badgeClass = stockVal > 0 ? 'success' : 'danger';

                    row.attr('data-item-name', formData.get('item_name'))
                       .attr('data-item-color', formData.get('color'))
                       .attr('data-item-stock', stockVal);

                    tableHadiah.cell(row.find('td:eq(1)')).data(`<div style="width: 25px; height: 25px; border-radius: 4px; background-color: ${esc(formData.get('color'))}"></div>`);
                    tableHadiah.cell(row.find('td:eq(2)')).data(esc(formData.get('item_name')));
                    tableHadiah.cell(row.find('td:eq(3)')).data(`<span class="badge badge-${badgeClass} stock-label">${stockVal}</span>`);
                    tableHadiah.draw(false); 
                }
                
                $('#editModal').modal('hide');

                // MANIS: Toast sukses update
                Swal.fire({
                    icon: 'success',
                    title: 'Hadiah Berhasil Diperbarui',
                    timer: 2000,
                    showConfirmButton: false
                });
            } else {
                Swal.fire('Gagal!', data.message, 'error');
            }
        })
        .catch(err => Swal.fire('Error!', 'Gagal memperbarui data.', 'error'))
        .finally(() => btn.prop('disabled', false).text('Simpan Perubahan'));
    });
});
</script>



