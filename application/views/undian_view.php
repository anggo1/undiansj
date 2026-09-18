<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Live Lucky Draw - Premium Spinner</title>

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
            width: 100%;
            max-width: 1200px;
            margin: auto;
            padding: 35px 25px 50px;
        }

        /* =========================
           HEADER
        ========================= */

        .showcase {
            text-align: center;
            margin-bottom: 35px;
        }

        .tagline {
            color: #8b5cf6;
            font-size: 13px;
            font-weight: 700;
            letter-spacing: 3px;
            text-transform: uppercase;
            margin-bottom: 12px;
        }

        .showcase h1 {
            font-size: 34px;
            font-weight: 800;
            margin-bottom: 10px;
        }

        .showcase p {
            color: #9ca3af;
            font-size: 15px;
        }

        /* =========================
           MAIN CARD
        ========================= */

        .main-card {
            background: #111827;
            border: 1px solid #1f2937;
            border-radius: 24px;
            padding: 30px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.35);
        }

        .headline {
            text-align: center;
            margin-bottom: 25px;
        }

        .headline h2 {
            font-size: 22px;
            font-weight: 800;
            margin-bottom: 8px;
        }

        .headline p {
            color: #9ca3af;
            font-size: 14px;
        }

        /* =========================
           CONTROL
        ========================= */

        .control-area {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 12px;
            flex-wrap: wrap;
            margin-bottom: 25px;
        }

        .select-wrapper {
            position: relative;
        }

        #itemSelect {
            min-width: 280px;
            padding: 13px 18px;
            border-radius: 12px;
            border: 1px solid #374151;
            background: #1f2937;
            color: #fff;
            font-size: 14px;
            outline: none;
            cursor: pointer;
        }

        #itemSelect:focus {
            border-color: #8b5cf6;
        }

        button {
            border: none;
            cursor: pointer;
            font-family: inherit;
            transition: all 0.2s ease;
        }

        .sound-btn {
            width: 46px;
            height: 46px;
            border-radius: 12px;
            background: #1f2937;
            color: #fff;
            font-size: 18px;
            border: 1px solid #374151;
        }

        .sound-btn:hover {
            background: #374151;
        }

        .spin-btn {
            padding: 14px 24px;
            border-radius: 12px;
            background: #8b5cf6;
            color: #fff;
            font-weight: 800;
            font-size: 14px;
            box-shadow: 0 8px 25px rgba(139, 92, 246, 0.25);
        }

        .spin-btn:hover {
            background: #7c3aed;
            transform: translateY(-1px);
        }

        .spin-btn:disabled {
            opacity: 0.5;
            cursor: not-allowed;
            transform: none;
        }

        /* =========================
           ACTION BUTTON
        ========================= */

        .winner-actions {
            display: flex;
            justify-content: center;
            gap: 12px;
            margin-top: 18px;
            flex-wrap: wrap;
        }

        .save-btn,
        .skip-btn {
            padding: 13px 22px;
            border-radius: 12px;
            font-size: 14px;
            font-weight: 800;
        }

        .save-btn {
            background: #22c55e;
            color: #fff;
        }

        .save-btn:hover {
            background: #16a34a;
            transform: translateY(-1px);
        }

        .skip-btn {
            background: #374151;
            color: #fff;
            border: 1px solid #4b5563;
        }

        .skip-btn:hover {
            background: #4b5563;
        }

        .save-btn:disabled,
        .skip-btn:disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }

        /* =========================
           WHEEL
        ========================= */

        .wheel-section {
            display: flex;
            justify-content: center;
            align-items: center;
            position: relative;
            margin: 20px 0 30px;
        }

        .wheel-wrapper {
            position: relative;
            width: 470px;
            height: 470px;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        #wheel {
            width: 440px;
            height: 440px;
            border-radius: 50%;
            display: block;
        }

        .pointer {
            position: absolute;
            top: -2px;
            left: 50%;
            transform: translateX(-50%);
            z-index: 10;

            width: 0;
            height: 0;

            border-left: 18px solid transparent;
            border-right: 18px solid transparent;
            border-top: 32px solid #ef4444;

            filter: drop-shadow(0 4px 5px rgba(0, 0, 0, 0.5));
        }

        .center-badge {
            position: absolute;
            width: 78px;
            height: 78px;
            border-radius: 50%;
            background: #111827;
            border: 5px solid #fff;

            display: flex;
            justify-content: center;
            align-items: center;

            font-size: 13px;
            font-weight: 900;
            letter-spacing: 1px;

            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.5);
            z-index: 5;
        }

        /* =========================
           RESULT
        ========================= */

        .result-box {
            width: 100%;
            min-height: 85px;

            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;

            background: #0f172a;
            border: 1px solid #1f2937;
            border-radius: 16px;

            padding: 15px 20px;
            text-align: center;

            margin-bottom: 25px;
        }

        .result-status {
            color: #9ca3af;
            font-size: 12px;
            margin-bottom: 6px;
        }

        .result-name {
            font-size: 24px;
            font-weight: 900;
            color: #fff;
        }

        .result-dept {
            color: #9ca3af;
            font-size: 13px;
            margin-top: 4px;
        }

        .result-item {
            color: #a78bfa;
            font-size: 13px;
            font-weight: 700;
            margin-top: 5px;
        }

        /* =========================
           STATS
        ========================= */

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 15px;
            margin-bottom: 30px;
        }

        .stat-card {
            background: #0f172a;
            border: 1px solid #1f2937;
            border-radius: 16px;
            padding: 20px;
            text-align: center;
        }

        .stat-label {
            color: #9ca3af;
            font-size: 12px;
            margin-bottom: 8px;
        }

        .stat-value {
            font-size: 28px;
            font-weight: 900;
        }

        /* =========================
           HISTORY
        ========================= */

        .history-title {
            font-size: 18px;
            font-weight: 800;
            margin-bottom: 15px;
        }

        .history-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 15px;
        }

        .history-card {
            background: #0f172a;
            border: 1px solid #1f2937;
            border-radius: 16px;
            padding: 18px;
        }

        .history-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 10px;
            margin-bottom: 14px;
        }

        .history-item-name {
            font-weight: 800;
            font-size: 15px;
        }

        .history-stock {
            color: #a78bfa;
            font-size: 12px;
            font-weight: 700;
        }

        .history-status {
            display: inline-block;
            padding: 5px 9px;
            border-radius: 8px;
            background: #1f2937;
            color: #9ca3af;
            font-size: 11px;
            margin-bottom: 10px;
        }

        .history-winners {
            color: #d1d5db;
            font-size: 13px;
            line-height: 1.6;
        }

        .empty-winner {
            color: #6b7280;
            font-size: 13px;
        }

        /* =========================
           LOADING
        ========================= */

        .loading {
            opacity: 0.6;
            pointer-events: none;
        }

        /* =========================
           RESPONSIVE
        ========================= */

        @media (max-width: 700px) {
            .container {
                padding: 20px 15px 40px;
            }

            .main-card {
                padding: 20px 15px;
            }

            .showcase h1 {
                font-size: 26px;
            }

            .wheel-wrapper {
                width: 330px;
                height: 330px;
            }

            #wheel {
                width: 310px;
                height: 310px;
            }

            .pointer {
                top: -5px;
                border-left-width: 14px;
                border-right-width: 14px;
                border-top-width: 26px;
            }

            .center-badge {
                width: 60px;
                height: 60px;
                font-size: 10px;
            }

            .stats-grid {
                grid-template-columns: 1fr;
            }

            .history-grid {
                grid-template-columns: 1fr;
            }

            #itemSelect {
                width: 100%;
                min-width: 0;
            }

            .result-name {
                font-size: 20px;
            }
        }
    </style>
