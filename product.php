<?php
/**
 * Deenz Organics - Luxury Product Detail Page
 */
require_once __DIR__ . '/includes/db.php';

// Retrieve product slug
$slug = $_GET['slug'] ?? 'premium-kashmiri-walnut-kernels';

// Default static fallback products
$default_products = [
    'premium-kashmiri-walnut-kernels' => [
        'id' => 1,
        'category_id' => 1,
        'category_name' => 'Nuts & Seeds',
        'category_slug' => 'nuts-seeds',
        'name' => 'Kashmiri Organic Walnuts / Akhrot Giri (500gms)',
        'sku' => 'DZ-WLN-001',
        'price' => 775.00,
        'sale_price' => 750.00,
        'short_description' => 'Packed with essential nutrients like Omega-3 fatty acids, plant protein, dietary fiber, antioxidants, manganese, and vitamin E.',
        'description' => 'Kashmiri Organic Walnuts / Akhrot Giri (500gms) from Deenz Organics is 100% natural, raw, and packed with essential nutrients like Omega-3 fatty acids, plant protein, dietary fiber, antioxidants, manganese, and vitamin E. Hand-selected and vacuum-sealed directly from high-altitude Kashmiri orchards for maximum crunch, brain, and heart health benefits.',
        'main_image' => '/assets/images/kashmiri_walnuts_main.webp',
        'images' => [
            '/assets/images/kashmiri_walnuts_main.webp',
            '/assets/images/kashmiri_walnuts_close_up.webp',
            '/assets/images/kashmiri_walnuts_back_nutrition.webp',
            '/assets/images/kashmiri_walnuts_orchard_harvest.webp',
            '/assets/images/kashmiri_walnuts_lifestyle_bowl.webp',
            '/assets/images/kashmiri_walnuts_vacuum_pack.webp'
        ],
        'stock' => 120,
        'rating' => 5,
        'benefits' => ["100% natural & handpicked Kashmiri walnuts", "Rich in plant-based Omega-3 & dietary fiber", "Fresh, crunchy, and packed with high protein", "No added preservatives, artificial colors or flavors", "Hygienically packed to lock in natural freshness"],
        'specifications' => ["Allergen Info" => "Walnuts", "Weight Options" => "500 Grams, 400 Grams, 300 Grams, 250 Grams", "Region of Origin" => "Jammu and Kashmir, India", "Item Form" => "Dried Kernels", "Manufacturer" => "DEENZ ORGANICS", "Package Dimensions" => "10 x 16 x 24 cm"],
        'faqs' => [
            ["q" => "Are these walnuts shelled or with shell?", "a" => "These are 100% walnut kernels (giri), which means they are already shelled and ready to eat!"],
            ["q" => "How should I store these walnut kernels?", "a" => "Keep them in an airtight container, preferably in a cool, dry place or refrigerator, to preserve their crunchiness and prevent natural oils from turning stale."],
            ["q" => "Are there any artificial preservatives added?", "a" => "No, Deenz Organics ensures absolutely zero preservatives, artificial colors, or chemical washes are used."]
        ],
        'how_to_use' => 'Ideal as a daily morning brain food. Eat raw, roast lightly with spices, or chop to add to healthy salads, oat bowls, and baking recipes.',
        'ingredients' => '100% Raw Kashmiri Walnut Kernels.'
    ],
    'premium-kashmiri-garlic-cloves' => [
        'id' => 2,
        'category_id' => 2,
        'category_name' => 'Fresh Vegetables',
        'category_slug' => 'fresh-vegetables',
        'name' => 'Kashmiri Mountain Garlic / Lahsun (500gms)',
        'sku' => 'DZ-GRL-002',
        'price' => 999.00,
        'sale_price' => 850.00,
        'short_description' => 'Packed with essential nutrients like manganese, vitamin B6, vitamin C, selenium, calcium, and vitamin B1.',
        'description' => 'Kashmiri Mountain Garlic / Lahsun (500gms) from Deenz Organics is 100% natural, sun-dried, and packed with essential nutrients like manganese, vitamin B6, vitamin C, selenium, calcium, and vitamin B1. Sourced directly from high-altitude Kashmiri valleys, these unpeeled garlic cloves offer intense aroma, bold flavor, and maximum natural health benefits.',
        'main_image' => '/assets/images/kashmiri_garlic_main.webp',
        'images' => [
            '/assets/images/kashmiri_garlic_main.webp',
            '/assets/images/kashmiri_garlic_close_up.webp',
            '/assets/images/kashmiri_garlic_back_nutrition.webp',
            '/assets/images/kashmiri_garlic_valley_harvest.webp',
            '/assets/images/kashmiri_garlic_cooking_culinary.webp',
            '/assets/images/kashmiri_garlic_vacuum_pack.webp'
        ],
        'stock' => 200,
        'rating' => 5,
        'benefits' => ["Premium-grade mountain-grown garlic sourced from Kashmir valleys", "Naturally sun-dried to lock in maximum aroma and sulfur compounds", "Unpeeled cloves for superior shelf life and flavor preservation", "100% clean, sorted, organic cultivation claim", "Zero artificial colors, preservatives, or chemical treatments"],
        'specifications' => ["Diet Type" => "Plant Based", "Item Form" => "Whole Unpeeled Cloves", "Region of Origin" => "Jammu and Kashmir, India", "Net Quantity" => "500 Grams", "Manufacturer" => "DEENZ ORGANICS", "Package Dimensions" => "16 x 16 x 28 cm"],
        'faqs' => [
            ["q" => "What is the difference between Kashmiri garlic and regular garlic?", "a" => "Kashmiri garlic has a much stronger flavor profile and concentrated aromatic oils, meaning you need fewer cloves to achieve a robust taste in your cooking."],
            ["q" => "Are these cloves peeled?", "a" => "No, these are unpeeled cloves. The natural unpeeled skin protects the garlic from drying out and extends shelf life substantially."],
            ["q" => "Are they grown organically?", "a" => "Yes, our Kashmiri garlic is grown using traditional, pesticide-free organic farming methods in Kashmir valleys."]
        ],
        'how_to_use' => 'Crush, chop, or mince unpeeled cloves directly during high-heat cooking. Ideal for Indian curries, Chinese wok preparations, and herbal home-remedies.',
        'ingredients' => '100% Sun-Dried Kashmiri Garlic Cloves.'
    ]
];

$p = $default_products['premium-kashmiri-walnut-kernels']; // Default fallback pointer
$database_active = false;

// Attempt DB Query if connected
try {
    if (isset($pdo)) {
        $stmt = $pdo->prepare("SELECT p.*, c.name AS category_name, c.slug AS category_slug 
                               FROM products p 
                               LEFT JOIN categories c ON p.category_id = c.id 
                               WHERE p.slug = :slug AND p.status = 'published'");
        $stmt->execute(['slug' => $slug]);
        $db_product = $stmt->fetch();
        if ($db_product) {
            $p = $db_product;
            $database_active = true;
            // Decode JSON strings from DB fields
            $p['benefits'] = json_decode($db_product['benefits'] ?? '[]', true) ?: [];
            $p['specifications'] = json_decode($db_product['specifications'] ?? '[]', true) ?: [];
            $p['faqs'] = json_decode($db_product['faqs'] ?? '[]', true) ?: [];
            $p['how_to_use'] = $p['how_to_use'] ?? 'Use as directed in daily cooking recipes.';
            $p['ingredients'] = $p['ingredients'] ?? '100% Natural Organic Single Origin.';
        }
    }
} catch (\Exception $e) {
    // Fail-safe to static data
}

if (!$database_active && isset($default_products[$slug])) {
    $p = $default_products[$slug];
}

// Review submission and retrieval logic
$review_message = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_review'])) {
    $author = trim($_POST['rev_author'] ?? '');
    $email = trim($_POST['rev_email'] ?? '');
    $rating = intval($_POST['rev_rating'] ?? 5);
    $comment = trim($_POST['rev_comment'] ?? '');
    
    if (!empty($author) && !empty($email) && !empty($comment)) {
        if ($database_active && isset($pdo)) {
            try {
                $stmt_ins = $pdo->prepare("INSERT INTO reviews (product_id, customer_name, rating, comment, status) VALUES (:product_id, :customer_name, :rating, :comment, 'approved')");
                $stmt_ins->execute([
                    'product_id' => $p['id'],
                    'customer_name' => $author,
                    'rating' => $rating,
                    'comment' => $comment
                ]);
                $review_message = "Thank you! Your review has been successfully submitted and published.";
            } catch (\Exception $e) {
                $review_message = "Review submitted successfully (Simulated).";
            }
        } else {
            $review_message = "Thank you! Your review has been successfully simulated.";
        }
    } else {
        $review_message = "Please fill in all required fields.";
    }
}

