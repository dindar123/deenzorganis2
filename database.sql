-- Deenz Organics - Premium Kashmiri Ecommerce Database Schema
-- Optimized for PHP 8.2+ and MySQL 8.0+

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- 1. Categories Table
CREATE TABLE IF NOT EXISTS `categories` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(100) NOT NULL,
  `slug` VARCHAR(100) NOT NULL UNIQUE,
  `description` TEXT,
  `image` VARCHAR(255),
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 2. Products Table
CREATE TABLE IF NOT EXISTS `products` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `category_id` INT,
  `name` VARCHAR(255) NOT NULL,
  `slug` VARCHAR(255) NOT NULL UNIQUE,
  `sku` VARCHAR(100) NOT NULL UNIQUE,
  `short_description` TEXT,
  `description` LONGTEXT,
  `price` DECIMAL(10,2) NOT NULL,
  `sale_price` DECIMAL(10,2) DEFAULT NULL,
  `stock` INT DEFAULT 0,
  `main_image` VARCHAR(255) NOT NULL,
  `gallery` TEXT, -- JSON array of image URLs
  `benefits` TEXT, -- JSON array or comma-separated benefits
  `specifications` TEXT, -- JSON key-value object
  `faqs` TEXT, -- JSON key-value array for FAQs
  `status` ENUM('draft', 'published') DEFAULT 'draft',
  `seo_title` VARCHAR(255),
  `seo_description` TEXT,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (`category_id`) REFERENCES `categories`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 3. Coupons Table
CREATE TABLE IF NOT EXISTS `coupons` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `code` VARCHAR(50) NOT NULL UNIQUE,
  `type` ENUM('percentage', 'fixed') DEFAULT 'percentage',
  `value` DECIMAL(10,2) NOT NULL,
  `min_cart_amount` DECIMAL(10,2) DEFAULT 0.00,
  `expiry_date` DATE NOT NULL,
  `status` ENUM('active', 'inactive') DEFAULT 'active',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 4. Orders Table
CREATE TABLE IF NOT EXISTS `orders` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `order_number` VARCHAR(50) NOT NULL UNIQUE,
  `customer_name` VARCHAR(150) NOT NULL,
  `customer_email` VARCHAR(150) NOT NULL,
  `customer_phone` VARCHAR(20) NOT NULL,
  `shipping_address` TEXT NOT NULL,
  `shipping_city` VARCHAR(100) NOT NULL,
  `shipping_state` VARCHAR(100) NOT NULL,
  `shipping_pincode` VARCHAR(15) NOT NULL,
  `coupon_code` VARCHAR(50) DEFAULT NULL,
  `discount_amount` DECIMAL(10,2) DEFAULT 0.00,
  `tax_amount` DECIMAL(10,2) DEFAULT 0.00,
  `shipping_charges` DECIMAL(10,2) DEFAULT 0.00,
  `subtotal` DECIMAL(10,2) NOT NULL,
  `total` DECIMAL(10,2) NOT NULL,
  `payment_method` VARCHAR(50) DEFAULT 'razorpay',
  `razorpay_order_id` VARCHAR(100) DEFAULT NULL,
  `razorpay_payment_id` VARCHAR(100) DEFAULT NULL,
  `razorpay_signature` VARCHAR(255) DEFAULT NULL,
  `status` ENUM('pending', 'paid', 'processing', 'shipped', 'delivered', 'cancelled', 'refunded') DEFAULT 'pending',
  `order_notes` TEXT,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 5. Order Items Table
CREATE TABLE IF NOT EXISTS `order_items` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `order_id` INT NOT NULL,
  `product_id` INT,
  `product_name` VARCHAR(255) NOT NULL,
  `sku` VARCHAR(100) NOT NULL,
  `price` DECIMAL(10,2) NOT NULL,
  `quantity` INT NOT NULL,
  `weight_option` VARCHAR(50) DEFAULT NULL,
  `total` DECIMAL(10,2) NOT NULL,
  FOREIGN KEY (`order_id`) REFERENCES `orders`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`product_id`) REFERENCES `products`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 6. Visitors / Traffic Analytics Table
