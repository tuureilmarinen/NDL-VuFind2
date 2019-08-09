<?php
/**
 * Finna Module Configuration
 *
 * PHP version 7
 *
 * Copyright (C) The National Library of Finland 2014-2018.
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
 * @package  Finna
 * @author   Ere Maijala <ere.maijala@helsinki.fi>
 * @license  http://opensource.org/licenses/gpl-2.0.php GNU General Public License
 * @link     http://github.com/KDK-Alli/NDL-VuFind2   NDL-VuFind2
 */
namespace Finna\Module\Configuration;

$config = [
    'router' => [
        'routes' => [
            'comments-inappropriate' => [
                'type'    => 'Zend\Router\Http\Segment',
                'options' => [
                    'route'    => '/Comments/Inappropriate/[:id]',
                    'constraints' => [
                        'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'action'     => '[a-zA-Z][a-zA-Z0-9_-]*',
                    ],
                    'defaults' => [
                        'controller' => 'Comments',
                        'action'     => 'Inappropriate',
                    ]
                ]
            ],
            'feed-content-page' => [
                'type'    => 'Zend\Router\Http\Segment',
                'options' => [
                    'route'    => '/FeedContent[/:page][/:element]',
                    'constraints' => [
                        'page'     => '[a-zA-Z][a-zA-Z0-9_-]*'
                    ],
                    'defaults' => [
                        'controller' => 'FeedContent',
                        'action'     => 'Content',
                    ]
                ],
            ],
            'list-page' => [
                'type'    => 'Zend\Router\Http\Segment',
                'options' => [
                    'route'    => '/List[/:lid]',
                    'constraints' => [
                        'lid'     => '[0-9]+',
                    ],
                    'defaults' => [
                        'controller' => 'ListPage',
                        'action'     => 'List',
                    ]
                ],
            ],
            'myresearch-changemessagingsettings' => [
                'type' => 'Zend\Router\Http\Literal',
                'options' => [
                    'route'    => '/MyResearch/ChangeMessagingSettings',
                    'defaults' => [
                        'controller' => 'MyResearch',
                        'action'     => 'ChangeMessagingSettings',
                    ]
                ],
            ],
            'myresearch-changeprofileaddress' => [
                'type' => 'Zend\Router\Http\Literal',
                'options' => [
                    'route'    => '/MyResearch/ChangeProfileAddress',
                    'defaults' => [
                        'controller' => 'MyResearch',
                        'action'     => 'ChangeProfileAddress',
                    ]
                ],
            ],
            'myresearch-unsubscribe' => [
                'type' => 'Zend\Router\Http\Literal',
                'options' => [
                    'route'    => '/MyResearch/Unsubscribe',
                    'defaults' => [
                        'controller' => 'MyResearch',
                        'action'     => 'Unsubscribe',
                    ]
                ],
            ],
            'myresearch-export' => [
                'type' => 'Zend\Router\Http\Literal',
                'options' => [
                    'route'    => '/MyResearch/Export',
                    'defaults' => [
                        'controller' => 'MyResearch',
                        'action'     => 'Export',
                    ]
                ],
            ],
            'myresearch-import' => [
                'type' => 'Zend\Router\Http\Literal',
                'options' => [
                    'route'    => '/MyResearch/Import',
                    'defaults' => [
                        'controller' => 'MyResearch',
                        'action'     => 'Import',
                    ]
                ],
            ],
            'myresearch-import-public-list' => [
                'type' => 'Zend\Router\Http\Literal',
                'options' => [
                    'route'    => '/MyResearch/ImportPublicList',
                    'defaults' => [
                        'controller' => 'MyResearch',
                        'action'     => 'Importpubliclist',
                    ]
                ],
            ],
            'record-feedback' => [
                'type'    => 'Zend\Router\Http\Segment',
                'options' => [
                    'route'    => '/Record/[:id]/Feedback',
                    'constraints' => [
                        'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'action'     => '[a-zA-Z][a-zA-Z0-9_-]*',
                    ],
                    'defaults' => [
                        'controller' => 'Record',
                        'action'     => 'Feedback',
                    ]
                ]
            ]
        ],
    ],
    'route_manager' => [
        'aliases' => [
            'Zend\Mvc\Router\Http\Segment' => 'Zend\Router\Http\Segment'
        ]
    ],
    'controllers' => [
        'factories' => [
            'Finna\Controller\AjaxController' => 'VuFind\Controller\AjaxControllerFactory',
            'Finna\Controller\BarcodeController' => 'VuFind\Controller\AbstractBaseFactory',
            'Finna\Controller\BrowseController' => 'VuFind\Controller\AbstractBaseWithConfigFactory',
            'Finna\Controller\CartController' => 'VuFind\Controller\CartControllerFactory',
            'Finna\Controller\CollectionController' => 'VuFind\Controller\AbstractBaseWithConfigFactory',
            'Finna\Controller\CombinedController' => 'VuFind\Controller\AbstractBaseFactory',
            'Finna\Controller\CommentsController' => 'VuFind\Controller\AbstractBaseFactory',
            'Finna\Controller\ContentController' => 'VuFind\Controller\AbstractBaseFactory',
            'Finna\Controller\CoverController' => 'Finna\Controller\CoverControllerFactory',
            'Finna\Controller\EdsController' => 'VuFind\Controller\AbstractBaseFactory',
            'Finna\Controller\ErrorController' => 'VuFind\Controller\AbstractBaseFactory',
            'Finna\Controller\ExternalAuthController' => 'VuFind\Controller\AbstractBaseFactory',
            'Finna\Controller\FeedbackController' => 'VuFind\Controller\AbstractBaseFactory',
            'Finna\Controller\FeedContentController' => 'VuFind\Controller\AbstractBaseFactory',
            'Finna\Controller\LibraryCardsController' => 'VuFind\Controller\AbstractBaseFactory',
            'Finna\Controller\LocationServiceController' => 'VuFind\Controller\AbstractBaseFactory',
            'Finna\Controller\MetaLibController' => 'VuFind\Controller\AbstractBaseFactory',
            'Finna\Controller\MetalibRecordController' => 'VuFind\Controller\AbstractBaseFactory',
            'Finna\Controller\MyResearchController' => 'VuFind\Controller\AbstractBaseFactory',
            'Finna\Controller\OrganisationInfoController' => 'VuFind\Controller\AbstractBaseFactory',
            'Finna\Controller\PCIController' => 'VuFind\Controller\AbstractBaseFactory',
            'Finna\Controller\PrimoController' => 'VuFind\Controller\AbstractBaseFactory',
            'Finna\Controller\PrimoRecordController' => 'VuFind\Controller\AbstractBaseFactory',
            'Finna\Controller\RecordController' => 'VuFind\Controller\AbstractBaseWithConfigFactory',
            'Finna\Controller\CollectionController' => 'VuFind\Controller\AbstractBaseWithConfigFactory',
            'Finna\Controller\SearchController' => 'VuFind\Controller\AbstractBaseFactory',
            'Finna\Controller\ListController' => 'Finna\Controller\ListControllerFactory',
        ],
        'aliases' => [
            'Barcode' => 'Finna\Controller\BarcodeController',
            'barcode' => 'Finna\Controller\BarcodeController',
            'Comments' => 'Finna\Controller\CommentsController',
            'comments' => 'Finna\Controller\CommentsController',
            'FeedContent' => 'Finna\Controller\FeedContentController',
            'feedcontent' => 'Finna\Controller\FeedContentController',
            'LocationService' => 'Finna\Controller\LocationServiceController',
            'locationservice' => 'Finna\Controller\LocationServiceController',
            'MetaLib' => 'Finna\Controller\MetaLibController',
            'metalib' => 'Finna\Controller\MetaLibController',
            'MetaLibRecord' => 'Finna\Controller\MetaLibrecordController',
            'metalibrecord' => 'Finna\Controller\MetaLibrecordController',
            'OrganisationInfo' => 'Finna\Controller\OrganisationInfoController',
            'organisationinfo' => 'Finna\Controller\OrganisationInfoController',
            'ListPage' => 'Finna\Controller\ListController',
            'listpage' => 'Finna\Controller\ListController',

            // Overrides:
            'VuFind\Controller\AjaxController' => 'Finna\Controller\AjaxController',
            'VuFind\Controller\BrowseController' => 'Finna\Controller\BrowseController',
            'VuFind\Controller\CartController' => 'Finna\Controller\CartController',
            'VuFind\Controller\CombinedController' => 'Finna\Controller\CombinedController',
            'VuFind\Controller\CollectionController' => 'Finna\Controller\CollectionController',
            'VuFind\Controller\ContentController' => 'Finna\Controller\ContentController',
            'VuFind\Controller\CoverController' => 'Finna\Controller\CoverController',
            'VuFind\Controller\EdsController' => 'Finna\Controller\EdsController',
            'VuFind\Controller\ErrorController' => 'Finna\Controller\ErrorController',
            'VuFind\Controller\ExternalAuthController' => 'Finna\Controller\ExternalAuthController',
            'VuFind\Controller\FeedbackController' => 'Finna\Controller\FeedbackController',
            'VuFind\Controller\LibraryCardsController' => 'Finna\Controller\LibraryCardsController',
            'VuFind\Controller\MyResearchController' => 'Finna\Controller\MyResearchController',
            'VuFind\Controller\PrimoController' => 'Finna\Controller\PrimoController',
            'VuFind\Controller\PrimoRecordController' => 'Finna\Controller\PrimoRecordController',
            'VuFind\Controller\RecordController' => 'Finna\Controller\RecordController',
            'VuFind\Controller\SearchController' => 'Finna\Controller\SearchController',

            // Legacy:
            'PCI' => 'Finna\Controller\PrimoController',
            'pci' => 'Finna\Controller\PrimoController',
        ]
    ],
    'controller_plugins' => [
        'factories' => [
            'Finna\Controller\Plugin\Recaptcha' => 'Finna\Controller\Plugin\RecaptchaFactory',
        ],
        'aliases' => [
            'VuFind\Controller\Plugin\Recaptcha' => 'Finna\Controller\Plugin\Recaptcha'
        ],
    ],
    'service_manager' => [
        'allow_override' => true,
        'factories' => [
            'Finna\Auth\ILSAuthenticator' => 'VuFind\Auth\ILSAuthenticatorFactory',
            'Finna\Auth\Manager' => 'VuFind\Auth\ManagerFactory',
            'Finna\Cache\Manager' => 'VuFind\Cache\ManagerFactory',
            'Finna\Config\PluginManager' => 'VuFind\Config\PluginManagerFactory',
            'Finna\Config\SearchSpecsReader' => 'VuFind\Config\YamlReaderFactory',
            'Finna\Config\YamlReader' => 'VuFind\Config\YamlReaderFactory',
            'Finna\Cover\Loader' => 'VuFind\Cover\LoaderFactory',
            'Finna\Feed\Feed' => 'Finna\Feed\FeedFactory',
            'Finna\Form\Form' => 'Finna\Form\FormFactory',
            'Finna\ILS\Connection' => 'VuFind\ILS\ConnectionFactory',
            'Finna\LocationService\LocationService' => 'Finna\LocationService\LocationServiceFactory',
            'Finna\Mailer\Mailer' => 'VuFind\Mailer\Factory',
            'Finna\OAI\Server' => 'VuFind\OAI\ServerFactory',
            'Finna\OnlinePayment\OnlinePayment' => 'Finna\OnlinePayment\OnlinePaymentFactory',
            'Finna\OnlinePayment\Session' => 'Finna\OnlinePayment\OnlinePaymentSessionFactory',
            'Finna\OrganisationInfo\OrganisationInfo' => 'Finna\OrganisationInfo\OrganisationInfoFactory',
            'Finna\Record\Loader' => 'Finna\Record\LoaderFactory',
            'Finna\RecordTab\PluginManager' => 'VuFind\ServiceManager\AbstractPluginManagerFactory',
            'Finna\Role\PermissionManager' => 'VuFind\Role\PermissionManagerFactory',
            'Finna\Search\Memory' => 'VuFind\Search\MemoryFactory',
            'Finna\Search\Solr\HierarchicalFacetHelper' => 'Zend\ServiceManager\Factory\InvokableFactory',

            'FinnaSearch\Service' => 'VuFind\Service\SearchServiceFactory',

            'VuFind\Search\SearchTabsHelper' => 'Finna\Search\SearchTabsHelperFactory',

            'Zend\Session\SessionManager' => 'Finna\Session\ManagerFactory',
        ],
        'aliases' => [
            'VuFind\Auth\Manager' => 'Finna\Auth\Manager',
            'VuFind\Auth\ILSAuthenticator' => 'Finna\Auth\ILSAuthenticator',
            'VuFind\Cache\Manager' => 'Finna\Cache\Manager',
            'VuFind\Config\PluginManager' => 'Finna\Config\PluginManager',
            'VuFind\Config\SearchSpecsReader' => 'Finna\Config\SearchSpecsReader',
            'VuFind\Config\YamlReader' => 'Finna\Config\YamlReader',
            'VuFind\Cover\Loader' => 'Finna\Cover\Loader',
            'VuFind\Form\Form' => 'Finna\Form\Form',
            'VuFind\ILS\Connection' => 'Finna\ILS\Connection',
            'VuFind\Mailer\Mailer' => 'Finna\Mailer\Mailer',
            'VuFind\OAI\Server' => 'Finna\OAI\Server',
            'VuFind\Record\Loader' => 'Finna\Record\Loader',
            'VuFind\RecordTab\PluginManager' => 'Finna\RecordTab\PluginManager',
            'VuFind\Role\PermissionManager' => 'Finna\Role\PermissionManager',
            'VuFind\Search\Memory' => 'Finna\Search\Memory',
            'VuFind\Search\Solr\HierarchicalFacetHelper' => 'Finna\Search\Solr\HierarchicalFacetHelper',

            'VuFindSearch\Service' => 'FinnaSearch\Service',
        ]
    ],
    // This section contains all VuFind-specific settings (i.e. configurations
    // unrelated to specific Zend Framework 2 components).
    'vufind' => [
        'plugin_managers' => [
            'ajaxhandler' => [
                'factories' => [
                    'Finna\AjaxHandler\AddToList' =>
                        'Finna\AjaxHandler\AddToListFactory',
                    'Finna\AjaxHandler\ChangePickupLocation' =>
                        'VuFind\AjaxHandler\AbstractIlsAndUserActionFactory',
                    'Finna\AjaxHandler\ChangeRequestStatus' =>
                        'VuFind\AjaxHandler\AbstractIlsAndUserActionFactory',
                    'Finna\AjaxHandler\CheckRequestsAreValid' =>
                        'VuFind\AjaxHandler\AbstractIlsAndUserActionFactory',
                    'Finna\AjaxHandler\CommentRecord' =>
                        'Finna\AjaxHandler\CommentRecordFactory',
                    'Finna\AjaxHandler\DeleteRecordComment' =>
                        'VuFind\AjaxHandler\DeleteRecordCommentFactory',
                    'Finna\AjaxHandler\EditList' =>
                        'Finna\AjaxHandler\EditListFactory',
                    'Finna\AjaxHandler\EditListResource' =>
                        'Finna\AjaxHandler\EditListResourceFactory',
                    'Finna\AjaxHandler\GetAuthorityInfo' =>
                        'Finna\AjaxHandler\GetAuthorityInfoFactory',
                    'Finna\AjaxHandler\GetACSuggestions' =>
                        'VuFind\AjaxHandler\GetACSuggestionsFactory',
                    'Finna\AjaxHandler\GetContentFeed' =>
                        'Finna\AjaxHandler\GetContentFeedFactory',
                    'Finna\AjaxHandler\GetDateRangeVisual' =>
                        'Finna\AjaxHandler\GetDateRangeVisualFactory',
                    'Finna\AjaxHandler\GetDescription' =>
                        'Finna\AjaxHandler\GetDescriptionFactory',
                    'Finna\AjaxHandler\GetFacetData' =>
                        'Finna\AjaxHandler\GetFacetDataFactory',
                    'Finna\AjaxHandler\GetFeed' =>
                        'Finna\AjaxHandler\GetFeedFactory',
                    'Finna\AjaxHandler\GetImageInformation' =>
                        'Finna\AjaxHandler\GetImageInformationFactory',
                    'Finna\AjaxHandler\GetOrganisationInfo' =>
                        'Finna\AjaxHandler\GetOrganisationInfoFactory',
                    'Finna\AjaxHandler\GetOrganisationPageFeed' =>
                        'Finna\AjaxHandler\GetOrganisationPageFeedFactory',
                    'Finna\AjaxHandler\GetPiwikPopularSearches' =>
                        'Finna\AjaxHandler\GetPiwikPopularSearchesFactory',
                    'Finna\AjaxHandler\GetSearchTabsRecommendations' =>
                        'Finna\AjaxHandler\GetSearchTabsRecommendationsFactory',
                    'Finna\AjaxHandler\GetSideFacets' =>
                        'VuFind\AjaxHandler\GetSideFacetsFactory',
                    'Finna\AjaxHandler\GetSimilarRecords' =>
                        'Finna\AjaxHandler\GetSimilarRecordsFactory',
                    'Finna\AjaxHandler\GetUserLists' =>
                        'Finna\AjaxHandler\GetUserListsFactory',
                    'Finna\AjaxHandler\ImportFavorites' =>
                        'Finna\AjaxHandler\ImportFavoritesFactory',
                    'Finna\AjaxHandler\OnlinePaymentNotify' =>
                        'Finna\AjaxHandler\AbstractOnlinePaymentActionFactory',
                    'Finna\AjaxHandler\RegisterOnlinePayment' =>
                        'Finna\AjaxHandler\AbstractOnlinePaymentActionFactory',
                    'Finna\AjaxHandler\SystemStatus' =>
                        'VuFind\AjaxHandler\SystemStatusFactory'
                ],
                'aliases' => [
                    'addToList' => 'Finna\AjaxHandler\AddToList',
                    'changePickupLocation' => 'Finna\AjaxHandler\ChangePickupLocation',
                    'changeRequestStatus' => 'Finna\AjaxHandler\ChangeRequestStatus',
                    'checkRequestsAreValid' => 'Finna\AjaxHandler\CheckRequestsAreValid',
                    'editList' => 'Finna\AjaxHandler\EditList',
                    'editListResource' => 'Finna\AjaxHandler\EditListResource',
                    'getAuthorityInfo' => 'Finna\AjaxHandler\GetAuthorityInfo',
                    'getContentFeed' => 'Finna\AjaxHandler\GetContentFeed',
                    'getDescription' => 'Finna\AjaxHandler\GetDescription',
                    'getDateRangeVisual' => 'Finna\AjaxHandler\GetDateRangeVisual',
                    'getFeed' => 'Finna\AjaxHandler\GetFeed',
                    'getImageInformation' => 'Finna\AjaxHandler\GetImageInformation',
                    'getOrganisationPageFeed' => 'Finna\AjaxHandler\GetOrganisationPageFeed',
                    'getMyLists' => 'Finna\AjaxHandler\GetUserLists',
                    'getOrganisationInfo' => 'Finna\AjaxHandler\GetOrganisationInfo',
                    'getPiwikPopularSearches' => 'Finna\AjaxHandler\GetPiwikPopularSearches',
                    'getSearchTabsRecommendations' => 'Finna\AjaxHandler\GetSearchTabsRecommendations',
                    'getSimilarRecords' => 'Finna\AjaxHandler\GetSimilarRecords',
                    'importFavorites' => 'Finna\AjaxHandler\ImportFavorites',
                    'onlinePaymentNotify' => 'Finna\AjaxHandler\OnlinePaymentNotify',
                    'registerOnlinePayment' => 'Finna\AjaxHandler\RegisterOnlinePayment',

                    // Overrides:
                    'VuFind\AjaxHandler\CommentRecord' => 'Finna\AjaxHandler\CommentRecord',
                    'VuFind\AjaxHandler\DeleteRecordComment' => 'Finna\AjaxHandler\DeleteRecordComment',
                    'VuFind\AjaxHandler\GetACSuggestions' => 'Finna\AjaxHandler\GetACSuggestions',
                    'VuFind\AjaxHandler\GetFacetData' => 'Finna\AjaxHandler\GetFacetData',
                    'VuFind\AjaxHandler\GetSideFacets' => 'Finna\AjaxHandler\GetSideFacets',
                    'VuFind\AjaxHandler\SystemStatus' => 'Finna\AjaxHandler\SystemStatus',
                ]
            ],
            'auth' => [
                'factories' => [
                    'Finna\Auth\ILS' => 'VuFind\Auth\ILSFactory',
                    'Finna\Auth\MultiILS' => 'VuFind\Auth\ILSFactory',
                    'Finna\Auth\Shibboleth' => 'VuFind\Auth\ShibbolethFactory',
                    'Finna\Auth\Suomifi' => 'VuFind\Auth\ShibbolethFactory',
                ],
                'aliases' => [
                    'VuFind\Auth\ILS' => 'Finna\Auth\ILS',
                    'VuFind\Auth\MultiILS' => 'Finna\Auth\MultiILS',
                    'VuFind\Auth\Shibboleth' => 'Finna\Auth\Shibboleth',
                    'Suomifi' => 'Finna\Auth\Suomifi'
                ]
            ],
            'autocomplete' => [
                'factories' => [
                    'Finna\Autocomplete\Solr' => 'Finna\Autocomplete\SolrFactory',
                ],
                'aliases' => [
                    'VuFind\Autocomplete\Solr' => 'Finna\Autocomplete\Solr',
                ]
            ],
            'db_row' => [
                'factories' => [
                    'Finna\Db\Row\CommentsInappropriate' => 'VuFind\Db\Row\RowGatewayFactory',
                    'Finna\Db\Row\CommentsRecord' => 'VuFind\Db\Row\RowGatewayFactory',
                    'Finna\Db\Row\DueDateReminder' => 'VuFind\Db\Row\RowGatewayFactory',
                    'Finna\Db\Row\Fee' => 'VuFind\Db\Row\RowGatewayFactory',
                    'Finna\Db\Row\Feedback' => 'VuFind\Db\Row\RowGatewayFactory',
                    'Finna\Db\Row\FinnaCache' => 'VuFind\Db\Row\RowGatewayFactory',
                    'Finna\Db\Row\PrivateUser' => 'VuFind\Db\Row\UserFactory',
                    'Finna\Db\Row\Resource' => 'VuFind\Db\Row\RowGatewayFactory',
                    'Finna\Db\Row\Search' => 'VuFind\Db\Row\RowGatewayFactory',
                    'Finna\Db\Row\Transaction' => 'VuFind\Db\Row\RowGatewayFactory',
                    'Finna\Db\Row\User' => 'VuFind\Db\Row\UserFactory',
                    'Finna\Db\Row\UserCard' => 'Finna\Db\Row\UserCardFactory',
                    'Finna\Db\Row\UserList' => 'VuFind\Db\Row\RowGatewayFactory',
                ],
                'aliases' => [
                    'VuFind\Db\Row\PrivateUser' => 'Finna\Db\Row\PrivateUser',
                    'VuFind\Db\Row\Resource' => 'Finna\Db\Row\Resource',
                    'VuFind\Db\Row\Search' => 'Finna\Db\Row\Search',
                    'VuFind\Db\Row\Transaction' => 'Finna\Db\Row\Transaction',
                    'VuFind\Db\Row\User' => 'Finna\Db\Row\User',
                    'VuFind\Db\Row\UserCard' => 'Finna\Db\Row\UserCard',
                    'VuFind\Db\Row\UserList' => 'Finna\Db\Row\UserList',

                    // Aliases for table classes without a row class counterpart
                    'Finna\Db\Row\Comments' => 'VuFind\Db\Row\Comments',
                    'Finna\Db\Row\Session' => 'VuFind\Db\Row\Session',
                    'Finna\Db\Row\UserResource' => 'VuFind\Db\Row\UserResource',

                    'commentsinappropriate' => 'Finna\Db\Row\CommentsInappropriate',
                    'commentsrecord' => 'Finna\Db\Row\CommentsRecord',
                    'duedatereminder' => 'Finna\Db\Row\DueDateReminder',
                    'fee' => 'Finna\Db\Row\Fee',
                    'finnacache' => 'Finna\Db\Row\FinnaCache',
                    'transaction' => 'Finna\Db\Row\Transaction',
                ]
            ],
            'db_table' => [
                'factories' => [
                    'Finna\Db\Table\Comments' => 'VuFind\Db\Table\GatewayFactory',
                    'Finna\Db\Table\CommentsInappropriate' => 'VuFind\Db\Table\GatewayFactory',
                    'Finna\Db\Table\CommentsRecord' => 'VuFind\Db\Table\GatewayFactory',
                    'Finna\Db\Table\DueDateReminder' => 'VuFind\Db\Table\GatewayFactory',
                    'Finna\Db\Table\Fee' => 'VuFind\Db\Table\GatewayFactory',
                    'Finna\Db\Table\Feedback' => 'VuFind\Db\Table\GatewayFactory',
                    'Finna\Db\Table\FinnaCache' => 'VuFind\Db\Table\GatewayFactory',
                    'Finna\Db\Table\Resource' => 'VuFind\Db\Table\ResourceFactory',
                    'Finna\Db\Table\Search' => 'VuFind\Db\Table\GatewayFactory',
                    'Finna\Db\Table\Session' => 'VuFind\Db\Table\GatewayFactory',
                    'Finna\Db\Table\Transaction' => 'VuFind\Db\Table\GatewayFactory',
                    'Finna\Db\Table\User' => 'VuFind\Db\Table\UserFactory',
                    'Finna\Db\Table\UserList' => 'VuFind\Db\Table\GatewayFactory',
                    'Finna\Db\Table\UserResource' => 'VuFind\Db\Table\GatewayFactory',
                ],
                'aliases' => [
                    'VuFind\Db\Table\Comments' => 'Finna\Db\Table\Comments',
                    'VuFind\Db\Table\Resource' => 'Finna\Db\Table\Resource',
                    'VuFind\Db\Table\Search' => 'Finna\Db\Table\Search',
                    'VuFind\Db\Table\Session' => 'Finna\Db\Table\Session',
                    'VuFind\Db\Table\User' => 'Finna\Db\Table\User',
                    'VuFind\Db\Table\UserList' => 'Finna\Db\Table\UserList',
                    'VuFind\Db\Table\UserResource' => 'Finna\Db\Table\UserResource',

                    'commentsinappropriate' => 'Finna\Db\Table\CommentsInappropriate',
                    'commentsrecord' => 'Finna\Db\Table\CommentsRecord',
                    'duedatereminder' => 'Finna\Db\Table\DueDateReminder',
                    'fee' => 'Finna\Db\Table\Fee',
                    'feedback' => 'Finna\Db\Table\Feedback',
                    'finnacache' => 'Finna\Db\Table\FinnaCache',
                    'transaction' => 'Finna\Db\Table\Transaction',
                ]
            ],
            'ils_driver' => [
                'factories' => [
                    'Finna\ILS\Driver\AxiellWebServices' => 'Finna\ILS\Driver\AxiellWebServicesFactory',
                    'Finna\ILS\Driver\Demo' => 'VuFind\ILS\Driver\DemoFactory',
                    'Finna\ILS\Driver\Gemini' => '\VuFind\ILS\Driver\DriverWithDateConverterFactory',
                    'Finna\ILS\Driver\KohaRest' => 'Finna\ILS\Driver\KohaRestFactory',
                    'Finna\ILS\Driver\Mikromarc' => '\VuFind\ILS\Driver\DriverWithDateConverterFactory',
                    'Finna\ILS\Driver\MultiBackend' => 'Finna\ILS\Driver\MultiBackendFactory',
                    'Finna\ILS\Driver\SierraRest' => 'VuFind\ILS\Driver\SierraRestFactory',
                    'Finna\ILS\Driver\Voyager' => '\VuFind\ILS\Driver\DriverWithDateConverterFactory',
                    'Finna\ILS\Driver\VoyagerRestful' => '\Finna\ILS\Driver\VoyagerRestfulFactory',
                ],
                'aliases' => [
                    'axiellwebservices' => 'Finna\ILS\Driver\AxiellWebServices',
                    'gemini' => 'Finna\ILS\Driver\Gemini',
                    'mikromarc' => 'Finna\ILS\Driver\Mikromarc',
                    // TOOD: remove the following line when KohaRest driver is available upstream:
                    'koharest' => 'Finna\ILS\Driver\KohaRest',

                    'VuFind\ILS\Driver\Demo' => 'Finna\ILS\Driver\Demo',
                    'VuFind\ILS\Driver\KohaRest' => 'Finna\ILS\Driver\KohaRest',
                    'VuFind\ILS\Driver\MultiBackend' => 'Finna\ILS\Driver\MultiBackend',
                    'VuFind\ILS\Driver\SierraRest' => 'Finna\ILS\Driver\SierraRest',
                    'VuFind\ILS\Driver\Voyager' => 'Finna\ILS\Driver\Voyager',
                    'VuFind\ILS\Driver\VoyagerRestful' => 'Finna\ILS\Driver\VoyagerRestful',
                ]
            ],
            'recommend' => [
                'factories' => [
                    'VuFind\Recommend\CollectionSideFacets' => 'Finna\Recommend\Factory::getCollectionSideFacets',
                    'VuFind\Recommend\SideFacets' => 'Finna\Recommend\Factory::getSideFacets',
                    'Finna\Recommend\SideFacetsDeferred' => 'Finna\Recommend\Factory::getSideFacetsDeferred',
                ],
                'aliases' => [
                    'sidefacetsdeferred' => 'Finna\Recommend\SideFacetsDeferred',
                ]
            ],
            'resolver_driver' => [
                'factories' => [
                    'Finna\Resolver\Driver\Sfx' => 'VuFind\Resolver\Driver\DriverWithHttpClientFactory',
                ],
                'aliases' => [
                    'VuFind\Resolver\Driver\Sfx' => 'Finna\Resolver\Driver\Sfx',
                ]
            ],
            'search_backend' => [
                'factories' => [
                    'Primo' => 'Finna\Search\Factory\PrimoBackendFactory',
                    'Solr' => 'Finna\Search\Factory\SolrDefaultBackendFactory',
                ],
            ],
            'search_options' => [
                'factories' => [
                    'Finna\Search\Combined\Options' => 'VuFind\Search\OptionsFactory',
                    'Finna\Search\EDS\Options' => 'VuFind\Search\EDS\OptionsFactory',
                    'Finna\Search\Primo\Options' => 'VuFind\Search\OptionsFactory',
                ],
                'aliases' => [
                    'VuFind\Search\Combined\Options' => 'Finna\Search\Combined\Options',
                    'VuFind\Search\EDS\Options' => 'Finna\Search\EDS\Options',
                    'VuFind\Search\Primo\Options' => 'Finna\Search\Primo\Options',

                    // Counterpart for EmptySet Params:
                    'Finna\Search\EmptySet\Options' => 'VuFind\Search\EmptySet\Options',
                ]
            ],
            'search_params' => [
                'factories' => [
                    'Finna\Search\Combined\Params' => 'Finna\Search\Solr\ParamsFactory',
                    'Finna\Search\EDS\Params' => 'VuFind\Search\Params\ParamsFactory',
                    'Finna\Search\EmptySet\Params' => 'VuFind\Search\Params\ParamsFactory',
                    'Finna\Search\Favorites\Params' => 'VuFind\Search\Params\ParamsFactory',
                    'Finna\Search\MixedList\Params' => 'VuFind\Search\Params\ParamsFactory',
                    'Finna\Search\Solr\Params' => 'Finna\Search\Solr\ParamsFactory',
                ],
                'aliases' => [
                    'VuFind\Search\Combined\Params' => 'Finna\Search\Combined\Params',
                    'VuFind\Search\EDS\Params' => 'Finna\Search\EDS\Params',
                    'VuFind\Search\EmptySet\Params' => 'Finna\Search\EmptySet\Params',
                    'VuFind\Search\Favorites\Params' => 'Finna\Search\Favorites\Params',
                    'VuFind\Search\MixedList\Params' => 'Finna\Search\MixedList\Params',
                    'VuFind\Search\Solr\Params' => 'Finna\Search\Solr\Params',
                ]
            ],
            'search_results' => [
                'factories' => [
                    'Finna\Search\Combined\Results' => 'VuFind\Search\Results\ResultsFactory',
                    'Finna\Search\Favorites\Results' => 'Finna\Search\Favorites\ResultsFactory',
                    'Finna\Search\Primo\Results' => 'VuFind\Search\Results\ResultsFactory',
                    'Finna\Search\Solr\Results' => 'VuFind\Search\Solr\ResultsFactory',
                ],
                'aliases' => [
                    'VuFind\Search\Combined\Results' => 'Finna\Search\Combined\Results',
                    'VuFind\Search\Favorites\Results' => 'Finna\Search\Favorites\Results',
                    'VuFind\Search\Primo\Results' => 'Finna\Search\Primo\Results',
                    'VuFind\Search\Solr\Results' => 'Finna\Search\Solr\Results',
                ]
            ],
            'content_covers' => [
                'factories' => [
                    'Finna\Content\Covers\BTJ' => 'Finna\Content\Covers\BTJFactory',
                    'Finna\Content\Covers\CoverArtArchive' => 'Finna\Content\Covers\CoverArtArchiveFactory',
                ],
                'invokables' => [
                    'bookyfi' => 'Finna\Content\Covers\BookyFi',
                    'natlibfi' => 'Finna\Content\Covers\NatLibFi',
                ],
                'aliases' => [
                    'btj' => 'Finna\Content\Covers\BTJ',
                    'coverartarchive' => 'Finna\Content\Covers\CoverArtArchive',
                ]
            ],
            'recorddriver' => [
                'factories' => [
                    'Finna\RecordDriver\EDS' =>
                        'VuFind\RecordDriver\NameBasedConfigFactory',
                    'Finna\RecordDriver\SolrDefault' =>
                        'VuFind\RecordDriver\SolrDefaultFactory',
                    'Finna\RecordDriver\SolrMarc' =>
                        'VuFind\RecordDriver\SolrDefaultFactory',
                    'Finna\RecordDriver\SolrAuthEaccpf' =>
                        'VuFind\RecordDriver\SolrDefaultFactory',
                    'Finna\RecordDriver\SolrEad' =>
                        'VuFind\RecordDriver\SolrDefaultFactory',
                    'Finna\RecordDriver\SolrEad3' =>
                        'VuFind\RecordDriver\SolrDefaultFactory',
                    'Finna\RecordDriver\SolrForward' =>
                        'VuFind\RecordDriver\SolrDefaultFactory',
                    'Finna\RecordDriver\SolrLido'
                        => 'Finna\RecordDriver\SolrLidoFactory',
                    'Finna\RecordDriver\SolrQdc' =>
                        'VuFind\RecordDriver\SolrDefaultFactory',
                    'Finna\RecordDriver\Primo' =>
                        'VuFind\RecordDriver\NameBasedConfigFactory',
                ],
                'aliases' => [
                    'SolrAuthEaccpf' => 'Finna\RecordDriver\SolrAuthEaccpf',
                    'SolrEad' => 'Finna\RecordDriver\SolrEad',
                    'SolrEad3' => 'Finna\RecordDriver\SolrEad3',
                    'SolrForward' => 'Finna\RecordDriver\SolrForward',
                    'SolrLido' => 'Finna\RecordDriver\SolrLido',
                    'SolrQdc' => 'Finna\RecordDriver\SolrQdc',

                    'VuFind\RecordDriver\EDS' => 'Finna\RecordDriver\EDS',
                    'VuFind\RecordDriver\SolrDefault' => 'Finna\RecordDriver\SolrDefault',
                    'VuFind\RecordDriver\SolrMarc' => 'Finna\RecordDriver\SolrMarc',
                    'VuFind\RecordDriver\Primo' => 'Finna\RecordDriver\Primo',
                ],
                'delegators' => [
                    'Finna\RecordDriver\SolrMarc' => [
                        'VuFind\RecordDriver\IlsAwareDelegatorFactory'
                    ],
                ],
            ],
            'recordtab' => [
                'factories' => [
                    'Finna\RecordTab\DescriptionFWD' => 'Finna\RecordTab\Factory::getDescriptionFWD',
                    'Finna\RecordTab\Distribution' => 'Finna\RecordTab\Factory::getDistribution',
                    'Finna\RecordTab\InspectionDetails' => 'Finna\RecordTab\Factory::getInspectionDetails',
                    'Finna\RecordTab\ItemDescription' => 'Finna\RecordTab\Factory::getItemDescription',
                    'Finna\RecordTab\LocationsEad3' => 'Finna\RecordTab\Factory::getLocationsEad3',
                    'Finna\RecordTab\Map' => 'Finna\RecordTab\Factory::getMap',
                    'Finna\RecordTab\Music' => 'Finna\RecordTab\Factory::getMusic',
                    'Finna\RecordTab\PressReviews' => 'Finna\RecordTab\Factory::getPressReviews',
                    'Finna\RecordTab\UserComments' => 'Finna\RecordTab\Factory::getUserComments',
                ],
                'invokables' => [
                    'componentparts' => 'Finna\RecordTab\ComponentParts',
                ],
                'aliases' => [
                    'componentparts' => 'Finna\RecordTab\ComponentParts',
                    'descriptionFWD' => 'Finna\RecordTab\DescriptionFWD',
                    'distribution' => 'Finna\RecordTab\Distribution',
                    'inspectionDetails' => 'Finna\RecordTab\InspectionDetails',
                    'itemdescription' => 'Finna\RecordTab\ItemDescription',
                    'LocationsEad3' => 'Finna\RecordTab\LocationsEad3',
                    'music' => 'Finna\RecordTab\Music',
                    'pressreview' => 'Finna\RecordTab\PressReviews',

                    // Overrides:
                    'VuFind\RecordTab\Map' => 'Finna\RecordTab\Map',
                    'VuFind\RecordTab\UserComments' => 'Finna\RecordTab\UserComments',
                ]
            ],
            'related' => [
                'factories' => [
                    'Finna\Related\RecordDriverRelated' => 'Finna\Related\RecordDriverRelatedFactory',
                    'Finna\Related\Nothing' => 'Zend\ServiceManager\Factory\InvokableFactory',
                    'Finna\Related\SimilarDeferred' => 'Zend\ServiceManager\Factory\InvokableFactory',
                    'Finna\Related\WorkExpressions' => 'Finna\Related\WorkExpressionsFactory',
                ],
                'aliases' =>  [
                    'nothing' => 'Finna\Related\Nothing',
                    'recorddriverrelated' => 'Finna\Related\RecordDriverRelated',
                    'similardeferred' => 'Finna\Related\SimilarDeferred',
                    'workexpressions' => 'Finna\Related\WorkExpressions',
                ]
            ],
        ],
        'recorddriver_collection_tabs' => [
            'Finna\RecordDriver\SolrEad' => [
                'tabs' => [
                    'CollectionList' => 'CollectionList',
                    'HierarchyTree' => 'CollectionHierarchyTree',
                    'UserComments' => 'UserComments',
                    'Details' => 'StaffViewArray',
                ],
                'defaultTab' => null,
            ],
        ],
        'recorddriver_tabs' => [
            'Finna\RecordDriver\EDS' => [
                'tabs' => [
                    'TOC' => 'TOC', 'UserComments' => 'UserComments',
                    'Reviews' => 'Reviews', 'Excerpt' => 'Excerpt',
                    'Preview' => 'preview',
                    'Details' => 'StaffViewArray',
                ],
                'defaultTab' => null,
            ],
            'Finna\RecordDriver\SolrDefault' => [
                'tabs' => [
                    'Holdings' => 'HoldingsILS',
                    'ComponentParts' => 'ComponentParts',
                    'TOC' => 'TOC', 'UserComments' => 'UserComments',
                    'Reviews' => 'Reviews', 'Excerpt' => 'Excerpt',
                    'Preview' => 'preview',
                    'HierarchyTree' => 'HierarchyTree', 'Map' => 'Map',
                    'Details' => 'StaffViewArray',
                ],
                'defaultTab' => null,
            ],
            'Finna\RecordDriver\SolrMarc' => [
                'tabs' => [
                    'Holdings' => 'HoldingsILS',
                    'ComponentParts' => 'ComponentParts',
                    'TOC' => 'TOC', 'UserComments' => 'UserComments',
                    'Reviews' => 'Reviews', 'Excerpt' => 'Excerpt',
                    'Preview' => 'preview',
                    'HierarchyTree' => 'HierarchyTree', 'Map' => 'Map',
                    'Details' => 'StaffViewMARC',
                ],
                'defaultTab' => null,
            ],
            'Finna\RecordDriver\SolrEad' => [
                'tabs' => [
                    'HierarchyTree' => 'HierarchyTree',
                    'UserComments' => 'UserComments',
                    'Reviews' => 'Reviews',
                    'Map' => 'Map',
                    'Details' => 'StaffViewArray',
                ],
                'defaultTab' => null,
            ],
            'Finna\RecordDriver\SolrEad3' => [
                'tabs' => [
                    'LocationsEad3' => 'LocationsEad3',
                    'HierarchyTree' => 'HierarchyTree',
                    'UserComments' => 'UserComments',
                    'Reviews' => 'Reviews',
                    'Map' => 'Map',
                    'Details' => 'StaffViewArray',
                ],
                'defaultTab' => null,
            ],
            'Finna\RecordDriver\SolrForward' => [
                'tabs' => [
                    'UserComments' => 'UserComments',
                    'Reviews' => 'Reviews',
                    'Map' => 'Map',
                    'PressReview' => 'PressReview',
                    'Music' => 'Music',
                    'Distribution' => 'Distribution',
                    'InspectionDetails' => 'InspectionDetails',
                    'DescriptionFWD' => 'DescriptionFWD',
                    'ItemDescription' => 'ItemDescription',
                    'Details' => 'StaffViewArray',
                ],
                'defaultTab' => null,
            ],
            'Finna\RecordDriver\SolrLido' => [
                'tabs' => [
                    'UserComments' => 'UserComments',
                    'Reviews' => 'Reviews',
                    'Map' => 'Map',
                    'Details' => 'StaffViewArray',
                ],
                'defaultTab' => null,
            ],
            'Finna\RecordDriver\SolrQdc' => [
                'tabs' => [
                    'UserComments' => 'UserComments',
                    'Reviews' => 'Reviews',
                    'Map' => 'Map',
                    'Details' => 'StaffViewArray',
                ],
                'defaultTab' => null,
            ],
            'Finna\RecordDriver\Primo' => [
                'tabs' => [
                    'UserComments' => 'UserComments',
                    'Details' => 'StaffViewArray'
                ],
                'defaultTab' => null,
            ],
        ],
    ],

    // Authorization configuration:
    'zfc_rbac' => [
        'vufind_permission_provider_manager' => [
            'factories' => [
                'Finna\Role\PermissionProvider\AuthenticationStrategy' => 'Finna\Role\PermissionProvider\AuthenticationStrategyFactory',
                'Finna\Role\PermissionProvider\IpRange' => 'VuFind\Role\PermissionProvider\IpRangeFactory'
            ],
            'aliases' => [
                'authenticationStrategy' => 'Finna\Role\PermissionProvider\AuthenticationStrategy',

                'VuFind\Role\PermissionProvider\IpRange' => 'Finna\Role\PermissionProvider\IpRange',
            ]
        ],
    ],

];

