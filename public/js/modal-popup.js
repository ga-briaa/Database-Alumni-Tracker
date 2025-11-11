document.addEventListener("DOMContentLoaded", function() {
  const modalTriggers = document.querySelectorAll(".btn-modal-trigger");
  const closeModal = document.querySelectorAll(".close");

  modalTriggers.forEach(button => {
    button.onclick = function() {
      const modalId = button.getAttribute("data-target");
      const modal = document.getElementById(modalId);

      if (modal) {
        modal.style.display = "flex";
      }
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