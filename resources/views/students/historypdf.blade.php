<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Riwayat Nilai Siswa</title>
    <style>
        @page {
            size: A4 portrait;
            margin: 1cm;
        }

        body {
            font-family: 'Arial', sans-serif;
            font-size: 10px;
            margin: 0;
            padding: 0;
            color: #333;
        }

        h2,
        h4 {
            text-align: center;
            margin: 10px 0;
            color: #0066cc;
        }

        .info {
            text-align: center;
            margin: 5px 0 20px;
            font-size: 9px;
            color: #555;
        }

        .table {
            margin-top: 20px;
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            border: 1px solid #333;
            padding: 6px;
            text-align: left;
            font-size: 9px;
            vertical-align: top;
        }

        th {
            background-color: #0066cd;
            color: white;
            text-align: center;
        }

        .no {
            text-align: center;
        }

        .summary {
            margin-top: 20px;
            text-align: center;
            font-weight: bold;
            font-size: 11px;
        }

        .summary p {
            margin: 5px 0;
        }

        .heading {
            background-color: #e0f7fa;
            font-weight: bold;
            color: #00796b;
        }

        .subheading {
            background-color: #b2ebf2;
            font-weight: bold;
            color: #00796b;
        }
    </style>
</head>

<body>
    <h2>RIWAYAT NILAI SISWA</h2>
    <h4>Tahun Ajaran {{ $year->name }}</h4>
    <div class="info">
        <p>Nama: {{ $student->user->name }} &nbsp;|&nbsp; Kelas: {{ $room->name }} </p>
    </div>

    <table class="table">
        <thead>
            <tr class="heading">
                <th class="no">No</th>
                <th>Komponen</th>
                <th>Nilai</th>
            </tr>
        </thead>
        <tbody>
            @if ($grades && count($grades))
                <tr class="subheading">
                    <td colspan="3">Nilai Pelajaran</td>
                </tr>
                @foreach ($grades as $index => $grade)
                    <tr>
                        <td class="no">{{ $index + 1 }}</td>
                        <td>{{ $grade->subject->name }}</td>
                        <td>{{ $grade->score }}</td>
                    </tr>
                @endforeach
            @endif

            @if ($components)
                <tr class="subheading">
                    <td colspan="3">Penilaian</td>
                </tr>
                @php $i = 1; @endphp
                <tr>
                    <td class="no">{{ $i++ }}</td>
                    <td>Akhlak</td>
                    <td>{{ $components->character }}</td>
                </tr>
                <tr>
                    <td class="no">{{ $i++ }}</td>
                    <td>Prestasi</td>
                    <td>{{ $components->achievement }}</td>
                </tr>
                <tr>
                    <td class="no">{{ $i++ }}</td>
                    <td>Absen</td>
                    <td>{{ $components->attendance }}</td>
                </tr>
                <tr>
                    <td class="no">{{ $i++ }}</td>
                    <td>Ekskul</td>
                    <td>{{ $components->extracurricular }}</td>
                </tr>
                <tr class="subheading">
                    <td colspan="3">Total dan Ranking</td>
                </tr>
                <tr>
                    <td>Total Nilai</td>
                    <td></td>
                    <td class="font-semibold">{{ $totalScore }}</td>
                </tr>
                @if ($ranking && $studentCount)
                    <tr>
                        <td>Ranking</td>
                        <td></td>
                        <td class="font-semibold">{{ $ranking }} dari {{ $studentCount }} Siswa</td>
                    </tr>
                @endif
            @endif
        </tbody>
    </table>
</body>

</html>
