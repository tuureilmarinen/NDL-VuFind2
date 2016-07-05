<?php
/**
 * Factory for DIGI record collection.
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
namespace FinnaSearch\Backend\Digi\Response;

use VuFindSearch\Response\RecordCollectionFactoryInterface;
use VuFindSearch\Exception\InvalidArgumentException;
use VuFindSearch\Backend\Solr\Response\Json\Record;

/**
 * Factory for DIGI record collection.
 *
 * @category VuFind
 * @package  Search
 * @author   Samuli Sillanpää <samuli.sillanpaa@helsinki.fi>
 * @license  http://opensource.org/licenses/gpl-2.0.php GNU General Public License
 * @link     http://vufind.org
 */
class RecordCollectionFactory implements RecordCollectionFactoryInterface
{
    /**
     * Factory to turn data into a record object.
     *
     * @var Callable
     */
    protected $recordFactory;

    /**
     * Class of collection.
     *
     * @var string
     */
    protected $collectionClass;

    /**
     * Constructor.
     *
     * @param Callable $recordFactory   Record factory callback (null for default)
     * @param string   $collectionClass Class of collection
     *
     * @return void
     */
    public function __construct($recordFactory = null, $collectionClass = null)
    {
        // Set default record factory if none provided:
        if (null === $recordFactory) {
            $recordFactory = function ($i) {
                return new Record($i);
            };
        } else if (!is_callable($recordFactory)) {
            throw new InvalidArgumentException('Record factory must be callable.');
        }
        $this->recordFactory = $recordFactory;
        $this->collectionClass = (null === $collectionClass)
            ? 'FinnaSearch\Backend\Digi\Response\RecordCollection'
            : $collectionClass;
    }

    /**
     * Return record collection.
     *
     * @param array $response Response
     * @param array $params   Parameters used in processing records (base URL etc)
     *
     * @return RecordCollection
     */
    public function factory($response, $params = null)
    {
        if (!is_array($response)) {
            throw new InvalidArgumentException(
                sprintf(
                    'Unexpected type of value: Expected array, got %s',
                    gettype($response)
                )
            );
        }
        $collection = new $this->collectionClass($response);

        extract($params);

        if (isset($response['rows'])) {
            $highlightTags = [
                 "<em>" => '{{{{START_HILITE}}}}',
                 '</em>' => '{{{{END_HILITE}}}}'
            ];
            $formats = [
                'newspaper' => ['0/Journal/', '1/Journal/Newspaper/'],
                'journal' => ['0/Journal/', '1/Journal/Journal/'],
                'printing' => ['0/Other/', '1/Other/Print/']
            ];
            foreach ($response['rows'] as $doc) {
                $doc['id'] = $baseId . $doc['bindingId'];

                if (isset($formats[$handler])) {
                    $doc['format'] = $formats[$handler];
                }

                if (isset($doc['url'])) {
                    $doc['url'] = $baseUrl . $doc['url'];
                }

                if (isset($doc['date']) && $date = date_parse($doc['date'])) {
                    $doc['publishDate'] = [$date['year']];
                }

                if (isset($doc['placeOfPublication'])) {
                    $doc['publication_place_txt_mv'] = $doc['placeOfPublication'];
                }

                // Highlighted snippet
                if (isset($doc['html'])) {
                    $html = $doc['html'];
                    $html
                        = str_replace("<div class='highlight-fragment'>", '', $html);
                    $html = str_replace('</div>', '', $html);

                    foreach ($highlightTags as $from => $to) {
                        $html = str_replace($from, $to, $html);
                    }

                    $doc['html'] = $html;
                }

                if (isset($doc['thumbnailUrl'])) {
                    $doc['thumbnail'] = $baseUrl . $doc['thumbnailUrl'];
                }

                $collection->add(call_user_func($this->recordFactory, $doc));
            }
        }

        return $collection;
    }
}