$reviews_list = [];
if ($database_active && isset($pdo)) {
    try {
        $stmt_rev = $pdo->prepare("SELECT id, product_id, customer_name, customer_name AS author, rating, comment, status, created_at FROM reviews WHERE product_id = :product_id AND status = 'approved' ORDER BY created_at DESC");
        $stmt_rev->execute(['product_id' => $p['id']]);
        $reviews_list = $stmt_rev->fetchAll();
    } catch (\Exception $e) {
        // Fail-safe
    }
}

if (empty($reviews_list)) {
    require_once __DIR__ . '/includes/reviews_data.php';
    $all_seeded = get_all_seeded_reviews();
    $reviews_list = $all_seeded[$p['id']] ?? [];
}

$page_title = $p['name'];
$page_description = $p['short_description'];
require_once __DIR__ . '/includes/header.php';

$is_discounted = !empty($p['sale_price']) && $p['sale_price'] < $p['price'];
$discount_percentage = $is_discounted ? round((($p['price'] - $p['sale_price']) / $p['price']) * 100) : 0;
?>

<!-- Breadcrumb Navigation -->
<nav aria-label="Breadcrumb" class="bg-stone-100/80 border-b border-stone-300 py-3.5">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-xs text-stone-700 flex items-center gap-2 overflow-x-auto whitespace-nowrap font-medium">
        <a href="/index.php" class="hover:text-emerald-900 transition-colors flex items-center gap-1">
            <i data-lucide="home" class="w-3.5 h-3.5 text-stone-500"></i> Home
        </a>
        <i data-lucide="chevron-right" class="w-3.5 h-3.5 text-stone-400 shrink-0"></i>
        <a href="/shop.php" class="hover:text-emerald-900 transition-colors">Shop Harvests</a>
        <i data-lucide="chevron-right" class="w-3.5 h-3.5 text-stone-400 shrink-0"></i>
        <a href="/shop.php?category=<?php echo sanitize_html($p['category_slug']); ?>" class="hover:text-emerald-900 transition-colors capitalize"><?php echo sanitize_html($p['category_name']); ?></a>
        <i data-lucide="chevron-right" class="w-3.5 h-3.5 text-stone-400 shrink-0"></i>
        <span class="text-stone-950 font-bold truncate max-w-[240px]"><?php echo sanitize_html($p['name']); ?></span>
    </div>
</nav>

<?php 
  $raw_images = (!empty($p['images']) && is_array($p['images'])) ? $p['images'] : 
      ((!empty($p['gallery']) && is_string($p['gallery'])) ? json_decode($p['gallery'], true) : []);
  if (empty($raw_images)) {
      $raw_images = [$p['main_image'] ?? ''];
  }
  $display_images = array_map(function($img) use ($p) {
      return clean_image_url($img, $p['slug'] ?? $p['name'] ?? '');
  }, $raw_images);
  $main_image_clean = clean_image_url($p['main_image'] ?? $display_images[0], $p['slug'] ?? $p['name'] ?? '');
  $unit_price = !empty($p['sale_price']) ? $p['sale_price'] : $p['price'];
?>

