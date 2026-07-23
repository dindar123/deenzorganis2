<?php
/**
 * Deenz Organics - Luxury Home Page
 */
$page_title = "Discover Premium Kashmiri Walnuts & Garlic Cloves";
$page_description = "Handpicked organic dry fruits, nuts, and sun-dried mountain garlic cloves. Sourced directly from Pahalgam and Pampore, Jammu & Kashmir.";
require_once __DIR__ . '/includes/header.php';

// Initialize products array
$featured_products = [];
$database_active = false;

// Attempt to load products from database
try {
    if (isset($pdo)) {
        $stmt = $pdo->prepare("SELECT * FROM products WHERE status = 'published' LIMIT 4");
        $stmt->execute();
        $featured_products = $stmt->fetchAll();
        if (!empty($featured_products)) {
            $database_active = true;
        }
    }
} catch (\Exception $e) {
    // Database connection details are local placeholders; fallback to mock data is safe and robust
}

// Fallback to high-quality mock data if database is not active yet (for instant file preview safety)
if (empty($featured_products)) {
    $featured_products = [
        [
            'id' => 1,
            'name' => 'Kashmiri Organic Walnuts / Akhrot Giri (500gms)',
            'slug' => 'premium-kashmiri-walnut-kernels',
            'sku' => 'DZ-WLN-001',
            'price' => 775.00,
            'sale_price' => 750.00,
            'main_image' => '/assets/images/kashmiri_walnuts_main.webp',
            'short_description' => 'Packed with essential nutrients like Omega-3 fatty acids, plant protein, dietary fiber, antioxidants, manganese, and vitamin E.',
            'stock' => 120
        ],
        [
            'id' => 2,
            'name' => 'Kashmiri Mountain Garlic / Lahsun (500gms)',
            'slug' => 'premium-kashmiri-garlic-cloves',
            'sku' => 'DZ-GRL-002',
            'price' => 999.00,
            'sale_price' => 850.00,
            'main_image' => '/assets/images/kashmiri_garlic_main.webp',
            'short_description' => 'Packed with essential nutrients like manganese, vitamin B6, vitamin C, selenium, calcium, and vitamin B1.',
            'stock' => 200
        ]
    ];
}
?>

<!-- 1. Premium Bright & Fresh Hero Section -->
<section class="relative bg-gradient-to-br from-emerald-50/50 via-stone-50 to-amber-50/40 text-stone-800 overflow-hidden py-20 sm:py-28 border-b border-stone-200/60">
    <!-- Ambient organic blurs -->
    <div class="absolute top-0 right-0 w-[450px] h-[450px] bg-emerald-100/30 rounded-full blur-3xl pointer-events-none"></div>
    <div class="absolute bottom-0 left-0 w-[450px] h-[450px] bg-amber-100/25 rounded-full blur-3xl pointer-events-none"></div>

    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-center">
            
            <!-- Left Side: Copywriting -->
            <div class="lg:col-span-7 space-y-8">
                <div class="inline-flex items-center gap-2 bg-emerald-50 border border-emerald-100/80 rounded-full py-1.5 px-3 text-xs tracking-widest uppercase text-emerald-800 font-semibold shadow-sm">
                    <span class="w-2 h-2 rounded-full bg-amber-500 animate-pulse"></span> Direct From Pahalgam & Pampore, J&K
                </div>
                
                <h1 class="font-display text-4xl sm:text-5xl lg:text-6xl font-bold tracking-tight leading-tight text-stone-900">
                    The Pure Gold of <br>
                    <span class="text-emerald-900">
                        Kashmiri Nature
                    </span>
                </h1>

                <p class="text-base sm:text-lg text-stone-600 leading-relaxed max-w-xl">
                    Experience hand-selected, mountain-grown organic gems. Sourced from traditional fields in Pahalgam and Pampore, and processed hygienically to capture authentic mountain rich minerals, crunch, and bold aromas.
                </p>

                <div class="flex flex-wrap items-center gap-4">
                    <a href="shop.php" class="bg-amber-400 hover:bg-amber-500 text-stone-950 font-display font-semibold text-sm px-8 py-4 rounded-md shadow-md hover:scale-[1.02] transition-all">
                        Explore Autumn Harvest
                    </a>
                    <a href="about.php" class="text-stone-700 hover:text-emerald-900 font-display text-sm font-medium flex items-center gap-2 px-4 py-2 hover:translate-x-1 transition-all">
                        Learn Our Farmers' Story <i data-lucide="arrow-right" class="w-4 h-4"></i>
                    </a>
                </div>

                <!-- Mini Trust Row -->
                <div class="grid grid-cols-3 gap-6 pt-8 border-t border-stone-200/80 text-xs text-stone-500">
                    <div>
                        <span class="font-bold text-stone-900 block text-sm sm:text-base">100% Natural</span>
                        Chemical-Free Growths
                    </div>
                    <div>
                        <span class="font-bold text-stone-900 block text-sm sm:text-base">Pahalgam & Pampore</span>
                        Direct Single Source
                    </div>
                    <div>
                        <span class="font-bold text-stone-900 block text-sm sm:text-base">Vacuum Packed</span>
                        Sealed-in Crunch & Oils
                    </div>
                </div>
            </div>

            <!-- Right Side: Majestic Kashmiri Nature Scene -->
            <div class="lg:col-span-5 relative flex justify-center items-center">
                <!-- Outer natural border ring decoration -->
                <div class="absolute inset-0 border border-emerald-100/50 rounded-full scale-105 pointer-events-none border-dashed animate-[spin_120s_linear_infinite]"></div>
                
                <div class="w-full max-w-[420px] aspect-square bg-gradient-to-b from-emerald-50 to-amber-50/50 rounded-3xl border border-stone-200/50 shadow-md overflow-hidden relative group">
                    <img src="assets/images/kashmiri_harvest_hero.webp" alt="Kashmiri Harvest Baskets with Premium Walnuts & Garlic" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700" referrerPolicy="no-referrer" />
                    <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-black/10 to-transparent"></div>
                    
                    <!-- Caption Badge overlay inside the scene -->
                    <div class="absolute bottom-4 left-4 right-4 bg-white/95 backdrop-blur-sm border border-stone-200 p-3 rounded-xl flex items-center justify-between text-[11px] text-stone-800">
                        <span class="font-medium">🏔️ Authentic Baskets: Walnuts & Garlic Sourced Direct</span>
                        <span class="font-mono text-emerald-800 font-bold">100% Organic</span>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>

