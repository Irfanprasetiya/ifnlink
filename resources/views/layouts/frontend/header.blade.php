    <!-- Header Box -->
    <section class="bg-white rounded-lg shadow-md p-5 mt-20 mb-8">
        <div class="flex justify-start items-center flex-wrap mb-3">
            <div class="flex items-center space-x-1">
                <div class="w-20 h-20 rounded overflow-hidden flex-shrink-0">
                    <img src="{{ asset('assets/images/irfan.png') }}"
                        alt="Logo merah kuning IC dengan tulisan pilihan kita bersama di bawahnya"
                        class="object-contain w-full h-full"
                        onerror="this.onerror=null;this.src='https://storage.googleapis.com/workspace-0f70711f-8b4e-4d94-86f1-2a93ccde5887/image/1f5b150c-5e0d-4d47-be9f-199775963a42.png';" />
                </div>
                <h2 class="text-lg font-semibold select-none cursor-default">Cabang
                    {{ Auth::user()->cabang->nama_cabang }}
                </h2>
            </div>
        </div>

        <!-- breadcrumb -->
        <x-breadcrumb />
    </section>
