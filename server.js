import express from 'express';
import session from 'express-session';
import path from 'path';
import { fileURLToPath } from 'url';
import fs from 'fs';
import { products, categories, coupons, reviews, orders, contactMessages, settings } from './src/db.js';

const __filename = fileURLToPath(import.meta.url);
const __dirname = path.dirname(__filename);

const app = express();
const PORT = 3000;

// View engine setup
app.set('views', path.join(__dirname, 'views'));
app.set('view engine', 'ejs');

// Body parsers
app.use(express.urlencoded({ extended: true }));
app.use(express.json());

// Session setup
app.use(session({
  secret: 'deenz-organics-kashmiri-gold-secret',
  resave: false,
  saveUninitialized: true,
  cookie: { maxAge: 24 * 60 * 60 * 1000 }
}));

// Static files
app.use('/includes', express.static(path.join(__dirname, 'includes')));
app.use('/assets/images', express.static(path.join(__dirname, 'assets', 'images')));
app.use('/assets', express.static(path.join(__dirname, 'assets')));
app.use(express.static(path.join(__dirname, 'assets', 'images')));
app.use(express.static(path.join(__dirname, 'assets')));
app.use(express.static(path.join(__dirname, 'public')));

// Universal image handler for root/nested image requests and trailing slash normalization
app.use((req, res, next) => {
  const cleanPath = req.path.replace(/\/+$/, '');
  if (cleanPath.match(/\.(webp|jpg|jpeg|png|gif|svg|ico)$/i)) {
    const filename = path.basename(cleanPath);
    const imgPath = path.join(__dirname, 'assets', 'images', filename);
    if (fs.existsSync(imgPath)) {
      return res.sendFile(imgPath);
    }
    // Check if filename contains walnut or garlic
    if (filename.toLowerCase().includes('walnut')) {
      const walnutPath = path.join(__dirname, 'assets', 'images', 'kashmiri_walnuts_main.webp');
      if (fs.existsSync(walnutPath)) return res.sendFile(walnutPath);
    }
    const garlicPath = path.join(__dirname, 'assets', 'images', 'kashmiri_garlic_main.webp');
    if (fs.existsSync(garlicPath)) return res.sendFile(garlicPath);
    const fallbackPath = path.join(__dirname, 'assets', 'images', 'kashmiri_garlic_main.webp');
    if (fs.existsSync(fallbackPath)) return res.sendFile(fallbackPath);
  }
  next();
});

// Middleware for local session state
app.use((req, res, next) => {
  if (!req.session.cart) {
    req.session.cart = [];
  }
  let cartCount = 0;
  let cartSubtotal = 0;
  for (const item of req.session.cart) {
    cartCount += item.quantity || 0;
    cartSubtotal += (item.price || 0) * (item.quantity || 0);
  }
  res.locals.cartCount = cartCount;
  res.locals.cartSubtotal = cartSubtotal;
  res.locals.cart = req.session.cart;
  res.locals.coupon = req.session.coupon || null;
  res.locals.categories = categories;
  res.locals.products = products;
  res.locals.settings = settings;
  next();
});

// Helper functions
const sanitize = (str) => typeof str === 'string' ? str.trim() : str;

// ==================== ROUTES ====================

// 1. Home Page
app.get(['/', '/index.php'], (req, res) => {
  res.render('index', {
    page_title: 'Home | Deenz Organics',
    page_description: 'Discover 100% natural, fresh and crunchy Kashmiri Walnuts and aromatic mountain Garlic Cloves from Deenz Organics.',
    products,
    categories
  });
});

// 2. Shop Page
app.get(['/shop', '/shop.php'], (req, res) => {
  const categoryFilter = req.query.category || '';
  const searchQuery = (req.query.search || '').toLowerCase().trim();

  let filteredProducts = [...products];

  if (categoryFilter) {
    filteredProducts = filteredProducts.filter(p => p.category_slug === categoryFilter);
  }

  if (searchQuery) {
    filteredProducts = filteredProducts.filter(p =>
      p.name.toLowerCase().includes(searchQuery) ||
      p.description.toLowerCase().includes(searchQuery) ||
      p.short_description.toLowerCase().includes(searchQuery)
    );
  }

  res.render('shop', {
    page_title: 'Our Harvests | Shop Organic',
    page_description: 'Browse pure Kashmiri walnuts, mountain garlic, and Himalayan organic produce.',
    products: filteredProducts,
    categories,
    currentCategory: categoryFilter,
    searchQuery
  });
});

