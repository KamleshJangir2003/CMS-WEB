<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\EmployeeBankDetail;
use App\Models\EmployeeDocument;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class EmployeeDocumentController extends Controller
{
    const REQUIRED_DOCUMENTS = [
        'aadhar_card',
        'pan_card',
        'marksheet_10th',
        'marksheet_12th',
        'graduation',
        'diploma',
        'post_graduation',
        'passbook',
    ];

    public function __construct()
    {
        $this->middleware('auth');
    }

    /* =====================================================
       EMPLOYEE DOCUMENTS INDEX (own documents)
    ===================================================== */
    public function index()
    {
        $user = Auth::user();
        $documents = EmployeeDocument::where('user_id', $user->id)->get();
        $bankDetail = EmployeeBankDetail::where('user_id', $user->id)->first();

        $totalRequired  = count(self::REQUIRED_DOCUMENTS);
        $uploadedCount  = $documents->unique('document_type')->count();
        $verifiedCount  = $documents->where('status', 'verified')->unique('document_type')->count();
        $pendingCount   = $documents->where('status', 'pending')->unique('document_type')->count();
        $isAdminView    = false;

        return view('auth.admin.employees.em_document', compact(
            'user',
            'documents',
            'bankDetail',
            'totalRequired',
            'uploadedCount',
            'verifiedCount',
            'pendingCount',
            'isAdminView'
        ));
    }

    /* =====================================================
       ADMIN DOCUMENTS INDEX – LIST EMPLOYEES
    ===================================================== */
    public function adminDocumentsIndex()
    {
        $employees = Employee::where('user_type', 'employee')
            ->orderBy('first_name')
            ->get();

        return view('auth.admin.employees.documents_index', compact('employees'));
    }

    /* =====================================================
       ADMIN VIEW – EMPLOYEE DOCUMENTS ✅ FIXED
    ===================================================== */
    public function adminView($userId)
    {
        $user = Employee::findOrFail($userId);

        $documents = EmployeeDocument::where('user_id', $userId)->get();
        $bankDetail = EmployeeBankDetail::where('user_id', $userId)->first();

        $totalRequired  = count(self::REQUIRED_DOCUMENTS);
        $uploadedCount = $documents->unique('document_type')->count();
        $verifiedCount = $documents->where('status', 'verified')->unique('document_type')->count();
        $pendingCount  = $documents->where('status', 'pending')->unique('document_type')->count();

        $isAdminView = true;

        return view('auth.admin.employees.em_document', compact(
            'user',
            'documents',
            'bankDetail',
            'totalRequired',
            'uploadedCount',
            'verifiedCount',
            'pendingCount',
            'isAdminView'
        ));
    }

    /* =====================================================
       ADMIN UPLOAD DOCUMENT (for employee)
    ===================================================== */
    public function adminUploadDocument(Request $request, $userId)
    {
        $request->validate([
            'document_type' => 'required|in:' . implode(',', self::REQUIRED_DOCUMENTS),
            'document'      => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120',
        ]);

        $employee = Employee::findOrFail($userId);

        if ($request->document_type !== 'salary_slips') {
            $existing = EmployeeDocument::where('user_id', $userId)
                ->where('document_type', $request->document_type)
                ->first();

            if ($existing && $existing->status === 'verified') {
                return back()->with('error', 'Verified document cannot be replaced');
            }

            if ($existing) {
                Storage::disk('public')->delete($existing->file_path);
                $existing->delete();
            }
        }

        $file = $request->file('document');
        $path = "documents/{$userId}/" . time() . '_' . $file->getClientOriginalName();

        Storage::disk('public')->put($path, file_get_contents($file));

        EmployeeDocument::create([
            'user_id'        => $userId,
            'document_type'  => $request->document_type,
            'document_name'  => $this->getDocumentDisplayName($request->document_type),
            'file_path'      => $path,
            'file_extension' => $file->getClientOriginalExtension(),
            'file_size'      => $file->getSize(),
            'status'         => 'uploaded',
            'uploaded_at'    => now(),
        ]);

        return redirect()->route('admin.employees.document', ['userId' => $userId])
            ->with('success', 'Document uploaded successfully');
    }

    /* =====================================================
       ADMIN SAVE BANK DETAILS (for employee)
    ===================================================== */
    public function adminSaveBankDetails(Request $request, $userId)
    {
        $request->validate([
            'bank_name'       => 'required',
            'account_number'  => 'required',
            'ifsc_code'       => 'required',
            'account_type'    => 'required|in:savings,current',
        ]);

        EmployeeBankDetail::updateOrCreate(
            ['user_id' => $userId],
            $request->only('bank_name', 'account_number', 'ifsc_code', 'account_type')
        );

        return redirect()->route('admin.employees.document', ['userId' => $userId])
            ->with('success', 'Bank details saved successfully');
    }

    /* =====================================================
       ADMIN SUBMIT FOR VERIFICATION
    ===================================================== */
    public function adminSubmitForVerification($userId)
    {
        $uploadedTypes = EmployeeDocument::where('user_id', $userId)
            ->pluck('document_type')
            ->unique()
            ->toArray();

        $missing = array_diff(self::REQUIRED_DOCUMENTS, $uploadedTypes);

        if (!empty($missing)) {
            return back()->with('error', 'Missing documents: ' . implode(', ', array_map(fn($m) => ucwords(str_replace('_', ' ', $m)), $missing)));
        }

        EmployeeDocument::where('user_id', $userId)
            ->where('status', 'uploaded')
            ->update(['status' => 'pending']);

        return redirect()->route('admin.employees.document', ['userId' => $userId])
            ->with('success', 'Documents submitted for verification');
    }

    /* =====================================================
       GENERATE OFFER LETTER
    ===================================================== */
    public function generateOfferLetter($userId)
    {
        $employee = Employee::findOrFail($userId);
        $bankDetail = EmployeeBankDetail::where('user_id', $userId)->first();
        
        // Check if all required documents are submitted for verification
        $submittedTypes = EmployeeDocument::where('user_id', $userId)
            ->where('status', 'pending')
            ->pluck('document_type')
            ->unique()
            ->toArray();

        $missing = array_diff(self::REQUIRED_DOCUMENTS, $submittedTypes);

        if (!empty($missing)) {
            return back()->with('error', 'Cannot generate offer letter. Please submit all documents for verification first.');
        }

        // Generate PDF using dompdf
        $pdf = app('dompdf.wrapper');
        $pdf->loadView('auth.admin.employees.offer-letter', compact('employee', 'bankDetail'));
        
        $fileName = 'offer_letter_' . $employee->first_name . '_' . $employee->last_name . '_' . date('Y-m-d') . '.pdf';
        
        return $pdf->download($fileName);
    }

    /* =====================================================
       EMPLOYEE UPLOAD DOCUMENT
    ===================================================== */
    public function uploadDocument(Request $request)
    {
        $request->validate([
            'document_type' => 'required|in:' . implode(',', self::REQUIRED_DOCUMENTS),
            'document'      => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120',
        ]);

        $user = Auth::user();

        if ($request->document_type !== 'salary_slips') {
            $existing = EmployeeDocument::where('user_id', $user->id)
                ->where('document_type', $request->document_type)
                ->first();

            if ($existing && $existing->status === 'verified') {
                return back()->with('error', 'Verified document cannot be replaced');
            }

            if ($existing) {
                Storage::disk('public')->delete($existing->file_path);
                $existing->delete();
            }
        }

        $file = $request->file('document');
        $path = "documents/{$user->id}/" . time() . '_' . $file->getClientOriginalName();

        Storage::disk('public')->put($path, file_get_contents($file));

        EmployeeDocument::create([
            'user_id'        => $user->id,
            'document_type'  => $request->document_type,
            'document_name'  => $this->getDocumentDisplayName($request->document_type),
            'file_path'      => $path,
            'file_extension' => $file->getClientOriginalExtension(),
            'file_size'      => $file->getSize(),
            'status'         => 'uploaded',
            'uploaded_at'    => now(),
        ]);

        return back()->with('success', 'Document uploaded successfully');
    }

    /* =====================================================
       SAVE BANK DETAILS
    ===================================================== */
    public function saveBankDetails(Request $request)
    {
        $request->validate([
            'bank_name'       => 'required',
            'account_number' => 'required',
            'ifsc_code'      => 'required',
            'account_type'   => 'required|in:savings,current',
        ]);

        EmployeeBankDetail::updateOrCreate(
            ['user_id' => Auth::id()],
            $request->only('bank_name', 'account_number', 'ifsc_code', 'account_type')
        );

        return back()->with('success', 'Bank details saved successfully');
    }

    /* =====================================================
       VIEW DOCUMENT (EMPLOYEE + ADMIN) ✅ FIXED
    ===================================================== */
    public function viewDocument($id)
    {
        $doc = EmployeeDocument::findOrFail($id);

        if (Auth::user()->user_type !== 'admin' && $doc->user_id !== Auth::id()) {
            abort(403);
        }

        return response()->file(
            Storage::disk('public')->path($doc->file_path)
        );
    }

    /* =====================================================
       DOWNLOAD DOCUMENT (EMPLOYEE + ADMIN) ✅ FIXED
    ===================================================== */
    public function downloadDocument($id)
    {
        $doc = EmployeeDocument::findOrFail($id);

        if (Auth::user()->user_type !== 'admin' && $doc->user_id !== Auth::id()) {
            abort(403);
        }

        return Storage::disk('public')->download(
            $doc->file_path,
            $doc->document_name . '.' . $doc->file_extension
        );
    }

    /* =====================================================
       DELETE DOCUMENT
    ===================================================== */
    public function deleteDocument($id)
    {
        $doc = EmployeeDocument::findOrFail($id);

        if ($doc->status === 'verified') {
            return back()->with('error', 'Verified document cannot be deleted');
        }

        if (Auth::user()->user_type !== 'admin' && $doc->user_id !== Auth::id()) {
            abort(403);
        }

        Storage::disk('public')->delete($doc->file_path);
        $doc->delete();

        return back()->with('success', 'Document deleted successfully');
    }

    /* =====================================================
       SUBMIT FOR VERIFICATION
    ===================================================== */
    public function submitForVerification()
    {
        $user = Auth::user();

        $uploadedTypes = EmployeeDocument::where('user_id', $user->id)
            ->pluck('document_type')
            ->unique()
            ->toArray();

        $missing = array_diff(self::REQUIRED_DOCUMENTS, $uploadedTypes);

        if (!empty($missing)) {
            return back()->with('error', 'Missing documents: ' . implode(', ', $missing));
        }

        EmployeeDocument::where('user_id', $user->id)
            ->where('status', 'uploaded')
            ->update(['status' => 'pending']);

        return back()->with('success', 'Documents submitted for verification');
    }

    private function getDocumentDisplayName($type)
    {
        return ucwords(str_replace('_', ' ', $type));
    }
}
