<?php
/**
 * Deenz Organics - Luxury Shop / Catalog Page
 */
$page_title = "Shop Organic Kashmiri Harvest";
$page_description = "Browse our natural, fresh, single-origin walnuts and sun-dried garlic cloves.";
require_once __DIR__ . '/includes/header.php';

// Fetch categories and filter query
$category_filter = $_GET['category'] ?? '';
$search_query = $_GET['search'] ?? '';
$sort_by = $_GET['sort'] ?? '';

// Mock catalog list with full structural fields
$all_products = [
    [
        'id' => 1,
        'category_id' => 1,
        'category_slug' => 'nuts-seeds',
        'category_name' => 'Nuts & Seeds',
        'name' => 'Kashmiri Organic Walnuts / Akhrot Giri (500gms)',
        'slug' => 'premium-kashmiri-walnut-kernels',
        'sku' => 'DZ-WLN-001',
        'price' => 775.00,
        'sale_price' => 750.00,
        'main_image' => '/assets/images/kashmiri_walnuts_main.webp',
        'short_description' => 'Packed with essential nutrients like Omega-3 fatty acids, plant protein, dietary fiber, antioxidants, manganese, and vitamin E.',
        'stock' => 120,
        'rating' => 5
    ],
    [
        'id' => 2,
        'category_id' => 2,
        'category_slug' => 'fresh-vegetables',
        'category_name' => 'Fresh Vegetables',
        'name' => 'Kashmiri Mountain Garlic / Lahsun (500gms)',
        'slug' => 'premium-kashmiri-garlic-cloves',
        'sku' => 'DZ-GRL-002',
        'price' => 999.00,
        'sale_price' => 850.00,
        'main_image' => '/assets/images/kashmiri_garlic_main.webp',
        'short_description' => 'Packed with essential nutrients like manganese, vitamin B6, vitamin C, selenium, calcium, and vitamin B1.',
        'stock' => 200,
        'rating' => 5
    ]
];

$database_active = false;
$filtered_products = [];

// Attempt to load from Database using secure prepared statements if connected
try {
    if (isset($pdo)) {
        $sql = "SELECT p.*, c.slug AS category_slug, c.name AS category_name 
                FROM products p 
                LEFT JOIN categories c ON p.category_id = c.id 
                WHERE p.status = 'published'";
        
        $params = [];
        
        if (!empty($category_filter)) {
            $sql .= " AND c.slug = :category_slug";
            $params['category_slug'] = $category_filter;
        }
        
        if (!empty($search_query)) {
            $sql .= " AND (p.name LIKE :search OR p.short_description LIKE :search_desc)";
            $params['search'] = '%' . $search_query . '%';
            $params['search_desc'] = '%' . $search_query . '%';
        }
        
        if ($sort_by == 'price_low_high') {
            $sql .= " ORDER BY COALESCE(p.sale_price, p.price) ASC";
        } elseif ($sort_by == 'price_high_low') {
            $sql .= " ORDER BY COALESCE(p.sale_price, p.price) DESC";
        } else {
            $sql .= " ORDER BY p.created_at DESC";
        }
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        $filtered_products = $stmt->fetchAll();
        if (!empty($filtered_products)) {
            $database_active = true;
        }
    }
} catch (\Exception $e) {
    // Fail-safe fallback to high-quality mock filter simulation for local preview robustness
}

if (!$database_active) {
    // Simulate database filtering in mock data
    foreach ($all_products as $p) {
        if (!empty($category_filter) && $p['category_slug'] !== $category_filter) {
            continue;
        }
        if (!empty($search_query) && stripos($p['name'], $search_query) === false && stripos($p['short_description'], $search_query) === false) {
            continue;
        }
        $filtered_products[] = $p;
    }
    
    // Sort mock list
    if ($sort_by == 'price_low_high') {
        usort($filtered_products, fn($a, $b) => ($a['sale_price'] ?? $a['price']) <=> ($b['sale_price'] ?? $b['price']));
    } elseif ($sort_by == 'price_high_low') {
        usort($filtered_products, fn($a, $b) => ($b['sale_price'] ?? $b['price']) <=> ($a['sale_price'] ?? $a['price']));
    }
}
?>

