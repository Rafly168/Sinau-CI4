<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - TOKO</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif; /* Menggunakan font Inter */
            background-color: #f4f6f9;
            padding: 20px;
        }
        .container {
            background-color: #ffffff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
            margin-top: 30px;
        }
        h1 {
            color: #333;
            margin-bottom: 20px;
            text-align: center;
        }
        .table-responsive {
            margin-top: 20px;
        }
        .table th, .table td {
            vertical-align: middle;
        }
        .table thead th {
            background-color: #e9ecef;
        }
        .loading-indicator {
            text-align: center;
            padding: 20px;
            font-size: 1.2em;
            color: #555;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Dashboard - TOKO</h1>
        <p class="text-center text-muted">Data Transaksi Pembelian</p>

        <div id="loading" class="loading-indicator">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
            <p>Memuat data transaksi...</p>
        </div>

        <div id="transactionTable" class="table-responsive" style="display: none;">
            <table class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Username</th>
                        <th>Alamat</th>
                        <th>Total Belanja</th>
                        <th>Ongkir</th>
                        <th>Status</th>
                        <th>Tanggal Transaksi</th>
                        <th>Jumlah Item</th> <!-- Kolom baru untuk jumlah item -->
                    </tr>
                </thead>
                <tbody id="transactionTableBody">
                    <!-- Data transaksi akan dimuat di sini oleh JavaScript -->
                </tbody>
            </table>
        </div>

        <div id="errorMessage" class="alert alert-danger" style="display: none;">
            Terjadi kesalahan saat memuat data transaksi. Silakan coba lagi nanti.
        </div>
    </div>

    <!-- Bootstrap JS Bundle (Popper.js included) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const loadingIndicator = document.getElementById('loading');
            const transactionTable = document.getElementById('transactionTable');
            const transactionTableBody = document.getElementById('transactionTableBody');
            const errorMessage = document.getElementById('errorMessage');

            const WEBSERVICE_URL = 'http://localhost:8080/api/transactions'; // Ganti dengan URL yang benar

            async function fetchTransactions() {
                loadingIndicator.style.display = 'block';
                transactionTable.style.display = 'none';
                errorMessage.style.display = 'none';

                try {
                    const response = await fetch(WEBSERVICE_URL);
                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }
                    const data = await response.json();

                    transactionTableBody.innerHTML = ''; // Bersihkan tabel sebelumnya
                    if (data.length === 0) {
                        transactionTableBody.innerHTML = '<tr><td colspan="8" class="text-center">Tidak ada data transaksi.</td></tr>';
                    } else {
                        data.forEach((transaction, index) => {
                            let totalItems = 0;
                            // Asumsi 'items' adalah array di dalam objek transaksi yang berisi detail item
                            // Setiap item memiliki properti 'jumlah' (quantity)
                            if (transaction.items && Array.isArray(transaction.items)) {
                                totalItems = transaction.items.reduce((sum, item) => sum + (item.jumlah || 0), 0);
                            } else if (transaction.total_item_count) { // Alternatif jika webservice langsung memberikan total count
                                totalItems = transaction.total_item_count;
                            } else {
                                // Jika struktur data item tidak sesuai, coba parse jika berupa string JSON
                                try {
                                    const parsedItems = JSON.parse(transaction.items_data || '[]'); // Asumsi nama field 'items_data'
                                    if (Array.isArray(parsedItems)) {
                                        totalItems = parsedItems.reduce((sum, item) => sum + (item.jumlah || 0), 0);
                                    }
                                } catch (e) {
                                    console.error("Error parsing items_data:", e);
                                    totalItems = 'N/A'; // Jika tidak bisa diparse
                                }
                            }

                            const row = `
                                <tr>
                                    <td>${index + 1}</td>
                                    <td>${transaction.username || 'N/A'}</td>
                                    <td>${transaction.alamat || 'N/A'}</td>
                                    <td>Rp ${new Intl.NumberFormat('id-ID').format(transaction.total_belanja || 0)}</td>
                                    <td>Rp ${new Intl.NumberFormat('id-ID').format(transaction.ongkir || 0)}</td>
                                    <td>${transaction.status || 'N/A'}</td>
                                    <td>${new Date(transaction.tanggal_transaksi).toLocaleString('id-ID')}</td>
                                    <td>${totalItems}</td>
                                </tr>
                            `;
                            transactionTableBody.insertAdjacentHTML('beforeend', row);
                        });
                    }
                    transactionTable.style.display = 'block'; // Tampilkan tabel setelah data dimuat
                } catch (error) {
                    console.error('Error fetching transactions:', error);
                    errorMessage.style.display = 'block'; // Tampilkan pesan error
                } finally {
                    loadingIndicator.style.display = 'none'; // Sembunyikan indikator loading
                }
            }

            fetchTransactions(); // Panggil fungsi untuk memuat data saat halaman dimuat
        });
    </script>
</body>
</html>