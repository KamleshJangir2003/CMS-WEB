@extends('auth.layouts.app')

@section('content')

<style>
.emp-email-wrapper {
    padding: 25px;
    margin-left: 130px;
    margin-top: 60px;
}

.emp-email-card {
    border-radius: 12px;
    border: 1px solid #e3e6f0;
    transition: 0.3s;
}

.emp-email-card:hover {
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
}

.emp-send-mail-btn {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border: none;
    border-radius: 8px;
}


.modal {
    z-index: 99999 !important;
}

.modal-backdrop {
    z-index: 99998 !important;
}

</style>

<div class="emp-email-wrapper">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fw-bold">All Employees - Send Email</h3>
        <button class="btn btn-primary" onclick="selectAll()">
            Select All
        </button>
    </div>

    <div class="card mb-4 d-none" id="empMailActions">
        <div class="card-body d-flex justify-content-between align-items-center">
            <span id="empSelectedCount" class="fw-semibold"></span>
            <button class="btn emp-send-mail-btn text-white"
                    data-bs-toggle="modal"
                    data-bs-target="#empComposeModal">
                Send Mail
            </button>
        </div>
    </div>

    <div class="row g-3">
        @foreach($employees as $employee)
        <div class="col-md-6 col-lg-4">
            <div class="card emp-email-card">
                <div class="card-body">
                    <div class="form-check mb-2">
                        <input class="form-check-input emp-checkbox"
                               type="checkbox"
                               value="{{ $employee->email }}"
                               data-name="{{ $employee->first_name }} {{ $employee->last_name }}">
                        <label class="form-check-label fw-semibold">
                            {{ $employee->first_name }} {{ $employee->last_name }}
                        </label>
                    </div>

                    <p class="text-muted mb-1">{{ $employee->email }}</p>
                    <p class="text-muted mb-1">{{ $employee->department ?? 'N/A' }}</p>
                    <span class="badge bg-primary">
                        {{ ucfirst($employee->user_type) }}
                    </span>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>

<!-- Modal -->
<div class="modal fade"
     id="empComposeModal"
     tabindex="-1"
     aria-labelledby="empComposeModalLabel"
     aria-hidden="true">

    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title" id="empComposeModalLabel">Compose Mail</h5>
                <button type="button"
                        class="btn-close"
                        data-bs-dismiss="modal"
                        aria-label="Close"></button>
            </div>

            <form action="{{ route('admin.send.bulk.mail') }}" method="POST">
                @csrf

                <div class="modal-body">

                    <div class="mb-3">
                        <label class="form-label fw-semibold">To:</label>
                        <div id="empSelectedEmails"
                             class="border rounded p-2 bg-light small"></div>
                        <input type="hidden" name="emails" id="empEmailsInput">
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Subject</label>
                        <input type="text"
                               name="subject"
                               class="form-control"
                               required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Message</label>
                        <textarea name="message"
                                  rows="6"
                                  class="form-control"
                                  required></textarea>
                    </div>

                </div>

                <div class="modal-footer">
                    <button type="button"
                            class="btn btn-secondary"
                            data-bs-dismiss="modal">
                        Cancel
                    </button>

                    <button type="submit"
                            class="btn emp-send-mail-btn text-white">
                        Send Mail
                    </button>
                </div>

            </form>

        </div>
    </div>
</div>
<script>
document.addEventListener("DOMContentLoaded", function () {
    var modal = document.getElementById('empComposeModal');
    if (modal) {
        document.body.appendChild(modal);
    }
});
</script>


<script>
function updateSelection() {
    const selected = document.querySelectorAll('.emp-checkbox:checked');
    const mailActions = document.getElementById('empMailActions');
    const selectedCount = document.getElementById('empSelectedCount');
    const selectedEmails = document.getElementById('empSelectedEmails');
    const emailsInput = document.getElementById('empEmailsInput');

    if (selected.length > 0) {
        mailActions.classList.remove('d-none');
        selectedCount.innerText = selected.length + (selected.length === 1 ? " employee selected" : " employees selected");

        let html = '';
        let emails = [];

        selected.forEach(cb => {
            html += `<span class="badge bg-primary me-1 mb-1">${cb.dataset.name} (${cb.value})</span>`;
            emails.push(cb.value);
        });

        selectedEmails.innerHTML = html;
        emailsInput.value = emails.join(',');
    } else {
        mailActions.classList.add('d-none');
        selectedEmails.innerHTML = '';
        emailsInput.value = '';
    }
}

function selectAll() {
    const checkboxes = document.querySelectorAll('.emp-checkbox');
    const allChecked = Array.from(checkboxes).every(cb => cb.checked);
    checkboxes.forEach(cb => cb.checked = !allChecked);
    updateSelection();
}

document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.emp-checkbox').forEach(cb => {
        cb.addEventListener('change', updateSelection);
    });
});
</script>


@endsection
