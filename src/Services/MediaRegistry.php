<?php

namespace ME\Services;

/**
 * Lets every package tell metheme about its media, independent of service-provider order:
 *   MediaRegistry::owner(Product::class, 'Product');          // name in the Media Library
 *   MediaRegistry::import(['type' => 'column', …]);           // old file columns for metheme:media-import
 */
class MediaRegistry
{
    /** @var array<class-string, string> */
    private static array $owners = [];

    /** @var array<int, array<string, mixed>> */
    private static array $imports = [];

    /** @var array<int, callable> */
    private static array $afterImport = [];

    public static function owner(string $class, string $name): void
    {
        self::$owners[$class] = $name;
    }

    /**
     * @return array<class-string, string>
     */
    public static function owners(): array
    {
        return self::$owners + (array) config('me_settings.media.owners', []);
    }

    public static function ownerName(string $class): string
    {
        return self::owners()[$class] ?? class_basename($class);
    }

    /**
     * Register an old-file source for metheme:media-import. Types:
     *   ['type' => 'setting', 'key' => 'app_logo', 'directory' => 'images/app_logo']
     *   ['type' => 'column', 'model' => User::class, 'column' => 'profile_image', 'collection' => 'avatar', 'directory' => 'images/profile_images']
     *   ['type' => 'table', 'table' => 'ecom_product_images', 'model' => Product::class, 'foreign_key' => 'product_id',
     *    'path_column' => 'path', 'collection' => 'gallery', 'order_column' => 'sort_order', 'conversions' => ['thumb' => 'thumbnail']]
     * "directory" is put in front of values that are only a file name. Paths are on the "public" disk unless 'disk' is given.
     *
     * @param  array<string, mixed>  $source
     */
    public static function import(array $source): void
    {
        self::$imports[] = $source;
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public static function imports(): array
    {
        return self::$imports;
    }

    /**
     * Run after metheme:media-import (e.g. point old foreign keys at the new media ids).
     * The callback gets a function that returns the media id imported from an origin like "ecom_product_images:12".
     */
    public static function afterImport(callable $callback): void
    {
        self::$afterImport[] = $callback;
    }

    /**
     * @return array<int, callable>
     */
    public static function afterImportCallbacks(): array
    {
        return self::$afterImport;
    }
}