</head>

<body>

<div class="container">

    <!-- =========================
         HEADER
    ========================= -->

    <div class="showcase">
        <div class="tagline">Live Lucky Draw</div>

        <h1>Undian Hadiah Karyawan</h1>

        <p>
            Sistem pengundian hadiah secara langsung dan transparan.
        </p>
    </div>


    <!-- =========================
         MAIN CARD
    ========================= -->

    <div class="main-card">

        <div class="headline">
            <h2>SIAP UNTUK MENENTUKAN PEMENANG?</h2>

            <p>
                Pilih hadiah terlebih dahulu, lalu tekan Spin Now.
            </p>
        </div>


        <!-- =========================
             CONTROL
        ========================= -->

        <div class="control-area">

            <div class="select-wrapper">

                <select id="itemSelect">

                    <option value="">
                        -- Pilih Hadiah --
                    </option>

                    <?php foreach ($items as $item): ?>

                        <?php
                            $stock = (int)$item['stock'];
                        ?>

                        <option
                            value="<?= (int)$item['id']; ?>"
                            data-stock="<?= $stock; ?>"
                            <?= $stock <= 0 ? 'disabled' : ''; ?>
                        >
                            <?= htmlspecialchars($item['item_name']); ?>
                            — Stok: <?= $stock; ?>
                        </option>

                    <?php endforeach; ?>

                </select>

            </div>


            <button
                type="button"
                class="sound-btn"
                id="muteBtn"
                title="Toggle Sound"
            >
                🔊
            </button>


            <!-- SPIN BUTTON -->

            <button
                type="button"
                class="spin-btn"
                id="spinBtn"
            >
                🚀 SPIN NOW
            </button>

        </div>


        <!-- =========================
             WHEEL
        ========================= -->

        <div class="wheel-section">

            <div class="wheel-wrapper">

                <div class="pointer"></div>

                <canvas
                    id="wheel"
                    width="440"
                    height="440"
                ></canvas>

                <div class="center-badge">
                    DRAW
                </div>

            </div>

        </div>


        <!-- =========================
             RESULT
        ========================= -->

        <div
            class="result-box"
            id="resultBox"
        >

            <div
                class="result-status"
                id="resultStatus"
            >
                Pilih hadiah dan tekan Spin Now
            </div>

            <div
                class="result-name"
                id="resultName"
            >
                —
            </div>

            <div
                class="result-dept"
                id="resultDept"
            ></div>

            <div
                class="result-item"
                id="resultItem"
            ></div>

        </div>


        <!-- =========================
             ACTION BUTTON
        ========================= -->

        <div
            class="winner-actions"
            id="winnerActions"
            style="display: none;"
        >

            <button
                type="button"
                class="save-btn"
                id="saveWinnerBtn"
            >
                💾 Simpan Pemenang
            </button>

            <button
                type="button"
                class="skip-btn"
                id="skipWinnerBtn"
            >
                ⏭️ Skip / Orang Tidak Ada
            </button>

        </div>


        <!-- =========================
             STATISTICS
        ========================= -->

        <div class="stats-grid">

            <div class="stat-card">

                <div class="stat-label">
                    Sisa Kandidat Roda
                </div>

                <div
                    class="stat-value"
                    id="candidateCount"
                >
                    <?= count($employees); ?>
                </div>

            </div>


            <div class="stat-card">

                <div class="stat-label">
                    Total Jenis Hadiah
                </div>

                <div
                    class="stat-value"
                    id="itemTypeCount"
                >
                    <?= count($items); ?>
                </div>

            </div>


            <div class="stat-card">

                <div class="stat-label">
                    Sisa Stok Hadiah
                </div>

                <div
                    class="stat-value"
                    id="totalStock"
                >
                    <?php
                        $total_stock = 0;

                        foreach ($items as $item) {
                            $total_stock += (int)$item['stock'];
                        }

                        echo $total_stock;
                    ?>
                </div>

            </div>

        </div>


        <!-- =========================
             HISTORY
        ========================= -->

        <div class="history-title">
            Rekap Pemenang
        </div>

        <div class="history-grid" id="historyGrid">

            <?php foreach ($items as $item): ?>

                <?php
                    $stock = (int)$item['stock'];
                    $total_terundi = (int)$item['total_terundi'];

                    $nama_pemenang = !empty($item['nama_pemenang'])
                        ? $item['nama_pemenang']
                        : '';
                ?>

                <div
                    class="history-card"
                    data-item-id="<?= (int)$item['id']; ?>"
                >

                    <div class="history-header">

                        <div class="history-item-name">
                            <?= htmlspecialchars($item['item_name']); ?>
                        </div>

                        <div
                            class="history-stock"
                            data-stock
                        >
                            Stok: <?= $stock; ?>
                        </div>

                    </div>


                    <div
                        class="history-status"
                        data-status
                    >
                        <?php if ($stock > 0): ?>

                            Tersedia

                        <?php else: ?>

                            Stok Habis

                        <?php endif; ?>

                    </div>


                    <div
                        class="history-winners"
                        data-winners
                    >

                        <?php if ($nama_pemenang): ?>

                            <?= htmlspecialchars($nama_pemenang); ?>

                        <?php else: ?>

                            <span class="empty-winner">
                                Belum ada pemenang
                            </span>

                        <?php endif; ?>

                    </div>

                </div>

            <?php endforeach; ?>

        </div>

    </div>

