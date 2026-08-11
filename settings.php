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
use tiny_richtext\plugininfo;

defined('MOODLE_INTERNAL') || die();

$component = plugininfo::COMPONENT_NAME;

if ($hassiteconfig) {
    $settings = new admin_settingpage('tiny_richtext_settings', new lang_string('pluginname', $component));

    if ($ADMIN->fulltree) {
        $yesnochoices = fn(): array => [
            (int) false => new lang_string('no'),
            (int) true => new lang_string('yes'),
        ];

        $plugins = plugininfo::get_available_tiny_plugins();

        foreach ($plugins as $plugin => $areas) {
            $pluginstring = new lang_string("tiny:plugin:$plugin", $component);
            $settings->add(
                new admin_setting_heading(
                    "$component/$plugin",
                    $pluginstring,
                    '',
                ),
            );

            foreach ($areas as $area) {
                $areastring = new lang_string("tiny:area:$area", $component);
                $configkey = plugininfo::get_tiny_plugin_area_config_key($plugin, $area);
                $setting = new admin_setting_configselect(
                    "$component/$configkey",
                    new lang_string('settings:showplugininarea', $component, [
                        'plugin' => $pluginstring,
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
