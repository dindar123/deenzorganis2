<?php
/**
 * Deenz Organics - Luxury Shopping Cart
 */
require_once __DIR__ . '/includes/db.php';

// Handle Action Requests (Add, Update, Remove, Coupon)
$message = '';
$message_type = 'info';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// 1. Add to Cart action
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] == 'add') {
    $product_id = intval($_POST['product_id']);
    $quantity = intval($_POST['quantity'] ?? 1);
    $slug = $_POST['slug'] ?? 'premium-kashmiri-walnut-kernels';
    
    // Validate quantity boundaries
    if ($quantity < 1) $quantity = 1;
    if ($quantity > 10) $quantity = 10;
    
    // Product static data block for adding (in case DB isn't seeded)
    $prod_details = [
        1 => ['name' => 'Kashmiri Organic Walnuts / Akhrot Giri (500gms)', 'sku' => 'DZ-WLN-001', 'price' => 750.00, 'weight' => '500g', 'emoji' => '🌰', 'main_image' => 'assets/images/kashmiri_walnuts_main.webp', 'slug' => 'premium-kashmiri-walnut-kernels'],
        2 => ['name' => 'Kashmiri Mountain Garlic / Lahsun (500gms)', 'sku' => 'DZ-GRL-002', 'price' => 850.00, 'weight' => '500g', 'emoji' => '🧄', 'main_image' => 'assets/images/kashmiri_garlic_main.webp', 'slug' => 'premium-kashmiri-garlic-cloves']
    ];
    
    // Attempt DB fetch to overwrite static placeholders
    try {
        if (isset($pdo)) {
            $stmt = $pdo->prepare("SELECT * FROM products WHERE id = :id AND status = 'published'");
            $stmt->execute(['id' => $product_id]);
            $db_prod = $stmt->fetch();
            if ($db_prod) {
                $prod_details[$product_id] = [
                    'name' => $db_prod['name'],
                    'sku' => $db_prod['sku'],
                    'price' => floatval($db_prod['sale_price'] ?? $db_prod['price']),
                    'weight' => '400g',
                    'emoji' => $db_prod['emoji'] ?? '🌰',
                    'main_image' => $db_prod['main_image'] ?? ''
                ];
            }
        }
    } catch (\Exception $e) {}

    // Fallback/enrich from session products if available
    if (isset($_SESSION['simulated_products'][$product_id])) {
        $sp = $_SESSION['simulated_products'][$product_id];
        $prod_details[$product_id]['emoji'] = $sp['emoji'] ?? '🌰';
        $prod_details[$product_id]['main_image'] = $sp['main_image'] ?? '';
    }
    
    if (isset($prod_details[$product_id])) {
        $p = $prod_details[$product_id];
        
        // Check if already in cart
        if (isset($_SESSION['cart'][$product_id])) {
            $_SESSION['cart'][$product_id]['quantity'] += $quantity;
            if ($_SESSION['cart'][$product_id]['quantity'] > 10) {
                $_SESSION['cart'][$product_id]['quantity'] = 10;
            }
        } else {
            $_SESSION['cart'][$product_id] = [
                'id' => $product_id,
                'name' => $p['name'],
                'sku' => $p['sku'],
                'price' => $p['price'],
                'weight' => $p['weight'],
                'quantity' => $quantity,
                'slug' => $slug,
                'emoji' => $p['emoji'] ?? '🌰',
                'main_image' => $p['main_image'] ?? ''
            ];
        }
        $message = "Successfully added '" . $p['name'] . "' to your shopping cart!";
        $message_type = "success";
    }
}

// 2. Update Quantity action
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] == 'update') {
    $product_id = intval($_POST['product_id']);
    $new_qty = intval($_POST['quantity'] ?? 1);
    
    if ($new_qty < 1) $new_qty = 1;
    if ($new_qty > 10) $new_qty = 10;
    
    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id]['quantity'] = $new_qty;
        $message = "Cart quantities updated successfully.";
        $message_type = "success";
    }
}

// 3. Remove Product action
if (isset($_GET['action']) && $_GET['action'] == 'remove') {
    $remove_id = intval($_GET['id']);
    if (isset($_SESSION['cart'][$remove_id])) {
        unset($_SESSION['cart'][$remove_id]);
        $message = "Item removed from cart.";
        $message_type = "success";
    }
}

