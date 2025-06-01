
document.getElementById('loginForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    
    const username = document.getElementById('username').value;
    const password = document.getElementById('password').value;
    const loginBtn = document.getElementById('loginBtn');
    const loading = document.getElementById('loading');
    
    // Show loading
    loading.style.display = 'inline-block';
    loginBtn.disabled = true;
    
    try {
        const response = await fetch('../../api/auth/index.php?action=login', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                username: username,
                password: password
            })
        });
        
        const data = await response.json();
        
        if (response.ok) {
            showAlert('Đăng nhập thành công! Đang chuyển hướng...', 'success');
            
            // Redirect based on role
            setTimeout(() => {
                if (data.user.role === 'admin') {
                    window.location.href = '../admin/index.html';
                } else {
                    window.location.href = '../../index.php';
                }
            }, 1500);
        } else {
            showAlert(data.message || 'Có lỗi xảy ra', 'error');
        }
    } catch (error) {
        showAlert('Có lỗi kết nối. Vui lòng thử lại!', 'error');
    } finally {
        loading.style.display = 'none';
        loginBtn.disabled = false;
    }
});

function showAlert(message, type) {
    const alertContainer = document.getElementById('alertContainer');
    alertContainer.innerHTML = `<div class="alert alert-${type}">${message}</div>`;
    
    setTimeout(() => {
        alertContainer.innerHTML = '';
    }, 5000);
}
