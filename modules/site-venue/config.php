<?php

return [
    '__name' => 'site-venue',
    '__version' => '0.0.1',
    '__git' => 'git@github.com:getmim/site-venue.git',
    '__license' => 'MIT',
    '__author' => [
        'name' => 'Iqbal Fauzi',
        'email' => 'iqbalfawz@gmail.com',
        'website' => 'http://iqbalfn.com/'
    ],
    '__files' => [
        'modules/site-venue' => ['install','update','remove'],
        'app/site-venue' => ['install','remove'],
        'theme/site/venue' => ['install','remove']
    ],
    '__dependencies' => [
        'required' => [
            [
                'venue' => NULL
            ],
            [
                'site' => NULL
            ],
            [
                'site-meta' => NULL
            ],
            [
                'lib-formatter' => NULL
            ]
        ],
        'optional' => [
            [
                'lib-event' => NULL
            ],
            [
                'lib-cache-output' => NULL
            ]
        ]
    ],
    'autoload' => [
        'classes' => [
            'SiteVenue\\Controller' => [
                'type' => 'file',
                'base' => ['app/site-venue/controller','modules/site-venue/controller']
            ],
            'SiteVenue\\Library' => [
                'type' => 'file',
                'base' => 'modules/site-venue/library'
            ],
            'SiteVenue\\Meta' => [
                'type' => 'file',
                'base' => 'modules/site-venue/meta'
            ]
        ],
        'files' => []
    ],
    'routes' => [
        'site' => [
            'siteVenueIndex' => [
                'path' => [
                    'value' => '/venue'
                ],
                'method' => 'GET',
                'handler' => 'SiteVenue\\Controller\\Venue::index'
            ],
            'siteVenueSingle' => [
                'path' => [
                    'value' => '/venue/read/(:slug)',
                    'params' => [
                        'slug' => 'slug'
                    ]
                ],
                'method' => 'GET',
                'handler' => 'SiteVenue\\Controller\\Venue::single'
            ],
            'siteVenueFeed' => [
                'path' => [
                    'value' => '/venue/feed.xml'
                ],
                'method' => 'GET',
                'handler' => 'SiteVenue\\Controller\\Robot::feed'
            ]
        ]
    ],
    'libFormatter' => [
        'formats' => [
            'venue' => [
                'page' => [
                    'type' => 'router',
                    'router' => [
                        'name' => 'siteVenueSingle',
                        'params' => [
                            'slug' => '$slug'
                        ]
                    ]
                ]
            ]
        ]
    ],
    'libEvent' => [
        'events' => [
            'venue:created' => [
                'SiteVenue\\Library\\Event::clear' => TRUE
            ],
            'venue:deleted' => [
                'SiteVenue\\Library\\Event::clear' => TRUE
            ],
            'venue:updated' => [
                'SiteVenue\\Library\\Event::clear' => TRUE
            ]
        ]
    ],
    'site' => [
        'robot' => [
            'feed' => [
                'SiteVenue\\Library\\Robot::feed' => TRUE
            ],
            'sitemap' => [
                'SiteVenue\\Library\\Robot::sitemap' => TRUE
            ]
        ]
    ]
];
