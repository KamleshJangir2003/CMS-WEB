@extends('auth.layouts.app')

@section('content')
<div class="main-content">
    <div class="card leads-card">
        <div class="card-header">
            <h4>Interested Candidates</h4>
            <a href="{{ route('admin.leads.index') }}" class="btn btn-secondary btn-sm">
                <i class="fa-solid fa-arrow-left me-1"></i> Back to All Leads
            </a>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="leads-table">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Number</th>
                            <th>Role</th>
                            <th>Status</th>
                            <th>Updated At</th>
                            <th>WhatsApp</th>
                            <th>View Profile</th>
                            <th>Actions</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($leads as $lead)
                        <tr>
                            <td>{{ $lead->name }}</td>
                            <td>{{ $lead->number }}</td>
                            <td>{{ $lead->role }}</td>
                            <td>
                                <span class="badge badge-success">✅ Interested</span>
                            </td>
                            <td>{{ $lead->updated_at->format('d M Y, h:i A') }}</td>
                            <td>
                                <a href="https://wa.me/91{{ $lead->number }}" target="_blank" class="whatsapp-btn">
                                    <i class="fa-brands fa-whatsapp"></i>
                                </a>
                            </td>
                            <td>
                                <a href="{{ route('admin.leads.cv', $lead->id) }}" class="view-btn">
                                    View CV
                                </a>
                            </td>
                            <td>
                                <a href="{{ route('admin.interviews.create', ['lead_id' => $lead->id]) }}" class="schedule-btn">
                                    <i class="fas fa-calendar-plus"></i> Schedule Interview
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" style="text-align:center;">No interested candidates found</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<style>
.leads-card {
    max-width: 1200px;
    margin: 0 auto;
    background: #fff;
    border-radius: 12px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.08);
}

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

.btn-secondary {
    background: #6c757d;
    color: #fff;
    text-decoration: none;
    padding: 6px 12px;
    border-radius: 4px;
}

.btn-secondary:hover {
    background: #5a6268;
    color: #fff;
}

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

.badge-success {
    background: #28a745;
    color: #fff;
    padding: 4px 8px;
    border-radius: 12px;
    font-size: 12px;
    font-weight: 500;
}

.view-btn {
    background: #0d6efd;
    color: #fff;
    padding: 6px 16px;
    border-radius: 20px;
    text-decoration: none;
    font-size: 13px;
    font-weight: 500;
}

.view-btn:hover {
    background: #084298;
}

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
}

.schedule-btn {
    background: #28a745;
    color: #fff;
    padding: 6px 12px;
    border-radius: 20px;
    text-decoration: none;
    font-size: 12px;
    font-weight: 500;
    display: inline-flex;
    align-items: center;
    gap: 5px;
}

.schedule-btn:hover {
    background: #218838;
    color: #fff;
}
</style>
@endsection