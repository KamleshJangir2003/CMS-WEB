@extends('auth.layouts.app')

@section('title', 'Admin Settings')

@section('content')
<div class="main-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="page-header">
                    <h1><i class="fa-solid fa-cog"></i> Settings</h1>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- System Settings -->
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-header">
                        <h5><i class="fa-solid fa-server"></i> System Settings</h5>
                    </div>
                    <div class="card-body">
                        <div class="setting-item">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <div>
                                    <h6>Email Notifications</h6>
                                    <small class="text-muted">Receive email notifications for important events</small>
                                </div>
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="emailNotifications" checked>
                                </div>
                            </div>
                        </div>

                        <div class="setting-item">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <div>
                                    <h6>Auto Backup</h6>
                                    <small class="text-muted">Automatically backup system data daily</small>
                                </div>
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="autoBackup" checked>
                                </div>
                            </div>
                        </div>

                        <div class="setting-item">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <div>
                                    <h6>Birthday Notifications</h6>
                                    <small class="text-muted">Show birthday notifications in header</small>
                                </div>
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="birthdayNotifications" checked>
                                </div>
                            </div>
                        </div>

                        <div class="setting-item">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <div>
                                    <h6>Bill Reminders</h6>
                                    <small class="text-muted">Show bill due notifications</small>
                                </div>
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="billReminders" checked>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Security Settings -->
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-header">
                        <h5><i class="fa-solid fa-shield-alt"></i> Security Settings</h5>
                    </div>
                    <div class="card-body">
                        <div class="setting-item">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <div>
                                    <h6>Two-Factor Authentication</h6>
                                    <small class="text-muted">Add extra security to your account</small>
                                </div>
                                <button class="btn btn-sm btn-outline-primary">Enable</button>
                            </div>
                        </div>

                        <div class="setting-item">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <div>
                                    <h6>Session Timeout</h6>
                                    <small class="text-muted">Auto logout after inactivity</small>
                                </div>
                                <select class="form-select form-select-sm" style="width: auto;">
                                    <option value="30">30 minutes</option>
                                    <option value="60" selected>1 hour</option>
                                    <option value="120">2 hours</option>
                                    <option value="240">4 hours</option>
                                </select>
                            </div>
                        </div>

                        <div class="setting-item">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <div>
                                    <h6>Login Alerts</h6>
                                    <small class="text-muted">Get notified of new login attempts</small>
                                </div>
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="loginAlerts" checked>
                                </div>
                            </div>
                        </div>

                        <div class="setting-item">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <div>
                                    <h6>Password Expiry</h6>
                                    <small class="text-muted">Force password change every 90 days</small>
                                </div>
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="passwordExpiry">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-4">
            <!-- Application Settings -->
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-header">
                        <h5><i class="fa-solid fa-desktop"></i> Application Settings</h5>
                    </div>
                    <div class="card-body">
                        <div class="form-group mb-3">
                            <label for="timezone">Timezone</label>
                            <select class="form-select" id="timezone">
                                <option value="Asia/Kolkata" selected>Asia/Kolkata (IST)</option>
                                <option value="UTC">UTC</option>
                                <option value="America/New_York">America/New_York (EST)</option>
                                <option value="Europe/London">Europe/London (GMT)</option>
                            </select>
                        </div>

                        <div class="form-group mb-3">
                            <label for="dateFormat">Date Format</label>
                            <select class="form-select" id="dateFormat">
                                <option value="d/m/Y" selected>DD/MM/YYYY</option>
                                <option value="m/d/Y">MM/DD/YYYY</option>
                                <option value="Y-m-d">YYYY-MM-DD</option>
                            </select>
                        </div>

                        <div class="form-group mb-3">
                            <label for="language">Language</label>
                            <select class="form-select" id="language">
                                <option value="en" selected>English</option>
                                <option value="hi">Hindi</option>
                                <option value="es">Spanish</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <!-- System Information -->
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-header">
                        <h5><i class="fa-solid fa-info-circle"></i> System Information</h5>
                    </div>
                    <div class="card-body">
                        <div class="info-item mb-3">
                            <strong>Application Version:</strong>
                            <span class="text-muted">v2.1.0</span>
                        </div>
                        <div class="info-item mb-3">
                            <strong>PHP Version:</strong>
                            <span class="text-muted">{{ phpversion() }}</span>
                        </div>
                        <div class="info-item mb-3">
                            <strong>Laravel Version:</strong>
                            <span class="text-muted">{{ app()->version() }}</span>
                        </div>
                        <div class="info-item mb-3">
                            <strong>Database:</strong>
                            <span class="text-muted">MySQL</span>
                        </div>
                        <div class="info-item mb-3">
                            <strong>Last Backup:</strong>
                            <span class="text-success">{{ now()->subHours(2)->diffForHumans() }}</span>
                        </div>
                        <div class="info-item mb-3">
                            <strong>System Status:</strong>
                            <span class="badge bg-success">Online</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-4">
            <div class="col-12">
                <div class="text-center">
                    <button class="btn btn-primary me-2">
                        <i class="fa-solid fa-save"></i> Save Settings
                    </button>
                    <button class="btn btn-secondary">
                        <i class="fa-solid fa-undo"></i> Reset to Default
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.setting-item {
    border-bottom: 1px solid #eee;
    padding-bottom: 15px;
}

.setting-item:last-child {
    border-bottom: none;
    padding-bottom: 0;
}

.form-check-input:checked {
    background-color: #2eacb3;
    border-color: #2eacb3;
}

.card {
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    border: none;
}

.card-header {
    background: linear-gradient(135deg, #2eacb3 0%, #2eacb3 100%);
    color: white;
    border-bottom: none;
}

.card-header h5 {
    margin: 0;
    font-weight: 500;
}

.info-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.btn-primary {
    background: linear-gradient(135deg, #2eacb3 0%, #2eacb3 100%);
    border: none;
}

.form-select:focus, .form-control:focus {
    border-color: #2eacb3;
    box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Handle switch changes
    document.querySelectorAll('.form-check-input').forEach(function(checkbox) {
        checkbox.addEventListener('change', function() {
            console.log(this.id + ' changed to: ' + this.checked);
            // Here you can add AJAX call to save settings
        });
    });

    // Handle select changes
    document.querySelectorAll('select').forEach(function(select) {
        select.addEventListener('change', function() {
            console.log(this.id + ' changed to: ' + this.value);
            // Here you can add AJAX call to save settings
        });
    });
});
</script>
@endsection