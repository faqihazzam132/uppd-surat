<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Pengajuan</title>
    <style>
        body { font-family: DejaVu Sans, Arial, sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #000; padding: 4px 6px; }
        th { background-color: #f0f0f0; }
        h2 { margin-bottom: 5px; }
        .filters { margin-bottom: 10px; font-size: 11px; }
    </style>
</head>
<body>
    <h2>Laporan Pengajuan</h2>
    <div class="filters">
        @if(!empty($filters['tanggal_from']) || !empty($filters['tanggal_to']))
            <div><strong>Periode:</strong>
                {{ $filters['tanggal_from'] ?? '-' }} s/d {{ $filters['tanggal_to'] ?? '-' }}
            </div>
        @endif
        @if(!empty($filters['status']))
            <div><strong>Status:</strong> {{ ucfirst(str_replace('_', ' ', $filters['status'])) }}</div>
        @endif
        @if(!empty($filters['jenis_surat']))
            <div><strong>Jenis Surat:</strong> {{ $filters['jenis_surat'] }}</div>
        @endif
    </div>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>No Registrasi</th>
                <th>Pemohon</th>
                <th>Jenis Surat</th>
                <th>Tanggal</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($pengajuans as $index => $p)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $p->no_registrasi }}</td>
                    <td>{{ optional($p->user)->name }}</td>
                    <td>{{ $p->jenis_surat }}</td>
                    <td>{{ $p->created_at->format('d-m-Y H:i') }}</td>
                    <td>{{ ucfirst(str_replace('_', ' ', $p->status)) }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" style="text-align:center;">Tidak ada data pengajuan.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>
