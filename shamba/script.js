/* Login Script */
function toggleForm(type) {
    const userForm = document.getElementById('user-login-form');
    const adminForm = document.getElementById('admin-login-form');
    const toggleOptions = document.querySelectorAll('.toggle-option');
    
    // Clear form fields
    userForm.reset();
    adminForm.reset();
    
    if (type === 'user') {
        userForm.classList.add('active');
        adminForm.classList.remove('active');
        toggleOptions[0].classList.add('active');
        toggleOptions[1].classList.remove('active');
    } else {
        adminForm.classList.add('active');
        userForm.classList.remove('active');
        toggleOptions[1].classList.add('active');
        toggleOptions[0].classList.remove('active');
    }
}

// Form validation for login and reset password
function validateForm(formId) {
    const form = document.getElementById(formId);
    const username = form.querySelector('input[name="username"]').value;
    const inputs = form.querySelectorAll('input');
    let isValid = true;

    inputs.forEach(input => {
        if (input.value.trim() === '') {
            isValid = false;
        }
    });

    if (!isValid) {
        alert('Please fill in all fields');
        return false;
    }

    // Additional validation for reset password forms
    if (formId.includes('login-form') && form.querySelector('input[name="new_password"]')) {
        const newPassword = form.querySelector('input[name="new_password"]').value;
        const confirmPassword = form.querySelector('input[name="confirm_password"]').value;
        
        if (newPassword !== confirmPassword) {
            alert('Passwords do not match');
            return false;
        }
    }

    return true;
}

// Add form submission handlers
document.addEventListener('DOMContentLoaded', function() {
    const userForm = document.getElementById('user-login-form');
    const adminForm = document.getElementById('admin-login-form');

    if (userForm && adminForm) {
        userForm.addEventListener('submit', function(e) {
            if (!validateForm('user-login-form')) {
                e.preventDefault();
            }
        });

        adminForm.addEventListener('submit', function(e) {
            if (!validateForm('admin-login-form')) {
                e.preventDefault(); 
            }
        });
    }
});
