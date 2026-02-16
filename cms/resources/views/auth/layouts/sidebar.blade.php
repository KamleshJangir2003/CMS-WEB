<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
<style>
    .sidebar .submenu {
    display: none;
    padding-left: 15px;
}

.sidebar .has-submenu.open > .submenu {
    display: block;
}

.sidebar .submenu-toggle {
    display: flex;
    justify-content: space-between;
    align-items: center;
    cursor: pointer;
}

.sidebar .arrow {
    font-size: 12px;
    transition: transform 0.3s ease;
}

.sidebar .has-submenu.open .arrow {
    transform: rotate(180deg);
}

.sidebar {
    position: fixed;
    top: 0;
    left: 0;
    height: 100vh;              /* full screen height */
    width: 260px;               /* sidebar width */
    background: white;          /* white background */
    overflow-y: auto;           /* 🔥 vertical scroll */
    overflow-x: hidden;
}

/* smooth scrollbar (Chrome / Edge) */
.sidebar::-webkit-scrollbar {
    width: 6px;
}

.sidebar::-webkit-scrollbar-thumb {
    background: #2eacb3;
    border-radius: 10px;
}

.sidebar::-webkit-scrollbar-track {
    background: transparent;
}

/* Company Info Styles */
.sidebar-header {
    padding: 1.4px 20px;
    border-bottom: 1px solidrgb(79, 124, 212);
}

.company-info {
    display: flex;
    align-items: center;
}

.company-logo {
    width: 40px;
    height: 40px;
    background: #2eacb3;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-right: 12px;
}

.company-logo i {
    color: white;
    font-size: 20px;
}

.company-name {
    color: #2eacb3;
    font-size: 18px;
    font-weight: 600;
    letter-spacing: 0.5px;
}


/* ===============================
   AUTO HIDE SIDEBAR ON CURSOR
================================ */

.sidebar {
    transition: transform 0.35s ease-in-out;
    z-index: 1000;
}

/* sidebar hide state */
.sidebar.sidebar-hidden {
    transform: translateX(-230px); /* thoda edge visible */
}

/* invisible hover strip (trigger area) */
.sidebar-hover-zone {
    position: fixed;
    top: 0;
    left: 0;
    width: 30px;      /* cursor sensitive area */
    height: 100vh;
    z-index: 999;
    background: transparent;
}


</style>
<style>
    /* 1. Professional Blue */
/* Sidebar Menu Styles */
.sidebar-menu {
    list-style: none;
    padding: 0;
    margin: 0;
}

.sidebar-menu li {
    margin: 0;
}

.sidebar-menu a {
    display: flex;
    align-items: center;
    padding: 12px 20px;
    color: #2eacb3;
    text-decoration: none;
    transition: all 0.3s ease;
    cursor: pointer;
}

.sidebar-menu a:hover,
.sidebar-menu a:focus,
.sidebar-menu a.active {
    background: #2eacb3;
    color: white;
}

.sidebar-menu a:hover i,
.sidebar-menu a:focus i,
.sidebar-menu a.active i {
    color: white !important;
}

.sidebar-menu i {
    color: #2eacb3 !important;
    margin-right: 10px;
    width: 20px;
}

.sidebar .submenu {
    background:rgb(242, 244, 245);
}

.sidebar .submenu a {
    padding-left: 50px;
    font-size: 14px;
    cursor: pointer;
}

.sidebar .submenu a:hover,
.sidebar .submenu a:focus,
.sidebar .submenu a.active {
    background: #2eacb3;
    color: white;
}

.sidebar .submenu a:hover i,
.sidebar .submenu a:focus i,
.sidebar .submenu a.active i {
    color: white !important;
}

.sidebar .submenu i {
    color: #2eacb3 !important;
}




.company-logo1 img {
    width: 200px;
    /* height: 20px; */
    object-fit: contain;
    align-items: center
}

</style>
<div class="sidebar" id="sidebar">
    <div class="sidebar-header">
    <div class="company-info">
    <div class="company-logo1">
        <img src="{{ asset('Kwikster.jpeg') }}" alt="Kwikster Logo">
    </div>
    
</div>


        <!-- <div class="user-info">
            <i class="fa-solid fa-user-circle"></i>
            <span class="user-name">HR Admin</span>
        </div> -->
    </div>


    <ul class="sidebar-menu">
    <li>
    <a href="{{ route('admin.dashboard') }}">
        <i class="fa-solid fa-gauge-high"></i>
        Dashboard
    </a>
</li>
<li class="has-submenu">
<a class="submenu-toggle">
    <span>
        <i class="fa-solid fa-folder-open"></i>
        Applicant Database
    </span>
    <i class="fa-solid fa-chevron-down arrow"></i>
</a>

    <ul class="submenu">
       
            <a href="{{ route('admin.leads.index') }}">
                <i class="fa-solid fa-address-book"></i>
                Leads
            </a>
        

        
            <a href="{{ route('admin.callbacks.index') }}">
                <i class="fa-solid fa-phone-volume"></i>
                
                pending Response
                <span class="callback-badge" id="callbackCount"
                    style="background:#ff6b6b;color:#fff;border-radius:12px;
                    padding:3px 8px;font-size:10px;font-weight:bold;
                    margin-left:8px;display:none;">
                    0
                </span>
            </a>
        
    </ul>
