<?php

/**
 * DIGI backend.
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
 * @package  Search
 * @author   Samuli Sillanpää <samuli.sillanpaa@helsinki.fi>
 * @license  http://opensource.org/licenses/gpl-2.0.php GNU General Public License
 * @link     http://vufind.org
 */
namespace FinnaSearch\Backend\Digi;

use VuFindSearch\Query\AbstractQuery;

use VuFindSearch\ParamBag;

use VuFindSearch\Response\RecordCollectionInterface;
use VuFindSearch\Response\RecordCollectionFactoryInterface;

use VuFindSearch\Backend\AbstractBackend;
use VuFindSearch\Backend\Exception\BackendException;

/**
 * DIGI backend.
 *
 * @category VuFind
 * @package  Search
 * @author   Samuli Sillanpää <samuli.sillanpaa@helsinki.fi>
 * @license  http://opensource.org/licenses/gpl-2.0.php GNU General Public License
 * @link     http://vufind.org
 */
class Backend extends AbstractBackend
{
    /**
     * Connector.
     *
     * @var Connector
     */
    protected $connector;

    /**
     * Query builder.
     *
     * @var QueryBuilder
     */
    protected $queryBuilder = null;

    /**
     * Base URL for record links and thumbnails
     *
     * @var string
     */
    protected $baseUrl = null;

    /**
     * Constructor.
     *
     * @param Connector                        $connector Primo connector
     * @param RecordCollectionFactoryInterface $factory   Record collection factory
     * (null for default)
     * @param string                           $baseUrl   Base URL for record links
     * and thumbnails
     *
     * @return void
     */
    public function __construct(
        Connector $connector,
        RecordCollectionFactoryInterface $factory,
        $baseUrl
    ) {
        if (null !== $factory) {
            $this->setRecordCollectionFactory($factory);
        }
        $this->baseUrl = $baseUrl;
        $this->connector = $connector;
    }

    /**
     * Perform a search and return record collection.
     *
     * @param AbstractQuery $query  Search query
     * @param integer       $offset Search offset
     * @param integer       $limit  Search limit
     * @param ParamBag      $params Search backend parameters
     *
     * @return RecordCollectionInterface
     */
    public function search(AbstractQuery $query, $offset, $limit,
        ParamBag $params = null
    ) {
        $baseParams = $this->getQueryBuilder()->build($query);
        if (null !== $params) {
            $baseParams->mergeWith($params);
        }
        $baseParams->set('offset', $offset);
        $baseParams->set('query', $query->getString());
        $baseParams->set('sort', $params->get('sort'));

        try {
            $response = $this->connector->query($baseParams);
        } catch (\Exception $e) {
            throw new BackendException(
                $e->getMessage(),
                $e->getCode(),
                $e
            );
        }
        $collection = $this->createRecordCollection(
            $response, [
                'baseUrl' => $this->baseUrl,
                'baseId' => 'digi.pienpainate.',
                'handler' => $params->get('handler')[0]
            ]
        );
        return $collection;
    }

    /**
     * Retrieve a single document.
     *
     * @param string   $id     Document identifier
     * @param ParamBag $params Search backend parameters
     *
     * @return RecordCollectionInterface
     */
    public function retrieve($id, ParamBag $params = null)
    {
        return null;
    }

    /**
     * Return query builder.
     *
     * Lazy loads an empty QueryBuilder if none was set.
     *
     * @return QueryBuilder
     */
    public function getQueryBuilder()
    {
        if (!$this->queryBuilder) {
            $this->queryBuilder = new QueryBuilder();
        }
        return $this->queryBuilder;
    }

    /**
     * Return the record collection factory.
     *
     * Lazy loads a generic collection factory.
     *
     * @return RecordCollectionFactoryInterface
     */
    public function getRecordCollectionFactory()
    {
        if ($this->collectionFactory === null) {
            $this->collectionFactory = new Response\RecordCollectionFactory();
        }
        return $this->collectionFactory;
    }

    /**
     * Return the Primo connector.
     *
     * @return Connector
     */
    public function getConnector()
    {
        return $this->connector;
    }

    /**
     * Create record collection.
     *
     * @param string $json   JSON response
     * @param array  $params Parameters for record collection factory
     *
     * @return RecordCollectionInterface
     */
    protected function createRecordCollection($json, $params)
    {
        return $this->getRecordCollectionFactory()
            ->factory($this->deserialize($json), $params);
    }

    /**
     * Deserialize JSON response.
     *
     * @param string $json Serialized JSON response
     *
     * @return array
     *
     * @throws BackendException Deserialization error
     */
    protected function deserialize($json)
    {
        $response = json_decode($json, true);
        $error    = json_last_error();
        if ($error != \JSON_ERROR_NONE) {
            throw new BackendException(
                sprintf('JSON decoding error: %s -- %s', $error, $json)
            );
        }
        return $response;
    }
}
