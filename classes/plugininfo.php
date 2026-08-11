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

declare(strict_types=1);

namespace tiny_richtext;

use core\context;
use editor_tiny\editor;
use editor_tiny\plugin;
use editor_tiny\plugin_with_buttons;
use editor_tiny\plugin_with_configuration;
use editor_tiny\plugin_with_menuitems;

/**
 * Tiny Rich Text plugin for Moodle.
 *
 * @package     tiny_richtext
 * @copyright   2026 Felix Yeung
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class plugininfo extends plugin implements plugin_with_buttons, plugin_with_configuration, plugin_with_menuitems {
    /** @var string */
    public const COMPONENT_NAME = 'tiny_richtext';

    /**
     * Retrives list of tiny core plugins enabled by this plugin.
     * @return array<string, ('toolbar'|'menubar')[]>
     */
    public static function get_available_tiny_core_identifiers(): array {
        return [
            'forecolor' => ['toolbar', 'menubar'],
            'backcolor' => ['toolbar', 'menubar'],
            'fontsize' => ['toolbar', 'menubar'],
            'fontsizeinput' => ['toolbar'],
            'fontfamily' => ['toolbar', 'menubar'],
            'charmap' => ['toolbar', 'menubar'],
        ];
    }

    #[\Override]
    public static function get_available_buttons(): array {
        return [];
    }

    #[\Override]
    public static function get_available_menuitems(): array {
        return [];
    }

    /**
     * Retruns the configuration key for a specific identifier/area pair.
     */
    public static function get_identifier_area_config_key(string $identifier, string $area): string {
        return "enable_{$identifier}_{$area}";
    }

    #[\Override]
    public static function get_plugin_configuration_for_context(
        context $context,
        array $options,
        array $fpoptions,
        ?editor $editor = null,
    ): array {
        $component = self::COMPONENT_NAME;
        $identifiers = self::get_available_tiny_core_identifiers();
        $enabledareas = [
            'none' => [],
        ];

        foreach ($identifiers as $identifier => $areas) {
            foreach ($areas as $area) {
                $enabled = (bool) get_config($component, self::get_identifier_area_config_key($identifier, $area));
                if (!$enabled) {
                    continue;
                }

                $enabledareas[$area] ??= [];
                $enabledareas[$area][] = $identifier;
            }
        }

        return [
            'enabledareas' => $enabledareas,
        ];
    }
}
