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
        $this->assertSame(['forecolor', 'backcolor', 'fontsize', 'fontsizeinput', 'fontfamily'], array_keys($plugins));
    }

    /**
     * Test that each plugin has the expected areas.
     */
    public function test_get_available_tiny_plugins_areas(): void {
        $plugins = plugininfo::get_available_tiny_plugins();

        // Plugins with both toolbar and menubar.
        foreach (['forecolor', 'backcolor', 'fontsize', 'fontfamily'] as $plugin) {
            $this->assertArrayHasKey('areas', $plugins[$plugin]);
            $this->assertSame(['toolbar', 'menubar'], $plugins[$plugin]['areas']);
        }

        // Verify that fontsizeinput only has toolbar.
        $this->assertArrayHasKey('areas', $plugins['fontsizeinput']);
        $this->assertSame(['toolbar'], $plugins['fontsizeinput']['areas']);
    }

    /**
     * Test that each plugin option has the required fields.
     */
    public function test_get_available_tiny_plugins_options_have_required_fields(): void {
        $plugins = plugininfo::get_available_tiny_plugins();

        foreach ($plugins as $pluginname => $plugin) {
            $this->assertArrayHasKey('options', $plugin);
            $this->assertIsArray($plugin['options']);

            foreach ($plugin['options'] as $option) {
                $this->assertArrayHasKey('name', $option, "Option in $pluginname missing 'name'");
                $this->assertArrayHasKey('type', $option, "Option in $pluginname missing 'type'");
                $this->assertArrayHasKey('config', $option, "Option in $pluginname missing 'config'");
                $this->assertArrayHasKey(
                    'defaultsetting',
                    $option['config'],
                    "Option in $pluginname missing 'config.defaultsetting'",
                );
                $this->assertContains(
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
        $this->assertSame([], plugininfo::get_available_buttons());
    }

    /**
     * Test that get_available_menuitems returns an empty array.
     */
    public function test_get_available_menuitems_returns_empty(): void {
        $this->assertSame([], plugininfo::get_available_menuitems());
    }

    /**
     * Test get_tiny_plugin_area_config_key format.
     */
    public function test_get_tiny_plugin_area_config_key_format(): void {
        $this->assertSame('enable_forecolor_toolbar', plugininfo::get_tiny_plugin_area_config_key('forecolor', 'toolbar'));
        $this->assertSame('enable_forecolor_menubar', plugininfo::get_tiny_plugin_area_config_key('forecolor', 'menubar'));
        $this->assertSame('enable_backcolor_toolbar', plugininfo::get_tiny_plugin_area_config_key('backcolor', 'toolbar'));
        $this->assertSame('enable_fontsizeinput_toolbar', plugininfo::get_tiny_plugin_area_config_key('fontsizeinput', 'toolbar'));
    }

    /**
     * Test get_tiny_option_config_key format.
     */
    public function test_get_tiny_option_config_key_format(): void {
        $this->assertSame('tiny_option_color_map_foreground', plugininfo::get_tiny_option_config_key('color_map_foreground'));
        $this->assertSame('tiny_option_color_map_background', plugininfo::get_tiny_option_config_key('color_map_background'));
        $this->assertSame('tiny_option_font_size_formats', plugininfo::get_tiny_option_config_key('font_size_formats'));
        $this->assertSame(
            'tiny_option_font_size_input_default_unit',
            plugininfo::get_tiny_option_config_key('font_size_input_default_unit'),
        );
        $this->assertSame('tiny_option_font_family_formats', plugininfo::get_tiny_option_config_key('font_family_formats'));
    }

    /**
     * Test get_plugin_configuration_for_context with no config set returns empty defaults.
     */
    public function test_get_plugin_configuration_no_config(): void {
        $context = \context_system::instance();
        $result = plugininfo::get_plugin_configuration_for_context($context, [], []);

        $this->assertArrayHasKey('enabledareas', $result);
        $this->assertArrayHasKey('tinyoptions', $result);
        $this->assertSame(['_' => []], $result['enabledareas']);
        $this->assertSame(['_' => []], $result['tinyoptions']);
    }

    /**
     * Test that enabling a control in toolbar populates enabledareas correctly.
     */
    public function test_get_plugin_configuration_enables_toolbar_area(): void {
        set_config('enable_forecolor_toolbar', '1', 'tiny_richtext');

        $context = \context_system::instance();
        $result = plugininfo::get_plugin_configuration_for_context($context, [], []);

        $this->assertArrayHasKey('toolbar', $result['enabledareas']);
        $this->assertContains('forecolor', $result['enabledareas']['toolbar']);
        // Menubar should not be enabled for forecolor.
        $this->assertArrayNotHasKey('menubar', $result['enabledareas']);
    }

    /**
     * Test that enabling a control in menubar populates enabledareas correctly.
     */
    public function test_get_plugin_configuration_enables_menubar_area(): void {
        set_config('enable_fontfamily_menubar', '1', 'tiny_richtext');

        $context = \context_system::instance();
        $result = plugininfo::get_plugin_configuration_for_context($context, [], []);

        $this->assertArrayHasKey('menubar', $result['enabledareas']);
        $this->assertContains('fontfamily', $result['enabledareas']['menubar']);
    }

    /**
     * Test that enabling a control in both toolbar and menubar works.
     */
    public function test_get_plugin_configuration_enables_both_areas(): void {
        set_config('enable_backcolor_toolbar', '1', 'tiny_richtext');
        set_config('enable_backcolor_menubar', '1', 'tiny_richtext');

        $context = \context_system::instance();
        $result = plugininfo::get_plugin_configuration_for_context($context, [], []);

        $this->assertContains('backcolor', $result['enabledareas']['toolbar']);
        $this->assertContains('backcolor', $result['enabledareas']['menubar']);
    }

    /**
     * Test that an unset option config key is not present in tinyoptions.
     */
    public function test_get_plugin_configuration_option_not_set(): void {
        $context = \context_system::instance();
        $result = plugininfo::get_plugin_configuration_for_context($context, [], []);

        $this->assertArrayNotHasKey('color_map_foreground', $result['tinyoptions']);
        $this->assertArrayNotHasKey('font_size_formats', $result['tinyoptions']);
        $this->assertArrayNotHasKey('font_family_formats', $result['tinyoptions']);
    }

    /**
     * Test that the colour map transform produces the expected flat array.
     */
    public function test_get_plugin_configuration_colour_transform(): void {
        set_config('tiny_option_color_map_foreground', "#ff0000 Red\n#00ff00 Green", 'tiny_richtext');

        $context = \context_system::instance();
        $result = plugininfo::get_plugin_configuration_for_context($context, [], []);

        $this->assertArrayHasKey('color_map_foreground', $result['tinyoptions']);
        $this->assertSame(['#ff0000', 'Red', '#00ff00', 'Green'], $result['tinyoptions']['color_map_foreground']);
    }

    /**
     * Test that the font size formats transform produces a space-separated string.
     */
    public function test_get_plugin_configuration_fontsize_transform(): void {
        set_config('tiny_option_font_size_formats', "8pt\n10pt", 'tiny_richtext');

        $context = \context_system::instance();
        $result = plugininfo::get_plugin_configuration_for_context($context, [], []);

        $this->assertArrayHasKey('font_size_formats', $result['tinyoptions']);
        $this->assertSame('8pt 10pt', $result['tinyoptions']['font_size_formats']);
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

        $this->assertArrayHasKey('font_family_formats', $result['tinyoptions']);
        $this->assertSame(
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

        $this->assertArrayHasKey('font_size_input_default_unit', $result['tinyoptions']);
        $this->assertSame('px', $result['tinyoptions']['font_size_input_default_unit']);
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

        $this->assertContains('forecolor', $result['enabledareas']['toolbar']);
        $this->assertContains('backcolor', $result['enabledareas']['toolbar']);
        $this->assertContains('fontsize', $result['enabledareas']['toolbar']);
        $this->assertContains('fontsizeinput', $result['enabledareas']['toolbar']);
        $this->assertContains('fontfamily', $result['enabledareas']['toolbar']);
    }

    /**
     * Test that an empty config value is treated as not set.
     */
    public function test_get_plugin_configuration_empty_string_option_ignored(): void {
        set_config('tiny_option_color_map_foreground', '', 'tiny_richtext');

        $context = \context_system::instance();
        $result = plugininfo::get_plugin_configuration_for_context($context, [], []);

        $this->assertArrayNotHasKey('color_map_foreground', $result['tinyoptions']);
    }
}
