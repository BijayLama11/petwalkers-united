document.addEventListener('DOMContentLoaded', function() {
  const form = document.getElementById('contact-form');
  
  if (!form) return;

  function createNotificationContainer() {
    let container = document.getElementById('notification-container');
    if (!container) {
      container = document.createElement('div');
      container.id = 'notification-container';
      container.style.cssText = `
        position: fixed;
        top: 24px;
        right: 24px;
        z-index: 1000;
        pointer-events: none;
        max-width: 384px;
      `;
      document.body.appendChild(container);
    }
    return container;
  }

  function showNotification(type, title, message, duration = 4500) {
    const container = createNotificationContainer();
    
    const notification = document.createElement('div');
    notification.style.cssText = `
      background: white;
      border-radius: 8px;
      box-shadow: 0 6px 16px rgba(0, 0, 0, 0.12);
      border: 1px solid #f0f0f0;
      margin-bottom: 16px;
      padding: 16px 24px;
      pointer-events: auto;
      transform: translateX(100%);
      transition: all 0.3s cubic-bezier(0.78, 0.14, 0.15, 0.86);
      position: relative;
      overflow: hidden;
      min-width: 320px;
    `;

    const borderColor = type === 'success' ? '#52c41a' : '#ff4d4f';
    const iconColor = type === 'success' ? '#52c41a' : '#ff4d4f';
    
    notification.style.borderLeft = `4px solid ${borderColor}`;

    const icon = type === 'success' ? 
      `<svg width="16" height="16" viewBox="0 0 16 16" fill="${iconColor}">
         <path d="M13.854 3.646a.5.5 0 0 1 0 .708l-7 7a.5.5 0 0 1-.708 0l-3.5-3.5a.5.5 0 1 1 .708-.708L6.5 10.293l6.646-6.647a.5.5 0 0 1 .708 0z"/>
       </svg>` :
      `<svg width="16" height="16" viewBox="0 0 16 16" fill="${iconColor}">
         <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zM8 4a.905.905 0 0 0-.9.995l.35 3.507a.552.552 0 0 0 1.1 0l.35-3.507A.905.905 0 0 0 8 4zm.002 6a1 1 0 1 0 0 2 1 1 0 0 0 0-2z"/>
       </svg>`;

    notification.innerHTML = `
      <div style="display: flex; align-items: flex-start; gap: 12px;">
        <div style="flex-shrink: 0; margin-top: 2px;">
          ${icon}
        </div>
        <div style="flex: 1;">
          <div style="font-weight: 600; color: #262626; font-size: 14px; line-height: 1.4; margin-bottom: 4px;">
            ${title}
          </div>
          <div style="color: #595959; font-size: 14px; line-height: 1.4;">
            ${message}
          </div>
        </div>
        <button onclick="this.parentElement.parentElement.remove()" 
                style="border: none; background: none; color: #8c8c8c; cursor: pointer; font-size: 16px; padding: 0; margin-left: 8px; flex-shrink: 0;">
          ×
        </button>
      </div>
    `;

    container.appendChild(notification);

    setTimeout(() => {
      notification.style.transform = 'translateX(0)';
    }, 10);

    setTimeout(() => {
      notification.style.transform = 'translateX(100%)';
      notification.style.opacity = '0';
      setTimeout(() => {
        if (notification.parentNode) {
          notification.remove();
        }
      }, 300);
    }, duration);
  }

  const fields = {
    name: document.getElementById('name'),
    email: document.getElementById('email'),
    phone: document.getElementById('phone'),
    service: document.getElementById('service'),
    date: document.getElementById('date'),
    time: document.getElementById('time'),
    consent: document.getElementById('consent')
  };

  const errorElements = {
    name: document.getElementById('err-name'),
    email: document.getElementById('err-email'),
    phone: document.getElementById('err-phone'),
    service: document.getElementById('err-service'),
    consent: document.getElementById('err-consent')
  };

  function showError(fieldName, message) {
    const errorElement = errorElements[fieldName];
    if (errorElement) {
      errorElement.textContent = message;
      errorElement.style.display = 'block';
      errorElement.style.color = '#ff4d4f';
      errorElement.style.fontSize = '12px';
      errorElement.style.marginTop = '4px';
    }
  }

  function clearAllErrors() {
    Object.values(errorElements).forEach(function(errorElement) {
      if (errorElement) {
        errorElement.textContent = '';
        errorElement.style.display = 'none';
      }
    });
  }

  function clearStatus() {
    const statusDiv = document.getElementById('form-status');
    if (statusDiv) {
      statusDiv.style.display = 'none';
    }
  }

  function validateField(fieldName) {
    const field = fields[fieldName];
    if (!field) return true;

    switch (fieldName) {
      case 'name':
        if (!field.value.trim()) {
          showError('name', 'Name is required.');
          return false;
        }
        if (field.value.trim().length < 2) {
          showError('name', 'Name must be at least 2 characters.');
          return false;
        }
        break;

      case 'email':
        if (!field.value.trim()) {
          showError('email', 'Email is required.');
          return false;
        }
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailRegex.test(field.value.trim())) {
          showError('email', 'Please enter a valid email address.');
          return false;
        }
        break;

      case 'phone':
        if (field.value.trim()) {
          const phoneRegex = /^[0-9 +()-]{8,}$/;
          if (!phoneRegex.test(field.value.trim())) {
            showError('phone', 'Please enter a valid phone number (8+ digits).');
            return false;
          }
        }
        break;

      case 'service':
        if (!field.value) {
          showError('service', 'Please select a service.');
          return false;
        }
        break;

      case 'consent':
        if (!field.checked) {
          showError('consent', 'You must agree to the privacy notice.');
          return false;
        }
        break;
    }
    return true;
  }

  function validateForm() {
    clearAllErrors();
    let isValid = true;

    const fieldsToValidate = ['name', 'email', 'phone', 'service', 'consent'];
    fieldsToValidate.forEach(function(fieldName) {
      if (!validateField(fieldName)) {
        isValid = false;
      }
    });

    if (!fields.date.value) {
      isValid = false;
    }
    
    if (!fields.time.value) {
      isValid = false;
    }

    return isValid;
  }

  form.addEventListener('submit', function(e) {
    e.preventDefault();
    clearStatus();

    if (validateForm()) {
      showNotification(
        'success',
        'Message Sent Successfully',
        'Thanks for reaching out. We will get back to you within one business day.',
        5000
      );
      
      setTimeout(function() {
        form.reset();
      }, 1000);
      
    } else {
      showNotification(
        'error',
        'Form Validation Failed',
        'Please fix the highlighted errors and try again.',
        4000
      );
    }
  });

  form.addEventListener('reset', function() {
    clearAllErrors();
    clearStatus();
  });

  Object.keys(fields).forEach(function(fieldName) {
    const field = fields[fieldName];
    if (field && fieldName !== 'date' && fieldName !== 'time') {
      field.addEventListener('blur', function() {
        if (field.value.trim() || fieldName === 'consent') {
          validateField(fieldName);
        }
      });

      field.addEventListener('focus', function() {
        const errorElement = errorElements[fieldName];
        if (errorElement) {
          errorElement.style.display = 'none';
        }
      });
    }
  });

  const yearSpan = document.getElementById('year');
  if (yearSpan) {
    yearSpan.textContent = new Date().getFullYear();
  }
});