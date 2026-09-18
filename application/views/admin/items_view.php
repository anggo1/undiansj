<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Kelola Hadiah</title>

    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: Arial, Helvetica, sans-serif;
            background: #0b0f19;
            color: #fff;
            min-height: 100vh;
        }

        .container {
            max-width: 1100px;
            margin: auto;
            padding: 40px 25px 60px;
        }

        .header {
            text-align: center;
            margin-bottom: 35px;
        }

        .tagline {
            color: #8b5cf6;
            font-size: 12px;
            font-weight: 800;
            letter-spacing: 3px;
            text-transform: uppercase;
            margin-bottom: 10px;
        }

        .header h1 {
            font-size: 32px;
            font-weight: 900;
            margin-bottom: 10px;
        }

        .header p {
            color: #9ca3af;
            font-size: 14px;
        }

        .card {
            background: #111827;
            border: 1px solid #1f2937;
            border-radius: 20px;
            padding: 25px;
            margin-bottom: 25px;
            box-shadow: 0 15px 40px rgba(0,0,0,.25);
        }

        .card-title {
            font-size: 19px;
            font-weight: 800;
            margin-bottom: 20px;
        }

        .form-grid {
            display: grid;
            grid-template-columns: 1fr 180px 150px 160px;
            gap: 15px;
            align-items: end;
        }

        .form-group {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .form-group label {
            font-size: 12px;
            color: #9ca3af;
            font-weight: 700;
        }

        .form-control {
            width: 100%;
            height: 46px;
            border-radius: 10px;
            border: 1px solid #374151;
            background: #0f172a;
            color: #fff;
            padding: 0 14px;
            font-size: 14px;
            outline: none;
        }

        .form-control:focus {
            border-color: #8b5cf6;
        }

        input[type="color"] {
            padding: 5px;
            cursor: pointer;
        }

        .add-btn {
            height: 46px;
            border: none;
            border-radius: 10px;
            background: #8b5cf6;
            color: #fff;
            font-size: 14px;
            font-weight: 800;
            cursor: pointer;
        }

        .add-btn:hover {
            background: #7c3aed;
        }

        .add-btn:disabled {
            opacity: .5;
            cursor: not-allowed;
        }

        .table-wrapper {
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th {
            text-align: left;
            color: #9ca3af;
            font-size: 12px;
            font-weight: 700;
            padding: 14px;
            border-bottom: 1px solid #1f2937;
        }

        td {
            padding: 15px 14px;
            border-bottom: 1px solid #1f2937;
            font-size: 14px;
        }

        .color-preview {
            width: 30px;
            height: 30px;
            border-radius: 8px;
            border: 2px solid rgba(255,255,255,.2);
        }

        .stock {
            font-weight: 800;
        }

        .stock.available {
            color: #22c55e;
        }

        .stock.empty {
            color: #ef4444;
        }

        .delete-btn {
            padding: 8px 12px;
            border-radius: 8px;
            border: 1px solid #374151;
            background: #1f2937;
            color: #fff;
            font-size: 12px;
            font-weight: 700;
            cursor: pointer;
        }

        .delete-btn:hover {
            background: #ef4444;
            border-color: #ef4444;
        }

        .delete-btn:disabled {
            opacity: .35;
            cursor: not-allowed;
        }

        .empty {
            text-align: center;
            color: #6b7280;
            padding: 30px;
        }

        .back-btn {
            display: inline-block;
            color: #9ca3af;
            text-decoration: none;
            font-size: 13px;
            font-weight: 700;
        }

        .back-btn:hover {
            color: #fff;
        }

        @media (max-width: 800px) {
            .form-grid {
                grid-template-columns: 1fr 1fr;
            }
        }

        @media (max-width: 550px) {
            .container {
                padding: 25px 15px 40px;
            }

            .header h1 {
                font-size: 26px;
            }

            .form-grid {
                grid-template-columns: 1fr;
            }

            .add-btn {
                width: 100%;
            }
        }
    </style>
</head>

<body>

<div class="container">

    <div class="header">
        <div class="tagline">Lucky Draw Management</div>

        <h1>Kelola Hadiah</h1>

        <p>
            Tambahkan dan kelola hadiah yang akan digunakan
            dalam pengundian.
        </p>
    </div>


    <!-- FORM TAMBAH HADIAH -->

    <div class="card">

        <div class="card-title">
            Tambah Hadiah Baru
        </div>

        <form id="itemForm">

            <div class="form-grid">

                <div class="form-group">

                    <label for="itemName">
                        Nama Hadiah
                    </label>

                    <input
                        type="text"
                        id="itemName"
                        name="item_name"
                        class="form-control"
                        placeholder="Contoh: Laptop"
                        maxlength="100"
                        required
                    >

                </div>


                <div class="form-group">

                    <label for="itemColor">
                        Warna Roda
                    </label>

                    <input
                        type="color"
                        id="itemColor"
                        name="color"
                        class="form-control"
                        value="#8b5cf6"
                        required
                    >

                </div>


                <div class="form-group">

                    <label for="itemStock">
                        Stok
                    </label>

                    <input
                        type="number"
                        id="itemStock"
                        name="stock"
                        class="form-control"
                        value="1"
                        min="0"
                        required
                    >

                </div>


                <button
                    type="submit"
                    class="add-btn"
                    id="addBtn"
                >
                    + Tambah Hadiah
                </button>

            </div>

        </form>

    </div>


    <!-- DAFTAR HADIAH -->

    <div class="card">

        <div class="card-title">
            Daftar Hadiah
        </div>

        <div class="table-wrapper">

            <table>

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

                    <?php if (!empty($items)): ?>

                        <?php foreach ($items as $index => $item): ?>

                            <?php
                                $stock = (int)$item['stock'];
                                $totalTerundi = isset($item['total_terundi'])
                                    ? (int)$item['total_terundi']
                                    : 0;
                            ?>

                            <tr
                                data-item-id="<?php echo (int)$item['id']; ?>"
                            >

                                <td>
                                    <?php echo $index + 1; ?>
                                </td>

                                <td>

                                    <div
                                        class="color-preview"
                                        style="background-color: <?php echo htmlspecialchars($item['color'], ENT_QUOTES, 'UTF-8'); ?>;"
                                    ></div>

                                </td>

                                <td>
                                    <?php
                                        echo htmlspecialchars(
                                            $item['item_name'],
                                            ENT_QUOTES,
                                            'UTF-8'
                                        );
                                    ?>
                                </td>

                                <td>

                                    <span
                                        class="stock <?php echo $stock > 0 ? 'available' : 'empty'; ?>"
                                    >
                                        <?php echo $stock; ?>
                                    </span>

                                </td>

                                <td>

                                    <button
                                        type="button"
                                        class="delete-btn"
                                        onclick="deleteItem(
                                            <?php echo (int)$item['id']; ?>,
                                            '<?php
                                                echo htmlspecialchars(
                                                    addslashes($item['item_name']),
                                                    ENT_QUOTES,
                                                    'UTF-8'
                                                );
                                            ?>'
                                        )"
                                        <?php echo $totalTerundi > 0 ? 'disabled' : ''; ?>
                                    >
                                        🗑 Hapus
                                    </button>

                                </td>

                            </tr>

                        <?php endforeach; ?>

                    <?php else: ?>

                        <tr id="emptyRow">

                            <td
                                colspan="5"
                                class="empty"
                            >
                                Belum ada hadiah.
                            </td>

                        </tr>

                    <?php endif; ?>

                </tbody>

            </table>

        </div>

    </div>


    <a
        href="<?php echo site_url('undian'); ?>"
        class="back-btn"
    >
        ← Kembali ke halaman Undian
    </a>

</div>


<script>

/* =====================================================
   TAMBAH HADIAH
===================================================== */

var itemForm =
    document.getElementById('itemForm');

var addBtn =
    document.getElementById('addBtn');


itemForm.addEventListener('submit', function(e) {

    e.preventDefault();


    var formData =
        new FormData(itemForm);


    addBtn.disabled = true;

    addBtn.innerHTML =
        '⏳ Menambahkan...';


    fetch(
        '<?php echo site_url('undian/add_item'); ?>',
        {
            method: 'POST',
            body: formData
        }
    )

    .then(function(response) {

        return response.json();

    })

    .then(function(data) {

        if (data.status !== 'success') {

            throw new Error(
                data.message ||
                'Gagal menambahkan hadiah.'
            );

        }


        var emptyRow =
            document.getElementById('emptyRow');


        if (emptyRow) {

            emptyRow.remove();

        }


        var tbody =
            document.getElementById('itemTable');


        var row =
            document.createElement('tr');


        row.setAttribute(
            'data-item-id',
            data.item_id
        );


        var nomor =
            tbody.querySelectorAll('tr').length + 1;


        var stockClass =
            parseInt(data.stock, 10) > 0
                ? 'available'
                : 'empty';


        var color =
            escapeHtml(data.color);


        var name =
            escapeHtml(data.item_name);


        row.innerHTML =
            '<td>' +
                nomor +
            '</td>' +

            '<td>' +

                '<div ' +
                    'class="color-preview" ' +
                    'style="background-color:' +
                    color +
                    ';"' +
                '></div>' +

            '</td>' +

            '<td>' +
                name +
            '</td>' +

            '<td>' +

                '<span class="stock ' +
                    stockClass +
                '">' +

                    data.stock +

                '</span>' +

            '</td>' +

            '<td>' +

                '<button ' +
                    'type="button" ' +
                    'class="delete-btn" ' +
                    'onclick="deleteItem(' +
                        data.item_id +
                        ', \'' +
                        escapeJs(data.item_name) +
                        '\'' +
                    ')"' +
                '>' +

                    '🗑 Hapus' +

                '</button>' +

            '</td>';


        tbody.appendChild(row);


        itemForm.reset();


        document.getElementById(
            'itemColor'
        ).value =
            '#8b5cf6';


        document.getElementById(
            'itemStock'
        ).value =
            '1';


        alert(
            'Hadiah berhasil ditambahkan.'
        );

    })

    .catch(function(error) {

        alert(
            error.message ||
            'Terjadi kesalahan.'
        );

    })

    .finally(function() {

        addBtn.disabled = false;

        addBtn.innerHTML =
            '+ Tambah Hadiah';

    });

});


/* =====================================================
   HAPUS HADIAH
===================================================== */

function deleteItem(itemId, itemName) {

    if (
        !confirm(
            'Hapus hadiah "' +
            itemName +
            '"?'
        )
    ) {

        return;

    }


    var formData =
        new URLSearchParams();


    formData.append(
        'item_id',
        itemId
    );


    fetch(
        '<?php echo site_url('undian/delete_item'); ?>',
        {
            method: 'POST',

            headers: {
                'Content-Type':
                    'application/x-www-form-urlencoded; charset=UTF-8'
            },

            body:
                formData.toString()
        }
    )

    .then(function(response) {

        return response.json();

    })

    .then(function(data) {

        if (data.status !== 'success') {

            throw new Error(
                data.message ||
                'Gagal menghapus hadiah.'
            );

        }


        var row =
            document.querySelector(
                'tr[data-item-id="' +
                itemId +
                '"]'
            );


        if (row) {

            row.remove();

        }


        var tbody =
            document.getElementById(
                'itemTable'
            );


        if (
            tbody.querySelectorAll('tr').length === 0
        ) {

            var emptyRow =
                document.createElement('tr');


            emptyRow.id =
                'emptyRow';


            emptyRow.innerHTML =
                '<td colspan="5" class="empty">' +
                    'Belum ada hadiah.' +
                '</td>';


            tbody.appendChild(
                emptyRow
            );

        }


        alert(
            'Hadiah berhasil dihapus.'
        );

    })

    .catch(function(error) {

        alert(
            error.message ||
            'Terjadi kesalahan.'
        );

    });

}


/* =====================================================
   ESCAPE HTML
===================================================== */

function escapeHtml(text) {

    var div =
        document.createElement('div');

    div.textContent =
        text;

    return div.innerHTML;

}


/* =====================================================
   ESCAPE JAVASCRIPT
===================================================== */

function escapeJs(text) {

    return String(text)
        .replace(/\\/g, '\\\\')
        .replace(/'/g, "\\'")
        .replace(/"/g, '\\"')
        .replace(/\r/g, '\\r')
        .replace(/\n/g, '\\n');

}

</script>

</body>
</html>