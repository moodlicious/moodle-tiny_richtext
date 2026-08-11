<?php
// This file is part of Moodle - https://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <https://www.gnu.org/licenses/>.

/**
 * Plugin administration pages are defined here.
 *
 * @var admin_root $ADMIN
 * @var bool $hassiteconfig
 *
 * @package     tiny_richtext
 * @category    admin
 * @copyright   2026 Felix Yeung
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

use core\lang_string;

defined('MOODLE_INTERNAL') || die();

$component = 'tiny_richtext';

if ($hassiteconfig) {
    $settings = new admin_settingpage('tiny_richtext_settings', new lang_string('pluginname', $component));

    if ($ADMIN->fulltree) {
        $yesnochoices = fn() => [
            (int) false => new lang_string('no'),
            (int) true => new lang_string('yes'),
        ];

        $identifiers = [
            'forecolor' => ['toolbar', 'menubar'],
            'backcolor' => ['toolbar', 'menubar'],
            'fontsizeinput' => ['toolbar', 'menubar'],
            'fontfamily' => ['toolbar', 'menubar'],
        ];

        foreach ($identifiers as $identifier => $areas) {
            $identifierstring = new lang_string("tiny:identifier:$identifier", $component);
            $settings->add(
                new admin_setting_heading(
                    "$component/$identifier",
                    $identifierstring,
                    '',
                ),
            );

            foreach ($areas as $area) {
                $areastring = new lang_string("tiny:area:$area", $component);
                $setting = new admin_setting_configselect(
                    "$component/enable_{$identifier}_{$area}",
                    new lang_string('settings:showidentifierinarea', $component, [
                        'identifier' => $identifierstring,
                        'area' => $areastring,
                    ]),
                    '',
                    (int) false,
                    $yesnochoices,
                );
                $settings->add($setting);
            }
        }
    }
}
