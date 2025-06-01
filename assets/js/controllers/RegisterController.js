
// Password strength checker
document.getElementById('password').addEventListener('input', function() {
    const password = this.value;
    const strengthDiv = document.getElementById('passwordStrength');
    
    if (password.length === 0) {
        strengthDiv.style.display = 'none';
        return;
    }
    
    strengthDiv.style.display = 'block';
    
    let strength = 0;
    if (password.length >= 8) strength++;
    if (/[a-z]/.test(password)) strength++;
    if (/[A-Z]/.test(password)) strength++;
    if (/[0-9]/.test(password)) strength++;
    if (/[^A-Za-z0-9]/.test(password)) strength++;
    
    if (strength < 3) {
        strengthDiv.className = 'password-strength weak';
        strengthDiv.textContent = 'Mật khẩu yếu';
    } else if (strength < 4) {
        strengthDiv.className = 'password-strength medium';
        strengthDiv.textContent = 'Mật khẩu trung bình';
    } else {
        strengthDiv.className = 'password-strength strong';
        strengthDiv.textContent = 'Mật khẩu mạnh';
    }
});

document.getElementById('registerForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    
    const formData = {
        full_name: document.getElementById('full_name').value,
        username: document.getElementById('username').value,
        email: document.getElementById('email').value,
        password: document.getElementById('password').value,
        confirm_password: document.getElementById('confirm_password').value
    };
    
    const registerBtn = document.getElementById('registerBtn');
    const loading = document.getElementById('loading');
    
    // Validate form
    if (formData.password !== formData.confirm_password) {
        showAlert('Mật khẩu xác nhận không khớp!', 'error');
        return;
    }
    
    if (formData.password.length < 6) {
        showAlert('Mật khẩu phải có ít nhất 6 ký tự!', 'error');
        return;
    }
    
    // Show loading
    loading.style.display = 'inline-block';
    registerBtn.disabled = true;
    
    try {
        const response = await fetch('../../api/auth/index.php?action=register', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(formData)
        });
        
        const data = await response.json();
        
        if (response.ok) {
            showAlert('Đăng ký thành công! Đang chuyển đến trang đăng nhập...', 'success');
            setTimeout(() => {
                window.location.href = 'login.html';
            }, 2000);
        } else {
            showAlert(data.message || 'Có lỗi xảy ra', 'error');
        }
    } catch (error) {
        showAlert('Có lỗi kết nối. Vui lòng thử lại!', 'error');
    } finally {
        loading.style.display = 'none';
        registerBtn.disabled = false;
    }
});

function showAlert(message, type) {
    const alertContainer = document.getElementById('alertContainer');
    alertContainer.innerHTML = `<div class="alert alert-${type}">${message}</div>`;
    
    setTimeout(() => {
        alertContainer.innerHTML = '';
    }, 5000);
}