const API_BASE = 'http://localhost/WebsiteMobileStore2/api/products/';

export class ProductService {

    // Lấy tất cả sản phẩm
    static async getAllProducts() {
    try {
        const response = await fetch(API_BASE);

        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }

        const data = await response.json();

        // Nếu API trả về dữ liệu trống, sử dụng dữ liệu mẫu
        if (!data || data.length === 0) {
            return this.getSampleProducts();
        }

        return data;

    } catch (error) {
        console.warn('API không khả dụng, sử dụng dữ liệu mẫu:', error);
        return this.getSampleProducts();
    }
}

    // Lấy tất cả sản phẩm theo thương hiệu
    static async getProductsByBrand(brand) {
        try {
            const response = await fetch(`${API_BASE}?brand=${encodeURIComponent(brand)}`);
            const data = await response.json();
            
            if (!data || data.length === 0) {
                return this.getSampleProducts().filter(p => 
                    p.brand.toLowerCase() === brand.toLowerCase()
                );
            }
            
            return data;
        } catch (error) {
            console.warn('API không khả dụng, sử dụng dữ liệu mẫu:', error);
            return this.getSampleProducts().filter(p => 
                p.brand.toLowerCase() === brand.toLowerCase()
            );
        }
    }

    // Lấy 1 sản phẩm theo ID
    static async getProductById(productId) {
        try {
            const response = await fetch(`${API_BASE}${productId}`);
            const data = await response.json();
            return data;
        } catch (error) {
            console.warn('API không khả dụng:', error);
            const sampleProducts = this.getSampleProducts();
            return sampleProducts.find(p => p.product_id == productId) || null;
        }
    }

    // Thêm sản phẩm mới
    static async createProduct(product) {
        const response = await fetch(API_BASE, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(product)
        });
        const result = await response.json();
        if (!response.ok) throw new Error(result.message || 'Lỗi khi thêm sản phẩm');
        return result;
    }

    // Cập nhật sản phẩm
    static async updateProduct(product) {
        const response = await fetch(`${API_BASE}${product.product_id}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-HTTP-Method-Override': 'PUT'
            },
            body: JSON.stringify(product)
        });

        const contentType = response.headers.get("content-type");
        let result = null;

        if (contentType && contentType.includes("application/json")) {
            result = await response.json();
        }

        if (!response.ok) {
            const errorMessage = result?.message || `Lỗi HTTP ${response.status}`;
            throw new Error(errorMessage);
        }

        return result;
    }

    // Xóa sản phẩm
    static async deleteProduct(productId) {
        const response = await fetch(`${API_BASE}${productId}`, {
            method: 'POST',
            headers: {
                'X-HTTP-Method-Override': 'DELETE'
            }
        });

        const contentType = response.headers.get("content-type");
        let result = null;

        if (contentType && contentType.includes("application/json")) {
            result = await response.json();
        }

        if (!response.ok) {
            const errorMessage = result?.message || `Lỗi HTTP ${response.status}`;
            throw new Error(errorMessage);
        }

        return result;
    }

    // Upload ảnh
    static async uploadImage(file) {
        const formData = new FormData();
        formData.append('image_file', file);

        const response = await fetch(`${API_BASE}uploadImage.php`, {
            method: 'POST',
            body: formData
        });

        const result = await response.json();
        if (!result.success) throw new Error(result.message || 'Lỗi khi upload ảnh');
        return result.image_url;
    }

    // Dữ liệu mẫu phù hợp với index2.html
    static getSampleProducts() {
        return [
            {
                product_id: 1,
                name: "Samsung Galaxy S24 Ultra 256GB",
                brand: "Samsung",
                model: "S24 Ultra",
                price: 32990000,
                old_price: 42000000,
                discount_percent: 21,
                stock_quantity: 50,
                description: "Flagship Android với camera 200MP và S Pen",
                image_url: "../assets/images/product/samsung.jpg",
                badge: "Hot",
                rating: "★★★★★",
                review_count: 128,
                features: ["256GB", "5G", "Camera 200MP"]
            },
            {
                product_id: 2,
                name: "iPhone 15 Pro Max 256GB",
                brand: "Apple",
                model: "15 Pro Max",
                price: 29990000,
                old_price: 34990000,
                discount_percent: 14,
                stock_quantity: 30,
                description: "iPhone mới nhất với chip A17 Pro và khung Titanium",
                image_url: "../assets/images/product/iphone.png",
                badge: "New",
                rating: "★★★★★",
                review_count: 89,
                features: ["256GB", "Titanium", "A17 Pro"]
            },
            {
                product_id: 3,
                name: "Xiaomi 14 Ultra 512GB",
                brand: "Xiaomi",
                model: "14 Ultra",
                price: 24990000,
                old_price: 29990000,
                discount_percent: 17,
                stock_quantity: 25,
                description: "Camera Leica và hiệu năng Snapdragon 8 Gen 3",
                image_url: "https://via.placeholder.com/300x250/f8f9fa/333?text=Xiaomi+14",
                badge: "Sale",
                rating: "★★★★☆",
                review_count: 45,
                features: ["512GB", "Leica", "Snapdragon 8 Gen 3"]
            },
            {
                product_id: 4,
                name: "OPPO Find X7 Pro 256GB",
                brand: "OPPO",
                model: "Find X7 Pro",
                price: 18990000,
                old_price: 22990000,
                discount_percent: 17,
                stock_quantity: 40,
                description: "Camera cộng tác Hasselblad và màn hình 120Hz",
                image_url: "https://via.placeholder.com/300x250/f8f9fa/333?text=OPPO+Find+X7",
                badge: "",
                rating: "★★★★☆",
                review_count: 67,
                features: ["256GB", "Hasselblad", "120Hz"]
            },
            {
                product_id: 5,
                name: "iPad Pro M4 11inch 256GB",
                brand: "Apple",
                model: "iPad Pro M4",
                price: 26990000,
                old_price: 29990000,
                discount_percent: 10,
                stock_quantity: 20,
                description: "iPad Pro với chip M4 và màn hình OLED",
                image_url: "https://via.placeholder.com/300x250/f8f9fa/333?text=iPad+Pro+M4",
                badge: "New",
                rating: "★★★★★",
                review_count: 156,
                features: ["M4 Chip", "OLED", "Apple Pencil"]
            },
            {
                product_id: 6,
                name: "Vivo X100 Pro 512GB",
                brand: "Vivo",
                model: "X100 Pro",
                price: 21990000,
                old_price: 25990000,
                discount_percent: 15,
                stock_quantity: 35,
                description: "Camera Zeiss và chip MediaTek Dimensity 9300",
                image_url: "https://via.placeholder.com/300x250/f8f9fa/333?text=Vivo+X100+Pro",
                badge: "Hot",
                rating: "★★★★☆",
                review_count: 34,
                features: ["512GB", "Zeiss", "MediaTek 9300"]
            },
            // Phụ kiện
            {
                product_id: 7,
                name: "Apple AirPods Pro 2nd Gen USB-C",
                brand: "Apple",
                model: "AirPods Pro 2",
                price: 5990000,
                old_price: 6990000,
                discount_percent: 14,
                stock_quantity: 100,
                description: "Tai nghe không dây với ANC và chip H2",
                image_url: "https://via.placeholder.com/300x250/f8f9fa/333?text=AirPods+Pro+2",
                badge: "Best Seller",
                rating: "★★★★★",
                review_count: 234,
                features: ["ANC", "USB-C", "H2 Chip"],
                category: "accessory"
            },
            {
                product_id: 8,
                name: "Sạc MacBook Pro 140W USB-C",
                brand: "Apple",
                model: "140W Charger",
                price: 2290000,
                old_price: 2590000,
                discount_percent: 12,
                stock_quantity: 80,
                description: "Sạc nhanh 140W cho MacBook Pro",
                image_url: "https://via.placeholder.com/300x250/f8f9fa/333?text=MacBook+Charger",
                badge: "",
                rating: "★★★★☆",
                review_count: 89,
                features: ["140W", "USB-C", "Fast Charge"],
                category: "accessory"
            },
            {
                product_id: 9,
                name: "Ốp lưng iPhone 15 Pro Max MagSafe",
                brand: "Apple",
                model: "iPhone Case",
                price: 890000,
                old_price: 1190000,
                discount_percent: 25,
                stock_quantity: 150,
                description: "Ốp lưng da với hỗ trợ MagSafe",
                image_url: "https://via.placeholder.com/300x250/f8f9fa/333?text=Ốp+lưng+iPhone",
                badge: "",
                rating: "★★★★☆",
                review_count: 156,
                features: ["MagSafe", "Leather", "Drop Protection"],
                category: "accessory"
            },
            {
                product_id: 10,
                name: "Cáp sạc nhanh USB-C to Lightning 2m",
                brand: "Generic",
                model: "USB-C Cable",
                price: 490000,
                old_price: 690000,
                discount_percent: 29,
                stock_quantity: 200,
                description: "Cáp sạc nhanh PD 30W dài 2m",
                image_url: "https://via.placeholder.com/300x250/f8f9fa/333?text=Cáp+sạc+nhanh",
                badge: "New",
                rating: "★★★★☆",
                review_count: 78,
                features: ["2m", "PD 30W", "Braided"],
                category: "accessory"
            }
        ];
    }
}