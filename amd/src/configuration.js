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
 * Tiny tiny_richtext for Moodle.
 *
 * @module      tiny_richtext/configuration
 * @copyright   2026 Felix Yeung
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

import {addMenubarItem, addToolbarButtons} from "editor_tiny/utils";
import {
    getInitialPluginConfiguration,
    getPluginOptionName,
} from "editor_tiny/options";
import {pluginName} from "./common";

const getToolbarConfiguration = (instanceConfig, items) => {
    let toolbar = instanceConfig.toolbar;
    toolbar = addToolbarButtons(toolbar, "formatting", [...items]);

    return toolbar;
};

const getMenuConfiguration = (instanceConfig, items) => {
    let menu = instanceConfig.menu;
    menu = addMenubarItem(menu, "format", [...items].join(" "), "codeformat");

    return menu;
};

export const configure = (instanceConfig, options) => {
    const pluginOptions = getInitialPluginConfiguration(options);
    const enabledAreas =
        pluginOptions[getPluginOptionName(pluginName, "enabledareas")];
    const enabledToolbars = enabledAreas.toolbar ?? [];
    const enabledMenubars = enabledAreas.menubar ?? [];

    return {
        toolbar: getToolbarConfiguration(instanceConfig, enabledToolbars),
        menu: getMenuConfiguration(instanceConfig, enabledMenubars),
    };
};
