
class ProductDetailController {
    constructor() {
        this.rootElement = document.getElementById('product-detail-root');
        this.productId = new URLSearchParams(window.location.search).get('id');
        this.API_BASE = 'api/products/';
    }

    async fetchProduct() {
        if (!this.productId || isNaN(this.productId)) {
            this.renderError("ID sản phẩm không hợp lệ.");
            return;
        }

        try {
            const response = await fetch(`${this.API_BASE}${this.productId}`);
            if (!response.ok) {
                const errorData = await response.json();
                throw new Error(errorData.message || `Lỗi ${response.status}`);
            }
            const product = await response.json();
            this.renderProduct(product);
        } catch (error) {
            console.error("Lỗi khi tải chi tiết sản phẩm:", error);
            this.renderError(error.message);
        }
    }

    renderProduct(p) {
        if (!p) {
            this.renderError("Không nhận được dữ liệu sản phẩm.");
            return;
        }
        document.title = p.name; // Cập nhật tiêu đề trang
        const price = p.price ? Number(p.price).toLocaleString('vi-VN') + '₫' : 'Liên hệ';
        const oldPrice = p.old_price ? Number(p.old_price).toLocaleString('vi-VN') + '₫' : '';
        const discount = p.discount_percent ? `<span class="discount-badge">-${p.discount_percent}%</span>` : '';
        const fallbackImage = 'assets/images/no-image.png';
        const imageSrc = p.image_url && p.image_url !== '' ? p.image_url : fallbackImage;
        const description = (p.description || 'Chưa có mô tả cho sản phẩm này.').replace(/\n/g, '<br>');
        const specifications = (p.specifications || 'Chưa có thông số cho sản phẩm này.').replace(/\n/g, '<br>');

        const html = `
            <div class="product-detail-container">
                <div class="product-detail-card">
                    <div class="product-image-section">
                        <img src="${imageSrc}" alt="${p.name}" onerror="this.onerror=null; this.src='${fallbackImage}';">
                    </div>
                    <div class="product-info-section">
                        <h1 class="product-name">${p.name}</h1>
                        <div class="product-brand">Thương hiệu: <strong>${p.brand || 'N/A'}</strong> | Model: <strong>${p.model || 'N/A'}</strong></div>
                        <div class="price-section">
                            <span class="current-price">${price}</span>
                            ${oldPrice ? `<span class="old-price">${oldPrice}</span>` : ''}
                            ${discount}
                        </div>
                        <div class="stock-status">
                            Tình trạng: <strong>${(p.stock_quantity > 0) ? 'Còn hàng' : 'Hết hàng'}</strong>
                        </div>
                        <div class="product-description">
                            <h2>Mô tả sản phẩm</h2>
                            <p>${description}</p>
                        </div>
                        <div class="specifications">
                            <h2>Thông số kỹ thuật</h2>
                            <p>${specifications}</p>
                        </div>
                        <div class="action-buttons">
                            <button class="buy-now-btn">Mua ngay</button>
                            <button class="add-to-cart-btn">Thêm vào giỏ hàng</button>
                        </div>
                    </div>
                </div>
            </div>
        `;
        this.rootElement.innerHTML = html;
    }

    renderError(message) {
        this.rootElement.innerHTML = `<div class="product-detail-container"><p style="text-align: center; padding: 50px; color: red; font-family: sans-serif;">${message}</p></div>`;
    }
}

document.addEventListener('DOMContentLoaded', () => {
    const controller = new ProductDetailController();
    controller.fetchProduct();
});