<!-- 2. Trust Badges / Certified organic bar -->
<section class="bg-white py-8 border-b border-luxury-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-wrap items-center justify-around gap-8 text-center text-xs tracking-wider text-luxury-500 uppercase font-display">
            <div class="flex items-center gap-3">
                <i data-lucide="shield-check" class="w-5 h-5 text-luxury-400"></i>
                <span>100% Safe & Lab-Tested</span>
            </div>
            <div class="flex items-center gap-3">
                <i data-lucide="package-check" class="w-5 h-5 text-luxury-400"></i>
                <span>Fresh Harvest Protection</span>
            </div>
            <div class="flex items-center gap-3">
                <i data-lucide="heart" class="w-5 h-5 text-luxury-400"></i>
                <span>Rich in Omega-3 & Anti-Oxidants</span>
            </div>
            <div class="flex items-center gap-3">
                <i data-lucide="truck" class="w-5 h-5 text-luxury-400"></i>
                <span>Free J&K & India Express Delivery</span>
            </div>
        </div>
    </div>
</section>

<!-- 3. Featured Products Section -->
<section class="py-20 bg-stone-50/50 border-b border-stone-200/60">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center max-w-xl mx-auto mb-16 space-y-3">
            <p class="text-xs uppercase tracking-[0.2em] text-emerald-800 font-display font-bold">100% Pure & Organic</p>
            <h2 class="font-display text-3xl font-bold tracking-tight text-stone-900">Our Kashmiri Organic Products</h2>
            <div class="w-12 h-[2px] bg-emerald-600 mx-auto"></div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-8 max-w-4xl mx-auto">
            <?php foreach ($featured_products as $p): ?>
                <?php
                    $is_discounted = !empty($p['sale_price']) && $p['sale_price'] < $p['price'];
                    $discount_percentage = 0;
                    if ($is_discounted) {
                        $discount_percentage = round((($p['price'] - $p['sale_price']) / $p['price']) * 100);
                    }
                    $img_src = clean_image_url($p['main_image'] ?? '', $p['slug'] ?? $p['name'] ?? '');
                    $prod_link = '/product/' . urlencode($p['slug'] ?? 'premium-kashmiri-walnut-kernels');
                ?>
                <div class="bg-white rounded-2xl shadow-xs border border-stone-200/80 overflow-hidden flex flex-col hover:shadow-md transition-all duration-300 group">
                    <!-- Shopify-Style Small Boxed Image Container -->
                    <div class="relative aspect-square w-full bg-stone-50/80 border-b border-stone-200/80 p-6 flex items-center justify-center overflow-hidden">
                        <img src="<?php echo sanitize_html($img_src); ?>" alt="<?php echo sanitize_html($p['name']); ?>" class="max-h-full max-w-full object-contain p-2 select-none group-hover:scale-105 transition-transform duration-300 drop-shadow-sm" />

                        <!-- Discount flag -->
                        <?php if ($is_discounted): ?>
                            <span class="absolute top-4 left-4 bg-rose-600 text-white font-mono text-[10px] font-extrabold px-2.5 py-1 rounded-md shadow-xs">
                                SAVE <?php echo $discount_percentage; ?>%
                            </span>
                        <?php endif; ?>

                        <span class="absolute top-4 right-4 bg-emerald-950/80 backdrop-blur-xs text-amber-300 font-mono text-[10px] font-bold px-2.5 py-1 rounded-md border border-emerald-800">
                            Single-Origin J&K
                        </span>
                    </div>

                    <!-- Details -->
                    <div class="p-6 sm:p-7 flex-grow flex flex-col justify-between space-y-6">
                        <div class="space-y-3">
                            <div class="flex items-center justify-between text-[10px] font-mono text-stone-400 uppercase tracking-wider">
                                <span>SKU: <?php echo sanitize_html($p['sku'] ?? 'DZ-001'); ?></span>
                                <span class="bg-emerald-50 text-emerald-800 px-2 py-0.5 rounded font-semibold">100% Organic</span>
                            </div>
                            <h3 class="font-display font-bold text-xl text-stone-900 group-hover:text-emerald-800 transition-colors">
                                <a href="<?php echo $prod_link; ?>"><?php echo sanitize_html($p['name']); ?></a>
                            </h3>
                            <p class="text-xs text-stone-600 leading-relaxed line-clamp-2">
                                <?php echo sanitize_html($p['short_description']); ?>
                            </p>
                        </div>

                        <!-- Action bar -->
                        <div class="flex items-center justify-between pt-5 border-t border-stone-100">
                            <div>
                                <span class="text-xs text-stone-400 block font-mono">Price / Pack</span>
                                <?php if ($is_discounted): ?>
                                    <span class="text-xl font-bold text-stone-950 font-display">₹<?php echo number_format($p['sale_price'], 2); ?></span>
                                    <span class="text-xs text-stone-400 line-through ml-2">₹<?php echo number_format($p['price'], 2); ?></span>
                                <?php else: ?>
                                    <span class="text-xl font-bold text-stone-950 font-display">₹<?php echo number_format($p['price'], 2); ?></span>
                                <?php endif; ?>
                            </div>
                            
                            <a href="<?php echo $prod_link; ?>" class="bg-emerald-900 hover:bg-emerald-800 text-white px-5 py-3 rounded-lg text-xs font-display font-semibold tracking-wider flex items-center gap-2 shadow-sm transition-all hover:scale-[1.02]">
                                View Details <i data-lucide="arrow-right" class="w-4 h-4"></i>
                            </a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- 5. Why Choose Us Section -->
