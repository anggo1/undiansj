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
            <p class="text-gray-600 small mb-0">Visualisasi dan rekapitulasi data kuesioner dinamis secara real-time.
            </p>
        </div>
        <a href="<?= site_url('survey/export_excel/' . $selected_survey_id . '?gelombang=' . urlencode($selected_gelombang)) ?>"
            class="btn btn-sm btn-success shadow-sm font-weight-bold text-xs uppercase px-3 py-2">
            <i class="fas fa-file-excel mr-1"></i> Export ke Excel (.xlsx)
        </a>
    </div>

    <!-- Bar Dropdown Filter Multi-Parameter -->
    <div class="card shadow mb-4 border-left-primary">
        <div class="card-body py-3 bg-light">
            <form method="GET" action="<?= site_url('survey/report_survey'); ?>" class="form-inline m-0 row">
                <!-- Filter 1: Judul Survey -->
                <div class="col-sm-12 col-md-6 mb-2 mb-md-0 pl-0">
                    <label class="mr-2 font-weight-bold text-gray-700 small" for="selectSurvey"><i
                            class="fas fa-poll-h mr-1"></i> Survey:</label>
                    <select name="survey_id" id="selectSurvey"
                        class="form-control form-control-sm custom-select shadow-sm w-75" onchange="this.form.submit()">
                        <?php foreach($all_surveys as $as): ?>
                        <option value="<?= $as->id ?>" <?= $as->id == $selected_survey_id ? 'selected' : '' ?>>
                            <?= htmlspecialchars($as->title) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Filter 2: Gelombang Dinamis -->
                <div class="col-sm-12 col-md-6 text-md-right pr-0">
                    <label class="mr-2 font-weight-bold text-gray-700 small" for="selectGelombang"><i
                            class="fas fa-filter text-primary mr-1"></i> Filter Gelombang:</label>
                    <select name="gelombang" id="selectGelombang"
                        class="form-control form-control-sm custom-select shadow-sm" style="min-width: 180px;"
                        onchange="this.form.submit()">
                        <option value="">Semua Gelombang</option>
                        <?php foreach($list_gelombang as $lg): if(!empty($lg['answer_text'])): ?>
                        <option value="<?= htmlspecialchars($lg['answer_text']) ?>"
                            <?= $selected_gelombang == $lg['answer_text'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($lg['answer_text']) ?>
                        </option>
                        <?php endif; endforeach; ?>
                    </select>
                </div>
            </form>
        </div>
    </div>

    <!-- Grafik Section -->
    <div class="row">
        <!-- Grafik Donut/Pie -->
        <div class="col-xl-4 col-lg-5">
            <div class="card shadow mb-4" style="border-radius: 12px;">
                <div class="card-header py-3 bg-white border-bottom">
                    <h6 class="m-0 font-weight-bold text-primary"><i class="fas fa-chart-pie mr-2"></i>Persentase
                        Kategori</h6>
                </div>
                <div class="card-body">
                    <div class="chart-pie pt-4 pb-2" style="position: relative; height:240px;">
                        <!-- FIX: Diubah agar sesuai dengan ID pemanggilan Script Chart di bawah -->
                        <canvas id="surveyPieChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Grafik Batang/Bar -->
        <div class="col-xl-8 col-lg-7">
            <div class="card shadow mb-4" style="border-radius: 12px;">
                <div class="card-header py-3 bg-white border-bottom">
                    <h6 class="m-0 font-weight-bold text-primary"><i class="fas fa-chart-bar mr-2"></i>Jumlah Responden
                        per Kategori</h6>
                </div>
                <div class="card-body">
                    <div class="chart-bar" style="position: relative; height:240px;">
                        <!-- FIX: Diubah agar sesuai dengan ID pemanggilan Script Chart di bawah -->
                        <canvas id="surveyBarChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Detail Tabel Responden Dinamis -->
    <div class="card shadow mb-5" style="border-radius: 15px; overflow: hidden;">
        <div class="card-header py-3 bg-white border-bottom">
            <h6 class="m-0 font-weight-bold text-dark"><i class="fas fa-list mr-2"></i>Data Mentah Tanggapan Responden
            </h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <!-- FIX: id diganti ke dataTableSurvey agar tidak bentrok dengan auto-load SB Admin global -->
                <table class="table table-hover table-bordered" id="dataTableSurvey" width="100%" cellspacing="0">
                    <thead class="bg-gray-100 text-dark font-weight-bold">
                        <tr>
                            <th style="width: 5%">#</th>
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
                            <?php foreach ($questions as $q): ?>
                            <td>
                                <?php 
                                        $jawaban = isset($r['answers'][$q['id']]) ? $r['answers'][$q['id']] : '-';
                                        echo htmlspecialchars($jawaban, ENT_QUOTES, 'UTF-8');
                                    ?>
                            </td>
                            <?php endforeach; ?>
                            <td class="small text-gray-600"><?= date('d-M-Y H:i', strtotime($r['submitted_at'])); ?>
                            </td>
                        </tr>
                        <?php endforeach; else: ?>
                        <tr>
                            <td colspan="<?= count($questions) + 2; ?>" class="text-center text-gray-500 py-4">Belum ada
                                tanggapan survey.</td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- FIX POSISI: Panggil komponen footer SB Admin terlebih dahulu agar jQuery terdefinisi -->
<?php $this->load->view('admin/komponen/footer'); ?>

<!-- Load Library Utama Chart.js -->
<script src="<?= base_url('assets/admin/vendor/chart.js/Chart.min.js') ?>"></script>

<script>
// Gunakan Vanilla JS DOM Listener untuk memastikan keandalan eksekusi lintas library
document.addEventListener("DOMContentLoaded", function() {

    // Inisialisasi DataTables secara manual pada ID yang spesifik
    if ($.fn.DataTable && !$.fn.DataTable.isDataTable('#dataTableSurvey')) {
        $('#dataTableSurvey').DataTable({
            "order": [
                [<?= count($questions) + 1 ?>, "desc"]
            ], // Mengurutkan berdasarkan waktu mengisi terbaru
            "pageLength": 10,
            "language": {
                "search": "Cari Baris:",
                "lengthMenu": "Tampilkan _MENU_ entri data",
                "info": "Menampilkan _START_ sampai _END_ dari _TOTAL_ responden",
                "paginate": {
                    "next": "Berikutnya",
                    "previous": "Sebelumnya"
                }
            }
        });
    }

    // Pemetaan Data Array Terenkripsi Aman dari Flash Memory CI3 Backend Controller
    const chartLabels =
        <?= isset($chart_labels) && is_array($chart_labels) ? json_encode(array_values($chart_labels)) : '[]'; ?>;
    const chartValues =
        <?= isset($chart_values) && is_array($chart_values) ? json_encode(array_values($chart_values)) : '[]'; ?>;

    // Hentikan rendering jika data kosong dari database
    if (chartLabels.length === 0 || chartValues.length === 0) {
        console.warn("Visualisasi dihentikan karena tidak ada data yang masuk kriteria chart.");
        return;
    }

    const hexColors = ['#4e73df', '#1cc88a', '#36b9cc', '#f6c23e', '#e74a3b', '#858796', '#5a5c69'];

    // 1. Pembuatan Diagram Lingkaran (Pie/Donut Chart)
    const ctxPie = document.getElementById("surveyPieChart");
    if (ctxPie) {
        new Chart(ctxPie, {
            type: 'doughnut',
            data: {
                labels: chartLabels,
                datasets: [{
                    data: chartValues,
                    backgroundColor: hexColors.slice(0, chartLabels.length),
                    hoverBorderColor: "rgba(234, 236, 244, 1)"
                }],
            },
            options: {
                maintainAspectRatio: false,
                legend: {
                    display: true,
                    position: 'bottom',
                    labels: {
                        boxWidth: 12,
                        padding: 15,
                        fontFamily: 'Nunito, sans-serif'
                    }
                },
                cutoutPercentage: 70
            }
        });
    }

    // 2. Pembuatan Diagram Batang (Bar Chart)
    const ctxBar = document.getElementById("surveyBarChart");
    if (ctxBar) {
        new Chart(ctxBar, {
            type: 'bar',
            data: {
                labels: chartLabels,
                datasets: [{
                    label: "Jumlah Responden",
                    backgroundColor: "#4e73df",
                    hoverBackgroundColor: "#2e59d9",
                    borderColor: "#4e73df",
                    data: chartValues
                }],
            },
            options: {
                maintainAspectRatio: false,
                layout: {
                    padding: {
                        left: 10,
                        right: 25,
                        top: 15,
                        bottom: 0
                    }
                },
                scales: {
                    xAxes: [{
                        gridLines: {
                            display: false,
                            drawBorder: false
                        },
                        maxBarThickness: 50
                    }],
                    yAxes: [{
                        ticks: {
                            min: 0,
                            beginAtZero: true,
                            maxTicksLimit: 6,
                            padding: 10
                        },
                        gridLines: {
                            color: "rgb(234, 236, 244)",
                            zeroLineColor: "rgb(234, 236, 244)",
                            drawBorder: false,
                            borderDash: [],
                            zeroLineBorderDash: []
                        }
                    }],
                },
                legend: {
                    display: false
                }
            }
        });
    }
});
</script>