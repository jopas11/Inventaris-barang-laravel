<!-- resources/views/layouts/app.blade.php -->
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard - Inventaris Barang')</title>
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- AlpineJS -->
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x/dist/cdn.min.js" defer></script>
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <style>[x-cloak] { display: none !important; }</style>

</head>

<body class="bg-gray-100" x-data="{ sidebarOpen: true }">
    <!-- Header -->
    @include('components.header')

    <div class="flex">
        <!-- Sidebar -->
        @include('components.sidebar')

        <!-- Main Content -->
        <main class="flex-1 p-6 mt-16 h-[calc(100vh-64px)] overflow-y-auto transition-all duration-300"
            :class="sidebarOpen ? 'ml-64' : 'ml-5'">
            @yield('content')
        </main>

    </div>



    <!-- SweetAlert2 Notifikasi -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            setTimeout(() => {
                @if (session('login_success'))
                    Swal.fire({
                        title: "Login Berhasil!",
                        text: "{{ session('login_success') }}",
                        icon: "success",
                        confirmButtonText: "OK"
                    });
                @endif
    
                @if (session('crud_success'))
                    Swal.fire({
                        title: "Sukses!",
                        text: "{{ session('crud_success') }}",
                        icon: "success",
                        confirmButtonText: "OK"
                    });
                @endif

                @if (session('crud_error'))
    Swal.fire({
        title: "Gagal!",
        text: @json(session('crud_error')),
        icon: "error",
        confirmButtonText: "OK"
    });
@endif

    
            }, 300); // Delay 300ms sebelum notifikasi muncul
        });
    </script>
    @stack('scripts')

</body>

</html>
