const API_URL = '/api/products/cart.php';

const CartService = {
  add(productId, quantity) {
    return fetch(API_URL, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json'
      },
      body: JSON.stringify({ productId, quantity, action: 'add' })
    }).then(res => res.json());
  }
};

export default CartService;
