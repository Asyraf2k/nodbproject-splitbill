<?php
$api_url = "http://localhost:3000/api/data";
$response = file_get_contents($api_url);
$data = json_decode($response, true);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Data ke Backend (Next.js)</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        input, button {
            padding: 10px;
            margin: 5px;
        }
        button {
            background-color: #4CAF50;
            color: white;
            border: none;
        }
    </style>
</head>
<body>
    <h1>Tambah Data ke Backend (Next.js)</h1>

    <form id="dataForm">
        <label for="nama">Nama:</label>
        <input type="text" id="nama" name="nama" required><br>

        <label for="umur">Umur:</label>
        <input type="number" id="umur" name="umur" required><br>

        <button type="submit">Kirim Data</button>
    </form>

    <script>
        document.getElementById('dataForm').addEventListener('submit', function(event) {
            event.preventDefault();  // Mencegah form dari pengiriman default

            // Mengambil data dari form
            const nama = document.getElementById('nama').value;
            const umur = document.getElementById('umur').value;

            // Mengirim data ke API Next.js tanpa ID
            fetch('http://localhost:3000/api/data', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ nama, umur }), // Tidak perlu kirimkan ID
            })
            .then(response => response.json())
            .then(data => {
                alert('Data berhasil ditambahkan');
                document.getElementById('dataForm').reset();
            })
            .catch(error => alert('Terjadi kesalahan: ' + error.message));
        });
    </script>
</body>
</html>
