# Rich Text

Re-enables rich text editing capabilities that Moodle's TinyMCE editor does not enable by default.

Adds three TinyMCE core formatting controls:

- `forecolor` - Text color
- `backcolor` - Background color
- `fontsizeinput` - Font size

That's literally it, nothing fancy.

## Pages

| Page | Description |
|------|-------------|
| [Installation](installation.md) | Requirements and setup |
| [Usage](usage.md) | Where the controls appear, pitfalls |
| [Development](development.md) | JS build, CI |

## Quick Start

1. Copy `lib/editor/tiny/plugins/richtext/` to your Moodle `lib/editor/tiny/plugins/` directory.
2. Visit Site admin > Notifications to install.
3. Open any TinyMCE editor. The Text color, Background color, and Font size controls appear in the formatting toolbar and the Format menu.

## Caution

You shouldn't really be using this plugin, unless you really want to. See [Usage](usage.md#pitfalls) for the accessibility and theming risks.