// 3. Product Detail Page & Redirects
app.get('/product.php', (req, res) => {
  const slug = req.query.slug;
  if (slug) {
    return res.redirect(301, `/product/${encodeURIComponent(slug)}`);
  }
  return res.redirect(301, '/shop');
});

const renderProductDetail = (req, res) => {
  let paramVal = req.params.slug || req.query.slug || req.query.id || req.query.product_id || 'premium-kashmiri-walnut-kernels';
  if (typeof paramVal === 'string') {
    paramVal = paramVal.replace(/\/$/, '');
  }

  let product = products.find(p => p.slug === paramVal || p.id === parseInt(paramVal));
  if (!product) {
    if (String(paramVal).includes('garlic')) {
      product = products[1] || products[0];
    } else {
      product = products[0];
    }
  }

  const productReviews = reviews[product.id] || [];

  res.render('product', {
    page_title: product.name,
    page_description: product.short_description,
    p: product,
    reviews_list: productReviews,
    review_message: req.session.review_message || null
  });
  delete req.session.review_message;
};

app.get(['/product/:slug', '/p/:slug', '/premium-kashmiri-walnut-kernels', '/premium-kashmiri-garlic-cloves'], renderProductDetail);

// Submit Review
app.post(['/product.php', '/product/:slug', '/review'], (req, res) => {
  const productId = parseInt(req.body.product_id || 1);
  const author = sanitize(req.body.rev_author || 'Anonymous Customer');
  const email = sanitize(req.body.rev_email || '');
  const rating = parseInt(req.body.rev_rating || 5);
  const comment = sanitize(req.body.rev_comment || '');

  if (author && comment) {
    if (!reviews[productId]) {
      reviews[productId] = [];
    }
    const newRev = {
      author,
      rating,
      created_at: new Date().toISOString().replace('T', ' ').substring(0, 16),
      comment
    };
    reviews[productId].unshift(newRev);
    req.session.review_message = "Thank you! Your review has been successfully submitted and published.";
  } else {
    req.session.review_message = "Please fill in all required fields.";
  }

  const product = products.find(p => p.id === productId) || products[0];
  res.redirect(`/product/${product.slug}#reviews`);
});

// 4. Cart Page & Actions
app.get(['/cart', '/cart.php'], (req, res) => {
  if (req.query.action === 'remove' && req.query.id) {
    const removeId = parseInt(req.query.id);
    req.session.cart = (req.session.cart || []).filter(item => item.id !== removeId && item.product_id !== removeId);
    return res.redirect('/cart.php');
  }

  if (req.query.clear_coupon) {
    req.session.coupon = null;
    req.session.coupon_msg = null;
    return res.redirect('/cart.php');
  }

  const rawCart = req.session.cart || [];
  const cartItems = rawCart.map(item => ({
    id: item.id || item.product_id,
    product_id: item.product_id || item.id,
    name: item.name,
    slug: item.slug || 'kashmiri-mountain-garlic',
    sku: item.sku || 'KMG-500',
    weight: item.weight || item.weight_variant || '500 Grams Pack',
    price: item.price || 850,
    quantity: item.quantity || 1,
    main_image: item.main_image || item.image || '/assets/images/kashmiri_garlic_main.webp'
  }));

  let subtotal = 0;
  cartItems.forEach(item => {
    subtotal += item.price * item.quantity;
  });

  let discountVal = 0;
  if (req.session.coupon) {
    if (req.session.coupon.type === 'percent') {
      discountVal = subtotal * (req.session.coupon.value / 100);
    } else {
      discountVal = req.session.coupon.value;
    }
  }

  const shippingCharge = subtotal > 1000 || subtotal === 0 ? 0 : 90;
  const taxVal = Math.max(0, subtotal - discountVal) * 0.05;
  const grandTotal = Math.max(0, subtotal - discountVal + shippingCharge + taxVal);

  const couponMsg = req.session.coupon_msg;
  req.session.coupon_msg = null;

  res.render('cart', {
    page_title: 'Shopping Cart | Deenz Organics',
    page_description: 'Review your selected Kashmiri organic harvests and proceed to secure checkout.',
    cartItems,
    subtotal,
    discountVal,
    shippingCharge,
    taxVal,
    grandTotal,
    couponCodeApplied: req.session.coupon ? req.session.coupon.code : null,
    message: couponMsg || null,
    messageType: couponMsg && couponMsg.includes('Invalid') ? 'error' : (couponMsg ? 'success' : 'info')
  });
});