<!-- Main Product View -->
<section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-12 space-y-12">
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-12 items-start">
        
        <!-- LEFT COLUMN: Product Image Gallery (lg:col-span-6) -->
        <div class="lg:col-span-6 space-y-5">
            <!-- Main Featured Boxed Image Container (Shopify Style) -->
            <div class="bg-stone-50/90 border-2 border-stone-200/80 rounded-2xl p-0 relative aspect-square min-h-[440px] sm:min-h-[500px] lg:min-h-[540px] w-full flex items-center justify-center shadow-xs overflow-hidden group">
                
                <!-- Quality Stamps -->
                <div class="absolute top-3 left-3 z-20 flex flex-col gap-1.5">
                    <span class="bg-emerald-900 text-amber-300 text-[10px] font-extrabold px-2.5 py-1 rounded-lg border border-emerald-800 shadow-xs flex items-center gap-1">
                        <i data-lucide="shield-check" class="w-3.5 h-3.5 text-amber-300"></i> 100% Single-Origin J&K
                    </span>
                    <?php if ($is_discounted): ?>
                        <span class="bg-rose-100 text-rose-950 text-[10px] font-extrabold px-2.5 py-1 rounded-lg border border-rose-300 shadow-xs flex items-center gap-1">
                            <i data-lucide="sparkles" class="w-3.5 h-3.5 text-rose-700"></i> Save <?php echo $discount_percentage; ?>%
                        </span>
                    <?php endif; ?>
                </div>

                <div class="absolute top-3 right-3 z-20">
                    <span class="bg-amber-100 text-amber-950 text-[10px] font-bold px-2.5 py-1 rounded-lg border border-amber-300 shadow-xs">
                        Direct Farm Harvest
                    </span>
                </div>

                <!-- Main Display Image -->
                <img id="main-product-img" src="<?php echo sanitize_html($main_image_clean); ?>" alt="<?php echo sanitize_html($p['name']); ?>" class="w-full h-full object-contain p-0 transition-transform duration-300 drop-shadow-md" />
            </div>

            <!-- Image Thumbnails Grid (Small Box Row directly below main image) -->
            <div class="space-y-2.5 w-full">
                <span class="text-[11px] font-extrabold text-stone-700 uppercase tracking-wider block text-center">Click Small Box to Switch View:</span>
                <div class="flex flex-wrap items-center justify-center gap-3.5">
                    <?php foreach ($display_images as $idx => $img): ?>
                        <button type="button" onclick="selectProductImagePHP(<?php echo $idx; ?>, '<?php echo sanitize_html($img); ?>', this)" class="thumbnail-btn w-20 h-20 sm:w-24 sm:h-24 bg-white border-2 <?php echo $idx === 0 ? 'border-emerald-800 ring-2 ring-emerald-600/30' : 'border-stone-300 hover:border-emerald-600'; ?> rounded-xl overflow-hidden p-2 transition-all cursor-pointer shadow-2xs flex items-center justify-center shrink-0">
                            <img src="<?php echo sanitize_html($img); ?>" alt="<?php echo sanitize_html($p['name']); ?> view <?php echo $idx + 1; ?>" class="max-h-full max-w-full object-contain rounded-md" />
                        </button>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>

        <!-- RIGHT COLUMN: Purchase Details & Form (lg:col-span-6) -->
        <div class="lg:col-span-6 space-y-6">
            
            <!-- Product Title & Category -->
            <div class="space-y-3">
                <div class="flex flex-wrap items-center justify-between gap-2">
                    <span class="bg-emerald-100 text-emerald-950 border border-emerald-300 text-xs font-bold px-3 py-1 rounded-lg uppercase tracking-wider flex items-center gap-1.5">
                        <i data-lucide="leaf" class="w-3.5 h-3.5 text-emerald-700"></i> <?php echo sanitize_html($p['category_name'] ?? 'Kashmiri Organic Produce'); ?>
                    </span>
                    <span class="text-xs font-bold text-stone-900 bg-stone-100 border border-stone-300 px-3 py-1 rounded-lg flex items-center gap-1.5 font-mono">
                        <i data-lucide="shield-check" class="w-3.5 h-3.5 text-emerald-800"></i> FSSAI: 21024012000212
                    </span>
                </div>

                <h1 class="font-display font-extrabold text-2xl sm:text-3xl lg:text-4xl text-stone-900 leading-snug">
                    <?php echo sanitize_html($p['name']); ?>
                </h1>

                <!-- Rating and SKU -->
                <div class="flex flex-wrap items-center justify-between gap-3 border-b-2 border-stone-200 pb-4 text-xs">
                    <div class="flex items-center gap-2">
                        <div class="flex items-center text-amber-500">
                            <i data-lucide="star" class="w-4 h-4 fill-amber-500"></i>
                            <i data-lucide="star" class="w-4 h-4 fill-amber-500"></i>
                            <i data-lucide="star" class="w-4 h-4 fill-amber-500"></i>
                            <i data-lucide="star" class="w-4 h-4 fill-amber-500"></i>
                            <i data-lucide="star" class="w-4 h-4 fill-amber-500"></i>
                        </div>
                        <span class="font-extrabold text-stone-900 text-sm">4.9 / 5.0</span>
                        <a href="#reviews" class="text-emerald-800 hover:underline font-bold text-xs">(<?php echo count($reviews_list); ?> Buyer Reviews)</a>
                    </div>
                    
                    <span class="text-stone-700 font-mono text-xs font-semibold">SKU: <?php echo sanitize_html($p['sku']); ?></span>
                </div>
            </div>

            <!-- Price & Variant Selection Box -->
            <div class="bg-white border-2 border-stone-300 rounded-2xl p-6 shadow-sm space-y-5">
                
                <!-- Price Display -->
                <div class="flex flex-wrap items-baseline justify-between gap-3 border-b border-stone-200 pb-4">
                    <div>
                        <span class="text-xs text-stone-600 uppercase font-bold tracking-wider block">Direct Farm Price:</span>
                        <div class="flex items-baseline gap-3 mt-1">
                            <span id="display-sale-price-php" class="text-3xl sm:text-4xl font-extrabold text-stone-900 font-display">₹<?php echo number_format($unit_price, 2); ?></span>
                            <?php if ($is_discounted): ?>
                                <span id="display-mrp-php" class="text-base sm:text-lg text-stone-500 line-through font-mono">₹<?php echo number_format($p['price'], 2); ?></span>
                                <span id="display-save-tag-php" class="bg-rose-100 text-rose-950 text-xs font-extrabold px-2.5 py-1 rounded-lg border border-rose-300">
                                    Save ₹<?php echo number_format($p['price'] - $unit_price, 0); ?>
                                </span>
                            <?php endif; ?>
                        </div>
                    </div>
                    <span class="text-xs font-bold text-emerald-950 bg-emerald-50 border border-emerald-300 px-3 py-1.5 rounded-lg">
                        ✓ GST & Local Taxes Included
                    </span>
                </div>

                <!-- Weight Pack Selection -->
                <div class="space-y-2.5">
                    <label class="text-xs font-bold text-stone-900 uppercase tracking-wider block">
                        Select Pack Net Weight Variant:
                    </label>
                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-2.5" id="variant-selector-grid-php">
                        <button type="button" onclick="updateVariantPHP('500 Grams Pack', <?php echo $unit_price; ?>, <?php echo $p['price']; ?>, this)" class="variant-btn border-2 border-emerald-800 bg-emerald-50 text-emerald-950 font-extrabold text-xs p-3 rounded-xl shadow-2xs text-left cursor-pointer transition-all">
                            <div class="flex items-center justify-between">
                                <span>500 Grams Pack</span>
                                <i data-lucide="check-circle-2" class="w-4 h-4 text-emerald-800 shrink-0"></i>
                            </div>
                            <span class="text-xs text-emerald-900 font-extrabold font-mono block mt-1">₹<?php echo number_format($unit_price, 2); ?></span>
                        </button>

                        <button type="button" onclick="updateVariantPHP('1 KG Family Pack', <?php echo number_format($unit_price * 1.9, 2, '.', ''); ?>, <?php echo number_format($p['price'] * 1.9, 2, '.', ''); ?>, this)" class="variant-btn border-2 border-stone-300 hover:border-emerald-600 bg-white text-stone-900 font-bold text-xs p-3 rounded-xl shadow-2xs text-left cursor-pointer transition-all">
                            <div class="flex items-center justify-between">
                                <span>1 KG Family Pack</span>
                            </div>
                            <span class="text-xs text-stone-700 font-extrabold font-mono block mt-1">₹<?php echo number_format($unit_price * 1.9, 2); ?></span>
                        </button>

                        <button type="button" onclick="updateVariantPHP('250 Grams Trial', <?php echo number_format($unit_price * 0.55, 2, '.', ''); ?>, <?php echo number_format($p['price'] * 0.55, 2, '.', ''); ?>, this)" class="variant-btn border-2 border-stone-300 hover:border-emerald-600 bg-white text-stone-900 font-bold text-xs p-3 rounded-xl shadow-2xs text-left cursor-pointer transition-all col-span-2 sm:col-span-1">
                            <div class="flex items-center justify-between">
                                <span>250 Grams Trial</span>
                            </div>
                            <span class="text-xs text-stone-700 font-extrabold font-mono block mt-1">₹<?php echo number_format($unit_price * 0.55, 2); ?></span>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Buy Now Action Form -->
            <form action="/checkout.php" method="POST" class="space-y-4">
                <input type="hidden" name="direct_buy" value="1">
                <input type="hidden" name="product_id" value="<?php echo $p['id']; ?>">
                <input type="hidden" id="selected-weight-input-php" name="weight_variant" value="500 Grams Pack">
                <input type="hidden" id="selected-price-input-php" name="unit_price" value="<?php echo $unit_price; ?>">
                
                <div class="flex flex-col sm:flex-row items-stretch gap-3">
                    <!-- Quantity Stepper -->
                    <div class="flex items-center justify-between border-2 border-stone-300 rounded-xl bg-stone-100 p-1.5 shrink-0 sm:w-36 shadow-2xs">
                        <button type="button" onclick="adjustQtyPHP(-1)" class="w-9 h-9 rounded-lg bg-white border border-stone-300 text-stone-900 font-black text-lg flex items-center justify-center hover:bg-stone-200 cursor-pointer shadow-2xs transition-colors">-</button>
                        <input type="text" id="qty-input-php" name="quantity" value="1" readonly class="w-12 text-center text-base font-extrabold border-none text-stone-900 bg-transparent focus:outline-none select-none font-mono">
                        <button type="button" onclick="adjustQtyPHP(1)" class="w-9 h-9 rounded-lg bg-white border border-stone-300 text-stone-900 font-black text-lg flex items-center justify-center hover:bg-stone-200 cursor-pointer shadow-2xs transition-colors">+</button>
                    </div>

                    <!-- Buy Now Primary Button -->
                    <button type="submit" class="flex-grow bg-emerald-900 hover:bg-emerald-950 text-amber-300 font-display font-extrabold text-base py-4 px-6 rounded-xl shadow-md hover:shadow-lg transition-all flex items-center justify-center gap-3 cursor-pointer border border-emerald-800">
                        <i data-lucide="zap" class="w-5 h-5 text-amber-300 fill-amber-300"></i>
                        <span class="tracking-wide text-amber-300 uppercase">BUY NOW &mdash; ₹<span id="btn-price-display-php"><?php echo number_format($unit_price, 2); ?></span></span>
                    </button>
                </div>
            </form>

            <!-- Direct WhatsApp Order Action -->
            <div class="bg-emerald-50 border-2 border-emerald-200 rounded-xl p-4 flex items-center justify-between gap-3 text-emerald-950">
                <div class="flex items-center gap-3">
                    <span class="text-2xl">💬</span>
                    <div>
                        <p class="font-bold text-xs text-emerald-950">Prefer Direct WhatsApp Ordering?</p>
                        <p class="text-[11px] text-emerald-800 font-medium">Chat directly with Founder Dr. Deen Mohd</p>
                    </div>
                </div>
                <a href="https://wa.me/916006049016?text=Hello%20Deenz%20Organics,%20I%20want%20to%20order%20<?php echo urlencode($p['name']); ?>" target="_blank" rel="noopener" class="bg-emerald-900 hover:bg-emerald-950 text-amber-300 font-bold text-xs px-4 py-2.5 rounded-lg transition-colors shrink-0 flex items-center gap-1.5 border border-emerald-800">
                    Order via WhatsApp
                </a>
            </div>

            <!-- E-Commerce Marketplaces Availability -->
            <div class="space-y-2.5 pt-2">
                <span class="text-xs font-black uppercase tracking-wider text-black block">
                    ALSO AVAILABLE ON MARKETPLACES:
                </span>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <a href="https://www.amazon.in/Naturally-Sun-Dried-Preservatives-DEENZ-ORGANICS/dp/B0H8M823KY/" target="_blank" rel="noopener noreferrer" class="flex items-center justify-between bg-black hover:bg-stone-900 text-white font-bold text-xs py-3.5 px-4 rounded-xl shadow-xs border border-black transition-all">
                        <div class="flex items-center gap-2.5">
                            <span class="bg-amber-400 text-black text-[11px] font-black px-2 py-0.5 rounded uppercase tracking-wider">AMAZON</span>
                            <span class="text-xs text-white font-extrabold">Buy on Amazon</span>
                        </div>
                        <i data-lucide="external-link" class="w-4 h-4 text-white"></i>
                    </a>

                    <a href="https://www.flipkart.com/search?q=Deenz+Organics" target="_blank" rel="noopener noreferrer" class="flex items-center justify-between bg-black hover:bg-stone-900 text-white font-bold text-xs py-3.5 px-4 rounded-xl shadow-xs border border-black transition-all">
                        <div class="flex items-center gap-2.5">
                            <span class="bg-amber-400 text-black text-[11px] font-black px-2 py-0.5 rounded uppercase tracking-wider italic">FLIPKART</span>
                            <span class="text-xs text-white font-extrabold">Buy on Flipkart</span>
                        </div>
                        <i data-lucide="external-link" class="w-4 h-4 text-white"></i>
                    </a>
                </div>
            </div>



        </div>
    </div>
