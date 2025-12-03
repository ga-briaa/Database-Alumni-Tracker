document.addEventListener("DOMContentLoaded", function() {
  const modalTriggers = document.querySelectorAll(".btn-modal-trigger");
  const closeButtons = document.querySelectorAll(".close");

  modalTriggers.forEach(button => {
    button.addEventListener("click", () => {
      const modalId = button.getAttribute("data-target");
      const modal = document.getElementById(modalId);
      if (!modal) return;

      // 1. Handle Alumni Info edit modal
      if (modalId === "editModal-info") {
        const id = button.getAttribute("data-id");
        const firstName = button.getAttribute("data-firstName");
        const lastName = button.getAttribute("data-lastName");
        const contactInfo = button.getAttribute("data-email");
        const status = button.getAttribute("data-status");

        if(modal.querySelector("#edit-alum-id")) modal.querySelector("#edit-alum-id").value = id;
        if(modal.querySelector("#edit-alum-old-id")) modal.querySelector("#edit-alum-old-id").value = id;
        if(modal.querySelector("#edit-alum-firstName")) modal.querySelector("#edit-alum-firstName").value = firstName;
        if(modal.querySelector("#edit-alum-lastName")) modal.querySelector("#edit-alum-lastName").value = lastName;
        if(modal.querySelector("#edit-alum-contactInfo")) modal.querySelector("#edit-alum-contactInfo").value = contactInfo;
        if(modal.querySelector("#edit-alum-status")) modal.querySelector("#edit-alum-status").value = status;
      }

      // 2. Handle Alumni Courses edit modal
      else if (modalId === "editModal-courses") {
        const id = button.getAttribute("data-id");
        const firstName = button.getAttribute("data-firstName");
        const lastName = button.getAttribute("data-lastName");
        const degree = button.getAttribute("data-degree");
        const program = button.getAttribute("data-program");
        const gradYear = button.getAttribute("data-gradYear");
        const gradId = button.getAttribute("data-grad-id");

        if(modal.querySelector("#edit-alum-id")) modal.querySelector("#edit-alum-id").value = id;
        if(modal.querySelector("#edit-alum-old-id")) modal.querySelector("#edit-alum-old-id").value = id;
        if(modal.querySelector("#edit-alum-firstName")) modal.querySelector("#edit-alum-firstName").value = firstName;
        if(modal.querySelector("#edit-alum-lastName")) modal.querySelector("#edit-alum-lastName").value = lastName;
        if(modal.querySelector("#edit-degree-id")) modal.querySelector("#edit-degree-id").value = degree;
        if(modal.querySelector("#edit-program-id")) modal.querySelector("#edit-program-id").value = program;
        if(modal.querySelector("#edit-grad-year")) modal.querySelector("#edit-grad-year").value = gradYear;
        if(modal.querySelector("#edit-grad-id")) modal.querySelector("#edit-grad-id").value = gradId;
      }

      // 3. Handle Alumni Employment edit modal
      else if (modalId === "editModal-employment") {
        const empId = button.getAttribute("data-emp-id");
        const id = button.getAttribute("data-id");
        const firstName = button.getAttribute("data-firstName");
        const lastName = button.getAttribute("data-lastName");
        const position = button.getAttribute("data-position");
        const company = button.getAttribute("data-company");
        const location = button.getAttribute("data-location");
        const startDate = button.getAttribute("data-startDate");
        const endDate = button.getAttribute("data-endDate"); // May be empty string if NULL

        if(modal.querySelector("#edit-emp-id")) modal.querySelector("#edit-emp-id").value = empId;
        if(modal.querySelector("#edit-alum-id")) modal.querySelector("#edit-alum-id").value = id;
        if(modal.querySelector("#edit-alum-old-id")) modal.querySelector("#edit-alum-old-id").value = id;
        if(modal.querySelector("#edit-alum-firstName")) modal.querySelector("#edit-alum-firstName").value = firstName;
        if(modal.querySelector("#edit-alum-lastName")) modal.querySelector("#edit-alum-lastName").value = lastName;
        
        if(modal.querySelector("#edit-position-id")) modal.querySelector("#edit-position-id").value = position;
        if(modal.querySelector("#edit-company-id")) modal.querySelector("#edit-company-id").value = company;
        if(modal.querySelector("#edit-location-id")) modal.querySelector("#edit-location-id").value = location;
        
        if(modal.querySelector("#edit-start-date")) modal.querySelector("#edit-start-date").value = startDate;
        if(modal.querySelector("#edit-end-date")) modal.querySelector("#edit-end-date").value = endDate;
      }

      // 4. Handle Program edit modal
      else if (modalId === "editModal-program") {
        const id = button.getAttribute("data-id");
        const name = button.getAttribute("data-name");
        const college = button.getAttribute("data-college");

        if(modal.querySelector("#edit-program-id")) modal.querySelector("#edit-program-id").value = id;
        if(modal.querySelector("#edit-program-old-id")) modal.querySelector("#edit-program-old-id").value = id;
        if(modal.querySelector("#edit-program-name")) modal.querySelector("#edit-program-name").value = name;
        if(modal.querySelector("#edit-program-college")) modal.querySelector("#edit-program-college").value = college;
      }

      // 5. Handle College edit modal
      else if (modalId === "editModal-college") {
        const id = button.getAttribute("data-id");
        const name = button.getAttribute("data-name");

        if(modal.querySelector("#edit-college-id")) modal.querySelector("#edit-college-id").value = id;
        if(modal.querySelector("#edit-college-old-id")) modal.querySelector("#edit-college-old-id").value = id;
        if(modal.querySelector("#edit-college-name")) modal.querySelector("#edit-college-name").value = name;
      }

      // 6. Handle Degree edit modal
      else if (modalId === "editModal-degree") {
        const id = button.getAttribute("data-id");
        const abbreviation = button.getAttribute("data-abbreviation");
        const name = button.getAttribute("data-name");

        if(modal.querySelector("#edit-degree-id")) modal.querySelector("#edit-degree-id").value = id;
        if(modal.querySelector("#edit-degree-old-id")) modal.querySelector("#edit-degree-old-id").value = id;
        if(modal.querySelector("#edit-degree-abbreviation")) modal.querySelector("#edit-degree-abbreviation").value = abbreviation;
        if(modal.querySelector("#edit-degree-name")) modal.querySelector("#edit-degree-name").value = name;
      }

      // 7. Handle Status edit modal
      else if (modalId === "editModal-status") {
        const id = button.getAttribute("data-id");
        const name = button.getAttribute("data-name");

        if(modal.querySelector("#edit-status-id")) modal.querySelector("#edit-status-id").value = id;
        if(modal.querySelector("#edit-status-old-id")) modal.querySelector("#edit-status-old-id").value = id;
        if(modal.querySelector("#edit-status-name")) modal.querySelector("#edit-status-name").value = name;
      }

      // 8. Handle Company edit modal
      else if (modalId === "editModal-company") {
        const id = button.getAttribute("data-id");
        const name = button.getAttribute("data-name");

        if(modal.querySelector("#edit-company-id")) modal.querySelector("#edit-company-id").value = id;
        if(modal.querySelector("#edit-company-old-id")) modal.querySelector("#edit-company-old-id").value = id;
        if(modal.querySelector("#edit-company-name")) modal.querySelector("#edit-company-name").value = name;
      }

      else if (modalId === "editModal-location") {
        const id = button.getAttribute("data-id");
        const country = button.getAttribute("data-country");
        const region = button.getAttribute("data-region");
        const city = button.getAttribute("data-city");

        if(modal.querySelector("#edit-location-id")) modal.querySelector("#edit-location-id").value = id;
        if(modal.querySelector("#edit-location-old-id")) modal.querySelector("#edit-location-old-id").value = id;
        if(modal.querySelector("#edit-location-country")) modal.querySelector("#edit-location-country").value = country;
        if(modal.querySelector("#edit-location-region")) modal.querySelector("#edit-location-region").value = region;
        if(modal.querySelector("#edit-location-city")) modal.querySelector("#edit-location-city").value = city;
      }

      // Handle Delete modal (for info, courses, employment, program, college, degree, status, AND company)
      else if (modalId === "deleteModal") {
        const idToDelete = button.getAttribute("data-id"); // General ID for deletion

        if (modal.querySelector("#delete-alum-id") && idToDelete) {
          modal.querySelector("#delete-alum-id").value = idToDelete;
        } else if (modal.querySelector("#delete-grad-id") && button.getAttribute("data-grad-id")) {
          modal.querySelector("#delete-grad-id").value = button.getAttribute("data-grad-id");
        } else if (modal.querySelector("#delete-emp-id") && button.getAttribute("data-emp-id")) {
          modal.querySelector("#delete-emp-id").value = button.getAttribute("data-emp-id");
        } else if (modal.querySelector("#delete-program-id") && idToDelete) {
            modal.querySelector("#delete-program-id").value = idToDelete;
        } else if (modal.querySelector("#delete-college-id") && idToDelete) {
            modal.querySelector("#delete-college-id").value = idToDelete;
        } else if (modal.querySelector("#delete-degree-id") && idToDelete) {
            modal.querySelector("#delete-degree-id").value = idToDelete;
        } else if (modal.querySelector("#delete-status-id") && idToDelete) {
            modal.querySelector("#delete-status-id").value = idToDelete;
        } else if (modal.querySelector("#delete-company-id") && idToDelete) {
            modal.querySelector("#delete-company-id").value = idToDelete;
        } else if (modal.querySelector("#delete-location-id") && idToDelete) {
            modal.querySelector("#delete-location-id").value = idToDelete;
        }
      }

      // Show the modal
      modal.style.display = "flex";
    });
  });

  // Handle URL parameters for displaying success, error, or duplicate modals
  const urlParams = new URLSearchParams(window.location.search);
  if (urlParams.has('error') && urlParams.get('error') === 'duplicate') {
    const duplicateIdErrorModal = document.getElementById('duplicateIdErrorModal');
    if (duplicateIdErrorModal) {
      duplicateIdErrorModal.style.display = 'flex';
    }
  }

  // Close modals
  closeButtons.forEach(button => {
    button.addEventListener("click", () => {
      const modal = button.closest(".modal");
      if (modal) {
        modal.style.display = "none";
        // Remove URL parameters related to modal status if they exist
        const newUrlParams = new URLSearchParams(window.location.search);
        if (newUrlParams.has('add')) newUrlParams.delete('add');
        if (newUrlParams.has('update')) newUrlParams.delete('update');
        if (newUrlParams.has('error')) newUrlParams.delete('error');
        if (newUrlParams.toString() !== urlParams.toString()) {
          history.replaceState(null, '', window.location.pathname + (newUrlParams.toString() ? '?' + newUrlParams.toString() : ''));
        }
      }
    });
  });

  // Close if clicking outside the modal
  window.addEventListener("click", event => {
    if (event.target.classList.contains("modal")) {
      event.target.style.display = "none";
      // Remove URL parameters related to modal status if they exist
      const newUrlParams = new URLSearchParams(window.location.search);
      if (newUrlParams.has('add')) newUrlParams.delete('add');
      if (newUrlParams.has('update')) newUrlParams.delete('update');
      if (newUrlParams.has('error')) newUrlParams.delete('error');
      if (newUrlParams.toString() !== urlParams.toString()) {
        history.replaceState(null, '', window.location.pathname + (newUrlParams.toString() ? '?' + newUrlParams.toString() : ''));
      }
    }
  });

  // Live search: update the table-display as the user types (debounced)
  const searchInput = document.getElementById('search-box');
  if (searchInput) {
    let debounceTimer = null;

    const performSearch = (value) => {
      const viewInput = document.querySelector("input[name='view-table']");
      const view = viewInput ? viewInput.value : (document.getElementById('view-table-select') ? document.getElementById('view-table-select').value : '');
      const params = new URLSearchParams();
      if (view) params.set('view-table', view);
      if (value) params.set('search', value);
      params.set('page', '1');

      const url = window.location.pathname + '?' + params.toString();

      fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
        .then(response => response.text())
        .then(html => {
          const parser = new DOMParser();
          const doc = parser.parseFromString(html, 'text/html');
          const newTable = doc.querySelector('.table-display');
          const currentTable = document.querySelector('.table-display');
          if (newTable && currentTable) {
            currentTable.innerHTML = newTable.innerHTML;
          }
          // Update the URL so the search can be shared/bookmarked
          history.replaceState(null, '', url);
        })
        .catch(err => console.error('Live search error:', err));
    };

    searchInput.addEventListener('input', (e) => {
      clearTimeout(debounceTimer);
      debounceTimer = setTimeout(() => performSearch(e.target.value.trim()), 300);
    });
  }
});