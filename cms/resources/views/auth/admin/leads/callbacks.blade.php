@extends('auth.layouts.app')

@section('content')
<div class="main-content">

    <div class="card leads-card">
        <div class="card-header">
            <h4>Callback Leads</h4>
            <a href="{{ route('admin.leads.index') }}" class="btn btn-outline-secondary btn-sm">
                <i class="fa-solid fa-arrow-left me-1"></i> Back to Leads
            </a>
        </div>

        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <div class="table-responsive">
                <table class="leads-table">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Number</th>
                            <th>Role</th>
                            <th>Callback Date</th>
                            <th>Notes</th>
                            <th>WhatsApp</th>
                            <th>Actions</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($callbacks as $callback)
                        <tr>
                            <td>{{ $callback->name }}</td>
                            <td>{{ $callback->number }}</td>
                            <td>{{ $callback->role }}</td>
                            <td>
                                <input type="date" 
                                       class="form-control callback-date" 
                                       data-id="{{ $callback->id }}"
                                       value="{{ $callback->callback_date ? $callback->callback_date->format('Y-m-d') : '' }}">
                            </td>
                            <td>
                                <textarea class="form-control callback-notes" 
                                          data-id="{{ $callback->id }}"
                                          rows="2" 
                                          placeholder="Add notes...">{{ $callback->notes }}</textarea>
                            </td>
                            <td>
                                <a href="https://wa.me/91{{ $callback->number }}" target="_blank" class="whatsapp-btn">
                                    <i class="fa-brands fa-whatsapp"></i>
                                </a>
                            </td>
                            <td>
                                <button class="btn btn-sm btn-success save-callback" data-id="{{ $callback->id }}">
                                    Save
                                </button>
                                <button class="btn btn-sm btn-danger delete-callback" data-id="{{ $callback->id }}">
                                    Delete
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" style="text-align:center;">No callback leads found</td>
                        </tr>
                        @endforelse
                    </tbody>

                </table>
            </div>
        </div>
    </div>

</div>

<style>
    /* ===============================
   GLOBAL RESET (SAFE)
================================ */
* {
    box-sizing: border-box;
}

body {
    margin: 0;
    background: #f4f6f9;
    overflow-x: hidden;
}
.main-content{
   
    margin-top: 60px;
}

/* ===============================
   LAYOUT FIX (SIDEBAR + CONTENT)
================================ */
/* sidebar width = 250px assumed */

.content,
.content-wrapper,
.page-content,
.container-fluid {
    margin-left: 250px !important;
    width: calc(100vw - 250px) !important;
    max-width: calc(100vw - 250px) !important;
    padding: 0 !important;
}

/* ===============================
   PAGE CONTENT
================================ */


/* ===============================
   CARD
================================ */
.leads-card {
    width: 100%;
    background: #fff;
    border-radius: 12px;
    box-shadow: 0 6px 18px rgba(0,0,0,0.08);
    overflow: hidden;
}

/* HEADER */
.leads-card .card-header {
 
    background: #ffffff;
    border-bottom: 1px solid #e6e6e6;
    display: flex;
    align-items: center;
    justify-content: space-between;
}

.leads-card .card-header h4 {
    margin: 0;
    font-size: 18px;
    font-weight: 600;
    color: #333;
}

/* ===============================
   TABLE
================================ */
.table-responsive {
    width: 100%;
    overflow-x: auto;
}

.leads-table {
    width: 100%;
   
    border-collapse: collapse;
}

.leads-table thead th {
    background: #f1f3f5;
    padding: 14px 12px;
    font-size: 13px;
    font-weight: 600;
    color: #444;
    text-align: left;
    white-space: nowrap;
}

.leads-table tbody td {
    padding: 14px 12px;
    font-size: 13px;
    color: #333;
    border-bottom: 1px solid #eee;
    vertical-align: middle;
}

.leads-table tbody tr:hover {
    background: #fafafa;
}

/* ===============================
   INPUTS
================================ */
.form-control {
    font-size: 12px;
    padding: 6px 8px;
    border-radius: 6px;
    border: 1px solid #ddd;
}

.callback-notes {
    min-width: 200px;
    resize: vertical;
}

/* ===============================
   WHATSAPP BUTTON
================================ */
.whatsapp-btn {
    width: 36px;
    height: 36px;
    border-radius: 50%;
    background: #25D366;
    color: #fff;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 18px;
    text-decoration: none;
}

.whatsapp-btn:hover {
    background: #1ebe5d;
    color: #fff;
}

/* ===============================
   ACTION BUTTONS
================================ */
.leads-table td:last-child {
    white-space: nowrap;
}

.btn {
    font-size: 12px;
    padding: 6px 10px;
    border-radius: 6px;
    border: none;
}

.btn-success {
    background: #28a745;
    color: #fff;
}

.btn-danger {
    background: #dc3545;
    color: #fff;
}

.btn-outline-secondary {
    background: transparent;
    border: 1px solid #6c757d;
    color: #6c757d;
}

.btn + .btn {
    margin-left: 6px;
}

/* ===============================
   ALERT
================================ */
.alert-success {
    font-size: 13px;
    border-radius: 6px;
}

/* ===============================
   MOBILE VIEW
================================ */
@media (max-width: 992px) {

    .content,
    .content-wrapper,
    .page-content,
    .container-fluid {
        margin-left: 0 !important;
        width: 100vw !important;
        max-width: 100vw !important;
    }

    .main-content {
        padding: 16px;
    }

    .leads-table {
        min-width: 100%;
    }

    .leads-table thead {
        display: none;
    }

    .leads-table tr {
        display: block;
        margin-bottom: 14px;
        background: #fff;
        border-radius: 10px;
        box-shadow: 0 3px 10px rgba(0,0,0,0.06);
        padding: 10px;
    }

    .leads-table td {
        display: flex;
        justify-content: space-between;
        padding: 8px 6px;
        border-bottom: none;
        font-size: 13px;
    }

    .leads-table td:last-child {
        justify-content: flex-start;
        gap: 8px;
    }
}

</style>
<script>
// Save callback functionality
document.querySelectorAll('.save-callback').forEach(btn => {
    btn.addEventListener('click', function() {
        const id = this.dataset.id;
        const date = document.querySelector(`.callback-date[data-id="${id}"]`).value;
        const notes = document.querySelector(`.callback-notes[data-id="${id}"]`).value;
        
        fetch(`/admin/callbacks/${id}`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                callback_date: date,
                notes: notes
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Callback updated successfully!');
            }
        });
    });
});

// Delete callback functionality
document.querySelectorAll('.delete-callback').forEach(btn => {
    btn.addEventListener('click', function() {
        if (confirm('Are you sure you want to delete this callback?')) {
            const id = this.dataset.id;
            
            fetch(`/admin/callbacks/${id}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                }
            });
        }
    });
});
</script>
@endsection