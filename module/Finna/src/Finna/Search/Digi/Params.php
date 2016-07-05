<?php
/**
 * DIGI Search Parameters
 *
 * PHP version 5
 *
 * Copyright (C) The National Library of Finland 2016.
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License version 2,
 * as published by the Free Software Foundation.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
 *
 * @category VuFind
 * @package  Search_MetaLib
 * @author   Samuli Sillanpää <samuli.sillanpaa@helsinki.fi>
 * @license  http://opensource.org/licenses/gpl-2.0.php GNU General Public License
 * @link     https://vufind.org Main Page
 */
namespace Finna\Search\Digi;
use VuFindSearch\ParamBag;

/**
 * DIGI Search Parameters
 *
 * @category VuFind
 * @package  Search_MetaLib
 * @author   Samuli Sillanpää <samuli.sillanpaa@helsinki.fi>
 * @license  http://opensource.org/licenses/gpl-2.0.php GNU General Public License
 * @link     https://vufind.org Main Page
 */
class Params extends \VuFind\Search\Base\Params
{
    use \Finna\Search\FinnaParams;

    /**
     * Default search handler
     *
     * @var string
     */
    protected $handler = 'newspaper';

    /**
     * Map sort options between Finna and DIGI
     *
     * @var array
     */
    protected $sortMap = [
       'relevance' => 'RELEVANCE',
       'main_date_str desc' => 'DATE_DESC',
       'main_date_str asc' => 'DATE',
       'last_indexed desc' => 'IMPORT_DATE'
    ];

    /**
     * Map from search handler to external URL part
     *
     * @var array
     */
    protected $externalUrlMap = [
       'newspaper' => 'sanomalehti',
       'journal' => 'aikakausi',
       'printing' => 'pienpainate'
    ];

    /**
     * Create search backend parameters.
     *
     * @return ParamBag
     */
    public function getBackendParameters()
    {
        $backendParams = new ParamBag();

        $sort = 'relevance';
        if ($selectedSort = $this->getSort()) {
            if (isset($this->sortMap[$selectedSort])) {
                $sort = $selectedSort;
            }
        }
        $this->sort = $sort;

        $backendParams->set('sort', $this->sortMap[$this->sort]);
        $backendParams->set('handler', $this->handler);
        return $backendParams;
    }

    /**
     * Pull the search parameters
     *
     * @param \Zend\StdLib\Parameters $request Parameter object representing user
     * request.
     *
     * @return void
     */
    public function initFromRequest($request)
    {
        parent::initFromRequest($request);
        if (isset($this->filterList['handler'])) {
            $this->handler = $this->filterList['handler'][0];
        }
    }

    /**
     * Return current search handler
     *
     * @return string
     */
    public function getHandler()
    {
        return $this->handler;
    }

    /**
     * Return external URL for the current search in digi.kansalliskirjasto.fi
     *
     * @return string
     */
    public function getExternalSearchLink()
    {
        $url = 'http://tethys.lib.helsinki.fi/';
        $url .= isset($this->externalUrlMap[$this->handler])
            ? $this->externalUrlMap[$this->handler] : 'newspaper';
        $url .= '/search';

        $params = [
            'query' => $this->query->getString(),
            'orderBy' => $this->sortMap[$this->sort],
            'fuzzy' => 'true'
        ];
        $url .= '?' . http_build_query($params);

        return $url;
    }
}
