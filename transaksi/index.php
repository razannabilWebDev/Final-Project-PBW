<?php
session_start();
// Pastikan file koneksi ada sesuai struktur foldermu
require_once '../config/koneksi.php';
require_once '../config/session.php';
cek_login();
// Ambil semua data pelanggan untuk diisi ke dropdown
$query_pelanggan = "SELECT id_pelanggan, nama_pelanggan, no_hp, poin_member FROM pelanggan ORDER BY nama_pelanggan ASC";
$result_pelanggan = mysqli_query($conn, $query_pelanggan);

// Asumsi ID kasir yang sedang login
$id_user = isset($_SESSION['id_user']) ? $_SESSION['id_user'] : 1; 
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modul Transaksi Penjualan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/transaksi.css">
    
</head>
<body>

    <?php include '../templates/sidebar.php'; ?>

    <div class="main-content">
        
        <div class="page-header">
            <h2>Transaksi Penjualan</h2>
        </div>

        <main class="transaction-container">
            
            <div class="left-column">
                <div class="card">
                    <div class="card-header"><span class="badge">1</span> Input Barang</div>
                    <div class="card-body">
                        <div class="form-group">
                            <label>Cari barang</label>
                            <input type="text" id="keyword_barang" class="form-control" placeholder="Ketik nama produk atau scan barcode..." onkeyup="cariBarang()">
                        </div>
                        <div id="hasil_pencarian_barang"></div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header"><span class="badge">2</span> Daftar Belanjaan</div>
                    <div class="card-body" style="padding: 0;">
                        <div class="table-responsive">
                            <table>
                                <thead>
                                    <tr>
                                        <th>Nama Barang</th>
                                        <th>Harga</th>
                                        <th>Qty</th>
                                        <th>Sub Total</th>
                                        <th>Hapus</th>
                                    </tr>
                                </thead>
                                <tbody id="cart_body">
                                    <tr><td colspan="5" style="text-align:center; color:#999;">Keranjang masih kosong</td></tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="right-column">
                <div class="card">
                    <div class="card-header"><span class="badge">3</span> Data Pelanggan</div>
                    <div class="card-body">
                        <div class="form-group">
                            <label>Cari Pelanggan / No. HP</label>
                            <select id="pilih_pelanggan" class="form-control" onchange="pilihMember()">
                                <option value="">-- Pelanggan Umum (Bukan Member) --</option>
                                <?php while($p = mysqli_fetch_assoc($result_pelanggan)): ?>
                                    <option value="<?= $p['id_pelanggan'] ?>" 
                                            data-nama="<?= htmlspecialchars($p['nama_pelanggan']) ?>" 
                                            data-poin="<?= $p['poin_member'] ?>">
                                        <?= $p['nama_pelanggan'] ?> - (<?= $p['no_hp'] ?>)
                                    </option>
                                <?php endwhile; ?>
                            </select>
                        </div>

                        <div id="info_pelanggan" class="info-box d-flex justify-between align-center" style="display: none !important;">
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header"><span class="badge">4</span> Pembayaran</div>
                    <div class="card-body">
                        <label>TOTAL BELANJA</label>
                        <div class="payment-box">
                            <div class="total-text">Rp <span id="label_total">0</span></div>
                            <div id="label_diskon" style="color: red; font-size: 12px; margin-top:5px; display: none;">
                                *Termasuk Potongan Diskon 10%
                            </div>
                        </div>

                        <div class="form-group">
                            <label>Uang Pembayaran</label>
                            <input type="number" id="uang_bayar" class="form-control" placeholder="Masukkan nominal uang..." onkeyup="hitungKembalian()">
                        </div>

                        <label>Kembalian</label>
                        <div class="payment-box" style="background-color: #f0f0f0;">
                            <div class="total-text" style="color: var(--text-main);">Rp <span id="label_kembalian">0</span></div>
                        </div>

                        <div class="d-flex gap-10" style="margin-top: 20px;">
                            <button class="btn btn-primary" style="flex: 1;" onclick="simpanTransaksi(true)">🖨️ Simpan & Cetak</button>
                            <button class="btn btn-outline" style="flex: 1;" onclick="simpanTransaksi(false)">💾 Simpan</button>
                        </div>
                    </div>
                </div>
                
            </div>
            
        </main>
        
        
        
    </div> 
    <footer class="footer" style="margin-left: 270px;">
        <?php include '../templates/footer.php'; ?>
    
    <script>
        // State Global
        let cart = [];
        let pelangganAktif = null;
        let gunakanPoin = false;
        let grandTotal = 0;
        let totalDiskon = 0;

        // FORMAT RUPIAH
        const formatRupiah = (angka) => {
            return new Intl.NumberFormat('id-ID').format(angka);
        }

        // ================= BARANG & KERANJANG =================
        async function cariBarang() {
            const keyword = document.getElementById('keyword_barang').value;
            const container = document.getElementById('hasil_pencarian_barang');
            
            if(keyword.length < 2) {
                container.innerHTML = ''; return;
            }

            const response = await fetch(`cari_barang.php?q=${keyword}`);
            const data = await response.json();
            
            let html = '';
            data.forEach(b => {
                html += `
                <div class="search-result-item">
                    <div>
                        <strong>${b.nama_barang}</strong><br>
                        <span style="font-size:12px; color:#666;">Stok: ${b.jumlah_stok} | Rp ${formatRupiah(b.harga_jual)}</span>
                    </div>
                    <button class="btn btn-primary" onclick="tambahKeKeranjang(${b.id_barang}, '${b.nama_barang}', ${b.harga_jual}, ${b.jumlah_stok})" style="padding:5px 15px;">+</button>
                </div>`;
            });
            container.innerHTML = html;
        }

        function tambahKeKeranjang(id, nama, harga, stokMax) {
            let exist = cart.find(item => item.id_barang === id);
            if (exist) {
                if(exist.qty < stokMax) exist.qty++;
                else alert("Stok barang tidak mencukupi!");
            } else {
                if(stokMax > 0) cart.push({id_barang: id, nama: nama, harga: harga, qty: 1, stokMax: stokMax});
                else alert("Stok habis!");
            }
            document.getElementById('keyword_barang').value = '';
            document.getElementById('hasil_pencarian_barang').innerHTML = '';
            renderCart();
        }

        function kurangiQty(index) {
            if(cart[index].qty > 1) {
                cart[index].qty--;
                renderCart();
            } else {
                hapusItem(index);
            }
        }

        function tambahQty(index) {
            if(cart[index].qty < cart[index].stokMax) {
                cart[index].qty++;
                renderCart();
            } else {
                alert("Stok maksimal tercapai!");
            }
        }

        function hapusItem(index) {
            cart.splice(index, 1);
            renderCart();
        }

        function renderCart() {
            let html = '';
            let subtotalKeseluruhan = 0;

            if(cart.length === 0) {
                html = `<tr><td colspan="5" style="text-align:center; color:#999;">Keranjang masih kosong</td></tr>`;
            } else {
                cart.forEach((item, index) => {
                    let subtotal = item.qty * item.harga;
                    subtotalKeseluruhan += subtotal;
                    html += `
                    <tr>
                        <td>${item.nama}</td>
                        <td>Rp ${formatRupiah(item.harga)}</td>
                        <td>
                            <div class="qty-control">
                                <button class="qty-btn" onclick="kurangiQty(${index})">-</button>
                                <input type="text" class="qty-input" value="${item.qty}" readonly>
                                <button class="qty-btn" onclick="tambahQty(${index})">+</button>
                            </div>
                        </td>
                        <td style="font-weight:bold;">Rp ${formatRupiah(subtotal)}</td>
                        <td><button class="btn btn-danger" onclick="hapusItem(${index})">🗑️</button></td>
                    </tr>`;
                });
            }

            document.getElementById('cart_body').innerHTML = html;

            // Hitung Diskon
            totalDiskon = 0;
            if(gunakanPoin && pelangganAktif && pelangganAktif.poin_member >= 100) {
                totalDiskon = subtotalKeseluruhan * 0.10; // Diskon 10%
                document.getElementById('label_diskon').style.display = 'block';
            } else {
                document.getElementById('label_diskon').style.display = 'none';
            }

            grandTotal = subtotalKeseluruhan - totalDiskon;
            document.getElementById('label_total').innerText = formatRupiah(grandTotal);
            hitungKembalian();
        }

        // ================= PELANGGAN & POIN (VERSI DROPDOWN) =================
        function pilihMember() {
            const select = document.getElementById('pilih_pelanggan');
            const infoDiv = document.getElementById('info_pelanggan');
            
            // Jika kasir memilih "Pelanggan Umum" (value kosong)
            if (select.value === "") {
                infoDiv.style.setProperty('display', 'none', 'important');
                pelangganAktif = null;
                gunakanPoin = false;
                renderCart();
                return;
            }

            // Ambil data dari option yang sedang dipilih
            const selectedOption = select.options[select.selectedIndex];
            const id = selectedOption.value;
            const nama = selectedOption.getAttribute('data-nama');
            const poin = parseInt(selectedOption.getAttribute('data-poin'));

            // Set state pelanggan aktif
            pelangganAktif = { id_pelanggan: id, nama_pelanggan: nama, poin_member: poin };
            
            let checkboxTukar = '';
            if (poin >= 100) {
                checkboxTukar = `
                <div style="background: #cfdacb; padding: 10px; border-radius: 5px; font-size: 12px; text-align:right;">
                    <label style="cursor: pointer; font-weight:bold;">
                        <input type="checkbox" onchange="toggleDiskon(this)"> Tukar 100 Poin
                    </label>
                    <br><span style="font-size: 10px;">(Diskon 10%)</span>
                </div>`;
            } else {
                checkboxTukar = `<div style="font-size:11px; color:#888;">Poin tidak cukup untuk diskon</div>`;
                gunakanPoin = false; // Reset otomatis jika ganti member yg poinnya kurang
            }

            // Tampilkan info box
            infoDiv.innerHTML = `
                <div style="font-size: 13px; line-height: 1.6;">
                    <strong>Nama:</strong> ${nama}<br>
                    <strong>Poin Anda:</strong> <span style="color:var(--primary-green); font-weight:bold;">${poin} Poin</span>
                </div>
                ${checkboxTukar}
            `;
            infoDiv.style.setProperty('display', 'flex', 'important');
            
            // Update kalkulasi total
            renderCart();
        }

        function toggleDiskon(checkbox) {
            gunakanPoin = checkbox.checked;
            renderCart();
        }

        // ================= PEMBAYARAN =================
        function hitungKembalian() {
            let bayar = parseInt(document.getElementById('uang_bayar').value) || 0;
            let kembalian = bayar - grandTotal;
            let label = document.getElementById('label_kembalian');
            
            if(kembalian < 0) {
                label.innerText = "Uang Kurang!";
                label.style.color = "red";
            } else {
                label.innerText = formatRupiah(kembalian);
                label.style.color = "var(--text-main)";
            }
        }

        async function simpanTransaksi(cetak) {
            if(cart.length === 0) return alert("Keranjang belanja kosong!");
            let bayar = parseInt(document.getElementById('uang_bayar').value) || 0;
            if(bayar < grandTotal) return alert("Uang pembayaran kurang dari total belanja!");

            const payload = {
                id_user: <?= $id_user ?>,
                id_pelanggan: pelangganAktif ? pelangganAktif.id_pelanggan : null,
                total_harga: grandTotal + totalDiskon,
                diskon: totalDiskon,
                bayar: bayar,
                kembalian: bayar - grandTotal,
                tukar_poin: gunakanPoin,
                items: cart
            };

            try {
                const response = await fetch('proses_transaksi.php', {
                    method: 'POST',
                    headers: {'Content-Type': 'application/json'},
                    body: JSON.stringify(payload)
                });
                
                const result = await response.json();
                
                if(result.status === 'success') {
                    alert("Transaksi Berhasil Disimpan!");
                    if(cetak) {
                        // Arahkan ke halaman cetak struk (buka tab baru)
                        window.open(`cetak_struk.php?id=${result.id_transaksi}`, '_blank');
                    }
                    window.location.reload(); // Reset aplikasi kasir
                } else {
                    alert("Gagal: " + result.message);
                }
            } catch (error) {
                alert("Terjadi kesalahan jaringan atau server.");
                console.error(error);
            }
        }
    </script>
</body>
</html>