# Rich Text

Re-enables rich text editing capabilities that Moodle's TinyMCE editor does not enable by default.

Adds TinyMCE core formatting controls:

- `forecolor` - Text color
- `backcolor` - Background color
- `fontsize` - Font size (predefined)
- `fontsizeinput` - Font size (input)
- `fontfamily` - Font family

Each control can be enabled in the toolbar and/or the Format menu on the plugin's settings page.

## Pages

| Page | Description |
|------|-------------|
| [Installation](installation.md) | Requirements and setup |
| [Usage](usage.md) | Controls, settings, pitfalls |
| [Development](development.md) | JS build, CI |

## Quick Start

1. Download the release zip and extract it to your Moodle `lib/editor/tiny/plugins/` directory. The folder must be named `richtext`.
2. Visit Site admin > Notifications to install.
3. Enable the controls on the plugin's settings page (Site admin > Plugins > Text editors > TinyMCE > Rich Text).
4. Open any TinyMCE editor. The enabled controls appear in the formatting toolbar and/or the Format menu.

## Caution

You shouldn't really be using this plugin, unless you really want to. See [Usage](usage.md#pitfalls) for the accessibility and theming risks.
