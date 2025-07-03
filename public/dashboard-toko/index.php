<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - TOKO</title>
    <!-- Bootstrap CSS (gunakan versi yang sama dengan NiceAdmin jika memungkinkan) -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome (untuk ikon jika diperlukan) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body {
            font-family: 'Open Sans', sans-serif;
            background-color: #f6f9ff;
            padding: 20px;
        }
        .container {
            max-width: 1200px;
            margin-top: 50px;
        }
        .card {
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        .card-header {
            background-color: #007bff;
            color: white;
            border-top-left-radius: 8px;
            border-top-right-radius: 8px;
            padding: 15px 20px;
            font-size: 1.2em;
            font-weight: bold;
        }
        .table th, .table td {
            vertical-align: middle;
        }
        .table thead th {
            background-color: #e9ecef;
        }
        .detail-button {
            background-color: #28a745;
            color: white;
            border: none;
            padding: 5px 10px;
            border-radius: 5px;
            cursor: pointer;
        }
        .detail-button:hover {
            background-color: #218838;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="card">
            <div class="card-header">
                Dashboard - TOKO
                <div class="float-end" id="currentDateTime"></div>
            </div>
            <div class="card-body">
                <h5 class="card-title">Transaksi Pembelian</h5>
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Username</th>
                                <th>Total Bayar</th>
                                <th>Ongkir</th>
                                <th>Alamat</th>
                                <th>Status</th>
                                <th>Waktu Pembelian</th>
                                <th>Jumlah Item</th> <!-- Kolom baru untuk jumlah item -->
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="transactionsTableBody">
                            <!-- Data transaksi akan dimuat di sini oleh JavaScript -->
                            <tr>
                                <td colspan="9" class="text-center">Memuat data...</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Detail Transaksi -->
    <div class="modal fade" id="transactionDetailModal" tabindex="-1" aria-labelledby="transactionDetailModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="transactionDetailModalLabel">Detail Data Transaksi</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <h6>Items Dibeli:</h6>
                    <ul id="detailItemsList" class="list-group">
                        <!-- Detail item akan dimuat di sini -->
                    </ul>
                    <h6 class="mt-3">Ongkir: <span id="detailOngkir"></span></h6>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS Bundle (termasuk Popper) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Fungsi untuk memperbarui waktu saat ini
        function updateDateTime() {
            const now = new Date();
            const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric', hour: '2-digit', minute: '2-digit', second: '2-digit' };
            document.getElementById('currentDateTime').textContent = now.toLocaleDateString('id-ID', options);
        }

        // Panggil saat halaman dimuat dan setiap detik
        updateDateTime();
        setInterval(updateDateTime, 1000);

        // Fungsi untuk memuat data transaksi dari API
        async function loadTransactions() {
            const tableBody = document.getElementById('transactionsTableBody');
            tableBody.innerHTML = '<tr><td colspan="9" class="text-center">Memuat data...</td></tr>'; // Reset loading state

            try {
                // Ganti URL ini dengan URL API yang benar dari proyek Toko Anda
                // Asumsi proyek Toko berjalan di localhost:8080
                const apiUrl = 'http://localhost:8080/api/transactions';
                const apiKey = 'YOUR_API_KEY'; // GANTI DENGAN API KEY ANDA YANG SEBENARNYA DARI FILE .env TOKO

                const response = await fetch(apiUrl, {
                    headers: {
                        'Key': apiKey, // Mengirim API Key di header
                        'Accept': 'application/json'
                    }
                });

                if (!response.ok) {
                    const errorText = await response.text();
                    throw new Error(`HTTP error! status: ${response.status}, message: ${errorText}`);
                }
                const result = await response.json();

                if (result.status.code !== 200) {
                    throw new Error(`API Error: ${result.status.description}`);
                }

                const transactions = result.results;

                tableBody.innerHTML = ''; // Kosongkan tabel
                if (transactions.length === 0) {
                    tableBody.innerHTML = '<tr><td colspan="9" class="text-center">Tidak ada data transaksi.</td></tr>';
                    return;
                }

                transactions.forEach((transaction, index) => {
                    const row = tableBody.insertRow();
                    row.innerHTML = `
                        <td>${index + 1}</td>
                        <td>${transaction.username}</td>
                        <td>IDR ${new Intl.NumberFormat('id-ID').format(transaction.total_harga)}</td>
                        <td>IDR ${new Intl.NumberFormat('id-ID').format(transaction.ongkir)}</td>
                        <td>${transaction.alamat}</td>
                        <td>${transaction.status == 0 ? 'Belum Selesai' : 'Selesai'}</td>
                        <td>${new Date(transaction.created_at).toLocaleString('id-ID', { year: 'numeric', month: 'numeric', day: 'numeric', hour: '2-digit', minute: '2-digit' })}</td>
                        <td>${transaction.total_items_bought}</td> <!-- Menampilkan jumlah item -->
                        <td>
                            <button class="detail-button" data-bs-toggle="modal" data-bs-target="#transactionDetailModal" data-transaction='${JSON.stringify(transaction)}'>
                                Detail
                            </button>
                        </td>
                    `;
                });

                // Menangani tampilan modal detail
                const detailModal = document.getElementById('transactionDetailModal');
                detailModal.addEventListener('show.bs.modal', function (event) {
                    const button = event.relatedTarget;
                    const transactionData = JSON.parse(button.dataset.transaction);

                    const detailItemsList = document.getElementById('detailItemsList');
                    detailItemsList.innerHTML = ''; // Bersihkan daftar sebelumnya

                    transactionData.details.forEach(detail => {
                        const listItem = document.createElement('li');
                        listItem.classList.add('list-group-item', 'd-flex', 'align-items-center');
                        listItem.innerHTML = `
                            <img src="${detail.product_image ? '<?= base_url()?>NiceAdmin/assets/img/product/' + detail.product_image : 'https://placehold.co/50x50/cccccc/000000?text=No+Image'}" alt="Product Image" style="width: 50px; height: 50px; margin-right: 15px; border-radius: 5px;">
                            <div>
                                <strong>${detail.product_name}</strong> (${detail.jumlah} pcs) <br>
                                IDR ${new Intl.NumberFormat('id-ID').format(detail.subtotal_harga)}
                            </div>
                        `;
                        detailItemsList.appendChild(listItem);
                    });
                    document.getElementById('detailOngkir').textContent = `IDR ${new Intl.NumberFormat('id-ID').format(transactionData.ongkir)}`;
                });

            } catch (error) {
                console.error('Error fetching transactions:', error);
                tableBody.innerHTML = `<tr><td colspan="9" class="text-center text-danger">Gagal memuat data transaksi: ${error.message}</td></tr>`;
            }
        }

        // Panggil fungsi untuk memuat data saat halaman dimuat
        document.addEventListener('DOMContentLoaded', loadTransactions);
    </script>
</body>
</html>
