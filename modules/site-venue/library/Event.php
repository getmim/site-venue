<?php
/**
 * Event
 * @package site-venue
 * @version 0.0.1
 */

namespace SiteVenue\Library;

use LibCacheOutput\Library\Cleaner;

class Event
{
    static function clear(array $data): void{
        $page = $data['page'] ?? $data['old'] ?? null;

        // clear output cache
        if($page && module_exists('lib-cache-output'))
            Cleaner::router('siteVenueSingle', (array)$page);

        // Clear static page RSS Feed output cache
        // Clear global RSS Feed output cache
        // Clear global Sitemap output cache
    }
}