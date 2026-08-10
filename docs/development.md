# Development

## JS Build

AMD modules in `amd/src/`. Build output in `amd/build/`.

```bash
# Install deps
bun install # or: npm install

# Build via Grunt
grunt amd --root=lib/editor/tiny/plugins/richtext
```

### Dependencies

| Package | Version | Use |
|---------|---------|-----|
| `tinymce` | ^8.2.2 | Types for editor integration |

## CI

GitHub Actions (`.github/workflows/moodle-ci.yaml`):

- PHP 8.3 & 8.4
- Moodle 405 & 502
- PostgreSQL 17
- PHP Lint, PHPMD, PHPCS, PHPDoc
- Validation, Savepoints, Mustache Lint
- Grunt, PHPUnit, Behat

## File Layout

```
amd/src/
  common.js         - Component name and constants
  configuration.js  - Toolbar and menu configuration
  plugin.js         - Plugin registration
  commands.js       - Command setup (empty)
  options.js        - Options registration (empty)
classes/
  plugininfo.php    - Tiny plugin API (buttons, menu items, configuration)
  privacy/provider.php - Null privacy provider (no personal data stored)
lang/en/
  tiny_richtext.php - Language strings
version.php         - Version and release metadata
settings.php        - Admin settings page (currently empty)
```
