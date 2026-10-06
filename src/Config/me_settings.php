<?php

return [

    'meta_title' => env('ME_META_TITLE', 'ME Dashboard - Laravel Admin Panel'),

    /*
    |--------------------------------------------------------------------------
    | Route Prefix
    |--------------------------------------------------------------------------
    | প্যাকেজের সব অ্যাডমিন পেজের URL prefix। ড্যাশবোর্ড হবে: {APP_URL}/{prefix}
    | লগইনের পরে এখানে যাবে। .env: METHEME_ROUTE_PREFIX=admin
    | অন্য প্যাকেজের একই URL (যেমন /admin/users) থাকলে metheme-এর route কাজ করবে।
    */
    'route_prefix' => trim((string) env('METHEME_ROUTE_PREFIX', 'admin'), '/'),

    // Route name of the admin home page (after login, logo links, /{prefix}). metheme has no dashboard page;
    // a package or the app sets this (ecom uses 'ecom.dashboard'). Empty = the profile page.
    'home_route' => env('METHEME_HOME_ROUTE'),

    /*
    |--------------------------------------------------------------------------
    | Mail Templates
    |--------------------------------------------------------------------------
    | me_mail($to, $subject, $content, $data, 'template-name') দিয়ে ব্যবহার করুন।
    | নাম => Blade view। নিজের টেমপ্লেট যোগ করতে এখানে লাইন যোগ করুন, অথবা সরাসরি
    | যেকোনো view-এর নাম দিন (যেমন 'emails.invoice')।
    | view-এ পাওয়া যাবে: $title, $content, $otp, $companyName, $companyLogo,
    | $currentYear, $showGreeting, $greetings এবং $data-তে দেওয়া সবকিছু।
    */
    /*
    |--------------------------------------------------------------------------
    | Data Change Log
    |--------------------------------------------------------------------------
    | me_change_log() দিয়ে store/update/delete-এর আগে-পরের ডেটা Activity Log-এ রাখা হয়।
    | hidden_fields: মান কখনো লগ হবে না, শুধু "বদলেছে" দেখাবে।
    | ignore_fields: বদলালেও পরিবর্তন হিসেবে ধরা হবে না।
    */
    'data_change_log' => [
        'enabled'          => true,
        'hidden_fields'    => ['password', 'remember_token', 'mail_password', 'sms_api_key', 'api_key', 'token', 'secret'],
        'ignore_fields'    => ['created_at', 'updated_at', 'email_verified_at'],
        'max_value_length' => 2000,
    ],

    'mail_templates' => [
        'default' => 'me::mail.message',       // common layout — যেকোনো মেসেজ (me_mail-এর ডিফল্ট)
        'auth'    => 'me::mail.auth-layout',   // OTP / ভেরিফিকেশন কোড ($otp), common layout
        'notice'  => 'me::mail.notice-layout', // পুরনো গ্রেডিয়েন্ট নোটিশ ডিজাইন
    ],

    /*
    |--------------------------------------------------------------------------
    | Developer / Author Name
    |--------------------------------------------------------------------------
    | প্যাকেজ বা প্রজেক্টের নির্মাতার নাম এখানে থাকবে।
    */
    'meta_author' => env('ME_META_AUTHOR', 'M. Estiaque Ahmed Khan'),

    /*
    |--------------------------------------------------------------------------
    | Dashboard Description
    |--------------------------------------------------------------------------
    | এটি আপনার অ্যাডমিন প্যানেলের একটি ছোট বর্ণনা যা SEO-তে সাহায্য করবে।
    */
    'meta_description' => env('ME_META_DESCRIPTION', 'ME Dashboard is a high-performance, flexible Admin Panel developed by M. Estiaque Ahmed Khan using Laravel and modern web technologies.'),

    /*
    |--------------------------------------------------------------------------
    | SEO Keywords
    |--------------------------------------------------------------------------
    | আপনার প্রজেক্টের সাথে সম্পর্কিত কি-ওয়ার্ডগুলো এখানে কমা দিয়ে লিখুন।
    */
    'meta_keywords' => env('ME_META_KEYWORDS', 'M. Estiaque Ahmed Khan, Admin Dashboard, Laravel Package, ESTIAQUE, PHP Developer, Full-Stack Developer, Web Panel'),

    /*
    |--------------------------------------------------------------------------
    | Package Version
    |--------------------------------------------------------------------------
    | আপনার তৈরি করা অ্যাডমিন প্যানেলের বর্তমান ভার্সন।
    */
    'version' => '1.0.0',

    /*
    |--------------------------------------------------------------------------
    | Footer Credits
    |--------------------------------------------------------------------------
    | ড্যাশবোর্ডের নিচে দেখানোর জন্য ক্রেডিট টেক্সট।
    */
    'credits' => 'Developed by M. Estiaque Ahmed Khan',

    /*
    |--------------------------------------------------------------------------
    | Developer Mode
    |--------------------------------------------------------------------------
    | চালু থাকলে (METHEME_DEVELOPER_MODE=true) নিচের ইমেইলগুলোর লগইন করা ইউজার
    | কোনো permission চেক ছাড়াই সব কিছুতে access পাবে। ডিফল্টে বন্ধ।
    | শুধু নিজের নিয়ন্ত্রিত সার্ভারের .env-এ চালু করুন।
    | METHEME_DEVELOPER_EMAILS: কমা দিয়ে একাধিক ইমেইল দেওয়া যাবে।
    */
    'developer_mode' => (bool) env('METHEME_DEVELOPER_MODE', false),

    'developer_emails' => array_values(array_filter(array_map(
        fn ($email) => strtolower(trim($email)),
        explode(',', (string) env('METHEME_DEVELOPER_EMAILS', ''))
    ))),

    /*
    |--------------------------------------------------------------------------
    | Developer-only Permissions
    |--------------------------------------------------------------------------
    | এই permission-গুলো কোনো role-এ দেওয়া যাবে না (role পেজের তালিকায় দেখাবে না)।
    | শুধু developer mode চালু থাকলে তালিকার ইমেইলের ইউজাররা পাবে।
    | নির্দিষ্ট key অথবা wildcard ("me.*") দেওয়া যাবে।
    */
    'developer_only_permissions' => [],

    /*
    |--------------------------------------------------------------------------
    | Media (me_media টেবিল — সব প্যাকেজের ফাইল/ছবি এক জায়গায়)
    |--------------------------------------------------------------------------
    | disk: কোন storage disk-এ নতুন ফাইল যাবে (public; পরে s3 দেওয়া যায়)।
    | directory: ফাইলের ফোল্ডার — {directory}/YYYY/MM/{uuid}.{ext}
    | conversions: ছোট সংস্করণ (webp) — নাম => সবচেয়ে বড় দিক (px)। মডেলের
    |   mediaCollections()-এ 'conversions' দিয়ে বদলানো যায়।
    | max_kb / mimes: collection-এ আলাদা না দিলে এগুলো।
    | cleanup: অ্যাটাচ না হওয়া আপলোড কত ঘণ্টা পর, ট্র্যাশ কত দিন পর মুছবে।
    */
    'media' => [
        'disk' => env('ME_MEDIA_DISK', 'public'),
        'directory' => 'media',
        'quality' => 80,
        'conversions' => [
            'thumb' => 400,
        ],
        'max_kb' => 5120,
        'mimes' => 'jpg,jpeg,png,webp,gif,svg,ico,pdf,doc,docx,xls,xlsx,csv,txt,zip,mp4',
        'cleanup' => [
            'unattached_hours' => 24,
            'trash_days' => 30,
        ],
        // Model class => readable name shown in the Media Library (packages add their own)
        'owners' => [
            \ME\Models\User::class => 'User',
            \ME\Models\Setting::class => 'Setting',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Developer-grantable Permissions
    |--------------------------------------------------------------------------
    | permissions.php-এর মতো একই গঠন। এগুলো role edit পেজে শুধু developer
    | (developer mode চালু + তালিকার ইমেইল) লগইন থাকলে দেখা যাবে, যাতে developer
    | কোনো role-কে এগুলো দিতে পারে। দেওয়ার পর সেই role-এর ইউজাররা স্বাভাবিকভাবেই
    | পাবে। সাধারণ অ্যাডমিন role সেভ করলেও এগুলো মুছে যাবে না।
    */
    'developer_permissions' => [
        'me_setting' => [
            'title' => 'Settings (ME)',
            'actions' => 'configurations,settings,mail,sms',
        ],
        'me_sms' => [
            'title' => 'SMS Log & Balance',
            'actions' => 'view,recharge',
        ],
        'me_mail' => [
            'title' => 'Mail Log',
            'actions' => 'view',
        ],
    ],

];
