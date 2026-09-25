<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Undian Gebyar Karyawan (CI3)</title>
    <style>
        body { text-align: center; font-family: 'Segoe UI', Arial, sans-serif; background: #f4f6f9; color: #333; margin: 0; padding: 20px; }
        .container { max-width: 800px; margin: auto; background: white; padding: 30px; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); }
        h1 { color: #2c3e50; margin-bottom: 20px; }
        select, button { padding: 12px 20px; font-size: 16px; margin: 10px 0; border-radius: 6px; border: 1px solid #ccc; width: 100%; max-width: 400px; box-sizing: border-box; }
        select { background-color: #fff; cursor: pointer; }
        button { background: #e74c3c; color: white; border: none; cursor: pointer; font-weight: bold; transition: background 0.2s; max-width: 250px; }
        button:hover { background: #c0392b; }
        .wheel-container { position: relative; display: inline-block; margin: 30px 0; }
        #canvas { border: 8px solid #2c3e50; border-radius: 50%; background: white; box-shadow: 0 8px 25px rgba(0,0,0,0.15); }
        .pointer { position: absolute; top: -18px; left: 50%; transform: translateX(-50%); width: 0; height: 0; border-left: 22px solid transparent; border-right: 22px solid transparent; border-top: 38px solid #e74c3c; z-index: 10; }
        .result-box { margin: 20px 0; font-size: 26px; font-weight: bold; color: #27ae60; min-height: 90px; line-height: 1.4; }
        .rekap-section { margin-top: 40px; text-align: left; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; background: white; }
        th, td { border: 1px solid #ddd; padding: 12px; text-align: left; }
        th { background-color: #2c3e50; color: white; }
        tr:nth-child(even) { background-color: #f9f9f9; }
        .status-badge { font-weight: bold; padding: 3px 8px; border-radius: 4px; font-size: 13px; }
        .status-available { color: #27ae60; background: #d4efdf; }
        .status-empty { color: #c0392b; background: #f9e79f; }
    </style>
</head>
<body>

<div class="container">
    <h1>🎯 Undian Hadiah Barang Karyawan</h1>
    
    <label for="itemSelect"><strong>Pilih Hadiah Barang Yang Akan Diundi:</strong></label>
    <br>
    <select id="itemSelect">
        <option value="">-- Silakan Pilih Hadiah Dahulu --</option>
        <?php foreach($items as $item): ?>
            <?php 
                $isDisabled = ($item['stock'] <= 0) ? 'disabled' : '';
                $statusText = ($item['stock'] <= 0) 
                    ? "[❌ SUDAH ADA PEMENANG: " . htmlspecialchars($item['nama_pemenang']) . "]" 
                    : "[🟢 Tersedia: " . $item['stock'] . " Unit]";
            ?>
            <option value="<?php echo $item['id']; ?>" <?php echo $isDisabled; ?>>
                <?php echo htmlspecialchars($item['item_name']); ?> <?php echo $statusText; ?>
            </option>
        <?php endforeach; ?>
    </select>
    
    <br>
    <div class="wheel-container">
        <div class="pointer"></div>
        <canvas id="canvas" width="460" height="460"></canvas>
    </div>
    
    <br>
    <button id="spinBtn">💥 PUTAR WHEEL NAMA</button>
    <div class="result-box" id="resultBox"></div>

    <div class="rekap-section">
        <h3>📊 Status Papan Rekapitulasi Hadiah</h3>
        <table>
            <thead>
                <tr>
                    <th>Nama Barang</th>
                    <th>Total Slot</th>
                    <th>Status</th>
                    <th>Nama Pemenang</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($items as $item): ?>
                    <tr>
                        <td><strong><?php echo htmlspecialchars($item['item_name']); ?></strong></td>
                        <td><?php echo ($item['stock'] + $item['total_terundi']); ?> Unit</td>
                        <td>
                            <?php if($item['stock'] <= 0): ?>
                                <span class="status-badge status-empty">Selesai Diundi</span>
                            <?php else: ?>
                                <span class="status-badge status-available">Tersedia (<?php echo $item['stock']; ?>)</span>
                            <?php endif; ?>
                        </td>
                        <td style="font-style: italic; color: #555;">
                            <?php echo $item['nama_pemenang'] ? htmlspecialchars($item['nama_pemenang']) : '-'; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- FILE AUDIO EFEK SUARA -->
<audio id="tickSound" src="<?php echo base_url('sounds/wheel-tick.mp3'); ?>" preload="auto"></audio>
<audio id="victorySound" src="<?php echo base_url('sounds/victory.mp3'); ?>" preload="auto"></audio>

<script>
    const employees = <?php echo json_encode($employees); ?>;
    const canvas = document.getElementById('canvas');
    const ctx = canvas.getContext('2d');
    const size = canvas.width;
    const center = size / 2;
    const numSegments = employees.length;
    const arc = 2 * Math.PI / numSegments;

    let currentAngle = 0;
    let isSpinning = false;

    const tickSound = document.getElementById('tickSound');
    const victorySound = document.getElementById('victorySound');
    tickSound.volume = 0.4;
    victorySound.volume = 0.7;

    const colors = ["#1abc9c", "#2ecc71", "#3498db", "#9b59b6", "#34495e", "#f1c40f", "#e67e22", "#e74c3c"];

    function drawWheel() {
        if(numSegments === 0) {
            ctx.clearRect(0, 0, size, size);
            ctx.font = "bold 16px Arial";
            ctx.fillStyle = "#333";
            ctx.textAlign = "center";
            ctx.fillText("Semua Karyawan Sudah Mendapat Undian", center, center);
            return;
        }

        employees.forEach((emp, i) => {
            const angle = currentAngle + i * arc;
            ctx.fillStyle = colors[i % colors.length];
            ctx.beginPath();
            ctx.moveTo(center, center);
            ctx.arc(center, center, center, angle, angle + arc);
            ctx.lineTo(center, center);
            ctx.fill();

            ctx.save();
            ctx.fillStyle = "#fff";
            ctx.translate(center, center);
            ctx.rotate(angle + arc / 2);
            ctx.textAlign = "right";
            ctx.font = "bold 11px Arial";
            const displayName = emp.name.length > 14 ? emp.name.substring(0,14) + '..' : emp.name;
            ctx.fillText(displayName + " ("+emp.department+")", center - 25, 4);
            ctx.restore();
        });
    }

    drawWheel();

    document.getElementById('spinBtn').addEventListener('click', function() {
        if (isSpinning || numSegments === 0) return;

        const itemId = document.getElementById('itemSelect').value;
        if(!itemId) {
            alert('Silakan pilih target hadiah barang yang ingin diundi terlebih dahulu!');
            return;
        }

        let formData = new FormData();
        formData.append('item_id', itemId);

        fetch('<?php echo base_url('undian/draw'); ?>', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'error') {
                alert(data.message);
                return;
            }

            isSpinning = true;
            document.getElementById('resultBox').innerText = "";
            victorySound.pause();
            victorySound.currentTime = 0;

            const winnerIndex = data.winner_index;
            const degreesPerSegment = 360 / numSegments;
            const targetAngle = 270 - (winnerIndex * degreesPerSegment) - (degreesPerSegment / 2);
            const finalRotation = targetAngle + 2160; 

            let startTimestamp = null;
            const duration = 5000; 
            let lastSegmentIndex = -1; 

            function animate(timestamp) {
                if (!startTimestamp) startTimestamp = timestamp;
                const elapsed = timestamp - startTimestamp;
                const progress = Math.min(elapsed / duration, 1);
                
                const easeOutCubic = 1 - Math.pow(1 - progress, 3);
                const currentRotation = easeOutCubic * finalRotation;
                
                currentAngle = (currentRotation * Math.PI) / 180;
                ctx.clearRect(0, 0, size, size);
                drawWheel();

                const normalizedRotation = (currentRotation % 360 + 360) % 360;
                const pointerAngleCorrection = (270 - normalizedRotation + 360) % 360;
                const currentSegmentIndex = Math.floor(pointerAngleCorrection / degreesPerSegment) % numSegments;

                if (currentSegmentIndex !== lastSegmentIndex && progress < 0.96) {
                    tickSound.currentTime = 0;
                    tickSound.play().catch(e => {});
                    lastSegmentIndex = currentSegmentIndex;
                }

                if (progress < 1) {
                    requestAnimationFrame(animate);
                } else {
                    isSpinning = false;
                    victorySound.play().catch(e => {});

                    document.getElementById('resultBox').innerHTML = 
                        `🎉 SELAMAT! 🎉<br>${data.winner_name} (${data.winner_dept})<br>Mendapatkan: ${data.item_name}`;
                    
                    setTimeout(() => { location.reload(); }, 6000);
                }
            }
            requestAnimationFrame(animate);
        });
    });
</script>
</body>
</html>