<section class="py-24 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">
            
            <!-- Content Left -->
            <div class="space-y-8">
                <div class="space-y-3">
                    <h2 class="font-display text-3xl sm:text-4xl font-bold tracking-tight text-luxury-950">Why Connoisseurs Choose Deenz Organics</h2>
                    <div class="w-12 h-[2px] bg-luxury-400"></div>
                </div>

                <p class="text-sm text-luxury-600 leading-relaxed">
                    Most organic labels are blended and processed using artificial high-heat washes. Deenz Organics is committed to the old ways. We select, inspect, and package our items right at the origin in Pahalgam and Pampore, Kashmir.
                </p>

                <!-- Bento Checklist -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 pt-4">
                    <div class="flex gap-3">
                        <div class="p-2 bg-luxury-50 rounded-lg text-luxury-600 h-fit">
                            <i data-lucide="mountain" class="w-5 h-5"></i>
                        </div>
                        <div>
                            <h4 class="font-display font-bold text-sm text-luxury-950">Pure Mountain Clay</h4>
                            <p class="text-xs text-luxury-500">Irrigated by high glacier meltwater streams rich in rare natural minerals.</p>
                        </div>
                    </div>

                    <div class="flex gap-3">
                        <div class="p-2 bg-luxury-50 rounded-lg text-luxury-600 h-fit">
                            <i data-lucide="sun" class="w-5 h-5"></i>
                        </div>
                        <div>
                            <h4 class="font-display font-bold text-sm text-luxury-950">Natural Sun Drying</h4>
                            <p class="text-xs text-luxury-500">Naturally slow sun-dried on organic hemp platforms, lock-in strong aroma.</p>
                        </div>
                    </div>

                    <div class="flex gap-3">
                        <div class="p-2 bg-luxury-50 rounded-lg text-luxury-600 h-fit">
                            <i data-lucide="check-circle" class="w-5 h-5"></i>
                        </div>
                        <div>
                            <h4 class="font-display font-bold text-sm text-luxury-950">Triple Graded Hand Sorting</h4>
                            <p class="text-xs text-luxury-500">Only the boldest kernel halves (Akhrot Giri) make it into our final packages.</p>
                        </div>
                    </div>

                    <div class="flex gap-3">
                        <div class="p-2 bg-luxury-50 rounded-lg text-luxury-600 h-fit">
                            <i data-lucide="users" class="w-5 h-5"></i>
                        </div>
                        <div>
                            <h4 class="font-display font-bold text-sm text-luxury-950">Fair Trade & Local Support</h4>
                            <p class="text-xs text-luxury-500">Every pack feeds and supports organic families in rural Pahalgam and Pampore.</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Visual Right (Premium Landscape Image of Pahalgam Farms) -->
            <div class="rounded-3xl h-[480px] border border-stone-200/60 shadow-xl relative overflow-hidden group">
                <img src="assets/images/kashmiri_farm_orchard.webp" alt="Pahalgam Walnut Orchard, Kashmir" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700" referrerPolicy="no-referrer" />
                <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-black/10 to-transparent"></div>
                <div class="absolute bottom-6 left-6 right-6 text-amber-100 space-y-1">
                    <span class="text-[10px] font-mono uppercase tracking-widest text-amber-300">Origin Showcase</span>
                    <h3 class="font-display font-bold text-lg text-amber-100">Pahalgam Walnut Groves & Garlic Fields</h3>
                    <p class="text-[11px] text-stone-300/90 leading-relaxed">Sourced from fertile volcanic mountain terraces irrigated by cold, pure glacier meltwater streams.</p>
                </div>
            </div>

        </div>
    </div>
