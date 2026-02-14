<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

<style>
    :root{
        --sidebar-width: 260px;
    }

    body{
        margin: 0;
        padding: 0;
        overflow-x: hidden;
    }

   .top-header{
    position: fixed;
    top: 0;
    left: 260px;
    width: calc(100% - 260px);
    height: 60px;
    background: #fff;
    border-bottom: 1px solid #e5e7eb;

    display: flex;
    align-items: center;
    justify-content: space-between;

    padding: 0 20px;
    z-index: 9999;            /* 🔥 header upar */
    box-sizing: border-box;
}

.header-left{
    display: flex;
    align-items: center;
    gap: 15px;
}

.menu-btn, .header-icon{
    background: none;
    border: none;
    font-size: 22px;
    cursor: pointer;
    position: relative;
    z-index: 10000;
    pointer-events: auto;
    padding: 8px;
    border-radius: 6px;
    transition: background 0.2s;
}

.header-icon:hover{
    background: #f3f4f6;
}

.menu-btn{
    position: relative;
    z-index: 10000;
    pointer-events: auto;
}

.header-right{
    display: flex;
    align-items: center;
    gap: 20px;
}

/* DROPDOWN */
.dropdown{
    position: relative;
    z-index: 10000;          /* 🔥 dropdown clickable */
}

.dropdown-btn{
    background: none;
    border: none;
    cursor: pointer;
    display: flex;
    align-items: center;
    gap: 6px;
}

.dropdown-menu{
    display: none;
    position: absolute;
    top: 45px;
    right: 0;
    background: #fff;
    border: 1px solid #ddd;
    min-width: 180px;
    border-radius: 6px;
    box-shadow: 0 10px 25px rgba(0,0,0,0.1);
    z-index: 10001;          /* 🔥 sabse upar */
}

.dropdown-menu li{
    padding: 10px;
    cursor: pointer;
    display: flex;
    align-items: center;
    gap: 8px;
    pointer-events: auto;
}

.dropdown-menu li:hover{
    background: #f3f4f6;
}

.dropdown.open .dropdown-menu{
    display: block;
}

/* USER */
.user-dropdown img{
    width: 34px;
    height: 34px;
    border-radius: 50%;
}

/* BADGE */
.badge{
    position: absolute;
    /* top: -6px;
    right: -6px; */
    background: red;
    color: #fff;
    font-size: 10px;
    /* padding: 2px 6px;
    border-radius: 50%; */
}

/* BILLS NOTIFICATION STYLES */
.bills-btn {
    animation: pulse 2s infinite;
}

.bills-badge {
    background: #f39c12 !important;
    top: -6px;
    right: -6px;
    padding: 2px 6px;
    border-radius: 50%;
    font-size: 10px;
}

.bills-menu {
    min-width: 280px;
}

.bills-header {
    background: #f39c12;
    color: white;
    font-weight: bold;
    text-align: center;
    padding: 10px;
    margin: -1px;
    border-radius: 6px 6px 0 0;
}

.bills-item {
    padding: 8px 12px;
    border-bottom: 1px solid #f0f0f0;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 8px;
}

.bills-item-info {
    display: flex;
    flex-direction: column;
    gap: 2px;
}

.bills-item-info strong {
    font-size: 14px;
}

.bills-item-info small {
    color: #666;
    font-size: 12px;
}

.bills-item button {
    padding: 4px 8px;
    font-size: 11px;
    border-radius: 4px;
}

/* BIRTHDAY NOTIFICATION STYLES */
.birthday-btn {
    animation: pulse 2s infinite;
}

@keyframes pulse {
    0% { transform: scale(1); }
    50% { transform: scale(1.1); }
    100% { transform: scale(1); }
}

.birthday-badge {
    background: #ff6b6b !important;
    top: -6px;
    right: -6px;
    padding: 2px 6px;
    border-radius: 50%;
    font-size: 10px;
}

.birthday-menu {
    min-width: 250px;
}

.birthday-header {
    background: #ff6b6b;
    color: white;
    font-weight: bold;
    text-align: center;
    padding: 10px;
    margin: -1px;
    border-radius: 6px 6px 0 0;
}

.birthday-item {
    padding: 8px 12px;
    border-bottom: 1px solid #f0f0f0;
    display: flex;
    align-items: center;
    gap: 8px;
}