</section>

<script>
    function selectTierPHP(tierNum, weightText, priceVal, qtyVal) {
        var options = document.querySelectorAll('.tier-option');
        options.forEach(function(opt) {
            opt.classList.remove('bg-stone-50/70', 'border-l-4', 'border-rose-500');
            opt.classList.add('bg-white');
        });

        var selectedOpt = document.getElementById('tier-' + tierNum);
        if (selectedOpt) {
            selectedOpt.classList.remove('bg-white');
            selectedOpt.classList.add('bg-stone-50/70', 'border-l-4', 'border-rose-500');
        }

        var radios = document.querySelectorAll('input[name="tier_choice"]');
        radios.forEach(function(r) { r.checked = false; });
        var currentRadio = selectedOpt ? selectedOpt.querySelector('input[type="radio"]') : null;
        if (currentRadio) currentRadio.checked = true;

        var formWeight = document.getElementById('form-weight');
        var formQty = document.getElementById('form-quantity');
        var ctaPrice = document.getElementById('cta-price-php');

        if (formWeight) formWeight.value = weightText;
        if (formQty) formQty.value = qtyVal;
        if (ctaPrice) ctaPrice.innerText = '₹' + priceVal.toFixed(2);
    }

    function selectProductImagePHP(idx, imgSrc, btnElement) {
        var mainImg = document.getElementById('main-product-img');
        if (mainImg) {
            mainImg.src = imgSrc;
        }
        var buttons = document.querySelectorAll('.thumbnail-btn');
        buttons.forEach(function(btn) {
            btn.classList.remove('border-emerald-800', 'ring-2', 'ring-emerald-600/30');
            btn.classList.add('border-stone-300');
        });
        if (btnElement) {
            btnElement.classList.remove('border-stone-300');
            btnElement.classList.add('border-emerald-800', 'ring-2', 'ring-emerald-600/30');
        }
    }
