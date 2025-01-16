<?php
// URL API Next.js yang mengembalikan data
$api_url = "http://localhost:3000/api/data";

// Mendapatkan respons dari API Next.js
$response = file_get_contents($api_url);

// Mengecek apakah permintaan berhasil
if ($response === FALSE) {
    die('Error occurred while fetching data from the API.');
}

// Mengonversi respons JSON menjadi array PHP
$data = json_decode($response, true);

// Cek apakah data berhasil di-decode
if ($data === NULL) {
    die('Error occurred while decoding JSON data.');
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Frontend - Next.js API</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #ccc;
            padding: 10px;
            text-align: left;
        }
        th {
            background-color: #f4f4f4;
        }
        button {
            background-color: red;
            color: white;
            border: none;
            padding: 5px 10px;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <h1>Baca Data dari Backend (Next.js)</h1>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nama</th>
                <th>Umur</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($data as $item): ?>
                <tr id="row-<?php echo $item['id']; ?>">
                    <td><?php echo htmlspecialchars($item['id']); ?></td>
                    <td><?php echo htmlspecialchars($item['nama']); ?></td>
                    <td><?php echo htmlspecialchars($item['umur']); ?></td>
                    <td>
                        <!-- Tombol Hapus -->
                        <button onclick="deleteData(<?php echo $item['id']; ?>)">Hapus</button>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <script>
        function deleteData(id) {
            if (confirm('Apakah Anda yakin ingin menghapus data dengan ID ' + id + '?')) {
                fetch('http://localhost:3000/api/data?id=' + id, {
                    method: 'DELETE'
                })
                .then(response => response.json())
                .then(data => {
                    if (data.message === 'Data deleted successfully') {
                        // Hapus baris tabel yang sesuai dengan ID
                        document.getElementById('row-' + id).remove();
                        alert('Data berhasil dihapus');
                    } else {
                        alert('Gagal menghapus data');
                    }
                })
                .catch(error => {
                    alert('Terjadi kesalahan: ' + error.message);
                });
            }
        }
    </script>
</body>
</html>
