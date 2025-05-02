<table>
    <thead>
        <tr>
            <th>Peringkat</th>
            <th>Nama Siswa</th>
            <th>NISN</th>
            <th>NIS</th>
            <th>Kelas</th>
            <th>Pelajaran</th>
            <th>Karakter</th>
            <th>Prestasi</th>
            <th>Absensi</th>
            <th>Ekskul</th>
            <th>Nilai Akhir</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($rankedStudents as $index => $item)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $item['student']->user->name }}</td>
                <td>'{{ $item['student']->nisn }}</td>
                <td>'{{ $item['student']->nis }}</td>
                <td>{{ $item['enrollment']->room->name ?? '-' }}</td>
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
