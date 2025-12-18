<!DOCTYPE html>
<html>

<head>
    <title>{{ $title }}</title>
    <style>
        body {
            font-family: sans-serif;
            font-size: 12px;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
        }

        .header h2 {
            margin: 0;
        }

        .header p {
            margin: 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        th,
        td {
            border: 1px solid #000;
            padding: 6px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
            text-align: center;
            font-weight: bold;
        }

        .text-center {
            text-align: center;
        }

        .footer {
            margin-top: 20px;
            text-align: right;
            font-size: 10px;
            font-style: italic;
        }
    </style>
</head>

<body>

    <div class="header">
        <h2>PEMERINTAH PROVINSI DKI JAKARTA</h2>
        <h3>UPPD KECAMATAN KALIDERES</h3>
        <p>Jl. Peta Selatan No. 24, Kalideres, Jakarta Barat</p>
        <hr>
        <h4>{{ strtoupper($title) }}</h4>
        <p>Periode: {{ \Carbon\Carbon::parse($startDate)->format('d F Y') }} -
            {{ \Carbon\Carbon::parse($endDate)->format('d F Y') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th width="5%">No</th>
                @if($jenis == 'surat_masuk')
                    <th>No. Agenda</th>
                    <th>No. Surat</th>
                    <th>Tanggal</th>
                    <th>Pengirim</th>
                    <th>Perihal</th>
                    <th>Status</th>
                @else
                    <th>No. Surat</th>
                    <th>Tanggal</th>
                    <th>Tujuan</th>
                    <th>Perihal</th>
                    <th>Status</th>
                @endif
            </tr>
        </thead>
        <tbody>
            @forelse($data as $key => $item)
                <tr>
                    <td class="text-center">{{ $key + 1 }}</td>
                    @if($jenis == 'surat_masuk')
                        <td>{{ $item->no_agenda }}</td>
                        <td>{{ $item->no_surat }}</td>
                        <td>{{ \Carbon\Carbon::parse($item->tanggal_surat)->format('d/m/Y') }}</td>
                        <td>{{ $item->pengirim }}</td>
                        <td>{{ $item->perihal }}</td>
                        <td class="text-center">{{ ucfirst(str_replace('_', ' ', $item->status)) }}</td>
                    @else
                        <td>{{ $item->no_surat }}</td>
                        <td>{{ \Carbon\Carbon::parse($item->tanggal_surat)->format('d/m/Y') }}</td>
                        <td>{{ $item->tujuan }}</td>
                        <td>{{ $item->perihal }}</td>
                        <td class="text-center">{{ ucfirst(str_replace('_', ' ', $item->status)) }}</td>
                    @endif
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center">Tidak ada data.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        Dicetak pada: {{ date('d F Y H:i') }}
    </div>

</body>

</html>