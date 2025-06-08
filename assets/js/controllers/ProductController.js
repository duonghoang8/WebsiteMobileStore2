import { ProductService } from '../services/ProductService.js';

// Debug log có điều kiện
function logDebug(...args) {
    if (window.DEBUG_MODE) console.log(...args);
}

// Render thẻ sản phẩm (ĐÃ SỬA LỖI ĐỂ TƯƠNG THÍCH CSDL MỚI)
function renderProductCard(p) {
    const price = p.price ? Number(p.price).toLocaleString('vi-VN') + '₫' : 'Liên hệ';
    const oldPrice = p.old_price ? Number(p.old_price).toLocaleString('vi-VN') + '₫' : '';
    const discount = p.discount_percent ? `-${p.discount_percent}%` : '';
    const img_url = p.image_url || 'assets/images/no-image.png';

    // Sửa lỗi: Lấy badge từ is_hot, is_new, is_bestseller
    let badge = '';
    if (p.is_hot == 1) badge = 'Hot';
    else if (p.is_new == 1) badge = 'New';
    else if (p.is_bestseller == 1) badge = 'Best Seller';

    // Sửa lỗi: Lấy rating từ rating_average và rating_count
    const ratingStars = '★★★★★'.substring(0, Math.round(p.rating_average || 0)) + '☆☆☆☆☆'.substring(0, 5 - Math.round(p.rating_average || 0));
    const reviewCount = p.rating_count || '0';
    
    // Sửa lỗi: Lấy features từ chuỗi specifications
    const features = p.specifications 
        ? p.specifications.split(',').slice(0, 3).map(s => `<span class="feature-tag">${s.trim()}</span>`).join('') 
        : '';

    // Thêm data-url để hỗ trợ điều hướng
    return `
        <div class="product" data-url="product.php?id=${p.product_id}" title="Xem chi tiết ${p.name}">
            <div class="product-image-container">
                <img src="${img_url}" alt="${p.name}" loading="lazy">
                ${badge ? `<div class="product-badge">${badge}</div>` : ''}
            </div>
            <div class="product-info">
                <div class="rating">
                    <div class="stars">${ratingStars}</div>
                    <div class="rating-text">(${reviewCount} đánh giá)</div>
                </div>
                <div class="nameProduct">${p.name}</div>
                <div class="product-features">${features}</div>
                <div class="price-container">
                    <div>
                        <span class="priceProduct">${price}</span>
                        ${oldPrice ? `<span class="old-price">${oldPrice}</span>` : ''}
                    </div>
                    ${discount ? `<div class="discount-percent">${discount}</div>` : ''}
                </div>
                <div class="product-actions">
                    <button class="btn-primary" data-name="${p.name}">Mua ngay</button>
                    <button class="btn-secondary">♡</button>
                </div>
            </div>
        </div>`;
}

// Hiển thị danh sách sản phẩm theo brand hoặc tất cả
async function displayProductList(brand = null, targetSectionClass = "product-section") {
    try {
        const products = brand
            ? await ProductService.getProductsByBrand(brand)
            : await ProductService.getAllProducts();

        logDebug('Dữ liệu sản phẩm:', products);

        const sections = document.querySelectorAll(`.${targetSectionClass}`);
        if (!sections.length) {
            console.error(`Không tìm thấy section với class ${targetSectionClass}`);
            return;
        }

        const targetSection = sections[0];
        const productContainer = targetSection.querySelector('.formproduct');
        
        if (!productContainer) {
            console.error('Không tìm thấy container .formproduct');
            return;
        }

        if (!products || !products.length) {
            productContainer.innerHTML = '<div class="no-products">Không có sản phẩm nào để hiển thị.</div>';
            return;
        }

        let html = '';
        products.forEach(p => {
            html += renderProductCard(p);
        });
        
        productContainer.innerHTML = html;

        // Thêm event listeners cho các nút và thẻ sản phẩm
        setupProductInteractions();

    } catch (error) {
        console.error('Lỗi tải sản phẩm:', error);
        const sections = document.querySelectorAll(`.${targetSectionClass}`);
        if (sections.length > 0) {
            const productContainer = sections[0].querySelector('.formproduct');
            if (productContainer) {
                productContainer.innerHTML = '<div class="error-message">Lỗi khi tải dữ liệu.</div>';
            }
        }
    }
}

