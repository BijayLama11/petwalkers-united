// js/admin.js
document.addEventListener("DOMContentLoaded", function () {
  const addServiceForm = document.getElementById("add-service-form");
  const servicesTable = document.getElementById("services-table");
  const statusMessage = document.getElementById("status-message");

  // NEW: User management elements
  const addUserForm = document.getElementById("add-user-form");
  const usersTable = document.getElementById("users-table");

  function showStatus(type, message) {
    statusMessage.textContent = message;
    statusMessage.className = `status ${type}`;
    setTimeout(() => {
      statusMessage.className = "status";
    }, 5000);
  }

  // Handle Service Management Forms (existing code)
  if (addServiceForm) {
    addServiceForm.addEventListener("submit", async function (e) {
      e.preventDefault();
      const formData = new FormData(this);
      try {
        const response = await fetch("add_service.php", {
          method: "POST",
          body: formData,
        });
        const result = await response.json();
        if (response.ok) {
          showStatus("success", result.message);
          this.reset();
          location.reload();
        } else {
          showStatus("error", result.message);
        }
      } catch (error) {
        showStatus("error", "An unexpected error occurred.");
      }
    });
  }

  if (servicesTable) {
    servicesTable.addEventListener("click", async function (e) {
      if (e.target.classList.contains("delete-btn")) {
        const serviceId = e.target.dataset.id;
        if (confirm("Are you sure you want to delete this service?")) {
          const formData = new FormData();
          formData.append("id", serviceId);
          try {
            const response = await fetch("delete_service.php", {
              method: "POST",
              body: formData,
            });
            const result = await response.json();
            if (response.ok) {
              showStatus("success", result.message);
              e.target.closest("tr").remove();
            } else {
              showStatus("error", result.message);
            }
          } catch (error) {
            showStatus("error", "An unexpected error occurred.");
          }
        }
      }
    });
  }

  // NEW: Handle User Management Forms
  if (addUserForm) {
    addUserForm.addEventListener("submit", async function (e) {
      e.preventDefault();
      const formData = new FormData(this);
      try {
        const response = await fetch("add_user.php", {
          method: "POST",
          body: formData,
        });
        const result = await response.json();
        if (response.ok) {
          showStatus("success", result.message);
          this.reset();
          location.reload(); // Simple reload to refresh the table
        } else {
          showStatus("error", result.message);
        }
      } catch (error) {
        showStatus("error", "An unexpected error occurred.");
      }
    });
  }

  if (usersTable) {
    usersTable.addEventListener("click", async function (e) {
      if (e.target.classList.contains("delete-user-btn")) {
        const userId = e.target.dataset.id;
        if (confirm("Are you sure you want to delete this user?")) {
          const formData = new FormData();
          formData.append("id", userId);
          try {
            const response = await fetch("delete_user.php", {
              method: "POST",
              body: formData,
            });
            const result = await response.json();
            if (response.ok) {
              showStatus("success", result.message);
              e.target.closest("tr").remove();
            } else {
              showStatus("error", result.message);
            }
          } catch (error) {
            showStatus("error", "An unexpected error occurred.");
          }
        }
      }
    });
  }
});
