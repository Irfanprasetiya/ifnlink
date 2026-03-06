@extends('layouts.app')

@section('title', 'Manajemen User')

@section('container')
    <!-- content -->
    <div class="mt-5">
        <div class="flex justify-between items-center">
            <div class="mt-5 font-bold text-xl lg:text-black sm:text-blue-500">
                <h1>Daftar User</h1>
            </div>
            <div>
                <!-- Modal toggle -->
                <button data-modal-target="crud-modal" data-modal-toggle="crud-modal"
                    class="block text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800"
                    type="button">
                    Tambah User
                </button>

                <!-- Main Modal: Register User -->
                <form method="POST" action="{{ route('users.register') }}">
                    @csrf
                    <div id="crud-modal" tabindex="-1" aria-hidden="true"
                        class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
                        <div class="relative p-4 w-full max-w-md max-h-full">
                            <!-- Modal content -->
                            <div class="relative bg-white rounded-lg shadow-sm dark:bg-gray-700">
                                <!-- Modal header -->
                                <div
                                    class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600 border-gray-200">
                                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                                        Register User
                                    </h3>
                                    <button type="button"
                                        class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white"
                                        data-modal-toggle="crud-modal">
                                        <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                            fill="none" viewBox="0 0 14 14">
                                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                                stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                                        </svg>
                                        <span class="sr-only">Close modal</span>
                                    </button>
                                </div>

                                <!-- Modal body -->
                                <div class="p-4 md:p-5">
                                    <div class="grid gap-4 mb-4 grid-cols-2">
                                        <!-- Name -->
                                        <div class="col-span-2">
                                            <label for="name"
                                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Name</label>
                                            <input type="text" name="name" id="name" value="{{ old('name') }}"
                                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-600 focus:border-blue-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:text-white"
                                                placeholder="Full name" required>
                                            <x-input-error :messages="$errors->get('name')" class="mt-2" />
                                        </div>

                                        <!-- Username -->
                                        <div class="col-span-2">
                                            <label for="username"
                                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Username</label>
                                            <input type="text" name="username" id="username"
                                                value="{{ old('username') }}"
                                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-600 focus:border-blue-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:text-white"
                                                placeholder="Username" required>
                                            <x-input-error :messages="$errors->get('username')" class="mt-2" />
                                        </div>

                                        <!-- Password -->
                                        <div class="col-span-2">
                                            <label for="password"
                                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Password</label>
                                            <input type="password" name="password" id="password"
                                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-600 focus:border-blue-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:text-white"
                                                placeholder="Password" required autocomplete="new-password">
                                            <x-input-error :messages="$errors->get('password')" class="mt-2" />
                                        </div>

                                        <!-- Confirm Password -->
                                        <div class="col-span-2">
                                            <label for="password_confirmation"
                                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Confirm
                                                Password</label>
                                            <input type="password" name="password_confirmation" id="password_confirmation"
                                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-600 focus:border-blue-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:text-white"
                                                placeholder="Repeat password" required autocomplete="new-password">
                                            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                                        </div>

                                        <!-- Role -->
                                        <div class="col-span-2">
                                            <label for="role"
                                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Role</label>
                                            <select id="role" name="role"
                                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-600 focus:border-blue-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:text-white">
                                                <option value="user" {{ old('role') === 'user' ? 'selected' : '' }}>User
                                                </option>
                                                <option value="admin" {{ old('role') === 'admin' ? 'selected' : '' }}>
                                                    Admin
                                                </option>
                                                <option value="super_admin"
                                                    {{ old('role') === 'super_admin' ? 'selected' : '' }}>Super Admin
                                                </option>
                                                <option value="owner" {{ old('role') === 'owner' ? 'selected' : '' }}>
                                                    Owner
                                                </option>
                                            </select>
                                            <x-input-error :messages="$errors->get('role')" class="mt-2" />
                                        </div>

                                        <!-- Cabang -->
                                        <div class="col-span-2">
                                            <label for="cabang_id"
                                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Cabang</label>
                                            <select id="cabang_id" name="cabang_id"
                                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-600 focus:border-blue-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:text-white">
                                                @foreach ($cabangs as $cabang)
                                                    <option value="{{ $cabang->id }}"
                                                        {{ old('cabang_id') == $cabang->id ? 'selected' : '' }}>
                                                        {{ $cabang->nama_cabang }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            <x-input-error :messages="$errors->get('cabang_id')" class="mt-2" />
                                        </div>

                                    </div>

                                    <!-- Submit Button -->
                                    <button type="submit"
                                        class="w-full text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                                        {{ __('Register') }}
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>


            </div>
        </div>

        <!-- table -->
        <div class="overflow-x-auto mt-5">
            <table
                class="min-w-full text-sm text-left text-gray-500 dark:text-gray-400 border border-gray-200 rounded-lg shadow-sm">
                <thead class="text-xs uppercase bg-blue-700 text-white dark:bg-red-500 dark:text-white">
                    <tr>
                        <th scope="col" class="px-4 py-3 text-center">No</th>
                        <th scope="col" class="px-4 py-3">Nama</th>
                        <th scope="col" class="px-4 py-3">Username</th>
                        <th scope="col" class="px-4 py-3 text-center">Password</th>
                        <th scope="col" class="px-4 py-3 text-center">Role</th>
                        <th scope="col" class="px-4 py-3">Cabang</th>
                        <th scope="col" class="px-4 py-3 text-center">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    @php $no = 1; @endphp
                    @foreach ($users as $user)
                        <tr
                            class="bg-white dark:bg-gray-800 even:bg-gray-50 dark:even:bg-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600">

                            <!-- Nomor Urut -->
                            <td class="px-4 py-3 text-center font-medium text-gray-900 dark:text-white">
                                {{ $no++ }}
                            </td>
                            <!-- Nama -->
                            <td class="px-4 py-3 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                {{ $user->name }}
                            </td>

                            <!-- Username -->
                            <td class="px-4 py-3">
                                {{ $user->username }}
                            </td>

                            <!-- Password (sebaiknya jangan tampilkan asli) -->
                            <td class="px-4 py-3 text-center">
                                ••••••
                            </td>

                            <!-- Role -->
                            <td class="px-4 py-3 text-center">
                                <span
                                    class="inline-block px-2 py-1 text-xs font-semibold rounded-full 
                                    {{ $user->role === 'admin' ? 'bg-purple-100 text-purple-700' : 'bg-blue-100 text-blue-700' }}">
                                    {{ ucfirst($user->role) }}
                                </span>
                            </td>

                            <!-- Cabang -->
                            <td class="px-4 py-3">
                                {{ $user->cabang->nama_cabang ?? '-' }}
                            </td>

                            <!-- Action -->
                            <td class="px-4 py-3 flex flex-wrap gap-2 justify-center">
                                <!-- Edit -->
                                <button type="button" data-modal-target="edit-modal-{{ $user->id }}"
                                    data-modal-toggle="edit-modal-{{ $user->id }}"
                                    class="p-2 bg-yellow-500 hover:bg-yellow-600 text-white rounded">
                                    <!-- Icon Pencil -->
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15.232 5.232l3.536 3.536M9 11l6.232-6.232a2 2 0 112.828 2.828L11.828 13.828a2 2 0 01-1.414.586H9v-2z" />
                                    </svg>
                                </button>

                                <!-- Hapus -->
                                <form action="{{ route('users.destroy', $user->id) }}" method="POST"
                                    onsubmit="return confirm('Yakin ingin menghapus user ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-2 bg-red-600 hover:bg-red-700 text-white rounded">
                                        <!-- Icon Trash -->
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6M9 7h6m2 0a2 2 0 012 2v0a2 2 0 01-2 2H7a2 2 0 01-2-2v0a2 2 0 012-2h10z" />
                                        </svg>
                                    </button>
                                </form>
                            </td>
                        </tr>

                        <!-- Modal Edit User -->
                        <div id="edit-modal-{{ $user->id }}" tabindex="-1"
                            class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
                            <div class="bg-white dark:bg-gray-800 p-6 rounded-lg w-full max-w-lg">
                                <div class="flex justify-between items-center mb-4">
                                    <h3 class="text-lg font-bold text-gray-900 dark:text-white">Edit User</h3>
                                    <button type="button" data-modal-toggle="edit-modal-{{ $user->id }}"
                                        class="text-gray-400 hover:text-red-600 text-xl">×</button>
                                </div>
                                <!-- Form edit user -->
                                <form method="POST" action="{{ route('users.update', $user->id) }}">
                                    @csrf
                                    @method('PUT')
                                    <!-- Input fields -->
                                    <div class="mb-4">
                                        <label class="block text-sm font-medium text-gray-700 dark:text-white">Nama</label>
                                        <input type="text" name="name" value="{{ $user->name }}" required
                                            class="w-full mt-1 p-2 border rounded dark:bg-gray-700 dark:text-white">
                                    </div>
                                    <div class="mb-4">
                                        <label
                                            class="block text-sm font-medium text-gray-700 dark:text-white">Username</label>
                                        <input type="text" name="username" value="{{ $user->username }}" required
                                            class="w-full mt-1 p-2 border rounded dark:bg-gray-700 dark:text-white">
                                    </div>
                                    <div class="mb-4">
                                        <label class="block text-sm font-medium text-gray-700 dark:text-white">Role</label>
                                        <select name="role"
                                            class="w-full mt-1 p-2 border rounded dark:bg-gray-700 dark:text-white">
                                            <option value="user" {{ $user->role == 'user' ? 'selected' : '' }}>User
                                            </option>
                                            <option value="admin" {{ $user->role == 'admin' ? 'selected' : '' }}>Admin
                                            </option>
                                        </select>
                                    </div>
                                    <div class="mb-4">
                                        <label
                                            class="block text-sm font-medium text-gray-700 dark:text-white">Cabang</label>
                                        <select name="cabang_id"
                                            class="w-full mt-1 p-2 border rounded dark:bg-gray-700 dark:text-white">
                                            @foreach ($cabangs as $cabang)
                                                <option value="{{ $cabang->id }}"
                                                    {{ $user->cabang_id == $cabang->id ? 'selected' : '' }}>
                                                    {{ $cabang->nama_cabang }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <button type="submit"
                                        class="w-full bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">Simpan</button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </tbody>
            </table>
        </div>


    </div>
@endsection
