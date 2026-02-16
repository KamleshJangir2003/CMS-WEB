<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Kwikster HRMS</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Sidebar CSS -->
    <link rel="stylesheet" href="{{ asset('css/sidebar.css') }}">
   <link rel="stylesheet" href="{{ asset('css/app.css') }}">

<!-- Bootstrap CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">


    <!-- Page Specific CSS -->
    @yield('styles')

    <style>
/* FORCE table layout */
table td, 
table th {
    position: static !important;
}

/* FIX badge floating issue */
.badge {
    position: relative !important;
    display: inline-block !important;
}

/* Fix sidebar overlap issue globally */
.modal {
    z-index: 99999 !important;
}

.modal-backdrop {
    z-index: 99998 !important;
}

/* Important for Mega Able type layouts */
.page-wrapper,
.pcoded-main-container,
.main-content,
.container-fluid {
    transform: none !important;
}

/* ================================
   RESPONSIVE MOBILE DESIGN
================================ */

/* Mobile Styles - 768px and below */
@media (max-width: 768px) {
    /* Hide sidebar by default on mobile */
    .sidebar {
        transform: translateX(-100%);
        transition: transform 0.3s ease;
        z-index: 9999;
    }
    
    /* Show sidebar when menu is open */
    body.sidebar-open .sidebar {
        transform: translateX(0);
    }
    
    /* Add overlay when sidebar is open */
    body.sidebar-open::before {
        content: '';
        position: fixed;
        top: 0;
        left: 0;
        /* width: 100%;
        height: 100%; */
        background: rgba(0,0,0,0.5);
        z-index: 9998;
    }
    
    /* Adjust header for mobile */
    .top-header {
        left: 0 !important;
        width: 100% !important;
        padding: 0 15px;
    }
    
    /* Show menu button on mobile */
    .menu-btn {
        display: flex !important;
        align-items: center;
        justify-content: center;
        background: none;
        border: none;
        font-size: 20px;
        cursor: pointer;
        padding: 8px;
        border-radius: 6px;
        color: #333;
        transition: background 0.2s;
    }
    
    .menu-btn:hover {
        background: #f3f4f6;
    }
    
    /* Adjust main content for mobile */
    .main-content {
        margin-left: 0 !important;
        /* padding-top: 50px; */
        width: 100%;
        /* padding-left: 15px;
        padding-right: 15px; */
    }
    
    /* Hide some header elements on small screens */
    .global-search {
        display: none;
    }
    
    /* Make header items smaller */
    .header-right {
        gap: 5px;
    }
    
    .header-right li a {
        padding: 4px 8px;
        font-size: 11px;
    }
    
    /* Global responsive styles for all pages */
    .container-fluid {
        padding-left: 10px !important;
        padding-right: 10px !important;
    }
    
    .card {
        margin-bottom: 15px;
    }
    
    .card-body {
        padding: 15px;
    }
    
    /* Responsive tables */
    .table-responsive {
        font-size: 11px;
    }
    
    .table th,
    .table td {
        padding: 6px 4px;
        font-size: 11px;
    }
    
    /* Responsive buttons */
    .btn {
        font-size: 12px;
        padding: 6px 10px;
    }
    
    .btn-sm {
        font-size: 10px;
        padding: 4px 6px;
    }
    
    /* Responsive forms */
    .form-control {
        font-size: 14px;
        padding: 8px 10px;
    }
    
    .form-label {
        font-size: 13px;
        margin-bottom: 4px;
    }
    
    /* Responsive modals */
    .modal-dialog {
        margin: 10px;
        max-width: calc(100% - 20px);
    }
    
    .modal-body {
        padding: 15px;
    }
    
    /* Responsive cards */
    .dashboard-card {
        padding: 15px;
    }
    
    .dashboard-card h2 {
        font-size: 1.5rem;
    }
    
    /* Responsive alerts */
    .alert {
        padding: 10px;
        font-size: 13px;
    }
    
    /* Responsive breadcrumbs */
    .breadcrumb {
        font-size: 12px;
        padding: 8px 0;
    }
    
    /* Hide some columns in tables on mobile */
    .d-none-mobile {
        display: none !important;
    }
    
    /* Stack form elements */
    .row .col-md-6,
    .row .col-lg-6 {
        margin-bottom: 10px;
    }
}

/* Tablet Styles - 769px to 1024px */
@media (min-width: 769px) and (max-width: 1024px) {
    .sidebar {
        width: 220px;
    }
    
    .top-header {
        left: 220px;
        width: calc(100% - 220px);
    }
    
    .main-content {
        margin-left: 220px;
    }
    
    .global-search {
        width: 250px;
    }
}

/* Desktop Styles - 1025px and above (default) */
@media (min-width: 1025px) {
    .menu-btn {
        display: none;
    }
}

</style>

</head>
<body>

    <!-- Sidebar -->
   @include('auth.layouts.sidebar')
     @include('auth.layouts.header') 

    <!-- Main Content -->
    <div class="main-content">
        @yield('content')
    </div>

    <!-- Sidebar JS -->
    <script src="{{ asset('js/sidebar.js') }}"></script>
    <!-- Excel Upload JS -->
    <script src="{{ asset('js/excel-upload.js') }}"></script>
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Page Specific JS -->
    @stack('scripts')
    @yield('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {

    // Mobile menu toggle functionality
    function initMobileMenu() {
        const toggleBtn = document.getElementById('menuToggle');
        
        if (toggleBtn) {
            toggleBtn.addEventListener('click', function (e) {
                e.preventDefault();
                e.stopPropagation();
                
                console.log('Menu button clicked'); // Debug log
                
                // For mobile: toggle sidebar visibility
                if (window.innerWidth <= 768) {
                    document.body.classList.toggle('sidebar-open');
                    console.log('Sidebar toggled:', document.body.classList.contains('sidebar-open'));
                } else {
                    // For desktop: collapse sidebar
                    document.body.classList.toggle('sidebar-collapsed');
                }
            });
        } else {
            console.log('Menu toggle button not found');
        }
    }

    // Initialize after a small delay to ensure header is loaded
    setTimeout(initMobileMenu, 100);

    // Close sidebar when clicking on overlay (mobile only)
    document.addEventListener('click', function(e) {
        if (window.innerWidth <= 768 && 
            document.body.classList.contains('sidebar-open') && 
            !e.target.closest('.sidebar') && 
            !e.target.closest('#menuToggle')) {
            document.body.classList.remove('sidebar-open');
        }
    });

    // Handle window resize
    window.addEventListener('resize', function() {
        if (window.innerWidth > 768) {
            document.body.classList.remove('sidebar-open');
        }
    });

});
</script>
<script>
document.addEventListener("DOMContentLoaded", function () {

    // Move ALL modals to body to prevent sidebar overlap
    document.querySelectorAll('.modal').forEach(function(modal){
        document.body.appendChild(modal);
    });

});
</script>


</body>
</html>
