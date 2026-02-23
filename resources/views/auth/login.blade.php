<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>

<body class="flex items-center justify-center min-h-screen bg-gradient-to-r from-indigo-500 to-purple-600">

    <div class="bg-white p-8 rounded-xl shadow-lg w-full max-w-sm text-center animate-fadeIn">
        <!-- Ikon Login -->
        <div class="flex justify-center mb-4 text-indigo-600 text-5xl">
            <i class="fas fa-user-circle"></i>
        </div>

        <!-- Judul -->
        <h2 class="text-2xl font-bold text-gray-800 mb-1">Selamat Datang Kembali!</h2>
        <p class="text-sm text-gray-500 mb-6">Silakan masuk ke akun Anda</p>

        <!-- Form -->
        <form action="{{ route('login') }}" method="POST" class="space-y-4 text-left">
            @csrf

            <!-- Email -->
            <div>
                <label for="email" class="block text-sm font-semibold text-gray-700 mb-1">Email</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                        <i class="fas fa-envelope"></i>
                    </span>
                    <input type="email" id="email" name="email" required
                        class="w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-400 transition">
                </div>
            </div>

            <!-- Password -->
            <div>
                <label for="password" class="block text-sm font-semibold text-gray-700 mb-1">Password</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                        <i class="fas fa-lock"></i>
                    </span>
                    <input type="password" id="password" name="password" required
                        class="w-full pl-10 pr-10 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-400 transition">
                    <span class="absolute inset-y-0 right-0 flex items-center pr-3 cursor-pointer"
                        onclick="togglePassword('password', this)">
                        <i class="fas fa-eye text-gray-400"></i>
                    </span>
                </div>
            </div>


            <!-- Tombol Masuk -->
            <button type="submit"
                class="w-full bg-indigo-600 hover:bg-indigo-700 text-white py-2 rounded-lg font-semibold transition duration-200">
                <i class="fas fa-sign-in-alt mr-2"></i> Masuk
            </button>
        </form>

        <!-- Divider -->
        <div class="flex items-center my-4">
            <div class="flex-grow border-t"></div>
            <span class="mx-2 text-gray-400 text-sm">atau</span>
            <div class="flex-grow border-t"></div>
        </div>

        <!-- Tombol Login Google -->
        <a href="{{ route('google.login') }}"
            class="w-full flex items-center justify-center gap-2 border border-gray-300 py-2 rounded-lg hover:bg-gray-50 transition font-semibold text-gray-700">
            <i class="fab fa-google text-red-500"></i>
            Masuk dengan Google
        </a>


        <!-- Link Tambahan -->
        <div class="mt-4 text-sm">
            <a href="{{ route('register') }}" class="text-indigo-500 hover:underline">Belum punya akun? Register</a>
        </div>
    </div>

    <!-- SweetAlert jika error -->
    @if ($errors->any())
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Login Gagal!',
                text: "{{ implode(', ', $errors->all()) }}",
                confirmButtonColor: '#e14b56'
            });
        </script>
    @endif

    <style>
        /* Animasi fadeIn */
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(30px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-fadeIn {
            animation: fadeIn 0.8s ease-in-out;
        }
    </style>
</body>
<script>
    function togglePassword(id, el) {
        const input = document.getElementById(id);
        const icon = el.querySelector('i');
        if (input.type === "password") {
            input.type = "text";
            icon.classList.remove("fa-eye");
            icon.classList.add("fa-eye-slash");
        } else {
            input.type = "password";
            icon.classList.remove("fa-eye-slash");
            icon.classList.add("fa-eye");
        }
    }
</script>

</html>
