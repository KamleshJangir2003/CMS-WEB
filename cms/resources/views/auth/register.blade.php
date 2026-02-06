<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Employee Signup | Kwikster CMS</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        
        .register-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
            overflow: hidden;
            width: 100%;
            max-width: 1000px;
        }
        
        .register-form-section {
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
            background: rgba(16, 185, 129, 0.3);
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
            border-color: #10b981;
            box-shadow: 0 0 0 0.25rem rgba(16, 185, 129, 0.25);
        }
        
        .btn-register {
            background: linear-gradient(to right, #10b981, #059669);
            border: none;
            color: white;
            padding: 12px;
            border-radius: 8px;
            font-weight: 600;
            width: 100%;
            transition: all 0.3s;
        }
        
        .btn-register:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(16, 185, 129, 0.3);
        }
        
        .login-link {
            color: #059669;
            text-decoration: none;
            font-weight: 500;
        }
        
        .login-link:hover {
            text-decoration: underline;
        }
        
        .employee-badge {
            display: inline-block;
            padding: 8px 20px;
            background: #d1fae5;
            color: #059669;
            border-radius: 20px;
            font-size: 1rem;
            font-weight: 600;
            margin-bottom: 15px;
        }
        
        .password-wrapper {
            position: relative;
        }
        
        .password-toggle {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: #6b7280;
        }
        
        .company-note {
            background: #f0fdf4;
            border-left: 4px solid #10b981;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
        }
        
        @media (max-width: 768px) {
            .register-card {
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
    <div class="register-card d-flex">
        <!-- Left Side - Employee Register Form -->
        <div class="register-form-section col-md-6">
            <div class="text-center mb-4">
                <h2 class="fw-bold mb-2">Employee Signup</h2>
                <span class="employee-badge">
                    <i class="fas fa-user-tie me-2"></i>Kwikster Employee
                </span>
                <p class="text-muted">Create your employee account for Kwikster CMS</p>
            </div>

            <div class="company-note">
                <p class="mb-0 small">
                    <i class="fas fa-info-circle me-2 text-success"></i>
                    <strong>Note:</strong> This portal is for Kwikster employees only.
                </p>
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

            @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            <form method="POST" action="{{ route('register') }}">
                @csrf
                <input type="hidden" name="user_type" value="employee">

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">First Name *</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light">
                                <i class="fas fa-user text-muted"></i>
                            </span>
                            <input type="text" name="first_name" class="form-control" 
                                   required placeholder="Enter first name">
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Last Name *</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light">
                                <i class="fas fa-user text-muted"></i>
                            </span>
                            <input type="text" name="last_name" class="form-control" 
                                   required placeholder="Enter last name">
                        </div>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Email *</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light">
                            <i class="fas fa-envelope text-muted"></i>
                        </span>
                        <input type="email" name="email" class="form-control" 
                               required placeholder="yourname@gmail.com">
                    </div>
                    <small class="text-muted">Use your personal email address</small>
                </div>

                <div class="mb-3">
                    <label class="form-label">Phone Number *</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light">
                            <i class="fas fa-phone text-muted"></i>
                        </span>
                        <input type="tel" name="phone" class="form-control" 
                               required placeholder="+91 98765 43210">
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Department *</label>
                    <select name="department" class="form-control" required>
                        <option value="">Select Your Department</option>
                        <option value="it">IT & Technical</option>
                        <option value="content">Content Management</option>
                        <option value="video">Video Production</option>
                        <option value="sales">Sales & Marketing</option>
                        <option value="support">Customer Support</option>
                        <option value="hr">Human Resources</option>
                        <option value="finance">Finance & Accounts</option>
                        <option value="operations">Operations</option>
                        <option value="other">Other Department</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Password *</label>
                    <div class="password-wrapper">
                        <div class="input-group">
                            <span class="input-group-text bg-light">
                                <i class="fas fa-lock text-muted"></i>
                            </span>
                            <input type="password" name="password" id="password" 
                                   class="form-control" required 
                                   placeholder="Create a strong password (min. 8 characters)">
                        </div>
                        <span class="password-toggle" id="togglePassword">
                            <i class="far fa-eye"></i>
                        </span>
                    </div>
                    <small class="text-muted">Include uppercase, lowercase, number and special character</small>
                </div>

                <div class="mb-4">
                    <label class="form-label">Confirm Password *</label>
                    <div class="password-wrapper">
                        <div class="input-group">
                            <span class="input-group-text bg-light">
                                <i class="fas fa-lock text-muted"></i>
                            </span>
                            <input type="password" name="password_confirmation" id="confirmPassword" 
                                   class="form-control" required 
                                   placeholder="Confirm your password">
                        </div>
                        <span class="password-toggle" id="toggleConfirmPassword">
                            <i class="far fa-eye"></i>
                        </span>
                    </div>
                </div>

                <div class="form-check mb-4">
                    <input class="form-check-input" type="checkbox" id="employeeTerms" required>
                    <label class="form-check-label" for="employeeTerms">
                        I confirm that I am a current employee of Kwikster and agree to the 
                        <a href="#" class="text-success fw-medium">Employee Terms of Service</a> and 
                        <a href="#" class="text-success fw-medium">Privacy Policy</a>.
                    </label>
                </div>

                <button type="submit" class="btn-register mb-3">
                    <i class="fas fa-user-tie me-2"></i>Create Employee Account
                </button>

                <div class="text-center mt-4">
                    <p class="text-muted">
                        Already have an employee account? 
                        <a href="{{ route('login') }}" class="login-link">Login here</a>
                    </p>
                    <p class="small text-muted">
                        For registration issues, contact HR: 
                        <a href="mailto:hr@kwikster.com" class="text-success">hr@kwikster.com</a>
                    </p>
                </div>
            </form>
        </div>

        <!-- Right Side - Employee Benefits & Videos -->
        <div class="video-section col-md-6">
            <h3 class="mb-4"><i class="fas fa-users me-2"></i>Welcome to Kwikster Team</h3>
            
            <div class="video-card">
                <div class="play-btn">
                    <i class="fas fa-play text-success"></i>
                </div>
                <div class="video-title">The Muppet Show</div>
                <div class="video-meta">Comedy • 45 min • Trending</div>
            </div>
            
            <div class="video-card">
                <div class="play-btn">
                    <i class="fas fa-video text-success"></i>
                </div>
                <div class="video-title">Employee Onboarding Guide</div>
                <div class="video-meta">Training • 15 min • Recommended</div>
            </div>
            
            <div class="video-card">
                <div class="play-btn">
                    <i class="fas fa-graduation-cap text-success"></i>
                </div>
                <div class="video-title">CMS Tutorial for Beginners</div>
                <div class="video-meta">Tutorial • 25 min • Recommended</div>
            </div>
            
            <div class="mt-4">
                <h5><i class="fas fa-star me-2 text-warning"></i>Employee Portal Features</h5>
                <ul class="list-unstyled mt-3">
                    <li class="mb-2">
                        <i class="fas fa-check-circle text-success me-2"></i>
                        Access to company video library
                    </li>
                    <li class="mb-2">
                        <i class="fas fa-check-circle text-success me-2"></i>
                        Upload and manage video content
                    </li>
                    <li class="mb-2">
                        <i class="fas fa-check-circle text-success me-2"></i>
                        Team collaboration tools
                    </li>
                    <li class="mb-2">
                        <i class="fas fa-check-circle text-success me-2"></i>
                        Analytics and performance reports
                    </li>
                    <li class="mb-2">
                        <i class="fas fa-check-circle text-success me-2"></i>
                        Internal communication system
                    </li>
                    <li class="mb-2">
                        <i class="fas fa-check-circle text-success me-2"></i>
                        Training and development resources
                    </li>
                    <li>
                        <i class="fas fa-check-circle text-success me-2"></i>
                        HR and payroll information access
                    </li>
                </ul>
            </div>
            
            <div class="mt-4 pt-3 border-top">
                <h6><i class="fas fa-shield-alt me-2"></i>Secure Portal</h6>
                <p class="small mt-2 mb-0">
                    Your data is protected with 256-bit encryption. Only verified employees can access this portal.
                </p>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Password visibility toggle
            const togglePassword = document.getElementById('togglePassword');
            const passwordInput = document.getElementById('password');
            
            const toggleConfirmPassword = document.getElementById('toggleConfirmPassword');
            const confirmPasswordInput = document.getElementById('confirmPassword');
            
            // Toggle main password
            if (togglePassword && passwordInput) {
                togglePassword.addEventListener('click', function() {
                    const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
                    passwordInput.setAttribute('type', type);
                    
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
            
            // Toggle confirm password
            if (toggleConfirmPassword && confirmPasswordInput) {
                toggleConfirmPassword.addEventListener('click', function() {
                    const type = confirmPasswordInput.getAttribute('type') === 'password' ? 'text' : 'password';
                    confirmPasswordInput.setAttribute('type', type);
                    
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
            document.querySelectorAll('.video-card').forEach(card => {
                card.addEventListener('click', function() {
                    const title = this.querySelector('.video-title').textContent;
                    const type = title.includes('Training') || title.includes('Tutorial') 
                        ? 'Training Video' 
                        : 'Entertainment Video';
                    alert(`Playing: ${title}\nType: ${type}`);
                });
            });
            
            // Form validation
            const form = document.querySelector('form');
            if (form) {
                form.addEventListener('submit', function(e) {
                    const email = form.querySelector('input[name="email"]').value;
                    const department = form.querySelector('select[name="department"]').value;
                    const password = form.querySelector('input[name="password"]').value;
                    const confirmPassword = form.querySelector('input[name="password_confirmation"]').value;
                    const terms = form.querySelector('#employeeTerms').checked;
                    
                    // Basic email validation
                    if (!email.includes('@') || !email.includes('.')) {
                        e.preventDefault();
                        alert('Please enter a valid email address');
                        return false;
                    }
                    
                    // Department validation
                    if (!department) {
                        e.preventDefault();
                        alert('Please select your department');
                        return false;
                    }
                    
                    // Check password match
                    if (password !== confirmPassword) {
                        e.preventDefault();
                        alert('Passwords do not match!');
                        return false;
                    }
                    
                    // Check password strength
                    if (password.length < 8) {
                        e.preventDefault();
                        alert('Password must be at least 8 characters long');
                        return false;
                    }
                    
                    // Check terms accepted
                    if (!terms) {
                        e.preventDefault();
                        alert('Please accept the Employee Terms of Service');
                        return false;
                    }
                    
                    return true;
                });
            }
        });
    </script>
</body>
</html>