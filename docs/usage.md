# Usage

The plugin has no buttons or UI of its own. It exposes six TinyMCE core formatting controls:

| Control | TinyMCE command | Description |
|---------|-----------------|-------------|
| Text color | `forecolor` | Foreground color of selected text |
| Background color | `backcolor` | Highlight / background color of selected text |
| Font size (predefined) | `fontsize` | Font size from a predefined list |
| Font size (input) | `fontsizeinput` | Font size entered freely |
| Font family | `fontfamily` | Font family of selected text |
| Custom characters | `charmap` | Insert special characters |

## Settings

On the plugin's settings page (Site admin > Plugins > Text editors > TinyMCE > Rich Text) each control has two toggles:

- Toolbar
- Menubar (Format menu)

All toggles are off by default. Controls only appear in the areas they are enabled for.

## Where The Controls Appear

The plugin's configuration handler adds the enabled controls to the `formatting` toolbar group and to the **Format** menu (after `codeformat`).

`fontsizeinput` is toolbar-only; it has no menu entry.

The `forecolor` and `backcolor` pickers use TinyMCE's native color pickers, not a custom palette.

## Pitfalls

You shouldn't really be using this plugin, unless you really want to.

Explained in this [discussion](https://moodle.org/mod/forum/discuss.php?d=460510#p1849378), accessibility and theming issues will arise if you use this plugin.

Also see this Jira issue: [MDL-76517](https://moodle.atlassian.net/browse/MDL-76517), which proposes adding these features to Moodle core reducing risks.
