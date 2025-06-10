import CartService from '../services/CartService.js';

const CartController = {
  addToCart(productId, quantity) {
    CartService.add(productId, quantity)
      .then(response => {
        console.log('Đã thêm vào giỏ hàng:', response);
      })
      .catch(error => {
        console.error('Lỗi khi thêm giỏ hàng:', error);
      });
  }
};

export default CartController;
