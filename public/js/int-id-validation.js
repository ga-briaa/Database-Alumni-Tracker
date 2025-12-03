document.addEventListener('DOMContentLoaded', function() {
    const addDegreeIdInput = document.getElementById('add-degree-id');
    const editDegreeIdInput = document.getElementById('edit-degree-id');
    const addCompanyIdInput = document.getElementById('add-company-id');
    const editCompanyIdInput = document.getElementById('edit-company-id');

    function formatIntIdInput(event) {
        if (event.which != 8 && event.which != 0 && (event.which < 48 || event.which > 57)) {
            event.preventDefault();
        }
    }

    if (addDegreeIdInput) {
        addDegreeIdInput.addEventListener('keypress', formatIntIdInput);
    }

    if (editDegreeIdInput) {
        editDegreeIdInput.addEventListener('keypress', formatIntIdInput);
    }

    if (addCompanyIdInput) {
        addCompanyIdInput.addEventListener('keypress', formatIntIdInput);
    }

    if (editCompanyIdInput) {
        editCompanyIdInput.addEventListener('keypress', formatIntIdInput);
    }
});