</div>


<!-- =========================
     AUDIO
========================= -->

<audio
    id="tickSound"
    preload="auto"
>
    <source
        src="<?= base_url('sounds/wheel-tick.mp3'); ?>"
        type="audio/mpeg"
    >
</audio>


<audio
    id="victorySound"
    preload="auto"
>
    <source
        src="<?= base_url('sounds/victory.mp3'); ?>"
        type="audio/mpeg"
    >
</audio>


<script>

/* =====================================================
   DATA DARI PHP
===================================================== */

let employees = <?= json_encode(
    $employees,
    JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES
); ?>;


/* =====================================================
   PENDING WINNER DARI SESSION
===================================================== */

const pendingWinner = <?= json_encode(
    !empty($pending_winner) ? $pending_winner : null,
    JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES
); ?>;


/* =====================================================
   ELEMENT
===================================================== */

const canvas = document.getElementById('wheel');
const ctx = canvas.getContext('2d');

const spinBtn = document.getElementById('spinBtn');
const saveWinnerBtn = document.getElementById('saveWinnerBtn');
const skipWinnerBtn = document.getElementById('skipWinnerBtn');

const winnerActions = document.getElementById('winnerActions');

const itemSelect = document.getElementById('itemSelect');

const resultStatus = document.getElementById('resultStatus');
const resultName = document.getElementById('resultName');
const resultDept = document.getElementById('resultDept');
const resultItem = document.getElementById('resultItem');