app.post('/cart.php', (req, res) => {
  const action = req.body.action || '';

  if (action === 'add' || req.body.add_to_cart) {
    const productId = parseInt(req.body.product_id || 1);
    const quantity = parseInt(req.body.quantity || 1);
    const weightVariant = req.body.weight_variant || '500 Grams';

    const product = products.find(p => p.id === productId);
    if (product) {
      const existingIndex = req.session.cart.findIndex(
        item => item.product_id === productId && item.weight_variant === weightVariant
      );

      if (existingIndex > -1) {
        req.session.cart[existingIndex].quantity += quantity;
      } else {
        req.session.cart.push({
          product_id: product.id,
          name: product.name,
          slug: product.slug,
          sku: product.sku,
          price: product.sale_price || product.price,
          quantity: quantity,
          weight_variant: weightVariant,
          image: product.main_image
        });
      }
    }
    return res.redirect('/cart.php');
  }

  if (action === 'update' || req.body.update_cart) {
    const itemIndex = parseInt(req.body.item_index);
    const newQty = parseInt(req.body.quantity);

    if (!isNaN(itemIndex) && req.session.cart[itemIndex]) {
      if (newQty <= 0) {
        req.session.cart.splice(itemIndex, 1);
      } else {
        req.session.cart[itemIndex].quantity = newQty;
      }
    }
    return res.redirect('/cart.php');
  }

  if (action === 'remove' || req.body.remove_item) {
    const itemIndex = parseInt(req.body.item_index);
    if (!isNaN(itemIndex) && req.session.cart[itemIndex]) {
      req.session.cart.splice(itemIndex, 1);
    }
    return res.redirect('/cart.php');
  }

  if (action === 'apply_coupon' || req.body.apply_coupon) {
    const couponCode = (req.body.coupon_code || '').toUpperCase().trim();
    const foundCoupon = coupons.find(c => c.code === couponCode);

    if (foundCoupon) {
      req.session.coupon = foundCoupon;
      req.session.coupon_msg = `Coupon '${couponCode}' applied successfully!`;
    } else {
      req.session.coupon_msg = `Invalid coupon code '${couponCode}'.`;
    }
    return res.redirect('/cart.php');
  }

  res.redirect('/cart.php');
});

// 5. Checkout Page & Actions
app.get(['/checkout', '/checkout.php'], (req, res) => {
  // Direct buy handle
  if (req.query.direct_buy || req.body.direct_buy) {
    const productId = parseInt(req.query.product_id || req.body.product_id || 1);
    const qty = parseInt(req.query.quantity || req.body.quantity || 1);
    const variant = req.query.weight_variant || req.body.weight_variant || '500 Grams';
    const unitPrice = parseFloat(req.query.unit_price || req.body.unit_price) || 0;

    const product = products.find(p => p.id === productId) || products[0];
    if (product) {
      req.session.cart = [{
        id: product.id,
        product_id: product.id,
        name: product.name,
        slug: product.slug,
        sku: product.sku,
        price: unitPrice || product.sale_price || product.price,
        quantity: qty,
        weight_variant: variant,
        main_image: product.main_image,
        image: product.main_image
      }];
    }
  }

  const checkoutItems = req.session.cart || [];
  let subtotal = 0;
  checkoutItems.forEach(item => {
    subtotal += (item.price || 0) * (item.quantity || 1);
  });

  let discountVal = 0;
  if (req.session.coupon) {
    if (req.session.coupon.type === 'percent') {
      discountVal = subtotal * (req.session.coupon.value / 100);
    } else {
      discountVal = req.session.coupon.value;
    }
  }

  const shippingCharge = subtotal > 1000 ? 0 : 90;
  const taxVal = Math.max(0, subtotal - discountVal) * 0.05;
  const grandTotal = Math.max(0, subtotal - discountVal + shippingCharge + taxVal);

  res.render('checkout', {
    page_title: 'Express Checkout | Deenz Organics',
    page_description: 'Enter your delivery details and choose your preferred payment method.',
    errors: [],
    is_direct_buy: !!req.query.direct_buy,
    checkoutItems,
    subtotal,
    discountVal,
    shippingCharge,
    taxVal,
    grandTotal
  });
});

