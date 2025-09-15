// js/admin.js
document.addEventListener("DOMContentLoaded", function () {
  const addServiceForm = document.getElementById("add-service-form");
  const servicesTable = document.getElementById("services-table");
  const statusMessage = document.getElementById("status-message");

  // NEW: User management elements
  const addUserForm = document.getElementById("add-user-form");
  const usersTable = document.getElementById("users-table");

  function showStatus(type, message) {
    if (statusMessage) {
      statusMessage.textContent = message;
      statusMessage.className = `status ${type}`;
      statusMessage.style.display = 'block';
      
      // Don't auto-hide info messages as quickly
      const hideDelay = type === 'info' ? 2000 : 5000;
      
      if (type !== 'info') {
        setTimeout(() => {
          statusMessage.className = "status";
          statusMessage.style.display = 'none';
        }, hideDelay);
      }
    }
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
          setTimeout(() => location.reload(), 1500);
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

  // Handle User Management Forms
  if (addUserForm) {
    addUserForm.addEventListener('submit', async function(e) {
      e.preventDefault();
      
      // Get form data
      const formData = new FormData(addUserForm);
      
      // Basic client-side validation
      const firstName = formData.get('firstName').trim();
      const lastName = formData.get('lastName').trim();
      const email = formData.get('email').trim();
      const phone = formData.get('phone').trim();
      const password = formData.get('password');
      const role = formData.get('role');
      
      if (!firstName || !lastName || !email || !phone || !password || !role) {
        showStatus('error', 'All fields are required.');
        return;
      }
      
      if (password.length < 6) {
        showStatus('error', 'Password must be at least 6 characters long.');
        return;
      }
      
      // Email validation
      const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
      if (!emailRegex.test(email)) {
        showStatus('error', 'Please enter a valid email address.');
        return;
      }
      
      // Show loading message
      showStatus('info', 'Adding user...');
      
      try {
        const response = await fetch('add_user.php', {
          method: 'POST',
          body: formData
        });
        
        const result = await response.json();
        console.log('Add user response:', result); // Debug log
        
        if (response.ok && result.success) {
          showStatus('success', result.message);
          addUserForm.reset();
          setTimeout(() => location.reload(), 1500);
        } else {
          showStatus('error', result.message || 'Failed to add user');
        }
      } catch (err) {
        console.error('Add user error:', err);
        showStatus('error', 'Network error occurred while adding user.');
      }
    });
  }

  // Handle User Deletion
  if (usersTable) {
    usersTable.addEventListener("click", async function (e) {
      if (e.target.classList.contains("delete-user-btn")) {
        const userId = e.target.dataset.id;
        const userName = e.target.closest('tr').querySelector('td:first-child').textContent + ' ' + 
                        e.target.closest('tr').querySelector('td:nth-child(2)').textContent;
        
        if (confirm(`Are you sure you want to delete user "${userName}"? This action cannot be undone.`)) {
          const formData = new FormData();
          formData.append("id", userId);
          
          try {
            const response = await fetch("delete_user.php", {
              method: "POST",
              body: formData,
            });
            const result = await response.json();
            
            if (response.ok && result.success) {
              showStatus("success", result.message);
              // Remove the row from the table
              e.target.closest("tr").remove();
            } else {
              showStatus("error", result.message || "Failed to delete user");
            }
          } catch (error) {
            showStatus("error", "An unexpected error occurred while deleting user.");
            console.error('Delete user error:', error);
          }
        }
      }
    });
  }
});