const candidateCount = document.getElementById('candidateCount');
const totalStock = document.getElementById('totalStock');

const muteBtn = document.getElementById('muteBtn');

const tickSound = document.getElementById('tickSound');
const victorySound = document.getElementById('victorySound');


/* =====================================================
   VARIABLE
===================================================== */

let currentAngle = 0;

let isSpinning = false;

let soundMuted = false;

let currentPendingWinner = pendingWinner;


/* =====================================================
   WARNA RODA
===================================================== */

const wheelColors = [
    '#8b5cf6',
    '#ec4899',
    '#3b82f6',
    '#22c55e',
    '#f59e0b',
    '#ef4444',
    '#06b6d4',
    '#6366f1'
];


/* =====================================================
   JUMLAH SEGMENT
===================================================== */

function getSegmentCount() {

    return employees.length;

}


/* =====================================================
   GAMBAR RODA
===================================================== */

function drawWheel() {

    const total = getSegmentCount();

    ctx.clearRect(
        0,
        0,
        canvas.width,
        canvas.height
    );

    if (total === 0) {

        ctx.beginPath();

        ctx.arc(
            220,
            220,
            205,
            0,
            Math.PI * 2
        );

        ctx.fillStyle = '#1f2937';

        ctx.fill();

        ctx.fillStyle = '#9ca3af';

        ctx.font = 'bold 16px Arial';

        ctx.textAlign = 'center';

        ctx.textBaseline = 'middle';

        ctx.fillText(
            'Tidak ada kandidat',
            220,
            220
        );

        return;
    }


    const arc = (Math.PI * 2) / total;

    const centerX = 220;
    const centerY = 220;

    const radius = 205;


    for (let i = 0; i < total; i++) {

        const startAngle =
            currentAngle + (i * arc);

        const endAngle =
            startAngle + arc;


        /* Segment */

        ctx.beginPath();

        ctx.moveTo(
            centerX,
            centerY
        );

        ctx.arc(
            centerX,
            centerY,
            radius,
            startAngle,
            endAngle
        );

        ctx.closePath();


        ctx.fillStyle =
            wheelColors[i % wheelColors.length];

        ctx.fill();


        ctx.strokeStyle = '#ffffff';

        ctx.lineWidth = 2;

        ctx.stroke();


        /* Nama */

        ctx.save();

        ctx.translate(
            centerX,
            centerY
        );

        ctx.rotate(
            startAngle + arc / 2
        );


        ctx.textAlign = 'right';

        ctx.textBaseline = 'middle';

        ctx.fillStyle = '#ffffff';

        let fontSize = 13;

        if (total > 30) {
            fontSize = 9;
        } else if (total > 20) {
            fontSize = 10;
        } else if (total > 12) {
            fontSize = 11;
        }

        ctx.font =
            'bold ' + fontSize + 'px Arial';


        let name =
            employees[i].name || '';


        /*
         * Potong nama agar tidak terlalu
         * panjang memenuhi roda.
         */

        if (name.length > 20) {

            name =
                name.substring(0, 18) + '...';

        }


        ctx.fillText(
            name,
            radius - 12,
            0
        );


        ctx.restore();

    }


    /* Lingkaran luar */

    ctx.beginPath();

    ctx.arc(
        centerX,
        centerY,
        radius,
        0,
        Math.PI * 2
    );

    ctx.strokeStyle = '#ffffff';

    ctx.lineWidth = 5;

    ctx.stroke();

}


