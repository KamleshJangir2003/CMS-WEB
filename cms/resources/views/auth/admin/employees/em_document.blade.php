@extends('auth.layouts.app')
<style>
    .container-fluid{
    margin-top: 60px !important;
    padding-left: 130px !important;
}
</style>
@section('content')

@php
$isAdminView = $isAdminView ?? false;
$totalRequired = $totalRequired ?? 0;
$uploadedCount = $uploadedCount ?? 0;
$verifiedCount = $verifiedCount ?? 0;
$pendingCount  = $pendingCount ?? 0;

$progress = $totalRequired > 0
    ? min(100, round(($uploadedCount / $totalRequired) * 100))
    : 0;

// Routes based on context
$uploadRoute = $isAdminView ? route('admin.employees.document.upload', ['userId' => $user->id]) : route('employee.documents.upload');
$bankRoute = $isAdminView ? route('admin.employees.document.bank-details', ['userId' => $user->id]) : route('employee.bank.details');
$submitRoute = $isAdminView ? route('admin.employees.document.submit', ['userId' => $user->id]) : route('employee.documents.submit');
@endphp

<div class="container-fluid">

    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4>Employee Documents</h4>
        @if($isAdminView)
        <a href="{{ route('admin.employees.documents.index') }}" class="btn btn-outline-secondary btn-sm">
            <i class="fa-solid fa-arrow-left me-1"></i> Back to List
        </a>
        @endif
    </div>

    <!-- Employee Info -->
    <div class="card mb-4">
        <div class="card-body d-flex justify-content-between align-items-center">
            <div>
                <strong>{{ $user->full_name ?? $user->name ?? 'N/A' }}</strong><br>
                <small>EMP ID: {{ $user->id }}</small>
            </div>

            <div style="width:150px">
                <div class="progress" style="height:6px">
                    <div class="progress-bar bg-success"
                         style="width: {{ $progress }}%"></div>
                </div>
                <small>{{ $progress }}%</small>
            </div>
        </div>
    </div>

    <!-- Alerts -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row">

        <!-- LEFT -->
        <div class="col-md-6">

            <!-- AADHAR -->
            @php $aadhar = $documents->where('document_type','aadhar_card')->first(); @endphp
            <div class="card mb-3">
                <div class="card-body">
                    <strong>Aadhar Card *</strong>
                    <span class="badge bg-{{ $aadhar?->status === 'verified' ? 'success' : ($aadhar?->status === 'pending' ? 'info' : 'warning') }} float-end">
                        {{ $aadhar->status ?? 'not uploaded' }}
                    </span>

                    @if($aadhar)
                        <div class="mt-2">
                            <a href="{{ route('employee.documents.view',$aadhar->id) }}" class="btn btn-sm btn-primary">View</a>
                            <a href="{{ route('employee.documents.download',$aadhar->id) }}" class="btn btn-sm btn-secondary">Download</a>
                            <button onclick="printDocument('{{ route('employee.documents.view',$aadhar->id) }}')" class="btn btn-sm btn-info">Print</button>
                            @if($aadhar->status !== 'pending')
                            <form method="POST" action="{{ $uploadRoute }}" enctype="multipart/form-data" class="mt-2">
                                @csrf
                                <input type="hidden" name="document_type" value="aadhar_card">
                                <input type="file" name="document" required class="form-control mb-2"
                                       accept=".pdf,.jpg,.jpeg,.png">
                                <button type="submit" class="btn btn-warning btn-sm">Replace</button>
                            </form>
                            @endif
                        </div>
                    @else
                        <form method="POST" action="{{ $uploadRoute }}" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="document_type" value="aadhar_card">
                            <input type="file" name="document" required class="form-control mt-2"
                                   accept=".pdf,.jpg,.jpeg,.png">
                            <button type="submit" class="btn btn-primary btn-sm mt-2">Upload</button>
                        </form>
                    @endif
                </div>
            </div>

            <!-- PAN -->
            @php $pan = $documents->where('document_type','pan_card')->first(); @endphp
            <div class="card mb-3">
                <div class="card-body">
                    <strong>PAN Card *</strong>
                    <span class="badge bg-{{ $pan?->status === 'verified' ? 'success' : ($pan?->status === 'pending' ? 'info' : 'warning') }} float-end">
                        {{ $pan->status ?? 'not uploaded' }}
                    </span>

                    @if($pan)
                        <div class="mt-2">
                            <a href="{{ route('employee.documents.view',$pan->id) }}" class="btn btn-sm btn-primary">View</a>
                            <a href="{{ route('employee.documents.download',$pan->id) }}" class="btn btn-sm btn-secondary">Download</a>
                            <button onclick="printDocument('{{ route('employee.documents.view',$pan->id) }}')" class="btn btn-sm btn-info">Print</button>
                            @if($pan->status !== 'pending')
                            <form method="POST" action="{{ $uploadRoute }}" enctype="multipart/form-data" class="mt-2">
                                @csrf
                                <input type="hidden" name="document_type" value="pan_card">
                                <input type="file" name="document" required class="form-control mb-2"
                                       accept=".pdf,.jpg,.jpeg,.png">
                                <button type="submit" class="btn btn-warning btn-sm">Replace</button>
                            </form>
                            @endif
                        </div>
                    @else
                        <form method="POST" action="{{ $uploadRoute }}" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="document_type" value="pan_card">
                            <input type="file" name="document" required class="form-control mt-2"
                                   accept=".pdf,.jpg,.jpeg,.png">
                            <button type="submit" class="btn btn-primary btn-sm mt-2">Upload</button>
                        </form>
                    @endif
                </div>
            </div>

            <!-- EDUCATION -->
            @php
            $eduDocs = [
                '10th Marksheet' => 'marksheet_10th',
                '12th Marksheet' => 'marksheet_12th',
                'Graduation'     => 'graduation',
                'Diploma'        => 'diploma',
                'Post Graduation' => 'post_graduation',
            ];
            @endphp

            @foreach($eduDocs as $label=>$type)
                @php $doc = $documents->where('document_type',$type)->first(); @endphp
                <div class="card mb-3">
                    <div class="card-body">
                        <strong>{{ $label }} *</strong>
                        <span class="badge bg-{{ $doc?->status === 'verified' ? 'success' : ($doc?->status === 'pending' ? 'info' : 'warning') }} float-end">
                            {{ $doc->status ?? 'not uploaded' }}
                        </span>

                        @if($doc)
                            <div class="mt-2">
                                <a href="{{ route('employee.documents.view',$doc->id) }}" class="btn btn-sm btn-primary">View</a>
                                <a href="{{ route('employee.documents.download',$doc->id) }}" class="btn btn-sm btn-secondary">Download</a>
                                <button onclick="printDocument('{{ route('employee.documents.view',$doc->id) }}')" class="btn btn-sm btn-info">Print</button>
                                @if($doc->status !== 'pending')
                                <form method="POST" action="{{ $uploadRoute }}" enctype="multipart/form-data" class="mt-2">
                                    @csrf
                                    <input type="hidden" name="document_type" value="{{ $type }}">
                                    <input type="file" name="document" required class="form-control mb-2"
                                           accept=".pdf,.jpg,.jpeg,.png">
                                    <button type="submit" class="btn btn-warning btn-sm">Replace</button>
                                </form>
                                @endif
                            </div>
                        @else
                            <form method="POST" action="{{ $uploadRoute }}" enctype="multipart/form-data">
                                @csrf
                                <input type="hidden" name="document_type" value="{{ $type }}">
                                <input type="file" name="document" required class="form-control mt-2"
                                       accept=".pdf,.jpg,.jpeg,.png">
                                <button type="submit" class="btn btn-primary btn-sm mt-2">Upload</button>
                            </form>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>

        <!-- RIGHT -->
        <div class="col-md-6">

            <!-- PASSBOOK -->
            @php $passbook = $documents->where('document_type','passbook')->first(); @endphp
            <div class="card mb-3">
                <div class="card-body">
                    <strong>Passbook (Bank Front Page) *</strong>
                    <span class="badge bg-{{ $passbook?->status === 'verified' ? 'success' : ($passbook?->status === 'pending' ? 'info' : 'warning') }} float-end">
                        {{ $passbook->status ?? 'not uploaded' }}
                    </span>

                    @if($passbook)
                        <div class="mt-2">
                            <a href="{{ route('employee.documents.view',$passbook->id) }}" class="btn btn-sm btn-primary">View</a>
                            <a href="{{ route('employee.documents.download',$passbook->id) }}" class="btn btn-sm btn-secondary">Download</a>
                            <button onclick="printDocument('{{ route('employee.documents.view',$passbook->id) }}')" class="btn btn-sm btn-info">Print</button>
                            @if($passbook->status !== 'pending')
                            <form method="POST" action="{{ $uploadRoute }}" enctype="multipart/form-data" class="mt-2">
                                @csrf
                                <input type="hidden" name="document_type" value="passbook">
                                <input type="file" name="document" required class="form-control mb-2"
                                       accept=".pdf,.jpg,.jpeg,.png">
                                <button type="submit" class="btn btn-warning btn-sm">Replace</button>
                            </form>
                            @endif
                        </div>
                    @else
                        <form method="POST" action="{{ $uploadRoute }}" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="document_type" value="passbook">
                            <input type="file" name="document" required class="form-control mt-2"
                                   accept=".pdf,.jpg,.jpeg,.png">
                            <button type="submit" class="btn btn-primary btn-sm mt-2">Upload</button>
                        </form>
                    @endif
                </div>
            </div>

            <!-- SUMMARY -->
            <div class="card">
                <div class="card-body">
                    <p>Total Required: <strong>{{ $totalRequired }}</strong></p>
                    <p>Uploaded: <strong>{{ $uploadedCount }}</strong></p>
                    <p>Verified: <strong>{{ $verifiedCount }}</strong></p>
                    <p>Pending: <strong>{{ $pendingCount }}</strong></p>

                    <form method="POST" action="{{ $submitRoute }}">
                        @csrf
                        <button type="submit" class="btn btn-success w-100 mb-2">Submit for Verification</button>
                    </form>
                    
                    @if($isAdminView)
                        @php
                        $submittedTypes = $documents->where('status', 'pending')->pluck('document_type')->unique()->count();
                        $allSubmitted = $submittedTypes >= $totalRequired;
                        @endphp
                        @if($allSubmitted)
                        <form method="POST" action="{{ route('admin.employees.document.generate-offer-letter', ['userId' => $user->id]) }}">
                            @csrf
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="fa-solid fa-file-pdf me-1"></i> Generate Offer Letter
                            </button>
                        </form>
                        @endif
                    @endif
                </div>
            </div>

        </div>
    </div>
</div>

<script>
function printDocument(url) {
    const printWindow = window.open(url, '_blank');
    printWindow.onload = function() {
        printWindow.print();
    };
}
</script>

@endsection