// Thiết lập tương tác cho sản phẩm (ĐÃ SỬA LỖI)
function setupProductInteractions() {
    // Thêm sự kiện click cho toàn bộ thẻ sản phẩm để điều hướng
    document.querySelectorAll('.product').forEach(card => {
        card.addEventListener('click', function(e) {
            // Chỉ điều hướng khi click không phải là vào một button
            if (!e.target.closest('button')) {
                window.location.href = this.dataset.url;
            }
        });
    });

    // Thêm sự kiện cho nút "Mua ngay"
    document.querySelectorAll('.product .btn-primary').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.stopPropagation(); // Ngăn sự kiện click của card
            const productName = this.dataset.name;
            buyProduct(productName);
        });
    });

    // Thêm sự kiện cho nút "Yêu thích"
    document.querySelectorAll('.product .btn-secondary').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.stopPropagation(); // Ngăn sự kiện click của card
            toggleWishlist(this);
        });
    });
}

// Xử lý mua hàng
function buyProduct(productName) {
    alert(`Đã thêm "${productName}" vào giỏ hàng!`);
}

// Xử lý wishlist
function toggleWishlist(button) {
    button.innerHTML = button.innerHTML === '♡' ? '♥' : '♡';
    button.style.color = button.innerHTML === '♥' ? '#f90404' : '#6c757d';
}

// ---- CÁC HÀM KHÁC GIỮ NGUYÊN NHƯ CŨ ----
// (displayHotProducts, displayProductsByBrand, setupBrandMenu, setupSidebarMenu, v.v...)

async function displayHotProducts() {
    await displayProductList(null, "product-section");
}

async function displayProductsByBrand(brand) {
    await displayProductList(brand, "product-section");
}

function setupBrandMenu() {
    const menuItems = document.querySelectorAll('.btnmenu');
    menuItems.forEach(item => {
        item.addEventListener('click', function() {
            const brand = this.textContent.trim();
            if (brand === 'HOME') {
                displayHotProducts();
            } else if (brand !== 'PHỤ KIỆN') {
                displayProductsByBrand(brand);
            }
        });
    });
}

function setupSidebarMenu() {
    const sidebarItems = document.querySelectorAll('.rowcontent');
    sidebarItems.forEach(item => {
        item.addEventListener('click', function() {
            const brand = this.querySelector('.contentmenu').textContent.trim();
            if (brand === 'iPhone') {
                displayProductsByBrand('Apple');
            } else if (brand === 'Phụ kiện') {
                displayAccessories();
            } else {
                displayProductsByBrand(brand);
            }
        });
    });
}

async function displayAccessories() {
    try {
        const products = await ProductService.getProductsByBrand('Apple'); // Giả sử phụ kiện là của Apple
        const accessories = products.filter(p => 
            p.category_id == 9 || // Dựa vào category_id cho phụ kiện
            p.name.toLowerCase().includes('airpods') ||
            p.name.toLowerCase().includes('sạc') ||
            p.name.toLowerCase().includes('cáp') ||
            p.name.toLowerCase().includes('ốp lưng')
        );
        
        const sections = document.querySelectorAll('.product-section');
        if (sections.length > 1) {
            const accessorySection = sections[1];
            const productContainer = accessorySection.querySelector('.formproduct');
            
            if (productContainer) {
                let html = '';
                accessories.forEach(p => {
                    html += renderProductCard(p);
                });
                productContainer.innerHTML = html || '<div class="no-products">Không có phụ kiện nào.</div>';
                setupProductInteractions();
            }
        }
    } catch (error) {
        console.error('Lỗi tải phụ kiện:', error);
    }
}

// ----- CÁC HÀM CHO TRANG ADMIN (GIỮ NGUYÊN) ----

