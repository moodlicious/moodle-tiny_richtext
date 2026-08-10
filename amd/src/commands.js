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
 * Commands helper for the Moodle tiny_fontstyles plugin.
 *
 * @module      tiny_fontstyles/commands
 * @copyright   2026 Felix Yeung
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

import {getButtonImage} from "editor_tiny/utils";
import {component, buttons, menus, icons} from "./common";

/**
 * Handle the action for your plugin.
 * @param {TinyMCE.editor} editor The tinyMCE editor instance.
 */
const handleAction = (editor) => {
    // TO-DO Handle the action.
    window.console.log(editor);
};

/**
 * Get the setup function for the buttons.
 *
 * This is performed in an async function which ultimately returns the registration function as the
 * Tiny.AddOnManager.Add() function does not support async functions.
 *
 * @returns {(editor: import('tinymce').Editor) => void} The registration function to call within the Plugin.add function.
 */
export const getSetup = async () => {
    const [pluginIcon, textColorIcon, backgroundColorIcon] = await Promise.all([
        getButtonImage("icon", component),
        getButtonImage("textcolor", component),
        getButtonImage("backgroundcolor", component),
    ]);

    return (editor) => {
        // Register the Moodle SVG as an icon suitable for use as a TinyMCE toolbar button.
        editor.ui.registry.addIcon(icons.plugin, pluginIcon.html);
        editor.ui.registry.addIcon(icons.textcolor, textColorIcon.html);
        editor.ui.registry.addIcon(
            icons.backgroundcolor,
            backgroundColorIcon.html,
        );

        editor.ui.registry.addButton(buttons.textcolor, {
            icon: icons.textcolor,
            tooltip: "Text colour",
            onAction: () => handleAction(editor),
        });
        editor.ui.registry.addButton(buttons.backgroundcolor, {
            icon: icons.backgroundcolor,
            tooltip: "Background colour",
            onAction: () => handleAction(editor),
        });

        editor.ui.registry.addMenuItem(menus.textcolor, {
            icon: icons.textcolor,
            text: "Text Colour",
            onAction: () => handleAction(editor),
        });
        editor.ui.registry.addMenuItem(menus.backgroundcolor, {
            icon: icons.backgroundcolor,
            text: "Background Colour",
            onAction: () => handleAction(editor),
        });
    };
};