.birthday-item small {
    color: #666;
    margin-left: auto;
}

/* LOGOUT */
.logout{
    color: red;
}

/* MAIN CONTENT FIX */
.main-content{
    margin-left: 131px;
    /* padding-top: 80px; */
    position: relative;
    z-index: 1;
}

/* UPLOAD PROGRESS */
.upload-progress{
    position: fixed;
    top: 70px;
    right: 20px;
    background: #fff;
    border: 1px solid #ddd;
    border-radius: 6px;
    padding: 15px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    z-index: 10002;
    display: none;
    min-width: 250px;
}

.progress-bar{
    width: 100%;
    height: 6px;
    background: #f0f0f0;
    border-radius: 3px;
    overflow: hidden;
    margin-top: 8px;
}

.progress-fill{
    height: 100%;
    background: #28a745;
    width: 0%;
    transition: width 0.3s;
}

/* Header right list */
.header-right li {
    list-style: none;
}

/* Salary Calculator & PF Form links */
.header-right li a {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 8px 14px;
    border-radius: 8px;
    font-size: 14px;
    font-weight: 600;
    text-decoration: none;
    transition: all 0.3s ease;
    white-space: nowrap;
}

/* Salary Calculator style */
.header-right li a .fa-calculator {
    color: #0d6efd;
}
.header-right li a[href*="salary"] {
    background: #eef5ff;
    color: #0d6efd;
    border: 1px solid #d6e4ff;
}
.header-right li a[href*="salary"]:hover {
    background: #0d6efd;
    color: #fff;
    box-shadow: 0 6px 15px rgba(13,110,253,0.3);
}

/* PF Form style */
.header-right li a .fa-file-invoice {
    color: #198754;
}
.header-right li a[href*="pf"] {
    background: #f4fff8;
    color: #198754;
    border: 1px solid #c9f1dc;
}
.header-right li a[href*="pf"]:hover {
    background: #198754;
    color: #fff;
    box-shadow: 0 6px 15px rgba(25,135,84,0.3);
}

/* Hover icon color fix */
.header-right li a:hover i {
    color: #fff;
}

/* Excel upload button */
#excelUploadBtn {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 8px 12px;
    border-radius: 8px;
    background: #e9f7ef;
    border: 1px solid #b7e4c7;
    color: #198754;
    font-size: 14px;
    cursor: pointer;
    transition: all 0.3s ease;
}

/* Excel icon */
#excelUploadBtn .fa-file-excel {
    font-size: 18px;
    color: #198754;
}

/* Dropdown arrow */
#excelUploadBtn .fa-chevron-down {
    font-size: 12px;
    opacity: 0.7;
}

/* Hover effect */
#excelUploadBtn:hover {
    background: #198754;
    color: #fff;
    box-shadow: 0 6px 15px rgba(25,135,84,0.35);
    transform: translateY(-1px);
}

/* Hover icon color */
#excelUploadBtn:hover i {
    color: #fff;
}

/* Active click */
#excelUploadBtn:active {
    transform: scale(0.97);
}

/* ADD EMPLOYEE BUTTON */
.header-icon[href*="employee/create"] {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 40px;
    height: 40px;
    border-radius: 8px;
    background: #e8f5e8;
    border: 1px solid #c3e6c3;
    color: #198754;
    text-decoration: none;
    transition: all 0.3s ease;
    margin-left: 10px;
}

.header-icon[href*="employee/create"]:hover {
    background: #198754;
    color: #fff;
    box-shadow: 0 4px 12px rgba(25,135,84,0.3);
    transform: translateY(-1px);
}

.header-icon[href*="employee/create"] i {
    font-size: 16px;
}


</style>
<style>
    /* GLOBAL SEARCH */
.global-search {
    position: relative;
    width: 300px;
}

.global-search input {
    width: 100%;
    padding: 10px 40px 10px 40px;
    border-radius: 20px;
    border: 2px solid #e1e5e9;
    font-size: 14px;
    transition: all 0.3s ease;
    background: #f8f9fa;
}

.global-search input:focus {
    outline: none;
    border-color: #ff9900;
    background: #fff;
    box-shadow: 0 2px 8px rgba(255, 153, 0, 0.2);
}

.search-icon {
    position: absolute;
    left: 12px;
    top: 50%;
    transform: translateY(-50%);
    color: #666;
    font-size: 16px;
}

