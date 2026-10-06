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
php artisan metheme:sync-geo-locations    # দেশ/বিভাগ/জেলা/উপজেলা ডেটা লোড
php artisan metheme:import-mail-sms-env   # পুরনো .env মেইল/এসএমএস থাকলে, একবার
php artisan metheme:media-import          # পুরনো প্রজেক্ট আপডেট করলে, একবার: লোগো/প্রোফাইল ছবি me_media-তে আনে
```

`.env`:

```env
METHEME_ROUTE_PREFIX=admin          # অ্যাডমিন URL prefix, ড্যাশবোর্ড = /admin
METHEME_DEVELOPER_MODE=false        # শুধু নিজের সার্ভারে true
METHEME_DEVELOPER_EMAILS=           # ডেভেলপারের ইমেইল, কমা দিয়ে
METHEME_HOME_ROUTE=                 # লগইনের পরের হোম পেজের রুট নাম (metheme-এর নিজের ড্যাশবোর্ড নেই), যেমন ecom.dashboard
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

### ৬. ডেটা পরিবর্তন লগ

1. গুরুত্বপূর্ণ store/update/delete-এ `me_change_log()` দিয়ে আগের ও পরের ডেটা লগ করুন — Activity Log-এ দেখা যায়।
2. একটা কাজে একাধিক মডেল বদলালে (Order + Items) **একটাই** লগ দিন: `->watch($order, ['items'])`, মডেল-প্রতি আলাদা নয়।
3. `->labels()` ও `->itemName()` দিয়ে ফিল্ড ও আইটেমের পড়ার মতো নাম দিন।
4. পাসওয়ার্ড/টোকেন/API key-র মান কখনো লগ হয় না (`me_settings.data_change_log.hidden_fields`); নতুন গোপন ফিল্ড থাকলে সেখানে যোগ করুন।

```php
$log = me_change_log('Order #'.$order->id.' updated', 'order.update')->watch($order, ['items']);
// ... আপডেট ...
$log->save();
```

### ৭. নিরাপত্তা

1. ডিফল্ট সিড অ্যাকাউন্ট (migration `0001_01_01_000001`) ইনস্টলের সঙ্গে সঙ্গে বদলান বা মুছুন — এর পাসওয়ার্ড পাবলিক রিপোজিটরিতে আছে।
2. রেজিস্ট্রেশন ও forgot password-এর OTP ফ্লো এখনো নিরাপদ নয় — প্রোডাকশনে Configurations পেজ থেকে বন্ধ রাখুন।
3. Activity Log পেজে এখনো পারমিশন চেক নেই — শুধু বিশ্বস্ত ইউজারকে অ্যাকাউন্ট দিন।
4. **Clear Data** প্রায় পুরো ডাটাবেস মুছে দেয় — `me.clearData` কাউকে দেবেন না।

### ৮. ঠিকানা (দেশ → বিভাগ → জেলা → উপজেলা)

1. সব লোকেশন **একটাই টেবিলে** — `geo_locations` (`parent_id`, `type` = `country` / `division` / `district` / `upazila`, `name`, `bn_name`, `lat`, `long`, `is_active`)। মডেল `ME\Models\GeoLocation` (`parent()`, `children()`, `active()`, `ofType()`)। ঠিকানার কলামে এই টেবিলের `id` রাখুন (`country_id`, `division_id`, …), হাতে নাম লিখবেন না।
2. ডেটা আসে `src/public/geo-data.json` থেকে; JSON-এ দেশ নেই, তাই sync নিজে "Bangladesh" রুট বানায়। `php artisan metheme:sync-geo-locations` (অন্য ফাইল: `--path=`) — বারবার চালানো নিরাপদ, ডুপ্লিকেট হয় না, নাম/স্থানাঙ্ক আপডেট হয়। কোড থেকে: `app(\ME\Services\GeoLocationSync::class)->sync()`।
3. `new-geo.json` ভাঙা JSON (দুটো অবজেক্ট জোড়া) — ব্যবহার করবেন না।
4. AJAX API (পাবলিক, লগইন লাগে না, ১২০ রিকোয়েস্ট/মিনিট):

