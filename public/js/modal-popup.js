document.addEventListener("DOMContentLoaded", function() {
  const modalTriggers = document.querySelectorAll(".btn-modal-trigger");
  const closeButtons = document.querySelectorAll(".close");

  modalTriggers.forEach(button => {
    button.addEventListener("click", () => {
      const modalId = button.getAttribute("data-target");
      const modal = document.getElementById(modalId);
      if (!modal) return;

      // Handle Alumni Info edit modal
      if (modalId === "editModal-info") {
        const id = button.getAttribute("data-id");
        const firstName = button.getAttribute("data-firstname");
        const lastName = button.getAttribute("data-lastname");
        const contactInfo = button.getAttribute("data-email");
        const status = button.getAttribute("data-status");

        modal.querySelector("#edit-alum-id").value = id;
        modal.querySelector("#edit-alum-old-id").value = id;
        modal.querySelector("#edit-alum-firstName").value = firstName;
        modal.querySelector("#edit-alum-lastName").value = lastName;
        modal.querySelector("#edit-alum-contactInfo").value = contactInfo;
        modal.querySelector("#edit-alum-status").value = status;
      }

      // Handle Alumni Courses edit modal
      else if (modalId === "editModal-courses") {
        const id = button.getAttribute("data-id");
        const firstName = button.getAttribute("data-firstName");
        const lastName = button.getAttribute("data-lastName");
        const degree = button.getAttribute("data-degree");
        const program = button.getAttribute("data-program");
        const gradYear = button.getAttribute("data-gradYear");
        const gradId = button.getAttribute("data-grad-id");

        modal.querySelector("#edit-alum-id").value = id;
        modal.querySelector("#edit-alum-old-id").value = id;
        modal.querySelector("#edit-alum-firstName").value = firstName;
        modal.querySelector("#edit-alum-lastName").value = lastName;
        modal.querySelector("#edit-degree-id").value = degree;
        modal.querySelector("#edit-program-id").value = program;
        modal.querySelector("#edit-grad-year").value = gradYear;
        modal.querySelector("#edit-grad-id").value = gradId;
      }

      // Handle Delete modal (for BOTH info and courses)
      else if (modalId === "deleteModal") {
        const alumId = button.getAttribute("data-id");
        const gradId = button.getAttribute("data-grad-id");

        if (alumId) {
          // This is a delete for alumni-info
          const deleteInput = modal.querySelector("#delete-alum-id");
          if (deleteInput) {
            deleteInput.value = alumId;
          }
        } else if (gradId) {
          // This is a delete for alumni-courses
          const deleteInput = modal.querySelector("#delete-grad-id");
          if (deleteInput) {
            deleteInput.value = gradId;
          }
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
});