.global-search-results {
    position: absolute;
    top: 50px;
    left: 0;
    width: 100%;
    background: #fff;
    border: 1px solid #ddd;
    border-radius: 12px;
    box-shadow: 0 8px 25px rgba(0,0,0,0.15);
    max-height: 400px;
    overflow-y: auto;
    display: none;
    z-index: 10005;
}

.search-result-item {
    padding: 12px 16px;
    cursor: pointer;
    border-bottom: 1px solid #f1f3f4;
    display: flex;
    align-items: center;
    gap: 12px;
    transition: background 0.2s ease;
}

.search-result-item:hover {
    background: #f8f9fa;
}

.search-result-item:last-child {
    border-bottom: none;
}

.search-result-avatar {
    width: 30px;
    height: 36px;
    border-radius: 50%;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-weight: 600;
    font-size: 14px;
}

.search-result-info {
    flex: 1;
}

.search-result-name {
    font-weight: 600;
    color: #1a1a1a;
    margin-bottom: 2px;
}

.search-result-number {
    font-size: 13px;
    color: #666;
}

.search-result-type {
    font-size: 11px;
    background: #e3f2fd;
    color: #1976d2;
    padding: 2px 6px;
    border-radius: 10px;
    font-weight: 500;
}

.search-result-page {
    font-size: 11px;
    color: #666;
    margin-top: 2px;
}

.search-no-results {
    padding: 20px;
    text-align: center;
    color: #666;
    font-style: italic;
}


.header-icon[href*="employee/create"] {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
    padding: 8px 14px;
    border-radius: 10px;
    background: linear-gradient(135deg, #198754, #20c997);
    color: #fff;
    text-decoration: none;
    font-weight: 600;
    font-size: 14px;
    transition: all 0.3s ease;
    box-shadow: 0 4px 12px rgba(25,135,84,0.25);
    position: relative;
    overflow: hidden;
}

/* Icon size */
.header-icon[href*="employee/create"] i {
    font-size: 15px;
}

/* Hover effect */
.header-icon[href*="employee/create"]:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(25,135,84,0.35);
    background: linear-gradient(135deg, #157347, #1aa179);
}

/* Active click */
.header-icon[href*="employee/create"]:active {
    transform: scale(0.96);
}

/* ===============================
   GLOBAL STATUS POPUP NOTIFICATION
================================ */
.status-popup {
    position: fixed;
    top: 20px;
    right: 20px;
    background: #fff;
    border-radius: 12px;
    box-shadow: 0 8px 25px rgba(0,0,0,0.15);
    padding: 20px;
    min-width: 300px;
    z-index: 10000;
    transform: translateX(400px);
    opacity: 0;
    transition: all 0.4s ease;
    border-left: 4px solid #28a745;
}

.status-popup.show {
    transform: translateX(0);
    opacity: 1;
}

.status-popup.success {
    border-left-color: #28a745;
}

.status-popup.error {
    border-left-color: #dc3545;
}

.status-popup.warning {
    border-left-color: #ffc107;
}

.status-popup-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 10px;
}

.status-popup-title {
    font-weight: 600;
    color: #333;
    font-size: 16px;
    display: flex;
    align-items: center;
    gap: 8px;
}

.status-popup-close {
    background: none;
    border: none;
    font-size: 18px;
    color: #666;
    cursor: pointer;
    padding: 0;
    width: 24px;
    height: 24px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 50%;
    transition: all 0.2s ease;
}

.status-popup-close:hover {
    background: #f0f0f0;
    color: #333;
}

.status-popup-message {
    color: #666;
    font-size: 14px;
    line-height: 1.4;
}

.status-popup-details {
    margin-top: 8px;
    padding: 8px 12px;
    background: #f8f9fa;
    border-radius: 6px;
    font-size: 13px;
    color: #555;
}


</style>

