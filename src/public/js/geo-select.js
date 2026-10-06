/**
 * Cascading geo selects (country -> division -> district -> upazila) via /api/geo.
 *
 * <select name="country_id"  data-geo-root data-geo-child="#division" data-selected="{{ old('country_id', $m->country_id) }}"></select>
 * <select name="division_id" id="division" data-geo-child="#district" data-selected="..."></select>
 * <select name="district_id" id="district" data-geo-child="#upazila"  data-selected="..."></select>
 * <select name="upazila_id"  id="upazila"  data-selected="..."></select>
 *
 * Optional on the root: data-geo-url="{{ url('api/geo') }}", data-geo-lang="bn" (show bn_name).
 * Every select may set data-placeholder. Requires jQuery.
 */
(function ($) {
    'use strict';

    function fill($select, items, lang) {
        var placeholder = $select.data('placeholder') || 'Select';
        var selected = String($select.data('selected') || '');

        $select.empty().append($('<option>', { value: '', text: placeholder }));
        $.each(items, function (_, item) {
            $select.append($('<option>', {
                value: item.id,
                text: lang === 'bn' && item.bn_name ? item.bn_name : item.name,
                selected: String(item.id) === selected
            }));
        });
        $select.prop('disabled', items.length === 0);

        // Load the next level for a preselected value (edit forms), only once.
        if (selected && $select.val() === selected) {
            $select.data('selected', '');
            $select.trigger('change');
        }
    }

    function reset($select) {
        if (!$select.length) {
            return;
        }
        $select.empty().append($('<option>', { value: '', text: $select.data('placeholder') || 'Select' })).prop('disabled', true);
        reset($($select.data('geo-child')));
    }

    function init($root) {
        var baseUrl = ($root.data('geo-url') || '/api/geo').replace(/\/$/, '');
        var lang = $root.data('geo-lang');
        var $chain = $root;

        while ($chain.length) {
            (function ($select) {
                var $child = $($select.data('geo-child'));
                $select.off('change.geo').on('change.geo', function () {
                    reset($child);
                    if (!$child.length || !this.value) {
                        return;
                    }
                    $.getJSON(baseUrl + '/' + encodeURIComponent(this.value) + '/children', function (res) {
                        fill($child, res.data, lang);
                    });
                });
                $chain = $child;
            })($chain);
        }

        reset($($root.data('geo-child')));
        $.getJSON(baseUrl + '/countries', function (res) {
            fill($root, res.data, lang);
        });
    }

    window.initGeoSelect = function (root) {
        $(root || '[data-geo-root]').each(function () {
            init($(this));
        });
    };

    $(function () {
        window.initGeoSelect();
    });
})(jQuery);
