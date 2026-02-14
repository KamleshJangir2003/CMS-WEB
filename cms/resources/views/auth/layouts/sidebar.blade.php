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
    background: #111827;        /* optional */
    overflow-y: auto;           /* 🔥 vertical scroll */
    overflow-x: hidden;
}

/* smooth scrollbar (Chrome / Edge) */
.sidebar::-webkit-scrollbar {
    width: 6px;
}

.sidebar::-webkit-scrollbar-thumb {
    background: #6b7280;
    border-radius: 10px;
}

.sidebar::-webkit-scrollbar-track {
    background: transparent;
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
<li class="has-submenu">
    <a class="submenu-toggle">
       
        <span>Apllicant Database</span>
        <i class="fa-solid fa-chevron-down arrow"></i>
    </a>

    <ul class="submenu">
        <li>
            <a href="{{ route('admin.leads.index') }}">
                <i class="fa-solid fa-address-book"></i>
                Leads
            </a>
        </li>

        <li>
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
        </li>
    </ul>
</li>

        <li class="has-submenu">
    <a class="submenu-toggle">
        
        <span>Screening Stage</span>
        <i class="fa-solid fa-chevron-down arrow"></i>
    </a>

    <ul class="submenu">
        <li>
            <a href="{{ route('admin.leads.interested') }}">
                <i class="fa-solid fa-star"></i>
                Interested
            </a>
        </li>

        <li>
            <a href="{{ route('admin.interviews.index') }}">
                <i class="fa-solid fa-calendar-check"></i>
                Interview Schedule
            </a>
        </li>

        <li>
            <a href="{{ route('admin.interviews.selected') }}">
                <i class="fa-solid fa-user-check"></i>
                Selected Employees
            </a>
        </li>

        <li>
            <a href="{{ route('admin.employees.documents.index') }}">
                <i class="fa-solid fa-file-lines"></i>
                Documentation Verification
            </a>
        </li>
        <li>
            <a href="{{ route('admin.employees.hired.index') }}">
                <i class="fa-solid fa-user-check"></i>
                Onboarded
            </a>
        </li>
        
    </ul>
</li>

        <li class="has-submenu">
    <a href="#" class="submenu-toggle">
    Application Status
        <i class="fa-solid fa-chevron-down arrow"></i>
    </a>
    <ul class="submenu">
        
        <li>
            <a href="{{ route('admin.leads.rejected') }}">Rejected</a>
        </li>
        <li>
            <a href="{{ route('admin.leads.not-interested') }}">Not Interested</a>
        </li>
        <li>
            <a href="{{ route('admin.leads.wrong-number') }}">Wrong Number</a>
        </li>
        <li>
            <a href="/admin/employees/not-selected">
                <i class="fa-solid fa-user-times"></i>
                Not Selected Employee
            </a>
        </li>
    </ul>
</li>


        
        
       
        <!-- Employee -->
        <li class="has-sub">
            <a href="#">Employee</a>
            <ul class="submenu">
                <li><a href="{{ route('admin.employees.index') }}">All Employee</a>
            </li>
             <li><a href="{{ route('admin.employee.create') }}">Add Employee</a>
            </li>
                <li><a href="{{ route('admin.employee.shifts.index') }}">Employee Shift</a></li>
                <li><a href="{{ route('admin.employees.profiles') }}">Employee Profile</a></li>
                <li><a href="{{ route('admin.employees.list') }}">All Employee Details</a></li>
                <li><a href="{{ route('admin.employee.credentials') }}">Employee Login</a></li>
                <li><a href="#">Employee Exit / Offboarding</a></li>
            </ul>
        </li>

        <!-- Leave Management -->
        <li class="has-sub">
            <a href="#">Leave Management</a>
            <ul class="submenu">
                <li><a href="#">All Leave Request</a></li>
                <li><a href="#">Leave Type</a></li>
                <li><a href="{{ route('admin.attendance.index') }}">
                    <i class="fa-solid fa-calendar-check"></i>
                    Attendance
                </a></li>
            </ul>
        </li>
        <li class="has-sub">
            <a href="#">Payroll</a>
            <ul class="submenu">
                <li><a href="#">Overview</a></li>
                <li><a href="#">Employee</a></li>
                <li><a href="{{ route('admin.salary.index') }}">
                    <i class="fa-solid fa-money-bill-wave"></i>
                    Salary Management
                </a></li>
                <li><a href="#">PaySlip</a></li>
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
        <li><a href="{{ route('admin.hr-notes.index') }}">HR Notes</a></li>
        <li><a href="{{ route('admin.job-openings.index') }}"><i class="fa-solid fa-briefcase"></i> Job Opening Management</a></li>
       
        <li><a href="{{ route('admin.birthdays.index') }}"><i class="fa-solid fa-birthday-cake"></i> Birthday</a></li>
        <li><a href="{{ route('admin.employees.all') }}"><i class="fa-solid fa-envelope"></i> All Emails</a></li>
        <li><a href="{{ route('admin.bills.index') }}"><i class="fa-solid fa-file-invoice"></i> Bill Management</a></li>
        <li><a href="{{ route('admin.expenses.index') }}"><i class="fa-solid fa-money-bill-wave"></i> Expenses</a></li>
        <li><a href="{{ route('admin.tickets.index') }}"><i class="fa-solid fa-ticket-alt"></i> Employee Tickets</a></li>
        <li><a href="{{ route('admin.employee-expenses.index') }}"><i class="fa-solid fa-receipt"></i> Reimbursement</a></li>
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


