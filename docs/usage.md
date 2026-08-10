# Usage

The plugin has no buttons or UI of its own. It only makes three TinyMCE core formatting controls available in the editor:

| Control | TinyMCE command | Description |
|---------|-----------------|-------------|
| Text color | `forecolor` | Foreground color of selected text |
| Background color | `backcolor` | Highlight / background color of selected text |
| Font size | `fontsizeinput` | Font size of selected text |
| Font family | `fontfamily` | Font family of selected text |

## Where The Controls Appear

On every TinyMCE editor instance the plugin's configuration handler:

- Adds `forecolor`, `backcolor`, `fontsizeinput`, `fontfamily` to the `formatting` toolbar group.
- Adds `forecolor backcolor fontsizeinput fontfamily` to the **Format** menu, after `codeformat`.

The `forecolor` and `backcolor` pickers use TinyMCE's native color pickers, not a custom palette.

## Pitfalls

You shouldn't really be using this plugin, unless you really want to.

Explained in this [discussion](https://moodle.org/mod/forum/discuss.php?d=460510#p1849378), accessibility and theming issues will arise if you use this plugin.

Also see this Jira issue: [MDL-76517](https://moodle.atlassian.net/browse/MDL-76517), which proposes adding these features to Moodle core reducing risks.
