<?php
/**
 * User publicly show name view helper
 *
 * PHP version 5
 *
 * Copyright (C) The National Library of Finland 2015-2017.
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
 * @package  View_Helpers
 * @author   Mika Hatakka <mika.hatakka@helsinki.fi>
 * @license  http://opensource.org/licenses/gpl-2.0.php GNU General Public License
 * @link     http://vufind.org/wiki/vufind2:developer_manual Wiki
 */
namespace Finna\View\Helper\Root;

/**
 * User publicly show name view helper
 *
 * @category VuFind
 * @package  View_Helpers
 * @author   Mika Hatakka <mika.hatakka@helsinki.fi>
 * @author   Konsta Raunio <konsta.rauniohelsinki.fi>
 * @license  http://opensource.org/licenses/gpl-2.0.php GNU General Public License
 * @link     http://vufind.org/wiki/vufind2:developer_manual Wiki
 */
class UserPublicName extends \Zend\View\Helper\AbstractHelper
{
    /**
     * Create publicly shown user name
     *
     * @param object $user current user information
     *
     * @return string
     */
    public function __invoke($user)
    {
        $username = '';
        if ($user) {
            if ($user->email
                && ($pos = strpos($user->email, '@')) !== false
                && $user->finna_nickname == null
            ) {
                $username = substr($user->email, 0, $pos);
            } else if ($user->firstname && $user->lastname
                && $user->finna_nickname == null
            ) {
                $username = "$user->firstname $user->lastname";
            } else if ($user->finna_nickname != null) {
                $username = $user->finna_nickname;
            }
        }
        return $username;
    }
}
