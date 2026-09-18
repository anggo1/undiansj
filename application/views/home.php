<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Undian Spin Wheel Khas Go Spin</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f4f7f6;
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 20px;
            margin: 0;
        }
        h1 { color: #333; }
        .container {
            display: flex;
            flex-wrap: wrap;
            gap: 40px;
            justify-content: center;
            align-items: flex-start;
            margin-top: 20px;
        }
        .wheel-box {
            position: relative;
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        /* Penunjuk panah di atas roda */
        .arrow {
            position: absolute;
            top: -15px;
            left: 50%;
            transform: translateX(-50%);
            width: 0;
            height: 0;
            border-left: 20px solid transparent;
            border-right: 20px solid transparent;
            border-top: 30px solid #e74c3c;
            z-index: 10;
        }
        canvas {
            background-color: #fff;
            border-radius: 50%;
            box-shadow: 0 8px 20px rgba(0,0,0,0.15);
            transition: transform 4s cubic-bezier(0.17, 0.67, 0.1, 1);
        }
        .spin-btn {
            margin-top: 20px;
            padding: 12px 35px;
            font-size: 18px;
            font-weight: bold;
            color: #fff;
            background-color: #2ecc71;
            border: none;
            border-radius: 25px;
            cursor: pointer;
            box-shadow: 0 4px 10px rgba(46, 204, 113, 0.4);
        }
        .spin-btn:hover { background-color: #27ae60; }
        .spin-btn:disabled { background-color: #bdc3c7; cursor: not-allowed; }
        
        .input-box {
            background: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.05);
            width: 300px;
        }
        textarea {
            width: 100%;
            height: 150px;
            padding: 10px;
            box-sizing: border-box;
            border: 1px solid #ccc;
            border-radius: 5px;
            resize: vertical;
            font-size: 14px;
        }
    </style>
</head>
<body>

    <h1>🎡 Custom Lucky Spin Wheel</h1>
    
    <div class="container">
        <!-- Area Roda Putar -->
        <div class="wheel-box">
            <div class="arrow"></div>
            <canvas id="wheel" width="400" height="400"></canvas>
            <button class="spin-btn" id="spinBtn" onclick="spinWheel()">PUTAR!</button>
        </div>

        <!-- Area Input Pilihan (Ciri khas Go Spin Wheel) -->
        <div class="input-box">
            <h3>Daftar Pilihan / Nama</h3>
            <p style="font-size: 12px; color: #7f8c8d;">Masukkan satu nama per baris:</p>
            <textarea id="itemsInput" oninput="updateWheel()">Hadiah A\nHadiah B\nHadiah C\nHadiah D\nHadiah E\nHadiah F</textarea>
        </div>
    </div>

    <script>
        const canvas = document.getElementById("wheel");
        const ctx = canvas.getContext("2d");
        const spinBtn = document.getElementById("spinBtn");
        const itemsInput = document.getElementById("itemsInput");

        let items = [];
        const colors = ["#f1c40f", "#e67e22", "#e74c3c", "#9b59b6", "#3498db", "#1abc9c", "#2ecc71"];
        let currentRotation = 0;
        let isSpinning = false;

        function updateWheel() {
            // Mengambil baris teks dan menghapus baris kosong
            items = itemsInput.value.split('\n').map(item => item.trim()).filter(item => item !== "");
            drawWheel();
        }

        function drawWheel() {
            const numSegments = items.length;
            if (numSegments === 0) {
                ctx.clearRect(0, 0, canvas.width, canvas.height);
                return;
            }

            const arcSize = (2 * Math.PI) / numSegments;
            const radius = canvas.width / 2;

            ctx.clearRect(0, 0, canvas.width, canvas.height);

            for (let i = 0; i < numSegments; i++) {
                const angle = i * arcSize;
                
                // Gambar Segmen/Irisan Roda
                ctx.beginPath();
                ctx.fillStyle = colors[i % colors.length];
                ctx.moveTo(radius, radius);
                ctx.arc(radius, radius, radius, angle, angle + arcSize);
                ctx.lineTo(radius, radius);
                ctx.fill();

                // Tulis Teks di Dalam Segmen
                ctx.save();
                ctx.translate(radius, radius);
                ctx.rotate(angle + arcSize / 2);
                ctx.textAlign = "right";
                ctx.fillStyle = "#fff";
                ctx.font = "bold 14px Arial";
                ctx.fillText(items[i], radius - 20, 5);
                ctx.restore();
            }
        }

        function spinWheel() {
            if (isSpinning || items.length === 0) return;

            isSpinning = true;
            spinBtn.disabled = true;

            // Menentukan putaran acak yang besar agar berputar beberapa kali
            const extraDegrees = Math.floor(Math.random() * 360) + 1440; // minimal 4 putaran penuh
            currentRotation += extraDegrees;

            // Efek transisi CSS animasi berputar
            canvas.style.transform = `rotate(${currentRotation}deg)`;

            // Hitung pemenang setelah animasi selesai (4000ms sesuai CSS)
            setTimeout(() => {
                isSpinning = false;
                spinBtn.disabled = false;

                // Hitung posisi derajat akhir relatif (0 - 360)
                const actualDegrees = (360 - (currentRotation % 360)) % 360;
                
                // Panah penunjuk berada di atas (-90 derajat dari titik awal canvas kanan)
                const adjustedDegrees = (actualDegrees + 90) % 360; 
                
                const numSegments = items.length;
                const degreesPerSegment = 360 / numSegments;
                const winnerIndex = Math.floor(adjustedDegrees / degreesPerSegment) % numSegments;

                alert(`🎉 Selamat! Hasil undian Anda: ${items[winnerIndex]}`);
            }, 4000);
        }

        // Jalankan saat pertama kali dimuat
        updateWheel();
    </script>
</body>
</html>
