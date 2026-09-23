<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Live Lucky Draw - Undi Nomor</title>
    <style>
        .draw-container {
    display: flex;
    justify-content: center; /* Membuat kedua panel berada di tengah halaman */
    gap: 20px;              /* Memberikan jarak pemisah antara panel kiri dan kanan */
    max-width: 1000px;      /* Batas maksimal lebar total gabungan kedua panel */
    margin: 40px auto;       /* Membuat kontainer berada di tengah halaman secara horizontal */
    flex-wrap: wrap;        /* Otomatis turun ke bawah jika dibuka di layar HP yang sempit */
}
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { min-height: 100vh; background: #0b0f19; color: #fff; font-family: Arial, Helvetica, sans-serif; }
        .container { width: 100%; max-width: 1200px; margin: auto; padding: 35px 25px 50px; }
        .showcase { margin-bottom: 35px; text-align: center; }
        .tagline { margin-bottom: 12px; color: #8b5cf6; font-size: 13px; font-weight: 700; letter-spacing: 3px; text-transform: uppercase; }
        .showcase h1 { margin-bottom: 10px; font-size: 34px; font-weight: 800; }
        .showcase p, .headline p { color: #9ca3af; font-size: 14px; }
        .main-card { padding: 30px; border: 1px solid #1f2937; border-radius: 24px; background: #111827; box-shadow: 0 20px 60px rgba(0, 0, 0, .35); }
        .headline { margin-bottom: 25px; text-align: center; }
        .headline h2 { margin-bottom: 8px; font-size: 22px; font-weight: 800; }
        .control-area, .winner-actions { display: flex; justify-content: center; align-items: center; gap: 12px; flex-wrap: wrap; }
        .control-area { margin-bottom: 25px; }
        #itemSelect { min-width: 280px; padding: 13px 18px; border: 1px solid #374151; border-radius: 12px; outline: none; background: #1f2937; color: #fff; font-size: 14px; cursor: pointer; }
        #itemSelect:focus { border-color: #8b5cf6; }
        button { border: none; cursor: pointer; font-family: inherit; transition: all .2s ease; }
        .sound-btn { width: 46px; height: 46px; border: 1px solid #374151; border-radius: 12px; background: #1f2937; color: #fff; font-size: 18px; }
        .sound-btn:hover { background: #374151; }
        .spin-btn, .stop-btn, .save-btn, .skip-btn { 
            padding: 14px 24px; 
            border-radius: 12px; 
            color: #fff; 
            font-size: 14px; 
            max-width: 100%; 
            font-weight: 800; }
        .spin-btn { background: #8b5cf6; box-shadow: 0 8px 25px rgba(139, 92, 246, .25); }
        .spin-btn:hover { background: #7c3aed; transform: translateY(-1px); }
        .stop-btn { background: #ef4444; box-shadow: 0 8px 25px rgba(239, 68, 68, .25); }
        .stop-btn:hover { background: #dc2626; transform: translateY(-1px); }
        .save-btn { background: #22c55e; }
        .save-btn:hover { background: #16a34a; transform: translateY(-1px); }
        .skip-btn { border: 1px solid #4b5563; background: #374151; }
        .skip-btn:hover { background: #4b5563; }
        button:disabled { opacity: .5; cursor: not-allowed; transform: none; }
        .draw-panel {
    flex: 1;                /* Membuat panel kiri fleksibel mengisi ruang */
    max-width: 460px; 
    padding: 28px 24px; 
    border: 1px solid #312e81; 
    border-radius: 18px; 
    background: linear-gradient(135deg, #171334, #0f172a); 
    text-align: center; 
}

.draw-panel2 {
    flex: 1;                /* Membuat panel kanan fleksibel mengisi ruang */
    max-width: 100%; 
    padding: 28px 24px; 
    border: 1px solid #312e81; 
    border-radius: 18px; 
    background: linear-gradient(135deg, #171334, #0f172a); 
    text-align: center; 
}
        .draw-label { display: block; color: #a78bfa; font-size: 12px; font-weight: 800; letter-spacing: 1.5px; }
        .draw-number { display: block; min-height: 84px; margin-top: 10px; color: #fff; font-size: clamp(48px, 10vw, 84px); font-variant-numeric: tabular-nums; line-height: 1; }
        .result-box { display: flex; flex-direction: column; justify-content: center; align-items: center; width: 100%; min-height: 85px; margin-bottom: 18px; padding: 15px 20px; border: 1px solid #1f2937; border-radius: 16px; background: #0f172a; text-align: center; }
        .result-status { margin-bottom: 6px; color: #9ca3af; font-size: 12px; }
        .result-name { color: #fff; font-size: 30px; font-weight: 900; }
        .result-nik { margin-top: 5px; color: #c4b5fd; font-family: monospace; font-size: 30px; font-weight: 900; }
        .result-dept { margin-top: 4px; color: #9ca3af; font-size: 30px; }
        .result-item { margin-top: 5px; color: #a78bfa; font-size: 30px; font-weight: 700; }
        .winner-actions { margin: 18px 0 25px; }
        .stats-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 15px; margin-bottom: 30px; }
        .stat-card, .history-card { border: 1px solid #1f2937; border-radius: 16px; background: #0f172a; }
        .stat-card { padding: 20px; text-align: center; }
        .stat-label { margin-bottom: 8px; color: #9ca3af; font-size: 12px; }
        .stat-value { font-size: 28px; font-weight: 900; }
        .history-title { margin-bottom: 15px; font-size: 18px; font-weight: 800; }
        .history-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 15px; }
        .history-card { padding: 18px; }
        .history-header { display: flex; justify-content: space-between; align-items: center; gap: 10px; margin-bottom: 14px; }
        .history-item-name { font-size: 15px; font-weight: 800; }
        .history-stock { color: #a78bfa; font-size: 12px; font-weight: 700; }
        .history-status { display: inline-block; margin-bottom: 10px; padding: 5px 9px; border-radius: 8px; background: #1f2937; color: #9ca3af; font-size: 11px; }
        .history-winners { color: #d1d5db; font-size: 13px; line-height: 1.6; }
        .empty-winner { color: #6b7280; }
        .loading { opacity: .6; pointer-events: none; }
        @media (max-width: 700px) { .container { padding: 20px 15px 40px; } .main-card { padding: 20px 15px; } .showcase h1 { font-size: 26px; } .stats-grid, .history-grid { grid-template-columns: 1fr; } #itemSelect { width: 100%; min-width: 0; } .result-name { font-size: 20px; } }
    </style>
</head>
<body>
<div class="container">
    <div class="showcase">
        <h1>Undian Hadiah Karyawan Sinar Jaya Group</h1>
        <p>Sistem pengundian hadiah secara langsung dan transparan.</p>
    </div>

    <div class="main-card">
        <div class="headline">
            <h2>SIAP UNTUK MENENTUKAN PEMENANG?</h2>
            <p>Pilih hadiah terlebih dahulu, lalu tekan Undi Nomor.</p>
        </div>

        <div class="control-area">
            <select id="itemSelect">
                <option value="">-- Pilih Hadiah --</option>
                <?php foreach ($items as $item): ?>
                    <?php $stock = (int) $item['stock']; ?>
                    <option value="<?= (int) $item['id']; ?>" data-stock="<?= $stock; ?>" <?= $stock <= 0 ? 'disabled' : ''; ?>>
                        <?= htmlspecialchars($item['item_name']); ?> — Stok: <?= $stock; ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <button type="button" class="sound-btn" id="muteBtn" title="Aktif/nonaktifkan suara">🔊</button>
        </div>
        <div class="draw-container">

    <!-- Panel Kiri (Tempat Angka Terpilih) -->
    <div class="draw-panel">
            <span class="draw-label">NOMOR PESERTA TERPILIH</span>
            <strong class="draw-number" id="drawNumber"></strong>
        <!-- Tempat memunculkan hasil angka/karyawan -->
    

    <!-- Panel Kanan (Tempat Tombol Draw) -->
        <div style="display: flex; justify-content: center; align-items: center; gap: 15px; margin-top: 20px;">
            <!-- Tombol Sound  -->
            <!-- Tombol Undi -->
            <button type="button" class="draw-panel2 spin-btn" id="spinBtn">🎲 UNDI NOMOR</button>
            <button type="button" class="draw-panel2 stop-btn" id="stopBtn" style="display:none;">⏹ STOP UNDI</button>
        </div>

</div>


</div>

        <div class="result-box" id="resultBox">
            <div class="result-status" id="resultStatus">Pilih hadiah dan tekan Undi Nomor</div>
            <table style="width:100%; margin-top:5px; text-align:center;">
                <tr>
                    <td style="width:50%;"><div class="result-name" id="resultName"></div></td>
                    <td style="width:50%;"><div class="result-nik" id="resultNik"></div></td>
                </tr>
                <tr>
                    <td><div class="result-dept" id="resultDept"></div></td>
                    <td><div class="result-item" id="resultItem"></div></td>
                </tr>
            </table>
        </div>

        <div class="winner-actions" id="winnerActions" style="display:none;">
            <button type="button" class="save-btn" id="saveWinnerBtn">💾 Simpan Pemenang</button>
            <button type="button" class="skip-btn" id="skipWinnerBtn">⏭️ Skip / Orang Tidak Ada</button>
        </div>

        <div class="stats-grid">
            <div class="stat-card"><div class="stat-label">Sisa Kandidat</div><div class="stat-value" id="candidateCount"><?= isset($candidate_count) ? (int) $candidate_count : count($employees); ?></div></div>
            <div class="stat-card"><div class="stat-label">Total Jenis Hadiah</div><div class="stat-value"><?= count($items); ?></div></div>
            <div class="stat-card"><div class="stat-label">Sisa Stok Hadiah</div><div class="stat-value" id="totalStock"><?php $total_stock = 0; foreach ($items as $item) { $total_stock += (int) $item['stock']; } echo $total_stock; ?></div></div>
        </div>

        <div class="history-title">Rekap Pemenang</div>
        <div class="history-grid" id="historyGrid">
            <?php foreach ($items as $item): ?>
                <?php $stock = (int) $item['stock']; $nama_pemenang = !empty($item['nama_pemenang']) ? $item['nama_pemenang'] : ''; ?>
                <div class="history-card" data-item-id="<?= (int) $item['id']; ?>">
                    <div class="history-header"><div class="history-item-name"><?= htmlspecialchars($item['item_name']); ?></div><div class="history-stock" data-stock>Stok: <?= $stock; ?></div></div>
                    <div class="history-status" data-status><?= $stock > 0 ? 'Tersedia' : 'Stok Habis'; ?></div>
                    <div class="history-winners" data-winners><?php if ($nama_pemenang): ?><?= htmlspecialchars($nama_pemenang); ?><?php else: ?><span class="empty-winner">Belum ada pemenang</span><?php endif; ?></div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<audio id="victorySound" preload="auto"><source src="<?= base_url('sounds/victory.mp3'); ?>" type="audio/mpeg"></audio>
<audio id="drumSound" preload="auto" loop><source src="<?= base_url(); ?>uploads/drum.mp3" type="audio/mpeg"></audio>

<script>
const pendingWinner = <?= json_encode(!empty($pending_winner) ? $pending_winner : null, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES); ?>;
const spinBtn = document.getElementById('spinBtn');
const stopBtn = document.getElementById('stopBtn');
const saveWinnerBtn = document.getElementById('saveWinnerBtn');
const skipWinnerBtn = document.getElementById('skipWinnerBtn');
const winnerActions = document.getElementById('winnerActions');
const itemSelect = document.getElementById('itemSelect');
const drawNumber = document.getElementById('drawNumber');
const resultStatus = document.getElementById('resultStatus');
const resultName = document.getElementById('resultName');
const resultNik = document.getElementById('resultNik');
const resultDept = document.getElementById('resultDept');
const resultItem = document.getElementById('resultItem');
const candidateCount = document.getElementById('candidateCount');
const totalStock = document.getElementById('totalStock');
const muteBtn = document.getElementById('muteBtn');
const victorySound = document.getElementById('victorySound');
const drumSound = document.getElementById('drumSound');
let isDrawing = false;
let soundMuted = false;
let currentPendingWinner = pendingWinner;
let pendingDrawData = null;
let drawInterval = null;
let stopRequested = false;

function playVictory() { if (!soundMuted) { try { victorySound.currentTime = 0; victorySound.play().catch(function () {}); } catch (error) {} } }
function stopVictory() { try { victorySound.pause(); victorySound.currentTime = 0; } catch (error) {} }
function playDrum() { if (!soundMuted) { try { drumSound.currentTime = 0; drumSound.play().catch(function () {}); } catch (error) {} } }
function stopDrum() { try { drumSound.pause(); drumSound.currentTime = 0; } catch (error) {} }
function showWinner(name, nik, department, itemName) { 
    resultStatus.textContent = 'CALON PEMENANG'; 
    resultName.textContent = 'NAMA: ' + name || '—'; 
    resultNik.textContent = nik ? 'NIK: ' + nik : ''; 
    resultDept.textContent = 'DEPARTEMEN: ' + department || ''; 
    resultItem.textContent = itemName ? 'Hadiah : 🎁 ' + itemName : ''; }
function resetResult() { resultStatus.textContent = 'Pilih hadiah dan tekan Undi Nomor'; resultName.textContent = '—'; resultNik.textContent = ''; resultDept.textContent = ''; resultItem.textContent = ''; drawNumber.textContent = '—'; }
function showWinnerActions() { winnerActions.style.display = 'flex'; spinBtn.style.display = 'none'; }
function hideWinnerActions() { winnerActions.style.display = 'none'; spinBtn.style.display = 'inline-block'; }
function updateCandidateCount(remainingCount) { candidateCount.textContent = Math.max(0, parseInt(remainingCount, 10) || 0); }
function updateTotalStock() { let total = 0; itemSelect.querySelectorAll('option[data-stock]').forEach(function (option) { total += parseInt(option.dataset.stock || 0, 10); }); totalStock.textContent = total; }
function updateItemStock(itemId, stock) {
    const option = itemSelect.querySelector('option[value="' + itemId + '"]');
    const card = document.querySelector('.history-card[data-item-id="' + itemId + '"]');
    if (option) { option.dataset.stock = stock; option.disabled = stock <= 0; option.textContent = option.textContent.replace(/Stok:\s*\d+/, 'Stok: ' + stock); }
    if (card) { card.querySelector('[data-stock]').textContent = 'Stok: ' + stock; card.querySelector('[data-status]').textContent = stock > 0 ? 'Tersedia' : 'Stok Habis'; }
    updateTotalStock();
}
function addWinnerToHistory(itemId, name) {
    const card = document.querySelector('.history-card[data-item-id="' + itemId + '"]');
    if (!card) return;
    const winners = card.querySelector('[data-winners]');
    const empty = winners.querySelector('.empty-winner');
    if (empty) winners.textContent = name; else winners.appendChild(document.createElement('br')).parentNode.appendChild(document.createTextNode(name));
}
function setDrawingState(active) { isDrawing = active; spinBtn.disabled = active; itemSelect.disabled = active; spinBtn.classList.toggle('loading', active); }
function startNumberAnimation() {
    const maxNumber = Math.max(1, parseInt(candidateCount.textContent, 10) || 1);
    drawNumber.textContent = Math.floor(Math.random() * maxNumber) + 1;
    drawInterval = setInterval(function () {
        drawNumber.textContent = Math.floor(Math.random() * maxNumber) + 1;
    }, 65);
}
function stopNumberAnimation() { if (drawInterval) { clearInterval(drawInterval); drawInterval = null; } stopDrum(); }
function finishDraw(data) {
    stopNumberAnimation();
    pendingDrawData = null;
    currentPendingWinner = { employee_id: data.winner_id, employee_number: data.winner_number || data.winner_id, employee_nik: data.winner_nik || '', employee_name: data.winner_name, employee_department: data.winner_dept, item_id: data.item_id, item_name: data.item_name };
    drawNumber.textContent = currentPendingWinner.employee_number;
    showWinner(data.winner_name, data.winner_nik, data.winner_dept, data.item_name);
    stopBtn.style.display = 'none';
    showWinnerActions();
    playVictory();
    setDrawingState(false);
    itemSelect.disabled = true;
}

muteBtn.addEventListener('click', function () { soundMuted = !soundMuted; if (soundMuted) { stopDrum(); stopVictory(); } muteBtn.textContent = soundMuted ? '🔇' : '🔊'; });

spinBtn.addEventListener('click', function () {
    //launchIntoFullscreen(document.documentElement);
    if (isDrawing) return;
    if (currentPendingWinner) return alert('Masih ada calon pemenang yang belum diproses. Silakan Simpan atau Skip terlebih dahulu.');
    const itemId = parseInt(itemSelect.value || 0, 10);
    if (!itemId) return alert('Silakan pilih hadiah terlebih dahulu.');
    if (itemSelect.options[itemSelect.selectedIndex].disabled) return alert('Stok hadiah ini sudah habis.');

    setDrawingState(true);
    resultStatus.textContent = 'MENENTUKAN NOMOR PEMENANG...';
    resultName.textContent = '—'; resultNik.textContent = ''; resultDept.textContent = ''; resultItem.textContent = '';
    stopRequested = false;
    pendingDrawData = null;
    spinBtn.style.display = 'none';
    stopBtn.style.display = 'inline-block';
    stopBtn.disabled = false;
    stopVictory();
    startNumberAnimation();
    playDrum();
    fetch('<?= site_url('undian/draw'); ?>', { method: 'POST', headers: { 'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8' }, body: 'item_id=' + encodeURIComponent(itemId) })
        .then(function (response) { return response.json(); })
        .then(function (data) {
            if (data.status !== 'success') throw new Error(data.message || 'Gagal menentukan pemenang.');
            pendingDrawData = data;
            if (stopRequested) finishDraw(data);
            else resultStatus.textContent = 'NOMOR BERPUTAR... TEKAN STOP UNDI';
        })
        .catch(function (error) { stopNumberAnimation(); stopBtn.style.display = 'none'; spinBtn.style.display = 'inline-block'; setDrawingState(false); resetResult(); alert(error.message || 'Terjadi kesalahan.'); });
});

stopBtn.addEventListener('click', function () {
    if (!isDrawing) return;
    stopRequested = true;
    stopBtn.disabled = true;
    stopNumberAnimation();
    resultStatus.textContent = 'MENAMPILKAN PEMENANG...';
    if (pendingDrawData) finishDraw(pendingDrawData);
});

saveWinnerBtn.addEventListener('click', function () {
    if (!currentPendingWinner) return alert('Tidak ada calon pemenang.');
    stopVictory();
    if (!confirm('Simpan ' + currentPendingWinner.employee_name + ' sebagai pemenang ' + currentPendingWinner.item_name + '?')) return;
    saveWinnerBtn.disabled = true; skipWinnerBtn.disabled = true; saveWinnerBtn.textContent = '⏳ Menyimpan...';
    fetch('<?= site_url('undian/save_winner'); ?>', { method: 'POST', headers: { 'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8' }, body: '' })
        .then(function (response) { return response.json(); })
         .then(function (data) {
            if (data.status !== 'success') throw new Error(data.message || 'Pemenang gagal disimpan.');
            
            // 1. Perbarui sisa kandidat dan stok item di background
            updateCandidateCount(data.remaining_candidate_count !== undefined ? data.remaining_candidate_count : parseInt(candidateCount.textContent, 10) - 1);
            updateItemStock(data.item_id, parseInt(data.remaining_stock, 10));
            addWinnerToHistory(data.item_id, data.winner_name);
            
            // 2. Kosongkan data calon pemenang yang baru saja disimpan
            currentPendingWinner = null;
            
            // 3. Reset teks tampilan hasil undian menjadi kosong / default seperti baru
            resultStatus.textContent = 'SIAP MEMULAI UNDIAN'; 
            resultName.textContent = '-'; 
            resultNik.textContent = ''; 
            resultDept.textContent = ''; 
            resultItem.textContent = '';
            
            // 4. Aktifkan kembali kontrol tombol utama
            hideWinnerActions(); 
            itemSelect.disabled = false; 
            spinBtn.disabled = false; 
            saveWinnerBtn.disabled = false; 
            skipWinnerBtn.disabled = false; 
            saveWinnerBtn.textContent = '💾 Simpan Pemenang';
            
            // 5. Kembalikan dropdown pilihan item ke opsi default (kosong)
            itemSelect.value = ''; 

            // tambahan agar halaman di-refresh untuk menampilkan data terbaru dari server (opsional)
            location.reload(); 
           // alert('Data pemenang disimpan dan tampilan telah di-reset!');
        })
        .catch(function (error) { alert(error.message || 'Gagal menyimpan pemenang.'); saveWinnerBtn.disabled = false; skipWinnerBtn.disabled = false; saveWinnerBtn.textContent = '💾 Simpan Pemenang'; });
});

skipWinnerBtn.addEventListener('click', function () {
    if (!currentPendingWinner) return alert('Tidak ada calon pemenang.');
    stopVictory();
    if (!confirm('Skip ' + currentPendingWinner.employee_name + '? Orang ini tidak akan disimpan sebagai pemenang.')) return;
    saveWinnerBtn.disabled = true; skipWinnerBtn.disabled = true; skipWinnerBtn.textContent = '⏳ Memproses...';
    fetch('<?= site_url('undian/skip_winner'); ?>', { method: 'POST', headers: { 'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8' }, body: '' })
        .then(function (response) { return response.json(); })
        .then(function (data) {
            if (data.status !== 'success') throw new Error(data.message || 'Gagal melakukan skip.');
            resultStatus.textContent = 'CALON PEMENANG DI-SKIP'; resultName.textContent = data.skipped_name || currentPendingWinner.employee_name; resultNik.textContent = ''; resultDept.textContent = ''; resultItem.textContent = 'Orang tidak ada — belum disimpan';
            currentPendingWinner = null; drawNumber.textContent = '—'; hideWinnerActions(); itemSelect.disabled = false; spinBtn.disabled = false; saveWinnerBtn.disabled = false; skipWinnerBtn.disabled = false; skipWinnerBtn.textContent = '⏭️ Skip / Orang Tidak Ada';
        })
        .catch(function (error) { alert(error.message || 'Gagal melakukan skip.'); saveWinnerBtn.disabled = false; skipWinnerBtn.disabled = false; skipWinnerBtn.textContent = '⏭️ Skip / Orang Tidak Ada'; });
});

itemSelect.addEventListener('change', function () { const option = itemSelect.options[itemSelect.selectedIndex]; if (option && option.value && parseInt(option.dataset.stock || 0, 10) <= 0) { alert('Stok hadiah ini sudah habis.'); itemSelect.value = ''; } });
if (currentPendingWinner) { itemSelect.value = currentPendingWinner.item_id; drawNumber.textContent = currentPendingWinner.employee_number || currentPendingWinner.employee_id; showWinner(currentPendingWinner.employee_name, currentPendingWinner.employee_nik, currentPendingWinner.employee_department, currentPendingWinner.item_name); showWinnerActions(); itemSelect.disabled = true; spinBtn.disabled = true; }
updateTotalStock();
function launchIntoFullscreen(element) {
    if(element.requestFullscreen) {
        element.requestFullscreen();
    } else if(element.mozRequestFullScreen) { // Firefox
        element.mozRequestFullScreen();
    } else if(element.webkitRequestFullscreen) { // Chrome, Safari & Opera
        element.webkitRequestFullscreen();
    } else if(element.msRequestFullscreen) { // IE/Edge
        element.msRequestFullscreen();
    }
}
</script>

</body>
</html>
