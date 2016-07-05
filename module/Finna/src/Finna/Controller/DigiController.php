<?php
/**
 * DIGI Controller
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
 * @package  Controller
 * @author   Samuli Sillanpää <samuli.sillanpaa@helsinki.fi>
 * @license  http://opensource.org/licenses/gpl-2.0.php GNU General Public License
 * @link     https://vufind.org/wiki/development:plugins:controllers Wiki
 */
namespace Finna\Controller;

/**
 * DIGI Controller
 *
 * @category VuFind
 * @package  Controller
 * @author   Samuli Sillanpää <samuli.sillanpaa@helsinki.fi>
 * @license  http://opensource.org/licenses/gpl-2.0.php GNU General Public License
 * @link     https://vufind.org/wiki/development:plugins:controllers Wiki
 */
class DigiController extends \VuFind\Controller\AbstractSearch
{
    use SearchControllerTrait;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->searchClassId = 'Digi';
        parent::__construct();
    }

    /**
     * Home action
     *
     * @return mixed
     */
    public function homeAction()
    {
        if (!$this->isAvailable()) {
            throw new \Exception('Digi is not enabled');
        }
        $this->layout()->searchClassId = $this->searchClassId;
        return $this->createViewModel();
    }

    /**
     * Results action.
     *
     * @return mixed
     */
    public function resultsAction()
    {
        if (!$this->isAvailable()) {
            throw new \Exception('Digi is not enabled');
        }
        return parent::resultsAction();
    }

    /**
     * Check if DIGI is available.
     *
     * @return bool
     */
    protected function isAvailable()
    {
        $config = $this->getServiceLocator()->get('VuFind\Config')->get('digi');
        return isset($config->General->enabled) && $config->General->enabled;
    }
}