| Route নাম | URL | ফেরত দেয় |
|---|---|---|
| `geo.countries` | `GET /api/geo/countries` | দেশ |
| `geo.children` | `GET /api/geo/{id}/children` | দেশের id → বিভাগ, বিভাগ → জেলা, জেলা → উপজেলা |

উত্তর: `{ "type": "division", "child_type": "district", "data": [{ "id", "parent_id", "type", "name", "bn_name" }] }`। শেষ ধাপে `child_type` = `null`।

5. ফর্মে চেইন করা select-এর জন্য `public/js/geo-select.js` (jQuery লাগে, `vendor:publish --tag=metheme-assets` দিয়ে কপি হয়)। একটা বদলালে পরেরটা লোড হয়, নিচেরগুলো খালি হয়। Edit ফর্মে `data-selected` দিলে পুরো চেইন আগে থেকে বাছাই হয়ে আসে। রুটে `data-geo-lang="bn"` দিলে বাংলা নাম, `data-geo-url` দিয়ে API-র base URL, প্রতিটা select-এ `data-placeholder`। নতুন HTML লোড হলে `initGeoSelect('#selector')`।

```html
<select name="country_id"  data-geo-root data-geo-child="#division" data-selected="{{ old('country_id', $m->country_id) }}"></select>
<select name="division_id" id="division" data-geo-child="#district" data-selected="{{ old('division_id', $m->division_id) }}"></select>
<select name="district_id" id="district" data-geo-child="#upazila"  data-selected="{{ old('district_id', $m->district_id) }}"></select>
<select name="upazila_id"  id="upazila"  data-selected="{{ old('upazila_id', $m->upazila_id) }}"></select>
<script src="{{ asset('js/geo-select.js') }}"></script>
```

### ৯. ড্যাশবোর্ড

metheme-এ কোনো ড্যাশবোর্ড পেজ বা `/dashboard` রুট নেই। মূল ড্যাশবোর্ড প্রজেক্টের; metheme-এর ইউজার/লগইন/কার্যক্রমের তথ্য দেখাতে সেখানে `@include('me::widgets.system-overview')` দিন (শুধু ডেটা: `ME\Services\SystemOverview`)। লগইনের পরে `me_settings.home_route`-এ যায়। বিস্তারিত: [doc.md › হোম পেজ ও উইজেট](doc.md#৪-ড্যাশবোর্ড-নেই--হোম-পেজ-ও-system-overview-উইজেট)।

### ১০. ফাইল ও ছবি (Media Library)

1. সব প্যাকেজের সব ফাইল/ছবি **একটাই টেবিলে** — `me_media` (polymorphic: `mediable_type` + `mediable_id` + `collection`)। নতুন টেবিলে ছবির কলাম (`image`, `logo`, `path` …) বানাবেন না।
2. মডেলে `use ME\Traits\HasMedia;` দিন আর `mediaCollections()`-এ স্লট ঘোষণা করুন (`single`, `mimes`, `max_kb`, `conversions`)।
3. ফর্মে `@include('me::components.media-input', [...])`, কন্ট্রোলারে `$model->syncMediaFromRequest($request, 'collection')`।
4. লিস্টে সবসময় `->with('media')` (নইলে প্রতি রো-তে একটা কুয়েরি)।
5. সেটিংয়ের ছবি: `Setting::setImage('app_logo', $file)`, পড়তে `get_image('app_logo')`।
6. থাম্বনেইল ব্যাকগ্রাউন্ডে তৈরি হয় (`GenerateMediaConversions` জব) — ক্রন/কিউ না থাকলেও চলে।
7. মুছলে আগে ট্র্যাশে যায়; Admin → Configuration → **Media Library** থেকে ফেরানো / চিরতরে মোছা যায় (পারমিশন `me_media.view/edit/delete`)।

পুরো API: [doc.md › মিডিয়া লাইব্রেরি](doc.md#মিডিয়া-লাইব্রেরি-ফাইল-ও-ছবি)।

বিস্তারিত ও বাকি জানা সমস্যা: [doc.md › সীমাবদ্ধতা](doc.md#১৬-সীমাবদ্ধতা-ও-জানা-সমস্যা)।

## লাইসেন্স

MIT