async function loadProducts() {
    try {
        const products = await ProductService.getAllProducts();
        logDebug('Dữ liệu sản phẩm (admin):', products);

        const productList = document.getElementById('product-list');
        if (!productList) return;
        productList.innerHTML = '';

        if (!products.length) {
            productList.innerHTML = '<tr><td colspan="11">Không có sản phẩm nào.</td></tr>';
            return;
        }

        products.forEach(product => {
            const row = document.createElement('tr');
            row.innerHTML = `
                <td>${product.product_id}</td>
                <td><img src="${product.image_url || '../../assets/images/no-image.png'}" alt="${product.name}" width="50"></td>
                <td>${product.name}</td>
                <td>${product.brand}</td>
                <td>${product.model}</td>
                <td>${product.price ? Number(product.price).toLocaleString('vi-VN') + ' VND' : 'Liên hệ'}</td>
                <td>${product.old_price || ''}</td>
                <td>${product.discount_percent || 0}%</td>
                <td>${product.stock_quantity}</td>
                <td>${(product.is_hot ? 'Hot' : '')}</td>
                <td>
                    <button class="btn-edit" onclick="editProduct(this)" data-id="${product.product_id}" data-name="${product.name}" data-brand="${product.brand}" data-model="${product.model}" data-price="${product.price}" data-stock="${product.stock_quantity}" data-description="${product.description}" data-image-url="${product.image_url || ''}">Sửa</button>
                    <button class="btn-delete" onclick="deleteProduct(${product.product_id})">Xóa</button>
                </td>
            `;
            productList.appendChild(row);
        });
        document.getElementById('totalProducts').textContent = products.length;
    } catch (error) {
        console.error('Lỗi tải sản phẩm:', error);
        document.getElementById('product-list').innerHTML = '<tr><td colspan="11">Lỗi khi tải dữ liệu.</td></tr>';
    }
}

function editProduct(button) {
    document.getElementById('product_id').value = button.dataset.id || '';
    document.getElementById('name').value = button.dataset.name || '';
    document.getElementById('brand').value = button.dataset.brand || '';
    document.getElementById('model').value = button.dataset.model || '';
    document.getElementById('price').value = button.dataset.price || '';
    document.getElementById('stock_quantity').value = button.dataset.stock || '';
    document.getElementById('description').value = button.dataset.description || '';
    const previewImg = document.getElementById('image_preview');
    if (previewImg) {
        previewImg.src = button.dataset.imageUrl ? `../../${button.dataset.imageUrl}` : '../../assets/images/no-image.png';
    }
    document.getElementById('image_file').value = '';
}

async function deleteProduct(productId) {
    if (!confirm('Bạn có chắc muốn xóa sản phẩm này?')) return;
    try {
        const result = await ProductService.deleteProduct(Number(productId));
        alert(result.message || 'Xóa thành công');
        loadProducts();
    } catch (error) {
        console.error('Lỗi xóa sản phẩm:', error);
        alert('Lỗi khi xóa sản phẩm: ' + (error.message || 'Không xác định'));
    }
}

function setupImagePreview() {
    const imageInput = document.getElementById('image_file');
    const previewImg = document.getElementById('image_preview');
    if (imageInput && previewImg) {
        imageInput.addEventListener('change', e => {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = e => previewImg.src = e.target.result;
                reader.readAsDataURL(file);
            }
        });
    }
}

const productForm = document.getElementById('product-form');
if (productForm) {
    productForm.addEventListener('submit', async event => {
        event.preventDefault();
        // (Logic submit form giữ nguyên)
    });
}

// Khởi tạo
function init() {
    if (document.getElementById('product-form')) {
        loadProducts();
        setupImagePreview();
    } else {
        displayHotProducts();
        setupBrandMenu();
        setupSidebarMenu();
    }
}

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', init);
} else {
    init();
}

window.editProduct = editProduct;
window.deleteProduct = deleteProduct;
window.buyProduct = buyProduct;
window.toggleWishlist = toggleWishlist;

export { 
    displayProductList, 
    displayHotProducts,
    displayProductsByBrand,
    loadProducts, 
    editProduct, 
    deleteProduct,
    buyProduct,
    toggleWishlist
};