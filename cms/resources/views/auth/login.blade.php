<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Sign in | Kwikster CMS</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        
        .login-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
            overflow: hidden;
            width: 100%;
            max-width: 1000px;
        }
        
        .login-form-section {
            padding: 40px;
        }
        
        .video-section {
            background: linear-gradient(rgba(0,0,0,0.7), rgba(0,0,0,0.7)), 
                        url('https://images.unsplash.com/photo-1598899134739-24c46f58b8c0?auto=format&fit=crop&w=800');
            background-size: cover;
            background-position: center;
            color: white;
            padding: 40px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }
        
        .video-card {
            background: rgba(255,255,255,0.1);
            backdrop-filter: blur(10px);
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 20px;
            transition: transform 0.3s;
            cursor: pointer;
        }
        
        .video-card:hover {
            transform: translateY(-5px);
        }
        
        .video-title {
            font-size: 1.2rem;
            font-weight: 600;
            margin-bottom: 5px;
        }
        
        .video-meta {
            font-size: 0.9rem;
            opacity: 0.8;
        }
        
        .play-btn {
            width: 40px;
            height: 40px;
            background: rgba(255,255,255,0.2);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 15px;
        }
        
        .form-control {
            border-radius: 8px;
            padding: 12px 15px;
            border: 1px solid #ddd;
        }
        
        .form-control:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.25rem rgba(102, 126, 234, 0.25);
        }
        
        .btn-login {
            background: linear-gradient(to right, #667eea, #764ba2);
            border: none;
            color: white;
            padding: 12px;
            border-radius: 8px;
            font-weight: 600;
            width: 100%;
            transition: all 0.3s;
        }
        
        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(102, 126, 234, 0.3);
        }
        
        .register-link {
            color: #667eea;
            text-decoration: none;
            font-weight: 500;
        }
        
        .register-link:hover {
            text-decoration: underline;
        }
        
        .user-type-selector {
            display: flex;
            gap: 10px;
            margin-bottom: 25px;
        }
        
        .user-type-btn {
            flex: 1;
            padding: 10px;
            border: 2px solid #e5e7eb;
            border-radius: 8px;
            background: white;
            cursor: pointer;
            transition: all 0.3s;
            text-align: center;
            font-weight: 500;
        }
        
        .user-type-btn.active {
            border-color: #667eea;
            background: #eef2ff;
            color: #667eea;
        }
        
        .user-type-btn:hover:not(.active) {
            background: #f9fafb;
        }
        
        .remember-forgot {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
        }
        
        .form-check-input:checked {
            background-color: #667eea;
            border-color: #667eea;
        }
        
        .forgot-password {
            color: #667eea;
            text-decoration: none;
            font-size: 0.9rem;
        }
        
        .forgot-password:hover {
            text-decoration: underline;
        }
        
        .password-toggle {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: #6b7280;
        }
        
        .password-wrapper {
            position: relative;
        }
        
        @media (max-width: 768px) {
            .login-card {
                flex-direction: column;
            }
            
            .video-section {
                order: -1;
                min-height: 300px;
            }
        }
    </style>
</head>
<body>
    <div class="login-card d-flex">
        <!-- Left Side - Login Form -->
        <div class="login-form-section col-md-6">
            <div class="text-center mb-4">
                <h2 class="fw-bold mb-2">Sign in</h2>
                <p class="text-muted">Enter your credentials to log in</p>
            </div>

            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if (session('status'))
                <div class="alert alert-success">
                    {{ session('status') }}
                </div>
            @endif

            <!-- User Type Selector -->
            <div class="user-type-selector">
                <button type="button" class="user-type-btn active" data-type="admin">
                    <i class="fas fa-user-shield me-2"></i>Admin
                </button>
                <button type="button" class="user-type-btn" data-type="employee">
                    <i class="fas fa-user-tie me-2"></i>Employee
                </button>
                <!-- <button type="button" class="user-type-btn" data-type="client">
                    <i class="fas fa-user me-2"></i>Client
                </button> -->
            </div>

            <form method="POST" action="{{ route('login') }}" id="loginForm">
                @csrf
                
                <!-- Hidden field for user type -->
                <input type="hidden" name="user_type" id="userType" value="admin">

              <div class="mb-3">
    <label class="form-label">Email Address *</label> <!-- Change label -->
    <div class="input-group">
        <span class="input-group-text bg-light border-end-0">
            <i class="fas fa-envelope text-muted"></i> <!-- Change icon -->
        </span>
        <!-- Change name to "email" and type to "email" -->
        <input type="email" name="email" class="form-control border-start-0" 
               value="{{ old('email', 'admin@kwikster.com') }}" required 
               placeholder="Enter your email address">
    </div>
