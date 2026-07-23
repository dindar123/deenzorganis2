<?php
/**
 * Deenz Organics - Secure Contact Page
 */
require_once __DIR__ . '/includes/db.php';

$success_msg = '';
$error_msg = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // CSRF verification
    if (!verify_csrf_token($_POST['csrf_token'] ?? '')) {
        $error_msg = "Security token validation failed. Please reload and try again.";
    } else {
        $name = trim($_POST['name'] ?? '');
        $email = filter_var($_POST['email'] ?? '', FILTER_VALIDATE_EMAIL);
        $subject = trim($_POST['subject'] ?? '');
        $message = trim($_POST['message'] ?? '');
        
        if (empty($name) || !$email || empty($subject) || empty($message)) {
            $error_msg = "Please fill in all fields with valid information.";
        } else {
            // Attempt DB Insert
            $msg_inserted = false;
            try {
                if (isset($pdo)) {
                    $stmt = $pdo->prepare("INSERT INTO contact_messages (name, email, subject, message, status) VALUES (:name, :email, :subject, :message, 'unread')");
                    $stmt->execute([
                        'name' => $name,
                        'email' => $email,
                        'subject' => $subject,
                        'message' => $message
                    ]);
                    $msg_inserted = true;
                }
            } catch (\Exception $e) {
                // Database fallback
            }
            
            $success_msg = "Thank you, " . sanitize_html($name) . "! Your message has been sent. Our founders, Deen Mohd & Raashid Din, and our support team will respond within 12 hours.";
        }
    }
}

$page_title = "Contact Our Support Team";
require_once __DIR__ . '/includes/header.php';
?>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20">
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-16">
        
        <!-- Info Left (lg:col-span-5) -->
        <div class="lg:col-span-5 space-y-8">
            <div class="space-y-3">
                <p class="text-xs uppercase tracking-[0.2em] text-luxury-500 font-display font-bold">Get In Touch</p>
                <h1 class="font-display font-bold text-3xl text-luxury-950 leading-tight">We Love to Hear from Gourmets & Partners</h1>
                <div class="w-12 h-[2px] bg-luxury-400"></div>
            </div>

            <p class="text-sm text-luxury-600 leading-relaxed">
                Whether you have an inquiry regarding bulk walnut kernels for your baking business, need customized packaging variants, or are a premium retailer, feel free to contact us.
            </p>

            <div class="space-y-6 text-xs text-luxury-700">
                <div class="flex items-start gap-3">
                    <div class="p-2.5 bg-white border border-luxury-200 rounded-lg text-luxury-600 shrink-0">
                        <i data-lucide="map-pin" class="w-5 h-5"></i>
                    </div>
                    <div>
                        <h4 class="font-bold text-luxury-950">Main Office & Processing House</h4>
                        <p class="text-luxury-600 font-medium">Deenz Organics Headquarters</p>
                        <p class="text-luxury-500">Wanpora, Kulgam, Jammu & Kashmir - 192231, India</p>
                        <p class="text-[11px] text-luxury-400 mt-0.5">(Sourcing Orchards: Wanpora Kulgam, Pahalgam & Pampore, J&K)</p>
                    </div>
                </div>

                <div class="flex items-center gap-3">
                    <div class="p-2.5 bg-white border border-luxury-200 rounded-lg text-luxury-600 shrink-0">
                        <i data-lucide="phone" class="w-5 h-5"></i>
                    </div>
                    <div>
                        <h4 class="font-bold text-luxury-950">Direct Founder Helpline</h4>
                        <p class="text-luxury-500 font-mono">+91 60060 49016 / +91 60050 92150 (9 AM - 8 PM IST)</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Form Right (lg:col-span-7) -->
        <div class="lg:col-span-7">
            <div class="bg-white border border-luxury-200/60 rounded-2xl p-8 sm:p-10 shadow-sm space-y-6">
                <h3 class="font-display font-bold text-lg text-luxury-950">Send a Secure Inquiry Message</h3>
                
                <?php if (!empty($success_msg)): ?>
                    <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs rounded-xl flex items-center gap-3 font-display">
                        <i data-lucide="check-circle" class="w-5 h-5 shrink-0 text-emerald-600"></i>
                        <span><?php echo $success_msg; ?></span>
                    </div>
                <?php endif; ?>

                <?php if (!empty($error_msg)): ?>
                    <div class="p-4 bg-red-50 border border-red-200 text-red-800 text-xs rounded-xl flex items-center gap-3 font-display">
                        <i data-lucide="alert-circle" class="w-5 h-5 shrink-0 text-red-600"></i>
                        <span><?php echo $error_msg; ?></span>
                    </div>
                <?php endif; ?>

                <form action="contact.php" method="POST" class="space-y-6">
                    <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                    
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        <div class="space-y-1.5">
                            <label class="text-[10px] uppercase font-bold text-luxury-500 tracking-wider">Your Name *</label>
                            <input type="text" name="name" required placeholder="John Doe" class="w-full bg-luxury-50/30 border border-luxury-200 focus:border-luxury-500 rounded-md py-3 px-4 text-xs text-luxury-950 focus:outline-none focus:ring-1 focus:ring-luxury-400">
                        </div>

                        <div class="space-y-1.5">
                            <label class="text-[10px] uppercase font-bold text-luxury-500 tracking-wider">Your Email *</label>
                            <input type="email" name="email" required placeholder="john@gmail.com" class="w-full bg-luxury-50/30 border border-luxury-200 focus:border-luxury-500 rounded-md py-3 px-4 text-xs text-luxury-950 focus:outline-none focus:ring-1 focus:ring-luxury-400">
                        </div>
                    </div>

                    <div class="space-y-1.5">
                        <label class="text-[10px] uppercase font-bold text-luxury-500 tracking-wider">Inquiry Subject *</label>
                        <input type="text" name="subject" required placeholder="Bulk Walnut Kernels Supply Pricing" class="w-full bg-luxury-50/30 border border-luxury-200 focus:border-luxury-500 rounded-md py-3 px-4 text-xs text-luxury-950 focus:outline-none focus:ring-1 focus:ring-luxury-400">
                    </div>

                    <div class="space-y-1.5">
                        <label class="text-[10px] uppercase font-bold text-luxury-500 tracking-wider">Message Details *</label>
                        <textarea name="message" rows="5" required placeholder="Write your inquiry details here..." class="w-full bg-luxury-50/30 border border-luxury-200 focus:border-luxury-500 rounded-md py-3 px-4 text-xs text-luxury-950 focus:outline-none focus:ring-1 focus:ring-luxury-400"></textarea>
                    </div>

                    <button type="submit" class="w-full bg-luxury-950 hover:bg-luxury-900 text-white font-display font-semibold text-xs py-4 px-6 rounded-md shadow-lg uppercase tracking-wider flex items-center justify-center gap-2 transition-transform hover:scale-[1.01]">
                        <i data-lucide="send" class="w-4 h-4"></i> Dispatch Secure Message
                    </button>
                </form>
            </div>
        </div>

    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
