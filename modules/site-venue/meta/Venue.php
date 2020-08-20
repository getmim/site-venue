<?php
/**
 * Page Meta Provider
 * @package site-static-page
 * @version 0.0.1
 */

namespace SiteVenue\Meta;

class Venue
{
    static function single(object $page){
        $result = [
            'head' => [],
            'foot' => []
        ];

        $home_url = \Mim::$app->router->to('siteHome');

        // reset meta
        if(!is_object($page->meta))
            $page->meta = (object)[];

        $def_meta = [
            'title'         => $page->title,
            'description'   => $page->content->chars(160),
            'schema'        => 'LocalBusiness',
            'keyword'       => ''
        ];

        foreach($def_meta as $key => $value){
            if(!isset($page->meta->$key) || !$page->meta->$key)
                $page->meta->$key = $value;
        }

        $result['head'] = [
            'description'       => $page->meta->description,
            'published_time'    => $page->created,
            'schema.org'        => [],
            'type'              => 'article',
            'title'             => $page->meta->title,
            'updated_time'      => $page->updated,
            'url'               => $page->page,
            'metas'             => []
        ];

        // schema breadcrumbList
        $result['head']['schema.org'][] = [
            '@context'  => 'http://schema.org',
            '@type'     => 'BreadcrumbList',
            'itemListElement' => [
                [
                    '@type' => 'ListItem',
                    'position' => 1,
                    'item' => [
                        '@id' => $home_url,
                        'name' => \Mim::$app->config->name
                    ]
                ],
                [
                    '@type' => 'ListItem',
                    'position' => 2,
                    'item' => [
                        '@id' => $home_url . '#venue',
                        'name' => 'Venues'
                    ]
                ]
            ]
        ];

        // schema logo
        $meta_image = null;
        if($page->logo->target){
            $meta_image = [
                '@context'   => 'http://schema.org',
                '@type'      => 'ImageObject',
                'contentUrl' => $page->logo,
                'url'        => $page->logo
            ];
        }

        $sameAs = [];
        if($page->socials){
            foreach($page->socials as $url)
                $sameAs[] = $url;
        }

        // schema page
        $schema = [
            '@context'      => 'http://schema.org',
            'name'          => $page->meta->title,
            'description'   => $page->meta->description,
            'url'           => $page->page,
        ];
        if($page->meta->schema === 'LocalBusiness'){
            $schema['@type'] = $page->meta->schema;

            $price = $page->prices;
            $schema['priceRange'] = sprintf('%s %d - %s %d', $price->currency->value, $price->min->value, $price->currency->value, $price->max->value);

            if($meta_image)
                $schema['logo'] = $meta_image;

            $open = '';
            $open_days = [];
            foreach($page->open_days as $enum)
                $open_days[] = substr($enum->label, 0, 2);
            $open.= implode(',', $open_days);
            $open.= ' ' . $page->open_hours->open . '-' . $page->open_hours->close;

            $schema['openingHours'] = $open;

            $contact = $page->contact;
            if(isset($contact->phone) && $contact->phone)
                $schema['telephone'] = $contact->phone;
            if(isset($contact->address) && $contact->address)
                $schema['address'] = $contact->address;
        }else{
            $schema['@type']        = 'Article';
            $schema['dateCreated']  = $page->created;
            $schema['dateModified'] = $page->updated;
            $schema['datePublished']= $page->created;
            $schema['publisher']    = \Mim::$app->meta->schemaOrg( \Mim::$app->config->name );
        }

        if($meta_image)
            $schema['image'] = $meta_image;

        if($sameAs)
            $schema['sameAs'] = $sameAs;

        $result['head']['schema.org'][] = $schema;

        return $result;
    }
}