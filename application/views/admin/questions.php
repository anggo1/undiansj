<!doctype html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Kelola Pertanyaan1</title>
    <style>
    * {
        box-sizing: border-box
    }

    body {
        margin: 0;
        background: #f5f6fa;
        font-family: system-ui;
        color: #202534
    }

    .wrap {
        max-width: 1050px;
        margin: 40px auto;
        padding: 0 18px
    }

    .top {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 22px
    }

    .btn {
        display: inline-block;
        background: #5d5fe8;
        color: #fff;
        text-decoration: none;
        border: 0;
        border-radius: 10px;
        padding: 11px 17px;
        font-weight: 700;
        cursor: pointer
    }

    .grid {
        display: grid;
        grid-template-columns: 1fr 1.35fr;
        gap: 18px
    }

    .card {
        background: #fff;
        border-radius: 17px;
        padding: 24px;
        box-shadow: 0 8px 30px rgba(30, 40, 80, .08)
    }

    h1 {
        margin: 0 0 5px
    }

    .muted {
        color: #7b8395
    }

    .input,
    textarea,
    select {
        width: 100%;
        padding: 12px;
        border: 1px solid #dce1eb;
        border-radius: 10px;
        font: inherit;
        margin: 7px 0 15px
    }

    textarea {
        min-height: 100px
    }

    .row {
        display: flex;
        gap: 12px
    }

    .row>* {
        flex: 1
    }

    .check {
        display: flex;
        gap: 8px;
        align-items: center;
        margin-bottom: 18px
    }

    .q {
        padding: 16px 0;
        border-bottom: 1px solid #edf0f5
    }

    .q:last-child {
        border: 0
    }

    .tag {
        font-size: 11px;
        background: #eef0ff;
        color: #5559c8;
        padding: 5px 8px;
        border-radius: 20px
    }

    .danger {
        color: #d43b3b;
        text-decoration: none;
        font-size: 13px
    }

    .nav {
        margin-top: 15px;
        display: flex;
        gap: 10px
    }

    @media(max-width:760px) {
        .grid {
            grid-template-columns: 1fr
        }

        .top {
            align-items: flex-start;
            gap: 10px;
            flex-direction: column
        }
    }
    </style>
</head>

<body>
    <div class="wrap">
        <div class="top">
            <div>
                <h1>Kelola Pertanyaan</h1>
                <div class="muted"><?= html_escape($survey->title) ?></div>
            </div>
            <div class="nav"><a class="btn" href="<?= site_url('survey/'.$survey->id) ?>" target="_blank">Lihat
                    Form</a><a class="btn" href="<?= site_url('admin/survey/'.$survey->id.'/results') ?>">Rekap
                    Hasil</a></div>
        </div>
        <div class="grid">
            <div class="card">
                <h2>Tambah Pertanyaan</h2><?= validation_errors('<p style="color:#c33">','</p>') ?><form method="post"
                    action="<?= site_url('admin/survey/'.$survey->id.'/add-question') ?>">
                    <label>Pertanyaan</label><input class="input" name="question_text"
                        placeholder="Contoh: Seberapa puas kamu...?" required><label>Tipe Jawaban</label><select
                        name="question_type">
                        <option value="text">Jawaban singkat</option>
                        <option value="textarea">Paragraf</option>
                        <option value="radio">Pilihan satu</option>
                        <option value="checkbox">Pilihan banyak</option>
                        <option value="select">Dropdown</option>
                    </select><label>Opsi jawaban</label><textarea name="options"
                        placeholder="Sangat Puas&#10;Puas&#10;Cukup&#10;Tidak Puas"></textarea>
                    <div class="row">
                        <div><label>Urutan</label><input class="input" type="number" name="sort_order" value="1"></div>
                    </div><label class="check"><input type="checkbox" name="is_required" checked> Wajib
                        diisi</label><button class="btn" type="submit">+ Tambahkan</button>
                </form>
            </div>
            <div class="card">
                <h2>Daftar Pertanyaan</h2><?php foreach($questions as $i=>$q): ?><div class="q">
                    <div style="display:flex;justify-content:space-between;gap:10px">
                        <b><?= ($i+1).'. '.html_escape($q->question_text) ?></b><span
                            class="tag"><?= html_escape($q->question_type) ?></span></div><?php if($q->options): ?><div
                        class="muted" style="margin-top:8px"><?= nl2br(html_escape($q->options)) ?></div><?php endif; ?>
                    <div style="margin-top:8px"><span
                            class="muted"><?= $q->is_required?'Wajib diisi':'Opsional' ?></span> · <a class="danger"
                            href="<?= site_url('admin/survey/'.$survey->id.'/delete-question/'.$q->id) ?>"
                            onclick="return confirm('Hapus pertanyaan ini?')">Hapus</a></div>
                </div><?php endforeach; ?>
            </div>
        </div>
    </div>
</body>

</html>