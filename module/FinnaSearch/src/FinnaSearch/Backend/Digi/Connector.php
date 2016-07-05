<?php
/**
 * DIGI connector.
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

/**
 * DIGI connector
 *
 * @category VuFind
 * @package  Search
 * @author   Samuli Sillanpää <samuli.sillanpaa@helsinki.fi>
 * @license  http://opensource.org/licenses/gpl-2.0.php GNU General Public License
 * @link     http://vufind.org
 */
class Connector implements \Zend\Log\LoggerAwareInterface
{
    use \VuFind\Log\LoggerAwareTrait;

    /**
     * HTTP client
     *
     * @var HttpClient
     */
    protected $client;

    /**
     * Base URL
     *
     * @var string
     */
    protected $host;

    /**
     * Constructor
     *
     * Sets up the DIGI Client
     *
     * @param string     $url    Base URL
     * @param HttpClient $client HTTP client
     */
    public function __construct($url, $client)
    {
        $this->host = $url;
        $this->client = $client;
    }

    /**
     * Execute a search.
     *
     * @param array $params Parameters
     *
     * @throws \Exception
     * @return array              An array of query results
     */
    public function query($params = null)
    {
        $types = [
            'printing'
               => ['handler' => 'printings'],
            'journal'
               => ['handler' => 'serial-publications', 'type' => 'JOURNAL'],
            'newspaper'
               => ['handler' => 'serial-publications', 'type' => 'NEWSPAPER']
        ];

        if ($type = $params->get('handler')) {
            $type = $type[0];
        }

        $url = $this->host;
        $url .= $types[$type]['handler']
            . '?offset=' . ($params->get('offset') ? $params->get('offset')[0] : 0);

        if (isset($types[$type]['type'])) {
            $url .= '&type=' . $types[$type]['type'];
        }

        $params = [
            'query' => $params->get('query')[0],
            'requireAllKeywords' => true,
            'fuzzy' => true,
            'hasIllustrations' => false,
            'startDate' => null,
            'endDate' => null,
            'pages' => '',
            'publishers' => [],
            'languages' => [],
            'orderBy' => $params->get('sort')[0]
        ];

        if ($type == 'printing') {
            $params = array_merge($params, ['districts' => []]);
        } else {
            $params = array_merge(
                $params,
                ['publications' => [], 'publicationPlaces' => []]
            );
        }

        $params = json_encode($params);

        $this->client->setUri($url);
        $this->client->resetParameters();
        $this->client->getRequest()->getHeaders()
            ->addheaderLine('Content-Type', 'application/json;charset=UTF-8');
        $this->client->setRawBody($params);

        $response = $this->client->setMethod('POST')->send();

        $req = $this->client->getLastRawRequest();

        if (!$response->isSuccess()) {
            throw new \Exception($response->getBody());
        }

        return $response->getBody();
    }
}
