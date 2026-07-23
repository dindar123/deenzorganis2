<?php
/**
 * Deenz Organics - Secure Order Success & Printable Invoice
 */
require_once __DIR__ . '/includes/db.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Retrieve last order details from Session or query DB
$order = $_SESSION['last_order'] ?? null;

if (empty($order)) {
    // If accessed directly without an active transaction, show a simulated demo invoice for testing
    $order = [
        'id' => rand(1000, 9999),
        'order_number' => "DO-20260720-4821",
        'customer_name' => "Dr. Deen Mohd",
        'customer_email' => "dr.deenmohd@gmail.com",
        'customer_phone' => "+91 60060 49016",
        'shipping_address' => "Pahalgam & Pampore, Jammu and Kashmir - 192126",
        'items' => [
            [
                'id' => 1,
                'name' => 'Premium Kashmiri Walnut Kernels (Akhrot Giri)',
                'sku' => 'DZ-WLN-001',
                'price' => 750.00,
                'quantity' => 2,
                'weight' => '400g'
            ]
        ],
        'subtotal' => 1500.00,
        'discount' => 150.00,
        'shipping' => 0.00,
        'tax' => 67.50,
        'total' => 1417.50,
        'notes' => 'Airtight packaging requested.',
        'payment_method' => 'demo_pay',
        'status' => 'paid',
        'created_at' => date("Y-m-d H:i:s")
    ];
}

$page_title = "Order Successfully Paid";
require_once __DIR__ . '/includes/header.php';
?>