app.post('/checkout.php', (req, res) => {
  // Handles direct buy post OR place order
  if (req.body.direct_buy) {
    const productId = parseInt(req.body.product_id || 1);
    const qty = parseInt(req.body.quantity || 1);
    const variant = req.body.weight_variant || '500 Grams';

    const product = products.find(p => p.id === productId);
    if (product) {
      req.session.cart = [{
        product_id: product.id,
        name: product.name,
        slug: product.slug,
        sku: product.sku,
        price: product.sale_price || product.price,
        quantity: qty,
        weight_variant: variant,
        image: product.main_image
      }];
    }
    return res.redirect('/checkout.php');
  }

  if (req.body.place_order || req.body.payment_method) {
    const name = sanitize(req.body.full_name || 'Valued Customer');
    const email = sanitize(req.body.email || 'customer@example.com');
    const phone = sanitize(req.body.phone || '');
    const address = sanitize(req.body.address || '');
    const city = sanitize(req.body.city || '');
    const state = sanitize(req.body.state || '');
    const pincode = sanitize(req.body.pincode || '');
    const paymentMethod = req.body.payment_method || 'COD';

    const cartItems = (req.session.cart || []).map(item => ({
      name: item.name || 'Kashmiri Organic Harvest',
      sku: item.sku || 'KMG-500',
      weight: item.weight_variant || item.weight || '500g',
      price: parseFloat(item.price) || 0,
      quantity: parseInt(item.quantity) || 1
    }));

    let subtotal = 0;
    cartItems.forEach(i => {
      subtotal += i.price * i.quantity;
    });

    let discountVal = 0;
    if (req.session.coupon) {
      if (req.session.coupon.type === 'percent') {
        discountVal = subtotal * (req.session.coupon.value / 100);
      } else {
        discountVal = req.session.coupon.value;
      }
    }

    const shippingCharge = subtotal > 1000 || subtotal === 0 ? 0 : 90;
    const taxVal = Math.max(0, subtotal - discountVal) * 0.05;
    const grandTotal = Math.max(0, subtotal - discountVal + shippingCharge + taxVal);

    const orderNumber = 'ORD-' + Math.floor(100000 + Math.random() * 900000);
    const newOrder = {
      id: orders.length + 1001,
      order_number: orderNumber,
      customer_name: name,
      customer_email: email,
      customer_phone: phone,
      shipping_address: `${address}, ${city}, ${state} - ${pincode}`,
      subtotal: subtotal,
      discount: discountVal,
      shipping: shippingCharge,
      tax: taxVal,
      total: grandTotal,
      total_amount: grandTotal,
      payment_method: paymentMethod,
      payment_status: paymentMethod === 'COD' ? 'PENDING (COD)' : 'PAID',
      order_status: 'PROCESSING',
      courier_name: 'Blue Dart Express',
      tracking_number: 'BD' + Math.floor(10000000 + Math.random() * 90000000),
      created_at: new Date().toISOString().replace('T', ' ').substring(0, 19),
      items: cartItems
    };

    orders.unshift(newOrder);

    // Clear cart and session coupon
    req.session.cart = [];
    delete req.session.coupon;

    return res.redirect(`/success.php?order_id=${orderNumber}`);
  }

  res.redirect('/checkout.php');
});

// 6. Success / Invoice Page
app.get(['/success', '/success.php'], (req, res) => {
  const orderNumber = req.query.order_id || 'ORD-1001';
  const order = orders.find(o => o.order_number === orderNumber) || orders[0];

  res.render('success', {
    page_title: 'Order Confirmed | Deenz Organics',
    page_description: 'Thank you for your order! Your Kashmiri organic harvest consignment is being packed.',
    order
  });
});

// 7. Order Tracking
app.get(['/track', '/track.php'], (req, res) => {
  const queryOrderNumber = (req.query.order_number || '').trim();
  let foundOrder = null;
  let currentStepIndex = 1;

  if (queryOrderNumber) {
    foundOrder = orders.find(o => o.order_number && o.order_number.toUpperCase() === queryOrderNumber.toUpperCase());
    if (foundOrder) {
      if (foundOrder.order_status === 'DELIVERED') {
        currentStepIndex = 4;
      } else if (foundOrder.order_status === 'DISPATCHED' || foundOrder.order_status === 'SHIPPED') {
        currentStepIndex = 3;
      } else if (foundOrder.order_status === 'PROCESSING') {
        currentStepIndex = 2;
      } else {
        currentStepIndex = 1;
      }
    }
  }

  res.render('track', {
    page_title: 'Track Order | Deenz Organics',
    page_description: 'Track your Kashmiri organic produce shipment in real-time.',
    searchedNumber: queryOrderNumber,
    orderFound: foundOrder,
    currentStepIndex,
    search_query: queryOrderNumber,
    order: foundOrder
  });
});

