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

        foreach ($plugins as $plugin => $plugindata) {
            ['areas' => $areas, 'options' => $options] = $plugindata;
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

            foreach ($options as $option) {
                $configkey = plugininfo::get_tiny_option_config_key($option['name']);
                $name = new lang_string("tiny:option:{$option['name']}", $component);
                $description = new lang_string("tiny:option:{$option['name']}_desc", $component);
                $common = [
                    'name' => "$component/$configkey",
                    'visiblename' => $name,
                    'description' => $description,
                ];

                /** @var class-string<admin_setting> $settingclass */
                $settingclass = match ($option['type']) {
                    'choice' => admin_setting_configselect::class,
                    'area' => admin_setting_configtextarea::class,
                    default => null,
                };

                if ($settingclass === null) {
                    continue;
                }

                $setting = new $settingclass(
                    ...$common,
                    ...$option['config'],
                );

                $setting && $settings->add($setting);
            }
        }

        $settings->add(new admin_setting_heading(
            "$component/editor_playground",
            new lang_string('settings:playground', $component),
            new lang_string('settings:playground_desc', $component),
        ));

        $settings->add(new admin_setting_confightmleditor(
            "$component/playground",
            new lang_string('settings:playground', $component),
            '',
            <<<LOREM
            Lorem ipsum dolor sit amet consectetur, adipisicing elit.
            Enim atque quas aliquid, aliquam corporis explicabo id eum rerum harum veritatis natus maxime?
            Neque impedit corporis tempora repudiandae laudantium aliquid delectus?,
            LOREM,
        ));
    }
}
