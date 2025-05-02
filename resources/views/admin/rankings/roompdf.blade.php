<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Ranking Siswa</title>
    <style>
        @page {
            size: A4 portrait;
            margin: 1cm;
        }

        body {
            font-family: sans-serif;
            font-size: 9px;
            margin: 0;
            padding: 0;
            color: #333;
        }

        h2,
        h4 {
            text-align: center;
            margin: 0;
        }

        .info {
            text-align: center;
            margin: 5px 0 10px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
        }

        th,
        td {
            border: 1px solid #333;
            padding: 3px;
            text-align: left;
            word-wrap: break-word;
            font-size: 8.5px;
            vertical-align: top;
        }

        th {
            background-color: #0066cd;
            color: white;
        }

        td:last-child {
            font-weight: bold;
            text-align: center;
        }
    </style>
</head>

<body>
    <h2>PERINGKAT SISWA</h2>
    <h4>Tahun Ajaran {{ $selectedYear }}</h4>
    <div class="info">
        <p>Kelas: {{ $selectedRoom }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 4%;">No</th>
                <th style="width: 14%;">Nama</th>
                <th style="width: 9%;">NISN</th>
                <th style="width: 9%;">NIS</th>
                {{-- <th style="width: 9%;">Kelas</th> --}}
                <th style="width: 11%;">Pelajaran</th>
                <th style="width: 10%;">Karakter</th>
                <th style="width: 10%;">Prestasi</th>
                <th style="width: 9%;">Absensi</th>
                <th style="width: 10%;">Ekskul</th>
                <th style="width: 5%;">Nilai</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($rankedStudents as $index => $item)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $item['student']->user->name }}</td>
                    <td>{{ $item['student']->nisn }}</td>
                    <td>{{ $item['student']->nis }}</td>
                    {{-- <td>{{ $item['enrollment']->room->name ?? '-' }}</td> --}}
                    <td>{{ $item['grades'] }}</td>
                    <td>{{ $item['character'] }}</td>
                    <td>{{ $item['achievement'] }}</td>
                    <td>{{ $item['attendance'] }}</td>
                    <td>{{ $item['extracurricular'] }}</td>
                    <td>{{ $item['score'] }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>
