document.addEventListener("DOMContentLoaded", function() {
  const modalTriggers = document.querySelectorAll(".btn-modal-trigger");
  const closeModal = document.querySelectorAll(".close");

  modalTriggers.forEach(button => {
    button.onclick = function() {
      const modalId = button.getAttribute("data-target");
      const modal = document.getElementById(modalId);

      if (!modal) return;

      if (modalId === "editModal") {
        const id = button.getAttribute("data-id");
        const firstName = button.getAttribute("data-firstName");
        const lastName = button.getAttribute("data-lastName");
        const contactInfo = button.getAttribute("data-email");
        const status = button.getAttribute("data-status");

        modal.querySelector("#edit-alum-id").value = id;
        modal.querySelector("#edit-alum-old-id").value = id;

        modal.querySelector("#edit-alum-firstName").value = firstName;
        modal.querySelector("#edit-alum-lastName").value = lastName;
        modal.querySelector("#edit-alum-contactInfo").value = contactInfo;
        modal.querySelector("#edit-alum-status").value = status;

      } else if (modalId === "deleteModal") {
        const id = button.getAttribute("data-id");

        modal.querySelector("#delete-alum-id").value = id;
      }

      modal.style.display = "flex";
    } 
  });

  closeModal.forEach(button => {
    button.onclick = function() {
      const modal = button.closest(".modal");
      if (modal) {
        modal.style.display = "none";
      }
    }
  });

  window.onclick = function(event) {
    if (event.target.classList.contains("modal")) {
      event.target.style.display = "none";
    }
  }
});