/* =====================================================
   NORMALISASI ANGLE
===================================================== */

function normalizeAngle(angle) {

    const full =
        Math.PI * 2;

    angle = angle % full;

    if (angle < 0) {
        angle += full;
    }

    return angle;

}


/* =====================================================
   UPDATE STATISTIK
===================================================== */

function updateCandidateCount() {

    candidateCount.textContent =
        employees.length;

}


/* =====================================================
   TOTAL STOCK DARI OPTION
===================================================== */

function updateTotalStock() {

    let total = 0;

    const options =
        itemSelect.querySelectorAll('option[data-stock]');

    options.forEach(function(option) {

        total +=
            parseInt(
                option.dataset.stock || 0,
                10
            );

    });

    totalStock.textContent = total;

}


/* =====================================================
   UPDATE STOCK HADIAH
===================================================== */

function updateItemStock(itemId, newStock) {

    const option =
        itemSelect.querySelector(
            'option[value="' + itemId + '"]'
        );

    if (option) {

        option.dataset.stock =
            newStock;

        const itemName =
            option.textContent
                .split('—')[0]
                .trim();

        option.textContent =
            itemName +
            ' — Stok: ' +
            newStock;


        if (newStock <= 0) {

            option.disabled = true;

        }

    }


    /* Update history card */

    const historyCard =
        document.querySelector(
            '.history-card[data-item-id="' +
            itemId +
            '"]'
        );


    if (historyCard) {

        const stockElement =
            historyCard.querySelector(
                '[data-stock]'
            );

        const statusElement =
            historyCard.querySelector(
                '[data-status]'
            );


        if (stockElement) {

            stockElement.textContent =
                'Stok: ' + newStock;

        }


        if (statusElement) {

            statusElement.textContent =
                newStock > 0
                    ? 'Tersedia'
                    : 'Stok Habis';

        }

    }


    updateTotalStock();

}


/* =====================================================
   TAMBAH NAMA PEMENANG KE REKAP
===================================================== */

function addWinnerToHistory(
    itemId,
    winnerName
) {

    const historyCard =
        document.querySelector(
            '.history-card[data-item-id="' +
            itemId +
            '"]'
        );


    if (!historyCard) {
        return;
    }


    const winnersElement =
        historyCard.querySelector(
            '[data-winners]'
        );


    if (!winnersElement) {
        return;
    }


    const empty =
        winnersElement.querySelector(
            '.empty-winner'
        );


    if (empty) {

        winnersElement.innerHTML =
            '';

    }


    const currentText =
        winnersElement.textContent.trim();


    if (currentText) {

        winnersElement.innerHTML +=
            '<br>';

    }


    winnersElement.innerHTML +=
        escapeHtml(winnerName);

}


/* =====================================================
   ESCAPE HTML
===================================================== */

function escapeHtml(text) {

    const div =
        document.createElement('div');

    div.textContent =
        text;

    return div.innerHTML;

}


/* =====================================================
   TAMPILKAN HASIL
===================================================== */

function showWinner(
    name,
    department,
    itemName
) {

    resultStatus.textContent =
        'CALON PEMENANG';

    resultName.textContent =
        name;

    resultDept.textContent =
        department
            ? department
            : '';

    resultItem.textContent =
        itemName
            ? '🎁 ' + itemName
            : '';

}


/* =====================================================
   RESET RESULT
===================================================== */

function resetResult() {

    resultStatus.textContent =
        'Pilih hadiah dan tekan Spin Now';

    resultName.textContent =
        '—';

    resultDept.textContent =
        '';

    resultItem.textContent =
        '';

}


/* =====================================================
   TAMPILKAN ACTION
===================================================== */

function showWinnerActions() {

    winnerActions.style.display =
        'flex';

    spinBtn.style.display =
        'none';

}


/* =====================================================
   SEMBUNYIKAN ACTION
===================================================== */

function hideWinnerActions() {

    winnerActions.style.display =
        'none';

    spinBtn.style.display =
        'inline-block';

}


/* =====================================================
   SOUND
===================================================== */

muteBtn.addEventListener(
    'click',
    function() {

        soundMuted =
            !soundMuted;


        if (soundMuted) {

            muteBtn.textContent =
                '🔇';

        } else {

            muteBtn.textContent =
                '🔊';

        }

    }
);


/* =====================================================
   TICK SOUND
===================================================== */

function playTick() {

    if (soundMuted) {
        return;
    }


    try {

        tickSound.currentTime = 0;

        tickSound.play().catch(
            function() {}
        );

    } catch (error) {}

}