<script>
// Global function to show status popup notification
function showStatusPopup(type, title, message, details = null) {
    // Remove existing popup if any
    const existingPopup = document.querySelector('.status-popup');
    if (existingPopup) {
        existingPopup.remove();
    }
    
    // Create popup element
    const popup = document.createElement('div');
    popup.className = `status-popup ${type}`;
    
    // Get appropriate icon
    let icon = '';
    switch(type) {
        case 'success':
            icon = '<i class="fa-solid fa-check-circle" style="color: #28a745;"></i>';
            break;
        case 'error':
            icon = '<i class="fa-solid fa-exclamation-circle" style="color: #dc3545;"></i>';
            break;
        case 'warning':
            icon = '<i class="fa-solid fa-exclamation-triangle" style="color: #ffc107;"></i>';
            break;
    }
    
    popup.innerHTML = `
        <div class="status-popup-header">
            <div class="status-popup-title">
                ${icon}
                ${title}
            </div>
            <button class="status-popup-close" onclick="this.parentElement.parentElement.remove()">
                <i class="fa-solid fa-times"></i>
            </button>
        </div>
        <div class="status-popup-message">${message}</div>
        ${details ? `<div class="status-popup-details">${details}</div>` : ''}
    `;
    
    // Add to body
    document.body.appendChild(popup);
    
    // Show with animation
    setTimeout(() => {
        popup.classList.add('show');
    }, 100);
    
    // Auto remove after 5 seconds
    setTimeout(() => {
        if (popup.parentElement) {
            popup.classList.remove('show');
            setTimeout(() => {
                if (popup.parentElement) {
                    popup.remove();
                }
            }, 400);
        }
    }, 5000);
}
</script>

<header class="top-header">
    <div class="header-left">
        <!-- MENU -->
        <!-- <button class="menu-btn" id="menuToggle">
            <i class="fa-solid fa-bars"></i>
        </button> -->

        <!-- EXCEL UPLOAD -->
        <div class="dropdown">
            <input type="file" id="excelFileInput" accept=".xlsx,.xls,.csv" style="display: none;">
            <button class="header-icon dropdown-btn" id="excelUploadBtn" title="Upload Excel">
    <i class="fa-solid fa-file-excel"></i>
    <i class="fa-solid fa-chevron-down"></i>
</button>

        

            
            <ul class="dropdown-menu" id="excelMenu" style="padding: 10px; width: 260px;">

<!-- Upload Excel -->
<li onclick="selectExcelFile(event)" style="cursor: pointer; padding: 6px 10px;">
    <i class="fa-solid fa-file-excel" style="color: #28a745;"></i>
    Upload Excel File
</li>

<li style="font-size: 12px; color: #666; padding: 5px 10px;">
    Supported: .xlsx, .xls, .csv
</li>

<li><hr class="dropdown-divider"></li>

<!-- Manual Entry -->
<li onclick="toggleManualEntry(event)" style="cursor: pointer; padding: 6px 10px;">
    <i class="fa-solid fa-user-plus" style="color: #007bff;"></i>
    Add Manual Entry
</li>

<li id="manualEntryForm" style="display: none; padding: 10px; background: #f8f9fa; border-radius: 4px; margin: 5px;">
    <input type="text" id="manualName" placeholder="Enter Name" class="form-control form-control-sm mb-2">
    <input type="tel"
       id="manualNumber"
       placeholder="Enter Number"
       maxlength="10"
       pattern="[0-9]{10}"
       oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0,10);"
       class="form-control form-control-sm mb-2">

    <button onclick="saveManualEntry()" class="btn btn-primary btn-sm w-100">Save Lead</button>
</li>

<li><hr class="dropdown-divider"></li>

<!-- Platform Select -->
<li style="padding: 5px 10px;">
    <label style="font-size: 12px; color: #555;">Source / Platform</label>
    <select class="form-select form-select-sm" id="platformSelect">
        <option value="">-- Select Platform --</option>
        <option value="workindia">WorkIndia</option>
        <option value="indeed">Indeed</option>
        <option value="apna_job">Apna Job</option>
        <option value="naukri">Naukri</option>
        <option value="reference">Reference</option>
        <option value="olx">OLX</option>
    </select>
</li>


<!-- Field Select -->
<li style="padding: 5px 10px;">
    <label style="font-size: 12px; color: #555;">Select Field</label>
    <select class="form-select form-select-sm" id="roleSelect">
        <option value="">-- Select Role --</option>
        <option value="python">Python</option>
        <option value="python_intern">Python Intern</option>
        <option value="php">PHP</option>
        <option value="php_intern">PHP Intern</option>
        <option value="frontend">Frontend</option>
        <option value="leads_constent">Leads Consistent</option>
        <option value="manager">Manager</option>
        <option value="team_leader">Team Leader</option>
        <option value="hr">HR</option>
        <option value="hr_intern">HR Intern</option>
        <option value="office_boy">Office Boy</option>
        <option value="digital_marketing">Digital Marketing</option>
        <option value="admin">Admin</option>
        <option value="tele_caller">Tele Caller</option>
        <option value="receptionist">Receptionist</option>
    </select>