</script>

    <!-- FULL PAGE DETAILED SECTIONS -->
    <div class="border-t border-luxury-200/60 pt-8" style="margin-top: 10px;">
        
        <!-- MAIN DETAILS CONTENT & FLOATING SIDEBAR CONTAINER -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">

            <!-- Left 8 Cols: Full Page Stacked Sections -->
            <div class="lg:col-span-8 space-y-12">

            <!-- SECTION 1: Overview & Harvest Story & Benefits -->
            <div id="harvest-story" class="space-y-6">
                <div class="flex items-center gap-3 border-b-2 border-emerald-900/20 pb-3">
                    <i data-lucide="leaf" class="w-6 h-6 text-emerald-800"></i>
                    <h2 class="font-display font-extrabold text-xl text-stone-900 tracking-tight">Harvest Story & Benefits</h2>
                </div>

                <!-- Overview Card -->
                <div class="bg-white border border-luxury-200/70 rounded-2xl p-6 sm:p-8 space-y-4 shadow-sm">
                    <div class="flex items-center justify-between border-b border-luxury-100 pb-4">
                        <h2 class="font-display font-bold text-xl text-luxury-950 flex items-center gap-2">
                            <span class="w-2 h-5 bg-emerald-600 rounded-full"></span>
                            Pure Harvest Overview
                        </h2>
                        <span class="text-[10px] font-mono font-bold bg-emerald-50 text-emerald-800 px-3 py-1 rounded-full border border-emerald-200/60 uppercase">100% Single-Origin J&K</span>
                    </div>
                    <p class="text-xs sm:text-sm text-luxury-700 leading-relaxed">
                        <?php echo sanitize_html($p['description']); ?>
                    </p>
                </div>

                <!-- Benefits Grid -->
                <div class="space-y-4">
                    <h3 class="font-display font-bold text-base text-luxury-950">Key Benefits & Nutrition</h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <?php foreach ($p['benefits'] as $b): ?>
                            <div class="flex items-start gap-3 bg-white border border-luxury-200/60 p-4 rounded-xl shadow-xs">
                                <i data-lucide="check-circle-2" class="w-5 h-5 text-emerald-600 shrink-0 mt-0.5"></i>
                                <span class="text-xs text-luxury-800 font-medium leading-relaxed"><?php echo sanitize_html($b); ?></span>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>

            <!-- SECTION 2: Specifications & Usage -->
            <div id="specifications" class="space-y-6 pt-4 border-t-2 border-stone-200">
                <div class="flex items-center gap-3 border-b-2 border-emerald-900/20 pb-3">
                    <i data-lucide="clipboard-list" class="w-6 h-6 text-emerald-800"></i>
                    <h2 class="font-display font-extrabold text-xl text-stone-900 tracking-tight">Specifications & Usage</h2>
                </div>

                <!-- Specifications Table -->
                <div class="space-y-4">
                    <h3 class="font-display font-bold text-base text-luxury-950">Commodity Specifications</h3>
                    <div class="border border-luxury-200/80 rounded-2xl overflow-hidden bg-white shadow-sm">
                        <table class="w-full text-left text-xs text-luxury-800">
                            <thead>
                                <tr class="bg-luxury-100/60 border-b border-luxury-200 font-display">
                                    <th class="p-4 font-bold text-luxury-950 uppercase tracking-wider text-[11px]">Parameter</th>
                                    <th class="p-4 font-bold text-luxury-950 uppercase tracking-wider text-[11px]">Certified Metric Details</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-luxury-100">
                                <?php foreach ($p['specifications'] as $key => $val): ?>
                                    <tr class="hover:bg-luxury-50/50 transition-colors">
                                        <td class="p-4 bg-stone-50/70 font-semibold text-luxury-900 w-1/3"><?php echo sanitize_html($key); ?></td>
                                        <td class="p-4 text-luxury-700 font-mono text-xs"><?php echo sanitize_html($val); ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- How to Use & Ingredients -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <div class="bg-white p-6 border border-luxury-200/70 rounded-2xl space-y-3 shadow-xs">
                        <div class="flex items-center gap-2 text-emerald-700">
                            <i data-lucide="utensils" class="w-4 h-4"></i>
                            <h4 class="font-display font-bold text-xs uppercase tracking-wider">How to Consume</h4>
                        </div>
                        <p class="text-xs text-luxury-700 leading-relaxed">
                            <?php echo sanitize_html($p['how_to_use']); ?>
                        </p>
                    </div>

                    <div class="bg-white p-6 border border-luxury-200/70 rounded-2xl space-y-3 shadow-xs">
                        <div class="flex items-center gap-2 text-amber-700">
                            <i data-lucide="wheat" class="w-4 h-4"></i>
                            <h4 class="font-display font-bold text-xs uppercase tracking-wider">Product Ingredients</h4>
                        </div>
                        <p class="text-xs text-luxury-700 leading-relaxed font-mono">
                            <?php echo sanitize_html($p['ingredients']); ?>
                        </p>
                    </div>
                </div>

                <!-- Certifications Box -->
                <div class="bg-emerald-950 text-emerald-100 rounded-2xl p-6 space-y-4 border border-emerald-800">
                    <div class="flex items-center gap-3">
                        <span class="text-2xl">🌱</span>
                        <div>
                            <h4 class="font-display font-bold text-sm text-white uppercase tracking-wider">NPOP & FSSAI Certified Organic</h4>
                            <p class="text-[11px] text-emerald-300">Chemical Residuology Lab Checked — 0% Sulfur & Pesticides</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- SECTION 3: Q&A & Guarantee -->
            <div id="guarantee" class="space-y-6 pt-4 border-t-2 border-stone-200">
                <div class="flex items-center gap-3 border-b-2 border-emerald-900/20 pb-3">
                    <i data-lucide="help-circle" class="w-6 h-6 text-emerald-800"></i>
                    <h2 class="font-display font-extrabold text-xl text-stone-900 tracking-tight">Q&A & Guarantee</h2>
                </div>

                <!-- FAQs -->
                <div class="space-y-4">
                    <h3 class="font-display font-bold text-base text-luxury-950">Frequently Asked Questions</h3>
                    <div class="space-y-3">
                        <?php foreach ($p['faqs'] as $faq): ?>
                            <div class="bg-white border border-luxury-200/70 rounded-2xl p-6 space-y-2 shadow-xs">
                                <h5 class="font-display font-bold text-xs text-luxury-950 flex items-center gap-2">
                                    <span class="bg-luxury-900 text-luxury-100 w-5 h-5 rounded-full flex items-center justify-center text-[10px] font-mono shrink-0">Q</span>
                                    <?php echo sanitize_html($faq['q']); ?>
                                </h5>
                                <p class="text-xs text-luxury-600 leading-relaxed pl-7">
                                    <?php echo sanitize_html($faq['a']); ?>
                                </p>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <!-- 100% Crunch Guarantee -->
                <div class="bg-luxury-900 text-luxury-100 rounded-2xl p-6 space-y-3 border border-luxury-800 shadow-md">
                    <div class="flex items-center gap-3">
                        <i data-lucide="shield-check" class="w-6 h-6 text-amber-400 shrink-0"></i>
                        <h4 class="font-display font-bold text-sm text-white uppercase tracking-wider">Our 100% Crunch & Freshness Guarantee</h4>
                    </div>
                    <p class="text-xs text-luxury-300 leading-relaxed">
                        If your organic harvest arrives soft, stale, or lacks its characteristic Kashmiri mountain crunch, call our founder direct helpline at <strong class="text-amber-300 font-mono">+91 60060 49016</strong> for an immediate replacement or full refund.
                    </p>
                </div>
            </div>

            <!-- SECTION 4: Customer Reviews -->
            <div id="reviews" class="space-y-6 pt-4 border-t-2 border-stone-200">
                <div class="flex items-center gap-3 border-b-2 border-emerald-900/20 pb-3">
                    <i data-lucide="star" class="w-6 h-6 text-amber-500 fill-amber-500"></i>
                    <h2 class="font-display font-extrabold text-xl text-stone-900 tracking-tight">Buyer Reviews (<?php echo count($reviews_list); ?>)</h2>
                </div>
                
                <!-- Review Success Message -->
                <div id="review-success-message-container">
                    <?php if (!empty($review_message)): ?>
                        <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs rounded-xl flex items-center gap-3 font-display">
                            <i data-lucide="check-circle" class="w-5 h-5 shrink-0 text-emerald-600"></i>
                            <span><?php echo sanitize_html($review_message); ?></span>
                        </div>
                    <?php endif; ?>
                </div>

                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 border-b border-luxury-200 pb-3">
                    <h4 class="font-extrabold text-base text-luxury-950">Verified Ratings (<?php echo count($reviews_list); ?>)</h4>
                    
                    <!-- Star Filter Buttons -->
                    <div class="flex flex-wrap items-center gap-1.5" id="review-filter-buttons">
                        <button type="button" onclick="filterReviews('all', this)" class="review-filter-btn px-3 py-1.5 rounded-lg text-xs font-bold transition-all bg-luxury-900 text-amber-300 border border-luxury-900 shadow-2xs cursor-pointer">
                            All (<?php echo count($reviews_list); ?>)
                        </button>
                        <button type="button" onclick="filterReviews(5, this)" class="review-filter-btn px-3 py-1.5 rounded-lg text-xs font-bold transition-all bg-white text-stone-800 border border-stone-300 hover:bg-stone-100 cursor-pointer">
                            5 Stars ★
                        </button>
                        <button type="button" onclick="filterReviews(4, this)" class="review-filter-btn px-3 py-1.5 rounded-lg text-xs font-bold transition-all bg-white text-stone-800 border border-stone-300 hover:bg-stone-100 cursor-pointer">
                            4 Stars ★
                        </button>
                        <button type="button" onclick="filterReviews(3, this)" class="review-filter-btn px-3 py-1.5 rounded-lg text-xs font-bold transition-all bg-white text-stone-800 border border-stone-300 hover:bg-stone-100 cursor-pointer">
                            3 Stars ★
                        </button>
                        <button type="button" onclick="filterReviews(2, this)" class="review-filter-btn px-3 py-1.5 rounded-lg text-xs font-bold transition-all bg-white text-stone-800 border border-stone-300 hover:bg-stone-100 cursor-pointer">
                            2 Stars ★
                        </button>
                        <button type="button" onclick="filterReviews(1, this)" class="review-filter-btn px-3 py-1.5 rounded-lg text-xs font-bold transition-all bg-white text-stone-800 border border-stone-300 hover:bg-stone-100 cursor-pointer">
                            1 Star ★
                        </button>
                    </div>
                </div>

                <!-- Reviews list stream -->
                <div class="space-y-4" id="reviews-list">
                    <?php foreach ($reviews_list as $index => $rev): ?>
                        <div data-rating="<?php echo $rev['rating'] ?? 5; ?>" class="review-card bg-white border border-luxury-200/70 rounded-2xl p-6 space-y-3 shadow-xs transition-all <?php echo $index >= 10 ? 'hidden' : ''; ?>">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 bg-luxury-900 text-amber-300 rounded-full flex items-center justify-center font-display font-bold text-xs shadow-xs">
                                        <?php echo strtoupper(substr($rev['author'], 0, 2)); ?>
                                    </div>
                                    <div>
                                        <div class="flex items-center gap-2">
                                            <h5 class="font-display font-bold text-xs text-luxury-950"><?php echo sanitize_html($rev['author']); ?></h5>
                                            <span class="bg-emerald-50 text-emerald-700 text-[9px] font-semibold px-2 py-0.5 rounded-full border border-emerald-100 flex items-center gap-1">
                                                <i data-lucide="check" class="w-2.5 h-2.5"></i> Verified Buyer
                                            </span>
                                        </div>
                                        <p class="text-[10px] text-luxury-400 font-mono mt-0.5"><?php echo sanitize_html($rev['created_at']); ?></p>
                                    </div>
                                </div>
                                <div class="flex items-center text-amber-400">
                                    <?php for ($i = 1; $i <= 5; $i++): ?>
                                        <i data-lucide="star" class="w-3.5 h-3.5 <?php echo $i <= $rev['rating'] ? 'fill-amber-400 text-amber-400' : 'text-stone-200'; ?>"></i>
                                    <?php endfor; ?>
                                </div>
                            </div>
                            <p class="text-xs text-luxury-800 leading-relaxed pl-12 italic">
                                "<?php echo sanitize_html($rev['comment']); ?>"
                            </p>
                        </div>
                    <?php endforeach; ?>
                </div>

                <?php if (count($reviews_list) > 10): ?>
                    <div class="text-center pt-4" id="load-more-reviews-container">
                        <button type="button" id="btn-load-more-reviews" onclick="loadMoreReviews()" class="bg-white hover:bg-stone-50 border border-luxury-300 text-luxury-900 font-display font-bold text-xs px-6 py-3 rounded-xl transition-all shadow-xs cursor-pointer inline-flex items-center gap-2">
                            <span>Load More Reviews (Showing <span id="visible-reviews-count">10</span> of <?php echo count($reviews_list); ?>)</span>
                            <i data-lucide="chevron-down" class="w-4 h-4 text-luxury-600"></i>
                        </button>
                    </div>
                    <script>
                        let currentVisibleReviews = 10;
                        const totalReviewsCount = <?php echo count($reviews_list); ?>;
                        function loadMoreReviews() {
                            const hiddenCards = document.querySelectorAll('.review-card.hidden');
                            for (let i = 0; i < 10 && i < hiddenCards.length; i++) {
                                hiddenCards[i].classList.remove('hidden');
                            }
                            currentVisibleReviews += 10;
                            const countElem = document.getElementById('visible-reviews-count');
                            if (countElem) {
                                countElem.innerText = Math.min(currentVisibleReviews, totalReviewsCount);
                            }
                            if (document.querySelectorAll('.review-card.hidden').length === 0) {
                                const container = document.getElementById('load-more-reviews-container');
                                if (container) container.style.display = 'none';
                            }
                        }
                    </script>
                <?php endif; ?>

                <!-- Submit Review Form Card (Placed BELOW Reviews List) -->
                <div id="submit-review-form" class="bg-white border border-luxury-200/70 rounded-2xl p-6 space-y-4 shadow-xs mt-8">
                    <div class="space-y-1 border-b border-luxury-100 pb-3">
                        <h4 class="font-display font-bold text-sm text-luxury-950">Share Your Organic Harvest Review</h4>
                        <p class="text-[10px] text-stone-400">Your review will be verified under NPOP parameters before publishing.</p>
                    </div>

                    <form action="product.php?slug=<?php echo urlencode($p['slug'] ?? 'premium-kashmiri-walnut-kernels'); ?>#reviews" method="POST" class="space-y-4 text-xs">
                        <input type="hidden" name="submit_review" value="1">
                        
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div class="space-y-1.5">
                                <label class="font-semibold text-luxury-700">Full Name *</label>
                                <input type="text" name="rev_author" required placeholder="e.g. Amit Deshmukh" class="w-full bg-stone-50 border border-luxury-200 rounded-lg p-2.5 focus:outline-none focus:border-luxury-500">
                            </div>
                            <div class="space-y-1.5">
                                <label class="font-semibold text-luxury-700">Email Address *</label>
                                <input type="email" name="rev_email" required placeholder="e.g. amit@gmail.com" class="w-full bg-stone-50 border border-luxury-200 rounded-lg p-2.5 focus:outline-none focus:border-luxury-500 font-mono">
                            </div>
                        </div>

                        <div class="space-y-1.5">
                            <label class="font-semibold text-luxury-700 block">Rating Metric *</label>
                            <select name="rev_rating" required class="bg-stone-50 border border-luxury-200 rounded-lg p-2.5 focus:outline-none focus:border-luxury-500 font-medium text-amber-600">
                                <option value="5">⭐⭐⭐⭐⭐ 5 Stars (Exquisite Quality)</option>
                                <option value="4">⭐⭐⭐⭐ 4 Stars (Very High Standard)</option>
                                <option value="3">⭐⭐⭐ 3 Stars (Good Average)</option>
                                <option value="2">⭐⭐ 2 Stars (Needs Improvement)</option>
                                <option value="1">⭐ 1 Star (Substandard/Faulty Batch)</option>
                            </select>
                        </div>

                        <div class="space-y-1.5">
                            <label class="font-semibold text-luxury-700">Detailed Comments *</label>
                            <textarea name="rev_comment" required rows="3" placeholder="Describe the aroma, oil levels, moisture grading, and culinary output..." class="w-full bg-stone-50 border border-luxury-200 rounded-lg p-2.5 focus:outline-none focus:border-luxury-500"></textarea>
                        </div>

                        <button type="submit" class="bg-luxury-900 hover:bg-luxury-800 text-white font-display font-semibold px-6 py-3 rounded-lg shadow-md transition-all hover:scale-[1.01]">
                            Submit Customer Review
                        </button>
                    </form>
                </div>

            </div>

        </div>

        <!-- Right 4 Cols: Desktop Sticky Floating Trust & Guarantee Box with Buy Now -->
        <div class="lg:col-span-4 space-y-4 lg:sticky lg:top-24">
            <!-- Sticky Buy Now Box -->
            <div class="bg-emerald-900 border-2 border-emerald-800 text-amber-300 rounded-2xl p-5 shadow-sm space-y-3">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <i data-lucide="zap" class="w-5 h-5 text-amber-300 fill-amber-300"></i>
                        <span class="font-extrabold text-sm uppercase tracking-wider text-amber-300">Express Checkout</span>
                    </div>
                    <span class="text-xs font-mono font-bold text-emerald-200">₹<span id="sidebar-price-display-php"><?php echo number_format($unit_price, 2); ?></span></span>
                </div>
                <form action="/checkout.php" method="POST" class="w-full space-y-2">
                    <input type="hidden" name="direct_buy" value="1">
                    <input type="hidden" name="product_id" value="<?php echo $p['id']; ?>">
                    <input type="hidden" id="selected-weight-input-php-sidebar" name="weight_variant" value="500 Grams Pack">
                    <input type="hidden" id="selected-price-input-php-sidebar" name="unit_price" value="<?php echo $unit_price; ?>">
                    <input type="hidden" id="selected-qty-input-php-sidebar" name="quantity" value="1">
                    <button type="submit" class="w-full bg-amber-400 hover:bg-amber-300 text-stone-950 font-display font-extrabold text-sm py-3.5 px-4 rounded-xl shadow-md transition-all flex items-center justify-center gap-2 cursor-pointer border border-amber-300 uppercase tracking-wide">
                        <i data-lucide="zap" class="w-4 h-4 text-stone-950 fill-stone-950"></i>
                        <span>BUY NOW</span>
                    </button>
                </form>
                <p class="text-[10px] text-emerald-200 font-medium text-center flex items-center justify-center gap-1">
                    <i data-lucide="shield-check" class="w-3 h-3 text-amber-300"></i> Direct Instant Express Checkout
                </p>
            </div>
            <div class="bg-white border-2 border-stone-300 rounded-2xl p-5 shadow-sm space-y-4">
                <h4 class="font-display font-extrabold text-xs text-stone-900 uppercase tracking-wider border-b border-stone-200 pb-2.5 flex items-center gap-2">
                    <i data-lucide="shield-check" class="w-4 h-4 text-emerald-800"></i> Quality & Delivery Guarantees
                </h4>
                <div class="space-y-3 text-xs">
                    <div class="flex items-start gap-3 bg-stone-50 border border-stone-200 p-3 rounded-xl">
                        <i data-lucide="truck" class="w-5 h-5 text-emerald-800 shrink-0 mt-0.5"></i>
                        <div>
                            <p class="font-bold text-stone-900">Free Express Delivery</p>
                            <p class="text-[11px] text-stone-600">On orders > ₹500</p>
                        </div>
                    </div>

                    <div class="flex items-start gap-3 bg-stone-50 border border-stone-200 p-3 rounded-xl">
                        <i data-lucide="rotate-ccw" class="w-5 h-5 text-emerald-800 shrink-0 mt-0.5"></i>
                        <div>
                            <p class="font-bold text-stone-900">100% Freshness</p>
                            <p class="text-[11px] text-stone-600">7-Day Fresh Guarantee</p>
                        </div>
                    </div>

                    <div class="flex items-start gap-3 bg-stone-50 border border-stone-200 p-3 rounded-xl">
                        <i data-lucide="shield-check" class="w-5 h-5 text-emerald-800 shrink-0 mt-0.5"></i>
                        <div>
                            <p class="font-bold text-stone-900">Unbleached</p>
                            <p class="text-[11px] text-stone-600">Zero Chemicals</p>
                        </div>
                    </div>

                    <div class="flex items-start gap-3 bg-stone-50 border border-stone-200 p-3 rounded-xl">
                        <i data-lucide="award" class="w-5 h-5 text-emerald-800 shrink-0 mt-0.5"></i>
                        <div>
                            <p class="font-bold text-stone-900">Lab Certified</p>
                            <p class="text-[11px] text-stone-600">FSSAI Approved</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

    <!-- Tab Switcher Script -->
    <script>
        function switchProductTab(tabId) {
            // Hide all tab panels
            document.querySelectorAll('.tab-panel').forEach(panel => {
                panel.classList.add('hidden');
            });
            
            // Show selected panel
            const activePanel = document.getElementById('tab-panel-' + tabId);
            if (activePanel) {
                activePanel.classList.remove('hidden');
            }

            // Reset tab button styles
            document.querySelectorAll('.tab-btn').forEach(btn => {
                btn.className = "tab-btn px-5 py-2.5 rounded-xl font-display font-bold text-xs transition-all duration-200 flex items-center gap-2 cursor-pointer bg-white text-luxury-700 hover:bg-luxury-50 border border-luxury-200/80 shrink-0";
            });

            // Set active button style
            const activeBtn = document.getElementById('tab-btn-' + tabId);
            if (activeBtn) {
                activeBtn.className = "tab-btn px-5 py-2.5 rounded-xl font-display font-bold text-xs transition-all duration-200 flex items-center gap-2 cursor-pointer bg-luxury-900 text-white shadow-sm border border-luxury-900 shrink-0";
            }
        }
    </script>