app.post(['/track', '/track.php'], (req, res) => {
  const orderNum = sanitize(req.body.order_number || '');
  res.redirect(`/track.php?order_number=${encodeURIComponent(orderNum)}`);
});

// 8. About Us
app.get(['/about', '/about.php'], (req, res) => {
  res.render('about', {
    page_title: 'Our Story & Valley Orchards | Deenz Organics',
    page_description: 'Learn about our roots in Wanpora, Kulgam, J&K and our dedication to pure single-origin organic produce.'
  });
});

// 9. Contact Us
app.get(['/contact', '/contact.php'], (req, res) => {
  res.render('contact', {
    page_title: 'Contact Founder & Customer Care | Deenz Organics',
    page_description: 'Reach out to Deenz Organics in Wanpora, Kulgam for customer care or wholesale inquiries.',
    success_message: req.session.contact_success || null
  });
  delete req.session.contact_success;
});

app.post(['/contact', '/contact.php'], (req, res) => {
  const name = sanitize(req.body.name || '');
  const email = sanitize(req.body.email || '');
  const phone = sanitize(req.body.phone || '');
  const subject = sanitize(req.body.subject || 'General Query');
  const message = sanitize(req.body.message || '');

  if (name && email && message) {
    contactMessages.unshift({
      id: contactMessages.length + 1,
      name,
      email,
      phone,
      subject,
      message,
      created_at: new Date().toISOString().replace('T', ' ').substring(0, 19)
    });
    req.session.contact_success = 'Thank you for contacting Deenz Organics! Our Wanpora team will get back to you shortly.';
  }

  res.redirect('/contact.php');
});

// 10. FAQ Page
app.get(['/faq', '/faq.php'], (req, res) => {
  res.render('faq', {
    page_title: 'Frequently Asked Questions | Deenz Organics',
    page_description: 'Answers to common queries regarding Kashmiri walnuts, garlic, shipping, and freshness guarantees.'
  });
});

// 11. Legal Pages
app.get(['/privacy', '/privacy.php'], (req, res) => {
  res.render('privacy', { page_title: 'Privacy Policy | Deenz Organics' });
});

app.get(['/refund', '/refund.php'], (req, res) => {
  res.render('refund', { page_title: 'Return & Refund Policy | Deenz Organics' });
});

app.get(['/shipping', '/shipping.php'], (req, res) => {
  res.render('shipping', { page_title: 'Shipping & Delivery Policy | Deenz Organics' });
});

app.get(['/terms', '/terms.php'], (req, res) => {
  res.render('terms', { page_title: 'Terms & Conditions | Deenz Organics' });
});

// 12. Admin Dashboard
app.get(['/admin', '/admin/index.php'], (req, res) => {
  const isAuthenticated = req.session.isAdminLoggedIn || false;
  const message = req.session.admin_message || null;
  delete req.session.admin_message;

  const totalRevenue = orders.reduce((sum, o) => sum + (o.total_amount || o.total || 0), 0);
  const totalOrders = orders.length;

  const editId = parseInt(req.query.edit_id);
  const editProduct = editId ? products.find(p => p.id === editId) || null : null;

  if (req.query.delete_id) {
    const deleteId = parseInt(req.query.delete_id);
    const idx = products.findIndex(p => p.id === deleteId);
    if (idx > -1) {
      products.splice(idx, 1);
      req.session.admin_message = 'Product deleted successfully.';
    }
    return res.redirect('/admin/index.php#catalog');
  }

  if (req.query.logout) {
    delete req.session.isAdminLoggedIn;
    req.session.admin_message = 'You have logged out.';
    return res.redirect('/admin/index.php');
  }

  if (req.query.approve_review_id) {
    const revId = parseInt(req.query.approve_review_id);
    Object.values(reviews).flat().forEach(r => {
      if (r.id === revId) r.status = 'approved';
    });
    req.session.admin_message = 'Review approved.';
    return res.redirect('/admin/index.php#reviews');
  }

  if (req.query.reject_review_id) {
    const revId = parseInt(req.query.reject_review_id);
    Object.values(reviews).flat().forEach(r => {
      if (r.id === revId) r.status = 'rejected';
    });
    req.session.admin_message = 'Review rejected.';
    return res.redirect('/admin/index.php#reviews');
  }

  if (req.query.delete_review_id) {
    const revId = parseInt(req.query.delete_review_id);
    for (const pid in reviews) {
      const idx = reviews[pid].findIndex(r => r.id === revId);
      if (idx > -1) reviews[pid].splice(idx, 1);
    }
    req.session.admin_message = 'Review deleted.';
    return res.redirect('/admin/index.php#reviews');
  }

  const allReviews = [];
  for (const pid in reviews) {
    const p = products.find(item => item.id == pid);
    reviews[pid].forEach((r, i) => {
      allReviews.push({
        id: r.id || (pid * 100 + i),
        author: r.author,
        comment: r.comment,
        rating: r.rating,
        status: r.status || 'approved',
        product_name: p ? p.name : 'Kashmiri Organic Product'
      });
    });
  }

  res.render('admin', {
    page_title: 'Admin Management Panel | Deenz Organics',
    isAuthenticated,
    message,
    totalRevenue,
    totalOrders,
    productsList: products,
    editProduct,
    ordersList: orders,
    reviewsList: allReviews,
    messagesList: contactMessages
  });
});

