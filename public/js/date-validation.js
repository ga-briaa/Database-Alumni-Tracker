document.addEventListener('DOMContentLoaded', function() {
    const today = new Date().toLocaleDateString('en-CA');

    function validateDateRange(startInput, endInput) {
        if (!startInput || !endInput) return;

        // Keep the 'max' limit to today
        startInput.setAttribute('max', today);
        endInput.setAttribute('max', today);

        // --- REMOVED THE 'MIN' ATTRIBUTE LOGIC HERE ---
        // This allows the user to select an end date earlier than the start date
        // in the UI without it being greyed out.

        // Check if logic is invalid and trigger native popup
        if (startInput.value && endInput.value && startInput.value > endInput.value) {
            // Updated the message as requested
            endInput.setCustomValidity("Start Date cannot be later than End Date.");
            endInput.reportValidity(); 
        } else {
            endInput.setCustomValidity("");
        }
    }

    function attachDateListeners(startId, endId) {
        const startInput = document.getElementById(startId);
        const endInput = document.getElementById(endId);

        if (!startInput || !endInput) return;

        validateDateRange(startInput, endInput);

        startInput.addEventListener('input', () => validateDateRange(startInput, endInput));
        endInput.addEventListener('input', () => validateDateRange(startInput, endInput));
    }

    attachDateListeners('add-start-date', 'add-end-date');
    attachDateListeners('edit-start-date', 'edit-end-date');

    const editStartField = document.getElementById('edit-start-date');
    const editEndField = document.getElementById('edit-end-date');

    document.querySelectorAll('.btn-modal-trigger[data-target="editModal-employment"]').forEach(button => {
        button.addEventListener('click', function() {
            editStartField.value = this.dataset.startdate || '';
            editEndField.value = this.dataset.enddate || '';

            validateDateRange(editStartField, editEndField);
        });
    });
});