</li>

</ul>

        </div>
        
    </div>
    <!-- GLOBAL SEARCH -->
<div class="global-search">
    <i class="fa-solid fa-search search-icon"></i>
    <input type="text" id="globalSearchInput"
           placeholder="Search employees by name or mobile number...">
    <div id="globalSearchResults" class="global-search-results"></div>
</div>



    <div class="header-right">
    <a href="{{ route('admin.employee.create') }}" class="header-icon" title="Add Employee">
    <i class="fa-solid fa-user-plus"></i>
    
</a>
    <!-- TEST BUTTON -->
    <!-- <button onclick="alert('Test works!')" style="background: red; color: white; padding: 10px;">TEST</button> -->
    
    <!-- SALARY CALCULATOR LINK -->
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


       

        <!-- NOTIFICATION -->
        <div class="dropdown">
            <button class="header-icon dropdown-btn" id="notifBtn">
                <i class="fa-regular fa-bell"></i>
                <span class="badge" id="notifBadge">0</span>
            </button>

            <ul class="dropdown-menu" id="notifMenu" style="width: 300px; max-height: 400px; overflow-y: auto;">
                <li style="padding: 10px; border-bottom: 1px solid #eee; font-weight: bold; background: #f8f9fa;">
                    <div class="d-flex justify-content-between align-items-center">
                        <span>Notifications</span>
                        <button onclick="markAllAsRead()" class="btn btn-sm btn-link p-0" style="font-size: 12px;">Mark all read</button>
                    </div>
                </li>
                <div id="notificationsList">
                    <li style="padding: 20px; text-align: center; color: #666;">Loading...</li>
                </div>
            </ul>
        </div>
        
        <!-- BIRTHDAY NOTIFICATION -->
        @if(isset($todayBirthdays) && $todayBirthdays->count() > 0)
        <div class="dropdown">
            <button class="header-icon dropdown-btn birthday-btn" id="birthdayBtn">
                <i class="fa-solid fa-birthday-cake" style="color: #ff6b6b;"></i>
                <span class="badge birthday-badge">{{ $todayBirthdays->count() }}</span>
            </button>

            <ul class="dropdown-menu birthday-menu">
                <li class="birthday-header">🎉 Today's Birthdays</li>
                @foreach($todayBirthdays as $employee)
                <li class="birthday-item">
                    <i class="fa-solid fa-gift" style="color: #ff6b6b;"></i>
                    {{ $employee->full_name }}
                    <small>({{ $employee->department }})</small>
                </li>
                @endforeach
            </ul>
        </div>
        @endif
        
        <!-- BILLS NOTIFICATION -->
        <div class="dropdown" id="billsDropdown" style="display: none;">
            <button class="header-icon dropdown-btn bills-btn" id="billsBtn">
                <i class="fa-solid fa-file-invoice" style="color: #f39c12;"></i>
                <span class="badge bills-badge" id="billsBadge">0</span>
            </button>

            <ul class="dropdown-menu bills-menu" id="billsMenu">
                <li class="bills-header">💰 Bills Due Today</li>
                <div id="billsContent">
                    <!-- Bills will be loaded here -->
                </div>
            </ul>
        </div>

        <!-- USER -->
        <div class="dropdown">
            <button class="user-dropdown dropdown-btn">
                <img src="https://i.pravatar.cc/40">
                <span>Admin</span>
                <i class="fa-solid fa-chevron-down"></i>
            </button>

            <ul class="dropdown-menu">
                <li><a href="{{ route('admin.profile') }}"><i class="fa-solid fa-user"></i> Profile</a></li>
                <li><a href="{{ route('admin.settings') }}"><i class="fa-solid fa-cog"></i> Settings</a></li>
                <li class="logout">
                    <form action="{{ route('logout') }}" method="POST" style="display: inline;">
                        @csrf
                        <button type="submit" style="background: none; border: none; color: inherit; cursor: pointer; width: 100%; text-align: left; padding: 0;">
                            <i class="fa-solid fa-sign-out-alt"></i> Logout
                        </button>
                    </form>
                </li>
            </ul>
        </div>

    </div>