app.post(['/admin', '/admin/index.php'], (req, res) => {
  if (req.body.login) {
    const username = sanitize(req.body.username || '');
    const password = sanitize(req.body.password || '');

    if (username === 'admin' && (password === 'admin123' || password === 'adminPassword123!')) {
      req.session.isAdminLoggedIn = true;
      req.session.admin_message = 'Welcome back, Administrator!';
    } else {
      req.session.admin_message = 'Invalid administrator username or password credentials.';
    }
    return res.redirect('/admin/index.php');
  }

  if (req.body.add_product) {
    const name = sanitize(req.body.prod_name || '');
    const price = parseFloat(req.body.prod_price || 0);
    const stock = parseInt(req.body.prod_stock || 100);
    const slug = name.toLowerCase().replace(/[^a-z0-9]+/g, '-').replace(/(^-|-$)/g, '');
    const sku = 'DZ-' + name.substring(0, 3).toUpperCase() + '-' + Math.floor(100 + Math.random() * 900);

    const newProd = {
      id: products.length > 0 ? Math.max(...products.map(p => p.id)) + 1 : 1,
      name,
      slug,
      sku,
      category_id: 1,
      category_slug: 'walnuts',
      price,
      sale_price: null,
      stock,
      short_description: name + ' harvested directly from Kashmir Wanpora orchards.',
      description: '100% pure Kashmiri organic produce. High oil content, raw, fresh, and hand-sorted.',
      main_image: '/assets/images/kashmiri_garlic_main.webp',
      badge: 'Fresh Crop',
      harvest_location: 'Wanpora, Kulgam, J&K',
      rating: 5.0,
      review_count: 0
    };
    products.push(newProd);
    req.session.admin_message = `Successfully added '${name}' to catalog.`;
    return res.redirect('/admin/index.php#catalog');
  }

  if (req.body.edit_product) {
    const prodId = parseInt(req.body.prod_id);
    const name = sanitize(req.body.prod_name || '');
    const price = parseFloat(req.body.prod_price || 0);
    const stock = parseInt(req.body.prod_stock || 100);

    const prod = products.find(p => p.id === prodId);
    if (prod) {
      prod.name = name;
      prod.price = price;
      prod.stock = stock;
      req.session.admin_message = `Successfully updated '${name}'.`;
    }
    return res.redirect('/admin#catalog');
  }

  res.redirect('/admin');
});

// Catch-all route for product slug routing (e.g., /premium-kashmiri-walnut-kernels/)
app.get('/:slug', (req, res, next) => {
  const slug = req.params.slug.replace(/\/$/, '');
  const matchedProduct = products.find(p => p.slug === slug);
  if (matchedProduct) {
    return renderProductDetail(req, res);
  }
  next();
});

// 404 Handler
app.use((req, res) => {
  res.status(404).render('shop', {
    page_title: 'Page Not Found | Deenz Organics',
    page_description: 'The requested page could not be found.',
    products,
    categories,
    currentCategory: '',
    searchQuery: ''
  });
});

app.listen(PORT, '0.0.0.0', () => {
  console.log(`Deenz Organics server listening on http://0.0.0.0:${PORT}`);
});
