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

use advanced_testcase;

/**
 * Unit tests for the \tiny_richtext\plugininfo class.
 *
 * @package     tiny_richtext
 * @covers      \tiny_richtext\plugininfo
 * @copyright   2026 Felix Yeung
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
final class plugininfo_test extends advanced_testcase {
    /**
     * Basic setup for tests.
     */
    public function setUp(): void {
        parent::setUp();
        $this->resetAfterTest(true);
    }

    /**
     * Test that get_available_tiny_plugins returns the expected plugin keys.
     */
    public function test_get_available_tiny_plugins_keys(): void {
        $plugins = plugininfo::get_available_tiny_plugins();
        static::assertSame(['forecolor', 'backcolor', 'fontsize', 'fontsizeinput', 'fontfamily'], array_keys($plugins));
    }

    /**
     * Test that each plugin has the expected areas.
     */
    public function test_get_available_tiny_plugins_areas(): void {
        $plugins = plugininfo::get_available_tiny_plugins();

        // Plugins with both toolbar and menubar.
        foreach (['forecolor', 'backcolor', 'fontsize', 'fontfamily'] as $plugin) {
            static::assertArrayHasKey('areas', $plugins[$plugin]);
            static::assertSame(['toolbar', 'menubar'], $plugins[$plugin]['areas']);
        }

        // Verify that fontsizeinput only has toolbar.
        static::assertArrayHasKey('areas', $plugins['fontsizeinput']);
        static::assertSame(['toolbar'], $plugins['fontsizeinput']['areas']);
    }

    /**
     * Test that each plugin option has the required fields.
     */
    public function test_get_available_tiny_plugins_options_have_required_fields(): void {
        $plugins = plugininfo::get_available_tiny_plugins();

        foreach ($plugins as $pluginname => $plugin) {
            static::assertArrayHasKey('options', $plugin);
            static::assertIsArray($plugin['options']);

            foreach ($plugin['options'] as $option) {
                static::assertArrayHasKey('name', $option, "Option in $pluginname missing 'name'");
                static::assertArrayHasKey('type', $option, "Option in $pluginname missing 'type'");
                static::assertArrayHasKey('config', $option, "Option in $pluginname missing 'config'");
                static::assertArrayHasKey(
                    'defaultsetting',
                    $option['config'],
                    "Option in $pluginname missing 'config.defaultsetting'",
                );
                static::assertContains(
                    $option['type'],
                    ['area', 'choice'],
                    "Option in $pluginname has invalid type: {$option['type']}",
                );
            }
        }
    }

    /**
     * Test that get_available_buttons returns an empty array.
     */
    public function test_get_available_buttons_returns_empty(): void {
        static::assertSame([], plugininfo::get_available_buttons());
    }

    /**
     * Test that get_available_menuitems returns an empty array.
     */
    public function test_get_available_menuitems_returns_empty(): void {
        static::assertSame([], plugininfo::get_available_menuitems());
    }

    /**
     * Test get_tiny_plugin_area_config_key format.
     */
    public function test_get_tiny_plugin_area_config_key_format(): void {
        static::assertSame('enable_forecolor_toolbar', plugininfo::get_tiny_plugin_area_config_key('forecolor', 'toolbar'));
        static::assertSame('enable_forecolor_menubar', plugininfo::get_tiny_plugin_area_config_key('forecolor', 'menubar'));
        static::assertSame('enable_backcolor_toolbar', plugininfo::get_tiny_plugin_area_config_key('backcolor', 'toolbar'));
        static::assertSame('enable_fontsizeinput_toolbar', plugininfo::get_tiny_plugin_area_config_key('fontsizeinput', 'toolbar'));
    }

    /**
     * Test get_tiny_option_config_key format.
     */
    public function test_get_tiny_option_config_key_format(): void {
        static::assertSame('tiny_option_color_map_foreground', plugininfo::get_tiny_option_config_key('color_map_foreground'));
        static::assertSame('tiny_option_color_map_background', plugininfo::get_tiny_option_config_key('color_map_background'));
        static::assertSame('tiny_option_font_size_formats', plugininfo::get_tiny_option_config_key('font_size_formats'));
        static::assertSame(
            'tiny_option_font_size_input_default_unit',
            plugininfo::get_tiny_option_config_key('font_size_input_default_unit'),
        );
        static::assertSame('tiny_option_font_family_formats', plugininfo::get_tiny_option_config_key('font_family_formats'));
    }

    /**
     * Test get_plugin_configuration_for_context with no config set returns empty defaults.
     */
    public function test_get_plugin_configuration_no_config(): void {
        $context = \context_system::instance();
        $result = plugininfo::get_plugin_configuration_for_context($context, [], []);

        static::assertArrayHasKey('enabledareas', $result);
        static::assertArrayHasKey('tinyoptions', $result);
        static::assertSame(['_' => []], $result['enabledareas']);
        static::assertSame(['_' => []], $result['tinyoptions']);
    }

    /**
     * Test that enabling a control in toolbar populates enabledareas correctly.
     */
    public function test_get_plugin_configuration_enables_toolbar_area(): void {
        set_config('enable_forecolor_toolbar', '1', 'tiny_richtext');

        $context = \context_system::instance();
        $result = plugininfo::get_plugin_configuration_for_context($context, [], []);

        static::assertArrayHasKey('toolbar', $result['enabledareas']);
        static::assertContains('forecolor', $result['enabledareas']['toolbar']);
        // Menubar should not be enabled for forecolor.
        static::assertArrayNotHasKey('menubar', $result['enabledareas']);
    }

    /**
     * Test that enabling a control in menubar populates enabledareas correctly.
     */
    public function test_get_plugin_configuration_enables_menubar_area(): void {
        set_config('enable_fontfamily_menubar', '1', 'tiny_richtext');

        $context = \context_system::instance();
        $result = plugininfo::get_plugin_configuration_for_context($context, [], []);

        static::assertArrayHasKey('menubar', $result['enabledareas']);
        static::assertContains('fontfamily', $result['enabledareas']['menubar']);
    }

    /**
     * Test that enabling a control in both toolbar and menubar works.
     */
    public function test_get_plugin_configuration_enables_both_areas(): void {
        set_config('enable_backcolor_toolbar', '1', 'tiny_richtext');
        set_config('enable_backcolor_menubar', '1', 'tiny_richtext');

        $context = \context_system::instance();
        $result = plugininfo::get_plugin_configuration_for_context($context, [], []);

        static::assertContains('backcolor', $result['enabledareas']['toolbar']);
        static::assertContains('backcolor', $result['enabledareas']['menubar']);
    }

    /**
     * Test that an unset option config key is not present in tinyoptions.
     */
    public function test_get_plugin_configuration_option_not_set(): void {
        $context = \context_system::instance();
        $result = plugininfo::get_plugin_configuration_for_context($context, [], []);

        static::assertArrayNotHasKey('color_map_foreground', $result['tinyoptions']);
        static::assertArrayNotHasKey('font_size_formats', $result['tinyoptions']);
        static::assertArrayNotHasKey('font_family_formats', $result['tinyoptions']);
    }

    /**
     * Test that the colour map transform produces the expected flat array.
     */
    public function test_get_plugin_configuration_colour_transform(): void {
        set_config('tiny_option_color_map_foreground', "#ff0000 Red\n#00ff00 Green", 'tiny_richtext');

        $context = \context_system::instance();
        $result = plugininfo::get_plugin_configuration_for_context($context, [], []);

        static::assertArrayHasKey('color_map_foreground', $result['tinyoptions']);
        static::assertSame(['#ff0000', 'Red', '#00ff00', 'Green'], $result['tinyoptions']['color_map_foreground']);
    }

    /**
     * Test that the font size formats transform produces a space-separated string.
     */
    public function test_get_plugin_configuration_fontsize_transform(): void {
        set_config('tiny_option_font_size_formats', "8pt\n10pt", 'tiny_richtext');

        $context = \context_system::instance();
        $result = plugininfo::get_plugin_configuration_for_context($context, [], []);

        static::assertArrayHasKey('font_size_formats', $result['tinyoptions']);
        static::assertSame('8pt 10pt', $result['tinyoptions']['font_size_formats']);
    }

    /**
     * Test that the font family formats transform produces a semicolon-separated string.
     */
    public function test_get_plugin_configuration_fontfamily_transform(): void {
        set_config(
            'tiny_option_font_family_formats',
            "Arial=arial,helvetica,sans-serif\nCourier New=courier new,courier",
            'tiny_richtext',
        );

        $context = \context_system::instance();
        $result = plugininfo::get_plugin_configuration_for_context($context, [], []);

        static::assertArrayHasKey('font_family_formats', $result['tinyoptions']);
        static::assertSame(
            'Arial=arial,helvetica,sans-serif; Courier New=courier new,courier',
            $result['tinyoptions']['font_family_formats'],
        );
    }

    /**
     * Test that the fontsizeinput choice option stores the selected value directly.
     */
    public function test_get_plugin_configuration_fontsizeinput_choice(): void {
        set_config('tiny_option_font_size_input_default_unit', 'px', 'tiny_richtext');

        $context = \context_system::instance();
        $result = plugininfo::get_plugin_configuration_for_context($context, [], []);

        static::assertArrayHasKey('font_size_input_default_unit', $result['tinyoptions']);
        static::assertSame('px', $result['tinyoptions']['font_size_input_default_unit']);
    }

    /**
     * Test that all five controls can be enabled simultaneously.
     */
    public function test_get_plugin_configuration_all_controls_enabled(): void {
        set_config('enable_forecolor_toolbar', '1', 'tiny_richtext');
        set_config('enable_backcolor_toolbar', '1', 'tiny_richtext');
        set_config('enable_fontsize_toolbar', '1', 'tiny_richtext');
        set_config('enable_fontsizeinput_toolbar', '1', 'tiny_richtext');
        set_config('enable_fontfamily_toolbar', '1', 'tiny_richtext');

        $context = \context_system::instance();
        $result = plugininfo::get_plugin_configuration_for_context($context, [], []);

        static::assertContains('forecolor', $result['enabledareas']['toolbar']);
        static::assertContains('backcolor', $result['enabledareas']['toolbar']);
        static::assertContains('fontsize', $result['enabledareas']['toolbar']);
        static::assertContains('fontsizeinput', $result['enabledareas']['toolbar']);
        static::assertContains('fontfamily', $result['enabledareas']['toolbar']);
    }

    /**
     * Test that an empty config value is treated as not set.
     */
    public function test_get_plugin_configuration_empty_string_option_ignored(): void {
        set_config('tiny_option_color_map_foreground', '', 'tiny_richtext');

        $context = \context_system::instance();
        $result = plugininfo::get_plugin_configuration_for_context($context, [], []);

        static::assertArrayNotHasKey('color_map_foreground', $result['tinyoptions']);
    }
}
