<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>E-Kartu {{ $eKartu->no_anggota }}</title>
    <style>
        body {
            margin: 0;
            padding: 24px;
            color: #ffffff;
            font-family: DejaVu Sans, sans-serif;
        }

        .card {
            min-height: 300px;
            padding: 36px;
            border-radius: 18px;
            background: #312e81;
        }

        .brand {
            color: #c7d2fe;
            font-size: 13px;
            letter-spacing: 3px;
        }

        h1 {
            margin: 8px 0 34px;
            font-size: 25px;
        }

        .label {
            margin-top: 18px;
            color: #c7d2fe;
            font-size: 12px;
        }

        .value {
            margin-top: 4px;
            font-size: 18px;
            font-weight: bold;
        }

        .code {
            margin-top: 28px;
            padding: 14px;
            color: #312e81;
            background: #ffffff;
            font-family: DejaVu Sans Mono, monospace;
            font-size: 11px;
        }
    </style>
</head>
<body>
    <div class="card">
        <div class="brand">SIPADI</div>
        <h1>Kartu Anggota Perpustakaan</h1>

        <div class="label">Nama</div>
        <div class="value">{{ $anggota->nama_lengkap }}</div>

        <div class="label">Nomor Anggota</div>
        <div class="value">{{ $eKartu->no_anggota }}</div>

        <div class="label">Kalangan / Masa Berlaku</div>
        <div class="value">{{ $eKartu->kalangan }} / {{ $eKartu->masa_berlaku?->format('d-m-Y') }}</div>

        <div class="code">{{ $eKartu->barcode }}</div>
    </div>
</body>
</html>