</section>

<!-- 6. Customer Reviews Section -->
<section class="py-20 bg-luxury-100/30">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center max-w-xl mx-auto mb-16 space-y-3">
            <p class="text-xs uppercase tracking-[0.2em] text-luxury-500 font-display font-bold">Client Testimonials</p>
            <h2 class="font-display text-3xl font-bold tracking-tight text-luxury-950">Endorsed by Gourmets</h2>
            <div class="w-12 h-[2px] bg-luxury-400 mx-auto"></div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <!-- Review 1 -->
            <div class="bg-white p-8 rounded-xl shadow-sm border border-luxury-200/50 space-y-4 flex flex-col justify-between">
                <div class="space-y-3">
                    <div class="flex items-center gap-1 text-amber-500">
                        <i data-lucide="star" class="w-4 h-4 fill-amber-500"></i>
                        <i data-lucide="star" class="w-4 h-4 fill-amber-500"></i>
                        <i data-lucide="star" class="w-4 h-4 fill-amber-500"></i>
                        <i data-lucide="star" class="w-4 h-4 fill-amber-500"></i>
                        <i data-lucide="star" class="w-4 h-4 fill-amber-500"></i>
                    </div>
                    <p class="text-xs text-luxury-600 leading-relaxed italic">
                        "The walnuts from Deenz Organics are unlike anything you find in general retail. Extraordinarily crunchy, creamy, and no bitter aftertaste. You can tell they were sorted by hand."
                    </p>
                </div>
                <div class="border-t border-luxury-50 pt-4 flex items-center gap-3">
                    <div class="w-8 h-8 bg-luxury-100 rounded-full flex items-center justify-center text-xs font-bold text-luxury-800">
                        AK
                    </div>
                    <div>
                        <h4 class="font-display font-bold text-xs text-luxury-950">Ananya Kulkarni</h4>
                        <p class="text-[9px] text-luxury-400">Verified Purchaser, Pune</p>
                    </div>
                </div>
            </div>

            <!-- Review 2 -->
            <div class="bg-white p-8 rounded-xl shadow-sm border border-luxury-200/50 space-y-4 flex flex-col justify-between">
                <div class="space-y-3">
                    <div class="flex items-center gap-1 text-amber-500">
                        <i data-lucide="star" class="w-4 h-4 fill-amber-500"></i>
                        <i data-lucide="star" class="w-4 h-4 fill-amber-500"></i>
                        <i data-lucide="star" class="w-4 h-4 fill-amber-500"></i>
                        <i data-lucide="star" class="w-4 h-4 fill-amber-500"></i>
                        <i data-lucide="star" class="w-4 h-4 fill-amber-500"></i>
                    </div>
                    <p class="text-xs text-luxury-600 leading-relaxed italic">
                        "I use the Kashmiri garlic cloves for traditional recipes. The aroma is incredibly pungent and sharp, it elevates my family gravies instantly. Plus, they stay fresh much longer!"
                    </p>
                </div>
                <div class="border-t border-luxury-50 pt-4 flex items-center gap-3">
                    <div class="w-8 h-8 bg-luxury-100 rounded-full flex items-center justify-center text-xs font-bold text-luxury-800">
                        MS
                    </div>
                    <div>
                        <h4 class="font-display font-bold text-xs text-luxury-950">Mohammad Shafi</h4>
                        <p class="text-[9px] text-luxury-400">Chef & Caterer, Delhi</p>
                    </div>
                </div>
            </div>

            <!-- Review 3 -->
            <div class="bg-white p-8 rounded-xl shadow-sm border border-luxury-200/50 space-y-4 flex flex-col justify-between">
                <div class="space-y-3">
                    <div class="flex items-center gap-1 text-amber-500">
                        <i data-lucide="star" class="w-4 h-4 fill-amber-500"></i>
                        <i data-lucide="star" class="w-4 h-4 fill-amber-500"></i>
                        <i data-lucide="star" class="w-4 h-4 fill-amber-500"></i>
                        <i data-lucide="star" class="w-4 h-4 fill-amber-500"></i>
                        <i data-lucide="star" class="w-4 h-4 fill-amber-500"></i>
                    </div>
                    <p class="text-xs text-luxury-600 leading-relaxed italic">
                        "Exceptional, genuine service. Fast delivery to Mumbai, beautifully packed inside vacuum pouches. Highly recommend ordering direct from their farm rather than commercial middleman platforms."
                    </p>
                </div>
                <div class="border-t border-luxury-50 pt-4 flex items-center gap-3">
                    <div class="w-8 h-8 bg-luxury-100 rounded-full flex items-center justify-center text-xs font-bold text-luxury-800">
                        PT
                    </div>
                    <div>
                        <h4 class="font-display font-bold text-xs text-luxury-950">Prashant Trivedi</h4>
                        <p class="text-[9px] text-luxury-400">Loyal Subscriber, Mumbai</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- 7. Frequently Asked Questions (Home section) -->
