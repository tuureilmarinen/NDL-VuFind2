<?php
/**
 * DIGI Search Options
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

/**
 * DIGI Search Options
 *
 * @category VuFind
 * @package  Search_MetaLib
 * @author   Samuli Sillanpää <samuli.sillanpaa@helsinki.fi>
 * @license  http://opensource.org/licenses/gpl-2.0.php GNU General Public License
 * @link     https://vufind.org Main Page
 */
class Options extends \Finna\Search\Solr\Options
{
    /**
     * Overall default sort option
     *
     * @var string
     */
    protected $defaultSort = 'relevance';

    /**
     * Configuration file to read search settings from
     *
     * @var string
     */
    protected $searchIni = 'digi';

    /**
     * Advanced search operators
     *
     * @var array
     */
    protected $advancedOperators = [];

    /**
     * Is autocomplete enabled?
     *
     * @return bool
     */
    public function autocompleteEnabled()
    {
        return false;
    }

    /**
     * Basic 'getter' for advanced search handlers.
     *
     * @return array
     */
    public function getAdvancedSearchAction()
    {
        return false;
    }

    /**
     * Load all recommendation settings from the relevant ini file.  Returns an
     * associative array where the key is the location of the recommendations (top
     * or side) and the value is the settings found in the file (which may be either
     * a single string or an array of strings).
     *
     * @param string $handler Name of handler for which to load specific settings.
     *
     * @return array associative: location (top/side/etc.) => search settings
     */
    public function getRecommendationSettings($handler = null)
    {
        return ['side' => ['digiNavigation']];
    }

    /**
     * Return the route name for the search results action.
     *
     * @return string
     */
    public function getSearchAction()
    {
        return 'digi-results';
    }
}
