@extends('auth.layouts.app')

@section('title', 'Employee Shift Management')
<style>
    .container-fluid{
    margin-top: 60px !important;
    padding-left: 130px !important;
}
</style>

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <!-- <div class="py-3 mb-4" style="background: linear-gradient(135deg, #2c3e50 0%, #3498db 100%); border-radius: 0 0 15px 15px;">
        <div class="container">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="text-white mb-1"><i class="fas fa-calendar-alt me-2"></i>Employee Shift Management</h1>
                    <p class="text-light mb-0">Manage, add, and export employee shifts</p>
                </div>
                <div class="text-end">
                    <div class="fw-bold text-white">HR Dashboard</div>
                    <div class="small text-light">Today: <span id="current-date">{{ date('F d, Y') }}</span></div>
                </div>
            </div>
        </div>
    </div>
     -->
    <div class="container">
        <!-- Search and Filter Section -->
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-body">
                <h5 class="card-title text-primary mb-3"><i class="fas fa-search me-2"></i>Search & Filter</h5>
                <div class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label">Employee Name</label>
                        <select class="form-select" id="employee-filter">
                            <option value="">All Employees</option>
                            @foreach($employees as $employee)
                                <option value="{{ $employee->id }}">
                                    {{ $employee->first_name }} {{ $employee->last_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Start Time</label>
                        <select class="form-select" id="start-time-filter">
                            <option value="">Any Time</option>
                            <option>06:00</option>
                            <option>08:00</option>
                            <option>09:00</option>
                            <option>14:00</option>
                            <option>16:00</option>
                            <option>18:00</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Shift Type</label>
                        <select class="form-select" id="shift-type-filter">
                            <option value="">All Types</option>
                            <option value="Day">Day</option>
                            <option value="Night">Night</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Status</label>
                        <select class="form-select" id="status-filter">
                            <option value="">All Status</option>
                            <option value="Scheduled">Scheduled</option>
                            <option value="Completed">Completed</option>
                            <option value="Cancelled">Cancelled</option>
                        </select>
                    </div>
                    <div class="col-md-3 d-flex align-items-end">
                        <button class="btn btn-primary w-100" id="search-btn">
                            <i class="fas fa-search me-2"></i>Search
                        </button>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Add New Shift Form -->
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-body">
                <h5 class="card-title text-primary mb-3"><i class="fas fa-plus-circle me-2"></i>Add New Shift</h5>
                <form id="shift-form" method="POST" action="{{ route('admin.shifts.store') }}">
                    @csrf
                    <input type="hidden" id="edit-shift-id" name="edit_id" value="">
                    <div class="row g-3">
                        <div class="col-md-3">
                            <label class="form-label">Employee Name *</label>
                            <select class="form-select" id="employee-name" name="employee_id" required>
                                <option value="" selected disabled>Select Employee</option>
                                @foreach($employees as $employee)
                                    <option value="{{ $employee->id }}">
                                        {{ $employee->first_name }} {{ $employee->last_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Start Time *</label>
                            <input type="time" class="form-control" id="start-time" name="start_time" value="09:00" required>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">End Time *</label>
                            <input type="time" class="form-control" id="end-time" name="end_time" value="17:00" required>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Shift Type *</label>
                            <select class="form-select" id="shift-type" name="shift_type" required>
                                <option value="Day" selected>Day</option>
                                <option value="Night">Night</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Shift Date *</label>
                            <input type="date" class="form-control" id="shift-date" name="shift_date" value="{{ date('Y-m-d') }}" required>
                        </div>
                    </div>
                    <div class="row g-3 mt-3">
                        <div class="col-md-3">
                            <label class="form-label">Break Start</label>
                            <input type="time" class="form-control" id="break-start" name="break_start" value="12:30">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Break End</label>
                            <input type="time" class="form-control" id="break-end" name="break_end" value="13:00">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Assigned By *</label>
                            <select class="form-select" id="assigned-by" name="assigned_by" required>
                                <option value="" selected disabled>Select Assigner</option>
                                <option value="Admin">Admin</option>
                                <option value="Manager">Manager</option>
                                <option value="Supervisor">Supervisor</option>
                            </select>
                        </div>
                        <div class="col-md-3 d-flex align-items-end">
                            <button type="submit" class="btn btn-success w-100" id="submit-btn">
                                <i class="fas fa-calendar-plus me-2"></i>Add Shift
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        
        <!-- Shifts Table -->
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white border-0 py-3">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 text-primary"><i class="fas fa-calendar-day me-2"></i>Employee Shifts</h5>
                    <div>
                        <button class="btn btn-sm btn-primary me-2" id="export-excel">
                            <i class="fas fa-file-excel me-1"></i>Export to Excel
                        </button>
                        <button class="btn btn-sm btn-secondary" id="email-shifts" data-bs-toggle="modal" data-bs-target="#emailModal">
                            <i class="fas fa-envelope me-1"></i>Email Shifts
                        </button>
                    </div>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0" id="shifts-table">
                        <thead class="table-light">
                            <tr>
                                <th>Employee Name</th>
                                <th>Start Time</th>
                                <th>End Time</th>
                                <th>Shift Type</th>
                                <th>Shift Date</th>
                                <th>Total Hours</th>
                                <th>Status</th>
                                <th>Assigned By</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody id="shifts-table-body">
                            <tr id="no-shifts-row">
                                <td colspan="9" class="text-center text-muted py-4">Loading shifts...</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Email Modal -->
<div class="modal fade" id="emailModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Email Shifts Schedule</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label">Recipient Email</label>
                    <input type="email" class="form-control" id="recipient-email" value="hr@company.com">
                </div>
                <div class="mb-3">
                    <label class="form-label">Subject</label>
                    <input type="text" class="form-control" id="email-subject" value="Employee Shift Schedule">
                </div>
                <div class="mb-3">
                    <label class="form-label">Message</label>
                    <textarea class="form-control" id="email-message" rows="4">Please find attached the employee shift schedule for the upcoming week.</textarea>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="include-excel">
                    <label class="form-check-label" for="include-excel">
                        Include Excel attachment
                    </label>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="send-email-btn">Send Email</button>
            </div>
        </div>
    </div>
</div>

<!-- Toast Notification -->
<div class="position-fixed bottom-0 end-0 p-3" style="z-index: 11">
    <div id="successToast" class="toast align-items-center text-bg-success border-0" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="d-flex">
            <div class="toast-body">
                <i class="fas fa-check-circle me-2"></i>
                <span id="toast-message">Shift added successfully!</span>
            </div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .badge.bg-warning {
        background-color: #f39c12 !important;
        color: white;
    }
    
    .badge.bg-dark {
        background-color: #2c3e50 !important;
        color: white;
    }
    
    .table > :not(caption) > * > * {
        padding: 0.75rem 0.75rem;
    }
    
    .table thead th {
        font-weight: 600;
        background-color: #f8f9fa;
    }
    
    .btn-outline-primary {
        border-color: #3498db;
        color: #3498db;
    }
    
    .btn-outline-primary:hover {
        background-color: #3498db;
        color: white;
    }
    
    .btn-outline-danger {
        border-color: #e74c3c;
        color: #e74c3c;
    }
    
    .btn-outline-danger:hover {
        background-color: #e74c3c;
        color: white;
    }
</style>
@endpush

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const storeUrl = '{{ route("admin.shifts.store") }}';
        const dataUrl = '{{ route("admin.shifts.data") }}';
        const csrfToken = '{{ csrf_token() }}';

        const toastEl = document.getElementById('successToast');
        const toast = toastEl ? new bootstrap.Toast(toastEl) : null;

        function showNotification(message, type = 'success') {
            const msgEl = document.getElementById('toast-message');
            if (msgEl) msgEl.textContent = message;
            if (toastEl) {
                toastEl.classList.remove('text-bg-success', 'text-bg-danger');
                toastEl.classList.add(type === 'success' ? 'text-bg-success' : 'text-bg-danger');
                if (toast) toast.show();
            }
        }

        function formatDate(dateStr) {
            const d = new Date(dateStr);
            return (d.getMonth() + 1) + '/' + d.getDate() + '/' + d.getFullYear();
        }

        function getStatusBadge(status) {
            const classes = { Scheduled: 'bg-success', Completed: 'bg-primary', Cancelled: 'bg-secondary' };
            return '<span class="badge ' + (classes[status] || 'bg-secondary') + '">' + status + '</span>';
        }

        function buildShiftRow(shift) {
            const emp = shift.employee || {};
            const empName = (emp.first_name || '') + ' ' + (emp.last_name || '');
            const badgeClass = shift.shift_type === 'Day' ? 'bg-warning' : 'bg-dark';
            return '<tr data-shift-id="' + shift.id + '">' +
                '<td>' + empName + '</td>' +
                '<td>' + (shift.start_time || '').substring(0, 5) + '</td>' +
                '<td>' + (shift.end_time || '').substring(0, 5) + '</td>' +
                '<td><span class="badge ' + badgeClass + '">' + shift.shift_type + '</span></td>' +
                '<td>' + formatDate(shift.shift_date) + '</td>' +
                '<td>' + shift.total_hours + ' hrs</td>' +
                '<td>' + getStatusBadge(shift.status) + '</td>' +
                '<td>' + (shift.assigned_by || '') + '</td>' +
                '<td><button class="btn btn-sm btn-outline-primary me-1 edit-btn" data-id="' + shift.id + '"><i class="fas fa-edit"></i></button>' +
                '<button class="btn btn-sm btn-outline-danger delete-btn" data-id="' + shift.id + '"><i class="fas fa-trash"></i></button></td>' +
                '</tr>';
        }

        function loadShifts() {
            const params = new URLSearchParams();
            const empFilter = document.getElementById('employee-filter');
            const shiftFilter = document.getElementById('shift-type-filter');
            const statusFilter = document.getElementById('status-filter');
            if (empFilter && empFilter.value) params.set('employee_id', empFilter.value);
            if (shiftFilter && shiftFilter.value) params.set('shift_type', shiftFilter.value);
            if (statusFilter && statusFilter.value) params.set('status', statusFilter.value);

            fetch(dataUrl + (params.toString() ? '?' + params : ''), {
                headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
            })
            .then(r => r.json())
            .then(res => {
                const tbody = document.getElementById('shifts-table-body');
                const noRow = document.getElementById('no-shifts-row');
                if (!tbody) return;
                const data = res.data || [];
                if (data.length === 0) {
                    if (noRow) noRow.innerHTML = '<td colspan="9" class="text-center text-muted py-4">No shifts found</td>';
                    else tbody.innerHTML = '<tr><td colspan="9" class="text-center text-muted py-4">No shifts found</td></tr>';
                    return;
                }
                if (noRow) noRow.remove();
                tbody.innerHTML = data.map(s => buildShiftRow(s)).join('');
            })
            .catch(() => {
                const tbody = document.getElementById('shifts-table-body');
                const noRow = document.getElementById('no-shifts-row');
                if (noRow) noRow.innerHTML = '<td colspan="9" class="text-center text-muted py-4">No shifts found</td>';
                else if (tbody) tbody.innerHTML = '<tr><td colspan="9" class="text-center text-muted py-4">Failed to load shifts</td></tr>';
            });
        }

        loadShifts();

        const form = document.getElementById('shift-form');
        if (form) {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                const editId = document.getElementById('edit-shift-id')?.value;
                const formData = new FormData(form);
                formData.append('_token', csrfToken);
                if (editId) formData.append('_method', 'PUT');
                const btn = document.getElementById('submit-btn');
                if (btn) btn.disabled = true;

                const url = editId ? '{{ url("/admin/shifts") }}/' + editId : storeUrl;
                const method = editId ? 'POST' : 'POST';

                fetch(url, {
                    method: method,
                    body: formData,
                    headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
                })
                .then(r => r.json())
                .then(res => {
                    if (btn) btn.disabled = false;
                    if (res.success) {
                        form.reset();
                        const editInput = document.getElementById('edit-shift-id');
                        if (editInput) editInput.value = '';
                        const dateInput = document.getElementById('shift-date');
                        const startInput = document.getElementById('start-time');
                        const endInput = document.getElementById('end-time');
                        const typeSelect = document.getElementById('shift-type');
                        if (dateInput) dateInput.value = '{{ date("Y-m-d") }}';
                        if (startInput) startInput.value = '09:00';
                        if (endInput) endInput.value = '17:00';
                        if (typeSelect) typeSelect.value = 'Day';
                        loadShifts();
                        showNotification(res.message || (editId ? 'Shift updated!' : 'Shift added successfully!'));
                    } else {
                        showNotification(res.message || (res.errors ? JSON.stringify(res.errors) : 'Error'), 'error');
                    }
                })
                .catch(() => {
                    if (btn) btn.disabled = false;
                    showNotification('Error creating shift', 'error');
                });
            });
        }

        document.addEventListener('click', function(e) {
            const editBtn = e.target.closest('.edit-btn');
            const deleteBtn = e.target.closest('.delete-btn');
            if (editBtn) {
                const id = editBtn.dataset.id;
                fetch('{{ url("/admin/shifts") }}/' + id + '/edit', { headers: { 'Accept': 'application/json' } })
                    .then(r => r.json())
                    .then(shift => {
                        const empSelect = document.getElementById('employee-name');
                        const startInput = document.getElementById('start-time');
                        const endInput = document.getElementById('end-time');
                        const typeSelect = document.getElementById('shift-type');
                        const dateInput = document.getElementById('shift-date');
                        const breakStart = document.getElementById('break-start');
                        const breakEnd = document.getElementById('break-end');
                        const assignedSelect = document.getElementById('assigned-by');
                        if (empSelect) empSelect.value = shift.employee_id;
                        if (startInput) startInput.value = (shift.start_time || '').substring(0, 5);
                        if (endInput) endInput.value = (shift.end_time || '').substring(0, 5);
                        if (typeSelect) typeSelect.value = shift.shift_type;
                        if (dateInput) dateInput.value = (shift.shift_date || '').substring(0, 10);
                        if (breakStart) breakStart.value = (shift.break_start || '').substring(0, 5);
                        if (breakEnd) breakEnd.value = (shift.break_end || '').substring(0, 5);
                        if (assignedSelect) assignedSelect.value = shift.assigned_by || '';
                        const editInput = document.getElementById('edit-shift-id');
                        if (editInput) editInput.value = id;
                        showNotification('Edit mode: Update values and click Add Shift to save changes.');
                    });
            }
            if (deleteBtn && confirm('Are you sure you want to delete this shift?')) {
                const id = deleteBtn.dataset.id;
                fetch('{{ url("/admin/shifts") }}/' + id, {
                    method: 'DELETE',
                    headers: { 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json', 'Content-Type': 'application/json' }
                })
                .then(r => r.json())
                .then(res => {
                    if (res.success) {
                        loadShifts();
                        showNotification('Shift deleted successfully!');
                    } else showNotification(res.message || 'Error', 'error');
                })
                .catch(() => showNotification('Error deleting shift', 'error'));
            }
        });

        document.getElementById('search-btn')?.addEventListener('click', loadShifts);

        document.getElementById('export-excel')?.addEventListener('click', function() {
            const table = document.getElementById('shifts-table');
            if (table && typeof XLSX !== 'undefined') {
                const ws = XLSX.utils.table_to_sheet(table);
                const wb = XLSX.utils.book_new();
                XLSX.utils.book_append_sheet(wb, ws, 'Employee Shifts');
                XLSX.writeFile(wb, 'employee_shifts_' + new Date().toISOString().split('T')[0] + '.xlsx');
                showNotification('Excel file downloaded successfully!');
            }
        });

        document.getElementById('send-email-btn')?.addEventListener('click', function() {
            showNotification('Email sent successfully!');
            const modal = bootstrap.Modal.getInstance(document.getElementById('emailModal'));
            if (modal) modal.hide();
        });
    });
</script>
@endpush