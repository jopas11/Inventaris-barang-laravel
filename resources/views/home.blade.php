<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>

    <!-- Font Awesome CDN -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background: linear-gradient(to right, #6366f1, #8b5cf6);
            /* indigo-500 to purple-600 */
            position: relative;
            overflow: hidden;
        }


        /* body::before {
            content: "";
            position: absolute;
            top: 0; left: 0; right: 0; bottom: 0;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 0;
        } */

        .home-container {
            position: relative;
            z-index: 1;
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            padding: 2.5rem 2rem;
            border-radius: 16px;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.3);
            max-width: 500px;
            text-align: center;
            color: #ffffff;
            animation: fadeIn 1s ease-in-out;
        }

        .logo {
            width: 80px;
            margin-bottom: 1rem;
        }

        .home-container i.main-icon {
            font-size: 3rem;
            margin-bottom: 1rem;
            color: #ffffff;
        }

        h1 {
            margin-bottom: 1rem;
            font-size: 2rem;
        }

        p {
            margin-bottom: 2rem;
            font-size: 1.1rem;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 12px 25px;
            margin: 0 10px;
            background: #ffffff;
            color: #2F80ED;
            text-decoration: none;
            font-weight: bold;
            border-radius: 30px;
            transition: all 0.3s ease;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }

        .btn i {
            font-size: 1rem;
        }

        .btn:hover {
            background: #f0f0f0;
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.2);
        }

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

        @media (max-width: 600px) {
            .home-container {
                width: 90%;
                padding: 2rem 1.5rem;
            }

            h1 {
                font-size: 1.5rem;
            }

            .logo {
                width: 60px;
            }
        }
    </style>
</head>

<body>
    <div class="home-container">
        <!-- Tambahkan logo di sini -->
        <img src="/images/13.png" alt="Logo" class="logo">

        <!-- Ganti dengan ikon yang valid -->
        <h1>Selamat Datang!</h1>
        <p><i class="fas fa-info-circle"></i> Silakan login atau daftar untuk melanjutkan.</p>

        <a href="/login" class="btn"><i class="fas fa-sign-in-alt"></i> Login</a>
        <a href="/register" class="btn"><i class="fas fa-user-plus"></i> Daftar</a>
    </div>
</body>

</html>