/* =====================================================
   VICTORY SOUND
===================================================== */

function playVictory() {

    if (soundMuted) {
        return;
    }


    try {

        victorySound.currentTime = 0;

        victorySound.play().catch(
            function() {}
        );

    } catch (error) {}

}


/* =====================================================
   PUTAR RODA
===================================================== */

function spinWheel(
    winnerIndex,
    callback
) {

    const total =
        getSegmentCount();


    if (total === 0) {

        return;

    }


    const arc =
        (Math.PI * 2) / total;


    /*
     * Posisi segment pemenang.
     */

    const winnerCenter =
        winnerIndex * arc +
        arc / 2;


    /*
     * Pointer berada di atas.
     *
     * Posisi atas canvas = -PI/2
     */

    const targetAngle =
        (-Math.PI / 2) -
        winnerCenter;


    const currentNormalized =
        normalizeAngle(currentAngle);


    let delta =
        normalizeAngle(
            targetAngle -
            currentNormalized
        );


    /*
     * Tambahkan beberapa putaran
     * supaya animasinya terasa seperti
     * lucky draw sungguhan.
     */

    const extraSpins =
        7 + Math.floor(
            Math.random() * 3
        );


    const finalAngle =
        currentAngle +
        (extraSpins * Math.PI * 2) +
        delta;


    const startAngle =
        currentAngle;


    const duration =
        5000;


    const startTime =
        performance.now();


    let lastTickSegment =
        -1;


    function animate(now) {

        const elapsed =
            now - startTime;


        let progress =
            elapsed / duration;


        if (progress > 1) {
            progress = 1;
        }


        /*
         * Ease out cubic
         */

        const ease =
            1 -
            Math.pow(
                1 - progress,
                3
            );


        currentAngle =
            startAngle +
            (
                finalAngle -
                startAngle
            ) * ease;


        drawWheel();


        /*
         * Sound tick ketika
         * melewati segment.
         */

        const currentSegment =
            Math.floor(
                normalizeAngle(
                    currentAngle +
                    Math.PI / 2
                ) / arc
            );


        if (
            currentSegment !==
            lastTickSegment
        ) {

            lastTickSegment =
                currentSegment;

            playTick();

        }


        if (progress < 1) {

            requestAnimationFrame(
                animate
            );

        } else {

            currentAngle =
                normalizeAngle(
                    currentAngle
                );

            drawWheel();

            playVictory();


            if (typeof callback === 'function') {

                callback();

            }

        }

    }


    requestAnimationFrame(
        animate
    );

}


/* =====================================================
   SPIN NOW
===================================================== */

spinBtn.addEventListener(
    'click',
    function() {

        if (isSpinning) {
            return;
        }


        if (currentPendingWinner) {

            alert(
                'Masih ada calon pemenang yang belum diproses. Silakan Simpan atau Skip terlebih dahulu.'
            );

            return;

        }


        const itemId =
            parseInt(
                itemSelect.value || 0,
                10
            );


        if (!itemId) {

            alert(
                'Silakan pilih hadiah terlebih dahulu.'
            );

            return;

        }


        const selectedOption =
            itemSelect.options[
                itemSelect.selectedIndex
            ];


        if (
            selectedOption &&
            selectedOption.disabled
        ) {

            alert(
                'Stok hadiah ini sudah habis.'
            );

            return;

        }


        if (employees.length === 0) {

            alert(
                'Semua karyawan sudah mendapatkan hadiah.'
            );

            return;

        }


        isSpinning = true;

        spinBtn.disabled = true;

        itemSelect.disabled = true;

        spinBtn.classList.add(
            'loading'
        );


        resultStatus.textContent =
            'MENENTUKAN PEMENANG...';

        resultName.textContent =
            '🎡';

        resultDept.textContent =
            '';

        resultItem.textContent =
            '';


        /*
         * Request ke controller.
         */

        fetch(
            '<?= site_url('undian/draw'); ?>',
            {
                method: 'POST',

                headers: {
                    'Content-Type':
                        'application/x-www-form-urlencoded; charset=UTF-8'
                },

                body:
                    'item_id=' +
                    encodeURIComponent(itemId)
            }
        )

        .then(function(response) {

            return response.json();

        })

        .then(function(data) {

            if (
                data.status !==
                'success'
            ) {

                throw new Error(
                    data.message ||
                    'Gagal menentukan pemenang.'
                );

            }


            /*
             * Simpan calon pemenang
             * di frontend.
             */

            currentPendingWinner = {

                employee_id:
                    data.winner_id,

                employee_name:
                    data.winner_name,

                employee_department:
                    data.winner_dept,

                item_id:
                    data.item_id,

                item_name:
                    data.item_name,

                winner_index:
                    data.winner_index

            };


            /*
             * Jalankan animasi roda.
             */

            spinWheel(
                data.winner_index,
                function() {

                    showWinner(
                        data.winner_name,
                        data.winner_dept,
                        data.item_name
                    );


                    /*
                     * Setelah nama muncul:
                     *
                     * SPIN NOW HILANG
                     * SIMPAN + SKIP MUNCUL
                     */

                    showWinnerActions();


                    isSpinning =
                        false;

                    spinBtn.disabled =
                        false;

                    itemSelect.disabled =
                        true;

                    spinBtn.classList.remove(
                        'loading'
                    );

                }
            );

        })

        .catch(function(error) {

            isSpinning =
                false;

            spinBtn.disabled =
                false;

            itemSelect.disabled =
                false;

            spinBtn.classList.remove(
                'loading'
            );


            resetResult();


            alert(
                error.message ||
                'Terjadi kesalahan.'
            );

        });

    }
);


