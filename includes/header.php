<?php
/**
 * Deenz Organics - Luxury Global Header
 */
require_once __DIR__ . '/db.php';

// Get Cart Items count
$cart_count = 0;
if (isset($_SESSION['cart']) && is_array($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $item) {
        $cart_count += ($item['quantity'] ?? 0);
    }
}
?>
<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <base href="/">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($page_title) ? sanitize_html($page_title) . " | Deenz Organics" : "Deenz Organics | Premium Kashmiri Organic Foods"; ?></title>
    <meta name="description" content="<?php echo isset($page_description) ? sanitize_html($page_description) : "Discover 100% natural, fresh and crunchy Kashmiri Walnuts and aromatic mountain Garlic Cloves from Deenz Organics. Sourced directly from Pahalgam and Pampore valleys."; ?>">
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Space+Grotesk:wght@400;500;600;700&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">
    
    <!-- Compiled Tailwind CSS & Play CDN Fallback -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        luxury: {
                            50: '#f9f6f0',
                            100: '#f0e8dc',
                            200: '#e1d2be',
                            300: '#ccb496',
                            400: '#b4946f',
                            500: '#9e7b54',
                            600: '#8c6846',
                            700: '#755439',
                            800: '#5c412b',
                            900: '#3d2a1b',
                            950: '#24180f',
                        }
                    },
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                        display: ['Space Grotesk', 'sans-serif'],
                        mono: ['JetBrains Mono', 'monospace'],
                    }
                }
            }
        }
    </script>
    <link rel="stylesheet" href="/includes/tailwind.css">
    
    <!-- Lucide Icons CDN -->
    <script src="https://unpkg.com/lucide@latest"></script>
    
    <style>
        img {
            max-width: 100%;
            height: auto;
        }
        .thumbnail-btn img {
            max-width: 100% !important;
            max-height: 100% !important;
            object-fit: contain !important;
        }
        .thumbnail-btn {
            flex-shrink: 0 !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
        }
        .aspect-square {
            aspect-ratio: 1 / 1 !important;
        }
        .custom-scrollbar::-webkit-scrollbar {
            width: 6px;
        }
        .custom-scrollbar::-webkit-scrollbar-track {
            background: #f1f1f1;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #ccb496;
            border-radius: 3px;
        }

        /* Soft blurred placeholder style */
        ::placeholder,
        ::-webkit-input-placeholder,
        ::-moz-placeholder,
        :-ms-input-placeholder {
            color: #88827a !important;
            opacity: 0.55 !important;
            font-style: italic !important;
            font-weight: 300 !important;
            filter: blur(0.35px) !important;
            -webkit-filter: blur(0.35px) !important;
            transition: all 0.2s ease-in-out !important;
        }
        input:focus::placeholder,
        textarea:focus::placeholder {
            opacity: 0.25 !important;
            filter: blur(0.75px) !important;
            -webkit-filter: blur(0.75px) !important;
        }
    </style>
    
    <!-- Schema.org Structured Data -->
    <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "Organization",
      "name": "Deenz Organics",
      "url": "<?php echo 'https://' . $_SERVER['HTTP_HOST']; ?>",
      "logo": "<?php echo 'https://' . $_SERVER['HTTP_HOST']; ?>/assets/images/deenz_walnut_logo.webp",
      "contactPoint": {
        "@type": "ContactPoint",
        "telephone": "+91-60060-49016",
        "contactType": "Customer Service",
        "email": "dr.deenmohd@gmail.com",
        "areaServed": "IN",
        "availableLanguage": ["en", "Hindi"]
      },
      "sameAs": [
        "https://facebook.com/deenzorganics",
        "https://instagram.com/deenzorganics",
        "https://twitter.com/deenzorganics"
      ]
    }
    </script>
