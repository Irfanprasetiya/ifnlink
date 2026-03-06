 <aside id="logo-sidebar" :class="sidebarOpen ? 'translate-x-0 w-64' : '-translate-x-full w-0'"
     class="fixed top-0 left-0 z-40 h-screen pt-20 transition-all bg-white border-r border-gray-200 dark:bg-gray-800 dark:border-gray-700"
     aria-label="Sidebar">

     <div class="h-full pb-4 overflow-y-auto bg-white dark:bg-gray-800">
         <div class="px-3">
             <ul class="space-y-3 font-medium">
                 @if (Auth::user()->role === 'super_admin')
                     <x-sidebar-menu label="Dashboard" route="dashboard" icon="dashboard" active="dashboard" />


                     <!-- Transaksi -->

                     <li>
                         <button type="button"
                             class="flex items-center w-full p-2 text-base text-gray-900 transition duration-75 rounded-lg group hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700"
                             aria-controls="dropdown-transaksi" data-collapse-toggle="dropdown-transaksi">

                             <x-icons.transaksi />

                             <span class="flex-1 ms-3 text-left rtl:text-right whitespace-nowrap">Transaksi</span>
                             <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                                 viewBox="0 0 10 6">
                                 <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                     stroke-width="2" d="m1 1 4 4 4-4" />
                             </svg>
                         </button>
                         <ul id="dropdown-transaksi" class="hidden py-2 space-y-2">
                             <li>
                                 <a href="{{ route('trx-bank.index') }}"
                                     class="flex items-center w-full p-2 text-gray-900 transition duration-75 rounded-lg pl-11 group hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700">Transaksi
                                     BriLink</a>
                             </li>
                             <li>
                                 <a href="#"
                                     class="flex items-center w-full p-2 text-gray-900 transition duration-75 rounded-lg pl-11 group hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700">Transaksi
                                     Konter</a>
                             </li>
                         </ul>
                     </li>
                     {{-- end Transaksi --}}

                     <!-- <li>
               <a href="{{ route('data_master.produk_konter.index') }}"
                   class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group">
                   <svg class="shrink-0 w-5 h-5 text-gray-500 transition duration-75 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white"
                       aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 18 20">
                       <path
                           d="M17 5.923A1 1 0 0 0 16 5h-3V4a4 4 0 1 0-8 0v1H2a1 1 0 0 0-1 .923L.086 17.846A2 2 0 0 0 2.08 20h13.84a2 2 0 0 0 1.994-2.153L17 5.923ZM7 9a1 1 0 0 1-2 0V7h2v2Zm0-5a2 2 0 1 1 4 0v1H7V4Zm6 5a1 1 0 1 1-2 0V7h2v2Z" />
                   </svg>
                   <span class="flex-1 ms-3 whitespace-nowrap">Produk Konter</span>
               </a>
           </li> -->
                     <!-- <li>
               <a href="{{ route('data_master.produk_konter.index') }}"
                   class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group">
                   <svg class="shrink-0 w-5 h-5 text-gray-500 transition duration-75 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white"
                       aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 18 20">
                       <path
                           d="M17 5.923A1 1 0 0 0 16 5h-3V4a4 4 0 1 0-8 0v1H2a1 1 0 0 0-1 .923L.086 17.846A2 2 0 0 0 2.08 20h13.84a2 2 0 0 0 1.994-2.153L17 5.923ZM7 9a1 1 0 0 1-2 0V7h2v2Zm0-5a2 2 0 1 1 4 0v1H7V4Zm6 5a1 1 0 1 1-2 0V7h2v2Z" />
                   </svg>
                   <span class="flex-1 ms-3 whitespace-nowrap">Manajemen Stok</span>
               </a>
           </li> -->

                     <!-- Laporan Bank & Konter -->
                     <li>
                         <button type="button"
                             class="flex items-center w-full p-2 text-base text-gray-900 transition duration-75 rounded-lg group hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700"
                             aria-controls="dropdown-example" data-collapse-toggle="dropdown-laporan">

                             <x-icons.laporan-transaksi />

                             <span class="flex-1 ms-3 text-left rtl:text-right whitespace-nowrap">Laporan
                                 Transaksi</span>
                             <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                                 viewBox="0 0 10 6">
                                 <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                     stroke-width="2" d="m1 1 4 4 4-4" />
                             </svg>
                         </button>
                         <ul id="dropdown-laporan" class="hidden py-2 space-y-2">
                             {{-- brilink --}}
                             <li>
                                 <a href="{{ route('laporan-bank.admin.index') }}"
                                     class="flex items-center w-full p-2 text-gray-900 transition duration-75 rounded-lg pl-11 group hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700">Laporan
                                     BriLink</a>
                             </li>

                             {{-- Konter --}}
                             <li>
                                 <a href="#"
                                     class="flex items-center w-full p-2 text-gray-900 transition duration-75 rounded-lg pl-11 group hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700">Laporan
                                     Konter</a>
                             </li>
                         </ul>
                     </li>
                     {{-- end --}}

                     <!-- data master -->
                     <li>
                         <button type="button"
                             class="flex items-center w-full p-2 text-base text-gray-900 transition duration-75 rounded-lg group hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700"
                             aria-controls="dropdown-example" data-collapse-toggle="dropdown-example">

                             <x-icons.data-master />

                             <span class="flex-1 ms-3 text-left rtl:text-right whitespace-nowrap">Data Master</span>
                             <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                                 viewBox="0 0 10 6">
                                 <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                     stroke-width="2" d="m1 1 4 4 4-4" />
                             </svg>
                         </button>
                         <ul id="dropdown-example" class="hidden py-2 space-y-2">
                             <li>
                                 <a href="{{ route('data_master.cabang.index') }}"
                                     class="flex items-center w-full p-2 text-gray-900 transition duration-75 rounded-lg pl-11 group hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700">Cabang</a>
                             </li>
                             <!-- <li>
                       <a href="{{ route('data_master.vouchers.index') }}"
                           class="flex items-center w-full p-2 text-gray-900 transition duration-75 rounded-lg pl-11 group hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700">Voucher</a>
                   </li> -->
                             <li>
                                 <a href="{{ route('data_master.kategoris.index') }}"
                                     class="flex items-center w-full p-2 text-gray-900 transition duration-75 rounded-lg pl-11 group hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700">Kategori</a>
                             </li>
                             <li>
                                 <a href="{{ route('data_master.jenis-transaksi.index') }}"
                                     class="flex items-center w-full p-2 text-gray-900 transition duration-75 rounded-lg pl-11 group hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700">Jenis
                                     Transaksi</a>
                             </li>
                             <li>
                                 <a href="{{ route('data_master.daftar_bank.index') }}"
                                     class="flex items-center w-full p-2 text-gray-900 transition duration-75 rounded-lg pl-11 group hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700">Daftar
                                     Bank</a>
                             </li>
                             <li>
                                 <a href="{{ route('data_master.akun_pengeluaran.index') }}"
                                     class="flex items-center w-full p-2 text-gray-900 transition duration-75 rounded-lg pl-11 group hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700">Akun
                                     Pengeluaran</a>
                             </li>
                         </ul>
                     </li>

                     {{-- saldo gudang --}}
                     <x-sidebar-menu label="Saldo Gudang" route="saldo_gudang.index" icon="saldo-gudang"
                         active="saldo-gudang" />
                     {{-- end --}}


                     <!-- manajemen barang -->
                     <!-- <li>
               <button type="button"
                   class="flex items-center w-full p-2 text-base text-gray-900 transition duration-75 rounded-lg group hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700"
                   aria-controls="dropdown-example" data-collapse-toggle="dropdown-barang">
                   <svg class="shrink-0 w-5 h-5 text-gray-800 dark:text-white" aria-hidden="true"
                       xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                       <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                           d="M19 6c0 1.657-3.134 3-7 3S5 7.657 5 6m14 0c0-1.657-3.134-3-7-3S5 4.343 5 6m14 0v6M5 6v6m0 0c0 1.657 3.134 3 7 3s7-1.343 7-3M5 12v6c0 1.657 3.134 3 7 3s7-1.343 7-3v-6" />
                   </svg>

                   <span class="flex-1 ms-3 text-left rtl:text-right whitespace-nowrap">Manajemen Barang</span>
                   <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                       viewBox="0 0 10 6">
                       <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                           d="m1 1 4 4 4-4" />
                   </svg>
               </button>
               <ul id="dropdown-barang" class="hidden py-2 space-y-2">
                   <li>
                       <a href="{{ route('barang_masuk.index') }}"
                           class="flex items-center w-full p-2 text-gray-900 transition duration-75 rounded-lg pl-11 group hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700">Barang
                           Masuk</a>
                   </li>
                   <li>
                       <a href="{{ route('data_master.vouchers.index') }}"
                           class="flex items-center w-full p-2 text-gray-900 transition duration-75 rounded-lg pl-11 group hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700">Barang
                           Keluar</a>
                   </li>
               </ul>
           </li> -->

                     <!-- Pengeluaran -->
                     <li>
                         <a href="{{ route('pengeluaran.index') }}"
                             class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group">

                             <x-icons.pengeluaran />

                             <span class="flex-1 ms-3 whitespace-nowrap">Pengeluaran</span>
                         </a>
                     </li>
                     {{-- end --}}

                     <!-- laporan Saldo -->
                     <li>
                         <a href="{{ route('laporan_saldo.index') }}"
                             class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group">

                             <x-icons.laporan-saldo />

                             <span class="flex-1 ms-3 whitespace-nowrap">Laporan Saldo</span>
                         </a>
                     </li>
                     {{-- end --}}


                     <!-- laba Rugi -->
                     <li>
                         <a href="{{ route('laba_rugi.index') }}"
                             class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group">

                             <x-icons.laba-rugi />

                             <span class="flex-1 ms-3 whitespace-nowrap">Laba Rugi</span>
                         </a>
                     </li>
                     {{-- end --}}

                     <!-- Users -->
                     <li>
                         <a href="{{ route('users.index') }}"
                             class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group">

                             <x-icons.users />

                             <span class="flex-1 ms-3 whitespace-nowrap">Users</span>
                         </a>
                     </li>


                     <a href="{{ route('register') }}"
                         class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group">
                         <svg class="shrink-0 w-5 h-5 text-gray-500 transition duration-75 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white"
                             aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                             viewBox="0 0 18 16">
                             <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                 stroke-width="2"
                                 d="M1 8h11m0 0L8 4m4 4-4 4m4-11h3a2 2 0 0 1 2 2v10a2 2 0 0 1-2 2h-3" />
                         </svg>
                         <span class="flex-1 ms-3 whitespace-nowrap">Sign In</span>
                     </a>
                     </li>
                     <li>
                         <a href="#"
                             class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group">
                             <svg class="shrink-0 w-5 h-5 text-gray-500 transition duration-75 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white"
                                 aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor"
                                 viewBox="0 0 20 20">
                                 <path d="M5 5V.13a2.96 2.96 0 0 0-1.293.749L.879 3.707A2.96 2.96 0 0 0 .13 5H5Z" />
                                 <path
                                     d="M6.737 11.061a2.961 2.961 0 0 1 .81-1.515l6.117-6.116A4.839 4.839 0 0 1 16 2.141V2a1.97 1.97 0 0 0-1.933-2H7v5a2 2 0 0 1-2 2H0v11a1.969 1.969 0 0 0 1.933 2h12.134A1.97 1.97 0 0 0 16 18v-3.093l-1.546 1.546c-.413.413-.94.695-1.513.81l-3.4.679a2.947 2.947 0 0 1-1.85-.227 2.96 2.96 0 0 1-1.635-3.257l.681-3.397Z" />
                                 <path
                                     d="M8.961 16a.93.93 0 0 0 .189-.019l3.4-.679a.961.961 0 0 0 .49-.263l6.118-6.117a2.884 2.884 0 0 0-4.079-4.078l-6.117 6.117a.96.96 0 0 0-.263.491l-.679 3.4A.961.961 0 0 0 8.961 16Zm7.477-9.8a.958.958 0 0 1 .68-.281.961.961 0 0 1 .682 1.644l-.315.315-1.36-1.36.313-.318Zm-5.911 5.911 4.236-4.236 1.359 1.359-4.236 4.237-1.7.339.341-1.699Z" />
                             </svg>
                             <span class="flex-1 ms-3 whitespace-nowrap">Sign Up</span>
                         </a>
                     </li> -->
                 @endif
                 @if (Auth::user()->role === 'admin')
                     {{-- dashboard admin --}}
                     <x-sidebar-menu label="Dashboard" route="dashboard" icon="dashboard" active="dashboard" />

                     <!-- Transaksi -->
                     <li>
                         <button type="button"
                             class="{{ Request::is('trx*') ? 'bg-blue-600 text-white border-l-4 border-blue-600 rounded-lg' : '' }} flex items-center w-full p-2 text-base text-gray-900 hover:text-white transition duration-75 rounded-lg group hover:bg-blue-600 dark:text-white dark:hover:bg-gray-700"
                             aria-controls="dropdown-transaksi" data-collapse-toggle="dropdown-transaksi">

                             <x-icons.transaksi />

                             <span class="flex-1 ms-3 text-left rtl:text-right whitespace-nowrap">Transaksi</span>
                             <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                 fill="none" viewBox="0 0 10 6">
                                 <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                     stroke-width="2" d="m1 1 4 4 4-4" />
                             </svg>
                         </button>
                         <ul id="dropdown-transaksi" class="hidden py-2 space-y-2">

                             <x-sidebar-menu label="Transaksi BriLink" route="trx-bank.index" active="trx*" />

                             <x-sidebar-menu label="Transaksi Konter" route="trx-bank.index" />

                         </ul>
                     </li>
                     {{-- end Transaksi --}}

                     <!-- Laporan Bank & Konter -->
                     <li>
                         <button type="button"
                             class="{{ Request::is('laporan-bank*') ? 'bg-blue-600 text-white border-l-4 border-blue-600 rounded-lg' : '' }} flex items-center w-full p-2 text-base text-gray-900 hover:text-white transition duration-75 rounded-lg group hover:bg-blue-600 dark:text-white dark:hover:bg-gray-700"
                             aria-controls="dropdown-example" data-collapse-toggle="dropdown-laporan">

                             <x-icons.laporan-transaksi />

                             <span class="flex-1 ms-3 text-left rtl:text-right whitespace-nowrap">Laporan
                                 Transaksi</span>
                             <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                 fill="none" viewBox="0 0 10 6">
                                 <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                     stroke-width="2" d="m1 1 4 4 4-4" />
                             </svg>
                         </button>
                         <ul id="dropdown-laporan" class="hidden py-2 space-y-2">
                             {{-- brilink --}}
                             <x-sidebar-menu label="Laporan BriLink" route="laporan-bank.admin.index"
                                 active="laporan-bank*" />


                             {{-- Konter --}}
                             <x-sidebar-menu label="Laporan Konter" route="laporan-bank.admin.index" />

                         </ul>
                     </li>
                     {{-- end --}}

                     <!-- data master -->
                     <li>
                         <button type="button"
                             class="{{ Request::is(['data_master*', 'cabang*', 'kategoris*', 'jenis-transaksi*', 'daftar-bank*', 'akun-pengeluaran*']) ? 'bg-blue-600 text-white border-l-4 border-blue-600 rounded-lg' : '' }} flex items-center w-full p-2 text-base text-gray-900 hover:text-white transition duration-75 rounded-lg group hover:bg-blue-600 dark:text-white dark:hover:bg-gray-700"
                             aria-controls="dropdown-example" data-collapse-toggle="dropdown-example">

                             <x-icons.data-master />

                             <span class="flex-1 ms-3 text-left rtl:text-right whitespace-nowrap">Data Master</span>
                             <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                 fill="none" viewBox="0 0 10 6">
                                 <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                     stroke-width="2" d="m1 1 4 4 4-4" />
                             </svg>
                         </button>
                         <ul id="dropdown-example" class="hidden py-2 space-y-2">

                             <x-sidebar-menu label="Cabang" route="data_master.cabang.index" active="cabang*" />

                             <!-- <li>
                          <a href="{{ route('data_master.vouchers.index') }}"
                              class="flex items-center w-full p-2 text-gray-900 transition duration-75 rounded-lg pl-11 group hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700">Voucher</a>
                      </li> -->
                             <x-sidebar-menu label="Kategori" route="data_master.kategoris.index"
                                 active="data_master/kategoris*" />
                             <x-sidebar-menu label="Jenis Transaksi" route="data_master.jenis-transaksi.index"
                                 active="data_master/jenis-transaksi*" />
                             <x-sidebar-menu label="Daftar Bank" route="data_master.daftar_bank.index"
                                 active="data_master/daftar_bank*" />
                             <x-sidebar-menu label="Akun Pengeluaran" route="data_master.akun_pengeluaran.index"
                                 active="data_master/akun-pengeluaran*" />

                         </ul>
                     </li>

                     {{-- saldo gudang --}}

                     <x-sidebar-menu label="Saldo Gudang" route="saldo_gudang.index" icon="saldo-gudang"
                         active="saldo-gudang" />

                     {{-- end --}}

                     <!-- Pengeluaran -->

                     <x-sidebar-menu label="Pengeluaran" route="pengeluaran.index" icon="pengeluaran"
                         active="pengeluaran" />

                     {{-- end --}}

                     <!-- Users -->

                     <x-sidebar-menu label="Manajemen User" route="users.index" icon="users" active="users" />

                     {{-- end --}}
                 @endif

                 @if (Auth::user()->role === 'owner')
                     {{-- dashboard --}}

                     <x-sidebar-menu label="Dashboard" route="dashboard" icon="dashboard" active="dashboard" />

                     <!-- Laporan Bank & Konter -->
                     <li>
                         <button type="button"
                             class="{{ Request::is('laporan-bank*') ? 'bg-blue-600 text-white border-l-4 border-blue-600 rounded-lg' : '' }} flex items-center w-full p-2 text-base text-gray-900 hover:text-white transition duration-75 rounded-lg group hover:bg-blue-600 dark:text-white dark:hover:bg-gray-700"
                             aria-controls="dropdown-example" data-collapse-toggle="dropdown-laporan">

                             <x-icons.laporan-transaksi />

                             <span class="flex-1 ms-3 text-left rtl:text-right whitespace-nowrap">Laporan
                                 Transaksi</span>
                             <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                 fill="none" viewBox="0 0 10 6">
                                 <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                     stroke-width="2" d="m1 1 4 4 4-4" />
                             </svg>
                         </button>
                         <ul id="dropdown-laporan" class="hidden py-2 space-y-2">
                             {{-- brilink --}}
                             <x-sidebar-menu label="Laporan BriLink" route="laporan-bank.admin.index"
                                 active="laporan-bank*" />


                             {{-- Konter --}}
                             <x-sidebar-menu label="Laporan Konter" route="laporan-bank.admin.index" />

                         </ul>
                     </li>
                     {{-- end --}}

                     <!-- laporan Saldo -->

                     <x-sidebar-menu label="Laporan Saldo" route="laporan_saldo.index" icon="laporan-saldo"
                         active="laporan-saldo*" />

                     {{-- end --}}

                     <!-- laba Rugi -->

                     <x-sidebar-menu label="Laba Rugi" route="laba_rugi.index" icon="laba-rugi"
                         active="laba-rugi*" />

                     <!-- Users -->

                     <x-sidebar-menu label="Manajemen User" route="users.index" icon="users" active="users" />

                     {{-- end --}}
                 @endif

             </ul>
         </div>
     </div>
 </aside>
