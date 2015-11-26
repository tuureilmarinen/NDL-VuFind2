<?php
/**
 * Helper class for displaying search-related HTML chunks.
 *
 * PHP version 5
 *
 * Copyright (C) The National Library of Finland 2015.
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
 * @category VuFind2
 * @package  View_Helpers
 * @author   Ere Maijala <ere.maijala@helsinki.fi>
 * @license  http://opensource.org/licenses/gpl-2.0.php GNU General Public License
 * @link     http://vufind.org/wiki/vufind2:developer_manual Wiki
 */
namespace Finna\View\Helper\Root;
use minSO;
use fminSO;
use \VuFind\Search\Results\PluginManager;
use \VuFind\Db\Table\PluginManager as TableManager;
use \Zend\Session\SessionManager;

/**
 * Helper class for displaying search-related HTML chunks.
 *
 * @category VuFind2
 * @package  View_Helpers
 * @author   Ere Maijala <ere.maijala@helsinki.fi>
 * @license  http://opensource.org/licenses/gpl-2.0.php GNU General Public License
 * @link     http://vufind.org/wiki/vufind2:developer_manual Wiki
 */
class Search extends \VuFind\View\Helper\Bootstrap3\Search
{
    /*
     * Results manager
     *
     * @var PluginManager
     */
    protected $resultsManager;

    /*
     * Table manager
     *
     * @var TableManager
     */
    protected $tableManager;

    /*
     * Session manager
     *
     * @var SessionManager
     */
    protected $sessionManager;

    /**
     * Constructor
     *
     * @param PluginManager  $resultsManager Results manager
     * @param TableManager   $tableManager   Table manager
     * @param SessionManager $sessionManager Session manager
     */
    public function __construct(PluginManager $resultsManager,
        TableManager $tableManager, SessionManager $sessionManager
    ) {
        $this->resultsManager = $resultsManager;
        $this->tableManager = $tableManager;
        $this->sessionManager = $sessionManager;
    }

    /**
     * Create a new search object with filters from a previous search
     *
     * @param \VuFind\Search\Base\Results $search         Search object
     * @param \VuFind\Search\Base\Results $previousSearch Previous search object
     *
     * @return \VuFind\Search\Base\Results New search object
     */
    public function getSearchWithPreviousFilters(\VuFind\Search\Base\Results $search,
        \VuFind\Search\Base\Results $previousSearch
    ) {
        // TODO: Try to do this cleaner with $search->params() when urlQueryHelper
        // cache is fixed.
        $minSO = new fminSO($search);
        $minSO->setParentSO(new minSO($search));
        $minSOPrevious = new minSO($previousSearch);
        $minSO->getParentSO()->f = $minSOPrevious->f;
        return $minSO->deminify($this->resultsManager);
    }

    /**
     * Retrieve any previous search from history of current session
     *
     * @param \VuFind\Search\Base\Results $currentSearch Current search object
     *
     * @return false|\VuFind\Search\Base\Results
     */
    public function getPreviousSearch(\VuFind\Search\Base\Results $currentSearch)
    {
        $searchClass = $currentSearch->getParams()->getSearchClassId();
        $currentId = $currentSearch->getSearchId();

        // Retrieve search history
        $searchTable = $this->tableManager->get('Search');
        $searches = $searchTable
            ->getPreviousSearches($this->sessionManager->getId());

        // Try to find a suitable previous search
        foreach ($searches as $search) {
            $minSO = $search->getSearchObject();
            $searchObject = $minSO->deminify($this->resultsManager);
            // Ignore current search
            if ($searchObject->getSearchId() == $currentId) {
                continue;
            }
            // Stop if we encounter a different search class
            if ($searchObject->getParams()->getSearchClassId() != $searchClass) {
                break;
            }
            return $searchObject;
        }
        return false;
    }

    /**
     * Support function to display spelling suggestions.
     *
     * @param string                          $msg     HTML to display at the top of
     * the spelling section.
     * @param \VuFind\Search\Base\Results     $results Results object
     * @param \Zend\View\Renderer\PhpRenderer $view    View renderer object
     *
     * @return string
     */
    public function renderSpellingSuggestions($msg, $results, $view)
    {
        $spellingSuggestions = $results->getSpellingSuggestions();
        if (empty($spellingSuggestions)) {
            return '';
        }

        $html = '<div class="spelling-suggestions">';
        $html .= $msg;
        foreach ($spellingSuggestions as $term => $details) {
            $html .= ' ' . $view->escapeHtml($term) . ' &raquo; ';
            $i = 0;
            foreach ($details['suggestions'] as $word => $data) {
                if ($i++ > 0) {
                    $html .= ', ';
                }
                $html .= '<a href="'
                    . $results->getUrlQuery()
                        ->replaceTerm($term, $data['new_term'])
                    . '">' . $view->escapeHtml($word) . '</a>';
                if (isset($data['expand_term']) && !empty($data['expand_term'])) {
                    $url = $results->getUrlQuery()
                        ->replaceTerm($term, $data['expand_term']);
                    $html .= ' ' . $this->renderExpandLink($url, $view);
                }
            }
        }
        $html .= '</div>';
        return $html;
    }

    /**
     * Render an expand link.
     *
     * @param string                          $url  Link href
     * @param \Zend\View\Renderer\PhpRenderer $view View renderer object
     *
     * @return string
     */
    protected function renderExpandLink($url, $view)
    {
        return '<a href="' . $url . '" title="' . $view->transEsc('spell_expand_alt')
            . '"><i class="fa fa-plus-small"></i></a>';
    }
}