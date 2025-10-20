<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Dispo Surat</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    
    <!-- Konfigurasi Tailwind untuk Dark Mode -->
    <script>
        tailwind.config = {
            darkMode: 'class',
        }
    </script>
    
    <style>
        /* Base Styles */
        .login-page { 
            font-family: 'Inter', sans-serif;
            background-color: #f1f1f1ff;
        }
        .dark .login-page {
            background-color: #1a202c;
        }

        /* Header Animation */
        .header-gradient { 
            font-weight: 600; 
            font-size: 14px; 
            background: linear-gradient(90deg, #aa3600ff, #b66700ff, #ffd000ff, #b66700ff, #aa3600ff); 
            background-size: 300% auto; 
            -webkit-background-clip: text; 
            -webkit-text-fill-color: transparent; 
            background-clip: text; 
            animation: shine 3s linear infinite; 
        }
        @keyframes shine { 
            0% { background-position: 300% center; } 
            100% { background-position: 0% center; } 
        }

        /* Form Elements */
        .form-input { 
            border: 1px solid #d8d8d8ff; 
            border-radius: 0.5rem; 
            padding: 0.75rem 1rem; 
            color: #374151; 
            width: 100%; 
            transition: all 0.3s; 
            background-color: white;
        }
        .dark .form-input {
            background-color: #2d3748;
            border-color: #4a5568;
            color: #e2e8f0;
        }
        .form-input:focus { 
            outline: 2px solid #9f0fffff; 
            box-shadow: 0 0 0 3px rgba(122, 15, 255, 0.1); 
        }
        .dark .form-input:focus {
            box-shadow: 0 0 0 3px rgba(122, 15, 255, 0.2);
        }
        .password-container { position: relative; }
        .toggle-password { 
            position: absolute; 
            right: 12px; 
            top: 50%; 
            transform: translateY(-50%); 
            background: none; 
            border: none; 
            color: #6b7280; 
            cursor: pointer; 
            padding: 4px; 
        }
        .dark .toggle-password {
            color: #a0aec0;
        }

        /* Alert & Message Styles */
        .alert-warning { 
            background-color: #fef3cd; 
            color: #856404; 
            border-radius: 0.375rem; 
            padding: 0.75rem; 
            margin-bottom: 1rem; 
            border-left: 4px solid #ffc107; 
        }
        .dark .alert-warning {
            background-color: #744210;
            color: #fef3cd;
            border-left-color: #d97706;
        }
        .alert-danger { 
            background-color: #ff0000ff; 
            color: #ffffffff; 
            border-radius: 0.375rem; 
            padding: 0.75rem; 
            margin-bottom: 1rem;  
        }
        .error-message { 
            background-color: #fee2e2; 
            color: #b91c1c; 
            border-radius: 0.375rem; 
            padding: 0.75rem; 
            margin-bottom: 1rem; 
            border-left: 4px solid #ef4444; 
        }
        .dark .error-message {
            background-color: #7f1d1d;
            color: #fecaca;
            border-left-color: #dc2626;
        }

        /* Button Styles */
        .btn-login { 
            background-image: linear-gradient(135deg, rgba(136, 252, 82, 0.83), rgba(58, 179, 88, 1)); 
            transition: all 0.3s ease; 
            position: relative; 
            overflow: hidden; 
        }
        .btn-login:hover { 
            box-shadow: 0 6px 20px rgba(0, 250, 33, 0.55); 
        }

        /* Layout & Container */
        .login-container { 
            box-shadow: 0 4px 12px rgba(36, 36, 36, 0.06); 
        }
        .dark .login-container {
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            background-color: #2d3748;
        }
        .logo-section { 
            transition: all 0.3s ease; 
        }
        .logo-section:hover { 
            box-shadow: 0 6px 20px rgba(0, 255, 64, 0.2); 
            background-color: #cafed5ff; 
        }
        .dark .logo-section {
            background-color: #2d3748;
        }
        .dark .logo-section:hover {
            background-color: #4a5568;
        }

        /* Ripple Effect */
        .btn-login .ripple { 
            position: absolute; 
            border-radius: 50%; 
            transform: scale(0); 
            animation: ripple-animation 0.6s linear; 
            background-color: rgba(255, 255, 255, 0.3); 
            pointer-events: none; 
        }
        @keyframes ripple-animation { 
            to { transform: scale(4); opacity: 0; } 
        }

        /* Checkbox Styles */
        .checkbox1 {
            --background: #fff;
            --border: #D1D6EE;
            --border-hover: #BBC1E1;
            --border-active: #1E2235;
            --tick: #fff;
            position: relative;
        }
        .dark .checkbox1 {
            --background: #2d3748;
            --border: #4a5568;
            --border-hover: #718096;
            --border-active: #38a169;
        }
        .checkbox1 input,
        .checkbox1 svg {
            width: 21px;
            height: 21px;
            display: block;
        }
        .checkbox1 input {
            -webkit-appearance: none;
            -moz-appearance: none;
            position: relative;
            outline: none;
            background: var(--background);
            border: none;
            margin: 0;
            padding: 0;
            cursor: pointer;
            border-radius: 4px;
            transition: box-shadow .3s;
            box-shadow: inset 0 0 0 var(--s, 1px) var(--b, var(--border));
        }
        .checkbox1 input:hover {
            --s: 2px;
            --b: var(--border-hover);
        }
        .checkbox1 input:checked {
            --b: var(--border-active);
        }
        .checkbox1 svg {
            pointer-events: none;
            fill: none;
            stroke-width: 2px;
            stroke-linecap: round;
            stroke-linejoin: round;
            stroke: var(--stroke, var(--border-active));
            position: absolute;
            top: 0;
            left: 0;
            width: 21px;
            height: 21px;
            transform: scale(var(--scale, 1)) translateZ(0);
        }
        .checkbox1.path input:checked {
            --s: 2px;
            transition-delay: .4s;
        }
        .checkbox1.path input:checked + svg {
            --a: 16.1 86.12;
            --o: 102.22;
        }
        .checkbox1.path svg {
            stroke-dasharray: var(--a, 86.12);
            stroke-dashoffset: var(--o, 86.12);
            transition: stroke-dasharray .6s, stroke-dashoffset .6s;
        }
        .checkbox1.bounce {
            --stroke: var(--tick);
        }
        .checkbox1.bounce input:checked {
            --s: 11px;
        }
        .checkbox1.bounce input:checked + svg {
            animation: bounce .4s linear forwards .2s;
        }
        .checkbox1.bounce svg {
            --scale: 0;
        }

        @keyframes bounce {
            50% {
                transform: scale(1.2);
            }
            75% {
                transform: scale(.9);
            }
            100% {
                transform: scale(1);
            }
        }

        /* Dark mode toggle button */
        .theme-toggle {
            position: fixed;
            top: 20px;
            right: 20px;
            background: #e2e8f0;
            border: none;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
            z-index: 1000;
        }
        .dark .theme-toggle {
            background: #4a5568;
            color: #e2e8f0;
        }
    </style>
</head>
<body class="login-page min-h-screen items-center justify-center p-6 sm:p-10 bg-gray-50 dark:bg-gray-900">
    <!-- Dark Mode Toggle Button -->
    <button id="theme-toggle" class="theme-toggle">
        <i class="fas fa-moon" id="theme-icon"></i>
    </button>

    <main class="max-w-7xl mx-auto">
        <section class="bg-white dark:bg-gray-800 rounded-xl shadow-sm flex flex-col md:flex-row overflow-hidden login-container">
            <!-- Bagian Gambar/Logo -->
            <div class="logo-section md:w-1/2 bg-white dark:bg-gray-800 flex justify-center items-center p-12">
                <img
                    src="{{ asset('images/logos/dispo.png') }}"
                    alt="RS Petrokimia Gresik Logo"
                    class="max-w-full max-h-[240px]"
                    width="300"
                />
            </div>
            
            <!-- Bagian Form Login -->
            <div class="md:w-1/2 p-8 flex flex-col justify-center space-y-6">
                <div class="alert-warning dark:bg-yellow-900 dark:text-yellow-200">
                    <b>Surat Management System</b><br>Silakan masuk ke akun Anda<br>
                </div>
                
                <form class="mt-8 space-y-6" action="{{ route('login') }}" method="POST" id="login-form">
                    @csrf
                    <div class="rounded-md shadow-sm space-y-4">
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Alamat Email
                            </label>
                            <input id="email" name="email" type="email" autocomplete="email" required 
                                class="form-input dark:bg-gray-700 dark:text-white dark:border-gray-600 rounded-md w-full"
                                placeholder="Alamat Email" value="{{ old('email') }}">
                        </div>

                        <div>
                            <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Password
                            </label>
                            <div class="relative">
                                <input id="password" name="password" type="password" autocomplete="current-password" required 
                                    class="form-input dark:bg-gray-700 dark:text-white dark:border-gray-600 rounded-md w-full pr-10" 
                                    placeholder="Password">
                                <button type="button" 
                                    class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300 focus:outline-none" 
                                    onclick="togglePassword()">
                                    <i class="fas fa-eye-slash" id="toggleIcon"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    @if ($errors->any())
                    <div class="error-message dark:bg-red-900 dark:text-red-200">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <i class="fas fa-exclamation-circle text-red-400"></i>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-red-800 dark:text-red-200">
                                    Terjadi kesalahan
                                </h3>
                                <div class="mt-2 text-sm text-red-700 dark:text-red-300">
                                    <ul class="list-disc list-inside">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <input id="remember_me" name="remember" type="checkbox" 
                                   class="checkbox1 path">
                            <label for="remember_me" class="ml-2 block text-sm text-gray-900 dark:text-gray-300">
                                Ingat saya
                            </label>
                        </div>

                        @if (Route::has('password.request'))
                        <div class="text-sm">
                            <a href="{{ route('password.request') }}" class="font-medium text-blue-600 hover:text-blue-500 dark:text-blue-400 dark:hover:text-blue-300">
                                Lupa password?
                            </a>
                        </div>
                        @endif
                    </div>

                    <div>
                        <button type="submit" name="login" 
                                class="btn-login text-white font-semibold rounded-md py-2 px-5 w-full flex items-center justify-center gap-2 shadow-md">
                            <i class="fas fa-sign-in-alt"></i>
                            Masuk
                        </button>
                    </div>

                    <div class="text-center mt-4 text-sm text-gray-600 dark:text-gray-400">
                        <p class="text-sm">
                            <b>AKUN DEMO UNTUK TESTING:</b><br>
                            <small class="text-xs">
                                Unit 1: unit1@example.com / password<br>
                                Pengadaan: pengadaan@example.com / password<br>
                                Direktur: direktur@example.com / password
                            </small>
                        </p>
                    </div>
                </form>
            </div>
        </section>
    </main>
    
    <footer class="text-center mt-6 text-gray-500 dark:text-gray-400 text-sm">
        &copy; <span id="year"></span> Dispo Surat - RS Petrokimia Gresik
    </footer>

    <script>
        // Fungsi untuk toggle password visibility
        function togglePassword() {
            const passwordInput = document.getElementById("password");
            const toggleIcon = document.getElementById("toggleIcon");

            if (passwordInput.type === "password") {
                passwordInput.type = "text";
                toggleIcon.classList.remove("fa-eye-slash");
                toggleIcon.classList.add("fa-eye");
            } else {
                passwordInput.type = "password";
                toggleIcon.classList.remove("fa-eye");
                toggleIcon.classList.add("fa-eye-slash");
            }
        }

        // Fungsi untuk toggle dark mode
        function toggleDarkMode() {
            const html = document.documentElement;
            const themeIcon = document.getElementById('theme-icon');
            
            if (html.classList.contains('dark')) {
                html.classList.remove('dark');
                localStorage.setItem('theme', 'light');
                themeIcon.classList.remove('fa-sun');
                themeIcon.classList.add('fa-moon');
            } else {
                html.classList.add('dark');
                localStorage.setItem('theme', 'dark');
                themeIcon.classList.remove('fa-moon');
                themeIcon.classList.add('fa-sun');
            }
        }

        // Inisialisasi dark mode berdasarkan preferensi pengguna
        function initDarkMode() {
            const savedTheme = localStorage.getItem('theme');
            const systemPrefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
            
            if (savedTheme === 'dark' || (!savedTheme && systemPrefersDark)) {
                document.documentElement.classList.add('dark');
                document.getElementById('theme-icon').classList.remove('fa-moon');
                document.getElementById('theme-icon').classList.add('fa-sun');
            }
        }

        // Ripple effect pada tombol
        document.querySelectorAll('.btn-login').forEach(button => {
            button.addEventListener('click', function (e) {
                // Hapus ripple lama (jika ada)
                const oldRipple = this.querySelector('.ripple');
                if (oldRipple) oldRipple.remove();

                const ripple = document.createElement('span');
                ripple.classList.add('ripple');
                this.appendChild(ripple);

                const rect = this.getBoundingClientRect();
                const size = Math.max(rect.width, rect.height);
                ripple.style.width = ripple.style.height = `${size}px`;

                const x = e.clientX - rect.left - size / 2;
                const y = e.clientY - rect.top - size / 2;

                ripple.style.left = `${x}px`;
                ripple.style.top = `${y}px`;

                // Hilangkan setelah animasi selesai
                setTimeout(() => ripple.remove(), 600);
            });
        });

        // Validasi form dengan SweetAlert
        document.addEventListener('DOMContentLoaded', function() {
            // Inisialisasi dark mode
            initDarkMode();
            
            // Set tahun di footer
            document.getElementById('year').textContent = new Date().getFullYear();
            
            // Fokus ke input email
            document.getElementById('email').focus();
            
            // Event listener untuk form submission
            const form = document.getElementById('login-form');
            form.addEventListener('submit', function(e) {
                const email = document.getElementById('email');
                const password = document.getElementById('password');
                
                // Validasi email
                if (!email.value.trim()) {
                    e.preventDefault();
                    Swal.fire({
                        icon: 'error',
                        title: 'Email tidak boleh kosong',
                        text: 'Silakan masukkan alamat email Anda',
                        confirmButtonColor: '#3085d6',
                        background: document.documentElement.classList.contains('dark') ? '#1a202c' : '#fff',
                        color: document.documentElement.classList.contains('dark') ? '#e2e8f0' : '#333',
                    });
                    email.focus();
                    return false;
                }
                
                // Validasi format email
                const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                if (!emailRegex.test(email.value)) {
                    e.preventDefault();
                    Swal.fire({
                        icon: 'error',
                        title: 'Format email tidak valid',
                        text: 'Silakan masukkan alamat email yang valid',
                        confirmButtonColor: '#3085d6',
                        background: document.documentElement.classList.contains('dark') ? '#1a202c' : '#fff',
                        color: document.documentElement.classList.contains('dark') ? '#e2e8f0' : '#333',
                    });
                    email.focus();
                    return false;
                }
                
                // Validasi password
                if (!password.value.trim()) {
                    e.preventDefault();
                    Swal.fire({
                        icon: 'error',
                        title: 'Password tidak boleh kosong',
                        text: 'Silakan masukkan password Anda',
                        confirmButtonColor: '#3085d6',
                        background: document.documentElement.classList.contains('dark') ? '#1a202c' : '#fff',
                        color: document.documentElement.classList.contains('dark') ? '#e2e8f0' : '#333',
                    });
                    password.focus();
                    return false;
                }
                
                // Jika semua validasi berhasil, tampilkan loading
                Swal.fire({
                    title: 'Memproses login...',
                    text: 'Mohon tunggu sebentar',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    },
                    background: document.documentElement.classList.contains('dark') ? '#1a202c' : '#fff',
                    color: document.documentElement.classList.contains('dark') ? '#e2e8f0' : '#333',
                });
            });
        });

        // Event listener untuk tombol dark mode toggle
        document.getElementById('theme-toggle').addEventListener('click', toggleDarkMode);
    </script>
</body>
</html>