<?php
/**
 * Deenz Organics - Dedicated FAQs & Help Center
 */
$page_title = "Frequently Asked Questions & Support | Deenz Organics";
$page_description = "Find clear answers about Deenz Organics single-origin Kashmiri produce, Wanpora Kulgam farm sourcing, storage tips, express shipping, and our 100% crunch guarantee.";
require_once __DIR__ . '/includes/header.php';
?>

<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-16 space-y-12">
    <!-- Hero Header -->
    <div class="text-center space-y-4 max-w-2xl mx-auto">
        <div class="inline-flex items-center gap-2 bg-emerald-50 text-emerald-800 border border-emerald-200 text-[10px] font-display font-bold uppercase tracking-widest px-3.5 py-1.5 rounded-full shadow-2xs">
            <i data-lucide="help-circle" class="w-3.5 h-3.5 text-emerald-600"></i> Direct Founder Support
        </div>
        <h1 class="font-display font-bold text-3xl sm:text-4xl text-stone-950">Frequently Asked Questions</h1>
        <p class="text-xs sm:text-sm text-stone-700 leading-relaxed">
            Everything you need to know about our single-origin harvesting in Wanpora, Kulgam, organic certifications, express delivery across India, and 100% freshness guarantee.
        </p>
        <div class="w-12 h-[2px] bg-emerald-600 mx-auto"></div>
    </div>

    <!-- Direct Founder Contact Banner (Light Theme with Dark Black Text) -->
    <div class="bg-emerald-50/90 border border-emerald-200 rounded-2xl p-6 sm:p-8 shadow-2xs flex flex-col md:flex-row items-center justify-between gap-6">
        <div class="space-y-1.5 text-center md:text-left">
            <span class="text-[10px] font-mono font-bold text-emerald-800 uppercase tracking-wider block">Wanpora Kulgam Direct Helpline</span>
            <h3 class="font-display font-bold text-base sm:text-lg text-stone-950">Have a specific question or bulk inquiry?</h3>
            <p class="text-xs text-stone-700 max-w-lg">
                Speak directly with our founders, Dr. Deen Mohd & Raashid Din, or our dedicated customer care team.
            </p>
        </div>
        <div class="flex items-center gap-3 shrink-0">
            <a href="tel:+916006049016" class="bg-emerald-800 hover:bg-emerald-900 text-white font-display font-bold text-xs py-3 px-5 rounded-xl transition-all shadow-2xs flex items-center gap-2">
                <i data-lucide="phone" class="w-4 h-4 text-emerald-200"></i> Call +91 60060 49016
            </a>
            <a href="contact.php" class="bg-white hover:bg-stone-50 text-stone-950 font-display font-bold text-xs py-3 px-5 rounded-xl border border-stone-300 transition-all flex items-center gap-2">
                <i data-lucide="mail" class="w-4 h-4 text-stone-700"></i> Contact Page
            </a>
        </div>
    </div>

    <!-- Category Filter Tabs -->
    <div class="flex items-center justify-center gap-2 flex-wrap border-b border-stone-200/80 pb-4" id="faq-categories">
        <button type="button" onclick="filterFaqs('All', event)" class="faq-cat-btn bg-emerald-800 text-white px-4 py-2 rounded-xl text-xs font-display font-bold shadow-2xs transition-all cursor-pointer">
            All Questions (10)
        </button>
        <button type="button" onclick="filterFaqs('Sourcing & Purity', event)" class="faq-cat-btn bg-white text-stone-900 hover:bg-stone-100 border border-stone-300 px-4 py-2 rounded-xl text-xs font-display font-bold transition-all cursor-pointer">
            🌱 Sourcing & Purity
        </button>
        <button type="button" onclick="filterFaqs('Products & Storage', event)" class="faq-cat-btn bg-white text-stone-900 hover:bg-stone-100 border border-stone-300 px-4 py-2 rounded-xl text-xs font-display font-bold transition-all cursor-pointer">
            🌰 Products & Storage
        </button>
        <button type="button" onclick="filterFaqs('Shipping & Delivery', event)" class="faq-cat-btn bg-white text-stone-900 hover:bg-stone-100 border border-stone-300 px-4 py-2 rounded-xl text-xs font-display font-bold transition-all cursor-pointer">
            🚚 Shipping & Delivery
        </button>
        <button type="button" onclick="filterFaqs('Payments & COD', event)" class="faq-cat-btn bg-white text-stone-900 hover:bg-stone-100 border border-stone-300 px-4 py-2 rounded-xl text-xs font-display font-bold transition-all cursor-pointer">
            💳 Payments & COD
        </button>
        <button type="button" onclick="filterFaqs('Returns & Guarantee', event)" class="faq-cat-btn bg-white text-stone-900 hover:bg-stone-100 border border-stone-300 px-4 py-2 rounded-xl text-xs font-display font-bold transition-all cursor-pointer">
            ✨ Returns & Guarantee
        </button>
    </div>

    <!-- FAQ List (Direct HTML so Node Express and PHP both render 100% of Q&A cards) -->
    <div class="space-y-4" id="faq-list-container">
        
        <!-- FAQ 1 -->
        <div class="faq-card bg-white border border-stone-200/90 rounded-2xl p-6 shadow-2xs hover:border-emerald-300 transition-all space-y-3" data-category="Sourcing & Purity">
            <div class="flex items-start justify-between gap-4">
                <div class="flex items-start gap-3">
                    <span class="w-7 h-7 rounded-lg bg-emerald-100 text-emerald-950 font-display font-bold text-xs flex items-center justify-center shrink-0 mt-0.5">
                        1
                    </span>
                    <h3 class="font-display font-bold text-sm sm:text-base text-stone-950 leading-snug">
                        Where is Deenz Organics located and where are your products sourced from?
                    </h3>
                </div>
                <span class="text-[9px] font-mono font-bold uppercase tracking-wider px-2.5 py-1 rounded-md bg-emerald-50 text-emerald-900 border border-emerald-200 shrink-0">
                    Sourcing & Purity
                </span>
            </div>
            <p class="text-xs sm:text-sm text-stone-700 leading-relaxed pl-10">
                Our central processing unit, grading facility, and main office are located in Wanpora, Kulgam, Jammu & Kashmir - 192231, India. Our produce is sourced directly from our family orchards and trusted local farmer collectives in high-altitude mountain valleys (Wanpora, Kulgam, Pahalgam, and Pampore). We never import or mix external grades.
            </p>
        </div>

        <!-- FAQ 2 -->
        <div class="faq-card bg-white border border-stone-200/90 rounded-2xl p-6 shadow-2xs hover:border-emerald-300 transition-all space-y-3" data-category="Sourcing & Purity">
            <div class="flex items-start justify-between gap-4">
                <div class="flex items-start gap-3">
                    <span class="w-7 h-7 rounded-lg bg-emerald-100 text-emerald-950 font-display font-bold text-xs flex items-center justify-center shrink-0 mt-0.5">
                        2
                    </span>
                    <h3 class="font-display font-bold text-sm sm:text-base text-stone-950 leading-snug">
                        How do you guarantee 100% organic, chemical-free purity?
                    </h3>
                </div>
                <span class="text-[9px] font-mono font-bold uppercase tracking-wider px-2.5 py-1 rounded-md bg-emerald-50 text-emerald-900 border border-emerald-200 shrink-0">
                    Sourcing & Purity
                </span>
            </div>
            <p class="text-xs sm:text-sm text-stone-700 leading-relaxed pl-10">
                We maintain complete direct ownership from farm to packaging. Our Akhrot Giri (walnut kernels) are carefully hand-cracked and sorted without sulfur bleaching or chemical sprays. Our mountain garlic is naturally sun-cured in clean Himalayan air with its papery protective skin intact. Zero artificial preservatives or sulfur fumes are ever used.
            </p>
        </div>

        <!-- FAQ 3 -->
        <div class="faq-card bg-white border border-stone-200/90 rounded-2xl p-6 shadow-2xs hover:border-emerald-300 transition-all space-y-3" data-category="Products & Storage">
            <div class="flex items-start justify-between gap-4">
                <div class="flex items-start gap-3">
                    <span class="w-7 h-7 rounded-lg bg-emerald-100 text-emerald-950 font-display font-bold text-xs flex items-center justify-center shrink-0 mt-0.5">
                        3
                    </span>
                    <h3 class="font-display font-bold text-sm sm:text-base text-stone-950 leading-snug">
                        How should I store Kashmiri Walnut Kernels (Akhrot Giri) for maximum crunch?
                    </h3>
                </div>
                <span class="text-[9px] font-mono font-bold uppercase tracking-wider px-2.5 py-1 rounded-md bg-amber-50 text-amber-900 border border-amber-200 shrink-0">
                    Products & Storage
                </span>
            </div>
            <p class="text-xs sm:text-sm text-stone-700 leading-relaxed pl-10">
                Because our raw Kashmiri walnut kernels are 100% natural and rich in essential Omega-3 oils, store them in an airtight glass container in a cool, dry place. For long-term storage (over 2 months), keep them in the refrigerator or freezer. This prevents natural plant oils from oxidizing and keeps them crisp for up to 12 months.
            </p>
        </div>

        <!-- FAQ 4 -->
        <div class="faq-card bg-white border border-stone-200/90 rounded-2xl p-6 shadow-2xs hover:border-emerald-300 transition-all space-y-3" data-category="Products & Storage">
            <div class="flex items-start justify-between gap-4">
                <div class="flex items-start gap-3">
                    <span class="w-7 h-7 rounded-lg bg-emerald-100 text-emerald-950 font-display font-bold text-xs flex items-center justify-center shrink-0 mt-0.5">
                        4
                    </span>
                    <h3 class="font-display font-bold text-sm sm:text-base text-stone-950 leading-snug">
                        Why is unpeeled Kashmiri mountain garlic superior to store-bought garlic?
                    </h3>
                </div>
                <span class="text-[9px] font-mono font-bold uppercase tracking-wider px-2.5 py-1 rounded-md bg-amber-50 text-amber-900 border border-amber-200 shrink-0">
                    Products & Storage
                </span>
            </div>
            <p class="text-xs sm:text-sm text-stone-700 leading-relaxed pl-10">
                Unpeeled garlic retains its natural protective papery skin, locking in volatile essential oils and powerful allicin compounds. Store-bought pre-peeled garlic often undergoes chemical dips to prevent browning and quickly loses its potency. Our sun-dried unpeeled mountain garlic stays naturally fresh for 6–12 months.
            </p>
        </div>

        <!-- FAQ 5 -->
        <div class="faq-card bg-white border border-stone-200/90 rounded-2xl p-6 shadow-2xs hover:border-emerald-300 transition-all space-y-3" data-category="Shipping & Delivery">
            <div class="flex items-start justify-between gap-4">
                <div class="flex items-start gap-3">
                    <span class="w-7 h-7 rounded-lg bg-emerald-100 text-emerald-950 font-display font-bold text-xs flex items-center justify-center shrink-0 mt-0.5">
                        5
                    </span>
                    <h3 class="font-display font-bold text-sm sm:text-base text-stone-950 leading-snug">
                        What are the shipping charges and delivery timelines across India?
                    </h3>
                </div>
                <span class="text-[9px] font-mono font-bold uppercase tracking-wider px-2.5 py-1 rounded-md bg-blue-50 text-blue-900 border border-blue-200 shrink-0">
                    Shipping & Delivery
                </span>
            </div>
            <p class="text-xs sm:text-sm text-stone-700 leading-relaxed pl-10">
                All orders above ₹500 qualify for FREE Express Courier Delivery across India. For orders below ₹500, a flat shipping fee of ₹50 applies. Consignments are dispatched directly from our Wanpora, Kulgam facility within 24–48 hours. Express transit takes 3–5 business days for metro cities (Delhi, Mumbai, Bengaluru, Hyderabad, Chennai, Kolkata) and 5–7 days for rest of India.
            </p>
        </div>

        <!-- FAQ 6 -->
        <div class="faq-card bg-white border border-stone-200/90 rounded-2xl p-6 shadow-2xs hover:border-emerald-300 transition-all space-y-3" data-category="Shipping & Delivery">
            <div class="flex items-start justify-between gap-4">
                <div class="flex items-start gap-3">
                    <span class="w-7 h-7 rounded-lg bg-emerald-100 text-emerald-950 font-display font-bold text-xs flex items-center justify-center shrink-0 mt-0.5">
                        6
                    </span>
                    <h3 class="font-display font-bold text-sm sm:text-base text-stone-950 leading-snug">
                        How can I track my shipment after placing an order?
                    </h3>
                </div>
                <span class="text-[9px] font-mono font-bold uppercase tracking-wider px-2.5 py-1 rounded-md bg-blue-50 text-blue-900 border border-blue-200 shrink-0">
                    Shipping & Delivery
                </span>
            </div>
            <p class="text-xs sm:text-sm text-stone-700 leading-relaxed pl-10">
                Once your package is dispatched from Wanpora, Kulgam, you will receive an automated order tracking link via SMS, WhatsApp, and Email. You can also enter your Order ID anytime on our website's 'Track Order' page for real-time live courier updates.
            </p>
        </div>

        <!-- FAQ 7 -->
        <div class="faq-card bg-white border border-stone-200/90 rounded-2xl p-6 shadow-2xs hover:border-emerald-300 transition-all space-y-3" data-category="Payments & COD">
            <div class="flex items-start justify-between gap-4">
                <div class="flex items-start gap-3">
                    <span class="w-7 h-7 rounded-lg bg-emerald-100 text-emerald-950 font-display font-bold text-xs flex items-center justify-center shrink-0 mt-0.5">
                        7
                    </span>
                    <h3 class="font-display font-bold text-sm sm:text-base text-stone-950 leading-snug">
                        What payment methods do you accept? Is Cash on Delivery (COD) available?
                    </h3>
                </div>
                <span class="text-[9px] font-mono font-bold uppercase tracking-wider px-2.5 py-1 rounded-md bg-purple-50 text-purple-900 border border-purple-200 shrink-0">
                    Payments & COD
                </span>
            </div>
            <p class="text-xs sm:text-sm text-stone-700 leading-relaxed pl-10">
                We accept all major secure payment options including UPI (Google Pay, PhonePe, Paytm, BHIM), Credit Cards, Debit Cards, Net Banking, and Cash on Delivery (COD) across 19,000+ pincodes in India.
            </p>
        </div>

        <!-- FAQ 8 -->
        <div class="faq-card bg-white border border-stone-200/90 rounded-2xl p-6 shadow-2xs hover:border-emerald-300 transition-all space-y-3" data-category="Payments & COD">
            <div class="flex items-start justify-between gap-4">
                <div class="flex items-start gap-3">
                    <span class="w-7 h-7 rounded-lg bg-emerald-100 text-emerald-950 font-display font-bold text-xs flex items-center justify-center shrink-0 mt-0.5">
                        8
                    </span>
                    <h3 class="font-display font-bold text-sm sm:text-base text-stone-950 leading-snug">
                        How do I apply promotional discount codes like KASHMIR10 or ORGANIC50?
                    </h3>
                </div>
                <span class="text-[9px] font-mono font-bold uppercase tracking-wider px-2.5 py-1 rounded-md bg-purple-50 text-purple-900 border border-purple-200 shrink-0">
                    Payments & COD
                </span>
            </div>
            <p class="text-xs sm:text-sm text-stone-700 leading-relaxed pl-10">
                During checkout on the Cart or Checkout page, type your discount code into the 'Promo / Coupon Code' input field and click Apply. For example, 'KASHMIR10' provides 10% off on eligible orders, and 'ORGANIC50' provides a flat ₹50 discount.
            </p>
        </div>

        <!-- FAQ 9 -->
        <div class="faq-card bg-white border border-stone-200/90 rounded-2xl p-6 shadow-2xs hover:border-emerald-300 transition-all space-y-3" data-category="Returns & Guarantee">
            <div class="flex items-start justify-between gap-4">
                <div class="flex items-start gap-3">
                    <span class="w-7 h-7 rounded-lg bg-emerald-100 text-emerald-950 font-display font-bold text-xs flex items-center justify-center shrink-0 mt-0.5">
                        9
                    </span>
                    <h3 class="font-display font-bold text-sm sm:text-base text-stone-950 leading-snug">
                        What is your 100% Crunch & Freshness Guarantee?
                    </h3>
                </div>
                <span class="text-[9px] font-mono font-bold uppercase tracking-wider px-2.5 py-1 rounded-md bg-teal-50 text-teal-900 border border-teal-200 shrink-0">
                    Returns & Guarantee
                </span>
            </div>
            <p class="text-xs sm:text-sm text-stone-700 leading-relaxed pl-10">
                We stand behind our produce with a direct founder guarantee. If your order arrives damaged, defective, or lacks its characteristic Kashmiri mountain crunch, simply send a photo/video to +91 60060 49016 or dr.deenmohd@gmail.com within 7 days. We will immediately dispatch a free replacement or issue a full refund.
            </p>
        </div>

        <!-- FAQ 10 -->
        <div class="faq-card bg-white border border-stone-200/90 rounded-2xl p-6 shadow-2xs hover:border-emerald-300 transition-all space-y-3" data-category="Sourcing & Purity">
            <div class="flex items-start justify-between gap-4">
                <div class="flex items-start gap-3">
                    <span class="w-7 h-7 rounded-lg bg-emerald-100 text-emerald-950 font-display font-bold text-xs flex items-center justify-center shrink-0 mt-0.5">
                        10
                    </span>
                    <h3 class="font-display font-bold text-sm sm:text-base text-stone-950 leading-snug">
                        Do you supply bulk wholesale orders and can customers visit your Wanpora office?
                    </h3>
                </div>
                <span class="text-[9px] font-mono font-bold uppercase tracking-wider px-2.5 py-1 rounded-md bg-emerald-50 text-emerald-900 border border-emerald-200 shrink-0">
                    Sourcing & Purity
                </span>
            </div>
            <p class="text-xs sm:text-sm text-stone-700 leading-relaxed pl-10">
                Yes! We supply bulk quantities (5kg to 500kg+) to bakeries, restaurants, health food manufacturers, and corporate gifting partners. Visitors are also welcome at our main office and sorting center in Wanpora, Kulgam, J&K. Please contact us a day prior via +91 60060 49016 so our founders can schedule your visit.
            </p>
        </div>

    </div>

    <!-- Office & Address Card -->
    <div class="bg-stone-50 border border-stone-200 rounded-2xl p-6 text-center space-y-2">
        <h4 class="font-display font-bold text-sm text-stone-950">Deenz Organics Registered Office & Dispatch Center</h4>
        <p class="text-xs text-stone-700">
            Wanpora, Kulgam, Jammu & Kashmir - 192231, India &bull; Direct Founder Line: <strong class="font-mono text-stone-950">+91 60060 49016</strong> &bull; Email: <strong class="font-mono text-stone-950">dr.deenmohd@gmail.com</strong>
        </p>
    </div>
</div>

<script>
    function filterFaqs(category, evt) {
        const cards = document.querySelectorAll('.faq-card');
        cards.forEach(card => {
            if (category === 'All' || card.dataset.category === category) {
                card.style.display = 'block';
            } else {
                card.style.display = 'none';
            }
        });

        // Update button styles to keep all text high-contrast dark/black
        document.querySelectorAll('.faq-cat-btn').forEach(btn => {
            btn.className = "faq-cat-btn bg-white text-stone-900 hover:bg-stone-100 border border-stone-300 px-4 py-2 rounded-xl text-xs font-display font-bold transition-all cursor-pointer";
        });

        if (evt && evt.target) {
            evt.target.className = "faq-cat-btn bg-emerald-800 text-white px-4 py-2 rounded-xl text-xs font-display font-bold shadow-2xs transition-all cursor-pointer";
        }
    }
</script>

<?php require_once __DIR__ . '/includes/footer.php'; ?>