$recordRoutes = [
   'metalibrecord' => 'MetaLibRecord'
];

// Define dynamic routes -- controller => [route name => action]
$dynamicRoutes = [
    'Comments' => ['inappropriate' => 'inappropriate/[:id]'],
    'LibraryCards' => ['newLibraryCardPassword' => 'newPassword/[:id]'],
    'MyResearch' => ['sortList' => 'SortList/[:id]']
];

$staticRoutes = [
    'Browse/Database', 'Browse/Journal',
    'LibraryCards/Recover', 'LibraryCards/ResetPassword',
    'LocationService/Modal',
    'MetaLib/Home', 'MetaLib/Search', 'MetaLib/Advanced',
    'MyResearch/SaveCustomOrder', 'MyResearch/PurgeHistoricLoans',
    'OrganisationInfo/Home',
    'PCI/Home', 'PCI/Search', 'PCI/Record',
    'Search/StreetSearch',
    'Barcode/Show', 'Search/MapFacet'
];

$routeGenerator = new \VuFind\Route\RouteGenerator();
$routeGenerator->addRecordRoutes($config, $recordRoutes);
$routeGenerator->addDynamicRoutes($config, $dynamicRoutes);
$routeGenerator->addStaticRoutes($config, $staticRoutes);

return $config;