/* =====================================================
   SIMPAN PEMENANG
===================================================== */

saveWinnerBtn.addEventListener(
    'click',
    function() {

        if (
            !currentPendingWinner
        ) {

            alert(
                'Tidak ada calon pemenang.'
            );

            return;

        }


        if (
            !confirm(
                'Simpan ' +
                currentPendingWinner.employee_name +
                ' sebagai pemenang ' +
                currentPendingWinner.item_name +
                '?'
            )
        ) {

            return;

        }


        saveWinnerBtn.disabled =
            true;

        skipWinnerBtn.disabled =
            true;


        saveWinnerBtn.textContent =
            '⏳ Menyimpan...';


        fetch(
            '<?= site_url('undian/save_winner'); ?>',
            {
                method: 'POST',

                headers: {
                    'Content-Type':
                        'application/x-www-form-urlencoded; charset=UTF-8'
                },

                body: ''
            }
        )

        .then(function(response) {

            return response.json();

        })

        .then(function(data) {

            if (
                data.status !==
                'success'
            ) {

                throw new Error(
                    data.message ||
                    'Pemenang gagal disimpan.'
                );

            }


            const savedEmployeeId =
                data.winner_id;


            const savedItemId =
                data.item_id;


            /*
             * Hapus karyawan yang sudah menang
             * dari roda.
             */

            employees =
                employees.filter(
                    function(employee) {

                        return (
                            parseInt(employee.id, 10) !==
                            parseInt(savedEmployeeId, 10)
                        );

                    }
                );


            /*
             * Update statistik.
             */

            updateCandidateCount();


            /*
             * Update stok.
             */

            updateItemStock(
                savedItemId,
                parseInt(
                    data.remaining_stock,
                    10
                )
            );


            /*
             * Update history.
             */

            addWinnerToHistory(
                savedItemId,
                data.winner_name
            );


            /*
             * Reset pending.
             */

            currentPendingWinner =
                null;


            /*
             * Bersihkan hasil.
             */

            resultStatus.textContent =
                'PEMENANG BERHASIL DISIMPAN';

            resultName.textContent =
                data.winner_name;

            resultDept.textContent =
                data.winner_dept || '';

            resultItem.textContent =
                '🎁 ' +
                data.item_name;


            /*
             * Bersihkan tombol.
             */

            hideWinnerActions();


            /*
             * Hadiah tetap dipilih.
             * User bisa langsung spin lagi.
             */

            itemSelect.disabled =
                false;


            spinBtn.disabled =
                false;


            saveWinnerBtn.disabled =
                false;

            skipWinnerBtn.disabled =
                false;


            saveWinnerBtn.textContent =
                '💾 Simpan Pemenang';


            /*
             * Gambar ulang roda
             * tanpa reload halaman.
             */

            drawWheel();


            /*
             * Kalau stok hadiah sudah habis,
             * pilihannya otomatis disabled.
             */

            const selectedOption =
                itemSelect.querySelector(
                    'option[value="' +
                    savedItemId +
                    '"]'
                );


            if (
                selectedOption &&
                selectedOption.disabled
            ) {

                /*
                 * Kalau stok habis,
                 * kosongkan pilihan agar
                 * user memilih hadiah lain.
                 */

                itemSelect.value =
                    '';

            }


        })

        .catch(function(error) {

            alert(
                error.message ||
                'Gagal menyimpan pemenang.'
            );


            saveWinnerBtn.disabled =
                false;

            skipWinnerBtn.disabled =
                false;

            saveWinnerBtn.textContent =
                '💾 Simpan Pemenang';

        });

    }
);


