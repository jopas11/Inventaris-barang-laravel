<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Verifikasi OTP</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="flex items-center justify-center min-h-screen bg-gradient-to-r from-indigo-500 to-purple-600">

@php
    $register = session('register_data');
@endphp

<div class="bg-white p-8 rounded-xl shadow-xl w-full max-w-md text-center">
    <h2 class="text-2xl font-bold mb-2">Verifikasi OTP</h2>

    @if($register)
        <p class="text-sm text-gray-500 mb-4">
            OTP dikirim ke {{ $register['email'] }}
        </p>
    @endif

    <form method="POST" action="{{ route('verify.otp') }}">
        @csrf
        <input type="text" name="otp"
               class="w-full p-3 border rounded-lg text-center text-xl tracking-widest"
               placeholder="Masukkan 6 digit"
               required>

        <button class="w-full mt-4 bg-indigo-600 text-white py-2 rounded-lg">
            Verifikasi
        </button>
    </form>

    <div class="mt-4 text-sm text-gray-600">
        Waktu tersisa:
        <span id="timer" class="font-bold text-red-500"></span>
    </div>

    <form method="POST" action="{{ route('resend.otp.session') }}">
        @csrf
        <button class="mt-4 text-indigo-600 hover:underline">
            Kirim Ulang OTP
        </button>
    </form>

    @if(session('success'))
        <div class="mt-4 text-green-600 text-sm">
            {{ session('success') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="mt-4 text-red-500 text-sm">
            {{ $errors->first() }}
        </div>
    @endif
</div>

@if($register)
<script>
    let expireTime = {{ \Carbon\Carbon::parse($register['otp_expires_at'])->timestamp * 1000 }};

    let x = setInterval(function() {
        let now = new Date().getTime();
        let distance = expireTime - now;

        if (distance <= 0) {
            clearInterval(x);
            document.getElementById("timer").innerHTML = "Kadaluarsa";
            return;
        }

        let minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
        let seconds = Math.floor((distance % (1000 * 60)) / 1000);

        document.getElementById("timer").innerHTML = minutes + "m " + seconds + "s";
    }, 1000);
</script>
@endif

</body>
</html>
