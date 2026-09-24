<!DOCTYPE html>
<html>
<head>
    <title>Export Data Tamu</title>
</head>
<body>
    <table border="1">
        <thead>
            <tr>
                <th>No</th>
                <th>NIK</th>
                <th>Nama</th>
                <th>Departemen</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            $no = 1; 
            foreach ($tamu as $t) { 
            ?>
            <tr>
                <td><?= $no++ ?></td>
                <!-- Ditambahkan \t agar angka nol di depan NIK tidak hilang di Excel -->
                <td>'000<?= $t->nik ?></td> 
                <td><?= $t->name ?></td>
                <td><?= $t->department ?></td>
            </tr>
            <?php } ?>
        </tbody>
    </table>
</body>
</html>
