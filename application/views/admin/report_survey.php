<?php
$this->load->view('admin/komponen/header');
$this->load->view('admin/komponen/sidebar');
$this->load->view('admin/komponen/navbar');
?>

<div class="container-fluid">

    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <div>
            <span class="text-xs font-weight-bold text-success text-uppercase tracking-wide">Survey & Feedback</span>
            <h1 class="h3 mb-0 text-gray-800 font-weight-bold">Laporan Hasil Survey</h1>
            <p class="text-gray-600 small mb-0">Visualisasi dan rekapitulasi data kuesioner dinamis secara real-time.</p>
        </div>
        <a href="<?= base_url('survey/export_excel') ?>" class="d-none d-sm-inline-block btn btn-sm btn-success shadow-sm font-weight-bold px-3">
            <i class="fas fa-file-excel fa-sm text-white-50 mr-2"></i> Export ke Excel
        </a>
    </div>

    <!-- Grafik Section -->
    <div class="row">
        <!-- Grafik Donut/Pie -->
        <div class="col-xl-4 col-lg-5">
            <div class="card shadow mb-4" style="border-radius: 12px;">
                <div class="card-header py-3 bg-white border-bottom">
                    <h6 class="m-0 font-weight-bold text-primary"><i class="fas fa-chart-pie mr-2"></i>Persentase Kategori</h6>
                </div>
                <div class="card-body">
                    <div class="chart-pie pt-4 pb-2" style="position: relative; height:240px;">
                        <canvas id="surveyPieChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Grafik Batang/Bar -->
        <div class="col-xl-8 col-lg-7">
            <div class="card shadow mb-4" style="border-radius: 12px;">
                <div class="card-header py-3 bg-white border-bottom">
                    <h6 class="m-0 font-weight-bold text-primary"><i class="fas fa-chart-bar mr-2"></i>Jumlah Responden per Kategori</h6>
                </div>
                <div class="card-body">
                    <div class="chart-bar" style="position: relative; height:240px;">
                        <canvas id="surveyBarChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Detail Tabel Responden Dinamis -->
    <div class="card shadow mb-5" style="border-radius: 15px; overflow: hidden;">
        <div class="card-header py-3 bg-white border-bottom">
            <h6 class="m-0 font-weight-bold text-dark"><i class="fas fa-list mr-2"></i>Data Mentah Tanggapan Responden</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead class="bg-gray-100 text-dark font-weight-bold">
                        <tr>
                            <th style="width: 5%">#</th>
                            <!-- Generate Header Kolom Otomatis Sesuai Pertanyaan di Database -->
                            <?php foreach ($questions as $q): ?>
                                <th><?= htmlspecialchars($q['question_text'], ENT_QUOTES, 'UTF-8'); ?></th>
                            <?php endforeach; ?>
                            <th>Waktu Mengisi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(!empty($respondents)): foreach ($respondents as $index => $r): ?>
                        <tr>
                            <td class="text-center font-weight-bold"><?= $index + 1; ?></td>
                            
                            <!-- Looping jawaban berdasarkan ID pertanyaan secara urut -->
                            <?php foreach ($questions as $q): ?>
                                <td>
                                    <?php 
                                        // Tampilkan jawaban jika ada, jika kosong tampilkan tanda strip (-)
                                        $jawaban = isset($r['answers'][$q['id']]) ? $r['answers'][$q['id']] : '-';
                                        echo htmlspecialchars($jawaban, ENT_QUOTES, 'UTF-8');
                                    ?>
                                </td>
                            <?php endforeach; ?>

                            <td class="small text-gray-600"><?= $r['submitted_at']; ?></td>
                        </tr>
                        <?php endforeach; else: ?>
                        <tr>
                            <td colspan="<?= count($questions) + 2; ?>" class="text-center text-gray-500 py-4">Belum ada tanggapan survey.</td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>

<!-- Load Library Utama Chart.js dari aset lokal Anda -->
<script src="<?= base_url('assets/admin/vendor/chart.js/Chart.min.js') ?>"></script>

<script>
    const chartLabels = <?= json_encode(array_column($chart_data, 'label')); ?>;
    const chartCounts = <?= json_encode(array_column($chart_data, 'count')); ?>;

    // 1. Penerapan Bar Chart
    const ctxBar = document.getElementById('surveyBarChart');
    new Chart(ctxBar, {
        type: 'bar',
        data: {
            labels: chartLabels,
            datasets: [{
                label: 'Jumlah Responden',
                data: chartCounts,
                backgroundColor: ['#4e73df', '#1cc88a', '#f6c23e', '#e74c3c', '#36b9cc'],
                borderWidth: 1
            }]
        },
        options: {
            maintainAspectRatio: false,
            scales: { yAxes: [{ ticks: { beginAtZero: true, stepSize: 1 } }] },
            legend: { display: false }
        }
    });

    // 2. Penerapan Doughnut/Pie Chart
    const ctxPie = document.getElementById('surveyPieChart');
    new Chart(ctxPie, {
        type: 'doughnut',
        data: {
            labels: chartLabels,
            datasets: [{
                data: chartCounts,
                backgroundColor: ['#4e73df', '#1cc88a', '#f6c23e', '#e74c3c', '#36b9cc'],
            }]
        },
        options: {
            maintainAspectRatio: false,
            legend: { position: 'bottom', labels: { boxWidth: 12 } },
            cutoutPercentage: 70
        }
    });
</script>

<?php $this->load->view('admin/komponen/footer'); ?>
