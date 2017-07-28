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
    protected function getFormConfig($formId, $configReader)
    {
        $config =  $configReader->get("form{$formId}");
        return $config->count() ? $config : null;
    }

    /**
     * Get general form settings (General-section in the ini).
     *
     * @param array $config Form configuration
     *
     * @return mixed null|\Zend\Config
     */
    protected function getFormSettings($config)
    {
        return !empty($config['General']) ? $config['General'] : null;
    }

    /**
     * Get form email settings.
     *
     * @param array $config Form configuration
     *
     * @return mixed null|\Zend\Config
     */
    protected function getFormEmailSettings($config)
    {
        return !empty($config['General']['email'])
            ? $config['General']['email'] : null;
    }

    /**
     * Get form element whose value specifies the recipient address.
     *
     * @param array $config Form configuration
     *
     * @return mixed null|string
     */
    protected function getFormRecipientElement($config)
    {
        if (!empty($config['General']['email']['recipient-email'])) {
            $recipient = $config['General']['email']['recipient-email'];
            if ($recipient[0] == '<' && $recipient[strlen($recipient) - 1] == '>') {
                return substr($recipient, 1, -1);
            }
        }
        return null;
    }
    
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
        foreach ($config as $key => $el) {
            if (in_array($key, ['General', '__form_sort__'])) {
                continue;
            }
            if (!empty($el->disabled)) {
                continue;
            }
            if (!isset($el->type)) {
                continue;
            }
            $elements[$key] = $el;
        }

        // Sort elements
        if (!empty($config['__form_sort__'])) {
            foreach ($config['__form_sort__'] as $key => $pos) {
                $currentPosition = $this->getFormElementIndex(
                    $elements, $key
                );
                if ($currentPosition === null) {
                    continue;
                }
                $elements = $this->moveFormElement(
                    $elements, $currentPosition, $pos
                );
            }
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
    protected function getFormOptionHash($option)
    {
        return md5($option);
    }

    /**
     * Return form option element id from a hashed id.
     * Use this to resolve a id from a submitted hashed id.
     *
     * @param string $hash    Hashed option id.
     * @param array  $options Option ids in plain text.
     *
     * @return string
     */
    protected function getFormOptionFromHash($hash, $options)
    {
        foreach ($options as $option) {
            if ($this->getFormOptionHash($option) === $hash) {
                return $option;
            }
        }
        return null;
    }

    /**
     * Get form element index
     *
     * @param array  $elements Form elements
     * @param string $id       Form element id
     *
     * @return mixed null|int
     */
    protected function getFormElementIndex($elements, $id)
    {
        $cnt = 0;
        foreach ($elements as $key => $val) {
            if ($key === $id) {
                return $cnt;
            }
            $cnt++;
        }
        return null;
    }

    /**
     * Move form element
     *
     * @param array $elements Form elements
     * @param int   $from     From (index)
     * @param int   $to       To (index)
     *
     * @return array Items
     */
    protected function moveFormElement($elements, $from, $to)
    {
        if ($from < 0 || $to < 0) {
            return $elements;
        }
        $move = array_splice($elements, $from, 1);
        array_splice($elements, $to, 0, $move);
        return $elements;
    }
}
