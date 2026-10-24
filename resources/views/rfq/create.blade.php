<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mini RFQ Form & ERP Integration</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Alpine.js CDN -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="bg-gray-100 p-6">
    <div class="max-w-6xl mx-auto bg-white p-8 rounded-lg shadow-md" x-data="rfqForm()">
        <h1 class="text-2xl font-bold mb-6 text-gray-800">Form Request for Quotation (RFQ) - ERP Integrated</h1>

        <!-- Input Nama Perusahaan -->
        <div class="mb-4">
            <label class="block font-medium text-gray-700 mb-1">Nama Perusahaan</label>
            <input type="text" x-model="form.company" class="w-full border border-gray-300 p-2.5 rounded focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Contoh: PT Maju Bersama">
        </div>

        <h2 class="text-lg font-semibold mb-3 text-gray-800">Daftar Produk (Mengambil dari Master ERP)</h2>

        <!-- Looping Items -->
        {{-- <template x-for="(item, index) in form.items" :key="index">
            <div class="flex flex-col md:flex-row gap-2 mb-3 items-center bg-gray-50 p-3 rounded border border-gray-200">
                
                <!-- Dropdown Pilih Produk dari ERP Master -->
                <select @change="selectProduct(index, $event.target.value)" class="border border-gray-300 p-2 rounded w-full md:w-1/3 bg-white">
                    <option value="">-- Pilih Produk ERP --</option>
                    @foreach($products as $prod)
                        <option value="{{ $prod->sku }}">
                            {{ $prod->product_name }} (Rp {{ number_format($prod->unit_price, 0, ',', '.') }})
                        </option>
                    @endforeach
                </select>

                <!-- SKU (Readonly/Auto-filled) -->
                <input type="text" x-model="item.sku" placeholder="SKU" readonly class="border border-gray-300 bg-gray-100 p-2 rounded w-full md:w-1/6">
                
                <!-- Qty -->
                <input type="number" x-model.number="item.qty" placeholder="Qty" min="1" class="border border-gray-300 p-2 rounded w-full md:w-1/12">
                
                <!-- Unit Price (Readonly dari Master ERP) -->
                <input type="number" x-model.number="item.unit_price" placeholder="Harga Satuan" readonly class="border border-gray-300 bg-gray-100 p-2 rounded w-full md:w-1/6">
                
                <!-- Line Total (Readonly Input) -->
                <input type="text" 
                       :value="'Rp ' + (((parseFloat(item.qty) || 0) * (parseFloat(item.unit_price) || 0))).toLocaleString('id-ID')" 
                       placeholder="Line Total" 
                       readonly 
                       class="border border-gray-300 bg-gray-100 p-2 rounded w-full md:w-1/6 font-semibold text-gray-700">

                <!-- Tombol Hapus Baris -->
                <button @click="removeItem(index)" class="bg-red-500 hover:bg-red-600 text-white px-3 py-2 rounded text-sm w-full md:w-auto">Hapus</button>
            </div>
        </template> --}}
        <!-- Looping Items -->
