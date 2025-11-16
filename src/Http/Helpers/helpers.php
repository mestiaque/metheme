<?php

if (!function_exists('get_setting')) {
    /**
     * Get a setting value by key
     *
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    function get_setting($key, $default = null)
    {
        return Encodex\Metheme\Models\Setting::get($key, $default);
    }
}


if (!function_exists('toBanglaNumber')) {
    function toBanglaNumber($number, $decimals = 0) {
        $number = (float) $number; // ensure numeric
        $formatted = number_format($number, $decimals);

        // Bangla conversion only if locale is 'bn'
        if (app()->getLocale() === 'bn') {
            $en = ['0','1','2','3','4','5','6','7','8','9'];
            $bn = ['০','১','২','৩','৪','৫','৬','৭','৮','৯'];
            return str_replace($en, $bn, $formatted);
        }

        // English locale
        return $formatted;
    }
}

//banglaPhone
if (!function_exists('toBanglaPhone')) {
    function toBanglaPhone($phone) {
        // শুধুমাত্র বাংলা locale হলে কনভার্ট করবে
        if (app()->getLocale() === 'bn') {
            $en = ['0','1','2','3','4','5','6','7','8','9'];
            $bn = ['০','১','২','৩','৪','৫','৬','৭','৮','৯'];
            return str_replace($en, $bn, $phone);
        }

        // অন্য যেকোনো locale হলে আসল ইংরেজি নম্বর ফেরত দেবে
        return $phone;
    }
}


if (!function_exists('formatDate')) {
    /**
     * Format date fully in Bangla (numbers + month)
     *
     * @param \DateTime|string|null $date
     * @param string $format
     * @return string
     */
    function formatDate($date, $format = 'd M, Y')
    {
        if (!$date) {
            return '';
        }

        // Convert string to Carbon instance if needed
        $carbonDate = $date instanceof \Carbon\Carbon ? $date : \Carbon\Carbon::parse($date);

        $formatted = $carbonDate->format($format); // e.g. "12 Dec, 2025"

        if (app()->getLocale() === 'bn') {
            // Digits mapping
            $en = ['0','1','2','3','4','5','6','7','8','9'];
            $bn = ['০','১','২','৩','৪','৫','৬','৭','৮','৯'];
            $formatted = str_replace($en, $bn, $formatted);

            // Month mapping
            $months = [
                'Jan' => 'জানুয়ারি',
                'Feb' => 'ফেব্রুয়ারি',
                'Mar' => 'মার্চ',
                'Apr' => 'এপ্রিল',
                'May' => 'মে',
                'Jun' => 'জুন',
                'Jul' => 'জুলাই',
                'Aug' => 'অগাস্ট',
                'Sep' => 'সেপ্টেম্বর',
                'Oct' => 'অক্টোবর',
                'Nov' => 'নভেম্বর',
                'Dec' => 'ডিসেম্বর',
            ];

            foreach ($months as $enMonth => $bnMonth) {
                $formatted = str_replace($enMonth, $bnMonth, $formatted);
            }
        }

        return $formatted;
    }
}

if (!function_exists('formatDateTime')) {
    /**
     * Format date with Bangla numbers, month, and time (hour:minute:second AM/PM)
     * Default format: 'd M, Y h:i:s A'
     *
     * @param \DateTime|string|null $date
     * @param string|null $format
     * @return string
     */
    function formatDateTime($date, $format = null)
    {
        if (!$date) {
            return '';
        }

        // Default format if not provided
        if (!$format) {
            $format = 'd M, Y h:i:s A';
        }

        // Convert string to Carbon instance if needed
        $carbonDate = $date instanceof \Carbon\Carbon ? $date : \Carbon\Carbon::parse($date);

        $formatted = $carbonDate->format($format);

        if (app()->getLocale() === 'bn') {
            // Digits mapping
            $en = ['0','1','2','3','4','5','6','7','8','9'];
            $bn = ['০','১','২','৩','৪','৫','৬','৭','৮','৯'];
            $formatted = str_replace($en, $bn, $formatted);

            // Month mapping
            $months = [
                'Jan' => 'জানুয়ারি',
                'Feb' => 'ফেব্রুয়ারি',
                'Mar' => 'মার্চ',
                'Apr' => 'এপ্রিল',
                'May' => 'মে',
                'Jun' => 'জুন',
                'Jul' => 'জুলাই',
                'Aug' => 'অগাস্ট',
                'Sep' => 'সেপ্টেম্বর',
                'Oct' => 'অক্টোবর',
                'Nov' => 'নভেম্বর',
                'Dec' => 'ডিসেম্বর',
            ];
            foreach ($months as $enMonth => $bnMonth) {
                $formatted = str_replace($enMonth, $bnMonth, $formatted);
            }

            // AM/PM mapping
            $ampm = [
                'AM' => 'পূর্বাহ্ণ',
                'PM' => 'অপরাহ্ণ',
            ];
            foreach ($ampm as $enAmpm => $bnAmpm) {
                $formatted = str_replace($enAmpm, $bnAmpm, $formatted);
            }
        }

        return $formatted;
    }

    if (!function_exists('banglaYear')) {
        /**
         * Format only year in Bangla (e.g. ২০২৫)
         *
         * @param \DateTime|string|null $date
         * @return string
         */
        function banglaYear($date = null)
        {
            if (!$date) {
                $date = now();
            }

            // Convert string to Carbon instance if needed
            $carbonDate = $date instanceof \Carbon\Carbon ? $date : \Carbon\Carbon::parse($date);

            $year = $carbonDate->format('Y'); // e.g. "2025"

            if (app()->getLocale() === 'bn') {
                $en = ['0','1','2','3','4','5','6','7','8','9'];
                $bn = ['০','১','২','৩','৪','৫','৬','৭','৮','৯'];
                $year = str_replace($en, $bn, $year);
            }

            return $year;
        }
    }

}



