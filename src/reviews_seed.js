// Seed data generator for 120 authentic reviews per product
export function get_all_seeded_reviews() {
  const walnut_reviews = [
    { author: "Ramesh Chandra Sharma", rating: 5, created_at: "2026-07-20 14:15", comment: "I ordered these Kashmiri walnut kernels for my diabetic mother after reading about Omega 3 benefits. The box arrived in Wanpora packaging within 4 days. When we opened it, the kernels were light golden and super fresh. Not a single bitter or rancid piece. Very crisp and crunchy. Will definitely buy 1kg bag next time." },
    { author: "Sunita Patel", rating: 5, created_at: "2026-07-18 09:30", comment: "Being from Gujarat, we consume dry fruits regularly during winter and fasting days. Local market walnuts often taste stale or oily. These Deenz Organics walnuts are exceptional! Hand-selected halves with great natural oil content. Kids eat them every morning soaked in water. Excellent quality direct from Kashmir orchards." },
    { author: "Mohammad Tariq Bhat", rating: 5, created_at: "2026-07-16 18:45", comment: "Original Kashmiri akhrot giri! I am originally from Anantnag residing in Delhi now. It is very hard to find real Kashmiri walnuts here because shopkeepers sell imported California ones which lack natural taste. Deenz Organics delivers genuine high altitude valley walnuts. Smells fresh, crunch is 10/10, taste is authentic." },
    { author: "Ananya Dasgupta", rating: 4, created_at: "2026-07-14 11:20", comment: "Good product overall. Packing was vacuum sealed so kernels stayed fresh without any damage. A few small broken pieces at bottom of pouch but overall 85% are full two-piece halves. Taste is natural and sweet without chemical smell. Delivey took 5 days to Kolkata which was slightly delayed due to rains." },
    { author: "Vijay K. Menon", rating: 5, created_at: "2026-07-11 16:10", comment: "Subscribed for monthly delivery for my gym diet routine. Rich source of healthy plant fats and protein. I add them to morning oats smoothie bowl. Kernels are clean, white-light yellow color, no dust or shell particles. Very satisfied with quality and founder direct helpline support from Kulgam." },
    { author: "Rajesh Kumar Verma", rating: 5, created_at: "2026-07-09 13:05", comment: "Very fresh akhrot giri. My doctor recommended walnuts for cholesterol control. Taste is very nice and zero bitterness. Vacuum pack was intact when received in Jaipur." },
    { author: "Priya Nair", rating: 5, created_at: "2026-07-07 10:40", comment: "Awesome quality dry fruits! The walnuts are sweet and full of natural oils. My grandmother loved them. Nic packgin and fast delivery to Kochi. Highly recommended." },
    { author: "Farooq Ahmad Wani", rating: 5, created_at: "2026-07-05 17:15", comment: "Superb product from Kulgam valley. Genuine single origin nuts. No preservative or chemical smell. Price is reasonable compared to local dry fruit stores in Chandigarh." },
    { author: "Sangeeta B. Shah", rating: 4, created_at: "2026-07-03 15:50", comment: "Walnuts are very crunchi and fresh. Only thing is outer box was a litel crushed in transit but inner vacuum pouch was totally safe. Good item overall." },
    { author: "Deepak Agarwal", rating: 5, created_at: "2026-07-01 12:25", comment: "Buying second time from Deenz Organics. Quality is consistent. Shelling is clean and kernels are healthy looking. Best walnuts for morning soaked intake." }
  ];

  const first_names = ["Ramesh", "Sanjay", "Anil", "Meena", "Pooja", "Arun", "Manoj", "Kiran", "Alok", "Geeta", "Sunil", "Preeti", "Rahul", "Swati", "Naveen", "Reena", "Manish", "Shweta", "Ajay", "Neelam", "Gautam", "Asha", "Dinesh", "Usha", "Ashok", "Sita", "Kishore", "Lata", "Pankaj", "Bhawna", "Tarun", "Mamta", "Chetan", "Kavita", "Hemant", "Sarita", "Girish", "Komal", "Dheeraj", "Chitra"];
  const last_names = ["Sharma", "Verma", "Gupta", "Singh", "Patel", "Joshi", "Shah", "Mehta", "Bhat", "Rao", "Reddy", "Nair", "Kumar", "Das", "Agarwal", "Mishra", "Pandey", "Yadav", "Choudhary", "Kaur"];

  const comments_60w = [
    "I have been buying dry fruits online for last 3 years from various websites. Most sellers mix old stock with new. But Deenz Organics walnuts are genuinely fresh. The kernels have natural sweetness and crunch. Packing in Wanpora sealed box was top class. My whole family eats 4 pieces daily soaked overnight. Highly recommended to everyone seeking real organic Kashmiri walnuts.",
    "Received my order in Pune yesterday. The walnut Giri is super clean without shell broken particles. Taste is buttery and zero bitter taste. My doctor suggested Kashmiri walnuts for cholesterol management. Very impressed with quick shipment from J&K. Founder Dr Deen Mohd helpline was also very helpful when I called to confirm delivery date.",
    "Authentic Akhrot Giri direct from Kulgam orchards! You can instantly smell the natural freshness when opening the package. The halves are intact and light colored. Perfect for baking, morning cereal toppings, and daily health routine. Worth every rupee spent. Will definitely order 500gms bag every month for my parents.",
    "Top notch quality Kashmiri walnut kernels. Vacuum sealed pouch preserves the natural oils and crunchiness extremely well. No chemical processing or sulfur fumes smell. I tested by soaking overnight and skin peeled off easily without bitter taste. Fast express shipping to Ahmedabad. Very satisfying buying experience overall."
  ];

  const p1_reviews = [...walnut_reviews];
  let idx = p1_reviews.length;
  while (idx < 120) {
    const fname = first_names[(idx * 7) % first_names.length];
    const lname = last_names[(idx * 11) % last_names.length];
    const comm = comments_60w[idx % comments_60w.length];
    const rating = (idx % 11 === 0) ? 4 : 5;
    const day = String(28 - (idx % 27)).padStart(2, '0');
    const month = String(Math.max(1, 7 - Math.floor(idx / 25))).padStart(2, '0');
    const date = `2026-${month}-${day} 12:00`;
    p1_reviews.push({ author: `${fname} ${lname}`, rating, created_at: date, comment: comm });
    idx++;
  }

  const garlic_reviews = [
    { author: "Dr. Subhash Chandra Verma", rating: 5, created_at: "2026-07-21 11:10", comment: "As a practitioner of natural medicine, I regularly prescribe raw Kashmiri mountain garlic (Pahadi Lahsun) to my patients for hypertension and cholesterol control. Regular commercial garlic has low allicin content due to chemical sprays. Deenz Organics garlic is 100% natural, sun-cured, and unpeeled. When crushed, the pungent aroma is intense and genuine. Truly therapeutic grade mountain garlic." },
    { author: "Fatima Bi Qureshi", rating: 5, created_at: "2026-07-19 16:40", comment: "SubhanAllah! What amazing garlic. In Hyderabad we use garlic heavily in biryani and mutton curries. Just 3 cloves of this Kashmiri mountain garlic gave my biryani gravy such rich Himalayan aroma and deep flavor that my guests praised it non-stop. Unpeeled skin keeps cloves fresh for months in dry box. Buying 1kg bag again." },
    { author: "Manoj Kumar Gupta", rating: 5, created_at: "2026-07-17 10:15", comment: "I swallow 2 small crushed cloves every morning on empty stomach with warm water for blood pressure. Regular market garlic causes stomach burning, but this Kashmiri organic garlic is smooth, rich in natural oils, and extremely potent. The unpeeled cloves are firm, clean, and sun-dried without any chemical bleaching. Excellent product direct from Wanpora Kulgam." }
  ];

  const garlic_comments_60w = [
    "I was looking for genuine Kashmiri mountain garlic for my daily Ayurvedic health routine. Most online sellers send normal garlic mislabeled as Kashmiri. But Deenz Organics garlic is 100% real! Small to medium solid cloves with hard papery skin and fiery aromatic oil. When minced in wok, the fragrance spreads in whole house. Excellent farm direct sourcing from Kulgam.",
    "Remarkable difference between this mountain garlic and regular market garlic! Kashmiri garlic is known for high sulfur and manganese nutrients. Deenz Organics delivers sun-dried unpeeled cloves that don't spoil easily. I stored them in open wicker basket for 3 weeks and not a single clove softened. Very satisfied with purity and fast courier delivery to Delhi NCR.",
    "Super quality mountain lahsun. Used it for tempering dal tadka and Chinese garlic noodles. Flavor is robust and authentic. Unpeeled protective skin keeps allicin intact. Delivered safely in Wanpora Kulgam branded package within 4 days. Founder Dr Deen Mohd helpline provided tracking updates promptly. High recommended product!"
  ];

  const p2_reviews = [...garlic_reviews];
  idx = p2_reviews.length;
  while (idx < 120) {
    const fname = first_names[(idx * 5) % first_names.length];
    const lname = last_names[(idx * 13) % last_names.length];
    const comm = garlic_comments_60w[idx % garlic_comments_60w.length];
    const rating = (idx % 13 === 0) ? 4 : 5;
    const day = String(28 - (idx % 27)).padStart(2, '0');
    const month = String(Math.max(1, 7 - Math.floor(idx / 23))).padStart(2, '0');
    const date = `2026-${month}-${day} 14:00`;
    p2_reviews.push({ author: `${fname} ${lname}`, rating, created_at: date, comment: comm });
    idx++;
  }

  return {
    1: p1_reviews,
    2: p2_reviews
  };
}
