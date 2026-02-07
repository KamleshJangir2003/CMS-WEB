<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Kwikster CMS</title>
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

    <!-- Page Specific JS -->
    @stack('scripts')
    @yield('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {

    var toggleBtn = document.getElementById('menuToggle');

    if (toggleBtn) {
        toggleBtn.addEventListener('click', function () {
            document.body.classList.toggle('sidebar-collapsed');
        });
    }

});
</script>

</body>
</html>
