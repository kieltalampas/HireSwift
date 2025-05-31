<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HIRE WIFI - Login & Register</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        body {
            background: linear-gradient(135deg, #1e293b 0%, #1e40af 50%, #1e293b 100%);
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            position: relative;
            overflow-x: hidden;
        }

        /* Background decorative elements */
        body::before {
            content: '';
            position: absolute;
            bottom: -200px;
            left: -200px;
            width: 400px;
            height: 400px;
            background: rgba(59, 130, 246, 0.2);
            border-radius: 50%;
            filter: blur(80px);
            z-index: 1;
        }

        body::after {
            content: '';
            position: absolute;
            top: -200px;
            right: -200px;
            width: 400px;
            height: 400px;
            background: rgba(59, 130, 246, 0.15);
            border-radius: 50%;
            filter: blur(80px);
            z-index: 1;
        }

        .auth-container {
            position: relative;
            z-index: 10;
            min-height: 100vh;
        }

        .nav-tabs {
            border: none;
            background: rgba(51, 65, 85, 0.3);
            border-radius: 0;
        }

        .nav-tabs .nav-link {
            border: none;
            color: #cbd5e1;
            background: rgba(71, 85, 105, 0.3);
            font-weight: 500;
            padding: 1rem 2rem;
            transition: all 0.3s ease;
        }

        .nav-tabs .nav-link.active {
            background: rgba(51, 65, 85, 0.5);
            color: white;
            border: none;
        }

        .nav-tabs .nav-link:hover {
            color: white;
            border: none;
        }

        .logo {
            color: white;
            font-size: 2rem;
            font-weight: bold;
            letter-spacing: 2px;
            margin-bottom: 2rem;
            text-align: center;
        }

        .logo .brand-highlight {
            background: white;
            color: #1e293b;
            padding: 0 8px;
            margin-left: 8px;
        }

        .form-container {
            background: rgba(51, 65, 85, 0.4);
            backdrop-filter: blur(10px);
            border-radius: 1rem;
            padding: 3rem;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
            border: 1px solid rgba(71, 85, 105, 0.5);
            max-width: 450px;
            margin: 0 auto;
        }

        .form-title {
            color: white;
            font-size: 1.8rem;
            font-weight: 600;
            text-align: center;
            margin-bottom: 2rem;
        }

        .form-label {
            color: #cbd5e1;
            font-size: 0.9rem;
            margin-bottom: 0.5rem;
        }

        .form-control {
            background: rgba(71, 85, 105, 0.5);
            border: 1px solid #475569;
            color: white;
            padding: 0.75rem 1rem;
            border-radius: 0.5rem;
            height: 3rem;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            background: rgba(71, 85, 105, 0.7);
            border-color: #3b82f6;
            box-shadow: 0 0 0 0.2rem rgba(59, 130, 246, 0.2);
            color: white;
        }

        .form-control::placeholder {
            color: #94a3b8;
        }

        .btn-primary {
            background: #3b82f6;
            border: none;
            padding: 0.75rem 2rem;
            font-weight: 500;
            border-radius: 0.5rem;
            width: 100%;
            height: 3rem;
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            background: #2563eb;
            transform: translateY(-1px);
        }

        .auth-links {
            text-align: center;
            margin-top: 1.5rem;
        }

        .auth-links p {
            color: #94a3b8;
            font-size: 0.9rem;
            margin-bottom: 0.5rem;
        }

        .auth-links a {
            color: #60a5fa;
            text-decoration: none;
            font-size: 0.9rem;
            transition: color 0.3s ease;
        }

        .auth-links a:hover {
            color: #93c5fd;
        }

        .tab-content {
            padding-top: 2rem;
        }

        .alert {
            margin-bottom: 1rem;
            border-radius: 0.5rem;
        }

        @media (max-width: 768px) {
            .form-container {
                margin: 1rem;
                padding: 2rem;
            }
            
            .nav-tabs .nav-link {
                padding: 0.75rem 1rem;
                font-size: 0.9rem;
            }
        }
    </style>
</head>
<body>
    <div class="auth-container">
        <!-- Navigation Tabs -->
        <ul class="nav nav-tabs" id="authTabs" role="tablist">
            <li class="nav-item flex-fill" role="presentation">
                <button class="nav-link active w-100" id="login-tab" data-bs-toggle="tab" data-bs-target="#login" type="button" role="tab">
                    Login
                </button>
            </li>
            <li class="nav-item flex-fill" role="presentation">
                <button class="nav-link w-100" id="register-tab" data-bs-toggle="tab" data-bs-target="#register" type="button" role="tab">
                    Register
                </button>
            </li>
        </ul>

        <!-- Tab Content -->
        <div class="tab-content" id="authTabContent">
            <!-- Login Tab -->
            <div class="tab-pane fade show active" id="login" role="tabpanel">
                <div class="container-fluid d-flex align-items-center justify-content-center" style="min-height: calc(100vh - 60px);">
                    <div class="form-container">
                        <!-- Logo -->
                        <div class="logo">
                            <i class="fas fa-paper-plane me-2"></i>
                            HIRE<span class="brand-highlight">WIFI</span>
                        </div>

                        <h1 class="form-title">Login</h1>
                        
                        <!-- Display any error messages -->
                        <?php
                        if (isset($_GET['error'])) {
                            echo '<div class="alert alert-danger">' . htmlspecialchars($_GET['error']) . '</div>';
                        }
                        if (isset($_GET['success'])) {
                            echo '<div class="alert alert-success">' . htmlspecialchars($_GET['success']) . '</div>';
                        }
                        ?>
                        
                        <form method="post" action="register.php">
                            <div class="mb-3">
                                <label for="loginEmail" class="form-label">E-mail Address</label>
                                <input type="email" class="form-control" id="loginEmail" name="email" placeholder="johndoe@live.forda.net" required>
                            </div>
                            
                            <div class="mb-4">
                                <label for="loginPassword" class="form-label">Password</label>
                                <input type="password" class="form-control" id="loginPassword" name="password" placeholder="Password" required>
                            </div>
                            
                            <button type="submit" class="btn btn-primary" name="signIn">LOGIN</button>
                            
                            <div class="auth-links">
                                <p>No account? <a href="#" onclick="switchToRegister()">Register Here</a></p>
                                <a href="#">Forgot Password?</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Register Tab -->
            <div class="tab-pane fade" id="register" role="tabpanel">
                <div class="container-fluid d-flex align-items-center justify-content-center" style="min-height: calc(100vh - 60px);">
                    <div class="form-container">
                        <!-- Logo -->
                        <div class="logo">
                            <i class="fas fa-paper-plane me-2"></i>
                            HIRE<span class="brand-highlight">WIFI</span>
                        </div>

                        <h1 class="form-title">Register</h1>
                        
                        <!-- Display any error messages -->
                        <?php
                        if (isset($_GET['error'])) {
                            echo '<div class="alert alert-danger">' . htmlspecialchars($_GET['error']) . '</div>';
                        }
                        if (isset($_GET['success'])) {
                            echo '<div class="alert alert-success">' . htmlspecialchars($_GET['success']) . '</div>';
                        }
                        ?>
                        
                        <form method="post" action="register.php">
                            <div class="mb-3">
                                <label for="companyName" class="form-label">Company Name</label>
                                <input type="text" class="form-control" id="companyName" name="companyName" placeholder="Company name" required>
                            </div>
                            
                            <div class="mb-3">
                                <label for="registerEmail" class="form-label">E-mail Address</label>
                                <input type="email" class="form-control" id="registerEmail" name="email" placeholder="johndoe@live.forda.net" required>
                            </div>
                            
                            <div class="mb-4">
                                <label for="registerPassword" class="form-label">Password</label>
                                <input type="password" class="form-control" id="registerPassword" name="password" placeholder="Password" required>
                            </div>
                            
                            <button type="submit" class="btn btn-primary" name="signUp">Register</button>
                            
                            <div class="auth-links">
                                <p>Already have an account? <a href="#" onclick="switchToLogin()">Sign In</a></p>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function switchToRegister() {
            const registerTab = new bootstrap.Tab(document.getElementById('register-tab'));
            registerTab.show();
        }

        function switchToLogin() {
            const loginTab = new bootstrap.Tab(document.getElementById('login-tab'));
            loginTab.show();
        }
    </script>
</body>
</html>