class CartController {
  constructor() {
    this.cartService = new CartService();
  }

  async getCart(req, res) {
    try {
      const response = await fetch('/api/cart', { method: 'GET' });
      const data = await response.json();
      if (data.error) {
        alert(data.error);
        window.location.href = 'views/auth/login.html';
        return;
      }
      res.json(data);
    } catch (error) {
      res.status(500).json({ error: 'Lỗi khi lấy giỏ hàng' });
    }
  }

  async addToCart(req, res) {
    try {
      const { productId, quantity } = req.body;
      const response = await fetch('/api/cart', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ productId, quantity })
      });
      const data = await response.json();
      if (data.error) {
        alert(data.error);
        window.location.href = 'views/auth/login.html';
        return;
      }
      res.json(data);
    } catch (error) {
      res.status(500).json({ error: 'Lỗi khi thêm vào giỏ hàng' });
    }
  }

  async removeFromCart(req, res) {
    try {
      const { cartId } = req.body;
      const response = await fetch('/api/cart', {
        method: 'DELETE',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ cartId })
      });
      const data = await response.json();
      if (data.error) {
        alert(data.error);
        window.location.href = 'views/auth/login.html';
        return;
      }
      res.json(data);
    } catch (error) {
      res.status(500).json({ error: 'Lỗi khi xóa khỏi giỏ hàng' });
    }
  }

  async updateQuantity(req, res) {
    try {
      const { cartId, quantity } = req.body;
      const response = await fetch('/api/cart', {
        method: 'PUT',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ cartId, quantity })
      });
      const data = await response.json();
      if (data.error) {
        alert(data.error);
        window.location.href = 'views/auth/login.html';
        return;
      }
      res.json(data);
    } catch (error) {
      res.status(500).json({ error: 'Lỗi khi cập nhật số lượng' });
    }
  }
}

module.exports = new CartController();