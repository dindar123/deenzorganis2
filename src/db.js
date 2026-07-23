// In-memory database mock for Deenz Organics
import { get_all_seeded_reviews } from './reviews_seed.js';

export const categories = [
  { id: 1, name: 'Nuts & Seeds', slug: 'nuts-seeds', description: 'Fresh, single-origin Kashmiri organic nuts and seeds.' },
  { id: 2, name: 'Fresh Vegetables', slug: 'fresh-vegetables', description: 'Sun-dried high-altitude mountain garlic and fresh valley produce.' }
];

export const products = [
  {
    id: 1,
    category_id: 1,
    category_name: 'Nuts & Seeds',
    category_slug: 'nuts-seeds',
    name: 'Kashmiri Organic Walnuts / Akhrot Giri (500gms)',
    slug: 'premium-kashmiri-walnut-kernels',
    sku: 'DZ-WLN-001',
    price: 775.00,
    sale_price: 750.00,
    short_description: 'Packed with essential nutrients like Omega-3 fatty acids, plant protein, dietary fiber, antioxidants, manganese, and vitamin E.',
    description: 'Kashmiri Organic Walnuts / Akhrot Giri (500gms) from Deenz Organics is 100% natural, raw, and packed with essential nutrients like Omega-3 fatty acids, plant protein, dietary fiber, antioxidants, manganese, and vitamin E. Hand-selected and vacuum-sealed directly from high-altitude Kashmiri orchards for maximum crunch, brain, and heart health benefits.',
    main_image: '/assets/images/kashmiri_walnuts_main.webp',
    images: [
      '/assets/images/kashmiri_walnuts_main.webp',
      '/assets/images/kashmiri_walnuts_close_up.webp',
      '/assets/images/kashmiri_walnuts_back_nutrition.webp',
      '/assets/images/kashmiri_walnuts_orchard_harvest.webp',
      '/assets/images/kashmiri_walnuts_lifestyle_bowl.webp',
      '/assets/images/kashmiri_walnuts_vacuum_pack.webp'
    ],
    stock: 120,
    status: 'published',
    rating: 5,
    benefits: [
      "100% natural & handpicked Kashmiri walnuts",
      "Rich in plant-based Omega-3 & dietary fiber",
      "Fresh, crunchy, and packed with high protein",
      "No added preservatives, artificial colors or flavors",
      "Hygienically packed to lock in natural freshness"
    ],
    specifications: {
      "Allergen Info": "Walnuts",
      "Weight Options": "500 Grams, 400 Grams, 300 Grams, 250 Grams",
      "Region of Origin": "Jammu and Kashmir, India",
      "Item Form": "Dried Kernels",
      "Manufacturer": "DEENZ ORGANICS",
      "Package Dimensions": "10 x 16 x 24 cm"
    },
    faqs: [
      { q: "Are these walnuts shelled or with shell?", a: "These are 100% walnut kernels (giri), which means they are already shelled and ready to eat!" },
      { q: "How should I store these walnut kernels?", a: "Keep them in an airtight container, preferably in a cool, dry place or refrigerator, to preserve their crunchiness and prevent natural oils from turning stale." },
      { q: "Are there any artificial preservatives added?", a: "No, Deenz Organics ensures absolutely zero preservatives, artificial colors, or chemical washes are used." }
    ],
    how_to_use: "Ideal as a daily morning brain food. Eat raw, roast lightly with spices, or chop to add to healthy salads, oat bowls, and baking recipes.",
    ingredients: "100% Raw Kashmiri Walnut Kernels."
  },
  {
    id: 2,
    category_id: 2,
    category_name: 'Fresh Vegetables',
    category_slug: 'fresh-vegetables',
    name: 'Kashmiri Mountain Garlic / Lahsun (500gms)',
    slug: 'premium-kashmiri-garlic-cloves',
    sku: 'DZ-GRL-002',
    price: 999.00,
    sale_price: 850.00,
    short_description: 'Packed with essential nutrients like manganese, vitamin B6, vitamin C, selenium, calcium, and vitamin B1.',
    description: 'Kashmiri Mountain Garlic / Lahsun (500gms) from Deenz Organics is 100% natural, sun-dried, and packed with essential nutrients like manganese, vitamin B6, vitamin C, selenium, calcium, and vitamin B1. Sourced directly from high-altitude Kashmiri valleys, these unpeeled garlic cloves offer intense aroma, bold flavor, and maximum natural health benefits.',
    main_image: '/assets/images/kashmiri_garlic_main.webp',
    images: [
      '/assets/images/kashmiri_garlic_main.webp',
      '/assets/images/kashmiri_garlic_close_up.webp',
      '/assets/images/kashmiri_garlic_back_nutrition.webp',
      '/assets/images/kashmiri_garlic_valley_harvest.webp',
      '/assets/images/kashmiri_garlic_cooking_culinary.webp',
      '/assets/images/kashmiri_garlic_vacuum_pack.webp'
    ],
    stock: 200,
    status: 'published',
    rating: 5,
    benefits: [
      "Premium-grade mountain-grown garlic sourced from Kashmir valleys",
      "Naturally sun-dried to lock in maximum aroma and sulfur compounds",
      "Unpeeled cloves for superior shelf life and flavor preservation",
      "100% clean, sorted, organic cultivation claim",
      "Zero artificial colors, preservatives, or chemical treatments"
    ],
    specifications: {
      "Diet Type": "Plant Based",
      "Item Form": "Whole Unpeeled Cloves",
      "Region of Origin": "Jammu and Kashmir, India",
      "Net Quantity": "500 Grams",
      "Manufacturer": "DEENZ ORGANICS",
      "Package Dimensions": "16 x 16 x 28 cm"
    },
    faqs: [
      { q: "What is the difference between Kashmiri garlic and regular garlic?", a: "Kashmiri garlic has a much stronger flavor profile and concentrated aromatic oils, meaning you need fewer cloves to achieve a robust taste in your cooking." },
      { q: "Are these cloves peeled?", a: "No, these are unpeeled cloves. The natural unpeeled skin protects the garlic from drying out and extends shelf life substantially." },
      { q: "Are they grown organically?", a: "Yes, our Kashmiri garlic is grown using traditional, pesticide-free organic farming methods in Kashmir valleys." }
    ],
    how_to_use: "Crush, chop, or mince unpeeled cloves directly during high-heat cooking. Ideal for Indian curries, Chinese wok preparations, and herbal home-remedies.",
    ingredients: "100% Sun-Dried Kashmiri Garlic Cloves."
  }
];