</li>

        <li class="has-submenu">
        <a class="submenu-toggle">
    <span>
        <i class="fa-solid fa-clipboard-list"></i>
        Screening Stage
    </span>
    <i class="fa-solid fa-chevron-down arrow"></i>
</a>


    <ul class="submenu">
       
            <a href="{{ route('admin.leads.interested') }}">
                <i class="fa-solid fa-star"></i>
                Interested
            </a>
       
            <a href="{{ route('admin.interviews.index') }}">
                <i class="fa-solid fa-calendar-check"></i>
                Interview Schedule
            </a>
        
            <a href="{{ route('admin.interviews.selected') }}">
                <i class="fa-solid fa-user-check"></i>
                Selected Employees
            </a>
        

        
            <a href="{{ route('admin.employees.documents.index') }}">
                <i class="fa-solid fa-file-lines"></i>
                Documentation Verification
            </a>
        
        
            <a href="{{ route('admin.employees.hired.index') }}">
                <i class="fa-solid fa-user-check"></i>
                Onboarded
            </a>
       
        
    </ul>
</li>

        <li class="has-submenu">
        <a href="#" class="submenu-toggle">
    <span>
        <i class="fa-solid fa-bars-progress"></i>
        Application Status
    </span>
    <i class="fa-solid fa-chevron-down arrow"></i>
</a>

    <ul class="submenu">
        
       
            <a href="{{ route('admin.leads.rejected') }}">Rejected</a>
        
            <a href="{{ route('admin.leads.not-interested') }}">Not Interested</a>
        
            <a href="{{ route('admin.leads.wrong-number') }}">Wrong Number</a>
        
            <a href="/admin/employees/not-selected">
               
                Not Selected Employee
            </a>
        
    </ul>
</li>


        
        
       
        <!-- Employee -->
        <li class="has-sub">
            <a href="#">
            <i class="fa-solid fa-users"></i> Employee</a>
            <ul class="submenu">
                <a href="{{ route('admin.employees.index') }}">All Employee</a>
            
             <a href="{{ route('admin.employee.create') }}">Add Employee</a>
            </li>
                <a href="{{ route('admin.employee.shifts.index') }}">Employee Shift</a>
                <a href="{{ route('admin.employees.profiles') }}">Employee Profile</a>
                <a href="{{ route('admin.employees.list') }}">All Employee Details</a>
                <a href="{{ route('admin.employee.credentials') }}">Employee Login</a>
                <!-- <a href="#">Employee Exit / Offboarding</a> -->
            </ul>
        </li>

        <!-- Leave Management -->
        <li class="has-sub">
            <a href="#">
            <i class="fa-solid fa-calendar-check"></i> Leave Management</a>
            <ul class="submenu">
                <a href="#">All Leave Request</a>
                <a href="#">Leave Type</a>
                <a href="{{ route('admin.attendance.index') }}">
                    <i class="fa-solid fa-calendar-check"></i>
                    Attendance
                </a>
            </ul>
        </li>
        <li class="has-sub">
            
            <a href="#">
            <i class="fa-solid fa-file-invoice-dollar"></i> Payroll</a>
            <ul class="submenu">
                <a href="#">Overview</a>
                <a href="#">Employee</a>
                <a href="{{ route('admin.salary.index') }}">
                    <i class="fa-solid fa-money-bill-wave"></i>
                    Salary Management
                </a>
                <a href="#">PaySlip</a>
            </ul>
        </li>


       

        <!-- Documents -->
        <!-- <li class="has-sub">
            <a href="#">Documents</a>
            <ul class="submenu">
                <li><a href="#">Employee Documents</a></li>
                <li><a href="#">Company Documents</a></li>
                <li><a href="#">E-Signatures</a></li>
            </ul>
        </li> -->

        <!-- Authentication -->
        <!-- <li class="has-sub">
            <a href="#">Authentication</a>
            <ul class="submenu">
                <li><a href="#">Sign Up</a></li>
                <li><a href="#">Sign In</a></li>
                <li><a href="#">Forget Password</a></li>
            </ul>
        </li> -->

        <!-- <li><a href="#">Maps</a></li> -->
        <li><a href="{{ route('admin.hr-notes.index') }}">
        <i class="fa-solid fa-clipboard"></i> HR Notes</a></li>
        <li><a href="{{ route('admin.job-openings.index') }}"><i class="fa-solid fa-briefcase"></i> Job Opening Management</a></li>
       
        <li><a href="{{ route('admin.birthdays.index') }}"><i class="fa-solid fa-birthday-cake"></i> Birthday</a></li>
        <li><a href="{{ route('admin.employees.all') }}"><i class="fa-solid fa-envelope"></i> All Emails</a></li>
        <li><a href="{{ route('admin.bills.index') }}"><i class="fa-solid fa-file-invoice"></i> Bill Management</a></li>
        <li><a href="{{ route('admin.expenses.index') }}"><i class="fa-solid fa-money-bill-wave"></i> Expenses</a></li>
        <li><a href="{{ route('admin.tickets.index') }}"><i class="fa-solid fa-ticket-alt"></i> Employee Tickets</a></li>
        <li><a href="{{ route('admin.employee-expenses.index') }}"><i class="fa-solid fa-receipt"></i> Reimbursement</a></li>
        <!-- <li class="logout"><a href="#">Logout</a></li> -->
    </ul>
    
    <!-- Mobile Header Items -->
    <div class="mobile-header-items" style="display: none;">
        <ul style="list-style: none; padding: 0; margin: 0;">
            <li>
                <a href="{{ route('admin.employee.create') }}">
                    <i class="fa-solid fa-user-plus"></i>
                    Add Employee
                </a>
            </li>
            <li>
                <a href="{{ route('admin.salary.calculator') }}">
                    <i class="fa-solid fa-calculator"></i>
                    Salary Calculator
                </a>
            </li>
            <li>
                <a href="{{ route('admin.pf.forms') }}">
                    <i class="fa-solid fa-file-invoice"></i>
                    PF Form
                </a>
            </li>
            <li>
                <a href="#" onclick="showNotifications()">
                    <i class="fa-regular fa-bell"></i>
                    Notifications
                </a>
            </li>
            <li>
                <a href="{{ route('admin.profile') }}">
                    <i class="fa-solid fa-user"></i>
                    Profile
                </a>
            </li>
            <li>
                <a href="{{ route('admin.settings') }}">
                    <i class="fa-solid fa-cog"></i>
                    Settings
                </a>
            </li>
            <li>
                <form action="{{ route('logout') }}" method="POST" style="margin: 0;">
                    @csrf
                    <button type="submit" style="background: none; border: none; color: #f9fafb; width: 100%; text-align: left; padding: 10px 15px; display: flex; align-items: center; gap: 10px; border-radius: 6px; transition: background 0.2s;" onmouseover="this.style.background='#374151'" onmouseout="this.style.background='none'">
                        <i class="fa-solid fa-sign-out-alt"></i>
                        Logout
                    </button>
                </form>
            </li>
        </ul>
    </div>
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

