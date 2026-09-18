<!DOCTYPE HTML>
<html>
    <head>
        <title>
            Doorprize Pertamina
        </title>
        <!-- Font Icon -->
        <script src="https://code.jquery.com/jquery-3.1.0.js"></script>
        <script src="public/js/script2.js"></script>
        <link
            rel="stylesheet"
            href="https://fonts.googleapis.com/icon?family=Material+Icons">
        <link
            rel="stylesheet"
            href="https://code.getmdl.io/1.3.0/material.teal-red.min.css"/>
        <script defer="defer" src="https://code.getmdl.io/1.3.0/material.min.js"></script>
        <link
            rel="stylesheet"
            href="https://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css"/>

        <link rel="stylesheet" href="<?= base_url()?>assets/style.css"/>
    </head>
    <body>

        <div id="div_refresh">
            <section>
                <!--
                <video
                    src="<?= base_url()?>uploads/video.mp4"
                    autoplay="autoplay"
                    muted="muted"
                    loop="loop"></video> 
-->
                <audio id="sound" src="<?= base_url()?>uploads/drum.mp3" loop="loop"></audio>
                <audio id="sound1" src="<?= base_url()?>uploads/winner.mp3"></audio>
                <h1>
                    <span class="nbr ltr"></span>
                    <form action="" onsubmit="return false;">

                        <br><br>

                        <br>
                        <b>
                            <div style="font-size: 100px; " id="random">00000</div>
                        </b>
                        <!-- <h2>Selamat Kepada:</h2> -->
                        <p>
                            <h2>
                                <b>
                                    <div class="background">
                                        <b>
                                            <span id="nama">..........</span></div>
                                    </b>
                                </b>
                            </h2>
                        </p>
                        <h4>
                            <b>
                                <div id="kota"></div>
                            </b>
                        </h4>

                        <input
                            value="Start"
                            class="tombol"
                            onclick="startRandomly(); return false;"
                            type="button">
                        <input
                            value="Stop"
                            class="tombol"
                            id="myButton"
                            onclick="stopRandomly()"
                            type="button">

                        <input
                            value="Reset"
                            class="tombol"
                            id="myButton"
                            onclick="document.location.reload(true)"
                            type="button">
                    </form>
                </h1>
            </section>
        </div>

        <script>

            var audio = document.getElementById('sound');
            var audio1 = document.getElementById('sound1');
            var interval = null;
            var slideSource = document.getElementById('nama');
            var randomValue = null;
            var id_anggota = [<?php foreach($anggota as $a){?>"00<?= $a->nomer?>", <?php }?>];
            var nama = [<?php foreach($anggota as $a){?>"<?= $a->nama?>", <?php }?>];
            var kota = [<?php foreach($anggota as $a){?>"<?= $a->kota?>", <?php }?>];
            var kosong = [<?php foreach($anggota as $a){?>"<?= $a->kosong?>", <?php }?>];

            function startRandomly() {
                interval = setInterval(function () {
                    randomValue = getRandomInt(0, id_anggota.length - 1);
                    document
                        .getElementById("random")
                        .innerHTML = id_anggota[randomValue];
                }, 1);

                audio.play();
                document
                    .documentElement
                    .requestFullscreen();

            }

            function stopRandomly() {
                clearInterval(interval);

                if (nama.length && randomValue >= 0) {
                    document
                        .getElementById("nama")
                        .innerHTML = nama[randomValue];
                }

                if (kota.length && randomValue >= 0) {
                    document
                        .getElementById("kota")
                        .innerHTML = kota[randomValue];

                }

                var video = document.getElementById('player');
                audio.pause();
                audio1.play();

                var ucapan = document.getElementById('slideSource');

                choices.splice(randomValue, 1);

                slideSource
                    .classList
                    .toggle('fade');
            }
			
			function fullscren(){
				document.documentElement.requestFullscreen();
			}

            function getRandomInt(min, max) {
                return Math.floor(Math.random() * (max - min + 1)) + min;
            }
        </script>

        <script>
            document.onkeydown = function (teziger) {
                switch (teziger.keyCode) {
                    case 13: // enter (start)
                        startRandomly();
                        break;
                    case 32: // space (start)
                        stopRandomly();
                        break;
                    case 82: // Tombol r (reset)
                        document.location.reload(true);
                        break;
                    case 70:
                        fullscren();
                        break;
                }

            }
        </script>

    </body>
</html>
