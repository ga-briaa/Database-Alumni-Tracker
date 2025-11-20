document.addEventListener('DOMContentLoaded', function() {
    const addAlumIdInput = document.getElementById('add-alum-id');
    const editAlumIdInput = document.getElementById('edit-alum-id');

    function formatAlumIdInput(event) {
        const input = event.target;
        const charCode = event.charCode;

        // Allow only numbers
        if (charCode > 47 && charCode < 58) { // 0-9
            const currentValue = input.value.replace(/[^0-9]/g, ''); // Remove non-numeric characters for length check
            
            // If at 4 digits and next is a number, add hyphen
            if (currentValue.length === 4) {
                // Prevent adding another hyphen if one is already present at the correct position
                if (input.value.charAt(4) !== '-') {
                    input.value += '-';
                }
            }
            // Allow the number to be typed
            return true;
        }
        event.preventDefault();
        return false;
    }

    if (addAlumIdInput) {
        addAlumIdInput.addEventListener('keypress', formatAlumIdInput);
    }

    if (editAlumIdInput) {
        editAlumIdInput.addEventListener('keypress', formatAlumIdInput);
    }
});