// 4. Coupon Code action
$discount_val = 0.00;
$coupon_code_applied = $_SESSION['coupon_code'] ?? '';
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['apply_coupon'])) {
    $typed_code = strtoupper(trim($_POST['coupon_code'] ?? ''));
    if ($typed_code == 'KASHMIR10') {
        $_SESSION['coupon_code'] = 'KASHMIR10';
        $_SESSION['coupon_type'] = 'percentage';
        $_SESSION['coupon_val'] = 10.00;
        $coupon_code_applied = 'KASHMIR10';
        $message = "Promo code 'KASHMIR10' applied! Enjoy 10% off your subtotal.";
        $message_type = "success";
    } elseif ($typed_code == 'ORGANIC50') {
        $_SESSION['coupon_code'] = 'ORGANIC50';
        $_SESSION['coupon_type'] = 'fixed';
        $_SESSION['coupon_val'] = 50.00;
        $coupon_code_applied = 'ORGANIC50';
        $message = "Promo code 'ORGANIC50' applied! ₹50.00 flat discount credited.";
        $message_type = "success";
    } else {
        $message = "Invalid promo code. Try 'KASHMIR10' (10% off) or 'ORGANIC50' (₹50 flat).";
        $message_type = "error";
    }
}

// Clear coupon action
if (isset($_GET['clear_coupon'])) {
    unset($_SESSION['coupon_code']);
    unset($_SESSION['coupon_type']);
    unset($_SESSION['coupon_val']);
    $coupon_code_applied = '';
    $message = "Promo code cleared.";
    $message_type = "info";
}

// Compute Totals
$subtotal = 0;
foreach ($_SESSION['cart'] as $item) {
    $subtotal += ($item['price'] * $item['quantity']);
}

// Discount math
if (!empty($coupon_code_applied)) {
    $c_type = $_SESSION['coupon_type'] ?? 'percentage';
    $c_val = $_SESSION['coupon_val'] ?? 0;
    if ($c_type == 'percentage') {
        $discount_val = ($subtotal * $c_val) / 100;
    } else {
        $discount_val = $c_val;
    }
}

// Shipping charges: flat ₹50.00, Free shipping over ₹500.00
$shipping_charge = ($subtotal > 500 || $subtotal == 0) ? 0.00 : 50.00;

// Tax calculation: 5% GST
$tax_val = ($subtotal - $discount_val) * 0.05;
if ($tax_val < 0) $tax_val = 0;

$grand_total = $subtotal - $discount_val + $shipping_charge + $tax_val;
if ($grand_total < 0) $grand_total = 0;

