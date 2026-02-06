// DOM Elements
const shiftForm = document.getElementById('shift-form');
const shiftsTableBody = document.getElementById('shifts-table-body');
const notification = document.getElementById('shifts-notification');
const searchBtn = document.getElementById('search-btn');
const perPageSelect = document.getElementById('per-page-select');

// Variables
let currentPage = 1;
let currentFilters = {};

// Initialize
document.addEventListener('DOMContentLoaded', function() {
    console.log('Shift Management System Loaded');
    
    // Load initial shifts
    loadShifts();
    
    // Event Listeners
    if (shiftForm) {
        shiftForm.addEventListener('submit', handleAddShift);
        console.log('Shift form listener added');
    } else {
        console.error('Shift form not found!');
    }
    
    if (searchBtn) {
        searchBtn.addEventListener('click', handleSearch);
    }
    
    if (perPageSelect) {
        perPageSelect.addEventListener('change', handlePerPageChange);
    }
    
    // Debug: Log URLs
    console.log('CSRF Token:', csrfToken);
    console.log('Shifts Store URL:', shiftsStoreUrl);
});

// Load shifts with AJAX
async function loadShifts() {
    try {
        console.log('Loading shifts...');
        
        const params = new URLSearchParams({
            page: currentPage,
            per_page: perPageSelect.value,
            ...currentFilters
        });
        
        console.log('Fetching URL:', `${shiftsDataUrl}?${params}`);
        
        const response = await fetch(`${shiftsDataUrl}?${params}`);
        console.log('Response status:', response.status);
        
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        
        const data = await response.json();
        console.log('Shifts data loaded:', data);
        
        renderShiftsTable(data);
        renderPagination(data);
        
    } catch (error) {
        console.error('Error loading shifts:', error);
        showNotification('Error loading shifts', 'error');
        
        // Show empty state
        shiftsTableBody.innerHTML = `
            <tr>
                <td colspan="12" class="text-center py-4">
                    <i class="fas fa-exclamation-triangle fa-2x mb-3 text-warning"></i>
                    <p class="text-muted">Unable to load shifts. Please check console.</p>
                    <button class="btn btn-sm btn-primary" onclick="loadShifts()">
                        <i class="fas fa-redo me-1"></i>Retry
                    </button>
                </td>
            </tr>
        `;
    }
}

// Add new shift - DEBUG VERSION
async function handleAddShift(e) {
    e.preventDefault();
    console.log('Add shift form submitted');
    
    const formData = new FormData(shiftForm);
    const submitBtn = document.getElementById('submit-btn');
    
    // Debug: Show form data
    console.log('Form Data:');
    for (let [key, value] of formData.entries()) {
        console.log(`${key}: ${value}`);
    }
    
    // Show loading state
    const originalText = submitBtn.innerHTML;
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Adding...';
    submitBtn.disabled = true;
    
    try {
        console.log('Sending request to:', shiftsStoreUrl);
        console.log('CSRF Token:', csrfToken);
        
        const response = await fetch(shiftsStoreUrl, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: formData
        });
        
        console.log('Response received:', response.status);
        
        const result = await response.json();
        console.log('Response data:', result);
        
        if (response.ok && result.success) {
            console.log('Shift added successfully:', result.shift);
            
            // Reset form
            shiftForm.reset();
            
            // Reset to default values
            document.getElementById('start-time').value = '09:00';
            document.getElementById('end-time').value = '17:00';
            document.getElementById('shift-type').value = 'Day';
            document.getElementById('shift-date').value = new Date().toISOString().split('T')[0];
            document.getElementById('break-start').value = '12:30';
            document.getElementById('break-end').value = '13:00';
            
            // Clear validation errors
            clearValidationErrors();
            
            // Show success message
            showNotification('Shift added successfully!');
            
            // Reload shifts after 1 second
            setTimeout(() => {
                loadShifts();
            }, 1000);
            
        } else {
            // Handle validation errors
            if (result.errors) {
                console.log('Validation errors:', result.errors);
                displayValidationErrors(result.errors);
                showNotification('Please fix the errors', 'error');
            } else {
                console.log('Server error:', result.message);
                showNotification(result.message || 'Error adding shift', 'error');
            }
        }
        
    } catch (error) {
        console.error('Network error:', error);
        showNotification('Network error. Please try again.', 'error');
        
    } finally {
        // Reset button state
        submitBtn.innerHTML = originalText;
        submitBtn.disabled = false;
    }
}

// Display validation errors
function displayValidationErrors(errors) {
    // Clear previous errors
    clearValidationErrors();
    
    // Add new errors
    for (const field in errors) {
        const fieldName = field.replace(/_/g, '-');
        const input = document.querySelector(`[name="${field}"]`);
        const errorDiv = document.getElementById(`${fieldName}_error`);
        
        console.log(`Error for ${field}:`, errors[field][0]);
        console.log('Input element:', input);
        console.log('Error div:', errorDiv);
        
        if (input) {
            input.classList.add('is-invalid');
            if (errorDiv) {
                errorDiv.textContent = errors[field][0];
            }
        }
    }
}

// Clear validation errors
function clearValidationErrors() {
    const invalidInputs = document.querySelectorAll('.is-invalid');
    const errorDivs = document.querySelectorAll('.invalid-feedback');
    
    invalidInputs.forEach(input => input.classList.remove('is-invalid'));
    errorDivs.forEach(div => div.textContent = '');
}

// Rest of your JavaScript code remains same...
// ... (previous renderShiftsTable, editShift, deleteShift functions)