<section class="py-24 bg-white">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16 space-y-3">
            <p class="text-xs uppercase tracking-[0.2em] text-luxury-500 font-display font-bold">Got Questions?</p>
            <h2 class="font-display text-3xl font-bold tracking-tight text-luxury-950">Common Questions & Answers</h2>
            <div class="w-12 h-[2px] bg-luxury-400 mx-auto"></div>
        </div>

        <div class="space-y-4">
            <div class="border border-luxury-100 rounded-lg p-6 bg-luxury-50/20">
                <h4 class="font-display font-bold text-sm text-luxury-950 mb-2">Where is Deenz Organics based?</h4>
                <p class="text-xs text-luxury-600 leading-relaxed">
                    We are headquartered in Pahalgam & Pampore, Jammu and Kashmir, India. Our packaging, grading, and shipping occur right at the origin fields to protect single-origin authenticity.
                </p>
            </div>

            <div class="border border-luxury-100 rounded-lg p-6 bg-luxury-50/20">
                <h4 class="font-display font-bold text-sm text-luxury-950 mb-2">How do you ensure pesticide-free quality?</h4>
                <p class="text-xs text-luxury-600 leading-relaxed">
                    Our local farmers rely solely on traditional compost, mountain silt, and natural organic matter. We systematically test batch samples for chemical contamination to ensure compliance with our pure organic oath.
                </p>
            </div>

            <div class="border border-luxury-100 rounded-lg p-6 bg-luxury-50/20">
                <h4 class="font-display font-bold text-sm text-luxury-950 mb-2">What are the shipping durations?</h4>
                <p class="text-xs text-luxury-600 leading-relaxed">
                    Orders inside Jammu & Kashmir are generally delivered within 2-3 business days. For the rest of India, our air cargo shipping partner ensures delivery within 4-7 business days with secure tracking codes.
                </p>
            </div>
        </div>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