</header>


   





<style>
/* User dropdown links styling */
.dropdown-menu li a {
    display: block;
    padding: 8px 15px;
    color: #333;
    text-decoration: none;
    transition: all 0.3s ease;
}

.dropdown-menu li a:hover {
    background-color: #f8f9fa;
    color: #667eea;
}

.dropdown-menu li a i {
    margin-right: 8px;
    width: 16px;
}

.dropdown-menu li.logout button {
    padding: 8px 15px;
    font-size: 14px;
}

.dropdown-menu li.logout button:hover {
    background-color: #dc3545;
    color: white;
}


</style>

<!-- Upload Progress -->
<div class="upload-progress" id="uploadProgress">
    <div style="display: flex; align-items: center; gap: 8px;">
        <i class="fa-solid fa-file-excel" style="color: #28a745;"></i>
        <span id="uploadText">Uploading Excel...</span>
    </div>
    <div class="progress-bar">
        <div class="progress-fill" id="progressFill"></div>
    </div>
</div>
<script>
document.getElementById('excelMenu').addEventListener('click', function (e) {
    e.stopPropagation();   // 🔥 yahi main fix hai
});
</script>


<script>
/* ---------------- DROPDOWN FIX ---------------- */
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.dropdown-btn').forEach(btn => {
        btn.addEventListener('click', function (e) {
            e.preventDefault();
            e.stopPropagation();

            // Close all other dropdowns
            document.querySelectorAll('.dropdown').forEach(d => {
                if (d !== this.parentElement) d.classList.remove('open');
            });

            // Toggle current dropdown
            this.parentElement.classList.toggle('open');
        });
    });

    // Close dropdowns when clicking outside
    document.addEventListener('click', () => {
        document.querySelectorAll('.dropdown').forEach(d => d.classList.remove('open'));
    });

    // Prevent dropdown menu from closing when clicking inside
    document.querySelectorAll('.dropdown-menu').forEach(menu => {
        menu.addEventListener('click', e => {
            e.stopPropagation();
        });
    });
});

/* ---------------- BIRTHDAY AUTO POPUP ---------------- */
@if(isset($todayBirthdays) && $todayBirthdays->count() > 0)
document.addEventListener('DOMContentLoaded', function() {
    // Auto show birthday notification after 2 seconds
    setTimeout(function() {
        const birthdayDropdown = document.querySelector('.birthday-btn').parentElement;
        birthdayDropdown.classList.add('open');
        
        // Auto hide after 5 seconds
        setTimeout(function() {
            birthdayDropdown.classList.remove('open');
        }, 5000);
    }, 2000);
});
@endif



/* ---------------- BILLS AUTO POPUP ---------------- */
document.addEventListener('DOMContentLoaded', function() {
    checkDueBillsForHeader();
});



function checkDueBillsForHeader() {
    fetch('/admin/bills/due-today')
        .then(response => response.json())
        .then(data => {
            if (data.bills && data.bills.length > 0) {
                showBillsInHeader(data.bills);
            }
        })
        .catch(error => console.error('Error:', error));
}

// Load notifications
function loadNotifications() {
    fetch('/admin/notifications/unread')
        .then(response => response.json())
        .then(data => {
            updateNotificationBadge(data.count);
            displayNotifications(data.notifications);
        })
        .catch(error => console.error('Error:', error));
}

function updateNotificationBadge(count) {
    const badge = document.getElementById('notifBadge');
    badge.textContent = count;
    badge.style.display = count > 0 ? 'inline' : 'none';
}

function displayNotifications(notifications) {
    const list = document.getElementById('notificationsList');
    
    if (notifications.length === 0) {
        list.innerHTML = '<li style="padding: 20px; text-align: center; color: #666;">No new notifications</li>';
        return;
    }
    
    list.innerHTML = notifications.map(notif => `
        <li style="padding: 10px; border-bottom: 1px solid #eee; cursor: pointer;" onclick="markAsRead(${notif.id})">
            <div style="font-size: 14px; font-weight: 500;">${notif.title}</div>
            <div style="font-size: 12px; color: #666; margin-top: 2px;">${notif.message}</div>
            <div style="font-size: 11px; color: #999; margin-top: 4px;">${formatTime(notif.created_at)}</div>
        </li>
    `).join('');
}

