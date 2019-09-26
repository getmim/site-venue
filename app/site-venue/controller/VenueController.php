<?php
/**
 * VenueController
 * @package site-venue
 * @version 0.0.1
 */

namespace SiteVenue\Controller;

use SiteVenue\Meta\Venue as Meta;
use Venue\Model\Venue;
use LibFormatter\Library\Formatter;

class VenueController extends \Site\Controller
{
    public function singleAction() {
        $slug = $this->req->param->slug;

        $page = Venue::getOne(['slug'=>$slug]);
        if(!$page)
            return $this->show404();

        $page = Formatter::format('venue', $page, ['user']);

        $params = [
            'page' => $page,
            'meta' => Meta::single($page)
        ];

        // deb($page, json_encode($params['meta'],  JSON_PRETTY_PRINT));

        $this->res->render('venue/single', $params);
        $this->res->setCache(86400);
        $this->res->send();
    }
}