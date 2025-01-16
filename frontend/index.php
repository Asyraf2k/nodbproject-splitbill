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
        .modal {
            display: none;
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background-color: white;
            padding: 20px;
            border: 1px solid #ccc;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
            z-index: 1000;
        }
        .overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 999;
        }
        .close-btn {
            cursor: pointer;
            font-size: 20px;
            color: #aaa;
        }
        .close-btn:hover {
            color: #000;
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
                        <!-- Tombol Edit -->
                        <button onclick="openEditModal(<?php echo $item['id']; ?>, '<?php echo htmlspecialchars($item['nama']); ?>', <?php echo $item['umur']; ?>)">Edit</button>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <!-- Modal Edit -->
    <div class="overlay" id="editModalOverlay"></div>
    <div class="modal" id="editModal">
        <span class="close-btn" onclick="closeEditModal()">&times;</span>
        <h2>Edit Data</h2>
        <form id="editForm">
            <label for="editNama">Nama:</label>
            <input type="text" id="editNama" name="nama" required><br><br>
            <label for="editUmur">Umur:</label>
            <input type="number" id="editUmur" name="umur" required><br><br>
            <input type="hidden" id="editId" name="id">
            <button type="submit">Simpan Perubahan</button>
        </form>
    </div>

    <script>
        // Fungsi untuk menghapus data
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

        // Fungsi untuk membuka modal edit
        function openEditModal(id, nama, umur) {
            document.getElementById('editId').value = id;
            document.getElementById('editNama').value = nama;
            document.getElementById('editUmur').value = umur;
            document.getElementById('editModal').style.display = 'block';
            document.getElementById('editModalOverlay').style.display = 'block';
        }

        // Fungsi untuk menutup modal edit
        function closeEditModal() {
            document.getElementById('editModal').style.display = 'none';
            document.getElementById('editModalOverlay').style.display = 'none';
        }

        // Menangani pengiriman form edit
        document.getElementById('editForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const id = document.getElementById('editId').value;
            const nama = document.getElementById('editNama').value;
            const umur = document.getElementById('editUmur').value;

            fetch('http://localhost:3000/api/data', {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ id, nama, umur })
            })
            .then(response => response.json())
            .then(data => {
                if (data.message === 'Data updated successfully') {
                    // Update tabel dengan data yang sudah diperbarui
                    const row = document.getElementById('row-' + id);
                    row.cells[1].textContent = nama;
                    row.cells[2].textContent = umur;
                    alert('Data berhasil diperbarui');
                    closeEditModal();
                } else {
                    alert('Gagal memperbarui data');
                }
            })
            .catch(error => {
                alert('Terjadi kesalahan: ' + error.message);
            });
        });
    </script>
</body>
</html>