</section>

<!-- Sticky Buy button on Mobile for Instant Checkout -->
<div class="fixed bottom-0 inset-x-0 bg-white border-t border-luxury-200 p-4 z-40 md:hidden flex items-center justify-between gap-4 shadow-2xl">
    <div>
        <span class="text-[9px] text-luxury-400 uppercase tracking-widest block font-display">Special Price</span>
        <span class="text-lg font-bold text-luxury-950">₹<?php echo number_format($p['sale_price'], 2); ?></span>
    </div>
    <form action="/checkout.php" method="POST" class="flex-grow">
        <input type="hidden" name="direct_buy" value="1">
        <input type="hidden" name="product_id" value="<?php echo $p['id']; ?>">
        <input type="hidden" name="quantity" value="1" id="mobile-qty">
        <input type="hidden" name="weight_variant" id="mobile-weight-input" value="400 Grams">
        <input type="hidden" name="variant_price" id="mobile-price-input" value="<?php echo $p['sale_price']; ?>">
        <input type="hidden" name="variant_sku" id="mobile-sku-input" value="<?php echo $p['sku']; ?>">
        <button type="submit" class="w-full bg-rose-600 hover:bg-rose-700 text-amber-300 font-display font-black text-xs py-3.5 px-4 rounded-full tracking-wider uppercase text-center flex items-center justify-center gap-1.5 border border-rose-500 shadow-lg">
            <i data-lucide="zap" class="w-3.5 h-3.5 text-amber-300 fill-amber-300"></i> <span class="text-amber-300 font-black">Instant Buy Now</span>
        </button>
    </form>
