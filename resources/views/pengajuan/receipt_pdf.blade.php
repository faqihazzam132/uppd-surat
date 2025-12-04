<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Bukti Pengajuan</title>
    <style>
        body { font-family: DejaVu Sans, Arial, sans-serif; font-size: 12px; }
        .header { text-align: center; margin-bottom: 20px; }
        .header h2 { margin: 0 0 5px 0; }
        .header p { margin: 0; font-size: 11px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { text-align: left; padding: 4px 6px; font-size: 12px; }
        .label { width: 30%; font-weight: bold; }
        .value { width: 70%; }
        .small { font-size: 11px; }
        .box { border: 1px solid #000; padding: 8px; margin-top: 15px; }
    </style>
</head>
<body>
    <div class="header">
        <h2>Bukti Pengajuan Permohonan Online</h2>
        <p>UPPD Kalideres</p>
    </div>

    <div class="box">
        <table>
            <tr>
                <td class="label">Nomor Registrasi</td>
                <td class="value">: {{ $pengajuan->no_registrasi }}</td>
            </tr>
            <tr>
                <td class="label">Nama Pemohon</td>
                <td class="value">: {{ $user->name }}</td>
            </tr>
            <tr>
                <td class="label">NIK</td>
                <td class="value">: {{ $user->nik ?? '-' }}</td>
            </tr>
            <tr>
                <td class="label">Jenis Surat</td>
                <td class="value">: {{ $pengajuan->jenis_surat }}</td>
            </tr>
            <tr>
                <td class="label">Tanggal Pengajuan</td>
                <td class="value">: {{ $pengajuan->created_at->format('d M Y H:i') }}</td>
            </tr>
            <tr>
                <td class="label">Status Saat Ini</td>
                <td class="value">: {{ ucfirst(str_replace('_', ' ', $pengajuan->status)) }}</td>
            </tr>
        </table>
    </div>

    <div class="box">
        <strong>Keterangan Pengajuan:</strong>
        <p class="small" style="margin-top: 5px;">
            {{ $pengajuan->keterangan }}
        </p>
    </div>

    <p class="small" style="margin-top: 20px;">
        Bukti ini dicetak secara otomatis dari sistem pengajuan online UPPD Kalideres.
        Harap simpan nomor registrasi untuk pengecekan status atau keperluan administrasi lainnya.
    </p>
</body>
</html>