$page_title = "Your Shopping Cart";
require_once __DIR__ . '/includes/header.php';
?>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
    <h1 class="font-display font-bold text-3xl text-luxury-950 mb-8 flex items-center gap-2">
        <i data-lucide="shopping-bag" class="w-8 h-8 text-luxury-500"></i> Your Harvest Cart Summary
    </h1>

    <!-- Alert Notices -->
    <?php if (!empty($message)): ?>
        <div class="p-4 rounded-xl mb-8 border flex items-center gap-3 text-xs font-display <?php 
            echo $message_type == 'success' ? 'bg-emerald-50 border-emerald-200 text-emerald-800' : 
                ($message_type == 'error' ? 'bg-red-50 border-red-200 text-red-800' : 'bg-luxury-100 border-luxury-300 text-luxury-800'); 
        ?>">
            <i data-lucide="<?php echo $message_type == 'error' ? 'alert-circle' : 'check-circle'; ?>" class="w-5 h-5 shrink-0"></i>
            <span><?php echo sanitize_html($message); ?></span>
        </div>
    <?php endif; ?>

    <?php if (empty($_SESSION['cart'])): ?>
        <!-- Empty Cart View -->
        <div class="bg-white border border-luxury-100 rounded-2xl p-16 text-center space-y-6">
            <span class="text-7xl select-none">🌾</span>
            <h2 class="font-display font-bold text-xl text-luxury-950">Your Cart is Currently Empty</h2>
            <p class="text-xs text-luxury-500 max-w-sm mx-auto leading-relaxed">
                You haven't added any premium organic Kashmiri harvests yet. Go browse our walnut kernels and mountain garlic selections to start!
            </p>
            <a href="shop.php" class="bg-luxury-900 hover:bg-luxury-800 text-white font-display font-semibold text-xs py-3 px-8 rounded-md tracking-wider uppercase inline-block shadow-md">
                Browse All Harvests
            </a>
        </div>
    <?php else: ?>
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-start">
            
            <!-- Items Area Left (lg:col-span-8) -->
            <div class="lg:col-span-8 space-y-6">
                <div class="bg-white border border-luxury-200/60 rounded-2xl overflow-hidden shadow-sm">
                    <div class="divide-y divide-luxury-100">
                        <?php foreach ($_SESSION['cart'] as $id => $item): ?>
                            <div class="p-6 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-6">
                                <!-- Info block -->
                                <div class="flex items-center gap-4">
                                    <div class="w-16 h-16 bg-luxury-50 border border-luxury-100 rounded-lg flex items-center justify-center text-3xl overflow-hidden p-1.5 flex-shrink-0">
                                        <?php 
                                            $cart_img = clean_image_url($item['main_image'] ?? '', $item['slug'] ?? $item['name'] ?? '');
                                        ?>
                                        <?php if (!empty($cart_img)): ?>
                                            <img src="<?php echo sanitize_html($cart_img); ?>" class="h-full w-full object-cover rounded" />
                                        <?php else: ?>
                                            <span class="text-3xl select-none"><?php echo sanitize_html($item['emoji'] ?? '🌰'); ?></span>
                                        <?php endif; ?>
                                    </div>
                                    <div>
                                        <h3 class="font-display font-bold text-sm text-luxury-950 hover:text-luxury-600 transition-colors">
                                            <a href="/product/<?php echo urlencode($item['slug'] ?? 'premium-kashmiri-walnut-kernels'); ?>"><?php echo sanitize_html($item['name']); ?></a>
                                        </h3>
                                        <p class="text-[10px] font-mono text-luxury-400 uppercase mt-0.5">SKU: <?php echo sanitize_html($item['sku']); ?> | Pack: <?php echo sanitize_html($item['weight']); ?></p>
                                        <p class="text-xs text-luxury-950 font-bold mt-1">₹<?php echo number_format($item['price'], 2); ?></p>
                                    </div>
                                </div>

                                <!-- Quantity and subtotal controls -->
                                <div class="flex items-center justify-between sm:justify-end gap-8 w-full sm:w-auto border-t sm:border-t-0 pt-4 sm:pt-0">
                                    <!-- Form to update quantity -->
                                    <form action="/cart.php" method="POST" class="flex items-center border border-luxury-200 rounded bg-white">
                                        <input type="hidden" name="action" value="update">
                                        <input type="hidden" name="product_id" value="<?php echo $id; ?>">
                                        
                                        <button type="submit" onclick="const q=this.form.quantity; if(parseInt(q.value) > 1) q.value = parseInt(q.value)-1;" class="px-2.5 py-1.5 text-luxury-400 hover:text-luxury-900 font-bold text-xs">-</button>
                                        <input type="text" name="quantity" value="<?php echo $item['quantity']; ?>" readonly class="w-8 text-center text-[11px] font-bold border-none text-luxury-950 focus:outline-none">
                                        <button type="submit" onclick="const q=this.form.quantity; if(parseInt(q.value) < 10) q.value = parseInt(q.value)+1;" class="px-2.5 py-1.5 text-luxury-400 hover:text-luxury-900 font-bold text-xs">+</button>
                                    </form>

                                    <!-- Price total -->
                                    <div class="text-right">
                                        <span class="text-xs text-luxury-400 block uppercase tracking-wider text-[9px] font-mono">Row Total</span>
                                        <span class="text-sm font-bold text-luxury-950">₹<?php echo number_format($item['price'] * $item['quantity'], 2); ?></span>
                                    </div>

                                    <!-- Remove -->
                                    <a href="/cart.php?action=remove&id=<?php echo $id; ?>" class="text-red-400 hover:text-red-700 p-2 transition-colors" title="Remove Item">
                                        <i data-lucide="trash-2" class="w-4.5 h-4.5"></i>
                                    </a>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <!-- Continue shopping trigger -->
                <div class="flex justify-between items-center text-xs">
                    <a href="/shop.php" class="font-display font-bold text-luxury-900 flex items-center gap-1.5 hover:underline">
                        <i data-lucide="arrow-left" class="w-4 h-4"></i> Continue Loading Commodities
                    </a>
                    
                    <a href="/cart.php?clear_all=1" onclick="alert('Demo notice: Cart session variables reset!');" class="text-luxury-400 hover:text-luxury-800">
                        Reset Session Variables
                    </a>
                </div>
            </div>

            <!-- Summary Area Right (lg:col-span-4) -->
            <div class="lg:col-span-4 space-y-6">
                <!-- Promo Code Box -->
                <div class="bg-white border border-luxury-200/60 rounded-2xl p-6 space-y-4 shadow-sm">
                    <h3 class="font-display font-bold text-sm text-luxury-950 uppercase tracking-wider">Apply Promo Coupon</h3>
                    <form action="/cart.php" method="POST" class="flex gap-2">
                        <input type="text" name="coupon_code" placeholder="Enter code (KASHMIR10)" class="flex-grow bg-luxury-50 border border-luxury-200 rounded-md py-2 px-3 text-xs font-mono uppercase text-luxury-950 focus:outline-none">
                        <button type="submit" name="apply_coupon" class="bg-luxury-900 hover:bg-luxury-800 text-white font-display text-xs font-semibold px-4 py-2.5 rounded-md transition-colors">
                            Apply
                        </button>
                    </form>
                    
                    <?php if (!empty($coupon_code_applied)): ?>
                        <div class="bg-luxury-50 border border-luxury-200 rounded-lg p-3 flex items-center justify-between text-[11px]">
                            <div class="flex items-center gap-2 text-luxury-700">
                                <i data-lucide="tag" class="w-4 h-4 text-luxury-500 animate-bounce"></i>
                                <span>Code <strong class="font-mono text-luxury-950"><?php echo sanitize_html($coupon_code_applied); ?></strong> active!</span>
                            </div>
                            <a href="/cart.php?clear_coupon=1" class="text-red-500 hover:text-red-700">
                                <i data-lucide="x" class="w-4 h-4"></i>
                            </a>
                        </div>
                    <?php else: ?>
                        <p class="text-[10px] text-luxury-400 leading-relaxed leading-none">
                            💡 Use <strong class="font-mono text-luxury-700 select-all">KASHMIR10</strong> for 10% off or <strong class="font-mono text-luxury-700 select-all">ORGANIC50</strong> for a flat ₹50.00 instant deduction!
                        </p>
                    <?php endif; ?>
                </div>

                <!-- Ledger Summary -->
                <div class="bg-white border border-luxury-200/60 rounded-2xl p-6 space-y-4 shadow-sm">
                    <h3 class="font-display font-bold text-sm text-luxury-950 uppercase tracking-wider pb-3 border-b border-luxury-100">Order Totals</h3>
                    
                    <div class="space-y-3 text-xs text-luxury-600">
                        <div class="flex items-center justify-between">
                            <span>Subtotal Items</span>
                            <span class="font-mono font-bold text-luxury-950">₹<?php echo number_format($subtotal, 2); ?></span>
                        </div>
                        
                        <?php if ($discount_val > 0): ?>
                            <div class="flex items-center justify-between text-red-600">
                                <span>Promo Discount</span>
                                <span class="font-mono font-bold">-₹<?php echo number_format($discount_val, 2); ?></span>
                            </div>
                        <?php endif; ?>

                        <div class="flex items-center justify-between">
                            <span>Shipping & Air Cargo</span>
                            <span class="font-mono font-bold text-luxury-950">
                                <?php echo $shipping_charge == 0 ? '<span class="text-emerald-600">FREE</span>' : '₹' . number_format($shipping_charge, 2); ?>
                            </span>
                        </div>

                        <div class="flex items-center justify-between">
                            <span>Estimated GST (5%)</span>
                            <span class="font-mono font-bold text-luxury-950">₹<?php echo number_format($tax_val, 2); ?></span>
                        </div>

                        <div class="border-t border-luxury-100 pt-4 flex items-center justify-between text-base font-display font-bold text-luxury-950">
                            <span>Grand Total Price</span>
                            <span class="font-mono text-lg text-luxury-900">₹<?php echo number_format($grand_total, 2); ?></span>
                        </div>
                    </div>

                    <!-- Checkout navigation button -->
                    <a href="/checkout.php" class="bg-luxury-950 hover:bg-luxury-900 text-white font-display font-semibold text-xs py-4 px-6 rounded-md shadow-lg text-center tracking-wider uppercase block transition-transform hover:scale-[1.01] mt-6">
                        Proceed to Secure Checkout <i data-lucide="chevron-right" class="w-4 h-4 inline-block -mt-0.5 ml-1"></i>
                    </a>
                </div>
            </div>

        </div>
    <?php endif; ?>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