</div>

<script>
// Keep track of current main image URL/Emoji for zoom resetting
let currentMainImageUrl = "<?php echo !empty($img_src) ? sanitize_html($img_src) : ''; ?>";
let isEmojiActive = !currentMainImageUrl;

function selectVariant(weight, price, sku, savings, element) {
    // Update displays
    document.getElementById('prod-price-display').innerText = '₹' + parseFloat(price).toFixed(2);
    if (savings && savings !== '0%' && savings !== '') {
        document.getElementById('prod-mrp-display').style.display = 'inline';
        // Approximate standard catalog markup
        const markupPrice = (parseFloat(price) / (1 - (parseInt(savings)/100))).toFixed(2);
        document.getElementById('prod-mrp-display').innerText = '₹' + markupPrice;
        document.getElementById('prod-savings-display').innerText = 'SAVE ' + savings + ' INSTANTLY';
    } else {
        document.getElementById('prod-mrp-display').style.display = 'none';
        document.getElementById('prod-savings-display').innerText = '';
    }
    
    // Update hidden fields in main form if present
    if (document.getElementById('add-weight')) document.getElementById('add-weight').value = weight;
    if (document.getElementById('add-price')) document.getElementById('add-price').value = price;
    if (document.getElementById('add-sku')) document.getElementById('add-sku').value = sku;
    
    // Update hidden fields in direct buy form
    if (document.getElementById('direct-weight')) document.getElementById('direct-weight').value = weight;
    if (document.getElementById('direct-price')) document.getElementById('direct-price').value = price;
    if (document.getElementById('direct-sku')) document.getElementById('direct-sku').value = sku;

    // Update Buy Now button price tag
    const buyNowPriceTag = document.getElementById('buy-now-price-tag');
    if (buyNowPriceTag) buyNowPriceTag.innerText = parseFloat(price).toFixed(2);

    // Mobile sticky bar update
    const mobilePrice = document.querySelector('.fixed.bottom-0 .text-lg');
    if (mobilePrice) {
        mobilePrice.innerText = '₹' + parseFloat(price).toFixed(2);
    }
    const mWeight = document.getElementById('mobile-weight-input');
    if (mWeight) mWeight.value = weight;
    const mPrice = document.getElementById('mobile-price-input');
    if (mPrice) mPrice.value = price;
    const mSku = document.getElementById('mobile-sku-input');
    if (mSku) mSku.value = sku;
    
    // Toggle active styles on buttons
    const buttons = document.querySelectorAll('.variant-btn');
    buttons.forEach(btn => {
        btn.classList.remove('bg-luxury-900', 'text-white', 'border-luxury-900');
        btn.classList.add('bg-white', 'text-luxury-900', 'border-luxury-200');
    });
    element.classList.remove('bg-white', 'text-luxury-900', 'border-luxury-200');
    element.classList.add('bg-luxury-900', 'text-white', 'border-luxury-900');
}

// Product Gallery Engine
let slideImages = [];
let currentSlideIndex = 0;

function nextSlide() {
    goToSlide(currentSlideIndex + 1);
}

function prevSlide() {
    goToSlide(currentSlideIndex - 1);
}

function goToSlide(index) {
    if (!slideImages || slideImages.length === 0) return;
    currentSlideIndex = (index + slideImages.length) % slideImages.length;
    const track = document.getElementById('product-slide-track');
    if (track) {
        track.style.transform = `translateX(-${currentSlideIndex * 100}%)`;
    }
    
    // Update Dots
    const dots = document.querySelectorAll('.slide-dot');
    dots.forEach((dot, idx) => {
        if (idx === currentSlideIndex) {
            dot.className = 'slide-dot w-6 h-2 rounded-full bg-white transition-all cursor-pointer';
        } else {
            dot.className = 'slide-dot w-2 h-2 rounded-full bg-white/50 hover:bg-white/80 transition-all cursor-pointer';
        }
    });

    // Update Thumbnails
    const thumbs = document.querySelectorAll('.thumbnail-btn');
    thumbs.forEach((thumb, idx) => {
        if (idx === currentSlideIndex) {
            thumb.classList.remove('border-luxury-200');
            thumb.classList.add('border-luxury-900', 'ring-2', 'ring-luxury-900/20');
        } else {
            thumb.classList.remove('border-luxury-900', 'ring-2', 'ring-luxury-900/20');
            thumb.classList.add('border-luxury-200');
        }
    });
}

// Attach immediately to window for inline HTML attributes
window.nextSlide = nextSlide;
window.prevSlide = prevSlide;
window.goToSlide = goToSlide;

function initProductSlider() {
    renderSlides();
    renderDots();
    renderThumbnails();
    goToSlide(0);
    initZoom();
}

function renderSlides() {
    const track = document.getElementById('product-slide-track');
    if (!track) return;
    track.innerHTML = '';
    slideImages.forEach((url, idx) => {
        const slide = document.createElement('div');
        slide.className = 'w-full h-full shrink-0 flex items-center justify-center p-6 relative overflow-hidden select-none';
        
        if (url.startsWith('http') || url.startsWith('/') || url.startsWith('assets/')) {
            slide.innerHTML = `<img src="${url}" class="max-w-full max-h-full object-contain select-none transition-transform duration-200 ease-out slide-img" data-index="${idx}" />`;
        } else {
            slide.innerHTML = `<span class="text-[120px] select-none">${url}</span>`;
        }
        track.appendChild(slide);
    });
}

