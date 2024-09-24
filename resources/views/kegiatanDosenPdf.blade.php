<!DOCTYPE html>
<html>
<head>
    <title>Data Kegiatan Dosen</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        table, th, td {
            border: 1px solid black;
        }
        th, td {
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    <h2>Data Kegiatan Dosen</h2>
    <table>
        <thead>
            <tr>
                <th>Nama Dosen</th>
                <th>Nama Kegiatan</th>
                <th>Tugas</th>
                <th>Tempat</th>
                <th>Tanggal</th>
                <th>Waktu Mulai</th>
                <th>Waktu Selesai</th>
            </tr>
        </thead>
        <tbody>
            @foreach($dtKegiatan as $kegiatan)
            <tr>
                <td>{{ $kegiatan->dosen->nama_dosen }}</td>
                <td>{{ $kegiatan->nama_kegiatan }}</td>
                <td>{{ $kegiatan->tugas }}</td>
                <td>{{ $kegiatan->Tempat }}</td>
                <td>{{ $kegiatan->tanggal }}</td>
                <td>{{ $kegiatan->waktu_mulai }}</td>
                <td>{{ $kegiatan->waktu_selesai }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <script type="text/javascript">
        window.print();
    </script>
</body>
</html>
