// js/auth.js

document.addEventListener("DOMContentLoaded", function () {
  const loginForm = document.getElementById("login-form");
  const registerForm = document.getElementById("register-form");
  const statusMessage = document.getElementById("status-message");

  // Function to show status messages
  function showStatus(type, message) {
    if (statusMessage) {
      statusMessage.textContent = message;
      statusMessage.className = `status ${type}`;
      setTimeout(() => {
        statusMessage.className = "status";
      }, 5000);
    }
  }

  // Handle Login Form Submission
  if (loginForm) {
    loginForm.addEventListener("submit", async function (e) {
      e.preventDefault();
      const formData = new FormData(this);

      try {
        const response = await fetch("backend/modules/auth/login.php", {
          method: "POST",
          body: formData,
        });
        const result = await response.json();

        if (response.ok) {
          showStatus("success", `Welcome ${result.firstName}! Redirecting...`);
          
          // Role-based redirection
          setTimeout(() => {
            if (result.role === 'admin') {
              // Open admin dashboard in new tab
              window.open("backend/modules/admin/dashboard.php", "_blank");
              // Redirect current tab to home
              window.location.href = "index.html";
            } else {
              // Regular user - redirect to user dashboard
              window.location.href = "user-dashboard.php";
            }
          }, 1500);
        } else {
          showStatus("error", result.message);
        }
      } catch (error) {
        showStatus("error", "An unexpected error occurred. Please try again.");
      }
    });
  }

  // Handle Registration Form Submission
  if (registerForm) {
    registerForm.addEventListener("submit", async function (e) {
      e.preventDefault();
      const formData = new FormData(this);

      // Client-side form validation
      const password = document.getElementById("password").value;
      const confirmPassword = document.getElementById("confirm-password").value;
      if (password !== confirmPassword) {
        showStatus("error", "Passwords do not match.");
        return;
      }

      try {
        const response = await fetch("backend/modules/auth/register.php", {
          method: "POST",
          body: formData,
        });
        const result = await response.json();

        if (response.ok) {
          showStatus("success", "Registration successful! You can now log in as a user.");
          // Clear form
          this.reset();
          // Redirect to login page after a delay
          setTimeout(() => {
            window.location.href = "login.html";
          }, 2000);
        } else {
          showStatus("error", result.message);
        }
      } catch (error) {
        showStatus("error", "An unexpected error occurred. Please try again.");
      }
    });
  }
});