function markAsRead(id) {
    fetch(`/admin/notifications/${id}/read`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    }).then(() => loadNotifications());
}

function markAllAsRead() {
    fetch('/admin/notifications/read-all', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    }).then(() => loadNotifications());
}

function formatTime(dateString) {
    const date = new Date(dateString);
    const now = new Date();
    const diff = now - date;
    const minutes = Math.floor(diff / 60000);
    
    if (minutes < 1) return 'Just now';
    if (minutes < 60) return `${minutes}m ago`;
    if (minutes < 1440) return `${Math.floor(minutes / 60)}h ago`;
    return date.toLocaleDateString();
}

// Load notifications on page load and refresh every 30 seconds
document.addEventListener('DOMContentLoaded', function() {
    loadNotifications();
    setInterval(loadNotifications, 30000);
});lsInHeader(data.bills);
            }
        })
        .catch(error => console.error('Error:', error));
}

function showBillsInHeader(bills) {
    const dropdown = document.getElementById('billsDropdown');
    const badge = document.getElementById('billsBadge');
    const content = document.getElementById('billsContent');
    
    // Show dropdown and set badge count
    dropdown.style.display = 'block';
    badge.textContent = bills.length;
    
    // Generate bills content
    let billsHtml = '';
    bills.forEach(function(bill) {
        billsHtml += `<li class="bills-item">
            <div class="bills-item-info">
                <strong>${bill.bill_type}</strong>
                <small>₹${parseFloat(bill.amount).toFixed(2)} - Due: ${new Date(bill.due_date).toLocaleDateString('en-GB')}</small>
            </div>
            <button class="btn btn-success btn-sm" onclick="markBillAsPaidFromHeader(${bill.id})">
                <i class="fa-solid fa-check"></i>
            </button>
        </li>`;
    });
    
    content.innerHTML = billsHtml;
    
    // Auto show popup after 3 seconds (after birthday popup)
    setTimeout(function() {
        dropdown.classList.add('open');
        
        // Auto hide after 6 seconds
        setTimeout(function() {
            dropdown.classList.remove('open');
        }, 6000);
    }, 3000);
}

function markBillAsPaidFromHeader(billId) {
    fetch(`/admin/bills/${billId}/mark-paid`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Refresh bills in header
            checkDueBillsForHeader();
        } else {
            alert('Error marking bill as paid');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error marking bill as paid');
    });
}

/* ---------------- SELECT EXCEL ---------------- */
function selectExcelFile(e) {
    e.stopPropagation();

    const role = document.getElementById('roleSelect').value;
    if (!role) {
        alert('Please select role first!');
        return;
    }

    document.getElementById('excelFileInput').click();
}

/* ---------------- PLATFORM FILTERING ---------------- */
document.addEventListener('DOMContentLoaded', function() {
    const platformSelect = document.getElementById('platformSelect');
    const roleSelect = document.getElementById('roleSelect');
    
    function filterEmployees() {
        const selectedPlatform = platformSelect.value;
        const selectedRole = roleSelect.value;
        
        if (selectedPlatform || selectedRole) {
            let url = '/admin/employees?';
            let params = [];
            
            if (selectedRole) params.push(`role=${selectedRole}`);
            if (selectedPlatform) params.push(`platform=${selectedPlatform}`);
            
            url += params.join('&');
            window.location.href = url;
        }
    }
    
    if (platformSelect) {
        platformSelect.addEventListener('change', filterEmployees);
    }
    
    if (roleSelect) {
        roleSelect.addEventListener('change', filterEmployees);
    }
});

