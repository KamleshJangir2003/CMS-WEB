@extends('auth.layouts.app')

@section('content')
<div class="main-content">
    <div class="card profile-card">
        <div class="card-header">
            <h4>Lead Profile</h4>
            <a href="{{ route('admin.leads.index') }}" class="back-btn">← Back to Leads</a>
        </div>

        <div class="card-body">
            <div class="profile-info">
                <div class="info-row">
                    <label>Number:</label>
                    <span>{{ $lead->number }}</span>
                </div>
                
                <div class="info-row">
                    <label>Name:</label>
                    <span>{{ $lead->name }}</span>
                </div>
                
                <div class="info-row">
                    <label>Status:</label>
                    <span class="status-badge status-{{ strtolower(str_replace(' ', '-', $lead->condition_status)) }}">
                        {{ $lead->condition_status }}
                    </span>
                </div>
                
                <div class="info-row">
                    <label>Created:</label>
                    <span>{{ $lead->created_at->format('d M Y, h:i A') }}</span>
                </div>
                
                <div class="info-row">
                    <label>WhatsApp:</label>
                    <a href="https://wa.me/91{{ $lead->number }}" target="_blank" class="whatsapp-link">
                        <i class="fa-brands fa-whatsapp"></i> Chat on WhatsApp
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.main-content {
    padding: 20px;
}

.profile-card {
    background: #fff;
    border-radius: 10px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.08);
    max-width: 600px;
    margin: 0 auto;
}

.card-header {
    padding: 20px;
    border-bottom: 1px solid #eee;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.card-header h4 {
    margin: 0;
    font-weight: 600;
}

.back-btn {
    background: #6c757d;
    color: white;
    padding: 8px 16px;
    border-radius: 4px;
    text-decoration: none;
}

.back-btn:hover {
    background: #5a6268;
}

.profile-info {
    padding: 20px;
}

.info-row {
    display: flex;
    margin-bottom: 15px;
    align-items: center;
}

.info-row label {
    font-weight: 600;
    width: 120px;
    color: #555;
}

.info-row span {
    color: #333;
}

.status-badge {
    padding: 4px 12px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
}

.status-not-interested {
    background: #f8d7da;
    color: #721c24;
}

.status-call-back {
    background: #fff3cd;
    color: #856404;
}

.status-picked {
    background: #d4edda;
    color: #155724;
}

.whatsapp-link {
    background: #25D366;
    color: white;
    padding: 8px 16px;
    border-radius: 4px;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 8px;
}

.whatsapp-link:hover {
    background: #1ebe5d;
}
</style>
@endsection