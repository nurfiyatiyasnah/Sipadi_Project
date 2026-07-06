<!DOCTYPE html>
<html lang="id">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>E-Kartu Anggota</title>
    <style>
        * {
            box-sizing: border-box;
        }

        @page {
            margin: 28pt;
        }

        body {
            margin: 0;
            background: #f8fafc;
            color: #061b3a;
            font-family: DejaVu Sans, sans-serif;
        }

        .page {
            width: 100%;
            background: #f8fafc;
        }

        .card {
            width: 700pt;
            background: #061b3a;
            border-radius: 30pt;
            color: #ffffff;
            padding: 30pt;
            page-break-inside: avoid;
        }

        .layout {
            width: 100%;
            border-collapse: collapse;
        }

        .left-panel {
            width: 56%;
            vertical-align: top;
            padding-right: 32pt;
        }

        .right-panel {
            width: 44%;
            vertical-align: top;
        }

        .brand {
            color: #ffdc7c;
            font-size: 12pt;
            font-weight: bold;
            letter-spacing: 4pt;
            text-transform: uppercase;
        }

        .title {
            margin: 18pt 0 54pt;
            font-size: 32pt;
            line-height: 1.1;
            font-weight: bold;
        }

        .identity {
            background: #1f3554;
            border-radius: 24pt;
            padding: 22pt;
        }

        .label {
            color: #cbd5e1;
            font-size: 10pt;
            margin-bottom: 5pt;
        }

        .value {
            font-size: 18pt;
            font-weight: bold;
            line-height: 1.25;
            margin-bottom: 18pt;
        }

        .number {
            font-family: DejaVu Sans Mono, monospace;
            font-size: 16pt;
        }

        .meta-table {
            width: 100%;
            border-collapse: collapse;
        }

        .meta-table td {
            width: 50%;
            vertical-align: top;
        }

        .white-box {
            background: #ffffff;
            color: #061b3a;
            border-radius: 24pt;
            padding: 28pt;
            text-align: center;
        }

        .avatar {
            width: 100pt;
            height: 100pt;
            margin: 0 auto 52pt;
            border-radius: 22pt;
            border: 3pt solid #ffdc7c;
            background: #f6f5e9;
            color: #061b3a;
            font-size: 48pt;
            font-weight: bold;
            line-height: 96pt;
            text-align: center;
        }

        .code {
            background: #f6f5e9;
            border-radius: 18pt;
            padding: 14pt;
            word-break: break-all;
        }

        .code-title {
            color: #64748b;
            font-size: 10pt;
            font-weight: bold;
            letter-spacing: 3pt;
            text-transform: uppercase;
            margin-bottom: 8pt;
        }

        .code-value {
            font-family: DejaVu Sans Mono, monospace;
            font-size: 8pt;
            line-height: 1.4;
        }

        .status {
            margin-top: 18pt;
            border-radius: 999px;
            background: #e8faf2;
            color: #047857;
            padding: 10pt 0;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="page">
        <div class="card">
            <table class="layout">
                <tr>
                    <td class="left-panel">
                        <div class="brand">SIPADI Bukittinggi</div>
                        <div class="title">Kartu Anggota<br>Digital</div>

                        <div class="identity">
                            <div class="label">Nama Anggota</div>
                            <div class="value">{{ $anggota->nama_lengkap }}</div>

                            <div class="label">Nomor Kartu / NIK</div>
                            <div class="value number">{{ $eKartu->no_anggota }}</div>

                            <table class="meta-table">
                                <tr>
                                    <td>
                                        <div class="label">Kalangan</div>
                                        <div class="value" style="font-size: 14pt; margin-bottom: 0;">{{ $eKartu->kalangan }}</div>
                                    </td>
                                    <td>
                                        <div class="label">Berlaku Sampai</div>
                                        <div class="value" style="font-size: 14pt; margin-bottom: 0;">{{ $eKartu->masa_berlaku?->translatedFormat('d F Y') }}</div>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </td>
                    <td class="right-panel">
                        <div class="white-box">
                            <div class="avatar">{{ mb_substr($anggota->nama_lengkap, 0, 1) }}</div>

                            <div class="code">
                                <div class="code-title">Kode Kartu</div>
                                <div class="code-value">{{ $eKartu->barcode }}</div>
                            </div>

                            <div class="status">Aktif</div>
                        </div>
                    </td>
                </tr>
            </table>
        </div>
    </div>
</body>
</html>
