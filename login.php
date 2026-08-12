<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Location Tracker</title>
    <link rel="stylesheet" href="/assets/css/style.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .login-container {
            background: white;
            border-radius: 10px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
            width: 100%;
            max-width: 400px;
            padding: 40px;
        }

        .logo {
            text-align: center;
            margin-bottom: 30px;
        }

        .logo h1 {
            color: #667eea;
            font-size: 28px;
            margin-bottom: 5px;
        }

        .logo p {
            color: #999;
            font-size: 14px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-bottom: 8px;
            color: #333;
            font-weight: 500;
        }

        input[type="text"],
        input[type="password"],
        input[type="email"] {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 14px;
            transition: border-color 0.3s;
        }

        input[type="text"]:focus,
        input[type="password"]:focus,
        input[type="email"]:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        .form-group.phone input {
            padding-left: 40px;
        }

        .phone-prefix {
            position: absolute;
            margin-top: 42px;
            margin-left: 12px;
            color: #999;
            font-size: 14px;
        }

        button {
            width: 100%;
            padding: 12px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: transform 0.2s;
        }

        button:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.3);
        }

        button:active {
            transform: translateY(0);
        }

        .error {
            color: #dc3545;
            font-size: 14px;
            margin-top: 5px;
            display: none;
        }

        .error.show {
            display: block;
        }

        .form-footer {
            margin-top: 20px;
            text-align: center;
            color: #999;
            font-size: 14px;
        }

        .form-footer a {
            color: #667eea;
            text-decoration: none;
        }

        .form-footer a:hover {
            text-decoration: underline;
        }

        .forgot-password {
            text-align: right;
            margin-top: -10px;
            margin-bottom: 20px;
        }

        .forgot-password a {
            color: #667eea;
            text-decoration: none;
            font-size: 12px;
        }

        .forgot-password a:hover {
            text-decoration: underline;
        }

        .loading {
            display: none;
            text-align: center;
        }

        .spinner {
            border: 3px solid rgba(102, 126, 234, 0.1);
            border-top: 3px solid #667eea;
            border-radius: 50%;
            width: 20px;
            height: 20px;
            animation: spin 1s linear infinite;
            margin: 0 auto;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        .tabs {
            display: flex;
            gap: 10px;
            margin-bottom: 25px;
            border-bottom: 2px solid #eee;
        }

        .tabs button {
            flex: 1;
            padding: 10px;
            background: none;
            border: none;
            color: #999;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            border-bottom: 3px solid transparent;
            margin-bottom: -2px;
            transition: all 0.3s;
        }

        .tabs button.active {
            color: #667eea;
            border-bottom-color: #667eea;
        }

        .tab-content {
            display: none;
        }

        .tab-content.active {
            display: block;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="logo">
            <h1>📍 Location Tracker</h1>
            <p>Sistem Pelacakan Lokasi Real-Time</p>
        </div>

        <div class="tabs">
            <button class="active" onclick="switchTab('login')">Login</button>
            <button onclick="switchTab('register')">Daftar</button>
        </div>

        <!-- Login Form -->
        <form id="loginForm" class="tab-content active" method="POST" action="/process/login.php">
            <div class="form-group phone">
                <label for="phone">Nomor Ponsel</label>
                <span class="phone-prefix">+62</span>
                <input type="text" id="phone" name="phone" placeholder="812345678" required>
                <div class="error" id="phoneError"></div>
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" placeholder="Masukkan password" required>
                <div class="error" id="passwordError"></div>
            </div>

            <div class="forgot-password">
                <a href="/forgot-password.php">Lupa password?</a>
            </div>

            <button type="submit" id="loginBtn">
                <span>Login</span>
                <div class="loading" id="loginLoading">
                    <div class="spinner"></div>
                </div>
            </button>

            <div class="error show" id="loginError"></div>
        </form>

        <!-- Register Form -->
        <form id="registerForm" class="tab-content" method="POST" action="/process/register.php">
            <div class="form-group">
                <label for="regName">Nama Lengkap</label>
                <input type="text" id="regName" name="name" placeholder="Nama Anda" required>
                <div class="error" id="nameError"></div>
            </div>

            <div class="form-group phone">
                <label for="regPhone">Nomor Ponsel</label>
                <span class="phone-prefix">+62</span>
                <input type="text" id="regPhone" name="phone" placeholder="812345678" required>
                <div class="error" id="regPhoneError"></div>
            </div>

            <div class="form-group">
                <label for="regEmail">Email</label>
                <input type="email" id="regEmail" name="email" placeholder="email@example.com">
                <div class="error" id="emailError"></div>
            </div>

            <div class="form-group">
                <label for="regPassword">Password</label>
                <input type="password" id="regPassword" name="password" placeholder="Minimal 6 karakter" required>
                <div class="error" id="regPasswordError"></div>
            </div>

            <div class="form-group">
                <label for="regConfirmPassword">Konfirmasi Password</label>
                <input type="password" id="regConfirmPassword" name="confirm_password" placeholder="Ulangi password" required>
                <div class="error" id="confirmPasswordError"></div>
            </div>

            <button type="submit" id="registerBtn">
                <span>Daftar Sekarang</span>
                <div class="loading" id="registerLoading">
                    <div class="spinner"></div>
                </div>
            </button>

            <div class="error show" id="registerError"></div>
        </form>

        <div class="form-footer">
            Untuk informasi lebih lanjut, hubungi: <a href="mailto:support@example.com">support@example.com</a>
        </div>
    </div>

    <script>
        function switchTab(tab) {
            // Hide all tabs
            document.querySelectorAll('.tab-content').forEach(el => {
                el.classList.remove('active');
            });
            document.querySelectorAll('.tabs button').forEach(btn => {
                btn.classList.remove('active');
            });

            // Show selected tab
            document.getElementById(tab + 'Form').classList.add('active');
            event.target.classList.add('active');
        }

        // Login form handler
        document.getElementById('loginForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const phone = document.getElementById('phone').value;
            const password = document.getElementById('password').value;
            const loginBtn = document.getElementById('loginBtn');
            const loginLoading = document.getElementById('loginLoading');
            const loginError = document.getElementById('loginError');

            // Validate
            if (!phone || !password) {
                loginError.textContent = 'Harap isi semua field';
                loginError.classList.add('show');
                return;
            }

            // Show loading
            loginBtn.disabled = true;
            loginLoading.style.display = 'block';
            loginError.classList.remove('show');

            try {
                const response = await fetch('/process/login.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        phone: '+62' + phone,
                        password: password
                    })
                });

                const data = await response.json();

                if (data.success) {
                    window.location.href = '/dashboard/index.php';
                } else {
                    loginError.textContent = data.message || 'Login gagal';
                    loginError.classList.add('show');
                }
            } catch (error) {
                loginError.textContent = 'Terjadi kesalahan: ' + error.message;
                loginError.classList.add('show');
            } finally {
                loginBtn.disabled = false;
                loginLoading.style.display = 'none';
            }
        });

        // Register form handler
        document.getElementById('registerForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const name = document.getElementById('regName').value;
            const phone = document.getElementById('regPhone').value;
            const email = document.getElementById('regEmail').value;
            const password = document.getElementById('regPassword').value;
            const confirmPassword = document.getElementById('regConfirmPassword').value;

            // Validate
            if (!name || !phone || !password || !confirmPassword) {
                document.getElementById('registerError').textContent = 'Harap isi semua field required';
                document.getElementById('registerError').classList.add('show');
                return;
            }

            if (password !== confirmPassword) {
                document.getElementById('confirmPasswordError').textContent = 'Password tidak cocok';
                document.getElementById('confirmPasswordError').classList.add('show');
                return;
            }

            if (password.length < 6) {
                document.getElementById('regPasswordError').textContent = 'Password minimal 6 karakter';
                document.getElementById('regPasswordError').classList.add('show');
                return;
            }

            // Submit
            this.submit();
        });
    </script>
</body>
</html>
