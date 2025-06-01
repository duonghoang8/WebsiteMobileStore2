import { ProductService } from '../services/ProductService.js';

// Debug log có điều kiện
function logDebug(...args) {
    if (window.DEBUG_MODE) console.log(...args);
}

// Render thẻ sản phẩm cho trang user (phù hợp với index2.html)
function renderProductCard(p) {
    const price = p.price ? Number(p.price).toLocaleString('vi-VN') + '₫' : 'Liên hệ';
    const oldPrice = p.old_price ? Number(p.old_price).toLocaleString('vi-VN') + '₫' : '';
    const discount = p.discount_percent ? `-${p.discount_percent}%` : '';
    const badge = p.badge || '';
    const rating = p.rating || '★★★★★';
    const reviewCount = p.review_count || '0';
    const features = p.features || [];
    const img_url = p.image_url || '/assets/images/no-image.png';
    
    return `
        <div class="product">
            <div class="product-image-container">
                <img src="${img_url}" alt="${p.name}">
                ${badge ? `<div class="product-badge">${badge}</div>` : ''}
            </div>
            <div class="product-info">
                <div class="rating">
                    <div class="stars">${rating}</div>
                    <div class="rating-text">(${reviewCount} đánh giá)</div>
                </div>
                <div class="nameProduct">${p.name}</div>
                ${features.length > 0 ? `
                    <div class="product-features">
                        ${features.map(feature => `<span class="feature-tag">${feature}</span>`).join('')}
                    </div>
                ` : ''}
                <div class="price-container">
                    <div>
                        <span class="priceProduct">${price}</span>
                        ${oldPrice ? `<span class="old-price">${oldPrice}</span>` : ''}
                    </div>
                    ${discount ? `<div class="discount-percent">${discount}</div>` : ''}
                </div>
                <div class="product-actions">
                    <button class="btn-primary" onclick="buyProduct('${p.name}')">Mua ngay</button>
                    <button class="btn-secondary" onclick="toggleWishlist(this)">♡</button>
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

        // Tìm section có class tương ứng
        const sections = document.querySelectorAll(`.${targetSectionClass}`);
        if (!sections.length) {
            console.error(`Không tìm thấy section với class ${targetSectionClass}`);
            return;
        }

        // Sử dụng section đầu tiên hoặc section cụ thể
        const targetSection = sections[0];
        const productContainer = targetSection.querySelector('.formproduct');
        
        if (!productContainer) {
            console.error('Không tìm thấy container .formproduct');
            return;
        }

        if (!products.length) {
            productContainer.innerHTML = '<div class="no-products">Không có sản phẩm nào để hiển thị.</div>';
            return;
        }

        // Render products
        let html = '';
        products.forEach(p => {
            html += renderProductCard(p);
        });
        
        productContainer.innerHTML = html;

        // Thêm event listeners cho các nút
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

// Hiển thị sản phẩm hot
async function displayHotProducts() {
    await displayProductList(null, "product-section");
}

// Hiển thị sản phẩm theo thương hiệu cụ thể
async function displayProductsByBrand(brand) {
    await displayProductList(brand, "product-section");
}

// Thiết lập tương tác cho sản phẩm
function setupProductInteractions() {
    // Đã có sẵn trong HTML, chỉ cần đảm bảo functions tồn tại
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

// Xử lý menu thương hiệu
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

// Xử lý menu sidebar
function setupSidebarMenu() {
    const sidebarItems = document.querySelectorAll('.rowcontent');
    sidebarItems.forEach(item => {
        item.addEventListener('click', function() {
            const brand = this.querySelector('.contentmenu').textContent.trim();
            if (brand === 'iPhone') {
                displayProductsByBrand('Apple');
            } else if (brand === 'Phụ kiện') {
                // Hiển thị phụ kiện
                displayAccessories();
            } else {
                displayProductsByBrand(brand);
            }
        });
    });
}

// Hiển thị phụ kiện
async function displayAccessories() {
    try {
        // Có thể tạo API riêng cho accessories hoặc filter từ products
        const products = await ProductService.getAllProducts();
        const accessories = products.filter(p => 
            p.category && p.category.toLowerCase().includes('accessory') ||
            p.name.toLowerCase().includes('airpods') ||
            p.name.toLowerCase().includes('sạc') ||
            p.name.toLowerCase().includes('cáp') ||
            p.name.toLowerCase().includes('ốp lưng')
        );
        
        const sections = document.querySelectorAll('.product-section');
        if (sections.length > 1) {
            // Sử dụng section thứ 2 cho accessories
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

// Hiển thị danh sách sản phẩm trang admin (giữ nguyên từ code cũ)
async function loadProducts() {
    try {
        const products = await ProductService.getAllProducts();
        logDebug('Dữ liệu sản phẩm (admin):', products);

        const productList = document.getElementById('product-list');
        if (!productList) return console.error('Không tìm thấy phần tử product-list');
        productList.innerHTML = '';

        if (!products.length) {
            productList.innerHTML = '<tr><td colspan="6">Không có sản phẩm nào.</td></tr>';
            return;
        }

        products.forEach(product => {
            const row = document.createElement('tr');
            row.innerHTML = `
                <td>${product.product_id}</td>
                <td><img src="../../${product.image_url || '../../assets/images/no-image.png'}" alt="${product.name}" width="50"></td>
                <td>${product.name}</td>
                <td>${product.brand}</td>
                <td>${product.model}</td>
                <td>${product.price ? Number(product.price).toLocaleString('vi-VN') + ' VND' : 'Liên hệ'}</td>
                <td>${product.old_price}</td>
                <td>${product.discount_percent}</td>
                <td>${product.stock_quantity}</td>
                <td>${product.gallery_images}</td>
                <td>
                    <button
                        onclick="editProduct(this)"
                        data-id="${product.product_id}"
                        data-name="${product.name}"
                        data-brand="${product.brand}"
                        data-model="${product.model}"
                        data-price="${product.price}"
                        data-stock="${product.stock_quantity}"
                        data-description="${product.description}"
                        data-image-url="${product.image_url || ''}"
                    >Sửa</button>
                    <button onclick="deleteProduct(${product.product_id})">Xóa</button>
                </td>
            `;
            productList.appendChild(row);
        });

    } catch (error) {
        console.error('Lỗi tải sản phẩm:', error);
        document.getElementById('product-list').innerHTML = '<tr><td colspan="6">Lỗi khi tải dữ liệu.</td></tr>';
    }
}

// Hàm edit product (điền form) - giữ nguyên
function editProduct(button) {
    logDebug('Click sửa:', button.dataset);

    document.getElementById('product_id').value = button.dataset.id || '';
    document.getElementById('name').value = button.dataset.name || '';
    document.getElementById('brand').value = button.dataset.brand || '';
    document.getElementById('model').value = button.dataset.model || '';
    document.getElementById('price').value = button.dataset.price || '';
    document.getElementById('stock_quantity').value = button.dataset.stock || '';
    document.getElementById('description').value = button.dataset.description || '';

    const previewImg = document.getElementById('image_preview');
    if (previewImg) {
        if (button.dataset.imageUrl) {
            previewImg.src = button.dataset.imageUrl;
        } else {
            previewImg.src = '../../assets/images/no-image.png';
        }
    }

    const imageInput = document.getElementById('image_file');
    if (imageInput) imageInput.value = '';
}

// Xóa sản phẩm - giữ nguyên
async function deleteProduct(productId) {
    logDebug('Xóa productId:', productId);

    if (!confirm('Bạn có chắc muốn xóa sản phẩm này?')) return;

    try {
        if (!productId || isNaN(productId)) throw new Error('product_id không hợp lệ');
        const result = await ProductService.deleteProduct(Number(productId));
        alert(result.message || 'Xóa thành công');
        loadProducts();
    } catch (error) {
        console.error('Lỗi xóa sản phẩm:', error);
        alert('Lỗi khi xóa sản phẩm: ' + (error.message || 'Không xác định'));
    }
}

// Thiết lập preview ảnh khi chọn file - giữ nguyên
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
            } else {
                previewImg.src = '../../assets/images/no-image.png';
            }
        });
    }
}

// Xử lý submit form - giữ nguyên phần admin
const productForm = document.getElementById('product-form');
if (productForm) {
    productForm.addEventListener('submit', async event => {
        event.preventDefault();

        const name = document.getElementById('name').value.trim();
        if (!name) {
            alert('Tên sản phẩm không được để trống.');
            return;
        }

        const priceStr = document.getElementById('price').value.trim();
        let price = priceStr ? Number(priceStr) : null;
        if (price !== null && (isNaN(price) || price <= 0)) {
            alert('Giá phải là số lớn hơn 0 hoặc để trống nếu liên hệ.');
            return;
        }

        const stockStr = document.getElementById('stock_quantity').value.trim();
        let stock_quantity = stockStr ? Number(stockStr) : 0;
        if (isNaN(stock_quantity) || stock_quantity < 0) {
            alert('Số lượng phải là số không âm.');
            return;
        }

        const imageInput = document.getElementById('image_file');
        let imageUrl = '';

        try {
            if (imageInput.files.length > 0) {
                imageUrl = await ProductService.uploadImage(imageInput.files[0]);
            } else {
                imageUrl = document.getElementById('image_preview')?.src || '';
                if (imageUrl.endsWith('no-image.png')) imageUrl = '';
            }
        } catch (error) {
            console.error('Lỗi upload ảnh:', error);
            alert('Upload ảnh thất bại: ' + error.message);
            return;
        }

        const product = {
            product_id: document.getElementById('product_id').value || null,
            name,
            brand: document.getElementById('brand').value.trim(),
            model: document.getElementById('model').value.trim(),
            price,
            description: document.getElementById('description').value.trim(),
            stock_quantity,
            image_url: imageUrl
        };

        try {
            let result;
            if (product.product_id) {
                result = await ProductService.updateProduct(product);
            } else {
                result = await ProductService.createProduct(product);
            }

            alert(result.message || 'Lưu sản phẩm thành công');

            document.getElementById('product-form').reset();
            document.getElementById('product_id').value = '';

            const previewImg = document.getElementById('image_preview');
            if (previewImg) previewImg.src = '../../assets/images/no-image.png';

            loadProducts();

        } catch (error) {
            console.error('Lỗi lưu sản phẩm:', error);
            alert('Lỗi khi lưu sản phẩm: ' + (error.message || 'Không xác định'));
        }
    });
}

// Khởi tạo
function init() {
    if (document.getElementById('product-form')) {
        // Trang admin
        loadProducts();
        setupImagePreview();
    } else {
        // Trang user (index2.html)
        displayHotProducts();
        setupBrandMenu();
        setupSidebarMenu();
        
        // Đảm bảo script slideshow chạy
        if (typeof showSlides === 'function') {
            showSlides();
        }
    }
}

// Chờ DOM load xong
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', init);
} else {
    init();
}

// Xuất hàm ra global để gọi từ HTML
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