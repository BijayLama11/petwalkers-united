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
          showStatus("success", "Login successful!");
          // Redirect to the admin dashboard after a short delay
          setTimeout(() => {
            window.location.href = "backend/modules/admin/dashboard.php";
          }, 1000);
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

      // Client-side form validation (optional, as PHP script also validates)
      const password = document.getElementById("password").value;
      const confirmPassword = document.getElementById("confirmPassword").value;
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
          showStatus("success", "Registration successful! You can now log in.");
          // Redirect to the login page after a short delay
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