<template x-for="(item, index) in form.items" :key="index">
    <div class="flex flex-col md:flex-row gap-2 mb-3 items-center bg-gray-50 p-3 rounded border border-gray-200">
        
        <!-- Tambahkan x-model="item.sku" di sini agar dropdown ikut mereset tampilan -->
        <select x-model="item.sku" @change="selectProduct(index, $event.target.value)" class="border border-gray-300 p-2 rounded w-full md:w-1/3 bg-white">
            <option value="">-- Pilih Produk ERP --</option>
            @foreach($products as $prod)
                <option value="{{ $prod->sku }}">
                    {{ $prod->product_name }} (Rp {{ number_format($prod->unit_price, 0, ',', '.') }})
                </option>
            @endforeach
        </select>

        <!-- SKU (Readonly/Auto-filled) -->
        <input type="text" x-model="item.sku" placeholder="SKU" readonly class="border border-gray-300 bg-gray-100 p-2 rounded w-full md:w-1/6">
        
        <!-- Qty -->
        <input type="number" x-model.number="item.qty" placeholder="Qty" min="1" class="border border-gray-300 p-2 rounded w-full md:w-1/12">
        
        <!-- Unit Price (Readonly dari Master ERP) -->
        <input type="number" x-model.number="item.unit_price" placeholder="Harga Satuan" readonly class="border border-gray-300 bg-gray-100 p-2 rounded w-full md:w-1/6">
        
        <!-- Line Total (Readonly Input) -->
        <input type="text" 
               :value="'Rp ' + (((parseFloat(item.qty) || 0) * (parseFloat(item.unit_price) || 0))).toLocaleString('id-ID')" 
               placeholder="Line Total" 
               readonly 
               class="border border-gray-300 bg-gray-100 p-2 rounded w-full md:w-1/6 font-semibold text-gray-700">

        <!-- Tombol Hapus Baris -->
        <button @click="removeItem(index)" class="bg-red-500 hover:bg-red-600 text-white px-3 py-2 rounded text-sm w-full md:w-auto">Hapus</button>
    </div>
</template>

        <button @click="addItem" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded text-sm font-medium mb-6">+ Tambah Item</button>

        <!-- Preview & Subtotal Real-time -->
        <div class="border-t border-gray-200 pt-4 mb-6">
            <h3 class="text-xl font-bold text-gray-800">Preview Subtotal Keseluruhan: 
                <span class="text-blue-600" x-text="'Rp ' + calculateSubtotal().toLocaleString('id-ID')"></span>
            </h3>
        </div>

        <!-- Tombol Submit -->
        <button @click="submitRfq" class="w-full bg-green-600 hover:bg-green-700 text-white py-3 rounded-lg font-bold text-lg transition duration-200">Kirim RFQ ke API</button>
    </div>

    <script>
        function rfqForm() {
            return {
                availableProducts: @json($products),
                form: {
                    company: '',
                    items: [
                        { sku: '', product_name: '', qty: 1, unit_price: 0 }
                    ]
                },
                selectProduct(index, sku) {
                    let found = this.availableProducts.find(p => p.sku === sku);
                    if (found) {
                        this.form.items[index].sku = found.sku;
                        this.form.items[index].product_name = found.product_name;
                        this.form.items[index].unit_price = parseFloat(found.unit_price);
                    } else {
                        this.form.items[index].sku = '';
                        this.form.items[index].product_name = '';
                        this.form.items[index].unit_price = 0;
                    }
                },
                addItem() {
                    this.form.items.push({ sku: '', product_name: '', qty: 1, unit_price: 0 });
                },
                removeItem(index) {
                    if (this.form.items.length > 1) {
                        this.form.items.splice(index, 1);
                    } else {
                        alert('Minimal harus ada 1 item produk.');
                    }
                },
                calculateSubtotal() {
                    return this.form.items.reduce((acc, item) => {
                        let qty = parseFloat(item.qty) || 0;
                        let price = parseFloat(item.unit_price) || 0;
                        return acc + (qty * price);
                    }, 0);
                },
                submitRfq() {
                    fetch('/api/rfq', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify(this.form)
                    })
                    .then(async res => {
                        let data = await res.json();
                        if (res.ok) {
                            alert('RFQ Berhasil Dikirim!\nStatus: ' + data.status + '\nSubtotal: ' + data.formatted_subtotal);

                            // --- BERSIHKAN FORM / KERANJANG DI SINI ---
                            this.form.company = '';
                            this.form.items = [
                                { sku: '', product_name: '', qty: 1, unit_price: 0 }
                            ];
                        } else {
                            alert('Validasi Gagal: ' + (data.message || JSON.stringify(data.errors)));
                        }
                    })
                    .catch(err => {
                        alert('Terjadi kesalahan koneksi.');
                        console.error(err);
                    });
                }
            }
        }
    </script>
</body>
</html>