<!-- Breadcrumbs Schema & UI -->
<div class="bg-luxury-100/50 py-4 border-b border-luxury-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-xs text-luxury-500 flex items-center gap-2">
        <a href="index.php" class="hover:text-luxury-900 transition-colors">Home</a>
        <i data-lucide="chevron-right" class="w-3 h-3"></i>
        <span class="text-luxury-900 font-medium">Shop Catalog</span>
        <?php if (!empty($category_filter)): ?>
            <i data-lucide="chevron-right" class="w-3 h-3"></i>
            <span class="text-luxury-600 capitalize"><?php echo str_replace('-', ' ', sanitize_html($category_filter)); ?></span>
        <?php endif; ?>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
    <div class="flex flex-col lg:flex-row gap-12">
        
        <!-- Sidebar Filters -->
        <aside class="w-full lg:w-64 shrink-0 space-y-8">
            <!-- Search Widget -->
            <div class="bg-white border border-luxury-200/60 rounded-xl p-6 space-y-4">
                <h3 class="font-display font-bold text-sm text-luxury-950 uppercase tracking-wider">Search Catalog</h3>
                <form action="shop.php" method="GET" class="relative">
                    <?php if (!empty($category_filter)): ?>
                        <input type="hidden" name="category" value="<?php echo sanitize_html($category_filter); ?>">
                    <?php endif; ?>
                    <input type="text" name="search" value="<?php echo sanitize_html($search_query); ?>" placeholder="Type keywords..." class="w-full bg-luxury-50 border border-luxury-200 focus:border-luxury-500 rounded-md py-2.5 px-4 text-xs text-luxury-950 focus:outline-none">
                    <button type="submit" class="absolute right-3 top-1/2 -translate-y-1/2 text-luxury-400 hover:text-luxury-900">
                        <i data-lucide="search" class="w-4 h-4"></i>
                    </button>
                </form>
            </div>

            <!-- Fast Information Widget -->
            <div class="bg-luxury-900 text-luxury-200 rounded-xl p-6 space-y-4">
                <i data-lucide="truck" class="w-6 h-6 text-luxury-300"></i>
                <h4 class="font-display font-bold text-sm text-white">Direct J&K Express Air-Cargo Cargo</h4>
                <p class="text-[11px] leading-relaxed text-luxury-300">
                    Your organic items are packaged securely in airtight vacuum zip bags immediately upon custom grading selection to protect vital nutrition!
                </p>
            </div>
        </aside>

        <!-- Product Grid Area -->
        <main class="flex-grow space-y-8">
            
            <!-- Controls & Count Bar -->
            <div class="bg-white border border-luxury-200/60 rounded-xl p-4 flex flex-col sm:flex-row items-center justify-between gap-4">
                <p class="text-xs text-luxury-500 font-medium font-display">
                    Displaying <span class="text-luxury-950 font-bold"><?php echo count($filtered_products); ?></span> Premium Organic Commodities
                </p>
                
                <!-- Sort Selector -->
                <div class="flex items-center gap-2">
                    <span class="text-xs text-luxury-400">Sort By:</span>
                    <select onchange="window.location.href = 'shop.php?category=<?php echo urlencode($category_filter); ?>&search=<?php echo urlencode($search_query); ?>&sort=' + this.value" class="bg-luxury-50 border border-luxury-200 rounded-md py-1.5 px-3 text-xs focus:outline-none text-luxury-950 font-medium">
                        <option value="">New Harvests</option>
                        <option value="price_low_high" <?php echo $sort_by == 'price_low_high' ? 'selected' : ''; ?>>Price: Low to High</option>
                        <option value="price_high_low" <?php echo $sort_by == 'price_high_low' ? 'selected' : ''; ?>>Price: High to Low</option>
                    </select>
                </div>
            </div>

            <!-- Grid -->
            <?php if (empty($filtered_products)): ?>
                <div class="bg-white border border-luxury-100 rounded-xl p-16 text-center space-y-4">
                    <span class="text-5xl">🌾</span>
                    <h3 class="font-display font-bold text-lg text-luxury-950">No Harvest Items Found</h3>
                    <p class="text-xs text-luxury-500 max-w-sm mx-auto">
                        We couldn't find items matching your search keywords or filter terms. Try adjusting your sidebar selections.
                    </p>
                    <a href="shop.php" class="inline-flex items-center gap-2 text-xs font-bold text-luxury-900 border-b border-luxury-900 pb-0.5 mt-4">
                        Reset Filters
                    </a>
                </div>
            <?php else: ?>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-2 xl:grid-cols-3 gap-6">
                    <?php foreach ($filtered_products as $p): ?>
                        <?php
                            $is_discounted = !empty($p['sale_price']) && $p['sale_price'] < $p['price'];
                            $discount_percentage = 0;
                            if ($is_discounted) {
                                $discount_percentage = round((($p['price'] - $p['sale_price']) / $p['price']) * 100);
                            }
                            $img_src = clean_image_url($p['main_image'] ?? '', $p['slug'] ?? $p['name'] ?? '');
                        ?>
                        <div class="bg-white rounded-2xl shadow-xs border border-stone-200/80 overflow-hidden flex flex-col hover:shadow-md transition-all duration-300 group">
                            <!-- Small Shopify-style Boxed Image Container -->
                            <div class="relative aspect-square w-full bg-stone-50/80 border-b border-stone-200/80 p-5 flex items-center justify-center overflow-hidden">
                                <img src="<?php echo sanitize_html($img_src); ?>" alt="<?php echo sanitize_html($p['name']); ?>" class="max-h-full max-w-full object-contain p-2 select-none group-hover:scale-105 transition-transform duration-300 drop-shadow-sm" />

                                <?php if ($is_discounted): ?>
                                    <span class="absolute top-3 left-3 bg-rose-600 text-white font-mono text-[10px] font-extrabold px-2.5 py-1 rounded-md shadow-xs">
                                        SAVE <?php echo $discount_percentage; ?>%
                                    </span>
                                <?php endif; ?>

                                <span class="absolute top-3 right-3 bg-emerald-950/80 backdrop-blur-xs text-amber-300 font-mono text-[9px] font-bold px-2 py-1 rounded-md border border-emerald-800">
                                    Single-Origin J&K
                                </span>
                            </div>

                            <!-- Details -->
                            <div class="p-5 flex-grow flex flex-col justify-between space-y-4">
                                <div class="space-y-2">
                                    <div class="flex items-center justify-between text-[10px] font-mono text-stone-500 uppercase tracking-wider">
                                        <span>SKU: <?php echo sanitize_html($p['sku']); ?></span>
                                        <span class="bg-emerald-50 text-emerald-900 px-2 py-0.5 rounded font-bold border border-emerald-200"><?php echo sanitize_html($p['category_name'] ?? 'Deenz Organics'); ?></span>
                                    </div>
                                    <h3 class="font-display font-bold text-base text-stone-900 group-hover:text-emerald-900 transition-colors leading-snug">
                                        <a href="/product/<?php echo urlencode($p['slug'] ?? 'premium-kashmiri-walnut-kernels'); ?>"><?php echo sanitize_html($p['name']); ?></a>
                                    </h3>
                                    <p class="text-xs text-stone-600 line-clamp-2 leading-relaxed">
                                        <?php echo sanitize_html($p['short_description']); ?>
                                    </p>
                                </div>

                                <!-- Action bar -->
                                <div class="flex items-center justify-between pt-4 border-t border-stone-200/80">
                                    <div>
                                        <?php if ($is_discounted): ?>
                                            <span class="text-lg font-extrabold text-stone-900 font-display">₹<?php echo number_format($p['sale_price'], 2); ?></span>
                                            <span class="text-xs text-stone-400 line-through ml-1.5 font-mono">₹<?php echo number_format($p['price'], 2); ?></span>
                                        <?php else: ?>
                                            <span class="text-lg font-extrabold text-stone-900 font-display">₹<?php echo number_format($p['price'], 2); ?></span>
                                        <?php endif; ?>
                                    </div>
                                    
                                    <a href="/product/<?php echo urlencode($p['slug'] ?? 'premium-kashmiri-walnut-kernels'); ?>" class="bg-emerald-900 hover:bg-emerald-950 text-amber-300 px-4 py-2.5 rounded-xl text-xs font-bold tracking-wide flex items-center gap-1.5 shadow-xs transition-all hover:scale-[1.02] border border-emerald-800">
                                        View Product <i data-lucide="chevron-right" class="w-4 h-4 text-amber-300"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </main>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
