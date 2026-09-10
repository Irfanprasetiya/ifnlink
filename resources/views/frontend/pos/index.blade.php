@extends('layouts.frontend.app')

@section('container')
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 pb-28 relative">

        {{-- ========== DAFTAR PRODUK ========== --}}
        <div class="lg:col-span-2 bg-white rounded-2xl shadow-soft border border-slate-200/80 p-4 sm:p-6 flex flex-col h-full">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
                <h2 class="text-xl font-extrabold text-slate-800 tracking-tight">Pilih Produk</h2>
                <div class="relative w-full sm:w-64 shrink-0">
                    <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400" fill="none"
                        stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                    <input type="text" id="search-produk" placeholder="Cari nama produk..." autocomplete="off"
                        class="w-full bg-slate-50 border border-slate-200 rounded-xl pl-9 pr-4 py-2.5 text-sm transition-all focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500/30 focus:border-blue-500">
                </div>
            </div>

            <div class="grid grid-cols-2 md:grid-cols-3 xl:grid-cols-4 gap-3 sm:gap-4 overflow-y-auto" id="produk-list">
                @forelse($produks as $produk)
                    @php $isHabis = $produk->stok <= 0; @endphp

                    <button type="button"
                        class="produk-item group flex flex-col justify-between border rounded-xl p-3 sm:p-4 text-left transition-all
                            {{ $isHabis
                                ? 'border-slate-200 bg-slate-100 opacity-60 cursor-not-allowed'
                                : 'border-slate-200 bg-white hover:border-blue-400 hover:shadow-[0_8px_15px_-3px_rgba(37,99,235,0.12)] active:scale-95' }}"
                        data-id="{{ $produk->voucher_id }}"
                        data-nama="{{ $produk->voucher->nama_produk }}"
                        data-nama-lower="{{ strtolower($produk->voucher->nama_produk) }}"
                        data-harga="{{ $produk->voucher->harga_jual }}"
                        data-stok="{{ $produk->stok }}"
                        data-habis="{{ $isHabis ? '1' : '0' }}"
                        {{ $isHabis ? 'disabled' : '' }}>

                        <div class="w-full">
                            <p class="font-bold text-sm leading-snug line-clamp-2
                                {{ $isHabis ? 'text-slate-500' : 'text-slate-800 group-hover:text-blue-700 transition-colors' }}">
                                {{ $produk->voucher->nama_produk }}
                            </p>
                        </div>

                        <div class="mt-3 w-full">
                            <p class="font-extrabold text-sm sm:text-base {{ $isHabis ? 'text-slate-400' : 'text-blue-600' }}">
                                Rp {{ number_format($produk->voucher->harga_jual, 0, ',', '.') }}
                            </p>

                            <div class="flex items-center gap-1.5 mt-1">
                                <span class="w-1.5 h-1.5 rounded-full
                                    {{ $produk->stok > 5 ? 'bg-emerald-500' : ($produk->stok > 0 ? 'bg-amber-500' : 'bg-rose-500') }}">
                                </span>
                                <p class="text-xs font-medium {{ $isHabis ? 'text-rose-500 font-bold' : 'text-slate-500' }}">
                                    {{ $isHabis ? 'Stok Habis' : 'Sisa: ' . $produk->stok }}
                                </p>
                            </div>
                        </div>
                    </button>
                @empty
                    <div class="col-span-full flex flex-col items-center justify-center py-16 text-slate-400">
                        <svg class="w-12 h-12 mb-3 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4">
                            </path>
                        </svg>
                        <p class="font-medium">Belum ada produk tersedia</p>
                    </div>
                @endforelse

                {{-- ✅ Pesan jika hasil search kosong --}}
                <div id="search-empty" class="hidden col-span-full flex flex-col items-center justify-center py-16 text-slate-400">
                    <svg class="w-12 h-12 mb-3 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                    <p class="font-medium">Produk tidak ditemukan</p>
                </div>
            </div>
        </div>

        {{-- ========== KERANJANG ========== --}}
        <div id="keranjang-section" class="lg:sticky lg:top-20 h-fit flex flex-col">
            <div class="bg-white rounded-2xl shadow-soft border border-slate-200/80 p-4 sm:p-5 flex flex-col">
                <div class="flex items-center justify-between mb-4 border-b border-slate-100 pb-3">
                    <h2 class="text-lg font-extrabold text-slate-800 flex items-center gap-2">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z">
                            </path>
                        </svg>
                        Keranjang
                    </h2>
                    <span id="badge-item-count" class="bg-blue-100 text-blue-700 text-xs font-bold px-2 py-1 rounded-md">0 Item</span>
                </div>

                {{-- Alert error keranjang --}}
                <div id="cart-alert"
                    class="hidden mb-3 bg-rose-50 border border-rose-200 text-rose-700 px-3 py-2.5 rounded-xl text-sm font-medium flex items-start gap-2">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z">
                        </path>
                    </svg>
                    <span id="cart-alert-text"></span>
                </div>

                <div id="cart-items" class="space-y-3 max-h-[35vh] lg:max-h-[300px] overflow-y-auto pr-1 no-scrollbar mb-4">
                    <div class="text-center py-8 text-slate-400 text-sm font-medium">
                        Keranjang masih kosong.
                    </div>
                </div>

                <div class="border-t border-slate-100 pt-4 space-y-4">
                    <div class="flex justify-between items-center px-1">
                        <span class="text-sm font-medium text-slate-500">Subtotal</span>
                        <span id="total-harga" class="text-base font-bold text-slate-800">Rp 0</span>
                    </div>

                    <div>
                        <label class="text-[11px] font-bold uppercase tracking-wider text-slate-500 mb-1.5 block px-1">
                            Potongan Diskon
                        </label>
                        <div class="relative">
                            <span class="absolute left-3.5 top-1/2 -translate-y-1/2 font-bold text-slate-400">Rp</span>
                            <input type="text" id="diskon" inputmode="numeric" placeholder="0"
                                class="w-full bg-slate-50 border border-slate-200 rounded-xl pl-10 pr-4 py-2.5 text-base font-bold text-slate-800 transition-all focus:bg-white focus:outline-none focus:ring-2 focus:ring-amber-500/30 focus:border-amber-500">
                        </div>
                    </div>

                    <div class="flex justify-between items-center px-1 border-b border-slate-100 pb-4">
                        <span class="text-sm font-bold text-slate-700">Total Akhir</span>
                        <span id="total-setelah-diskon" class="text-xl font-black text-blue-600">Rp 0</span>
                    </div>

                    <div>
                        <div class="flex items-center justify-between mb-1.5 px-1">
                            <label class="text-[11px] font-bold uppercase tracking-wider text-slate-500">Uang Pelanggan</label>
                            <button type="button" id="btn-uang-pas"
                                class="text-xs font-bold text-emerald-600 bg-emerald-50 hover:bg-emerald-100 px-2.5 py-1 rounded-md transition-colors">
                                Set Uang Pas
                            </button>
                        </div>
                        <div class="relative">
                            <span class="absolute left-3.5 top-1/2 -translate-y-1/2 font-bold text-slate-400">Rp</span>
                            <input type="text" id="bayar" inputmode="numeric" placeholder="0"
                                class="w-full bg-slate-50 border border-slate-200 rounded-xl pl-10 pr-4 py-3 text-lg font-bold text-slate-800 transition-all focus:bg-white focus:outline-none focus:ring-2 focus:ring-emerald-500/30 focus:border-emerald-500">
                        </div>
                    </div>

                    <div class="flex justify-between items-center px-1 py-1">
                        <span class="text-sm font-bold text-slate-500">Kembalian</span>
                        <span id="kembalian" class="text-lg font-extrabold text-emerald-500">Rp 0</span>
                    </div>

                    <button id="btn-bayar" disabled
                        class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold text-base py-3.5 rounded-xl transition-all active:scale-[0.98] disabled:opacity-50 disabled:cursor-not-allowed shadow-[0_8px_20px_-6px_rgba(37,99,235,0.4)]">
                        Proses Transaksi
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- ✅ Floating Button Mobile --}}
    <button type="button" id="fab-cart"
        class="lg:hidden fixed bottom-24 right-4 z-40 bg-slate-900 text-white p-4 rounded-full shadow-lg flex items-center justify-center gap-2 transition-all transform translate-y-20 opacity-0">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z">
            </path>
        </svg>
        <span id="fab-badge"
            class="absolute -top-1 -right-1 bg-rose-500 text-white text-[10px] font-bold w-5 h-5 flex items-center justify-center rounded-full border-2 border-slate-900 hidden">0</span>
    </button>

    {{-- ✅ Modal sukses --}}
    <div id="success-modal"
        class="hidden fixed inset-0 z-[60] flex items-center justify-center bg-slate-900/60 backdrop-blur-sm p-4 opacity-0 transition-opacity duration-300">
        <div class="bg-white rounded-3xl shadow-2xl border border-slate-100 p-8 w-full max-w-sm text-center transform scale-95 transition-transform duration-300">
            <div class="w-20 h-20 rounded-full bg-emerald-100 flex items-center justify-center mx-auto mb-5 relative">
                <div class="absolute inset-0 rounded-full bg-emerald-400 animate-ping opacity-20"></div>
                <svg class="w-10 h-10 text-emerald-600 relative z-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" />
                </svg>
            </div>
            <h3 class="text-xl font-extrabold text-slate-800">Transaksi Berhasil!</h3>
            <p class="text-sm font-medium text-slate-500 mt-2">Uang kembalian ke pelanggan:</p>
            <div id="success-kembalian" class="text-3xl font-black text-emerald-600 mt-1 mb-6">Rp 0</div>

            <a id="btn-struk" href="#" target="_blank"
                class="w-full inline-block bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-3 rounded-xl transition-colors mb-2">
                Cetak Struk
            </a>
            <button onclick="closeSuccess()"
                class="w-full bg-slate-900 hover:bg-slate-800 text-white font-bold py-3.5 rounded-xl transition-colors">
                Tutup & Transaksi Baru
            </button>
        </div>
    </div>

    <style>
        .no-scrollbar::-webkit-scrollbar { display: none; }
        .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
    </style>

    <script>
        // ========== STATE ==========
        let cart = [];
        let activeDiskonId = null; // ✅ Pakai voucher_id, bukan index

        // ========== HELPERS ==========
        const formatRupiah = (angka) => 'Rp ' + Math.round(angka).toLocaleString('id-ID');
        const parseRupiah = (value) => parseInt(value.replace(/[^\d]/g, '')) || 0;

        function showCartAlert(msg) {
            const alertEl = document.getElementById('cart-alert');
            document.getElementById('cart-alert-text').textContent = msg;
            alertEl.classList.remove('hidden');
            clearTimeout(alertEl._timeout);
            alertEl._timeout = setTimeout(() => alertEl.classList.add('hidden'), 3000);
        }

        // ========== PRODUK CLICK ==========
        document.querySelectorAll('.produk-item').forEach(btn => {
            btn.addEventListener('click', () => {
                const id = btn.dataset.id;
                const nama = btn.dataset.nama;
                const harga = parseFloat(btn.dataset.harga);
                const stok = parseInt(btn.dataset.stok);
                const isHabis = btn.dataset.habis === '1';

                if (isHabis || stok <= 0) {
                    showCartAlert(`Maaf, stok ${nama} habis.`);
                    return;
                }

                const existing = cart.find(item => item.id === id);
                if (existing) {
                    if (existing.qty >= stok) {
                        showCartAlert(`Stok ${nama} tidak mencukupi (Sisa ${stok}).`);
                        return;
                    }
                    existing.qty++;
                } else {
                    cart.push({ id, nama, harga, qty: 1, stok, diskon: 0 });
                }

                renderCart();
            });
        });

        // ========== RENDER CART ==========
        function renderCart() {
            const container = document.getElementById('cart-items');
            const badgeCount = document.getElementById('badge-item-count');
            const fabBadge = document.getElementById('fab-badge');
            const fabCart = document.getElementById('fab-cart');

            if (cart.length === 0) {
                container.innerHTML = `<div class="text-center py-8 text-slate-400 text-sm font-medium">Keranjang masih kosong.</div>`;
                document.getElementById('btn-bayar').disabled = true;
                badgeCount.textContent = '0 Item';
                fabBadge.classList.add('hidden');
                fabCart.classList.add('translate-y-20', 'opacity-0');
                updateTotals();
                return;
            }

            document.getElementById('btn-bayar').disabled = false;

            let totalItems = 0;

            container.innerHTML = cart.map((item, index) => {
                const subtotal = item.harga * item.qty;
                const diskonItem = item.diskon || 0;
                const totalItem = subtotal - diskonItem;
                totalItems += item.qty;

                return `
                    <div class="bg-slate-50 border border-slate-100 rounded-xl p-2.5 sm:p-3 mb-2">
                        <div class="flex justify-between items-start">
                            <div class="min-w-0 flex-1 pr-2">
                                <p class="font-bold text-sm text-slate-800 truncate">${item.nama}</p>
                                <p class="text-[11px] sm:text-xs text-slate-500 font-medium mt-0.5">
                                    ${item.qty} x ${formatRupiah(item.harga)}
                                    ${diskonItem > 0 ? `<span class="text-rose-500 font-bold"> (-${formatRupiah(diskonItem)})</span>` : ''}
                                </p>
                                <p class="text-xs font-bold text-slate-700 mt-1">${formatRupiah(totalItem)}</p>
                            </div>
                            <div class="flex items-center gap-1.5 shrink-0">
                                <button onclick="updateQty(${index}, -1)" class="w-7 h-7 sm:w-8 sm:h-8 flex items-center justify-center bg-white border border-slate-200 text-slate-600 rounded-lg font-bold shadow-sm hover:bg-slate-100 transition active:scale-90">-</button>
                                <span class="font-bold text-sm w-5 sm:w-6 text-center">${item.qty}</span>
                                <button onclick="updateQty(${index}, 1)" class="w-7 h-7 sm:w-8 sm:h-8 flex items-center justify-center bg-white border border-slate-200 text-slate-600 rounded-lg font-bold shadow-sm hover:bg-slate-100 transition active:scale-90">+</button>
                                <button onclick="removeItem(${index})" class="shrink-0 w-7 h-7 sm:w-8 sm:h-8 ml-1 flex items-center justify-center bg-rose-50 hover:bg-rose-100 text-rose-500 rounded-lg transition active:scale-90">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                </button>
                            </div>
                        </div>
                        <div class="mt-2 flex items-center gap-2">
                            <label class="text-[10px] font-bold text-slate-400 uppercase">Diskon Item</label>
                            <input type="text"
                                id="diskon-item-${item.id}"
                                value="${diskonItem > 0 ? diskonItem.toLocaleString('id-ID') : ''}"
                                onfocus="activeDiskonId = '${item.id}'"
                                onblur="activeDiskonId = null"
                                oninput="setDiskonItem('${item.id}', this.value)"
                                placeholder="0"
                                class="w-28 bg-white border border-slate-200 rounded-lg px-2 py-1 text-xs font-bold text-rose-500 text-right focus:ring-2 focus:ring-rose-500/30 focus:border-rose-500">
                        </div>
                    </div>`;
            }).join('');

            badgeCount.textContent = `${totalItems} Item`;
            fabBadge.textContent = totalItems;
            fabBadge.classList.remove('hidden');
            fabCart.classList.remove('translate-y-20', 'opacity-0');

            // ✅ Kembalikan fokus ke input diskon yang aktif (by voucher_id)
            if (activeDiskonId !== null) {
                const activeInput = document.getElementById(`diskon-item-${activeDiskonId}`);
                if (activeInput) {
                    const length = activeInput.value.length;
                    activeInput.focus();
                    activeInput.setSelectionRange(length, length);
                }
            }

            updateTotals();
        }

        // ========== DISKON ITEM ==========
        function setDiskonItem(voucherId, value) {
            const item = cart.find(i => i.id === voucherId);
            if (!item) return;

            const clean = value.replace(/[^\d]/g, '');
            item.diskon = clean ? parseInt(clean) : 0;

            // Update nilai input langsung tanpa render ulang
            const input = document.getElementById(`diskon-item-${voucherId}`);
            if (input) {
                const cursorPos = input.selectionStart;
                input.value = item.diskon > 0 ? item.diskon.toLocaleString('id-ID') : '';

                // Kembalikan posisi cursor
                const newPos = input.value.length;
                input.setSelectionRange(newPos, newPos);
            }

            updateTotals();
        }

        // ========== UPDATE TOTAL ==========
        function updateTotals() {
            const total = cart.reduce((sum, item) => {
                const subtotal = item.harga * item.qty;
                const diskonItem = item.diskon || 0;
                return sum + (subtotal - diskonItem);
            }, 0);

            document.getElementById('total-harga').textContent = formatRupiah(total);

            // Update subtotal per item di keranjang (kalau ada)
            document.getElementById('total-setelah-diskon').textContent = formatRupiah(getTotalSetelahDiskon());

            hitungKembalian();
        }

        // ========== QTY ==========
        function updateQty(index, delta) {
            const newQty = cart[index].qty + delta;
            if (newQty <= 0) {
                cart.splice(index, 1);
            } else if (newQty > cart[index].stok) {
                showCartAlert(`Maksimal stok ${cart[index].nama} adalah ${cart[index].stok}.`);
                return;
            } else {
                cart[index].qty = newQty;
            }
            renderCart();
        }

        function removeItem(index) {
            cart.splice(index, 1);
            renderCart();
        }

        // ========== TOTAL ==========
        function getTotal() {
            return cart.reduce((sum, item) => {
                const subtotal = item.harga * item.qty;
                const diskonItem = item.diskon || 0;
                return sum + (subtotal - diskonItem);
            }, 0);
        }

        function getDiskon() {
            return parseRupiah(document.getElementById('diskon').value);
        }

        function getTotalSetelahDiskon() {
            const total = getTotal();
            const diskon = getDiskon();
            return Math.max(0, total - diskon);
        }

        function hitungKembalian() {
            const totalSetelahDiskon = getTotalSetelahDiskon();
            const bayar = parseRupiah(document.getElementById('bayar').value);
            const kembalian = bayar - totalSetelahDiskon;

            document.getElementById('total-setelah-diskon').textContent = formatRupiah(totalSetelahDiskon);

            const elKembalian = document.getElementById('kembalian');
            if (bayar > 0 && kembalian >= 0) {
                elKembalian.textContent = formatRupiah(kembalian);
                elKembalian.classList.remove('text-rose-500');
                elKembalian.classList.add('text-emerald-500');
            } else if (bayar > 0 && kembalian < 0) {
                elKembalian.textContent = 'Uang Kurang!';
                elKembalian.classList.remove('text-emerald-500');
                elKembalian.classList.add('text-rose-500');
            } else {
                elKembalian.textContent = 'Rp 0';
                elKembalian.classList.remove('text-rose-500');
                elKembalian.classList.add('text-emerald-500');
            }
        }

        // ========== INPUT DISKON & BAYAR ==========
        ['bayar', 'diskon'].forEach(id => {
            document.getElementById(id).addEventListener('input', function () {
                let value = this.value.replace(/[^\d]/g, '');
                this.value = value ? parseInt(value).toLocaleString('id-ID') : '';
                hitungKembalian();
            });
        });

        // ========== UANG PAS ==========
        document.getElementById('btn-uang-pas').addEventListener('click', () => {
            if (cart.length === 0) return;
            document.getElementById('bayar').value = getTotalSetelahDiskon().toLocaleString('id-ID');
            hitungKembalian();
        });

        // ========== SEARCH PRODUK (optimasi) ==========
        const searchInput = document.getElementById('search-produk');
        const searchEmpty = document.getElementById('search-empty');
        let searchTimeout;

        searchInput.addEventListener('input', function () {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                const keyword = this.value.toLowerCase().trim();
                const items = document.querySelectorAll('.produk-item');
                let found = 0;

                items.forEach(btn => {
                    const nama = btn.dataset.namaLower || '';
                    const match = !keyword || nama.includes(keyword);
                    btn.classList.toggle('hidden', !match);
                    if (match) found++;
                });

                // ✅ Tampilkan pesan kosong kalau hasil search 0
                searchEmpty.classList.toggle('hidden', found > 0 || !keyword);
            }, 150); // debounce 150ms
        });

        // ========== FAB CART ==========
        document.getElementById('fab-cart').addEventListener('click', () => {
            document.getElementById('keranjang-section').scrollIntoView({ behavior: 'smooth' });
        });

        // ========== BAYAR ==========
        document.getElementById('btn-bayar').addEventListener('click', () => {
            if (cart.length === 0) {
                showCartAlert('Keranjang masih kosong!');
                return;
            }

            const totalSetelahDiskon = getTotalSetelahDiskon();
            const bayar = parseRupiah(document.getElementById('bayar').value);
            const diskon = getDiskon();

            if (bayar < totalSetelahDiskon) {
                showCartAlert('Nominal bayar pelanggan tidak cukup!');
                return;
            }

            const btn = document.getElementById('btn-bayar');
            btn.disabled = true;
            btn.innerHTML = `<svg class="animate-spin h-5 w-5 mx-auto text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>`;

            fetch('{{ route('pos.store') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                },
                body: JSON.stringify({
                    items: cart.map(item => ({
                        voucher_id: item.id,
                        qty: item.qty,
                        diskon: item.diskon || 0,
                    })),
                    bayar: bayar,
                    diskon: diskon,
                }),
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    const modal = document.getElementById('success-modal');
                    document.getElementById('success-kembalian').textContent = formatRupiah(data.kembalian);
                    document.getElementById('btn-struk').href = '{{ url('/pos/struk') }}/' + data.kode_transaksi;
                    modal.classList.remove('hidden');
                    setTimeout(() => {
                        modal.classList.remove('opacity-0');
                        modal.firstElementChild.classList.remove('scale-95');
                    }, 10);
                } else {
                    showCartAlert(data.message);
                    resetBayarBtn(btn);
                }
            })
            .catch(() => {
                showCartAlert('Terjadi kesalahan koneksi sistem.');
                resetBayarBtn(btn);
            });
        });

        function resetBayarBtn(btn) {
            btn.disabled = false;
            btn.textContent = 'Proses Transaksi';
        }

        function closeSuccess() {
            const modal = document.getElementById('success-modal');
            modal.classList.add('opacity-0');
            modal.firstElementChild.classList.add('scale-95');
            setTimeout(() => {
                modal.classList.add('hidden');
                window.location.reload();
            }, 300);
        }
    </script>
@endsection