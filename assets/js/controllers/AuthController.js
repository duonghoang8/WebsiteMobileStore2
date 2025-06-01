
// Kiểm tra trạng thái đăng nhập khi trang load
document.addEventListener('DOMContentLoaded', function() {
    checkAuthStatus();
});

async function checkAuthStatus() {
    try {
        const response = await fetch('api/auth/index.php?action=check_session');
        const data = await response.json();
        
        const authSection = document.getElementById('authSection');
        
        if (data.logged_in) {
            // Người dùng đã đăng nhập
            const adminBadge = data.user.role === 'admin' ? '<span class="admin-badge">ADMIN</span>' : '';
            
            authSection.innerHTML = `
                <div class="user-menu">
                    <div class="user-info" onclick="toggleDropdown()">
                        👤 ${data.user.full_name || data.user.username}${adminBadge}
                    </div>
                    <div class="dropdown-menu" id="userDropdown">
                        <a href="#" class="dropdown-item">👤 Thông tin cá nhân</a>
                        <a href="#" class="dropdown-item">📦 Đơn hàng của tôi</a>
                        ${data.user.role === 'admin' ? '<a href="views/admin/index.php" class="dropdown-item">⚙️ Quản trị</a>' : ''}
                        <a href="#" class="dropdown-item" onclick="logout(event)">🚪 Đăng xuất</a>
                    </div>
                </div>
            `;
        } else {
            // Người dùng chưa đăng nhập
            authSection.innerHTML = `
                <div class="auth-links">
                    <a href="views/auth/login.html">Đăng nhập</a>
                    <span> | </span>
                    <a href="views/auth/register.html">Đăng ký</a>
                </div>
            `;
        }
    } catch (error) {
        console.error('Error checking auth status:', error);
        // Hiển thị mặc định nếu có lỗi
        const authSection = document.getElementById('authSection');
        authSection.innerHTML = `
            <div class="auth-links">
                <a href="views/auth/login.html">Đăng nhập</a>
                <span> | </span>
                <a href="views/auth/register.html">Đăng ký</a>
            </div>
        `;
    }
}

function toggleDropdown() {
    const dropdown = document.getElementById('userDropdown');
    dropdown.classList.toggle('show');
}

// Đóng dropdown khi click ra ngoài
document.addEventListener('click', function(event) {
    const userMenu = document.querySelector('.user-menu');
    if (userMenu && !userMenu.contains(event.target)) {
        const dropdown = document.getElementById('userDropdown');
        if (dropdown) {
            dropdown.classList.remove('show');
        }
    }
});

async function logout(event) {
    event.preventDefault();
    
    try {
        const response = await fetch('api/auth/index.php?action=logout', {
            method: 'POST'
        });
        
        if (response.ok) {
            alert('Đăng xuất thành công!');
            window.location.reload();
        } else {
            alert('Có lỗi xảy ra khi đăng xuất!');
        }
    } catch (error) {
        console.error('Error:', error);
        alert('Có lỗi xảy ra khi đăng xuất!');
    }
}
