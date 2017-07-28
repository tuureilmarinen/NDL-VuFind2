<?php
/**
 * Dynamic form view helper
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
 * Dynamic form view helper
 *
 * @category VuFind
 * @package  View_Helpers
 * @author   Samuli Sillanpää <samuli.sillanpaa@helsinki.fi>
 * @license  http://opensource.org/licenses/gpl-2.0.php GNU General Public License
 * @link     http://vufind.org   Main Site
 */
class DynamicForm extends \Zend\View\Helper\AbstractHelper
{
    use \Finna\Form\DynamicFormTrait;
    
    /**
     * Config Reader
     *
     * @var \VuFind\Config\PluginManager
     */
    protected $configReader;

    /**
     * Config Reader
     *
     * @var \VuFind\AuthManager
     */
    protected $authManager;

    /**
     * Constructor
     *
     * @param \VuFind\Config\PluginManager $configReader Config Reader
     * @param \VuFind\AuthManager          $authManager  Authentication manager
     */
    public function __construct($configReader, $authManager)
    {
        $this->configReader = $configReader;
        $this->authManager = $authManager;
    }

    /**
     * Returns HTML for embedding a dynamic form.
     *
     * @param string $formId Form id
     * @param array  $params Key-value array of parameters:
     * - elementsOnly <boolean> Output only form elements
     *   (without form tag, submit button, captcha)
     * - prefill <array> Llist of element-value pairs to prefill.
     *
     * @return mixed null|string
     */
    public function __invoke($formId, $params = null)
    {
        if (!$config = $this->getFormConfig($formId, $this->configReader)) {
            return null;
        }

        $view = $this->getView();
        if (null === ($general = $this->getFormSettings($config))) {
            return;
        }

        $form = [
           'id' => $formId,
           'name' => !empty($general->name) ? $general->name : $formId
        ];
        foreach (['title','help','submit','action'] as $key) {
            if (!empty($general->{$key})) {
                $form[$key] = $general->{$key};
            }
        }
        
        $recipientElement = $this->getFormRecipientElement($config);
        
        $elements = [];
        foreach ($this->getFormElements($config) as $key => $el) {
            $element = ['id' => $key];
            foreach (
                ['label','required','css',
                 'help','value','title','template'
                ]
                as $field
            ) {
                if (isset($el->$field)) {
                    if (is_string($el->$field)) {
                        $fieldVal = explode(',', $el->$field);
                        if (count($fieldVal) === 1) {
                            $fieldVal = $fieldVal[0];
                        }
                    } else {
                        $fieldVal = $el->$field->toArray();
                    }
                    $element[$field] = $fieldVal;
                }
            }

            $type = explode('|', $el->type);
            $element['type'] = $elementType = $type[0];
            $inputType = null;

            if ($elementType == 'input') {
                if (!empty($type[1])) {
                    $element['inputType'] = $inputType = $type[1];
                }
            }

            // Prefill information for logged-in user
            if (in_array($el->value, ['<user.email>', '<user.name>'])) {
                if ($user = $this->authManager->isLoggedIn()) {
                    switch ($el->value) {
                    case '<user.email>':
                        $element['value'] = $user['email'];
                        break;
                    case '<user.name>':
                        $element['value'] = $user->getDisplayName();
                        break;
                    }
                }
            }

            if (!empty($params['prefill'][$key])) {
                $element['value'] = $params['prefill'][$key];
            }

            // Options
            if (in_array($elementType, ['select', 'multiselect'])
                || $inputType == 'radio'
            ) {
                if (empty($el->options)) {
                    continue;
                }
                //$recipientAddress = !empty($el->recipientAddress);
                $options = [];
                foreach ($el->options as $option) {
                    $optionId = $optionLabel = $option;
                    $optionData = explode('|', $option);
                    if (count($optionData) > 1) {
                        $optionId = $optionData[0];
                        $optionLabel = $optionData[1];
                    }
                    if ($recipientElement == $key) {
                        $optionId = $this->getFormOptionHash($optionId);
                    }
                    $options[] = ['id' => $optionId, 'label' => $optionLabel];
                        
                }
                $element['options'] = $options;
                
                if (isset($el['active'])) {
                    $active = $el['active']->toArray();
                    if (count($active) == 1) {
                        $active = $active[0];
                    }
                    $element['active'] = $active;
                } else {
                    $element['active'] = $options[0]['id'];
                }
            }
            if (isset($el['settings'])) {
                $settings = [];
                foreach ($el['settings'] as $setting) {
                    list($settingId, $settingVal) = explode('=', $setting);
                    $settings[trim($settingId)] = trim($settingVal);
                }
                $element['settings'] = $settings;
            }
            if ($elementType == 'input' && !isset($element['settings']['size'])) {
                $element['settings']['size'] = 40;
            }
            
            if ($elementType == 'textarea') {
                if (!isset($element['settings']['cols'])) {
                    $element['settings']['cols'] = 40;
                }
                if (!isset($element['settings']['rows'])) {
                    $element['settings']['rows'] = 5;
                }
            }

            $elements[] = $element;
        }
        $form['elements'] = $elements;

        $tpl = !empty($params['elementsOnly'])
            ? 'dynamic-form-elements.phtml' : 'dynamic-form.phtml';
            
        $view = $this->getView();
        return $view->render(
            "Helpers/$tpl",
            ['form' => $form, 'useRecaptcha' => !empty($view->useRecaptcha)]
        );
    }
}
