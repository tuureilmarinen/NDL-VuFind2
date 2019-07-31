<?php
/**
 * List Controller
 *
 * PHP version 7
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
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301  USA
 *
 * @category VuFind
 * @package  Controller
 * @author   Mika Hatakka <mika.hatakka@helsinki.fi>
 * @author   Tuure Ilmarinen <tuure.ilmarinen@helsinki.fi>
 * @license  http://opensource.org/licenses/gpl-2.0.php GNU General Public License
 * @link     http://vufind.org   Main Site
 */
namespace Finna\Controller;

use VuFind\Exception\ListPermission as ListPermissionException;
use VuFind\Exception\RecordMissing as RecordMissingException;
use Zend\Stdlib\Parameters;

/**
 * Controller for the public favorite lists.
 *
 * @category VuFind
 * @package  Controller
 * @author   Mika Hatakka <mika.hatakka@helsinki.fi>
 * @author   Tuure Ilmarinen <tuure.ilmarinen@helsinki.fi>
 * @license  http://opensource.org/licenses/gpl-2.0.php GNU General Public License
 * @link     http://vufind.org   Main Site
 */
class ListController extends \Finna\Controller\MyResearchController
{
    /**
     * Send user's saved favorites from a particular list to the view
     *
     * @return mixed
     */
    public function listAction()
    {
        $lid = $this->params()->fromRoute('lid');
        if ($lid === null) {
            return $this->notFoundAction();
        }
        try {
            $list = $this->getTable('UserList')->getExisting($lid);
            if (!$list->isPublic()) {
                return $this->createNoAccessView();
            }
        } catch (RecordMissingException $e) {
            return $this->notFoundAction();
        }

        try {
            $results = $this->serviceLocator
                ->get(\VuFind\Search\Results\PluginManager::class)->get('Favorites');
            $params = $results->getParams();

            // We want to merge together GET, POST and route parameters to
            // initialize our search object:
            $params->initFromRequest(
                new Parameters(
                    $this->getRequest()->getQuery()->toArray()
                    + $this->getRequest()->getPost()->toArray()
                    + ['id' => $lid]
                )
            );

            $results->performAndProcessSearch();
            $listObj = $results->getListObject();

            // Special case: If we're in RSS view, we need to render differently:
            if (isset($params) && $params->getView() == 'rss') {
                $response = $this->getResponse();
                $response->getHeaders()->addHeaderLine('Content-type', 'text/xml');

                if (!$listObj = $results->getListObject()) {
                    return $this->notFoundAction();
                }

                $feed = $this->getViewRenderer()->plugin('resultfeed');
                $feed->setList($listObj);
                $feed = $feed($results);
                $feed->setTitle($listObj->title);
                if ($desc = $listObj->description) {
                    $feed->setDescription($desc);
                }
                $feed->setLink($this->getServerUrl('home') . "List/$lid");
                $response->setContent($feed->export('rss'));
                return $response;
            }

            $this->rememberCurrentSearchUrl();

            $view = $this->createViewModel(
                [
                    'params' => $params,
                    'results' => $results,
                    'sortList' => $this->createSortList($listObj)
                ]
            );
            return $view;
        } catch (ListPermissionException $e) {
            return $this->createNoAccessView();
        }
    }

    /**
     * Save action - Allows the save template to appear,
     *   passes containingLists & nonContainingLists
     *
     * @return mixed
     */
    public function saveAction()
    {
        // Fail if lists are disabled:
        if (!$this->listsEnabled()) {
            throw new ForbiddenException('Lists disabled');
        }

        // Check permission:
        $response = $this->permission()->check('feature.Favorites', false);
        if (is_object($response)) {
            return $response;
        }

        // Process form submission:
        if ($this->formWasSubmitted('submit')) {
            return $this->processSave();
        }

        // Retrieve user object and force login if necessary:
        if (!($user = $this->getUser())) {
            return $this->forceLogin();
        }
        $listId = $this->params()->fromRoute('id');
        $this->setFollowupUrlToReferer();
        $runner = $this->serviceLocator->get(\VuFind\Search\SearchRunner::class);

        $request = $this->getRequest()->getQuery()->toArray()
            + $this->getRequest()->getPost()->toArray()
            + ['id' => $listId];
        $records = $runner->run($request, 'Favorites', $runner)->getResults();

        $view = $this->createViewModel(
            [
                'listId' => $listId,
                'lists' => $user->getLists(),
                'records' => $records,
            ]
        );
        $view->setTemplate('list/save');
        return $view;
    }

    /**
     * ProcessSave -- store the results of the Save action.
     *
     * @return mixed
     */
    protected function processSave()
    {
        // Retrieve user object and force login if necessary:
        if (!($user = $this->getUser())) {
            return $this->forceLogin();
        }

        $runner = $this->serviceLocator->get(\VuFind\Search\SearchRunner::class);

        // We want to merge together GET, POST and route parameters to
        // initialize our search object:
        $request = $this->getRequest()->getQuery()->toArray()
            + $this->getRequest()->getPost()->toArray()
            + ['id' => $this->params()->fromRoute('id')];

        $drivers = $runner->run($request, 'Favorites', $runner)->getResults();

        // Perform the save operation:
        $post = $this->getRequest()->getPost()->toArray();
        $favorites = $this->serviceLocator
            ->get(\Finna\Favorites\FavoritesService::class);
        $results = $favorites->saveMany($post, $user, $drivers);

        // Display a success status message:
        $listUrl = $this->url()->fromRoute('userList', ['id' => $results['listId']]);
        $message = [
            'html' => true,
            'msg' => $this->translate('bulk_save_success') . '. '
            . '<a href="' . $listUrl . '" class="gotolist">'
            . $this->translate('go_to_list') . '</a>.'
        ];
        $this->flashMessenger()->addMessage($message, 'success');

        // redirect to followup url saved in saveAction
        if ($url = $this->getFollowupUrl()) {
            $this->clearFollowupUrl();
            return $this->redirect()->toUrl($url);
        }

        // No followup info found?  Send back to record view:
        return $this->redirectToList();
    }

    /**
     * Create simple error page for no access error.
     *
     * @return type
     */
    protected function createNoAccessView()
    {
        $config = $this->serviceLocator->get(\VuFind\Config\PluginManager::class)
            ->get('config');
        $view = $this->createViewModel();
        $view->setTemplate('list/no_access');
        $view->email = $config->Site->email;
        return $view;
    }
}
