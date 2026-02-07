<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

<div class="sidebar" id="sidebar">
    <div class="sidebar-header">
    <div class="user-info">
        <i class="fa-solid fa-user-circle"></i>
        <span class="user-name">HR Admin</span>
    </div>
</div>


    <ul class="sidebar-menu">
        <li>
    <a href="{{ route('admin.dashboard') }}">Dashboard</a>
</li>

        <li><a href="{{ route('admin.leads.index') }}"> Leads</a></li>
        <li>
            <a href="{{ route('admin.callbacks.index') }}">
                 Callbacks 
                <span class="callback-badge" id="callbackCount" style="background: #ff6b6b; color: white; border-radius: 12px; padding: 3px 8px; font-size: 10px; font-weight: bold; margin-left: 8px; display: none;">0</span>
            </a>
        </li>
        
        <li>
            <a href="{{ route('admin.leads.interested') }}">
                 Intrested Candidates
            </a>
        </li>
        <li>
            <a href="{{ route('admin.leads.rejected') }}">
                 Rejected Candidates
            </a>
        </li>
        <li><a href="{{ route('admin.interviews.index') }}"> Interview Schedule</a></li>

        <!-- Employee -->
        <li class="has-sub">
            <a href="#">Employee</a>
            <ul class="submenu">
                <li><a href="{{ route('admin.employees.index') }}">All Employee</a>
            </li>
             <li><a href="{{ route('admin.employee.create') }}">Add Employee</a>
            </li>
          
               <!-- <li>
    <a href="#">Edit Employee</a>
</li> -->

                <li><a href="{{ route('admin.employee.shifts.index') }}">Employee Shift</a></li>
                <li><a href="{{ route('admin.employees.profiles') }}">Employee Profile</a></li>
               <li>
    <a href="{{ route('admin.employees.documents.index') }}">
        Employee Document
    </a>
</li>

                <li><a href="#">Employee Exit / Offboarding</a></li>
            </ul>
        </li>

        <!-- Leave Management -->
        <li class="has-sub">
            <a href="#">Leave Management</a>
            <ul class="submenu">
                <li><a href="#">All Leave Request</a></li>
                <li><a href="#">Leave Type</a></li>
            </ul>
        </li>

        <li><a href="#">Attendance</a></li>

        <!-- Documents -->
        <li class="has-sub">
            <a href="#">Documents</a>
            <ul class="submenu">
                <li><a href="#">Employee Documents</a></li>
                <li><a href="#">Company Documents</a></li>
                <li><a href="#">E-Signatures</a></li>
            </ul>
        </li>

        <!-- Authentication -->
        <li class="has-sub">
            <a href="#">Authentication</a>
            <ul class="submenu">
                <li><a href="#">Sign Up</a></li>
                <li><a href="#">Sign In</a></li>
                <li><a href="#">Forget Password</a></li>
            </ul>
        </li>

        <li><a href="#">Maps</a></li>
        <li class="logout"><a href="#">Logout</a></li>
    </ul>
</div>

<script>
// Update callback count in sidebar
window.updateCallbackCount = function() {
    fetch('/admin/callbacks/count', {
        method: 'GET',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        const badge = document.getElementById('callbackCount');
        if (badge) {
            badge.textContent = data.count;
            badge.style.display = data.count > 0 ? 'inline' : 'none';
        }
    })
    .catch(error => console.log('Error fetching callback count:', error));
};

// Update count on page load
document.addEventListener('DOMContentLoaded', updateCallbackCount);

// Update count every 30 seconds
setInterval(updateCallbackCount, 30000);
</script>

