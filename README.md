# Rich Text

Re-enables rich text editing capabilities that were not enabled by Moodle by default. Enables:

- `forecolor`: Text color
- `backcolor`: Background color
- `fontsize`: Font size (predefined)
- `fontsizeinput`: Font size (input)
- `fontfamily`: Font family
- `charmap`: Custom characters

Each control can be enabled in the toolbar and/or the Format menu on the plugin's settings page.

## Pitfalls

You shouldn't really be using this plugin, unless you really want to.

Explained in this [discussion](https://moodle.org/mod/forum/discuss.php?d=460510#p1849378), accessibility and theming issues will arise if you use this plugin.

Also see this Jira issue: [MDL-76517](https://moodle.atlassian.net/browse/MDL-76517), which proposes adding these features to Moodle core reducing risks.