</div>

                <div class="mb-3">
                    <label class="form-label">Password *</label>
                    <div class="password-wrapper">
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0">
                                <i class="fas fa-lock text-muted"></i>
                            </span>
                            <input type="password" name="password" id="password" 
                                   class="form-control border-start-0 pe-5" 
                                   required placeholder="Enter your password">
                        </div>
                        <span class="password-toggle" id="togglePassword">
                            <i class="far fa-eye"></i>
                        </span>
                    </div>
                </div>

                <div class="remember-forgot">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="remember" id="remember">
                        <label class="form-check-label" for="remember">
                            Remember me
                        </label>
                    </div>
                    <a href="{{ route('password.request') }}" class="forgot-password">
                        Forgot Password?
                    </a>
                </div>

                <button type="submit" class="btn-login mb-3">
                    Login
                </button>

                <div class="text-center mt-3">
                    <p class="text-muted">
                        Don't have an account? 
                        <a href="{{ route('register') }}" class="register-link">Sign up</a>
                    </p>
                </div>
            </form>
        </div>

        <!-- Right Side - Trending Videos -->
        <div class="video-section col-md-6">
            <h3 class="mb-4">Trending Videos</h3>
            
            <div class="video-card">
                <div class="play-btn">
                    <i class="fas fa-play"></i>
                </div>
                <div class="video-title">The Muppet Show</div>
                <div class="video-meta">Comedy • 45 min • Trending Now</div>
            </div>
            
            <div class="video-card">
                <div class="play-btn">
                    <i class="fas fa-play"></i>
                </div>
                <div class="video-title">Nature's Wonders</div>
                <div class="video-meta">Documentary • 32 min</div>
            </div>
            
            <div class="video-card">
                <div class="play-btn">
                    <i class="fas fa-play"></i>
                </div>
                <div class="video-title">Tech Today</div>
                <div class="video-meta">Technology • 28 min</div>
            </div>
            
            <div class="video-card">
                <div class="play-btn">
                    <i class="fas fa-play"></i>
                </div>
                <div class="video-title">Cooking Master</div>
                <div class="video-meta">Culinary • 40 min</div>
            </div>
            
            <div class="mt-4">
                <h5>Kwikster CMS Stats</h5>
                <div class="row mt-3 text-center">
                    <div class="col-6 mb-3">
                        <div class="bg-white/10 p-3 rounded">
                            <div class="fs-4 fw-bold">1,240</div>
                            <div class="small">Active Users</div>
                        </div>
                    </div>
                    <div class="col-6 mb-3">
                        <div class="bg-white/10 p-3 rounded">
                            <div class="fs-4 fw-bold">586</div>
                            <div class="small">Videos</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // User type selection
            const userTypeBtns = document.querySelectorAll('.user-type-btn');
            const userTypeInput = document.getElementById('userType');
            
            userTypeBtns.forEach(btn => {
                btn.addEventListener('click', function() {
                    // Remove active class from all buttons
                    userTypeBtns.forEach(b => b.classList.remove('active'));
                    
                    // Add active class to clicked button
                    this.classList.add('active');
                    
                    // Update hidden input value
                    const type = this.getAttribute('data-type');
                    userTypeInput.value = type;
                });
            });
            
            // Password visibility toggle
            const togglePassword = document.getElementById('togglePassword');
            const passwordInput = document.getElementById('password');
            
            if (togglePassword && passwordInput) {
                togglePassword.addEventListener('click', function() {
                    const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
                    passwordInput.setAttribute('type', type);
                    
                    // Toggle eye icon
                    const icon = this.querySelector('i');
                    if (type === 'text') {
                        icon.classList.remove('fa-eye');
                        icon.classList.add('fa-eye-slash');
                    } else {
                        icon.classList.remove('fa-eye-slash');
                        icon.classList.add('fa-eye');
                    }
                });
            }
            
            // Video card click
            const videoCards = document.querySelectorAll('.video-card');
            videoCards.forEach(card => {
                card.addEventListener('click', function() {
                    const title = this.querySelector('.video-title').textContent;
                    alert(`Playing: ${title} (Demo Mode)`);
                });
            });
            
            // Form submission
            const loginForm = document.getElementById('loginForm');
            if (loginForm) {
                loginForm.addEventListener('submit', function(e) {
                    // You can add additional validation here
                    console.log('Logging in as:', userTypeInput.value);
                    // Form will submit normally to Laravel route
                });
            }
        });
    </script>
</body>
</html>