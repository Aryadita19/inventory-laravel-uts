<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pembelian & Payment Customer</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
        <a class="navbar-brand font-weight-bold" href="#">🛒 Toko Online Customer</a>
        <a href="/dashboard" class="btn btn-outline-light btn-sm">Kembali ke Dashboard</a>
    </div>
</nav>

<div class="container my-5">
    <h3 class="mb-4">Halaman Pembelian & Checkout</h3>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <strong>Berhasil!</strong> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>Gagal!</strong> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row">
        <!-- List Produk -->
        <div class="col-md-7">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Pilih Barang</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        @forelse($products as $product)
                            @php
                                // Menangkap berbagai kemungkinan nama kolom harga di database project
                                $productPrice = $product->selling_price ?? $product->purchase_price ?? $product->price ?? 0;
                            @endphp
                            <div class="col-md-6 mb-3">
                                <div class="card h-100 border">
                                    <div class="card-body">
                                        <h6 class="card-title font-weight-bold">{{ $product->name }}</h6>
                                        <p class="text-muted small mb-1">Stok Tersedia: <strong>{{ $product->stock }}</strong></p>
                                        <p class="text-success font-weight-bold">Rp {{ number_format($productPrice, 0, ',', '.') }}</p>
                                        <button class="btn btn-sm btn-outline-primary w-100" onclick="selectProduct({{ $product->id }}, '{{ $product->name }}', {{ $productPrice }})">Pilih Barang</button>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <p class="text-muted">Tidak ada produk yang tersedia.</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        <!-- Form Payment -->
        <div class="col-md-5">
            <div class="card shadow-sm">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0">💳 Payment Gateway (Form Pembayaran)</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('customer.pay') }}" method="POST">
                        @csrf
                        <input type="hidden" name="product_id" id="product_id" required>
                        
                        <div class="mb-3">
                            <label class="form-label">Produk Terpilih</label>
                            <input type="text" id="product_name" class="form-control" readonly placeholder="Klik 'Pilih Barang' di sebelah kiri" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Jumlah (Qty)</label>
                            <input type="number" name="quantity" id="quantity" class="form-control" value="1" min="1" oninput="calculateTotal()" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Metode Pembayaran (Payment Method)</label>
                            <select name="payment_method" class="form-select" required>
                                <option value="qris">QRIS / GoPay / OVO</option>
                                <option value="transfer">Transfer Bank (BCA/Mandiri)</option>
                                <option value="cod">Cash on Delivery (COD)</option>
                            </select>
                        </div>

                        <hr>
                        <div class="d-flex justify-content-between mb-3">
                            <span>Total Bayar:</span>
                            <strong class="text-danger h5" id="total_price">Rp 0</strong>
                        </div>

                        <button type="submit" class="btn btn-success w-100 btn-lg">Bayar Sekarang</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    let currentPrice = 0;

    function selectProduct(id, name, price) {
        document.getElementById('product_id').value = id;
        document.getElementById('product_name').value = name;
        currentPrice = price;
        calculateTotal();
    }

    function calculateTotal() {
        let qty = document.getElementById('quantity').value || 1;
        let total = currentPrice * qty;
        document.getElementById('total_price').innerText = 'Rp ' + total.toLocaleString('id-ID');
    }
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>