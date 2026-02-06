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

/* LOGOUT */
.logout{
    color: red;
}

/* MAIN CONTENT FIX */
.main-content{
    margin-left: 260px;
    padding-top: 80px;
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



</style>

<header class="top-header">
    <div class="header-left">
        <!-- MENU -->
        <button class="menu-btn" id="menuToggle">
            <i class="fa-solid fa-bars"></i>
        </button>

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

    <div class="header-right">

        <!-- COUNTRY SELECT -->
        <div class="dropdown">
            <button class="header-icon dropdown-btn" id="countryBtn">
                <img src="https://flagcdn.com/w20/in.png"> India
                <i class="fa-solid fa-chevron-down"></i>
            </button>

            <ul class="dropdown-menu" id="countryMenu">
                <li onclick="selectCountry('in','India')">
                    <img src="https://flagcdn.com/w20/in.png"> India
                </li>
                <li onclick="selectCountry('us','USA')">
                    <img src="https://flagcdn.com/w20/us.png"> USA
                </li>
                <li onclick="selectCountry('gb','UK')">
                    <img src="https://flagcdn.com/w20/gb.png"> UK
                </li>
                <li onclick="selectCountry('ca','Canada')">
                    <img src="https://flagcdn.com/w20/ca.png"> Canada
                </li>
            </ul>
        </div>

        <!-- NOTIFICATION -->
        <div class="dropdown">
            <button class="header-icon dropdown-btn" id="notifBtn">
                <i class="fa-regular fa-bell"></i>
                <span class="badge">3</span>
            </button>

            <ul class="dropdown-menu">
                <li>New employee added</li>
                <li>Leave request pending</li>
                <li>Payroll updated</li>
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
                <li>Profile</li>
                <li>Settings</li>
                <li class="logout">Logout</li>
            </ul>
        </div>

    </div>
</header>

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
document.querySelectorAll('.dropdown-btn').forEach(btn => {
    btn.addEventListener('click', function (e) {
        e.preventDefault();
        e.stopPropagation();

        document.querySelectorAll('.dropdown').forEach(d => {
            if (d !== this.parentElement) d.classList.remove('open');
        });

        this.parentElement.classList.toggle('open');
    });
});

document.addEventListener('click', () => {
    document.querySelectorAll('.dropdown').forEach(d => d.classList.remove('open'));
});

/* 🔥 Excel menu ke andar click close na ho */
document.getElementById('excelMenu').addEventListener('click', e => {
    e.stopPropagation();
});

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

/* ---------------- UPLOAD EXCEL ---------------- */
document.getElementById('excelFileInput').addEventListener('change', function (e) {
    const file = e.target.files[0];
    const role = document.getElementById('roleSelect').value;
    if (!file || !role) return;

    const formData = new FormData();
    formData.append('excel_file', file);
    formData.append('role', role);
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
            const response = JSON.parse(xhr.responseText);
            if (response.success) {
                text.innerText = `Upload Successful! ${response.count} leads imported.`;
                if (response.duplicates > 0) {
                    text.innerText += ` (${response.duplicates} duplicates skipped)`;
                }
                // Refresh page after 2 seconds to show new data
                setTimeout(() => {
                    window.location.reload();
                }, 2000);
            } else {
                text.innerText = 'Upload Failed: ' + response.message;
            }
        } else {
            text.innerText = 'Upload Failed!';
        }
        
        setTimeout(() => {
            progress.style.display = 'none';
            fill.style.width = '0%';
        }, 3000);
    };

    xhr.onerror = () => {
        text.innerText = 'Upload Error!';
    };

    xhr.open('POST', '{{ route("upload.excel") }}');
    xhr.send(formData);
});
</script>



