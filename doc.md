# MeTheme — ডকুমেন্টেশন

MeTheme একটি Laravel অ্যাডমিন প্যাকেজ (`mestiaque/metheme`, namespace `ME\`)। এতে আছে লগইন/রেজিস্ট্রেশন (OTP), ইউজার, রোল ও পারমিশন, রোল অনুক্রম (parent), অ্যাক্টিভিটি লগ, সেটিংস, ডাটাবেস থেকে মেইল ও এসএমএস কনফিগারেশন, মেইল টেমপ্লেট, ডেমো ড্যাশবোর্ড এবং গ্লাস-স্টাইলের অ্যাডমিন থিম।

নিয়মের সংক্ষিপ্ত তালিকা আছে [readme.md](readme.md)-তে। এই ফাইলে বিস্তারিত।

---

## সূচিপত্র

1. [ইনস্টলেশন](#১-ইনস্টলেশন)
2. [.env সেটিংস](#২-env-সেটিংস)
3. [Route ও URL prefix](#৩-route-ও-url-prefix)
4. [ড্যাশবোর্ড](#৪-ড্যাশবোর্ড)
5. [Sidebar মেনু](#৫-sidebar-মেনু)
6. [পারমিশন](#৬-পারমিশন)
7. [রোল অনুক্রম (Parent role)](#৭-রোল-অনুক্রম-parent-role)
8. [রোলের ব্যাজ রঙ](#৮-রোলের-ব্যাজ-রঙ)
9. [ডেভেলপার মোড](#৯-ডেভেলপার-মোড)
10. [মেইল ও এসএমএস (শুধু ডাটাবেস)](#১০-মেইল-ও-এসএমএস-শুধু-ডাটাবেস)
11. [মেইল টেমপ্লেট: `me_mail()`](#১১-মেইল-টেমপ্লেট-me_mail)
12. [এসএমএস: `me_sms()`](#১২-এসএমএস-me_sms)
13. [Helper ফাংশন](#১৩-helper-ফাংশন)
14. [Artisan কমান্ড](#১৪-artisan-কমান্ড)
15. [ডাটাবেস টেবিল](#১৫-ডাটাবেস-টেবিল)
16. [সীমাবদ্ধতা ও জানা সমস্যা](#১৬-সীমাবদ্ধতা-ও-জানা-সমস্যা)

---

## ১. ইনস্টলেশন

প্রয়োজন: PHP ^8.2, Laravel 12 (Laravel 13 দাবি করা আছে কিন্তু পরীক্ষা করা হয়নি), MySQL।

```bash
composer require mestiaque/metheme
php artisan migrate
php artisan vendor:publish --tag=metheme-assets   # CSS/JS/ছবি public/-এ
php artisan storage:link
php artisan metheme:import-mail-sms-env           # পুরনো .env মেইল/এসএমএস থাকলে একবার
```

Service provider `ME\MEServiceProvider` নিজে থেকেই লোড হয় (package auto-discovery)।

> **সতর্কতা:** প্যাকেজের migration `users`, `sessions`, `password_reset_tokens`, `jobs`, `failed_jobs` টেবিল তৈরি করে। নতুন Laravel অ্যাপে Laravel-এর নিজের একই migration থাকলে `migrate` ব্যর্থ হবে। [সীমাবদ্ধতা](#১৬-সীমাবদ্ধতা-ও-জানা-সমস্যা) দেখুন।

---

## ২. .env সেটিংস

| কী | ডিফল্ট | কাজ |
|---|---|---|
| `METHEME_ROUTE_PREFIX` | `admin` | সব অ্যাডমিন পেজের URL prefix। ড্যাশবোর্ড = `{APP_URL}/{prefix}` |
| `METHEME_DEVELOPER_MODE` | `false` | ডেভেলপার মোড চালু/বন্ধ |
| `METHEME_DEVELOPER_EMAILS` | খালি | ডেভেলপার ইউজারের ইমেইল, কমা দিয়ে একাধিক |
| `ME_META_TITLE`, `ME_META_AUTHOR`, `ME_META_DESCRIPTION`, `ME_META_KEYWORDS` | — | অ্যাডমিন পেজের meta ট্যাগ |

মেইল ও এসএমএসের কোনো মান `.env` থেকে নেওয়া **হয় না** ([১০ নম্বর](#১০-মেইল-ও-এসএমএস-শুধু-ডাটাবেস) দেখুন)।

`.env` বদলানোর পর `php artisan config:clear` (config cache ব্যবহার করলে `php artisan config:cache`) চালান।

---

## ৩. Route ও URL prefix

সব অ্যাডমিন পেজ একটি group-এ, URL prefix আসে `me_prefix()` থেকে (`.env`-এর `METHEME_ROUTE_PREFIX`)।

```php
// src/routes/web.php
Route::group(['prefix' => me_prefix(), 'middleware' => ['web', 'auth', LocaleMiddleware::class, 'activityLog']], function () { ... });
```

- **Route নামে কোনো prefix নেই:** `dashboard`, `users.index`, `roles.edit`, `profile.edit`, `configurations.edit`, `mail-config.edit`, `sms-config.edit`, `sms-log.index`, `mail-log.index`, `activity.index`, `menus.index`, `theme`, `mail-layout-preview`, `data.clear.form`।
- লিংক বানাতে সবসময় route নাম ব্যবহার করুন: `route('users.index')`। prefix বদলালেও লিংক ঠিক থাকবে।
- লগইনের পর সবসময় `route('dashboard')`-এ যায়। লগআউটের পর লগইন পেজে।
- Auth route (`/login`, `/register`, `/logout`, `/forget-password`, …) prefix ছাড়া থাকে।
- অন্য প্যাকেজে একই URL থাকলে (যেমন prefix `admin` হলে `/admin/users`) metheme-এর route কাজ করবে, কারণ এটা পরে লোড হয়।

উদাহরণ: `.env`-এ `METHEME_ROUTE_PREFIX=panel` দিলে ড্যাশবোর্ড `/panel`, ইউজার `/panel/users`।

---

## ৪. ড্যাশবোর্ড

`/{prefix}` — `DataController@index`, view `me::dashboard-demo`। পারমিশন: `me.dashboard`।

সব তথ্য আসল ডাটাবেস থেকে:

- কার্ড: মোট ইউজার (সক্রিয় কতজন), রোল, আজকের লগইন (ব্যর্থ কত), আজকের কার্যক্রম, এই মাসের ইমেইল ও এসএমএস
- গত ৭ দিনের কার্যক্রম ও লগইনের চার্ট (ApexCharts)
- রোল অনুযায়ী ইউজার
- সাম্প্রতিক ৮টি কার্যক্রম
- দ্রুত লিংক — শুধু যেগুলোর পারমিশন ইউজারের আছে

কোনো টেবিল না থাকলে পুরো পেজ ভাঙে না, শুধু সেই অংশ ফাঁকা দেখায়।

---

## ৫. Sidebar মেনু

মেনু আসে `config('sidebar')` থেকে। প্রতিটি প্যাকেজ নিজের `sidebar.php` merge করে; আইটেম `sl` অনুযায়ী সাজানো হয়।

metheme-এর মেনু (`src/Config/sidebar.php`):

| গ্রুপ | আইটেম |
|---|---|
| Dashboard | (একক লিংক) |
| Users & Roles | Users, Roles |
| Configuration | Configurations, Mail Configuration, SMS Configuration, Menus, Clear Data |
| Logs | Activity Log, SMS Log & Balance, Mail Log |
| Theme | Theme Info, Email Templates |

আইটেমের গঠন:

```php
[
    'title'      => 'Users',          // অনুবাদ: menu_trans()
    'icon'       => 'fas fa-users',
    'route'      => 'users.index',    // route নাম
    'for_active' => 'users.',         // এই নাম দিয়ে শুরু হওয়া route-এ মেনু active
    'permit'     => 'me_user.view',   // এই পারমিশন না থাকলে দেখাবে না
    'icon_color' => 'icc-81',
],
```

- গ্রুপের ভেতরে একটিও দেখানোর মতো আইটেম না থাকলে পুরো গ্রুপ লুকিয়ে যায়।
- হেডারের সার্চ বক্স (`/menu-search`) একই মেনু ও একই `permit` নিয়ম মেনে খোঁজে।

---

## ৬. পারমিশন

### ঘোষণা (declare)

পারমিশন ঘোষণা হয় `config('permissions')`-এ, গঠন `module => [title, actions]`। প্রতিটি key হয় `module.action`:

```php
// src/Config/permissions.php
"me_user" => [
    "title" => "Users",
    "actions" => "view,create,edit,delete",   // me_user.view, me_user.create, ...
],
```

অন্য প্যাকেজ বা অ্যাপ নিজের `permissions.php` merge করে (`mergeConfigFrom(..., 'permissions')`)।

**Role পেজে শুধু ঘোষিত পারমিশনই দেখা যায় ও দেওয়া যায়।** কোডে কোনো পারমিশন চেক করলে সেটা অবশ্যই কোথাও ঘোষণা করতে হবে। নইলে কাউকে দেওয়া যাবে না, আর role পেজ থেকে রোল সেভ করলে সেটা মুছে যাবে।

### metheme-এর পারমিশন

| Key | কাজ |
|---|---|
| `me.dashboard` | ড্যাশবোর্ড |
| `me.theme` | Theme Info |
| `me.mailLayoutPreview` | Email Templates প্রিভিউ |
| `me.clearData` | Clear Data (⚠️ প্রায় পুরো ডাটাবেস মুছে দেয়) |
| `me_user.view/create/edit/delete` | ইউজার |
| `me_role.view/create/edit/delete` | রোল |
| `me_activity.view` | Activity Log মেনু |
| `me_menus.view` | Menus |
| `me_setting.configurations/settings/mail/sms` | Configurations, Settings, Mail ও SMS Configuration |
| `me_sms.view/recharge` | SMS Log ও রিচার্জ |
| `me_mail.view` | Mail Log |

### চেক করার উপায়

```php
// Route/controller middleware
$this->middleware('authorization:me_user.view');

// কোডে
auth()->user()->hasPermission('me_user.edit');
auth()->user()->can('me_user.edit');   // Gate::before → hasPermission
can('me_user.edit');                   // global helper

// Blade
@if(auth()->user()->can('me_role.create')) ... @endif

// Sidebar
'permit' => 'me_user.view'
```

রোলের পারমিশন রাখা হয় `role_permissions.permissions` (JSON) কলামে।

---

## ৭. রোল অনুক্রম (Parent role)

প্রতিটি রোলের একটি parent থাকতে পারে (`roles.parent_id`)। Parent নেই মানে সর্বোচ্চ (top) রোল।

উদাহরণ:

```
Super Admin            (parent নেই — সর্বোচ্চ)
└── Admin              (parent: Super Admin)
    └── Staff          (parent: Admin)
```

| রোল | কে edit/delete করতে পারবে |
|---|---|
| Super Admin | শুধু ডেভেলপার |
| Admin | Super Admin |
| Staff | Super Admin ও Admin |

নিয়ম (`ME\Services\RoleHierarchy`, সার্ভারে যাচাই হয়):

- শুধু **নিজের রোলের নিচের** রোল (children, grandchildren, …) edit/delete করা যায়। নিজের রোল, উপরের বা পাশের রোল নয়।
- নতুন রোলের parent হতে পারে নিজের রোল বা তার নিচের কোনো রোল। **Top রোল (parent ছাড়া) শুধু ডেভেলপার বানাতে পারে।**
- রোলের parent হিসেবে নিজেকে বা নিজের নিচের রোল দেওয়া যায় না (চক্র তৈরি হয় না)।
- রোলকে শুধু **নিজের আছে এমন পারমিশন** দেওয়া যায়। সেভ করার সময় যেসব পারমিশন আপনার নেই (তাই ফর্মে দেখেননি), সেগুলো যেমন ছিল তেমনই থাকে।
- যে রোলের নিচে child রোল আছে, সেটা মোছা যায় না — আগে child সরাতে বা মুছতে হবে।
- ইউজারকে শুধু নিজের রোলের নিচের রোল দেওয়া যায়।
- শুধু সেই ইউজারদের edit/deactivate/delete করা যায় যাদের সব রোল নিজের রোলের নিচে। নিজেকে Users পেজ থেকে edit করা যায় না — Profile পেজ ব্যবহার করুন।
- সাধারণ পারমিশনও লাগবে: যেমন Staff রোল edit করতে Admin-এর `me_role.edit` থাকতে হবে।
- ডেভেলপার সব কিছু করতে পারে।

ফলাফল: Super Admin ইউজার অন্য Super Admin বানাতে পারে না (নিজের সমান রোল) — শুধু ডেভেলপার পারে।

ব্যবহৃত মেথড:

```php
$role->parent;            // parent রোল
$role->children;          // সরাসরি child রোল
$role->descendantIds();   // নিচের সব রোলের id
$role->hierarchyPath();   // "Super Admin › Admin › Staff"

$h = app(\ME\Services\RoleHierarchy::class);
$h->manageableRoleIds($user);      // যে রোলগুলো ম্যানেজ করতে পারে
$h->canManageRole($user, $role);
$h->canManageUser($user, $target);
$h->allowedParentIds($user, $role);
$h->grantablePermissions($user);   // null = সীমা নেই (ডেভেলপার)
```

---

## ৮. রোলের ব্যাজ রঙ

রোল create/edit পেজে "Badge Color" দিয়ে রঙ বেছে নেওয়া যায় (`roles.color`, ফরম্যাট `#RRGGBB`)।

- Users তালিকা, Roles তালিকা ও রোলের বিস্তারিত পেজে রোল নিজের রঙের ব্যাজে দেখায়।
- লেখার রঙ নিজে থেকে কালো বা সাদা হয় (যেটা পড়া যায়)।
- রঙ না দিলে ডিফল্ট `#0dcaf0`।
- ব্যাজের উপর মাউস রাখলে পুরো অনুক্রম দেখায়।

নিজের view-এ ব্যবহার:

```blade
@include('me::roles.partials.badge', ['role' => $role])
```

---

## ৯. ডেভেলপার মোড

নিজের নিয়ন্ত্রিত সার্ভারের জন্য। `.env`:

```env
METHEME_DEVELOPER_MODE=true
METHEME_DEVELOPER_EMAILS=you@example.com,other@example.com
```

দুটোই মিললে (মোড চালু **এবং** লগইন করা ইউজারের ইমেইল তালিকায় আছে) সেই ইউজার:

1. **কোনো পারমিশন ছাড়াই সব কিছুতে access পায়** (`hasPermission()` সবসময় true, রোল অনুক্রমও তার জন্য প্রযোজ্য নয়)।
2. Role পেজে **ডেভেলপার পারমিশন** দেখে ও অন্য রোলকে দিতে পারে (`me_settings.developer_permissions`)।
3. Top রোল (parent ছাড়া) বানাতে ও edit করতে পারে।

`me_settings.php`-এর দুটি তালিকা:

| তালিকা | মানে |
|---|---|
| `developer_permissions` | শুধু ডেভেলপার role পেজে দেখে ও **দিতে পারে**। দেওয়ার পর সেই রোলের ইউজাররা স্বাভাবিকভাবে পায়, ডেভেলপার মোড বন্ধ থাকলেও। সাধারণ অ্যাডমিন রোল সেভ করলে এগুলো মুছে যায় না, যোগও হয় না। |
| `developer_only_permissions` | কখনো কোনো রোলকে দেওয়া যায় না, role পেজে কারও জন্য দেখায় না। শুধু ডেভেলপার পায়। নির্দিষ্ট key বা wildcard (`me.*`) দেওয়া যায়। এখন খালি। |

> **খেয়াল রাখুন:** `developer_permissions`-এর মডিউল যদি `permissions.php`-তেও ঘোষিত থাকে, তাহলে সেটা সবাই দেখে। এখন `me_setting`, `me_sms`, `me_mail` দুই জায়গাতেই আছে — এগুলো শুধু ডেভেলপারকে দেখাতে চাইলে `permissions.php` থেকে comment out করুন।

নিরাপত্তা:

- ডিফল্টে বন্ধ। CodeCanyon বা ক্লায়েন্টের কপিতে `.env`-এ না রাখলে কেউ এই সুবিধা পায় না।
- সুবিধাটা ইমেইলের উপর নির্ভর করে। যে কেউ কোনো ইউজারের ইমেইল তালিকার ঠিকানায় বদলাতে পারলে সে ডেভেলপার হয়ে যাবে — তাই ইউজার edit করার পারমিশন শুধু বিশ্বস্ত মানুষকে দিন।

---

## ১০. মেইল ও এসএমএস (শুধু ডাটাবেস)

সব মেইল ও এসএমএস চলে **Mail Configuration** ও **SMS Configuration** পেজে সেভ করা সেটিং দিয়ে (টেবিল `settings`)। `.env`-এর `MAIL_*` / `SMS_*` কখনো ব্যবহার হয় না।

প্রতিটি request-এর শুরুতে `MEServiceProvider` ডাটাবেস থেকে সেটিং পড়ে Laravel config-এ বসায়:

| ডাটাবেস key | Laravel config |
|---|---|
| `mail_mailer` (`smtp`/`sendmail`/`log`) | `mail.default` |
| `mail_host`, `mail_port`, `mail_username` | `mail.mailers.smtp.*` |
| `mail_password` (এনক্রিপ্টেড) | `mail.mailers.smtp.password` |
| `mail_encryption` (`ssl` → `smtps`, অন্যথা `smtp`) | `mail.mailers.smtp.scheme` |
| `mail_from_address` → নেই হলে `app_email` → নেই হলে `noreply@{host}` | `mail.from.address` |
| `mail_from_name` → নেই হলে `app_name` | `mail.from.name` |
| `enable_sms` | `services.sms_enabled` |
| `sms_api_url`, `sms_sender_id`, `sms_balance_url` | `services.sms_*` |
| `sms_api_key` (এনক্রিপ্টেড) | `services.sms_api_key` |

আচরণ:

- SMTP host সেভ না থাকলে মেইল পাঠানো হয় না, **লগে লেখা হয়** (`mail.default = log`)।
- "Enable SMS" বন্ধ থাকলে কোনো এসএমএস যায় না; SMS Log-এ কারণসহ লেখা থাকে।
- পাসওয়ার্ড ও API key ডাটাবেসে এনক্রিপ্ট থাকে (`APP_KEY` দিয়ে)। `APP_KEY` বদলালে আবার সেভ করতে হবে।
- প্রতিটি মেইল `mail_logs`-এ, প্রতিটি এসএমএস `sms_logs`-এ লগ হয়।
- Queue worker চালালে সেটিং বদলানোর পর `php artisan queue:restart` চালান।

পুরনো `.env` থেকে একবার ডাটাবেসে আনতে: `php artisan metheme:import-mail-sms-env` ([১৪ নম্বর](#১৪-artisan-কমান্ড))।

---

## ১১. মেইল টেমপ্লেট: `me_mail()`

```php
me_mail($to, string $subject, string $content = '', array $data = [], string $template = 'notice', bool $queue = false): bool
```

| প্যারামিটার | মানে |
|---|---|
| `$to` | একটি ইমেইল বা ইমেইলের array |
| `$subject` | বিষয় (টেমপ্লেটে `$title` হিসেবেও যায়) |
| `$content` | HTML বডি। **ইউজারের দেওয়া লেখা `e()` দিয়ে escape করুন।** |
| `$data` | টেমপ্লেটের অতিরিক্ত ভেরিয়েবল |
| `$template` | `mail_templates`-এর নাম (ডিফল্ট `default` = কমন লেআউট), অথবা যেকোনো Blade view-এর নাম |
| `$queue` | `true` হলে queue-তে পাঠায় |

ফেরত দেয় `true`/`false`। ভুল হলে exception ছোড়ে না — `report()` দিয়ে লগে লেখে।

উদাহরণ:

```php
// যেকোনো মেসেজ — কমন লেআউট (default)
me_mail('user@example.com', 'অর্ডার প্রস্তুত', '<p>আপনার অর্ডার #42 প্রস্তুত।</p>');

// শুভেচ্ছা লাইনসহ নোটিশ
me_mail($email, 'Notice', '<p>...</p>', ['showGreeting' => true]);

// OTP / ভেরিফিকেশন কোড (auth টেমপ্লেট)
me_mail([$a, $b], 'Your code', '<p>Use this code</p>', ['otp' => 123456], 'auth');

// নিজের view, queue-তে
me_mail($email, 'Invoice', '', ['invoice' => $invoice], 'emails.invoice', queue: true);
```

### কমন মেইল লেআউট: `me::mail.master`

অ্যাডমিন পেজের `me::master`-এর মতো মেইলেরও একটা master layout আছে। যেকোনো মেইল view এটা extend করলে একই হেডার (লোগো), কার্ড ডিজাইন, ফুটার ও কপিরাইট পায়:

```blade
{{-- resources/views/emails/order-shipped.blade.php --}}
@extends('me::mail.master')

@section('title', 'Order shipped')                  {{-- ঐচ্ছিক শিরোনাম --}}
@section('preheader', 'আপনার অর্ডার রওনা হয়েছে')      {{-- ঐচ্ছিক: ইনবক্সের প্রিভিউ লেখা --}}

@section('content')
    <p>Hello {{ $name }}, your order #{{ $order->id }} is on its way.</p>
    @include('me::mail.partials.button', ['url' => $trackUrl, 'text' => 'Track order'])
@endsection

@section('footer')                                   {{-- ঐচ্ছিক: ডিফল্ট ফুটার লেখা বদলাতে --}}
    You ordered from our shop.
@endsection
```

```php
me_mail($email, 'Order shipped', '', ['name' => $name, 'order' => $order, 'trackUrl' => $url], 'emails.order-shipped');
// অথবা নিজের Mailable-এ: ->view('emails.order-shipped', [...])
```

- `$companyName`, `$companyLogo`, `$currentYear` না দিলেও Settings থেকে নিজে নেয়, তাই `me_mail()` ছাড়াও (নিজের Mailable বা Notification) কাজ করে।
- শুধু table ও inline style ব্যবহার করা — Gmail, Outlook ও ফোনে একই রকম দেখায়।
- লেআউটে ব্যবহারযোগ্য partial:
  - `@include('me::mail.partials.button', ['url' => ..., 'text' => ..., 'color' => '#0052cc'])`
  - `@include('me::mail.partials.otp', ['otp' => $otp, 'minutes' => 5])`

### শুধু মেসেজ লিখলেই হবে (default টেমপ্লেট)

`me_mail()`-এর ডিফল্ট টেমপ্লেট `default` (`me::mail.message`) — যেকোনো মেসেজ কমন লেআউটে বসে যায়, আলাদা view লাগে না:

```php
me_mail($email, 'Order shipped', '<p>আপনার অর্ডার রওনা হয়েছে।</p>');

me_mail($email, 'Invoice', '<p>আপনার ইনভয়েস তৈরি।</p>', [
    'heading'      => 'Invoice ready',            // ডিফল্ট: subject; false দিলে শিরোনাম থাকবে না
    'buttonUrl'    => route('invoices.show', 1),  // বাটন দেখাবে
    'buttonText'   => 'Open invoice',
    'otp'          => 123456,                     // কোড বক্স দেখাবে
    'showGreeting' => true,                       // শেষে শুভেচ্ছা লাইন
]);
```

### টেমপ্লেটের তালিকা

`src/Config/me_settings.php`:

```php
'mail_templates' => [
    'default' => 'me::mail.message',       // common layout — যেকোনো মেসেজ (me_mail-এর ডিফল্ট)
    'auth'    => 'me::mail.auth-layout',   // OTP / ভেরিফিকেশন কোড ($otp), common layout
    'notice'  => 'me::mail.notice-layout', // পুরনো গ্রেডিয়েন্ট নোটিশ ডিজাইন
],
```

নতুন টেমপ্লেট যোগ করতে এখানে এক লাইন দিন, যেমন `'invoice' => 'emails.invoice'`, তারপর `me_mail(..., 'invoice')`। Mail Configuration পেজের টেস্ট ফর্মে এই তালিকার সব টেমপ্লেট বেছে পরীক্ষা করা যায়।

### টেমপ্লেটে পাওয়া ভেরিয়েবল

| ভেরিয়েবল | ডিফল্ট |
|---|---|
| `$title` | subject |
| `$content` | `$content` প্যারামিটার (`{!! $content !!}` দিয়ে দেখান) |
| `$otp` | `null` |
| `$companyName` | Settings-এর `app_name`, নেই হলে `APP_NAME` |
| `$companyLogo` | `route('app_logo.show')` |
| `$currentYear` | চলতি বছর |
| `$showGreeting` | `false` |
| `$greetings` | শুভেচ্ছা লাইনের তালিকা |
| অন্য যেকোনো | `$data`-তে যা দেবেন |

সব টেমপ্লেট প্রিভিউ দেখা যায়: Theme → Email Templates (`route('mail-layout-preview')`)।

পুরনো ক্লাস `ME\Mail\NoticeMailLayout` ও `ME\Mail\AuthMailLayout` আগের মতো কাজ করে। নতুন কোডে `me_mail()` ব্যবহার করুন। ভেতরে সব টেমপ্লেট চলে একটি Mailable দিয়ে: `ME\Mail\TemplateMail`।

---

## ১২. এসএমএস: `me_sms()`

```php
me_sms(string $to, string $message, bool $hideMessage = false): bool
```

```php
me_sms('01712345678', 'আপনার অর্ডার প্রস্তুত।');
me_sms($phone, "Your code is {$otp}", hideMessage: true); // লেখা SMS Log-এ জমা হবে না
```

- গেটওয়ে: SMS Configuration পেজের সেটিং। প্রোটোকল — form POST (`api_key`, `senderid`, `number`, `message`); `response_code` 202 মানে গৃহীত।
- ফোন নম্বর: শুধু বাংলাদেশি মোবাইল (`01XXXXXXXXX` বা `+8801XXXXXXXXX`)। স্পেস ও ড্যাশ নিজে থেকে সরানো হয়।
- লোকাল ব্যালেন্স (`sms_accounts`) প্রতি এসএমএসের রেট কভার না করলে পাঠানো হয় না; সফল হলে রেট কাটা হয়।
- প্রতিটি চেষ্টা `sms_logs`-এ। OTP-র মতো গোপন লেখায় `hideMessage: true` দিন।
- বিস্তারিত ফলাফল লাগলে: `\ME\Services\SmsService::send($to, $message)` — array ফেরত দেয় (`success`, `response_code`, `response`, `error`)।

---

## ১৩. Helper ফাংশন

| ফাংশন | কাজ |
|---|---|
| `me_prefix()` | অ্যাডমিন URL prefix (`.env` `METHEME_ROUTE_PREFIX`, ডিফল্ট `admin`) |
| `me_mail(...)` | টেমপ্লেট দিয়ে মেইল ([১১](#১১-মেইল-টেমপ্লেট-me_mail)) |
| `me_sms(...)` | এসএমএস ([১২](#১২-এসএমএস-me_sms)) |
| `me_is_developer($user = null)` | ইউজার ডেভেলপার কিনা |
| `me_is_developer_only($permission)` | পারমিশনটি developer-only তালিকায় কিনা |
| `me_developer_permission_keys()` | ডেভেলপার পারমিশনের সব key |
| `can($permission)` | লগইন করা ইউজারের পারমিশন আছে কিনা |
| `get_setting($key, $default = null)` | `settings` টেবিল থেকে মান |
| `get_image($key, $default = null)` | সেভ করা ছবির URL (যেমন `app_logo`) |
| `menu_trans($text)` | মেনুর লেখা অনুবাদ |
| `toBanglaNumber($n, $decimals = 0)` | `bn` locale-এ বাংলা অঙ্ক |
| `toBanglaPhone($phone)` | `bn` locale-এ বাংলা অঙ্কে ফোন |
| `formatDate($date, $format = 'd M, Y')` | তারিখ (বাংলায় মাস ও অঙ্কসহ) |
| `formatDateTime($date, $format = null)` | তারিখ ও সময় |
| `banglaYear($date = null)` | বছর |

### টেক্সট এডিটর (Summernote)

`me::master` লেআউটে Summernote লোড করা আছে। যেকোনো `<textarea>`-এ শুধু `summernote` class দিলেই এডিটর হয়ে যায় — আলাদা JS লাগে না:

```blade
<textarea name="description" class="summernote">{{ old('description') }}</textarea>

{{-- ঐচ্ছিক attribute --}}
<textarea name="body" class="summernote" data-height="250" data-placeholder="লিখুন..." data-images="false"></textarea>
```

| Attribute | মানে |
|---|---|
| `data-height` | উচ্চতা px-এ (ডিফল্ট 150) |
| `data-placeholder` | placeholder (না দিলে textarea-র `placeholder`) |
| `data-images="false"` | ছবির বাটন লুকাবে (ডিফল্টে থাকে) |

- টুলবার: Bold, Italic, Underline, ফরম্যাটিং মোছা, লেখার রঙ, বুলেট/নম্বর তালিকা, লিংক, ছবি। (Undo/Redo: Ctrl+Z / Ctrl+Y)
- ছবি base64 হিসেবে লেখার ভেতরে বসে যায়। ওয়েব পেজে ঠিক আছে, কিন্তু **ইমেইলে Gmail-সহ অনেক client base64 ছবি দেখায় না** — মেইলে ছবি দিতে "ছবি → URL" দিয়ে অনলাইন ছবির লিংক দিন।
- Bootstrap modal-এর ভেতরের textarea modal খোলার সময় চালু হয়।
- JS দিয়ে পরে যোগ করা textarea: `window.meSummernote($('#id'))`।
- সেভ করা HTML দেখানোর সময় `summernote-content` class দিলে ছবিতে ক্লিক করে বড় করে দেখা যায়।

---

## ১৪. Artisan কমান্ড

### `php artisan metheme:import-mail-sms-env`

`.env`-এর `MAIL_*` ও `SMS_API_URL`, `SMS_API_KEY`, `SMS_SENDER_ID`, `SMS_BALANCE_URL` একবার ডাটাবেসে (Mail/SMS Configuration) কপি করে।

- পাসওয়ার্ড ও API key এনক্রিপ্ট করে রাখে। কোনো মান স্ক্রিনে দেখায় না, শুধু key-র নাম।
- ডাটাবেসে আগে থেকে মান থাকলে বদলায় না; বদলাতে `--force`।
- `SMS_API_URL` থাকলে "Enable SMS" চালু করে দেয়।
- এরপর `.env` থেকে `MAIL_*` / `SMS_*` মুছে ফেলা যায়।

---

## ১৫. ডাটাবেস টেবিল

| টেবিল | কাজ |
|---|---|
| `users` | ইউজার (`phone`, `profile_image`, `is_active` সহ) |
| `roles` | রোল (`parent_id`, `color` সহ) |
| `role_user` | ইউজার ↔ রোল |
| `role_permissions` | রোলের পারমিশন (JSON) |
| `settings` | সব সেটিং (key/value), মেইল ও এসএমএস কনফিগারেশনসহ |
| `user_activities` | অ্যাক্টিভিটি লগ |
| `mail_logs` | পাঠানো মেইলের লগ (বডি রাখা হয় না) |
| `sms_logs`, `sms_accounts` | এসএমএস লগ ও লোকাল ব্যালেন্স |
| `menus` | Menus পেজের আইটেম (sidebar নয়; guest লেআউটে ব্যবহৃত) |
| `sessions`, `password_reset_tokens`, `jobs`, `failed_jobs` | Laravel-এর সাধারণ টেবিল |

সাম্প্রতিক migration:

- `2026_09_27_000001_add_parent_id_to_roles_table` — রোল অনুক্রম
- `2026_09_27_000002_add_color_to_roles_table` — ব্যাজ রঙ

---

## ১৬. সীমাবদ্ধতা ও জানা সমস্যা

এগুলো এখনো ঠিক করা হয়নি:

- **ডিফল্ট সিড অ্যাকাউন্ট:** `0001_01_01_000001_create_users_and_roles_table` migration একটি ইউজার ও `encodex` রোল তৈরি করে, যার পাসওয়ার্ড কোডে লেখা এবং পাবলিক GitHub-এ আছে। নতুন ইনস্টলে সঙ্গে সঙ্গে সেই ইউজারের পাসওয়ার্ড বদলান বা ইউজারটি মুছে দিন। `encodex` রোলের ইউজার এখনো সব পারমিশন পায়।
- **Activity Log-এ পারমিশন চেক নেই:** `ActivityController` (`/{prefix}/activities`, export, statistics) কোনো পারমিশন চেক করে না — লগইন করা যেকোনো ইউজার সরাসরি URL দিয়ে সবার কার্যক্রম দেখতে পারে। Sidebar-এ `me_activity.view` দিয়ে শুধু মেনু লুকানো থাকে।
- **নতুন Laravel অ্যাপে migration সংঘর্ষ:** প্যাকেজ `users` ইত্যাদি টেবিল নিজে তৈরি করে, Laravel-এর ডিফল্ট migration-এর সাথে মেলে না।
- **Clear Data** প্রায় পুরো ডাটাবেস (users, roles, role_user, migrations ছাড়া) মুছে দেয়, শুধু MySQL-এ চলে।
- **OTP রেজিস্ট্রেশন/রিসেট** ফ্লোতে নিরাপত্তা সমস্যা আছে (কোড রেসপন্সে ফেরত যায়, রেট লিমিট নেই)। প্রোডাকশনে রেজিস্ট্রেশন ও forgot password বন্ধ রাখুন (Configurations পেজ), যতক্ষণ না ঠিক হয়।
- এসএমএস ফোন নম্বর শুধু বাংলাদেশি ফরম্যাট।
- `TelegramBotService` এখনো `.env` থেকে টোকেন পড়ে।
- কোনো স্বয়ংক্রিয় টেস্ট নেই।

এগুলোর বেশিরভাগের সমাধান `feature/codecanyon-production-readiness` ব্রাঞ্চে শুরু করা আছে (অসম্পূর্ণ, পরীক্ষা হয়নি)।
