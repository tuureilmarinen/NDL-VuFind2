<?php
/**
 * Authority link view helper
 *
 * PHP version 5
 *
 * Copyright (C) The National Library of Finland 2017.
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
 * @package  View_Helpers
 * @author   Samuli Sillanpää <samuli.sillanpaa@helsinki.fi>
 * @license  http://opensource.org/licenses/gpl-2.0.php GNU General Public License
 * @link     http://vufind.org   Main Site
 */
namespace Finna\View\Helper\Root;

/**
 * Authority link view helper
 *
 * @category VuFind
 * @package  View_Helpers
 * @author   Samuli Sillanpää <samuli.sillanpaa@helsinki.fi>
 * @license  http://opensource.org/licenses/gpl-2.0.php GNU General Public License
 * @link     http://vufind.org   Main Site
 */
class Authority extends \Zend\View\Helper\AbstractHelper
{
    /**
     * Configuration
     *
     * @var \Zend\Config\Config
     */
    protected $config;

    /**
     * Constructor
     *
     * @param Zend\Config\Config $config Configuration
     */
    public function __construct($config)
    {
        $this->config = $config;
    }

    /**
     * Returns HTML for a authority link.
     *
     * @param string $url          Link URL
     * @param string $label        Link label
     * @param string $id           Authority id
     * @param string $type         Authority type
     * @param string $recordSource Biblio record datasource
     *
     * @return string|null
     */
    public function link($url, $label, $id, $type, $recordSource)
    {
        if (empty($this->config->Authority->enabled)) {
            return null;
        }
        
        return $this->getView()->render(
            'RecordDriver/SolrDefault/link-authority.phtml', [
               'url' => $url, 'label' => $label,
               'id' => $id, 'type' => $type, 'recordSource' => $recordSource
            ]
        );
    }

    /**
     * Returns HTML for a authority info container on record page.
     *
     * @return string
     */
    public function recordInfo()
    {
        return $this->getView()->render('Helpers/authority-info.phtml');
    }
}