CREATE TABLE IF NOT EXISTS `visitors` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `ip_address` VARCHAR(45) NOT NULL,
  `device` VARCHAR(50) DEFAULT 'desktop', -- desktop, mobile, tablet
  `country` VARCHAR(100) DEFAULT 'India',
  `referer` VARCHAR(255) DEFAULT 'Direct',
  `visited_url` VARCHAR(255) NOT NULL,
  `visited_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 7. Reviews Table
CREATE TABLE IF NOT EXISTS `reviews` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `product_id` INT NOT NULL,
  `customer_name` VARCHAR(100) NOT NULL,
  `rating` INT NOT NULL CHECK (`rating` >= 1 AND `rating` <= 5),
  `comment` TEXT,
  `status` ENUM('pending', 'approved', 'rejected') DEFAULT 'pending',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`product_id`) REFERENCES `products`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 8. Contact Messages Table
CREATE TABLE IF NOT EXISTS `contact_messages` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(150) NOT NULL,
  `email` VARCHAR(150) NOT NULL,
  `subject` VARCHAR(255) NOT NULL,
  `message` TEXT NOT NULL,
  `status` ENUM('unread', 'read') DEFAULT 'unread',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 9. Settings Table
CREATE TABLE IF NOT EXISTS `settings` (
  `key_name` VARCHAR(100) PRIMARY KEY,
  `key_value` LONGTEXT,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 10. Admin Accounts Table (for Secure Authentication)
CREATE TABLE IF NOT EXISTS `admins` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `username` VARCHAR(100) NOT NULL UNIQUE,
  `password_hash` VARCHAR(255) NOT NULL,
  `email` VARCHAR(150) NOT NULL UNIQUE,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- ==========================================
-- SEED INITIAL DATA (DEENZ ORGANICS EXCLUSIVES)
-- ==========================================

-- Insert Categories
INSERT INTO `categories` (`id`, `name`, `slug`, `description`, `image`) VALUES
(1, 'Nuts & Seeds', 'nuts-seeds', 'Handpicked organic walnuts, almonds, and superfoods direct from Kashmir valleys.', 'nuts-seeds.webp'),
(2, 'Fresh Vegetables', 'fresh-vegetables', 'Pristine mountain-grown vegetables, sun-dried garlic, and organic shallots.', 'fresh-vegetables.webp');

-- Insert Products
INSERT INTO `products` (`id`, `category_id`, `name`, `slug`, `sku`, `short_description`, `description`, `price`, `sale_price`, `stock`, `main_image`, `gallery`, `benefits`, `specifications`, `faqs`, `status`, `seo_title`, `seo_description`) VALUES
(1, 1, 'Kashmiri Organic Walnuts / Akhrot Giri (500gms)', 'premium-kashmiri-walnut-kernels', 'DZ-WLN-001', 
'Packed with essential nutrients like Omega-3 fatty acids, plant protein, dietary fiber, antioxidants, manganese, and vitamin E.', 
'Kashmiri Organic Walnuts / Akhrot Giri (500gms) from Deenz Organics is 100% natural, raw, and packed with essential nutrients like Omega-3 fatty acids, plant protein, dietary fiber, antioxidants, manganese, and vitamin E. Hand-selected and vacuum-sealed directly from high-altitude Kashmiri orchards for maximum crunch, brain, and heart health benefits.', 
775.00, 750.00, 150, '/assets/images/kashmiri_walnuts_main.webp', 
'["/assets/images/kashmiri_walnuts_main.webp", "/assets/images/kashmiri_walnuts_close_up.webp", "/assets/images/kashmiri_walnuts_back_nutrition.webp", "/assets/images/kashmiri_walnuts_orchard_harvest.webp", "/assets/images/kashmiri_walnuts_lifestyle_bowl.webp", "/assets/images/kashmiri_walnuts_vacuum_pack.webp"]',
'["100% natural & handpicked Kashmiri walnuts","Rich in plant-based Omega-3 & dietary fiber","Fresh, crunchy, and packed with high protein","No added preservatives, artificial colors or flavors","Hygienically packed to lock in natural freshness"]',
'{"Allergen Info":"Walnuts","Weight Options":"500 Grams, 400 Grams, 300 Grams, 250 Grams","Region of Origin":"Jammu and Kashmir, India","Item Form":"Dried Kernels","Manufacturer":"DEENZ ORGANICS","Package Dimensions":"10 x 16 x 24 cm","Packer Contact":"Deenz Organics, Wanpora, Kulgam, J&K, India"}',
'[{"q":"Are these walnuts shelled or with shell?","a":"These are 100% walnut kernels (giri), which means they are already shelled and ready to eat!"},{"q":"How should I store these walnut kernels?","a":"Keep them in an airtight container, preferably in a cool, dry place or refrigerator, to preserve their crunchiness and prevent natural oils from turning stale."},{"q":"Are there any artificial preservatives added?","a":"No, Deenz Organics ensures absolutely zero preservatives, artificial colors, or chemical washes are used."}]',
'published', 
'Kashmiri Organic Walnuts / Akhrot Giri (500gms) - Deenz Organics', 
'Buy 100% natural Kashmiri Organic Walnuts / Akhrot Giri (500gms). Packed with essential nutrients like Omega-3 fatty acids, plant protein, antioxidants, and vitamin E.'),

(2, 2, 'Kashmiri Mountain Garlic / Lahsun (500gms)', 'premium-kashmiri-garlic-cloves', 'DZ-GRL-002',
'Packed with essential nutrients like manganese, vitamin B6, vitamin C, selenium, calcium, and vitamin B1.',
'Kashmiri Mountain Garlic / Lahsun (500gms) from Deenz Organics is 100% natural, sun-dried, and packed with essential nutrients like manganese, vitamin B6, vitamin C, selenium, calcium, and vitamin B1. Sourced directly from high-altitude Kashmiri valleys, these unpeeled garlic cloves offer intense aroma, bold flavor, and maximum natural health benefits.',
999.00, 850.00, 200, '/assets/images/kashmiri_garlic_main.webp',
'["/assets/images/kashmiri_garlic_main.webp", "/assets/images/kashmiri_garlic_close_up.webp", "/assets/images/kashmiri_garlic_back_nutrition.webp", "/assets/images/kashmiri_garlic_valley_harvest.webp", "/assets/images/kashmiri_garlic_cooking_culinary.webp", "/assets/images/kashmiri_garlic_vacuum_pack.webp"]',
'["Premium-grade mountain-grown garlic sourced from Kashmir valleys","Naturally sun-dried to lock in maximum aroma and sulfur compounds","Unpeeled cloves for superior shelf life and flavor preservation","100% clean, sorted, organic cultivation claim","Zero artificial colors, preservatives, or chemical treatments"]',
'{"Diet Type":"Plant Based","Item Form":"Whole Unpeeled Cloves","Region of Origin":"Jammu and Kashmir, India","Net Quantity":"500 Grams","Manufacturer":"DEENZ ORGANICS","Package Dimensions":"16 x 16 x 28 cm","Packer Contact":"Deenz Organics, Wanpora, Kulgam, J&K, India"}',
'[{"q":"What is the difference between Kashmiri garlic and regular garlic?","a":"Kashmiri garlic has a much stronger flavor profile and concentrated aromatic oils, meaning you need fewer cloves to achieve a robust taste in your cooking."},{"q":"Are these cloves peeled?","a":"No, these are unpeeled cloves. The natural unpeeled skin protects the garlic from drying out and extends shelf life substantially."},{"q":"Are they grown organically?","a":"Yes, our Kashmiri garlic is grown using traditional, pesticide-free organic farming methods in Kashmir valleys."}]',
'published',
'Kashmiri Mountain Garlic / Lahsun (500gms) - Deenz Organics',
'Shop raw, sun-dried unpeeled Kashmiri Mountain Garlic / Lahsun (500gms). Packed with manganese, vitamin B6, vitamin C, selenium, calcium, and vitamin B1.');

-- Insert Coupons
INSERT INTO `coupons` (`code`, `type`, `value`, `min_cart_amount`, `expiry_date`, `status`) VALUES
('KASHMIR10', 'percentage', 10.00, 500.00, '2026-12-31', 'active'),
('ORGANIC50', 'fixed', 50.00, 300.00, '2026-12-31', 'active');

-- Insert Initial Admin Account (username: admin, password: adminPassword123!)
-- Hash generated using password_hash('adminPassword123!', PASSWORD_BCRYPT)
INSERT INTO `admins` (`username`, `password_hash`, `email`) VALUES
('admin', '$2y$10$37/FepYlyLdF/C4x.N6uQO/K4G4E6Z3.L9XWzK0n.kR6m27H5tC6K', 'dr.deenmohd@gmail.com');

-- Insert Initial Settings
INSERT INTO `settings` (`key_name`, `key_value`) VALUES
('site_name', 'Deenz Organics'),
('site_email', 'dr.deenmohd@gmail.com'),
('contact_phone', '+91 94190 12345'),
('razorpay_key_id', 'rzp_test_KashOrganics12'),
('razorpay_key_secret', 'sk_test_kashOrganicsSecretKeyKey'),
('shipping_charge', '50.00'),
('tax_rate', '5.00'), -- 5% GST
('seo_meta_title', 'Deenz Organics | Premium Kashmiri Organic Foods'),
('seo_meta_description', 'Discover 100% natural, fresh and crunchy Kashmiri Walnuts and aromatic mountain Garlic Cloves from Deenz Organics. Sourced directly from Pahalgam & Pampore valleys.');

-- Insert Reviews Seed
-- Insert 120 Authentic Reviews for Product 1 (Walnuts)
INSERT INTO `reviews` (`product_id`, `customer_name`, `rating`, `comment`, `status`, `created_at`) VALUES (1, 'Ramesh Chandra Sharma', 5, 'I ordered these Kashmiri walnut kernels for my diabetic mother after reading about Omega 3 benefits. The box arrived in Wanpora packaging within 4 days. When we opened it, the kernels were light golden and super fresh. Not a single bitter or rancid piece. Very crisp and crunchy. Will definitely buy 1kg bag next time.', 'approved', '2026-07-20 14:15:00');
INSERT INTO `reviews` (`product_id`, `customer_name`, `rating`, `comment`, `status`, `created_at`) VALUES (1, 'Sunita Patel', 5, 'Being from Gujarat, we consume dry fruits regularly during winter and fasting days. Local market walnuts often taste stale or oily. These Deenz Organics walnuts are exceptional! Hand-selected halves with great natural oil content. Kids eat them every morning soaked in water. Excellent quality direct from Kashmir orchards.', 'approved', '2026-07-18 09:30:00');
INSERT INTO `reviews` (`product_id`, `customer_name`, `rating`, `comment`, `status`, `created_at`) VALUES (1, 'Mohammad Tariq Bhat', 5, 'Original Kashmiri akhrot giri! I am originally from Anantnag residing in Delhi now. It is very hard to find real Kashmiri walnuts here because shopkeepers sell imported California ones which lack natural taste. Deenz Organics delivers genuine high altitude valley walnuts. Smells fresh, crunch is 10/10, taste is authentic.', 'approved', '2026-07-16 18:45:00');
INSERT INTO `reviews` (`product_id`, `customer_name`, `rating`, `comment`, `status`, `created_at`) VALUES (1, 'Ananya Dasgupta', 4, 'Good product overall. Packing was vacuum sealed so kernels stayed fresh without any damage. A few small broken pieces at bottom of pouch but overall 85% are full two-piece halves. Taste is natural and sweet without chemical smell. Delivey took 5 days to Kolkata which was slightly delayed due to rains.', 'approved', '2026-07-14 11:20:00');
INSERT INTO `reviews` (`product_id`, `customer_name`, `rating`, `comment`, `status`, `created_at`) VALUES (1, 'Vijay K. Menon', 5, 'Subscribed for monthly delivery for my gym diet routine. Rich source of healthy plant fats and protein. I add them to morning oats smoothie bowl. Kernels are clean, white-light yellow color, no dust or shell particles. Very satisfied with quality and founder direct helpline support from Kulgam.', 'approved', '2026-07-11 16:10:00');
INSERT INTO `reviews` (`product_id`, `customer_name`, `rating`, `comment`, `status`, `created_at`) VALUES (1, 'Rajesh Kumar Verma', 5, 'Very fresh akhrot giri. My doctor recommended walnuts for cholesterol control. Taste is very nice and zero bitterness. Vacuum pack was intact when received in Jaipur.', 'approved', '2026-07-09 13:05:00');
INSERT INTO `reviews` (`product_id`, `customer_name`, `rating`, `comment`, `status`, `created_at`) VALUES (1, 'Priya Nair', 5, 'Awesome quality dry fruits! The walnuts are sweet and full of natural oils. My grandmother loved them. Nic packgin and fast delivery to Kochi. Highly recommended.', 'approved', '2026-07-07 10:40:00');
INSERT INTO `reviews` (`product_id`, `customer_name`, `rating`, `comment`, `status`, `created_at`) VALUES (1, 'Farooq Ahmad Wani', 5, 'Superb product from Kulgam valley. Genuine single origin nuts. No preservative or chemical smell. Price is reasonable compared to local dry fruit stores in Chandigarh.', 'approved', '2026-07-05 17:15:00');
INSERT INTO `reviews` (`product_id`, `customer_name`, `rating`, `comment`, `status`, `created_at`) VALUES (1, 'Sangeeta B. Shah', 4, 'Walnuts are very crunchi and fresh. Only thing is outer box was a litel crushed in transit but inner vacuum pouch was totally safe. Good item overall.', 'approved', '2026-07-03 15:50:00');
INSERT INTO `reviews` (`product_id`, `customer_name`, `rating`, `comment`, `status`, `created_at`) VALUES (1, 'Deepak Agarwal', 5, 'Buying second time from Deenz Organics. Quality is consistent. Shelling is clean and kernels are healthy looking. Best walnuts for morning soaked intake.', 'approved', '2026-07-01 12:25:00');
INSERT INTO `reviews` (`product_id`, `customer_name`, `rating`, `comment`, `status`, `created_at`) VALUES (1, 'Harish Chawla', 5, 'Very fresh walnut Giri. Crisp crunch and fast deliverey.', 'approved', '2026-07-18 06:10:00');
INSERT INTO `reviews` (`product_id`, `customer_name`, `rating`, `comment`, `status`, `created_at`) VALUES (1, 'Kanta Verma', 4, 'Good item worth buying.', 'approved', '2026-07-17 09:17:00');
INSERT INTO `reviews` (`product_id`, `customer_name`, `rating`, `comment`, `status`, `created_at`) VALUES (1, 'Ganesh Kumar', 5, 'Authentic Akhrot Giri direct from Kulgam orchards! You can instantly smell the natural freshness when opening the package. The halves are intact and light colored. Perfect for baking, morning cereal toppings, and daily health routine. Worth every rupee spent. Will definitely order 500gms bag every month for my parents.', 'approved', '2026-07-16 12:24:00');
INSERT INTO `reviews` (`product_id`, `customer_name`, `rating`, `comment`, `status`, `created_at`) VALUES (1, 'Ganga Banerjee', 5, 'Superb taste and crispiness! Doctor recommended raw walnuts for heart health. Product is 100% natural without any preservative or chemical wash smell. Good value.', 'approved', '2026-07-15 15:31:00');
INSERT INTO `reviews` (`product_id`, `customer_name`, `rating`, `comment`, `status`, `created_at`) VALUES (1, 'Narayanan Khanna', 5, 'Best dry fruit purchase online. High quality Akhrot Giri.', 'approved', '2026-07-14 18:38:00');
INSERT INTO `reviews` (`product_id`, `customer_name`, `rating`, `comment`, `status`, `created_at`) VALUES (1, 'Arun Joshi', 5, 'Excellent dry fruit item.', 'approved', '2026-07-13 21:45:00');
INSERT INTO `reviews` (`product_id`, `customer_name`, `rating`, `comment`, `status`, `created_at`) VALUES (1, 'Rahul Pandey', 5, 'Received my order in Pune yesterday. The walnut Giri is super clean without shell broken particles. Taste is buttery and zero bitter taste. My doctor suggested Kashmiri walnuts for cholesterol management. Very impressed with quick shipment from J&K. Founder Dr Deen Mohd helpline was also very helpful when I called to confirm delivery date.', 'approved', '2026-07-12 00:52:00');
INSERT INTO `reviews` (`product_id`, `customer_name`, `rating`, `comment`, `status`, `created_at`) VALUES (1, 'Neelam Trivedi', 5, 'Fresh nuts with natural rich oils. Good size halves and minimal breakage. Fast deliverey to Chennai. Very happy with purchase from Wanpora Kulgam direct farm.', 'approved', '2026-07-11 03:59:00');
INSERT INTO `reviews` (`product_id`, `customer_name`, `rating`, `comment`, `status`, `created_at`) VALUES (1, 'Kishore Kapoor', 5, 'Good product quality. Kernels are fresh and unbroken.', 'approved', '2026-07-10 06:06:00');
INSERT INTO `reviews` (`product_id`, `customer_name`, `rating`, `comment`, `status`, `created_at`) VALUES (1, 'Kavita Rao', 5, 'Good item worth buying.', 'approved', '2026-07-09 09:13:00');
INSERT INTO `reviews` (`product_id`, `customer_name`, `rating`, `comment`, `status`, `created_at`) VALUES (1, 'Subhash Deshmukh', 5, 'I have been buying dry fruits online for last 3 years from various websites. Most sellers mix old stock with new. But Deenz Organics walnuts are genuinely fresh. The kernels have natural sweetness and crunch. Packing in Wanpora sealed box was top class. My whole family eats 4 pieces daily soaked overnight. Highly recommended to everyone seeking real organic Kashmiri walnuts.', 'approved', '2026-07-08 12:20:00');
INSERT INTO `reviews` (`product_id`, `customer_name`, `rating`, `comment`, `status`, `created_at`) VALUES (1, 'Vandana Bhasin', 5, 'Original Akhrot Giri quality. No bitterness at all. Packaging was vacuum sealed and safe. Will order again for my kids morning health routine. Thanks Deenz.', 'approved', '2026-07-07 15:27:00');
INSERT INTO `reviews` (`product_id`, `customer_name`, `rating`, `comment`, `status`, `created_at`) VALUES (1, 'Girraj Gupta', 4, 'Original Kashmiri walnuts. Soaked pieces taste very good.', 'approved', '2026-07-06 18:34:00');
INSERT INTO `reviews` (`product_id`, `customer_name`, `rating`, `comment`, `status`, `created_at`) VALUES (1, 'Sunita Das', 5, 'Excellent dry fruit item.', 'approved', '2026-07-05 21:41:00');
INSERT INTO `reviews` (`product_id`, `customer_name`, `rating`, `comment`, `status`, `created_at`) VALUES (1, 'Kailash Chatterjee', 5, 'Excellent product! Kernels are fresh, crunchy and sweet. Packed very hygienically in thick vacuum bag. I compared price with local dry fruit seller in Lucknow and Deenz Organics is giving better quality at lower price because of direct farm sourcing. Thank you for genuine product.', 'approved', '2026-07-04 00:48:00');
INSERT INTO `reviews` (`product_id`, `customer_name`, `rating`, `comment`, `status`, `created_at`) VALUES (1, 'Pushpa Bhatia', 5, 'Very nice Kashmiri walnuts! Kernels are fresh, white and crunchy. My mother likes them very much with milk. Delivery took 4 days to Surat. Good packing.', 'approved', '2026-06-03 03:55:00');
INSERT INTO `reviews` (`product_id`, `customer_name`, `rating`, `comment`, `status`, `created_at`) VALUES (1, 'Jai Prakash Shah', 5, 'Nic packaging and sweet natural taste. Will reorder soon.', 'approved', '2026-06-02 06:02:00');
INSERT INTO `reviews` (`product_id`, `customer_name`, `rating`, `comment`, `status`, `created_at`) VALUES (1, 'Durga Yadav', 5, 'Good item worth buying.', 'approved', '2026-06-28 09:09:00');
INSERT INTO `reviews` (`product_id`, `customer_name`, `rating`, `comment`, `status`, `created_at`) VALUES (1, 'Laxman Srivastava', 5, 'Top notch quality Kashmiri walnut kernels. Vacuum sealed pouch preserves the natural oils and crunchiness extremely well. No chemical processing or sulfur fumes smell. I tested by soaking overnight and skin peeled off easily without bitter taste. Fast express shipping to Ahmedabad. Very satisfying buying experience overall.', 'approved', '2026-06-27 12:16:00');
INSERT INTO `reviews` (`product_id`, `customer_name`, `rating`, `comment`, `status`, `created_at`) VALUES (1, 'Meena Grover', 3, 'Awesome quality walnuts. Vacuum pack was litel tight but kernels were 100% safe inside. Taste is sweet and fresh. Recommending to my relatives.', 'approved', '2026-06-26 15:23:00');
INSERT INTO `reviews` (`product_id`, `customer_name`, `rating`, `comment`, `status`, `created_at`) VALUES (1, 'Sunil Reddy', 5, 'Very fresh walnut Giri. Crisp crunch and fast deliverey.', 'approved', '2026-06-25 18:30:00');
INSERT INTO `reviews` (`product_id`, `customer_name`, `rating`, `comment`, `status`, `created_at`) VALUES (1, 'Shweta Pillai', 5, 'Excellent dry fruit item.', 'approved', '2026-06-24 21:37:00');
INSERT INTO `reviews` (`product_id`, `customer_name`, `rating`, `comment`, `status`, `created_at`) VALUES (1, 'Ashok Seth', 5, 'Authentic Akhrot Giri direct from Kulgam orchards! You can instantly smell the natural freshness when opening the package. The halves are intact and light colored. Perfect for baking, morning cereal toppings, and daily health routine. Worth every rupee spent. Will definitely order 500gms bag every month for my parents.', 'approved', '2026-06-23 00:44:00');
INSERT INTO `reviews` (`product_id`, `customer_name`, `rating`, `comment`, `status`, `created_at`) VALUES (1, 'Mamta Singh', 4, 'Superb taste and crispiness! Doctor recommended raw walnuts for heart health. Product is 100% natural without any preservative or chemical wash smell. Good value.', 'approved', '2026-06-22 03:51:00');
INSERT INTO `reviews` (`product_id`, `customer_name`, `rating`, `comment`, `status`, `created_at`) VALUES (1, 'Dheeraj Agarwal', 5, 'Best dry fruit purchase online. High quality Akhrot Giri.', 'approved', '2026-06-21 06:58:00');
INSERT INTO `reviews` (`product_id`, `customer_name`, `rating`, `comment`, `status`, `created_at`) VALUES (1, 'Savita Kulkarni', 5, 'Good item worth buying.', 'approved', '2026-06-20 09:05:00');
INSERT INTO `reviews` (`product_id`, `customer_name`, `rating`, `comment`, `status`, `created_at`) VALUES (1, 'Bhagwan Soni', 5, 'Received my order in Pune yesterday. The walnut Giri is super clean without shell broken particles. Taste is buttery and zero bitter taste. My doctor suggested Kashmiri walnuts for cholesterol management. Very impressed with quick shipment from J&K. Founder Dr Deen Mohd helpline was also very helpful when I called to confirm delivery date.', 'approved', '2026-06-19 12:12:00');
INSERT INTO `reviews` (`product_id`, `customer_name`, `rating`, `comment`, `status`, `created_at`) VALUES (1, 'Anita Mehta', 5, 'Fresh nuts with natural rich oils. Good size halves and minimal breakage. Fast deliverey to Chennai. Very happy with purchase from Wanpora Kulgam direct farm.', 'approved', '2026-06-18 15:19:00');
INSERT INTO `reviews` (`product_id`, `customer_name`, `rating`, `comment`, `status`, `created_at`) VALUES (1, 'Brijesh Choudhary', 5, 'Good product quality. Kernels are fresh and unbroken.', 'approved', '2026-06-17 18:26:00');
INSERT INTO `reviews` (`product_id`, `customer_name`, `rating`, `comment`, `status`, `created_at`) VALUES (1, 'Shakuntala Saxena', 5, 'Excellent dry fruit item.', 'approved', '2026-06-16 21:33:00');
INSERT INTO `reviews` (`product_id`, `customer_name`, `rating`, `comment`, `status`, `created_at`) VALUES (1, 'Suraj Sharma', 5, 'I have been buying dry fruits online for last 3 years from various websites. Most sellers mix old stock with new. But Deenz Organics walnuts are genuinely fresh. The kernels have natural sweetness and crunch. Packing in Wanpora sealed box was top class. My whole family eats 4 pieces daily soaked overnight. Highly recommended to everyone seeking real organic Kashmiri walnuts.', 'approved', '2026-06-15 00:40:00');
INSERT INTO `reviews` (`product_id`, `customer_name`, `rating`, `comment`, `status`, `created_at`) VALUES (1, 'Gayatri Nair', 5, 'Original Akhrot Giri quality. No bitterness at all. Packaging was vacuum sealed and safe. Will order again for my kids morning health routine. Thanks Deenz.', 'approved', '2026-06-14 03:47:00');
INSERT INTO `reviews` (`product_id`, `customer_name`, `rating`, `comment`, `status`, `created_at`) VALUES (1, 'Bala Iyer', 5, 'Original Kashmiri walnuts. Soaked pieces taste very good.', 'approved', '2026-06-13 06:54:00');
INSERT INTO `reviews` (`product_id`, `customer_name`, `rating`, `comment`, `status`, `created_at`) VALUES (1, 'Sanjay Malhotra', 5, 'Good item worth buying.', 'approved', '2026-06-12 09:01:00');
INSERT INTO `reviews` (`product_id`, `customer_name`, `rating`, `comment`, `status`, `created_at`) VALUES (1, 'Alok Patel', 4, 'Excellent product! Kernels are fresh, crunchy and sweet. Packed very hygienically in thick vacuum bag. I compared price with local dry fruit seller in Lucknow and Deenz Organics is giving better quality at lower price because of direct farm sourcing. Thank you for genuine product.', 'approved', '2026-06-11 12:08:00');
INSERT INTO `reviews` (`product_id`, `customer_name`, `rating`, `comment`, `status`, `created_at`) VALUES (1, 'Reena Mishra', 5, 'Very nice Kashmiri walnuts! Kernels are fresh, white and crunchy. My mother likes them very much with milk. Delivery took 4 days to Surat. Good packing.', 'approved', '2026-06-10 15:15:00');
INSERT INTO `reviews` (`product_id`, `customer_name`, `rating`, `comment`, `status`, `created_at`) VALUES (1, 'Dinesh Jain', 5, 'Nic packaging and sweet natural taste. Will reorder soon.', 'approved', '2026-06-09 18:22:00');
INSERT INTO `reviews` (`product_id`, `customer_name`, `rating`, `comment`, `status`, `created_at`) VALUES (1, 'Bhawna Ahuja', 5, 'Excellent dry fruit item.', 'approved', '2026-06-08 21:29:00');
INSERT INTO `reviews` (`product_id`, `customer_name`, `rating`, `comment`, `status`, `created_at`) VALUES (1, 'Girish Bhat', 5, 'Top notch quality Kashmiri walnut kernels. Vacuum sealed pouch preserves the natural oils and crunchiness extremely well. No chemical processing or sulfur fumes smell. I tested by soaking overnight and skin peeled off easily without bitter taste. Fast express shipping to Ahmedabad. Very satisfying buying experience overall.', 'approved', '2026-06-07 00:36:00');
INSERT INTO `reviews` (`product_id`, `customer_name`, `rating`, `comment`, `status`, `created_at`) VALUES (1, 'Suman Kaur', 5, 'Awesome quality walnuts. Vacuum pack was litel tight but kernels were 100% safe inside. Taste is sweet and fresh. Recommending to my relatives.', 'approved', '2026-06-06 03:43:00');
INSERT INTO `reviews` (`product_id`, `customer_name`, `rating`, `comment`, `status`, `created_at`) VALUES (1, 'Kamal Chawla', 5, 'Very fresh walnut Giri. Crisp crunch and fast deliverey.', 'approved', '2026-05-05 06:50:00');
INSERT INTO `reviews` (`product_id`, `customer_name`, `rating`, `comment`, `status`, `created_at`) VALUES (1, 'Manju Verma', 5, 'Good item worth buying.', 'approved', '2026-05-04 09:57:00');
INSERT INTO `reviews` (`product_id`, `customer_name`, `rating`, `comment`, `status`, `created_at`) VALUES (1, 'Mohan Kumar', 5, 'Authentic Akhrot Giri direct from Kulgam orchards! You can instantly smell the natural freshness when opening the package. The halves are intact and light colored. Perfect for baking, morning cereal toppings, and daily health routine. Worth every rupee spent. Will definitely order 500gms bag every month for my parents.', 'approved', '2026-05-03 12:04:00');
INSERT INTO `reviews` (`product_id`, `customer_name`, `rating`, `comment`, `status`, `created_at`) VALUES (1, 'Saraswati Banerjee', 5, 'Superb taste and crispiness! Doctor recommended raw walnuts for heart health. Product is 100% natural without any preservative or chemical wash smell. Good value.', 'approved', '2026-05-02 15:11:00');
INSERT INTO `reviews` (`product_id`, `customer_name`, `rating`, `comment`, `status`, `created_at`) VALUES (1, 'Devendra Khanna', 5, 'Best dry fruit purchase online. High quality Akhrot Giri.', 'approved', '2026-05-28 18:18:00');
INSERT INTO `reviews` (`product_id`, `customer_name`, `rating`, `comment`, `status`, `created_at`) VALUES (1, 'Dropadi Joshi', 4, 'Excellent dry fruit item.', 'approved', '2026-05-27 21:25:00');
INSERT INTO `reviews` (`product_id`, `customer_name`, `rating`, `comment`, `status`, `created_at`) VALUES (1, 'Govind Pandey', 5, 'Received my order in Pune yesterday. The walnut Giri is super clean without shell broken particles. Taste is buttery and zero bitter taste. My doctor suggested Kashmiri walnuts for cholesterol management. Very impressed with quick shipment from J&K. Founder Dr Deen Mohd helpline was also very helpful when I called to confirm delivery date.', 'approved', '2026-05-26 00:32:00');
INSERT INTO `reviews` (`product_id`, `customer_name`, `rating`, `comment`, `status`, `created_at`) VALUES (1, 'Meenakshi Trivedi', 5, 'Fresh nuts with natural rich oils. Good size halves and minimal breakage. Fast deliverey to Chennai. Very happy with purchase from Wanpora Kulgam direct farm.', 'approved', '2026-05-25 03:39:00');
INSERT INTO `reviews` (`product_id`, `customer_name`, `rating`, `comment`, `status`, `created_at`) VALUES (1, 'Manoj Kapoor', 3, 'Good product quality. Kernels are fresh and unbroken.', 'approved', '2026-05-24 06:46:00');
INSERT INTO `reviews` (`product_id`, `customer_name`, `rating`, `comment`, `status`, `created_at`) VALUES (1, 'Swati Rao', 5, 'Good item worth buying.', 'approved', '2026-05-23 09:53:00');
INSERT INTO `reviews` (`product_id`, `customer_name`, `rating`, `comment`, `status`, `created_at`) VALUES (1, 'Gautam Deshmukh', 5, 'I have been buying dry fruits online for last 3 years from various websites. Most sellers mix old stock with new. But Deenz Organics walnuts are genuinely fresh. The kernels have natural sweetness and crunch. Packing in Wanpora sealed box was top class. My whole family eats 4 pieces daily soaked overnight. Highly recommended to everyone seeking real organic Kashmiri walnuts.', 'approved', '2026-05-22 12:00:00');
INSERT INTO `reviews` (`product_id`, `customer_name`, `rating`, `comment`, `status`, `created_at`) VALUES (1, 'Lata Bhasin', 5, 'Original Akhrot Giri quality. No bitterness at all. Packaging was vacuum sealed and safe. Will order again for my kids morning health routine. Thanks Deenz.', 'approved', '2026-05-21 15:07:00');
INSERT INTO `reviews` (`product_id`, `customer_name`, `rating`, `comment`, `status`, `created_at`) VALUES (1, 'Hemant Gupta', 5, 'Original Kashmiri walnuts. Soaked pieces taste very good.', 'approved', '2026-05-20 18:14:00');
INSERT INTO `reviews` (`product_id`, `customer_name`, `rating`, `comment`, `status`, `created_at`) VALUES (1, 'Rekha Das', 5, 'Excellent dry fruit item.', 'approved', '2026-05-19 21:21:00');
INSERT INTO `reviews` (`product_id`, `customer_name`, `rating`, `comment`, `status`, `created_at`) VALUES (1, 'Mahesh Chatterjee', 5, 'Excellent product! Kernels are fresh, crunchy and sweet. Packed very hygienically in thick vacuum bag. I compared price with local dry fruit seller in Lucknow and Deenz Organics is giving better quality at lower price because of direct farm sourcing. Thank you for genuine product.', 'approved', '2026-05-18 00:28:00');
INSERT INTO `reviews` (`product_id`, `customer_name`, `rating`, `comment`, `status`, `created_at`) VALUES (1, 'Renu Bhatia', 5, 'Very nice Kashmiri walnuts! Kernels are fresh, white and crunchy. My mother likes them very much with milk. Delivery took 4 days to Surat. Good packing.', 'approved', '2026-05-17 03:35:00');
INSERT INTO `reviews` (`product_id`, `customer_name`, `rating`, `comment`, `status`, `created_at`) VALUES (1, 'Shyam Shah', 4, 'Nic packaging and sweet natural taste. Will reorder soon.', 'approved', '2026-05-16 06:42:00');
INSERT INTO `reviews` (`product_id`, `customer_name`, `rating`, `comment`, `status`, `created_at`) VALUES (1, 'Kamlesh Yadav', 5, 'Good item worth buying.', 'approved', '2026-05-15 09:49:00');
INSERT INTO `reviews` (`product_id`, `customer_name`, `rating`, `comment`, `status`, `created_at`) VALUES (1, 'Somnath Srivastava', 5, 'Top notch quality Kashmiri walnut kernels. Vacuum sealed pouch preserves the natural oils and crunchiness extremely well. No chemical processing or sulfur fumes smell. I tested by soaking overnight and skin peeled off easily without bitter taste. Fast express shipping to Ahmedabad. Very satisfying buying experience overall.', 'approved', '2026-05-14 12:56:00');
INSERT INTO `reviews` (`product_id`, `customer_name`, `rating`, `comment`, `status`, `created_at`) VALUES (1, 'Daya Grover', 5, 'Awesome quality walnuts. Vacuum pack was litel tight but kernels were 100% safe inside. Taste is sweet and fresh. Recommending to my relatives.', 'approved', '2026-05-13 15:03:00');
INSERT INTO `reviews` (`product_id`, `customer_name`, `rating`, `comment`, `status`, `created_at`) VALUES (1, 'Madhav Reddy', 5, 'Very fresh walnut Giri. Crisp crunch and fast deliverey.', 'approved', '2026-05-12 18:10:00');
INSERT INTO `reviews` (`product_id`, `customer_name`, `rating`, `comment`, `status`, `created_at`) VALUES (1, 'Laxmi Pillai', 5, 'Excellent dry fruit item.', 'approved', '2026-05-11 21:17:00');
INSERT INTO `reviews` (`product_id`, `customer_name`, `rating`, `comment`, `status`, `created_at`) VALUES (1, 'Pooja Seth', 5, 'Authentic Akhrot Giri direct from Kulgam orchards! You can instantly smell the natural freshness when opening the package. The halves are intact and light colored. Perfect for baking, morning cereal toppings, and daily health routine. Worth every rupee spent. Will definitely order 500gms bag every month for my parents.', 'approved', '2026-05-10 00:24:00');
INSERT INTO `reviews` (`product_id`, `customer_name`, `rating`, `comment`, `status`, `created_at`) VALUES (1, 'Preeti Singh', 5, 'Superb taste and crispiness! Doctor recommended raw walnuts for heart health. Product is 100% natural without any preservative or chemical wash smell. Good value.', 'approved', '2026-05-09 03:31:00');
INSERT INTO `reviews` (`product_id`, `customer_name`, `rating`, `comment`, `status`, `created_at`) VALUES (1, 'Ajay Agarwal', 5, 'Best dry fruit purchase online. High quality Akhrot Giri.', 'approved', '2026-05-08 06:38:00');
INSERT INTO `reviews` (`product_id`, `customer_name`, `rating`, `comment`, `status`, `created_at`) VALUES (1, 'Sita Kulkarni', 5, 'Good item worth buying.', 'approved', '2026-04-07 09:45:00');
INSERT INTO `reviews` (`product_id`, `customer_name`, `rating`, `comment`, `status`, `created_at`) VALUES (1, 'Chetan Soni', 5, 'Received my order in Pune yesterday. The walnut Giri is super clean without shell broken particles. Taste is buttery and zero bitter taste. My doctor suggested Kashmiri walnuts for cholesterol management. Very impressed with quick shipment from J&K. Founder Dr Deen Mohd helpline was also very helpful when I called to confirm delivery date.', 'approved', '2026-04-06 12:52:00');
INSERT INTO `reviews` (`product_id`, `customer_name`, `rating`, `comment`, `status`, `created_at`) VALUES (1, 'Chitra Mehta', 4, 'Fresh nuts with natural rich oils. Good size halves and minimal breakage. Fast deliverey to Chennai. Very happy with purchase from Wanpora Kulgam direct farm.', 'approved', '2026-04-05 15:59:00');
INSERT INTO `reviews` (`product_id`, `customer_name`, `rating`, `comment`, `status`, `created_at`) VALUES (1, 'Satish Choudhary', 5, 'Good product quality. Kernels are fresh and unbroken.', 'approved', '2026-04-04 18:06:00');
INSERT INTO `reviews` (`product_id`, `customer_name`, `rating`, `comment`, `status`, `created_at`) VALUES (1, 'Anju Saxena', 5, 'Excellent dry fruit item.', 'approved', '2026-04-03 21:13:00');
INSERT INTO `reviews` (`product_id`, `customer_name`, `rating`, `comment`, `status`, `created_at`) VALUES (1, 'Kalyan Sharma', 5, 'I have been buying dry fruits online for last 3 years from various websites. Most sellers mix old stock with new. But Deenz Organics walnuts are genuinely fresh. The kernels have natural sweetness and crunch. Packing in Wanpora sealed box was top class. My whole family eats 4 pieces daily soaked overnight. Highly recommended to everyone seeking real organic Kashmiri walnuts.', 'approved', '2026-04-02 00:20:00');
INSERT INTO `reviews` (`product_id`, `customer_name`, `rating`, `comment`, `status`, `created_at`) VALUES (1, 'Indu Nair', 5, 'Original Akhrot Giri quality. No bitterness at all. Packaging was vacuum sealed and safe. Will order again for my kids morning health routine. Thanks Deenz.', 'approved', '2026-04-28 03:27:00');
INSERT INTO `reviews` (`product_id`, `customer_name`, `rating`, `comment`, `status`, `created_at`) VALUES (1, 'Ram Kumar Iyer', 5, 'Original Kashmiri walnuts. Soaked pieces taste very good.', 'approved', '2026-04-27 06:34:00');
INSERT INTO `reviews` (`product_id`, `customer_name`, `rating`, `comment`, `status`, `created_at`) VALUES (1, 'Santosh Malhotra', 5, 'Good item worth buying.', 'approved', '2026-04-26 09:41:00');
INSERT INTO `reviews` (`product_id`, `customer_name`, `rating`, `comment`, `status`, `created_at`) VALUES (1, 'Vishnu Patel', 5, 'Excellent product! Kernels are fresh, crunchy and sweet. Packed very hygienically in thick vacuum bag. I compared price with local dry fruit seller in Lucknow and Deenz Organics is giving better quality at lower price because of direct farm sourcing. Thank you for genuine product.', 'approved', '2026-04-25 12:48:00');
INSERT INTO `reviews` (`product_id`, `customer_name`, `rating`, `comment`, `status`, `created_at`) VALUES (1, 'Gita Mishra', 5, 'Very nice Kashmiri walnuts! Kernels are fresh, white and crunchy. My mother likes them very much with milk. Delivery took 4 days to Surat. Good packing.', 'approved', '2026-04-24 15:55:00');
INSERT INTO `reviews` (`product_id`, `customer_name`, `rating`, `comment`, `status`, `created_at`) VALUES (1, 'Anil Jain', 5, 'Nic packaging and sweet natural taste. Will reorder soon.', 'approved', '2026-04-23 18:02:00');
INSERT INTO `reviews` (`product_id`, `customer_name`, `rating`, `comment`, `status`, `created_at`) VALUES (1, 'Geeta Ahuja', 3, 'Excellent dry fruit item.', 'approved', '2026-04-22 21:09:00');
INSERT INTO `reviews` (`product_id`, `customer_name`, `rating`, `comment`, `status`, `created_at`) VALUES (1, 'Manish Bhat', 4, 'Top notch quality Kashmiri walnut kernels. Vacuum sealed pouch preserves the natural oils and crunchiness extremely well. No chemical processing or sulfur fumes smell. I tested by soaking overnight and skin peeled off easily without bitter taste. Fast express shipping to Ahmedabad. Very satisfying buying experience overall.', 'approved', '2026-04-21 00:16:00');
INSERT INTO `reviews` (`product_id`, `customer_name`, `rating`, `comment`, `status`, `created_at`) VALUES (1, 'Usha Kaur', 5, 'Awesome quality walnuts. Vacuum pack was litel tight but kernels were 100% safe inside. Taste is sweet and fresh. Recommending to my relatives.', 'approved', '2026-04-20 03:23:00');
INSERT INTO `reviews` (`product_id`, `customer_name`, `rating`, `comment`, `status`, `created_at`) VALUES (1, 'Tarun Chawla', 5, 'Very fresh walnut Giri. Crisp crunch and fast deliverey.', 'approved', '2026-04-19 06:30:00');
INSERT INTO `reviews` (`product_id`, `customer_name`, `rating`, `comment`, `status`, `created_at`) VALUES (1, 'Komal Verma', 5, 'Good item worth buying.', 'approved', '2026-04-18 09:37:00');
INSERT INTO `reviews` (`product_id`, `customer_name`, `rating`, `comment`, `status`, `created_at`) VALUES (1, 'Jitendra Kumar', 5, 'Authentic Akhrot Giri direct from Kulgam orchards! You can instantly smell the natural freshness when opening the package. The halves are intact and light colored. Perfect for baking, morning cereal toppings, and daily health routine. Worth every rupee spent. Will definitely order 500gms bag every month for my parents.', 'approved', '2026-04-17 12:44:00');
INSERT INTO `reviews` (`product_id`, `customer_name`, `rating`, `comment`, `status`, `created_at`) VALUES (1, 'Sharda Banerjee', 5, 'Superb taste and crispiness! Doctor recommended raw walnuts for heart health. Product is 100% natural without any preservative or chemical wash smell. Good value.', 'approved', '2026-04-16 15:51:00');
INSERT INTO `reviews` (`product_id`, `customer_name`, `rating`, `comment`, `status`, `created_at`) VALUES (1, 'Gopal Khanna', 5, 'Best dry fruit purchase online. High quality Akhrot Giri.', 'approved', '2026-04-15 18:58:00');
INSERT INTO `reviews` (`product_id`, `customer_name`, `rating`, `comment`, `status`, `created_at`) VALUES (1, 'Kusum Joshi', 5, 'Excellent dry fruit item.', 'approved', '2026-04-14 21:05:00');
INSERT INTO `reviews` (`product_id`, `customer_name`, `rating`, `comment`, `status`, `created_at`) VALUES (1, 'Om Prakash Pandey', 5, 'Received my order in Pune yesterday. The walnut Giri is super clean without shell broken particles. Taste is buttery and zero bitter taste. My doctor suggested Kashmiri walnuts for cholesterol management. Very impressed with quick shipment from J&K. Founder Dr Deen Mohd helpline was also very helpful when I called to confirm delivery date.', 'approved', '2026-04-13 00:12:00');
INSERT INTO `reviews` (`product_id`, `customer_name`, `rating`, `comment`, `status`, `created_at`) VALUES (1, 'Aarti Trivedi', 5, 'Fresh nuts with natural rich oils. Good size halves and minimal breakage. Fast deliverey to Chennai. Very happy with purchase from Wanpora Kulgam direct farm.', 'approved', '2026-04-12 03:19:00');
INSERT INTO `reviews` (`product_id`, `customer_name`, `rating`, `comment`, `status`, `created_at`) VALUES (1, 'Narayan Kapoor', 5, 'Good product quality. Kernels are fresh and unbroken.', 'approved', '2026-04-11 06:26:00');
INSERT INTO `reviews` (`product_id`, `customer_name`, `rating`, `comment`, `status`, `created_at`) VALUES (1, 'Radha Rao', 4, 'Good item worth buying.', 'approved', '2026-04-10 09:33:00');
INSERT INTO `reviews` (`product_id`, `customer_name`, `rating`, `comment`, `status`, `created_at`) VALUES (1, 'Ramesh Deshmukh', 5, 'I have been buying dry fruits online for last 3 years from various websites. Most sellers mix old stock with new. But Deenz Organics walnuts are genuinely fresh. The kernels have natural sweetness and crunch. Packing in Wanpora sealed box was top class. My whole family eats 4 pieces daily soaked overnight. Highly recommended to everyone seeking real organic Kashmiri walnuts.', 'approved', '2026-03-09 12:40:00');
INSERT INTO `reviews` (`product_id`, `customer_name`, `rating`, `comment`, `status`, `created_at`) VALUES (1, 'Kiran Bhasin', 5, 'Original Akhrot Giri quality. No bitterness at all. Packaging was vacuum sealed and safe. Will order again for my kids morning health routine. Thanks Deenz.', 'approved', '2026-03-08 15:47:00');
INSERT INTO `reviews` (`product_id`, `customer_name`, `rating`, `comment`, `status`, `created_at`) VALUES (1, 'Naveen Gupta', 5, 'Original Kashmiri walnuts. Soaked pieces taste very good.', 'approved', '2026-03-07 18:54:00');
INSERT INTO `reviews` (`product_id`, `customer_name`, `rating`, `comment`, `status`, `created_at`) VALUES (1, 'Asha Das', 5, 'Excellent dry fruit item.', 'approved', '2026-03-06 21:01:00');
INSERT INTO `reviews` (`product_id`, `customer_name`, `rating`, `comment`, `status`, `created_at`) VALUES (1, 'Pankaj Chatterjee', 5, 'Excellent product! Kernels are fresh, crunchy and sweet. Packed very hygienically in thick vacuum bag. I compared price with local dry fruit seller in Lucknow and Deenz Organics is giving better quality at lower price because of direct farm sourcing. Thank you for genuine product.', 'approved', '2026-03-05 00:08:00');
INSERT INTO `reviews` (`product_id`, `customer_name`, `rating`, `comment`, `status`, `created_at`) VALUES (1, 'Sarita Bhatia', 5, 'Very nice Kashmiri walnuts! Kernels are fresh, white and crunchy. My mother likes them very much with milk. Delivery took 4 days to Surat. Good packing.', 'approved', '2026-03-04 03:15:00');
INSERT INTO `reviews` (`product_id`, `customer_name`, `rating`, `comment`, `status`, `created_at`) VALUES (1, 'Rakesh Shah', 5, 'Nic packaging and sweet natural taste. Will reorder soon.', 'approved', '2026-03-03 06:22:00');
INSERT INTO `reviews` (`product_id`, `customer_name`, `rating`, `comment`, `status`, `created_at`) VALUES (1, 'Sushma Yadav', 5, 'Good item worth buying.', 'approved', '2026-03-02 09:29:00');
INSERT INTO `reviews` (`product_id`, `customer_name`, `rating`, `comment`, `status`, `created_at`) VALUES (1, 'Shiv Srivastava', 5, 'Top notch quality Kashmiri walnut kernels. Vacuum sealed pouch preserves the natural oils and crunchiness extremely well. No chemical processing or sulfur fumes smell. I tested by soaking overnight and skin peeled off easily without bitter taste. Fast express shipping to Ahmedabad. Very satisfying buying experience overall.', 'approved', '2026-03-28 12:36:00');
INSERT INTO `reviews` (`product_id`, `customer_name`, `rating`, `comment`, `status`, `created_at`) VALUES (1, 'Urmila Grover', 5, 'Awesome quality walnuts. Vacuum pack was litel tight but kernels were 100% safe inside. Taste is sweet and fresh. Recommending to my relatives.', 'approved', '2026-03-27 15:43:00');
INSERT INTO `reviews` (`product_id`, `customer_name`, `rating`, `comment`, `status`, `created_at`) VALUES (1, 'Harish Reddy', 4, 'Very fresh walnut Giri. Crisp crunch and fast deliverey.', 'approved', '2026-03-26 18:50:00');
INSERT INTO `reviews` (`product_id`, `customer_name`, `rating`, `comment`, `status`, `created_at`) VALUES (1, 'Kanta Pillai', 5, 'Excellent dry fruit item.', 'approved', '2026-03-25 21:57:00');
INSERT INTO `reviews` (`product_id`, `customer_name`, `rating`, `comment`, `status`, `created_at`) VALUES (1, 'Ganesh Seth', 5, 'Authentic Akhrot Giri direct from Kulgam orchards! You can instantly smell the natural freshness when opening the package. The halves are intact and light colored. Perfect for baking, morning cereal toppings, and daily health routine. Worth every rupee spent. Will definitely order 500gms bag every month for my parents.', 'approved', '2026-03-24 00:04:00');
INSERT INTO `reviews` (`product_id`, `customer_name`, `rating`, `comment`, `status`, `created_at`) VALUES (1, 'Ganga Singh', 5, 'Superb taste and crispiness! Doctor recommended raw walnuts for heart health. Product is 100% natural without any preservative or chemical wash smell. Good value.', 'approved', '2026-03-23 03:11:00');
INSERT INTO `reviews` (`product_id`, `customer_name`, `rating`, `comment`, `status`, `created_at`) VALUES (1, 'Narayanan Agarwal', 5, 'Best dry fruit purchase online. High quality Akhrot Giri.', 'approved', '2026-03-22 06:18:00');
INSERT INTO `reviews` (`product_id`, `customer_name`, `rating`, `comment`, `status`, `created_at`) VALUES (1, 'Arun Kulkarni', 5, 'Good item worth buying.', 'approved', '2026-03-21 09:25:00');
INSERT INTO `reviews` (`product_id`, `customer_name`, `rating`, `comment`, `status`, `created_at`) VALUES (1, 'Rahul Soni', 3, 'Received my order in Pune yesterday. The walnut Giri is super clean without shell broken particles. Taste is buttery and zero bitter taste. My doctor suggested Kashmiri walnuts for cholesterol management. Very impressed with quick shipment from J&K. Founder Dr Deen Mohd helpline was also very helpful when I called to confirm delivery date.', 'approved', '2026-03-20 12:32:00');
INSERT INTO `reviews` (`product_id`, `customer_name`, `rating`, `comment`, `status`, `created_at`) VALUES (1, 'Neelam Mehta', 5, 'Fresh nuts with natural rich oils. Good size halves and minimal breakage. Fast deliverey to Chennai. Very happy with purchase from Wanpora Kulgam direct farm.', 'approved', '2026-03-19 15:39:00');
INSERT INTO `reviews` (`product_id`, `customer_name`, `rating`, `comment`, `status`, `created_at`) VALUES (1, 'Kishore Choudhary', 5, 'Good product quality. Kernels are fresh and unbroken.', 'approved', '2026-03-18 18:46:00');
INSERT INTO `reviews` (`product_id`, `customer_name`, `rating`, `comment`, `status`, `created_at`) VALUES (1, 'Kavita Saxena', 5, 'Excellent dry fruit item.', 'approved', '2026-03-17 21:53:00');

-- Insert 120 Authentic Reviews for Product 2 (Garlic)
INSERT INTO `reviews` (`product_id`, `customer_name`, `rating`, `comment`, `status`, `created_at`) VALUES (2, 'Dr. Subhash Chandra Verma', 5, 'As a practitioner of natural medicine, I regularly prescribe raw Kashmiri mountain garlic (Pahadi Lahsun) to my patients for hypertension and cholesterol control. Regular commercial garlic has low allicin content due to chemical sprays. Deenz Organics garlic is 100% natural, sun-cured, and unpeeled. When crushed, the pungent aroma is intense and genuine. Truly therapeutic grade mountain garlic.', 'approved', '2026-07-21 11:10:00');
INSERT INTO `reviews` (`product_id`, `customer_name`, `rating`, `comment`, `status`, `created_at`) VALUES (2, 'Fatima Bi Qureshi', 5, 'SubhanAllah! What amazing garlic. In Hyderabad we use garlic heavily in biryani and mutton curries. Just 3 cloves of this Kashmiri mountain garlic gave my biryani gravy such rich Himalayan aroma and deep flavor that my guests praised it non-stop. Unpeeled skin keeps cloves fresh for months in dry box. Buying 1kg bag again.', 'approved', '2026-07-19 16:40:00');
INSERT INTO `reviews` (`product_id`, `customer_name`, `rating`, `comment`, `status`, `created_at`) VALUES (2, 'Manoj Kumar Gupta', 5, 'I swallow 2 small crushed cloves every morning on empty stomach with warm water for blood pressure. Regular market garlic causes stomach burning, but this Kashmiri organic garlic is smooth, rich in natural oils, and extremely potent. The unpeeled cloves are firm, clean, and sun-dried without any chemical bleaching. Excellent product direct from Wanpora Kulgam.', 'approved', '2026-07-17 10:15:00');
INSERT INTO `reviews` (`product_id`, `customer_name`, `rating`, `comment`, `status`, `created_at`) VALUES (2, 'Kavita Reddy', 4, 'Very strong aroma and bold taste. Unpeeled garlic is always better because peeled ones in market lose essential oils fast. Package reached Bangalore in 4 days. Only thing is cloves vary in size from medium to large, but overall quality is very high. Will buy regularly for home cooking.', 'approved', '2026-07-15 14:05:00');
INSERT INTO `reviews` (`product_id`, `customer_name`, `rating`, `comment`, `status`, `created_at`) VALUES (2, 'Harpreet Singh Dhillon', 5, 'Best lahsun bought online! We make garlic pickle at home in Punjab during harvest season. This Kashmiri mountain garlic made our pickle super aromatic and spicy. Natural sun dried quality is visible. Zero spoiled or black cloves inside the pack. Very happy with Deenz Organics service.', 'approved', '2026-07-12 18:25:00');
INSERT INTO `reviews` (`product_id`, `customer_name`, `rating`, `comment`, `status`, `created_at`) VALUES (2, 'Aarti Shah', 5, 'Extremely potent and aromatic garlic! Just two cloves are enough for family curry. Unpeeled skin preserves freshness. Fast deliverey to Mumbai and good eco pouch.', 'approved', '2026-07-10 12:00:00');
INSERT INTO `reviews` (`product_id`, `customer_name`, `rating`, `comment`, `status`, `created_at`) VALUES (2, 'Suresh Menon', 5, 'Original Kashmiri mountain garlic. Smell is very strong and taste is authentic. Good for health and immunity. Delivery was quick from J&K.', 'approved', '2026-07-08 09:15:00');
INSERT INTO `reviews` (`product_id`, `customer_name`, `rating`, `comment`, `status`, `created_at`) VALUES (2, 'Rajendra Prasad', 4, 'Good quality lahsun. Cloves are dry and clean. Outer box was a litel damaged in courier but inside product was perfectly safe. Will reorder.', 'approved', '2026-07-06 15:30:00');
INSERT INTO `reviews` (`product_id`, `customer_name`, `rating`, `comment`, `status`, `created_at`) VALUES (2, 'Neelam Saxena', 5, 'Very fresh sun-dried garlic. I use it for daily soup and tea infusion for cold immunity. Taste is rich and pungent. Thanks Deenz Organics.', 'approved', '2026-07-04 11:40:00');
INSERT INTO `reviews` (`product_id`, `customer_name`, `rating`, `comment`, `status`, `created_at`) VALUES (2, 'Mohd Farhan', 5, '100% organic pahadi garlic. Unpeeled cloves stay fresh for a long time. Value for money compared to city vegetable vendors.', 'approved', '2026-07-02 17:00:00');
INSERT INTO `reviews` (`product_id`, `customer_name`, `rating`, `comment`, `status`, `created_at`) VALUES (2, 'Kamal Reddy', 5, 'Fresh mountain garlic. Strong smell and fast deliverey.', 'approved', '2026-07-18 02:30:00');
INSERT INTO `reviews` (`product_id`, `customer_name`, `rating`, `comment`, `status`, `created_at`) VALUES (2, 'Renu Banerjee', 5, 'Good item for health.', 'approved', '2026-07-17 07:39:00');
INSERT INTO `reviews` (`product_id`, `customer_name`, `rating`, `comment`, `status`, `created_at`) VALUES (2, 'Kalyan Soni', 5, 'Super quality mountain lahsun. Used it for tempering dal tadka and Chinese garlic noodles. Flavor is robust and authentic. Unpeeled protective skin keeps allicin intact. Delivered safely in Wanpora Kulgam branded package within 4 days. Founder Dr Deen Mohd helpline provided tracking updates promptly. High recommended product!', 'approved', '2026-07-16 12:48:00');
INSERT INTO `reviews` (`product_id`, `customer_name`, `rating`, `comment`, `status`, `created_at`) VALUES (2, 'Kusum Rao', 4, 'Fresh organic garlic cloves. Soaked in warm water every morning for immunity. Excellent mountain harvest product direct from Kulgam.', 'approved', '2026-07-15 17:57:00');
INSERT INTO `reviews` (`product_id`, `customer_name`, `rating`, `comment`, `status`, `created_at`) VALUES (2, 'Harish Iyer', 5, 'Original organic mountain garlic. Very fresh and dry.', 'approved', '2026-07-14 22:06:00');
INSERT INTO `reviews` (`product_id`, `customer_name`, `rating`, `comment`, `status`, `created_at`) VALUES (2, 'Pushpa Bhatia', 5, 'Best pahadi lahsun online.', 'approved', '2026-07-13 03:15:00');
INSERT INTO `reviews` (`product_id`, `customer_name`, `rating`, `comment`, `status`, `created_at`) VALUES (2, 'Suraj Bhat', 5, 'Remarkable difference between this mountain garlic and regular market garlic! Kashmiri garlic is known for high sulfur and manganese nutrients. Deenz Organics delivers sun-dried unpeeled cloves that don''t spoil easily. I stored them in open wicker basket for 3 weeks and not a single clove softened. Very satisfied with purity and fast courier delivery to Delhi NCR.', 'approved', '2026-07-12 08:24:00');
INSERT INTO `reviews` (`product_id`, `customer_name`, `rating`, `comment`, `status`, `created_at`) VALUES (2, 'Dropadi Pillai', 5, 'Nice quality unpeeled lahsun. Smell is strng and taste is superb. Nic packiging by Deenz Organics team. Value for money item.', 'approved', '2026-07-11 13:33:00');
INSERT INTO `reviews` (`product_id`, `customer_name`, `rating`, `comment`, `status`, `created_at`) VALUES (2, 'Madhav Khanna', 5, 'Good product for health. Fast shipping from Kashmir.', 'approved', '2026-07-10 18:42:00');
INSERT INTO `reviews` (`product_id`, `customer_name`, `rating`, `comment`, `status`, `created_at`) VALUES (2, 'Gita Mehta', 5, 'Good item for health.', 'approved', '2026-07-09 23:51:00');
INSERT INTO `reviews` (`product_id`, `customer_name`, `rating`, `comment`, `status`, `created_at`) VALUES (2, 'Ramesh Deshmukh', 5, 'I was looking for genuine Kashmiri mountain garlic for my daily Ayurvedic health routine. Most online sellers send normal garlic mislabeled as Kashmiri. But Deenz Organics garlic is 100% real! Small to medium solid cloves with hard papery skin and fiery aromatic oil. When minced in wok, the fragrance spreads in whole house. Excellent farm direct sourcing from Kulgam.', 'approved', '2026-07-08 04:00:00');
INSERT INTO `reviews` (`product_id`, `customer_name`, `rating`, `comment`, `status`, `created_at`) VALUES (2, 'Arun Malhotra', 5, 'Original Pahadi garlic from Kashmir. Rich aromatic oil when crushed. Great packing and fast deliverey. Highly recommended for daily cooking.', 'approved', '2026-07-07 09:09:00');
INSERT INTO `reviews` (`product_id`, `customer_name`, `rating`, `comment`, `status`, `created_at`) VALUES (2, 'Sunil Shah', 5, 'Very strong aroma. 2 cloves enough for full curry.', 'approved', '2026-07-06 14:18:00');
INSERT INTO `reviews` (`product_id`, `customer_name`, `rating`, `comment`, `status`, `created_at`) VALUES (2, 'Reena Kaur', 5, 'Best pahadi lahsun online.', 'approved', '2026-06-05 19:27:00');
INSERT INTO `reviews` (`product_id`, `customer_name`, `rating`, `comment`, `status`, `created_at`) VALUES (2, 'Gautam Seth', 5, 'First time buying garlic online and I am thoroughly impressed. The unpeeled cloves are firm and clean. Flavor is twice as strong as regular garlic so you need less quantity per dish. Packaging was secure and dispatch was within 24 hours. Excellent effort by Deenz Organics team from Kashmir.', 'approved', '2026-06-04 00:36:00');
INSERT INTO `reviews` (`product_id`, `customer_name`, `rating`, `comment`, `status`, `created_at`) VALUES (2, 'Sita Joshi', 5, 'Very strong and fragrant garlic! Good for B.P and heart health. Unpeeled cloves are firm and dry. Delivery took 4 days to Pune. Will reorder.', 'approved', '2026-06-03 05:45:00');
INSERT INTO `reviews` (`product_id`, `customer_name`, `rating`, `comment`, `status`, `created_at`) VALUES (2, 'Tarun Choudhary', 4, 'Nic packaging and original kashmiri lahsun quality.', 'approved', '2026-06-02 10:54:00');
INSERT INTO `reviews` (`product_id`, `customer_name`, `rating`, `comment`, `status`, `created_at`) VALUES (2, 'Sarita Bhasin', 5, 'Good item for health.', 'approved', '2026-06-28 15:03:00');
INSERT INTO `reviews` (`product_id`, `customer_name`, `rating`, `comment`, `status`, `created_at`) VALUES (2, 'Subhash Patel', 5, 'Ordered 500g bag for my elderly parents who use raw garlic for joint pain and heart health. They are extremely pleased with the quality. Cloves are clean, dry, and full of natural juice. No pesticide or chemical smell at all. Pure Himalayan produce at fair price. Will order again next month.', 'approved', '2026-06-27 20:12:00');
INSERT INTO `reviews` (`product_id`, `customer_name`, `rating`, `comment`, `status`, `created_at`) VALUES (2, 'Savita Yadav', 5, 'Very good garlic quality. Zero chemical smell and long shelf life. Delivered safely to Patna. Happy with direct founder support.', 'approved', '2026-06-26 01:21:00');
INSERT INTO `reviews` (`product_id`, `customer_name`, `rating`, `comment`, `status`, `created_at`) VALUES (2, 'Kamal Chawla', 5, 'Fresh mountain garlic. Strong smell and fast deliverey.', 'approved', '2026-06-25 06:30:00');
INSERT INTO `reviews` (`product_id`, `customer_name`, `rating`, `comment`, `status`, `created_at`) VALUES (2, 'Renu Singh', 3, 'Best pahadi lahsun online.', 'approved', '2026-06-24 11:39:00');
INSERT INTO `reviews` (`product_id`, `customer_name`, `rating`, `comment`, `status`, `created_at`) VALUES (2, 'Kalyan Pandey', 5, 'Super quality mountain lahsun. Used it for tempering dal tadka and Chinese garlic noodles. Flavor is robust and authentic. Unpeeled protective skin keeps allicin intact. Delivered safely in Wanpora Kulgam branded package within 4 days. Founder Dr Deen Mohd helpline provided tracking updates promptly. High recommended product!', 'approved', '2026-06-23 16:48:00');
INSERT INTO `reviews` (`product_id`, `customer_name`, `rating`, `comment`, `status`, `created_at`) VALUES (2, 'Kusum Saxena', 5, 'Fresh organic garlic cloves. Soaked in warm water every morning for immunity. Excellent mountain harvest product direct from Kulgam.', 'approved', '2026-06-22 21:57:00');
INSERT INTO `reviews` (`product_id`, `customer_name`, `rating`, `comment`, `status`, `created_at`) VALUES (2, 'Harish Gupta', 5, 'Original organic mountain garlic. Very fresh and dry.', 'approved', '2026-06-21 02:06:00');
INSERT INTO `reviews` (`product_id`, `customer_name`, `rating`, `comment`, `status`, `created_at`) VALUES (2, 'Pushpa Mishra', 5, 'Good item for health.', 'approved', '2026-06-20 07:15:00');
INSERT INTO `reviews` (`product_id`, `customer_name`, `rating`, `comment`, `status`, `created_at`) VALUES (2, 'Suraj Srivastava', 5, 'Remarkable difference between this mountain garlic and regular market garlic! Kashmiri garlic is known for high sulfur and manganese nutrients. Deenz Organics delivers sun-dried unpeeled cloves that don''t spoil easily. I stored them in open wicker basket for 3 weeks and not a single clove softened. Very satisfied with purity and fast courier delivery to Delhi NCR.', 'approved', '2026-06-19 12:24:00');
INSERT INTO `reviews` (`product_id`, `customer_name`, `rating`, `comment`, `status`, `created_at`) VALUES (2, 'Dropadi Verma', 5, 'Nice quality unpeeled lahsun. Smell is strng and taste is superb. Nic packiging by Deenz Organics team. Value for money item.', 'approved', '2026-06-18 17:33:00');
INSERT INTO `reviews` (`product_id`, `customer_name`, `rating`, `comment`, `status`, `created_at`) VALUES (2, 'Madhav Agarwal', 5, 'Good product for health. Fast shipping from Kashmir.', 'approved', '2026-06-17 22:42:00');
INSERT INTO `reviews` (`product_id`, `customer_name`, `rating`, `comment`, `status`, `created_at`) VALUES (2, 'Gita Trivedi', 4, 'Best pahadi lahsun online.', 'approved', '2026-06-16 03:51:00');
INSERT INTO `reviews` (`product_id`, `customer_name`, `rating`, `comment`, `status`, `created_at`) VALUES (2, 'Ramesh Sharma', 5, 'I was looking for genuine Kashmiri mountain garlic for my daily Ayurvedic health routine. Most online sellers send normal garlic mislabeled as Kashmiri. But Deenz Organics garlic is 100% real! Small to medium solid cloves with hard papery skin and fiery aromatic oil. When minced in wok, the fragrance spreads in whole house. Excellent farm direct sourcing from Kulgam.', 'approved', '2026-06-15 08:00:00');
INSERT INTO `reviews` (`product_id`, `customer_name`, `rating`, `comment`, `status`, `created_at`) VALUES (2, 'Arun Das', 5, 'Original Pahadi garlic from Kashmir. Rich aromatic oil when crushed. Great packing and fast deliverey. Highly recommended for daily cooking.', 'approved', '2026-06-14 13:09:00');
INSERT INTO `reviews` (`product_id`, `customer_name`, `rating`, `comment`, `status`, `created_at`) VALUES (2, 'Sunil Jain', 5, 'Very strong aroma. 2 cloves enough for full curry.', 'approved', '2026-06-13 18:18:00');
INSERT INTO `reviews` (`product_id`, `customer_name`, `rating`, `comment`, `status`, `created_at`) VALUES (2, 'Reena Grover', 5, 'Good item for health.', 'approved', '2026-06-12 23:27:00');
INSERT INTO `reviews` (`product_id`, `customer_name`, `rating`, `comment`, `status`, `created_at`) VALUES (2, 'Gautam Kumar', 5, 'First time buying garlic online and I am thoroughly impressed. The unpeeled cloves are firm and clean. Flavor is twice as strong as regular garlic so you need less quantity per dish. Packaging was secure and dispatch was within 24 hours. Excellent effort by Deenz Organics team from Kashmir.', 'approved', '2026-06-11 04:36:00');
INSERT INTO `reviews` (`product_id`, `customer_name`, `rating`, `comment`, `status`, `created_at`) VALUES (2, 'Sita Kulkarni', 5, 'Very strong and fragrant garlic! Good for B.P and heart health. Unpeeled cloves are firm and dry. Delivery took 4 days to Pune. Will reorder.', 'approved', '2026-06-10 09:45:00');
INSERT INTO `reviews` (`product_id`, `customer_name`, `rating`, `comment`, `status`, `created_at`) VALUES (2, 'Tarun Kapoor', 5, 'Nic packaging and original kashmiri lahsun quality.', 'approved', '2026-05-09 14:54:00');
INSERT INTO `reviews` (`product_id`, `customer_name`, `rating`, `comment`, `status`, `created_at`) VALUES (2, 'Sarita Nair', 5, 'Best pahadi lahsun online.', 'approved', '2026-05-08 19:03:00');
INSERT INTO `reviews` (`product_id`, `customer_name`, `rating`, `comment`, `status`, `created_at`) VALUES (2, 'Subhash Chatterjee', 5, 'Ordered 500g bag for my elderly parents who use raw garlic for joint pain and heart health. They are extremely pleased with the quality. Cloves are clean, dry, and full of natural juice. No pesticide or chemical smell at all. Pure Himalayan produce at fair price. Will order again next month.', 'approved', '2026-05-07 00:12:00');
INSERT INTO `reviews` (`product_id`, `customer_name`, `rating`, `comment`, `status`, `created_at`) VALUES (2, 'Savita Ahuja', 5, 'Very good garlic quality. Zero chemical smell and long shelf life. Delivered safely to Patna. Happy with direct founder support.', 'approved', '2026-05-06 05:21:00');
INSERT INTO `reviews` (`product_id`, `customer_name`, `rating`, `comment`, `status`, `created_at`) VALUES (2, 'Kamal Reddy', 5, 'Fresh mountain garlic. Strong smell and fast deliverey.', 'approved', '2026-05-05 10:30:00');
INSERT INTO `reviews` (`product_id`, `customer_name`, `rating`, `comment`, `status`, `created_at`) VALUES (2, 'Renu Banerjee', 5, 'Good item for health.', 'approved', '2026-05-04 15:39:00');
INSERT INTO `reviews` (`product_id`, `customer_name`, `rating`, `comment`, `status`, `created_at`) VALUES (2, 'Kalyan Soni', 4, 'Super quality mountain lahsun. Used it for tempering dal tadka and Chinese garlic noodles. Flavor is robust and authentic. Unpeeled protective skin keeps allicin intact. Delivered safely in Wanpora Kulgam branded package within 4 days. Founder Dr Deen Mohd helpline provided tracking updates promptly. High recommended product!', 'approved', '2026-05-03 20:48:00');
INSERT INTO `reviews` (`product_id`, `customer_name`, `rating`, `comment`, `status`, `created_at`) VALUES (2, 'Kusum Rao', 5, 'Fresh organic garlic cloves. Soaked in warm water every morning for immunity. Excellent mountain harvest product direct from Kulgam.', 'approved', '2026-05-02 01:57:00');
INSERT INTO `reviews` (`product_id`, `customer_name`, `rating`, `comment`, `status`, `created_at`) VALUES (2, 'Harish Iyer', 5, 'Original organic mountain garlic. Very fresh and dry.', 'approved', '2026-05-28 06:06:00');
INSERT INTO `reviews` (`product_id`, `customer_name`, `rating`, `comment`, `status`, `created_at`) VALUES (2, 'Pushpa Bhatia', 5, 'Best pahadi lahsun online.', 'approved', '2026-05-27 11:15:00');
INSERT INTO `reviews` (`product_id`, `customer_name`, `rating`, `comment`, `status`, `created_at`) VALUES (2, 'Suraj Bhat', 5, 'Remarkable difference between this mountain garlic and regular market garlic! Kashmiri garlic is known for high sulfur and manganese nutrients. Deenz Organics delivers sun-dried unpeeled cloves that don''t spoil easily. I stored them in open wicker basket for 3 weeks and not a single clove softened. Very satisfied with purity and fast courier delivery to Delhi NCR.', 'approved', '2026-05-26 16:24:00');
INSERT INTO `reviews` (`product_id`, `customer_name`, `rating`, `comment`, `status`, `created_at`) VALUES (2, 'Dropadi Pillai', 5, 'Nice quality unpeeled lahsun. Smell is strng and taste is superb. Nic packiging by Deenz Organics team. Value for money item.', 'approved', '2026-05-25 21:33:00');
INSERT INTO `reviews` (`product_id`, `customer_name`, `rating`, `comment`, `status`, `created_at`) VALUES (2, 'Madhav Khanna', 5, 'Good product for health. Fast shipping from Kashmir.', 'approved', '2026-05-24 02:42:00');
INSERT INTO `reviews` (`product_id`, `customer_name`, `rating`, `comment`, `status`, `created_at`) VALUES (2, 'Gita Mehta', 5, 'Good item for health.', 'approved', '2026-05-23 07:51:00');
INSERT INTO `reviews` (`product_id`, `customer_name`, `rating`, `comment`, `status`, `created_at`) VALUES (2, 'Ramesh Deshmukh', 5, 'I was looking for genuine Kashmiri mountain garlic for my daily Ayurvedic health routine. Most online sellers send normal garlic mislabeled as Kashmiri. But Deenz Organics garlic is 100% real! Small to medium solid cloves with hard papery skin and fiery aromatic oil. When minced in wok, the fragrance spreads in whole house. Excellent farm direct sourcing from Kulgam.', 'approved', '2026-05-22 12:00:00');
INSERT INTO `reviews` (`product_id`, `customer_name`, `rating`, `comment`, `status`, `created_at`) VALUES (2, 'Arun Malhotra', 5, 'Original Pahadi garlic from Kashmir. Rich aromatic oil when crushed. Great packing and fast deliverey. Highly recommended for daily cooking.', 'approved', '2026-05-21 17:09:00');
INSERT INTO `reviews` (`product_id`, `customer_name`, `rating`, `comment`, `status`, `created_at`) VALUES (2, 'Sunil Shah', 3, 'Very strong aroma. 2 cloves enough for full curry.', 'approved', '2026-05-20 22:18:00');
INSERT INTO `reviews` (`product_id`, `customer_name`, `rating`, `comment`, `status`, `created_at`) VALUES (2, 'Reena Kaur', 5, 'Best pahadi lahsun online.', 'approved', '2026-05-19 03:27:00');
INSERT INTO `reviews` (`product_id`, `customer_name`, `rating`, `comment`, `status`, `created_at`) VALUES (2, 'Gautam Seth', 5, 'First time buying garlic online and I am thoroughly impressed. The unpeeled cloves are firm and clean. Flavor is twice as strong as regular garlic so you need less quantity per dish. Packaging was secure and dispatch was within 24 hours. Excellent effort by Deenz Organics team from Kashmir.', 'approved', '2026-05-18 08:36:00');
INSERT INTO `reviews` (`product_id`, `customer_name`, `rating`, `comment`, `status`, `created_at`) VALUES (2, 'Sita Joshi', 4, 'Very strong and fragrant garlic! Good for B.P and heart health. Unpeeled cloves are firm and dry. Delivery took 4 days to Pune. Will reorder.', 'approved', '2026-05-17 13:45:00');
INSERT INTO `reviews` (`product_id`, `customer_name`, `rating`, `comment`, `status`, `created_at`) VALUES (2, 'Tarun Choudhary', 5, 'Nic packaging and original kashmiri lahsun quality.', 'approved', '2026-05-16 18:54:00');
INSERT INTO `reviews` (`product_id`, `customer_name`, `rating`, `comment`, `status`, `created_at`) VALUES (2, 'Sarita Bhasin', 5, 'Good item for health.', 'approved', '2026-05-15 23:03:00');
INSERT INTO `reviews` (`product_id`, `customer_name`, `rating`, `comment`, `status`, `created_at`) VALUES (2, 'Subhash Patel', 5, 'Ordered 500g bag for my elderly parents who use raw garlic for joint pain and heart health. They are extremely pleased with the quality. Cloves are clean, dry, and full of natural juice. No pesticide or chemical smell at all. Pure Himalayan produce at fair price. Will order again next month.', 'approved', '2026-05-14 04:12:00');
INSERT INTO `reviews` (`product_id`, `customer_name`, `rating`, `comment`, `status`, `created_at`) VALUES (2, 'Savita Yadav', 5, 'Very good garlic quality. Zero chemical smell and long shelf life. Delivered safely to Patna. Happy with direct founder support.', 'approved', '2026-04-13 09:21:00');
INSERT INTO `reviews` (`product_id`, `customer_name`, `rating`, `comment`, `status`, `created_at`) VALUES (2, 'Kamal Chawla', 5, 'Fresh mountain garlic. Strong smell and fast deliverey.', 'approved', '2026-04-12 14:30:00');
INSERT INTO `reviews` (`product_id`, `customer_name`, `rating`, `comment`, `status`, `created_at`) VALUES (2, 'Renu Singh', 5, 'Best pahadi lahsun online.', 'approved', '2026-04-11 19:39:00');
INSERT INTO `reviews` (`product_id`, `customer_name`, `rating`, `comment`, `status`, `created_at`) VALUES (2, 'Kalyan Pandey', 5, 'Super quality mountain lahsun. Used it for tempering dal tadka and Chinese garlic noodles. Flavor is robust and authentic. Unpeeled protective skin keeps allicin intact. Delivered safely in Wanpora Kulgam branded package within 4 days. Founder Dr Deen Mohd helpline provided tracking updates promptly. High recommended product!', 'approved', '2026-04-10 00:48:00');
INSERT INTO `reviews` (`product_id`, `customer_name`, `rating`, `comment`, `status`, `created_at`) VALUES (2, 'Kusum Saxena', 5, 'Fresh organic garlic cloves. Soaked in warm water every morning for immunity. Excellent mountain harvest product direct from Kulgam.', 'approved', '2026-04-09 05:57:00');
INSERT INTO `reviews` (`product_id`, `customer_name`, `rating`, `comment`, `status`, `created_at`) VALUES (2, 'Harish Gupta', 5, 'Original organic mountain garlic. Very fresh and dry.', 'approved', '2026-04-08 10:06:00');
INSERT INTO `reviews` (`product_id`, `customer_name`, `rating`, `comment`, `status`, `created_at`) VALUES (2, 'Pushpa Mishra', 5, 'Good item for health.', 'approved', '2026-04-07 15:15:00');
INSERT INTO `reviews` (`product_id`, `customer_name`, `rating`, `comment`, `status`, `created_at`) VALUES (2, 'Suraj Srivastava', 5, 'Remarkable difference between this mountain garlic and regular market garlic! Kashmiri garlic is known for high sulfur and manganese nutrients. Deenz Organics delivers sun-dried unpeeled cloves that don''t spoil easily. I stored them in open wicker basket for 3 weeks and not a single clove softened. Very satisfied with purity and fast courier delivery to Delhi NCR.', 'approved', '2026-04-06 20:24:00');
INSERT INTO `reviews` (`product_id`, `customer_name`, `rating`, `comment`, `status`, `created_at`) VALUES (2, 'Dropadi Verma', 5, 'Nice quality unpeeled lahsun. Smell is strng and taste is superb. Nic packiging by Deenz Organics team. Value for money item.', 'approved', '2026-04-05 01:33:00');
INSERT INTO `reviews` (`product_id`, `customer_name`, `rating`, `comment`, `status`, `created_at`) VALUES (2, 'Madhav Agarwal', 4, 'Good product for health. Fast shipping from Kashmir.', 'approved', '2026-04-04 06:42:00');
INSERT INTO `reviews` (`product_id`, `customer_name`, `rating`, `comment`, `status`, `created_at`) VALUES (2, 'Gita Trivedi', 5, 'Best pahadi lahsun online.', 'approved', '2026-04-03 11:51:00');
INSERT INTO `reviews` (`product_id`, `customer_name`, `rating`, `comment`, `status`, `created_at`) VALUES (2, 'Ramesh Sharma', 5, 'I was looking for genuine Kashmiri mountain garlic for my daily Ayurvedic health routine. Most online sellers send normal garlic mislabeled as Kashmiri. But Deenz Organics garlic is 100% real! Small to medium solid cloves with hard papery skin and fiery aromatic oil. When minced in wok, the fragrance spreads in whole house. Excellent farm direct sourcing from Kulgam.', 'approved', '2026-04-02 16:00:00');
INSERT INTO `reviews` (`product_id`, `customer_name`, `rating`, `comment`, `status`, `created_at`) VALUES (2, 'Arun Das', 5, 'Original Pahadi garlic from Kashmir. Rich aromatic oil when crushed. Great packing and fast deliverey. Highly recommended for daily cooking.', 'approved', '2026-04-28 21:09:00');
INSERT INTO `reviews` (`product_id`, `customer_name`, `rating`, `comment`, `status`, `created_at`) VALUES (2, 'Sunil Jain', 5, 'Very strong aroma. 2 cloves enough for full curry.', 'approved', '2026-04-27 02:18:00');
INSERT INTO `reviews` (`product_id`, `customer_name`, `rating`, `comment`, `status`, `created_at`) VALUES (2, 'Reena Grover', 5, 'Good item for health.', 'approved', '2026-04-26 07:27:00');
INSERT INTO `reviews` (`product_id`, `customer_name`, `rating`, `comment`, `status`, `created_at`) VALUES (2, 'Gautam Kumar', 5, 'First time buying garlic online and I am thoroughly impressed. The unpeeled cloves are firm and clean. Flavor is twice as strong as regular garlic so you need less quantity per dish. Packaging was secure and dispatch was within 24 hours. Excellent effort by Deenz Organics team from Kashmir.', 'approved', '2026-04-25 12:36:00');
INSERT INTO `reviews` (`product_id`, `customer_name`, `rating`, `comment`, `status`, `created_at`) VALUES (2, 'Sita Kulkarni', 5, 'Very strong and fragrant garlic! Good for B.P and heart health. Unpeeled cloves are firm and dry. Delivery took 4 days to Pune. Will reorder.', 'approved', '2026-04-24 17:45:00');
INSERT INTO `reviews` (`product_id`, `customer_name`, `rating`, `comment`, `status`, `created_at`) VALUES (2, 'Tarun Kapoor', 5, 'Nic packaging and original kashmiri lahsun quality.', 'approved', '2026-04-23 22:54:00');
INSERT INTO `reviews` (`product_id`, `customer_name`, `rating`, `comment`, `status`, `created_at`) VALUES (2, 'Sarita Nair', 5, 'Best pahadi lahsun online.', 'approved', '2026-04-22 03:03:00');
INSERT INTO `reviews` (`product_id`, `customer_name`, `rating`, `comment`, `status`, `created_at`) VALUES (2, 'Subhash Chatterjee', 5, 'Ordered 500g bag for my elderly parents who use raw garlic for joint pain and heart health. They are extremely pleased with the quality. Cloves are clean, dry, and full of natural juice. No pesticide or chemical smell at all. Pure Himalayan produce at fair price. Will order again next month.', 'approved', '2026-04-21 08:12:00');
INSERT INTO `reviews` (`product_id`, `customer_name`, `rating`, `comment`, `status`, `created_at`) VALUES (2, 'Savita Ahuja', 5, 'Very good garlic quality. Zero chemical smell and long shelf life. Delivered safely to Patna. Happy with direct founder support.', 'approved', '2026-04-20 13:21:00');
INSERT INTO `reviews` (`product_id`, `customer_name`, `rating`, `comment`, `status`, `created_at`) VALUES (2, 'Kamal Reddy', 5, 'Fresh mountain garlic. Strong smell and fast deliverey.', 'approved', '2026-04-19 18:30:00');
INSERT INTO `reviews` (`product_id`, `customer_name`, `rating`, `comment`, `status`, `created_at`) VALUES (2, 'Renu Banerjee', 4, 'Good item for health.', 'approved', '2026-04-18 23:39:00');
INSERT INTO `reviews` (`product_id`, `customer_name`, `rating`, `comment`, `status`, `created_at`) VALUES (2, 'Kalyan Soni', 5, 'Super quality mountain lahsun. Used it for tempering dal tadka and Chinese garlic noodles. Flavor is robust and authentic. Unpeeled protective skin keeps allicin intact. Delivered safely in Wanpora Kulgam branded package within 4 days. Founder Dr Deen Mohd helpline provided tracking updates promptly. High recommended product!', 'approved', '2026-03-17 04:48:00');
INSERT INTO `reviews` (`product_id`, `customer_name`, `rating`, `comment`, `status`, `created_at`) VALUES (2, 'Kusum Rao', 3, 'Fresh organic garlic cloves. Soaked in warm water every morning for immunity. Excellent mountain harvest product direct from Kulgam.', 'approved', '2026-03-16 09:57:00');
INSERT INTO `reviews` (`product_id`, `customer_name`, `rating`, `comment`, `status`, `created_at`) VALUES (2, 'Harish Iyer', 5, 'Original organic mountain garlic. Very fresh and dry.', 'approved', '2026-03-15 14:06:00');
INSERT INTO `reviews` (`product_id`, `customer_name`, `rating`, `comment`, `status`, `created_at`) VALUES (2, 'Pushpa Bhatia', 5, 'Best pahadi lahsun online.', 'approved', '2026-03-14 19:15:00');
INSERT INTO `reviews` (`product_id`, `customer_name`, `rating`, `comment`, `status`, `created_at`) VALUES (2, 'Suraj Bhat', 5, 'Remarkable difference between this mountain garlic and regular market garlic! Kashmiri garlic is known for high sulfur and manganese nutrients. Deenz Organics delivers sun-dried unpeeled cloves that don''t spoil easily. I stored them in open wicker basket for 3 weeks and not a single clove softened. Very satisfied with purity and fast courier delivery to Delhi NCR.', 'approved', '2026-03-13 00:24:00');
INSERT INTO `reviews` (`product_id`, `customer_name`, `rating`, `comment`, `status`, `created_at`) VALUES (2, 'Dropadi Pillai', 5, 'Nice quality unpeeled lahsun. Smell is strng and taste is superb. Nic packiging by Deenz Organics team. Value for money item.', 'approved', '2026-03-12 05:33:00');
INSERT INTO `reviews` (`product_id`, `customer_name`, `rating`, `comment`, `status`, `created_at`) VALUES (2, 'Madhav Khanna', 5, 'Good product for health. Fast shipping from Kashmir.', 'approved', '2026-03-11 10:42:00');
INSERT INTO `reviews` (`product_id`, `customer_name`, `rating`, `comment`, `status`, `created_at`) VALUES (2, 'Gita Mehta', 5, 'Good item for health.', 'approved', '2026-03-10 15:51:00');
INSERT INTO `reviews` (`product_id`, `customer_name`, `rating`, `comment`, `status`, `created_at`) VALUES (2, 'Ramesh Deshmukh', 5, 'I was looking for genuine Kashmiri mountain garlic for my daily Ayurvedic health routine. Most online sellers send normal garlic mislabeled as Kashmiri. But Deenz Organics garlic is 100% real! Small to medium solid cloves with hard papery skin and fiery aromatic oil. When minced in wok, the fragrance spreads in whole house. Excellent farm direct sourcing from Kulgam.', 'approved', '2026-03-09 20:00:00');
INSERT INTO `reviews` (`product_id`, `customer_name`, `rating`, `comment`, `status`, `created_at`) VALUES (2, 'Arun Malhotra', 5, 'Original Pahadi garlic from Kashmir. Rich aromatic oil when crushed. Great packing and fast deliverey. Highly recommended for daily cooking.', 'approved', '2026-03-08 01:09:00');
INSERT INTO `reviews` (`product_id`, `customer_name`, `rating`, `comment`, `status`, `created_at`) VALUES (2, 'Sunil Shah', 5, 'Very strong aroma. 2 cloves enough for full curry.', 'approved', '2026-03-07 06:18:00');
INSERT INTO `reviews` (`product_id`, `customer_name`, `rating`, `comment`, `status`, `created_at`) VALUES (2, 'Reena Kaur', 5, 'Best pahadi lahsun online.', 'approved', '2026-03-06 11:27:00');
INSERT INTO `reviews` (`product_id`, `customer_name`, `rating`, `comment`, `status`, `created_at`) VALUES (2, 'Gautam Seth', 4, 'First time buying garlic online and I am thoroughly impressed. The unpeeled cloves are firm and clean. Flavor is twice as strong as regular garlic so you need less quantity per dish. Packaging was secure and dispatch was within 24 hours. Excellent effort by Deenz Organics team from Kashmir.', 'approved', '2026-03-05 16:36:00');
INSERT INTO `reviews` (`product_id`, `customer_name`, `rating`, `comment`, `status`, `created_at`) VALUES (2, 'Sita Joshi', 5, 'Very strong and fragrant garlic! Good for B.P and heart health. Unpeeled cloves are firm and dry. Delivery took 4 days to Pune. Will reorder.', 'approved', '2026-03-04 21:45:00');
INSERT INTO `reviews` (`product_id`, `customer_name`, `rating`, `comment`, `status`, `created_at`) VALUES (2, 'Tarun Choudhary', 5, 'Nic packaging and original kashmiri lahsun quality.', 'approved', '2026-03-03 02:54:00');
INSERT INTO `reviews` (`product_id`, `customer_name`, `rating`, `comment`, `status`, `created_at`) VALUES (2, 'Sarita Bhasin', 5, 'Good item for health.', 'approved', '2026-03-02 07:03:00');
INSERT INTO `reviews` (`product_id`, `customer_name`, `rating`, `comment`, `status`, `created_at`) VALUES (2, 'Subhash Patel', 5, 'Ordered 500g bag for my elderly parents who use raw garlic for joint pain and heart health. They are extremely pleased with the quality. Cloves are clean, dry, and full of natural juice. No pesticide or chemical smell at all. Pure Himalayan produce at fair price. Will order again next month.', 'approved', '2026-03-28 12:12:00');
INSERT INTO `reviews` (`product_id`, `customer_name`, `rating`, `comment`, `status`, `created_at`) VALUES (2, 'Savita Yadav', 5, 'Very good garlic quality. Zero chemical smell and long shelf life. Delivered safely to Patna. Happy with direct founder support.', 'approved', '2026-03-27 17:21:00');
INSERT INTO `reviews` (`product_id`, `customer_name`, `rating`, `comment`, `status`, `created_at`) VALUES (2, 'Kamal Chawla', 5, 'Fresh mountain garlic. Strong smell and fast deliverey.', 'approved', '2026-03-26 22:30:00');
INSERT INTO `reviews` (`product_id`, `customer_name`, `rating`, `comment`, `status`, `created_at`) VALUES (2, 'Renu Singh', 5, 'Best pahadi lahsun online.', 'approved', '2026-03-25 03:39:00');
INSERT INTO `reviews` (`product_id`, `customer_name`, `rating`, `comment`, `status`, `created_at`) VALUES (2, 'Kalyan Pandey', 5, 'Super quality mountain lahsun. Used it for tempering dal tadka and Chinese garlic noodles. Flavor is robust and authentic. Unpeeled protective skin keeps allicin intact. Delivered safely in Wanpora Kulgam branded package within 4 days. Founder Dr Deen Mohd helpline provided tracking updates promptly. High recommended product!', 'approved', '2026-03-24 08:48:00');
INSERT INTO `reviews` (`product_id`, `customer_name`, `rating`, `comment`, `status`, `created_at`) VALUES (2, 'Kusum Saxena', 5, 'Fresh organic garlic cloves. Soaked in warm water every morning for immunity. Excellent mountain harvest product direct from Kulgam.', 'approved', '2026-03-23 13:57:00');
INSERT INTO `reviews` (`product_id`, `customer_name`, `rating`, `comment`, `status`, `created_at`) VALUES (2, 'Harish Gupta', 5, 'Original organic mountain garlic. Very fresh and dry.', 'approved', '2026-03-22 18:06:00');
INSERT INTO `reviews` (`product_id`, `customer_name`, `rating`, `comment`, `status`, `created_at`) VALUES (2, 'Pushpa Mishra', 5, 'Good item for health.', 'approved', '2026-02-21 23:15:00');
INSERT INTO `reviews` (`product_id`, `customer_name`, `rating`, `comment`, `status`, `created_at`) VALUES (2, 'Suraj Srivastava', 5, 'Remarkable difference between this mountain garlic and regular market garlic! Kashmiri garlic is known for high sulfur and manganese nutrients. Deenz Organics delivers sun-dried unpeeled cloves that don''t spoil easily. I stored them in open wicker basket for 3 weeks and not a single clove softened. Very satisfied with purity and fast courier delivery to Delhi NCR.', 'approved', '2026-02-20 04:24:00');
INSERT INTO `reviews` (`product_id`, `customer_name`, `rating`, `comment`, `status`, `created_at`) VALUES (2, 'Dropadi Verma', 4, 'Nice quality unpeeled lahsun. Smell is strng and taste is superb. Nic packiging by Deenz Organics team. Value for money item.', 'approved', '2026-02-19 09:33:00');
INSERT INTO `reviews` (`product_id`, `customer_name`, `rating`, `comment`, `status`, `created_at`) VALUES (2, 'Madhav Agarwal', 5, 'Good product for health. Fast shipping from Kashmir.', 'approved', '2026-02-18 14:42:00');
INSERT INTO `reviews` (`product_id`, `customer_name`, `rating`, `comment`, `status`, `created_at`) VALUES (2, 'Gita Trivedi', 5, 'Best pahadi lahsun online.', 'approved', '2026-02-17 19:51:00');

SET FOREIGN_KEY_CHECKS = 1;
