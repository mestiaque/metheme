# MeTheme

[M. Estiaque](https://mestiaque.com)-এর Laravel অ্যাডমিন প্যাকেজ। এতে আছে লগইন/রেজিস্ট্রেশন (OTP), ইউজার, রোল ও পারমিশন, রোল অনুক্রম, অ্যাক্টিভিটি লগ, সেটিংস, ডাটাবেস থেকে মেইল ও এসএমএস, মেইল টেমপ্লেট, ডেমো ড্যাশবোর্ড এবং গ্লাস-স্টাইলের অ্যাডমিন থিম।

বিস্তারিত ডকুমেন্টেশন: **[doc.md](doc.md)**

## প্রয়োজন

- PHP ^8.2
- Laravel 12 (13 দাবি করা, পরীক্ষা করা হয়নি)
- MySQL
- PHP `zip` extension
- অ্যাপে `App\Http\Controllers\Controller` ক্লাস (সাধারণ Laravel অ্যাপে থাকে) — প্যাকেজের কিছু controller এটা extend করে

## ইনস্টলেশন

```bash
composer require mestiaque/metheme
php artisan migrate
php artisan vendor:publish --tag=metheme-assets
php artisan storage:link
php artisan metheme:import-mail-sms-env   # পুরনো .env মেইল/এসএমএস থাকলে, একবার
```

`.env`:

```env
METHEME_ROUTE_PREFIX=admin          # অ্যাডমিন URL prefix, ড্যাশবোর্ড = /admin
METHEME_DEVELOPER_MODE=false        # শুধু নিজের সার্ভারে true
METHEME_DEVELOPER_EMAILS=           # ডেভেলপারের ইমেইল, কমা দিয়ে
```

অন্যান্য publish ট্যাগ: `metheme-auth-config`, `metheme-errors`।

---

## নিয়মাবলি

### ১. Route ও URL

1. সব অ্যাডমিন পেজের URL prefix আসে `.env`-এর `METHEME_ROUTE_PREFIX` থেকে (ডিফল্ট `admin`)। কোডে লিখুন `me_prefix()`, হাতে `'admin'` লিখবেন না।
2. Route নামে কোনো prefix নেই (`dashboard`, `users.index`, `roles.edit`, …)। লিংক সবসময় `route('নাম')` দিয়ে বানান, হাতে URL লিখবেন না।
3. লগইনের পরে সবসময় ড্যাশবোর্ড (`/{prefix}`), লগআউটের পরে লগইন পেজ।

### ২. পারমিশন

1. কোডে যে পারমিশন চেক করবেন (`authorization:x.y`, `can('x.y')`, sidebar `permit`), সেটা অবশ্যই কোনো `permissions.php`-এ **ঘোষণা** করতে হবে। নইলে কাউকে দেওয়া যাবে না, আর role পেজ থেকে রোল সেভ করলে সেটা মুছে যাবে।
2. Key-র ফরম্যাট `module.action` (যেমন `me_user.edit`)।
3. মেনু লুকানো পারমিশন নয় — প্রতিটি route/controller-এ `authorization:` middleware দিন।

### ৩. রোল অনুক্রম (Parent role)

1. প্রতিটি রোলের একটি parent থাকে; parent নেই মানে সর্বোচ্চ (top) রোল।
2. শুধু **নিজের রোলের নিচের** রোল edit/delete করা যায় — নিজের, উপরের বা পাশের রোল নয়।
3. নতুন রোলের parent হবে নিজের রোল বা তার নিচের রোল। **Top রোল শুধু ডেভেলপার বানাতে পারে।**
4. রোলকে শুধু **নিজের আছে এমন পারমিশন** দেওয়া যায়।
5. যে রোলের নিচে child রোল আছে সেটা মোছা যায় না।
6. ইউজারকে শুধু নিজের রোলের নিচের রোল দেওয়া যায়; শুধু নিচের রোলের ইউজারদের edit/deactivate/delete করা যায়। নিজেকে Users পেজ থেকে নয়, Profile পেজ থেকে edit করুন।
7. এসব ছাড়াও সাধারণ পারমিশন লাগবে (যেমন `me_role.edit`)।

উদাহরণ:

| রোল | Parent | কে edit/delete করতে পারবে |
|---|---|---|
| Super Admin | — (top) | শুধু ডেভেলপার |
| Admin | Super Admin | Super Admin |
| Staff | Admin | Super Admin, Admin |

### ৪. ডেভেলপার মোড

1. `METHEME_DEVELOPER_MODE=true` **এবং** লগইন করা ইউজারের ইমেইল `METHEME_DEVELOPER_EMAILS`-এ থাকলে সেই ইউজার কোনো পারমিশন ছাড়াই সব কিছু পায়।
2. `me_settings.developer_permissions`-এর পারমিশন শুধু ডেভেলপার role পেজে দেখে এবং অন্য রোলকে দিতে পারে; দেওয়ার পর সেই রোলের ইউজাররা স্বাভাবিকভাবে পায়। সাধারণ অ্যাডমিন রোল সেভ করলে এগুলো বদলায় না।
3. `me_settings.developer_only_permissions`-এর পারমিশন কোনো রোলকে দেওয়া যায় না — শুধু ডেভেলপার পায়।
4. ডিফল্টে বন্ধ। ক্লায়েন্ট বা বিক্রির কপিতে কখনো চালু রাখবেন না।
5. ডেভেলপারের ইমেইল অন্য কোনো অ্যাকাউন্টে বসানো যাবে না — ইউজার edit করার পারমিশন শুধু বিশ্বস্ত মানুষকে দিন।

### ৫. মেইল ও এসএমএস

1. সব মেইল ও এসএমএস **শুধু ডাটাবেসের সেটিং** দিয়ে যায় (Mail Configuration ও SMS Configuration পেজ)। `.env`-এর `MAIL_*` / `SMS_*` ব্যবহার হয় না।
2. কোডে মেইল পাঠাতে `me_mail()`, এসএমএস পাঠাতে `me_sms()` ব্যবহার করুন। `env('SMS_...')` বা গেটওয়েতে সরাসরি `Http::` কল করবেন না।
3. OTP বা গোপন লেখার এসএমএসে `hideMessage: true` দিন।
4. মেইলের `$content`-এ ইউজারের দেওয়া লেখা থাকলে `e()` দিয়ে escape করুন।
5. SMTP host সেভ না থাকলে মেইল শুধু লগে যায়; "Enable SMS" বন্ধ থাকলে এসএমএস যায় না।
6. Queue worker চালালে সেটিং বদলানোর পরে `php artisan queue:restart`।

```php
me_mail('user@example.com', 'বিষয়', '<p>বার্তা</p>');                          // কমন লেআউট (default)
me_mail($email, 'Your code', '<p>Use this code</p>', ['otp' => $otp], 'auth');  // OTP টেমপ্লেট
me_sms('01712345678', 'বার্তা');
```

নতুন মেইল টেমপ্লেট: `me_settings.php`-এর `mail_templates`-এ `'নাম' => 'blade.view'` যোগ করুন।

### ৬. নিরাপত্তা

1. ডিফল্ট সিড অ্যাকাউন্ট (migration `0001_01_01_000001`) ইনস্টলের সঙ্গে সঙ্গে বদলান বা মুছুন — এর পাসওয়ার্ড পাবলিক রিপোজিটরিতে আছে।
2. রেজিস্ট্রেশন ও forgot password-এর OTP ফ্লো এখনো নিরাপদ নয় — প্রোডাকশনে Configurations পেজ থেকে বন্ধ রাখুন।
3. Activity Log পেজে এখনো পারমিশন চেক নেই — শুধু বিশ্বস্ত ইউজারকে অ্যাকাউন্ট দিন।
4. **Clear Data** প্রায় পুরো ডাটাবেস মুছে দেয় — `me.clearData` কাউকে দেবেন না।

বিস্তারিত ও বাকি জানা সমস্যা: [doc.md › সীমাবদ্ধতা](doc.md#১৬-সীমাবদ্ধতা-ও-জানা-সমস্যা)।

## লাইসেন্স

MIT
