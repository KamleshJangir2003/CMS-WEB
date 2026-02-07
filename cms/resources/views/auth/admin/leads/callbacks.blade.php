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
.main-content {
    margin-left: 250px;              /* sidebar width */
    padding: 20px;
    min-height: 100vh;
    background: #f8f9fa;
    width: calc(100% - 250px);       /* 🔥 KEY FIX */
    box-sizing: border-box;
}

/* CARD */
.leads-card {
    width: 100%;
    max-width: 100%;                 /* ❌ center limit hatao */
    margin: 0;                       /* ❌ auto remove */
    background: #fff;
    border-radius: 12px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.08);
}

/* ALERTS */
.alert {
    padding: 10px 14px;
    border-radius: 6px;
    margin-bottom: 15px;
    font-size: 14px;
}

.alert-success {
    background: #d4edda;
    color: #155724;
    border: 1px solid #c3e6cb;
}


/* CARD HEADER */
.card-header {
    padding: 16px 20px;
    border-bottom: 1px solid #eee;
    background: #f8f9fa;
    border-radius: 12px 12px 0 0;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.card-header h4 {
    margin: 0;
    font-weight: 600;
}

/* TABLE */
.table-responsive {
    overflow-x: auto;
}

.leads-table {
    width: 100%;
    border-collapse: collapse;
}

.leads-table th {
    background: #f1f3f5;
    padding: 14px;
    text-align: left;
    font-weight: 600;
    font-size: 14px;
    white-space: nowrap;
}

.leads-table td {
    padding: 14px;
    border-bottom: 1px solid #eee;
    vertical-align: middle;
    font-size: 14px;
}

.leads-table tr:hover {
    background: #f9fafb;
}

/* WHATSAPP */
.whatsapp-btn {
    background: #25D366;
    color: #fff;
    width: 38px;
    height: 38px;
    border-radius: 50%;
    font-size: 18px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    text-decoration: none;
}

.whatsapp-btn:hover {
    background: #1ebe5d;
    color: #fff;
}

/* FORM */
.form-control {
    padding: 6px 10px;
    border: 1px solid #ddd;
    border-radius: 4px;
    font-size: 13px;
}

/* BUTTONS */
.btn {
    padding: 6px 12px;
    border: none;
    border-radius: 4px;
    font-size: 12px;
    cursor: pointer;
    text-decoration: none;
    display: inline-block;
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
    color: #6c757d;
    border: 1px solid #6c757d;
}

.btn:hover {
    opacity: 0.9;
}

/* MOBILE */
@media (max-width: 992px) {
    .main-content {
        margin-left: 0;
        width: 100%;
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