<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
    
    <!-- Congratulations Notice -->
    <div class="text-center space-y-4 mb-12">
        <div class="w-16 h-16 bg-emerald-100 border border-emerald-200 text-emerald-600 rounded-full flex items-center justify-center text-3xl mx-auto animate-bounce">
            ✓
        </div>
        <h1 class="font-display font-bold text-3xl text-luxury-950">Payment Received & Confirmed</h1>
        <p class="text-xs text-luxury-500 max-w-sm mx-auto leading-relaxed">
            Thank you for purchasing from Deenz Organics! Your order has been registered securely and is scheduled for air cargo sorting.
        </p>
    </div>

    <!-- Printable Invoice Card -->
    <div class="bg-white border border-luxury-200 rounded-2xl p-8 shadow-sm space-y-8" id="printable-invoice">
        
        <!-- Invoice Header -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-6 border-b border-luxury-100 pb-6">
            <div class="space-y-1">
                <span class="font-display font-bold text-lg text-luxury-950 uppercase tracking-wide">DEENZ ORGANICS</span>
                <p class="text-[10px] font-mono text-luxury-400">Pahalgam & Pampore, J&K, India</p>
                <p class="text-[10px] font-mono text-luxury-400">Helpline: +91 60060 49016 / +91 60050 92150</p>
            </div>
            <div class="text-left sm:text-right">
                <span class="bg-emerald-100 text-emerald-800 text-[10px] font-mono font-bold px-2.5 py-1 rounded uppercase tracking-wider">
                    Official Tax Invoice
                </span>
                <p class="text-xs text-luxury-950 font-bold mt-2 font-mono">Invoice #: <?php echo sanitize_html($order['order_number']); ?></p>
                <p class="text-[11px] text-luxury-400 font-mono">Date: <?php echo sanitize_html($order['created_at']); ?></p>
            </div>
        </div>

        <!-- Address Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-8 text-xs leading-relaxed">
            <div class="space-y-1">
                <h4 class="font-bold text-luxury-500 uppercase tracking-widest text-[10px] font-display">Billed & Shipped To</h4>
                <p class="font-bold text-luxury-950 text-sm"><?php echo sanitize_html($order['customer_name']); ?></p>
                <p class="text-luxury-600"><?php echo sanitize_html($order['shipping_address']); ?></p>
                <p class="text-luxury-600">Phone: <?php echo sanitize_html($order['customer_phone']); ?></p>
                <p class="text-luxury-400 font-mono">Email: <?php echo sanitize_html($order['customer_email']); ?></p>
            </div>
            <div class="space-y-1 sm:text-right">
                <h4 class="font-bold text-luxury-500 uppercase tracking-widest text-[10px] font-display sm:text-right">Payment Status</h4>
                <p class="text-luxury-600">Payment Gateway: <strong class="text-luxury-950 uppercase"><?php echo sanitize_html($order['payment_method']); ?></strong></p>
                <p class="text-luxury-600">Payment Status: <strong class="text-emerald-600 font-bold uppercase">PAID & COMPLETED</strong></p>
                <p class="text-luxury-600">Fulfillment: <strong class="text-amber-600 font-bold uppercase">PREPARING DISPATCH</strong></p>
                <?php if (!empty($order['notes'])): ?>
                    <p class="text-luxury-400 mt-2 text-[11px] italic">Notes: "<?php echo sanitize_html($order['notes']); ?>"</p>
                <?php endif; ?>
            </div>
        </div>

        <!-- Items Table -->
        <div class="border border-luxury-100 rounded-xl overflow-hidden">
            <table class="w-full text-left text-xs text-luxury-700">
                <thead>
                    <tr class="bg-luxury-100/50 border-b border-luxury-200 font-display font-semibold text-luxury-900">
                        <th class="p-4">Harvest Commodity Item</th>
                        <th class="p-4 text-center">Unit Price</th>
                        <th class="p-4 text-center">Qty</th>
                        <th class="p-4 text-right">Total</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-luxury-50">
                    <?php foreach ($order['items'] as $item): ?>
                        <tr>
                            <td class="p-4">
                                <div class="font-bold text-luxury-950"><?php echo sanitize_html($item['name']); ?></div>
                                <span class="text-[10px] font-mono text-luxury-400">SKU: <?php echo sanitize_html($item['sku']); ?> | Variant: <?php echo sanitize_html($item['weight'] ?? '400g'); ?></span>
                            </td>
                            <td class="p-4 text-center font-mono">₹<?php echo number_format($item['price'], 2); ?></td>
                            <td class="p-4 text-center font-mono font-bold"><?php echo $item['quantity']; ?></td>
                            <td class="p-4 text-right font-mono font-bold text-luxury-950">₹<?php echo number_format($item['price'] * $item['quantity'], 2); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <!-- Financial Ledger -->
        <div class="flex justify-end pt-4">
            <div class="w-full sm:w-80 space-y-3 text-xs text-luxury-600 border-t border-luxury-100 pt-4">
                <div class="flex justify-between">
                    <span>Subtotal Items</span>
                    <span class="font-mono font-bold text-luxury-950">₹<?php echo number_format($order['subtotal'], 2); ?></span>
                </div>
                
                <?php if ($order['discount'] > 0): ?>
                    <div class="flex justify-between text-red-600">
                        <span>Coupon Deduction</span>
                        <span class="font-mono font-bold">-₹<?php echo number_format($order['discount'], 2); ?></span>
                    </div>
                <?php endif; ?>

                <div class="flex justify-between">
                    <span>Air Cargo Carriage</span>
                    <span class="font-mono font-bold text-luxury-950">
                        <?php echo $order['shipping'] == 0 ? '<span class="text-emerald-600">FREE</span>' : '₹' . number_format($order['shipping'], 2); ?>
                    </span>
                </div>

                <div class="flex justify-between">
                    <span>Estimated 5% GST</span>
                    <span class="font-mono font-bold text-luxury-950">₹<?php echo number_format($order['tax'], 2); ?></span>
                </div>

                <div class="border-t border-luxury-200 pt-3 flex justify-between text-sm font-display font-bold text-luxury-950">
                    <span>Total Paid</span>
                    <span class="font-mono text-base text-luxury-900">₹<?php echo number_format($order['total'], 2); ?></span>
                </div>
            </div>
        </div>

        <!-- Footer terms -->
        <div class="text-center text-[10px] text-luxury-400 border-t border-luxury-100 pt-6">
            <span>Thank you for supporting sustainable farmers in Pahalgam & Pampore, Kashmir. This is a computer-generated tax invoice dually verified under Indian organic standards.</span>
        </div>
    </div>

    <!-- Actions bar -->
    <div class="flex flex-wrap items-center justify-between gap-4 mt-8">
        <a href="index.php" class="font-display font-bold text-xs text-luxury-900 hover:text-luxury-600 flex items-center gap-1">
            <i data-lucide="arrow-left" class="w-4 h-4"></i> Return to Store Homepage
        </a>
        
        <div class="flex gap-4">
            <!-- Tracking Link -->
            <a href="track.php?order_number=<?php echo urlencode($order['order_number']); ?>" class="border border-luxury-300 hover:border-luxury-500 font-display text-xs font-semibold px-4 py-2.5 rounded-md text-luxury-700 hover:text-luxury-950 flex items-center gap-1.5 transition-all">
                <i data-lucide="truck" class="w-4 h-4"></i> Track Dispatch Delivery
            </a>

            <!-- Print Trigger -->
            <button onclick="window.print();" class="bg-luxury-950 hover:bg-luxury-800 font-display text-xs font-semibold px-5 py-2.5 rounded-md text-white flex items-center gap-1.5 shadow transition-all hover:scale-[1.01]">
                <i data-lucide="printer" class="w-4 h-4"></i> Print Invoice PDF
            </button>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
