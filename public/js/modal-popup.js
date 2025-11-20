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

      // 3. Handle Alumni Employment edit modal (NEW)
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

      // 5. Handle College edit modal (NEW)
      else if (modalId === "editModal-college") {
        const id = button.getAttribute("data-id");
        const name = button.getAttribute("data-name");

        if(modal.querySelector("#edit-college-id")) modal.querySelector("#edit-college-id").value = id;
        if(modal.querySelector("#edit-college-old-id")) modal.querySelector("#edit-college-old-id").value = id;
        if(modal.querySelector("#edit-college-name")) modal.querySelector("#edit-college-name").value = name;
      }

      // 6. Handle Delete modal (for info, courses, employment, program, AND college)
      else if (modalId === "deleteModal") {
        const alumId = button.getAttribute("data-id");
        const gradId = button.getAttribute("data-grad-id");
        const empId = button.getAttribute("data-emp-id");
        const programId = button.getAttribute("data-id"); // All 'data-id' are unique enough for now

        if (modal.querySelector("#delete-alum-id") && alumId) {
          modal.querySelector("#delete-alum-id").value = alumId;
        } else if (modal.querySelector("#delete-grad-id") && gradId) {
          modal.querySelector("#delete-grad-id").value = gradId;
        } else if (modal.querySelector("#delete-emp-id") && empId) {
          modal.querySelector("#delete-emp-id").value = empId;
        } else if (modal.querySelector("#delete-program-id") && programId) {
            modal.querySelector("#delete-program-id").value = programId;
        } else if (modal.querySelector("#delete-college-id") && alumId) { // Re-using alumId for general data-id
            modal.querySelector("#delete-college-id").value = alumId;
        }
      }

      // Show the modal
      modal.style.display = "flex";
    });
  });

  // Close modals
  closeButtons.forEach(button => {
    button.addEventListener("click", () => {
      const modal = button.closest(".modal");
      if (modal) modal.style.display = "none";
    });
  });

  // Close if clicking outside the modal
  window.addEventListener("click", event => {
    if (event.target.classList.contains("modal")) {
      event.target.style.display = "none";
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