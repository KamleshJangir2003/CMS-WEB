@extends('auth.layouts.app')

@section('content')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

<style>
.dashboard-wrapper{
    padding: 25px;
    margin-left: 130px;
    margin-top: 60px;
}

/* Mobile Birthday Page Responsive */
@media (max-width: 768px) {
    .dashboard-wrapper {
        margin-left: 0 !important;
        margin-top: 70px;
        padding: 15px;
    }
    
    .birthday-header {
        padding: 15px;
    }
    
    .birthday-header h4 {
        font-size: 16px;
    }
    
    .birthday-item {
        padding: 12px 15px;
        gap: 10px;
    }
    
    .birthday-avatar {
        width: 40px;
        height: 40px;
        font-size: 14px;
    }
    
    .birthday-info h6 {
        font-size: 14px;
    }
    
    .birthday-info small {
        font-size: 11px;
    }
    
    .month-header {
        padding: 10px 15px;
    }
    
    .month-header h5 {
        font-size: 16px;
    }
    
    .birthday-badge {
        font-size: 10px;
        padding: 3px 6px;
    }
}
.birthday-card{
    border: none;
    border-radius: 14px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    transition: transform 0.3s ease;
}
.birthday-card:hover{
    transform: translateY(-5px);
}
.birthday-header{
    background: linear-gradient(135deg, #ff6b6b, #ffa500);
    color: white;
    border-radius: 14px 14px 0 0;
    padding: 20px;
}
.birthday-item{
    padding: 15px 20px;
    border-bottom: 1px solid #f0f0f0;
    display: flex;
    align-items: center;
    gap: 15px;
}
.birthday-item:last-child{
    border-bottom: none;
}
.birthday-avatar{
    width: 50px;
    height: 50px;
    border-radius: 50%;
    background: linear-gradient(135deg, #ff6b6b, #ffa500);
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-weight: bold;
    font-size: 18px;
}
.birthday-info h6{
    margin: 0;
    font-weight: 600;
}
.birthday-info small{
    color: #666;
}
.birthday-badge{
    background: #ff6b6b;
    color: white;
    padding: 4px 8px;
    border-radius: 12px;
    font-size: 12px;
}
.month-section{
    margin-bottom: 30px;
}
.month-header{
    background: #f8f9fa;
    padding: 15px 20px;
    border-radius: 10px;
    margin-bottom: 15px;
}
</style>

<div class="dashboard-wrapper">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2></h2>
        <div class="text-muted">
            <i class="fa-solid fa-calendar"></i> {{ date('Y') }}
        </div>
    </div>

    <!-- Today's Birthdays -->
    @if($todayBirthdays->count() > 0)
    <div class="card birthday-card mb-4">
        <div class="birthday-header">
            <h4 class="mb-0">🎉 Today's Birthdays ({{ date('d M Y') }})</h4>
        </div>
        <div class="card-body p-0">
            @foreach($todayBirthdays as $employee)
            <div class="birthday-item">
                <div class="birthday-avatar">
                    {{ strtoupper(substr($employee->first_name, 0, 1)) }}
                </div>
                <div class="birthday-info flex-grow-1">
                    <h6>{{ $employee->full_name }}</h6>
                    <small>{{ $employee->department }} • {{ $employee->job_title ?? 'Employee' }}</small>
                </div>
                <div class="birthday-badge">
                    🎂 Today
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    <!-- Upcoming Birthdays by Month -->
    @foreach($birthdaysByMonth as $month => $employees)
    @if($employees->count() > 0)
    <div class="month-section">
        <div class="month-header">
            <h5 class="mb-0">{{ $month }} ({{ $employees->count() }} birthdays)</h5>
        </div>
        
        <div class="card birthday-card">
            <div class="card-body p-0">
                @foreach($employees as $employee)
                <div class="birthday-item">
                    <div class="birthday-avatar">
                        {{ strtoupper(substr($employee->first_name, 0, 1)) }}
                    </div>
                    <div class="birthday-info flex-grow-1">
                        <h6>{{ $employee->full_name }}</h6>
                        <small>{{ $employee->department }} • {{ $employee->job_title ?? 'Employee' }}</small>
                    </div>
                    <div class="text-muted">
                        <i class="fa-solid fa-calendar"></i>
                        {{ $employee->dob ? $employee->dob->format('d M') : 'N/A' }}
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    @endif
    @endforeach

    @if($todayBirthdays->count() == 0 && collect($birthdaysByMonth)->flatten()->count() == 0)
    <div class="text-center py-5">
        <i class="fa-solid fa-birthday-cake fa-4x text-muted mb-3"></i>
        <h4 class="text-muted">No birthdays found</h4>
        <p class="text-muted">No employees have their date of birth recorded.</p>
    </div>
    @endif
</div>
@endsection