/* ---------------- UPLOAD EXCEL ---------------- */
document.getElementById('excelFileInput').addEventListener('change', function (e) {
    const file = e.target.files[0];
    const role = document.getElementById('roleSelect').value;
    const platform = document.getElementById('platformSelect').value;
    if (!file || !role) return;

    const formData = new FormData();
    formData.append('excel_file', file);
    formData.append('role', role);
    formData.append('platform', platform);
    formData.append('_token', '{{ csrf_token() }}');

    const progress = document.getElementById('uploadProgress');
    const fill = document.getElementById('progressFill');
    const text = document.getElementById('uploadText');

    progress.style.display = 'block';
    fill.style.width = '0%';
    text.innerText = 'Uploading Excel...';

    const xhr = new XMLHttpRequest();

    xhr.upload.onprogress = e => {
        if (e.lengthComputable) {
            fill.style.width = (e.loaded / e.total) * 100 + '%';
        }
    };

    xhr.onload = () => {
        if (xhr.status === 200) {
            fill.style.width = '100%';
            text.innerText = 'Upload Complete!';
            setTimeout(() => {
                progress.style.display = 'none';
                document.querySelector('.dropdown.open').classList.remove('open');
            }, 2000);
        } else {
            text.innerText = 'Upload Failed!';
            fill.style.background = '#dc3545';
        }
    };

    xhr.send(formData);
});

/* ---------------- MANUAL ENTRY ---------------- */
function toggleManualEntry(e) {
    e.stopPropagation();
    const form = document.getElementById('manualEntryForm');
    form.style.display = form.style.display === 'none' ? 'block' : 'none';
}

function saveManualEntry() {
    const name = document.getElementById('manualName').value.trim();
    const number = document.getElementById('manualNumber').value.trim();
    const role = document.getElementById('roleSelect').value;
    const platform = document.getElementById('platformSelect').value;

    if (!name || !number) {
        alert('Please enter both name and number!');
        return;
    }
      // ✅ Only 10 digit number allowed
      if (!/^[0-9]{10}$/.test(number)) {
        alert('Mobile number must be exactly 10 digits!');
        return;
    }
    if (!role) {
        alert('Please select role first!');
        return;
    }

    const formData = new FormData();
    formData.append('name', name);
    formData.append('number', number);
    formData.append('role', role);
    formData.append('platform', platform);
    formData.append('_token', '{{ csrf_token() }}');

    fetch('/save-manual-lead', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Lead saved successfully!');
            document.getElementById('manualName').value = '';
            document.getElementById('manualNumber').value = '';
            document.getElementById('manualEntryForm').style.display = 'none';
        } else {
            alert('Error saving lead!');
        }
    })
    .catch(() => alert('Network error!'));
}
</script>
<script>
const searchInput = document.getElementById('globalSearchInput');
const searchResults = document.getElementById('globalSearchResults');
let searchTimeout;

searchInput.addEventListener('input', function () {
    let query = this.value.trim();

    // Clear previous timeout
    clearTimeout(searchTimeout);

    if (query.length < 2) {
        searchResults.style.display = 'none';
        return;
    }

    // Add debounce for better performance
    searchTimeout = setTimeout(() => {
        fetch(`/admin/global-search?q=${encodeURIComponent(query)}`)
            .then(res => res.json())
            .then(data => {
                searchResults.innerHTML = '';

                if (data.length > 0) {
                    data.forEach(item => {
                        let div = document.createElement('div');
                        div.classList.add('search-result-item');

                        // Get initials for avatar
                        let initials = item.name.split(' ').map(n => n[0]).join('').toUpperCase().substring(0, 2);

                        div.innerHTML = `
                            <div class="search-result-avatar">${initials}</div>
                            <div class="search-result-info">
                                <div class="search-result-name">${item.name}</div>
                                <div class="search-result-number">${item.number || 'No number'}</div>
                                <div class="search-result-page">📍 ${item.page} - <span class="search-result-type">${item.type}</span></div>
                            </div>
                        `;

                        div.onclick = function () {
                            window.location.href = item.url;
                        };

                        searchResults.appendChild(div);
                    });

                    searchResults.style.display = 'block';
                } else {
                    searchResults.innerHTML = `<div class="search-no-results">No employees found for "${query}"</div>`;
                    searchResults.style.display = 'block';
                }
            })
            .catch(error => {
                console.error('Search error:', error);
                searchResults.innerHTML = `<div class="search-no-results">Search error occurred</div>`;
                searchResults.style.display = 'block';
            });
    }, 300);
});

// Hide results when clicking outside
document.addEventListener('click', function (e) {
    if (!e.target.closest('.global-search')) {
        searchResults.style.display = 'none';
    }
});

// Show results when focusing on input if there's content
searchInput.addEventListener('focus', function() {
    if (this.value.length >= 2 && searchResults.innerHTML) {
        searchResults.style.display = 'block';
    }
});
</script>




