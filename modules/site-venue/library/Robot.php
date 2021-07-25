<?php
/**
 * Robot
 * @package site-venue
 * @version 0.0.1
 */

namespace SiteVenue\Library;

use Venue\Model\Venue;

class Robot
{
    static private function getPages(): ?array{
        $cond = [
            'status' => 2,
            'updated' => ['__op', '>', date('Y-m-d H:i:s', strtotime('-2 days'))]
        ];
        $pages = Venue::get($cond);
        if(!$pages)
            return null;

        return $pages;
    }

    static function feed(): array {
        $mim = &\Mim::$app;

        $pages = self::getPages();
        if(!$pages)
            return [];

        $result = [];
        foreach($pages as $page){
            $route = $mim->router->to('siteVenueSingle', (array)$page);
            $meta  = json_decode($page->meta);
            $title = $meta->title ?? $page->title;
            $desc  = $meta->description ?? substr($page->content, 0, 100);

            $result[] = (object)[
                'description'   => $desc,
                'page'          => $route,
                'published'     => $page->created,
                'updated'       => $page->updated,
                'title'         => $title,
                'guid'          => $route
            ];
        }

        return $result;
    }

    static function sitemap(): array {
        $mim = &\Mim::$app;

        $pages = self::getPages();
        if(!$pages)
            return [];

        $result = [];
        foreach($pages as $page){
            $route  = $mim->router->to('siteVenueSingle', (array)$page);
            $result[] = (object)[
                'page'          => $route,
                'updated'       => $page->updated,
                'priority'      => '0.4',
                'changefreq'    => 'monthly'
            ];
        }

        return $result;
    }
}