/* =====================================================
   SKIP PEMENANG
===================================================== */

skipWinnerBtn.addEventListener(
    'click',
    function() {

        if (
            !currentPendingWinner
        ) {

            alert(
                'Tidak ada calon pemenang.'
            );

            return;

        }


        if (
            !confirm(
                'Skip ' +
                currentPendingWinner.employee_name +
                '? Orang ini tidak akan disimpan sebagai pemenang.'
            )
        ) {

            return;

        }


        saveWinnerBtn.disabled =
            true;

        skipWinnerBtn.disabled =
            true;

        skipWinnerBtn.textContent =
            '⏳ Memproses...';


        fetch(
            '<?= site_url('undian/skip_winner'); ?>',
            {
                method: 'POST',

                headers: {
                    'Content-Type':
                        'application/x-www-form-urlencoded; charset=UTF-8'
                },

                body: ''
            }
        )

        .then(function(response) {

            return response.json();

        })

        .then(function(data) {

            if (
                data.status !==
                'success'
            ) {

                throw new Error(
                    data.message ||
                    'Gagal melakukan skip.'
                );

            }


            /*
             * Tidak ada data yang diubah
             * di database.
             *
             * Orang yang di-skip tetap
             * berada di employees.
             */


            currentPendingWinner =
                null;


            resultStatus.textContent =
                'CALON PEMENANG DI-SKIP';

            resultName.textContent =
                data.skipped_name ||
                '—';

            resultDept.textContent =
                '';

            resultItem.textContent =
                'Orang tidak ada — belum disimpan';


            /*
             * Kembalikan tombol Spin.
             */

            hideWinnerActions();


            itemSelect.disabled =
                false;

            spinBtn.disabled =
                false;


            saveWinnerBtn.disabled =
                false;

            skipWinnerBtn.disabled =
                false;


            skipWinnerBtn.textContent =
                '⏭️ Skip / Orang Tidak Ada';


            /*
             * Roda tidak berubah karena
             * orang tersebut belum menang.
             */

            drawWheel();

        })

        .catch(function(error) {

            alert(
                error.message ||
                'Gagal melakukan skip.'
            );


            saveWinnerBtn.disabled =
                false;

            skipWinnerBtn.disabled =
                false;

            skipWinnerBtn.textContent =
                '⏭️ Skip / Orang Tidak Ada';

        });

    }
);


/* =====================================================
   PILIH HADIAH
===================================================== */

itemSelect.addEventListener(
    'change',
    function() {

        if (
            currentPendingWinner
        ) {

            return;

        }


        const option =
            itemSelect.options[
                itemSelect.selectedIndex
            ];


        if (
            !option ||
            !option.value
        ) {

            return;

        }


        const stock =
            parseInt(
                option.dataset.stock || 0,
                10
            );


        if (stock <= 0) {

            alert(
                'Stok hadiah ini sudah habis.'
            );

            itemSelect.value =
                '';

        }

    }
);


/* =====================================================
   RESTORE PENDING WINNER
   JIKA PAGE DI-REFRESH
===================================================== */

function restorePendingWinner() {

    if (
        !currentPendingWinner
    ) {

        return;

    }


    const winnerIndex =
        parseInt(
            currentPendingWinner.winner_index,
            10
        );


    if (
        isNaN(winnerIndex) ||
        winnerIndex < 0 ||
        winnerIndex >= employees.length
    ) {

        /*
         * Kalau index sudah tidak cocok,
         * cari berdasarkan employee_id.
         */

        const foundIndex =
            employees.findIndex(
                function(employee) {

                    return (
                        parseInt(employee.id, 10) ===
                        parseInt(
                            currentPendingWinner.employee_id,
                            10
                        )
                    );

                }
            );


        if (foundIndex >= 0) {

            currentPendingWinner.winner_index =
                foundIndex;

        }

    }


    itemSelect.value =
        currentPendingWinner.item_id;


    showWinner(
        currentPendingWinner.employee_name,
        currentPendingWinner.employee_department,
        currentPendingWinner.item_name
    );


    showWinnerActions();


    itemSelect.disabled =
        true;


    spinBtn.disabled =
        true;

}


/* =====================================================
   INIT
===================================================== */

drawWheel();

updateCandidateCount();

updateTotalStock();

restorePendingWinner();

</script>

</body>
</html>