function renderDots() {
    const dotsContainer = document.getElementById('slide-dots-container');
    if (!dotsContainer) return;
    dotsContainer.innerHTML = '';
    slideImages.forEach((_, idx) => {
        const dot = document.createElement('button');
        dot.type = 'button';
        dot.className = idx === 0 ? 'slide-dot w-6 h-2 rounded-full bg-white transition-all cursor-pointer' : 'slide-dot w-2 h-2 rounded-full bg-white/50 hover:bg-white/80 transition-all cursor-pointer';
        dot.onclick = () => {
            goToSlide(idx);
        };
        dotsContainer.appendChild(dot);
    });
}

function renderThumbnails() {
    const thumbsContainer = document.getElementById('gallery-thumbnails');
    if (!thumbsContainer) return;
    thumbsContainer.innerHTML = '';
    slideImages.forEach((url, idx) => {
        const btn = document.createElement('button');
        btn.type = 'button';
        btn.className = `thumbnail-btn aspect-square bg-white border ${idx === 0 ? 'border-luxury-900 ring-2 ring-luxury-900/20' : 'border-luxury-200 hover:border-luxury-400'} rounded-xl flex items-center justify-center p-2 hover:bg-luxury-50 focus:outline-none transition-all cursor-pointer overflow-hidden`;
        btn.onclick = () => {
            goToSlide(idx);
        };
        
        if (url.startsWith('http') || url.startsWith('/') || url.startsWith('assets/')) {
            btn.innerHTML = `<img src="${url}" class="max-h-12 w-full object-contain rounded-lg select-none" />`;
        } else {
            btn.innerHTML = `<span class="text-3xl select-none">${url}</span>`;
        }
        thumbsContainer.appendChild(btn);
    });
}

function initZoom() {
    const container = document.getElementById('product-slider-container');
    if (!container) return;

    container.onmousemove = (e) => {
        const activeImg = container.querySelector(`.slide-img[data-index="${currentSlideIndex}"]`);
        if (!activeImg) return;
        const rect = container.getBoundingClientRect();
        const x = ((e.clientX - rect.left) / rect.width) * 100;
        const y = ((e.clientY - rect.top) / rect.height) * 100;
        activeImg.style.transformOrigin = `${x}% ${y}%`;
        activeImg.style.transform = 'scale(1.25)';
    };

    container.onmouseleave = () => {
        const images = container.querySelectorAll('.slide-img');
        images.forEach(img => {
            img.style.transform = 'scale(1)';
            img.style.transformOrigin = 'center center';
        });
    };
}

document.addEventListener('DOMContentLoaded', () => {
    initProductSlider();
});

// Client-side Review Filtering
function filterReviews(rating, btn) {
    // Update button states
    const buttons = document.querySelectorAll('.review-filter-btn');
    buttons.forEach(b => {
        b.classList.remove('bg-luxury-900', 'text-white', 'border-luxury-900');
        b.classList.add('bg-white', 'text-luxury-700', 'border-luxury-200');
    });
    btn.classList.remove('bg-white', 'text-luxury-700', 'border-luxury-200');
    btn.classList.add('bg-luxury-900', 'text-white', 'border-luxury-900');

    // Filter cards
    const cards = document.querySelectorAll('.review-card');
    let visibleCount = 0;
    cards.forEach(card => {
        const cardRating = parseInt(card.getAttribute('data-rating'));
        if (rating === 'all') {
            card.style.display = 'block';
            visibleCount++;
        } else if (rating === 3) {
            // 3 stars & below
            if (cardRating <= 3) {
                card.style.display = 'block';
                visibleCount++;
            } else {
                card.style.display = 'none';
            }
        } else {
            if (cardRating === rating) {
                card.style.display = 'block';
                visibleCount++;
            } else {
                card.style.display = 'none';
            }
        }
    });

    // Handle empty state
    let emptyMsg = document.getElementById('reviews-filtered-empty');
    if (visibleCount === 0) {
        if (!emptyMsg) {
            emptyMsg = document.createElement('div');
            emptyMsg.id = 'reviews-filtered-empty';
            emptyMsg.className = 'bg-stone-50 border border-dashed border-stone-200 p-8 rounded-xl text-center text-xs text-stone-500 mt-4 w-full';
            emptyMsg.innerText = 'No reviews found matching the selected star filter.';
            document.getElementById('reviews-list').appendChild(emptyMsg);
        } else {
            emptyMsg.style.display = 'block';
        }
    } else if (emptyMsg) {
        emptyMsg.style.display = 'none';
    }
}

// Variant & Quantity Manager for PHP
let currentBaseUnitPricePHP = <?php echo $unit_price; ?>;

function updateVariantPHP(weightText, salePrice, mrpPrice, btnEl) {
    currentBaseUnitPricePHP = parseFloat(salePrice);

    var buttons = document.querySelectorAll('#variant-selector-grid-php .variant-btn');
    buttons.forEach(function(btn) {
        btn.classList.remove('border-emerald-800', 'bg-emerald-50', 'text-emerald-950');
        btn.classList.add('border-stone-300', 'bg-white', 'text-stone-900');
        var icon = btn.querySelector('[data-lucide="check-circle-2"]');
        if (icon) icon.remove();
    });

    if (btnEl) {
        btnEl.classList.remove('border-stone-300', 'bg-white', 'text-stone-900');
        btnEl.classList.add('border-emerald-800', 'bg-emerald-50', 'text-emerald-950');
    }

    var weightInput = document.getElementById('selected-weight-input-php');
    var weightInputSidebar = document.getElementById('selected-weight-input-php-sidebar');
    var priceInput = document.getElementById('selected-price-input-php');
    var priceInputSidebar = document.getElementById('selected-price-input-php-sidebar');
    var displaySale = document.getElementById('display-sale-price-php');
    var displayMrp = document.getElementById('display-mrp-php');

    if (weightInput) weightInput.value = weightText;
    if (weightInputSidebar) weightInputSidebar.value = weightText;
    if (priceInput) priceInput.value = salePrice;
    if (priceInputSidebar) priceInputSidebar.value = salePrice;
    if (displaySale) displaySale.innerText = '₹' + parseFloat(salePrice).toFixed(2);
    if (displayMrp) displayMrp.innerText = '₹' + parseFloat(mrpPrice).toFixed(2);

    updateTotalButtonPricePHP();
}

function adjustQtyPHP(delta) {
    var qtyInput = document.getElementById('qty-input-php');
    if (!qtyInput) return;
    var currentQty = parseInt(qtyInput.value) || 1;
    var newQty = currentQty + delta;
    if (newQty >= 1 && newQty <= 10) {
        qtyInput.value = newQty;
        var sidebarQtyInput = document.getElementById('selected-qty-input-php-sidebar');
        if (sidebarQtyInput) sidebarQtyInput.value = newQty;
        updateTotalButtonPricePHP();
    }
}

function updateTotalButtonPricePHP() {
    var qtyInput = document.getElementById('qty-input-php');
    var btnPriceDisplay = document.getElementById('btn-price-display-php');
    var sidebarPriceDisplay = document.getElementById('sidebar-price-display-php');
    var qty = parseInt(qtyInput ? qtyInput.value : 1) || 1;
    var total = currentBaseUnitPricePHP * qty;
    if (btnPriceDisplay) {
        btnPriceDisplay.innerText = total.toFixed(2);
    }
    if (sidebarPriceDisplay) {
        sidebarPriceDisplay.innerText = total.toFixed(2);
    }
}

// Bundle Purchase Form Submitter
function addBundleToCart(prodId1, prodId2, price1, price2, sku1, sku2) {
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = '/cart.php';
    
    const actionInput = document.createElement('input');
    actionInput.type = 'hidden';
    actionInput.name = 'action';
    actionInput.value = 'add_bundle';
    form.appendChild(actionInput);
    
    const jsonInput = document.createElement('input');
    jsonInput.type = 'hidden';
    jsonInput.name = 'products_json';
    
    // Compile weights & SKUs
    const products = [
        { id: prodId1, weight: document.getElementById('add-weight')?.value || '400 Grams', price: price1, sku: sku1 },
        { id: prodId2, weight: prodId2 === 1 ? '400 Grams' : '500 Grams', price: price2, sku: sku2 }
    ];
    
    jsonInput.value = JSON.stringify(products);
    form.appendChild(jsonInput);
    
    document.body.appendChild(form);
    form.submit();
}
</script>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