export const coupons = [
  { code: 'KASHMIR10', type: 'percent', value: 10, min_spend: 500 },
  { code: 'ORGANIC50', type: 'flat', value: 50, min_spend: 300 }
];

export const reviews = get_all_seeded_reviews();

export const orders = [
  {
    id: 1001,
    order_number: 'ORD-1001',
    customer_name: 'Rajesh Sharma',
    customer_email: 'rajesh@example.com',
    customer_phone: '+919876543210',
    shipping_address: '12 MG Road, Connaught Place, New Delhi - 110001',
    total_amount: 1500.00,
    payment_status: 'PAID',
    order_status: 'DISPATCHED',
    courier_name: 'Blue Dart Express',
    tracking_number: 'BD98234101',
    created_at: '2026-07-20 10:30:00',
    items: [
      { product_name: 'Kashmiri Organic Walnuts / Akhrot Giri (500gms)', quantity: 2, price: 750.00 }
    ]
  },
  {
    id: 1002,
    order_number: 'ORD-1002',
    customer_name: 'Priya Nair',
    customer_email: 'priya@example.com',
    customer_phone: '+919812345678',
    shipping_address: '45 Marine Drive, Kochi, Kerala - 682001',
    total_amount: 850.00,
    payment_status: 'PAID',
    order_status: 'DELIVERED',
    courier_name: 'Delhivery',
    tracking_number: 'DL88392019',
    created_at: '2026-07-18 14:15:00',
    items: [
      { product_name: 'Kashmiri Mountain Garlic / Lahsun (500gms)', quantity: 1, price: 850.00 }
    ]
  }
];

export const contactMessages = [
  {
    id: 1,
    name: 'Amit Deshmukh',
    email: 'amit@gmail.com',
    phone: '+919822011223',
    subject: 'Bulk Wholesale Inquiry',
    message: 'Hello, I would like to inquire about bulk wholesale pricing for 50kg of Kashmiri Walnut Kernels for my dry fruit store in Pune.',
    created_at: '2026-07-21 09:15:00'
  }
];

export const settings = {
  site_name: 'Deenz Organics',
  site_email: 'dr.deenmohd@gmail.com',
  contact_phone: '+91 60060 49016',
  secondary_phone: '+91 60050 92150',
  address: 'Main Office: Wanpora, Kulgam, J&K - 192231, India',
  razorpay_key_id: 'rzp_test_deenz_organics_mock'
};