// Show employee list for details
function showEmployeeList() {
    fetch('/admin/employees', {
        method: 'GET',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.text())
    .then(html => {
        // Create modal to show employee list
        const modal = document.createElement('div');
        modal.innerHTML = `
            <div class="modal fade" id="employeeListModal" tabindex="-1">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Select Employee for Details</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Name</th>
                                            <th>Email</th>
                                            <th>Department</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody id="employeeTableBody">
                                        <!-- Will be populated by AJAX -->
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        `;
        document.body.appendChild(modal);
        
        // Load employees via AJAX
        loadEmployeeList();
        
        // Show modal
        const bootstrapModal = new bootstrap.Modal(document.getElementById('employeeListModal'));
        bootstrapModal.show();
        
        // Remove modal when closed
        document.getElementById('employeeListModal').addEventListener('hidden.bs.modal', function() {
            modal.remove();
        });
    })
    .catch(error => console.log('Error:', error));
}

function loadEmployeeList() {
    fetch('/admin/employees/data', {
        method: 'GET',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        const tbody = document.getElementById('employeeTableBody');
        tbody.innerHTML = '';
        
        data.employees.forEach(employee => {
            const row = document.createElement('tr');
            row.innerHTML = `
                <td>${employee.first_name} ${employee.last_name}</td>
                <td>${employee.email}</td>
                <td>${employee.department || 'N/A'}</td>
                <td>
                    <a href="/admin/employees/${employee.id}/details" class="btn btn-sm btn-primary">
                        <i class="fa-solid fa-eye"></i> View Details
                    </a>
                </td>
            `;
            tbody.appendChild(row);
        });
    })
    .catch(error => console.log('Error loading employees:', error));
}
</script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.submenu-toggle').forEach(toggle => {
        toggle.addEventListener('click', function (e) {
            e.preventDefault(); // 🚫 page reload stop

            const parent = this.closest('.has-submenu');

            // close other open menus (optional – premium feel)
            document.querySelectorAll('.has-submenu').forEach(item => {
                if (item !== parent) {
                    item.classList.remove('open');
                }
            });

            parent.classList.toggle('open');
        });
    });
});
</script>
<!-- <script>
document.addEventListener('DOMContentLoaded', function () {
    const sidebar = document.getElementById('sidebar');

   
    const hoverZone = document.createElement('div');
    hoverZone.className = 'sidebar-hover-zone';
    document.body.appendChild(hoverZone);

    let hideTimer;

    
    sidebar.addEventListener('mouseleave', () => {
        hideTimer = setTimeout(() => {
            sidebar.classList.add('sidebar-hidden');
        }, 300);
    });

    
    sidebar.addEventListener('mouseenter', () => {
        clearTimeout(hideTimer);
        sidebar.classList.remove('sidebar-hidden');
    });

    
    hoverZone.addEventListener('mouseenter', () => {
        sidebar.classList.remove('sidebar-hidden');
    });
});
</script> -->