</head>
<body class="bg-luxury-50 font-sans text-luxury-950 flex flex-col min-h-screen">

    <!-- Top Announcement Bar -->
    <div class="bg-luxury-950 text-luxury-100 text-xs py-2 px-4 text-center tracking-wider font-display border-b border-luxury-900">
        🏔️ Sourced Directly from Pahalgam and Pampore, Kashmir | Free Shipping on Orders over ₹500
    </div>

    <!-- Main Header -->
    <header class="relative z-50 bg-white shadow-sm border-b border-luxury-100 transition-all duration-300">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-20 flex items-center justify-between">
            
            <!-- Logo -->
            <a href="/" class="flex items-center gap-2 group">
                <div class="w-10 h-10 bg-luxury-900 rounded-full flex items-center justify-center text-luxury-200 font-display font-bold text-lg shadow-md group-hover:scale-105 transition-transform">
                    D+O
                </div>
                <div>
                    <span class="font-display font-bold text-xl tracking-tight uppercase block text-luxury-950 group-hover:text-luxury-600 transition-colors">DEENZ ORGANICS</span>
                    <span class="text-[9px] uppercase tracking-[0.2em] block text-luxury-400 -mt-1">Kashmiri Mountain Gold</span>
                </div>
            </a>

            <!-- Desktop Nav Links -->
            <nav class="hidden md:flex items-center gap-8 font-display text-sm font-medium tracking-wide">
                <a href="/" class="text-luxury-900 hover:text-luxury-500 transition-colors">Home</a>
                <a href="/shop" class="text-luxury-900 hover:text-luxury-500 transition-colors">Our Harvests</a>
                <a href="/about" class="text-luxury-900 hover:text-luxury-500 transition-colors">Our Story</a>
                <a href="/track" class="text-luxury-900 hover:text-luxury-500 transition-colors flex items-center gap-1">
                    <i data-lucide="package" class="w-4 h-4"></i> Track Order
                </a>
            </nav>

            <!-- Action Buttons -->
            <div class="flex items-center gap-4">
                
                <!-- Autocomplete Search Bar -->
                <div class="hidden lg:flex items-center relative" id="header-search-container">
                    <form action="/shop" method="GET" class="flex items-center relative" id="header-search-form">
                        <input type="text" id="header-search-input" name="search" autocomplete="off" placeholder="Search Organic..." class="bg-luxury-50/50 hover:bg-luxury-50 focus:bg-white text-xs border border-luxury-200 rounded-full py-2 pl-4 pr-10 focus:outline-none focus:ring-1 focus:ring-luxury-400 w-52 transition-all">
                        <button type="submit" class="absolute right-3 text-luxury-400 hover:text-luxury-700">
                            <i data-lucide="search" class="w-4 h-4"></i>
                        </button>
                    </form>
                    <!-- Search Suggestion Dropdown -->
                    <div id="search-suggestions-dropdown" class="hidden absolute top-full right-0 mt-2 w-80 bg-white border border-luxury-100 rounded-xl shadow-xl p-4 z-50 text-xs">
                        <div class="space-y-4">
                            <div>
                                <h5 class="text-[9px] uppercase tracking-wider font-bold text-luxury-400 mb-1.5 font-display">Popular Harvests</h5>
                                <div class="flex flex-wrap gap-1.5" id="popular-suggestions">
                                    <a href="/shop?search=walnut" class="bg-luxury-50 hover:bg-luxury-100 text-luxury-800 px-2.5 py-1 rounded-full transition-colors font-medium">Walnuts</a>
                                    <a href="/shop?search=garlic" class="bg-luxury-50 hover:bg-luxury-100 text-luxury-800 px-2.5 py-1 rounded-full transition-colors font-medium">Garlic</a>
                                    <a href="/shop?search=akhrot" class="bg-luxury-50 hover:bg-luxury-100 text-luxury-800 px-2.5 py-1 rounded-full transition-colors font-medium">Akhrot Giri</a>
                                    <a href="/shop?search=organic" class="bg-luxury-50 hover:bg-luxury-100 text-luxury-800 px-2.5 py-1 rounded-full transition-colors font-medium">Organic</a>
                                </div>
                            </div>
                            <div id="autocomplete-products-section" class="hidden border-t border-luxury-50 pt-3">
                                <h5 class="text-[9px] uppercase tracking-wider font-bold text-luxury-400 mb-1.5 font-display">Matching Products</h5>
                                <div class="space-y-2" id="autocomplete-products-list">
                                    <!-- Dynamic list of products -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <script>
                document.addEventListener('DOMContentLoaded', () => {
                    const searchContainer = document.getElementById('header-search-container');
                    const searchInput = document.getElementById('header-search-input');
                    const dropdown = document.getElementById('search-suggestions-dropdown');
                    const autocompleteSection = document.getElementById('autocomplete-products-section');
                    const autocompleteList = document.getElementById('autocomplete-products-list');

                    const CATALOG = [
                        { name: "Kashmiri Organic Walnuts / Akhrot Giri (500gms)", slug: "premium-kashmiri-walnut-kernels", emoji: "🌰", price: "₹750.00", cat: "Nuts & Seeds" },
                        { name: "Kashmiri Mountain Garlic / Lahsun (500gms)", slug: "premium-kashmiri-garlic-cloves", emoji: "🧄", price: "₹850.00", cat: "Fresh Produce" }
                    ];

                    if (!searchInput || !dropdown) return;

                    // Show dropdown on focus
                    searchInput.addEventListener('focus', () => {
                        dropdown.classList.remove('hidden');
                        renderAutocomplete();
                    });

                    // Hide dropdown when clicking outside
                    document.addEventListener('click', (e) => {
                        if (searchContainer && !searchContainer.contains(e.target)) {
                            dropdown.classList.add('hidden');
                        }
                    });

                    // Match on typing
                    searchInput.addEventListener('input', () => {
                        renderAutocomplete();
                    });

                    function renderAutocomplete() {
                        const query = searchInput.value.toLowerCase().trim();
                        if (query.length === 0) {
                            autocompleteSection.classList.add('hidden');
                            autocompleteList.innerHTML = '';
                            return;
                        }

                        const matches = CATALOG.filter(item => 
                            item.name.toLowerCase().includes(query) || 
                            item.cat.toLowerCase().includes(query)
                        );

                        if (matches.length > 0) {
                            autocompleteSection.classList.remove('hidden');
                            autocompleteList.innerHTML = matches.map(item => `
                                <a href="/${item.slug}/" class="flex items-center gap-2.5 p-1.5 rounded-lg hover:bg-luxury-50 transition-colors">
                                    <span class="w-7 h-7 bg-luxury-50 border border-luxury-100 rounded flex items-center justify-center text-sm">${item.emoji}</span>
                                    <div class="flex-1 min-w-0 text-left">
                                        <p class="font-display font-bold text-luxury-950 truncate text-[11px]">${item.name}</p>
                                        <p class="text-[9px] text-luxury-400 font-mono">${item.cat} | ${item.price}</p>
                                    </div>
                                    <svg class="w-3.5 h-3.5 text-luxury-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"></path></svg>
                                </a>
                            `).join('');
                        } else {
                            autocompleteSection.classList.remove('hidden');
                            autocompleteList.innerHTML = `<p class="text-[10px] text-luxury-400 p-1 text-left">No matching harvests found...</p>`;
                        }
                    }
                });
                </script>

                <!-- Cart Icon with badge -->
                <a href="/cart" class="relative p-2 text-luxury-900 hover:text-luxury-500 hover:scale-105 transition-all">
                    <i data-lucide="shopping-bag" class="w-6 h-6"></i>
                    <?php if ($cart_count > 0): ?>
                        <span class="absolute -top-1 -right-1 bg-luxury-600 text-white font-mono text-[10px] w-5 h-5 rounded-full flex items-center justify-center font-bold animate-pulse">
                             <?php echo $cart_count; ?>
                        </span>
                    <?php endif; ?>
                </a>

                <!-- Mobile Menu Button -->
                <button id="mobile-menu-btn" class="md:hidden p-2 text-luxury-900 hover:text-luxury-600 focus:outline-none">
                    <i data-lucide="menu" class="w-6 h-6"></i>
                </button>
            </div>
        </div>

        <!-- Mobile Drawer Menu -->
        <div id="mobile-drawer" class="hidden fixed inset-0 z-50 bg-black/50 backdrop-blur-sm transition-opacity duration-300">
            <div class="fixed top-0 bottom-0 right-0 w-80 max-w-[85vw] bg-white shadow-xl flex flex-col p-6 transition-transform duration-300 translate-x-full" id="mobile-drawer-content">
                <div class="flex items-center justify-between border-b border-luxury-100 pb-4 mb-6">
                    <div class="font-display font-bold text-lg text-luxury-950">NAVIGATION</div>
                    <button id="mobile-drawer-close" class="p-2 text-luxury-400 hover:text-luxury-800">
                        <i data-lucide="x" class="w-6 h-6"></i>
                    </button>
                </div>
                
                <nav class="flex flex-col gap-5 font-display text-base font-medium">
                    <a href="/" class="py-2 text-luxury-900 hover:text-luxury-500 border-b border-luxury-50 transition-colors">Home</a>
                    <a href="/shop" class="py-2 text-luxury-900 hover:text-luxury-500 border-b border-luxury-50 transition-colors">Our Harvests</a>
                    <a href="/about" class="py-2 text-luxury-900 hover:text-luxury-500 border-b border-luxury-50 transition-colors">Our Story</a>
                    <a href="/track" class="py-2 text-luxury-900 hover:text-luxury-500 border-b border-luxury-50 transition-colors flex items-center gap-2">
                        <i data-lucide="package" class="w-5 h-5 text-luxury-400"></i> Track Your Order
                    </a>
                </nav>

                <div class="mt-auto border-t border-luxury-100 pt-6 text-xs text-luxury-500 space-y-2">
                    <p class="flex items-center gap-2">
                        <i data-lucide="mail" class="w-4 h-4"></i> dr.deenmohd@gmail.com
                    </p>
                    <p class="flex items-center gap-2">
                        <i data-lucide="map-pin" class="w-4 h-4"></i> Pahalgam & Pampore, J&K, India
                    </p>
                </div>
            </div>
        </div>
    </header>

    <!-- Main content wrapper -->
    <main class="flex-grow">
