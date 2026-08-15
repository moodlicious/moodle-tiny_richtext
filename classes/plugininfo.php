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
use tiny_richtext\local\utils;

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
     * @return array<string, array{
     *     areas: ('toolbar'|'menubar')[],
     *     options: array{name: string, type: 'area'|'choice', config: mixed[], transform?: callable}[]
     * }>
     * @link https://www.tiny.cloud/docs/tinymce/latest/user-formatting-options Available options and default values
     */
    public static function get_available_tiny_plugins(): array {
        $defaultcolours = trim(
            <<<COLOURS
            #bfedd2 Light Green
            #fbeeb8 Light Yellow
            #f8cac6 Light Red
            #eccafa Light Purple
            #c2e0f4 Light Blue
            #2dc26b Green
            #f1c40f Yellow
            #e03e2d Red
            #b96ad9 Purple
            #3598db Blue
            #169179 Dark Turquoise
            #e67e23 Orange
            #ba372a Dark Red
            #843fa1 Dark Purple
            #236fa1 Dark Blue
            #ecf0f1 Light Gray
            #ced4d9 Medium Gray
            #95a5a6 Gray
            #7e8c8d Dark Gray
            #34495e Navy Blue
            #000000 Black
            #ffffff White
            COLOURS
        );

        $colourtransform = function (string $value): array {
            $lines = utils::get_lines($value);
            $values = array_map(fn(string $v): array => explode(' ', $v, 2), $lines);
            return array_values(array_merge(...$values));
        };

        return [
            'forecolor' => [
                'areas' => ['toolbar', 'menubar'],
                'options' => [
                    [
                        'type' => 'area',
                        'name' => 'color_map_foreground',
                        'config' => [
                            'defaultsetting' => $defaultcolours,
                        ],
                        'transform' => $colourtransform,
                    ],
                ],
            ],
            'backcolor' => [
                'areas' => ['toolbar', 'menubar'],
                'options' => [
                    [
                        'type' => 'area',
                        'name' => 'color_map_background',
                        'config' => [
                            'defaultsetting' => $defaultcolours,
                        ],
                        'transform' => $colourtransform,
                    ],
                ],
            ],
            'fontsize' => [
                'areas' => ['toolbar', 'menubar'],
                'options' => [
                    [
                        'type' => 'area',
                        'name' => 'font_size_formats',
                        'config' => [
                            'defaultsetting' => "8pt\n10pt\n12pt\n14pt\n18pt\n24pt\n36pt",
                        ],
                        'transform' => function (string $value): string {
                            $lines = utils::get_lines($value);
                            return implode(' ', $lines);
                        },
                    ],
                ],
            ],
            'fontsizeinput' => [
                'areas' => ['toolbar'],
                'options' => [
                    [
                        'type' => 'choice',
                        'name' => 'font_size_input_default_unit',
                        'config' => [
                            'defaultsetting' => 'em',
                            'choices' => fn(): array => ['pt', 'px', 'em', 'cm', 'mm'],
                        ],
                    ],
                ],
            ],
            'fontfamily' => [
                'areas' => ['toolbar', 'menubar'],
                'options' => [
                    [
                        'type' => 'area',
                        'name' => 'font_family_formats',
                        'config' => [
                            'defaultsetting' => trim(
                                <<<SETTING
                                Andale Mono=andale mono,times
                                Arial=arial,helvetica,sans-serif
                                Arial Black=arial black,avant garde
                                Book Antiqua=book antiqua,palatino
                                Comic Sans MS=comic sans ms,sans-serif
                                Courier New=courier new,courier
                                Georgia=georgia,palatino
                                Helvetica=helvetica
                                Impact=impact,chicago
                                Symbol=symbol
                                Tahoma=tahoma,arial,helvetica,sans-serif
                                Terminal=terminal,monaco
                                Times New Roman=times new roman,times
                                Trebuchet MS=trebuchet ms,geneva
                                Verdana=verdana,geneva
                                Webdings=webdings
                                Wingdings=wingdings,zapf dingbats
                                SETTING
                            ),
                        ],
                        'transform' => fn(string $value): string => implode('; ', utils::get_lines($value)),
                    ],
                ],
            ],
            'charmap' => [
                'areas' => ['toolbar', 'menubar'],
                'options' => [],
            ],
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
     * Retruns the configuration key for a specific plugin/area pair.
     */
    public static function get_tiny_plugin_area_config_key(string $plugin, string $area): string {
        return "enable_{$plugin}_{$area}";
    }

    /**
     * Returns the configuration key for a specific tiny option.
     */
    public static function get_tiny_option_config_key(string $option): string {
        return "tiny_option_$option";
    }

    #[\Override]
    public static function get_plugin_configuration_for_context(
        context $context,
        array $options,
        array $fpoptions,
        ?editor $editor = null,
    ): array {
        $component = self::COMPONENT_NAME;
        $plugins = self::get_available_tiny_plugins();
        $enabledareas = [
            '_' => [],
        ];
        $tinyoptions = [
            '_' => [],
        ];

        foreach ($plugins as $plugin => $plugindata) {
            ['areas' => $areas, 'options' => $pluginoptions] = $plugindata;
            foreach ($areas as $area) {
                $enabled = (bool) get_config($component, self::get_tiny_plugin_area_config_key($plugin, $area));
                if (!$enabled) {
                    continue;
                }

                $enabledareas[$area] ??= [];
                $enabledareas[$area][] = $plugin;
            }

            foreach ($pluginoptions as $option) {
                $value = get_config($component, self::get_tiny_option_config_key($option['name']));
                if ($value === false || $value === '') {
                    continue;
                }
                if (isset($option['transform'])) {
                    $value = $option['transform']($value);
                }
                $tinyoptions[$option['name']] = $value;
            }
        }

        return [
            'enabledareas' => $enabledareas,
            'tinyoptions' => $tinyoptions,
        ];
    }
}
