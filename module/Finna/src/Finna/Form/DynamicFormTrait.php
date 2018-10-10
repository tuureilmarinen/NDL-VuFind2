<?php
/**
 * Dynamic form trait.
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
 * @package  Controller
 * @author   Samuli Sillanpää <samuli.sillanpaa@helsinki.fi>
 * @license  http://opensource.org/licenses/gpl-2.0.php GNU General Public License
 * @link     https://vufind.org/wiki/development:plugins:controllers Wiki
 */
namespace Finna\Form;

/**
 * Dynamic form trait.
 *
 * @category VuFind
 * @package  Controller
 * @author   Samuli Sillanpää <samuli.sillanpaa@helsinki.fi>
 * @license  http://opensource.org/licenses/gpl-2.0.php GNU General Public License
 * @link     https://vufind.org/wiki/development:plugins:controllers Wiki
 */
trait DynamicFormTrait
{
    /**
     * Get form configurtion.
     *
     * @param string                       $formId       Form id
     * @param \VuFind\Config\PluginManager $configReader Config reader
     *
     * @return mixed null|array
     */
    protected function getFormConfig($formId = null)
    {
        $confName = 'FeedbackForms.json';
        $localConfig = $parentConfig = $config = null;
        
        if ($parentFile = \VuFind\Config\Locator::getLocalConfigPath(
            $confName, 'config/finna'
        )
        ) {
            $parentConfig = json_decode(file_get_contents($parentFile), true);
        }

        if ($localFile = \VuFind\Config\Locator::getLocalConfigPath($confName)) {
            $localConfig = json_decode(file_get_contents($localFile), true);
        }

        if (JSON_ERROR_NONE !== ($error = json_last_error())) {
            die("json err");
            return null;
        }

        $config = $parentConfig;
        if ($localConfig) {
            // Handle non-overridable forms before merging local config
            foreach ($parentConfig['forms'] as $id => $form) {
                if (isset($form['allowOverride'])
                    && $form['allowOverride'] === false
                    && isset($localConfig['forms'][$id])
                ) {
                    $localConfig['forms'][$id] = $parentConfig['forms'][$id];
                }
            }
            $config = array_replace($parentConfig, $localConfig);
        }

        if (!$formId) {
            if (!isset($config['default'])) {
                return null;
            }
            $formId = $config['default'];
        }

        if (! isset($config['forms'][$formId])) {
            return null;
        }

        return $config['forms'][$formId];
    }

    /*
    protected function getFormConfig($formId = null)
    {
        $confName = 'FeedbackForms.json';
        $file = \VuFind\Config\Locator::getLocalConfigPath($confName);
        if ($file === null) {
            $file = \VuFind\Config\Locator::getLocalConfigPath(
                $confName, 'config/finna'
            );
            if ($file === null) {
                $file = \VuFind\Config\Locator::getConfigPath($confName);
            }
        }

        $config = json_decode(file_get_contents($file), true);

        if (JSON_ERROR_NONE !== ($error = json_last_error())) {
            die("json err");
            return null;
        }


        if (!$formId) {
            if (!isset($config['default'])) {
                return null;
            }
            $formId = $config['default'];
        }


        if (! isset($config['forms'][$formId])) {
            return null;
        }

        return $config['forms'][$formId];
        }*/

    /**
     * Get general form settings (General-section in the ini).
     *
     * @param array $config Form configuration
     *
     * @return mixed null|\Zend\Config
     */
    /*
    protected function getFormSettings($config)
    {
        return !empty($config['General']) ? $config['General'] : null;
        }*/
    /*
    public function getFormTitle($config)
    {
        return $config['title'] ?? null;
        }*/
    /*
    public function getFormEmailSubject($config)
    {
        return $config['emailSubject'] ?? null;
        }*/

    /**
     * Get form email settings.
     *
     * @param array $config Form configuration
     *
     * @return mixed null|\Zend\Config
     */
    /*
    protected function getFormEmailSettings($config)
    {
        $email = [];
        foreach (
           ['email-sender-name', 'email-sender-address', 'email-subject',
            'email-reply-to-address', 'email-reply-to-name',
            'email-recipient-name', 'email-recipient-address',
           ] as $key
        ) {
            if (!empty($config['General'][$key])) {
                $email[substr($key, 6)] = $config['General'][$key];
            }
        }
        return $email;
        }*/

    /**
     * Get form element whose value specifies the recipient address.
     *
     * @param array $config Form configuration
     *
     * @return mixed null|string
     */
    /*
    protected function getFormRecipientElement($config)
    {
        if (!empty($config['General']['email-recipient-address'])) {
            $recipient = $config['General']['email-recipient-address'];
            if ($recipient[0] == '_' && $recipient[strlen($recipient) - 1] == '_') {
                return substr($recipient, 1, -1);
            }
        }
        return null;
        }*/
    
    /**
     * Get form elements
     *
     * @param array $config Form configuration
     *
     * @return mixed null|array
     */
    protected function getFormElements($config)
    {
        $elements = [];
        foreach ($config['fields'] as $field) {
            /*
            if (in_array($key, ['General', '__form_sort__'])) {
                continue;
                }
            if (!empty($el->disabled)) {
                continue;
                }*/
            if (!isset($field['type'])) {
                continue;
            }
            $elements[] = $field;
        }
        return $elements;
    }

    /**
     * Get a hash for a form select option element id.
     * Use this when the option id can not be exposed
     * as plain text to the UI.
     *
     * @param string $option Option id
     *
     * @return string
     */
    /*
    protected function getFormOptionHash($option)
    {
        return md5($option);
        }*/

    /**
     * Return form option element id from a hashed id.
     * Use this to resolve a id from a submitted hashed id.
     *
     * @param string $hash    Hashed option id.
     * @param array  $options Option ids in plain text.
     *
     * @return string
     */
    /*
    protected function getFormOptionFromHash($hash, $options)
    {
        foreach ($options as $option) {
            if ($this->getFormOptionHash($option) === $hash) {
                return $option;
            }
        }
        return null;
        }*/

}
