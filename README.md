# Folio Showroom Theme

## Description

Folio Showroom Theme based on Bootstrap Framework and Underscores.

### Languages Available

- English
- Catalan
- Spanish (Supported by Author)

### Installation

1. Upload the 'folio-showroom' folder to the '/wp-content/themes/' directory
2. Activate the theme through the 'Appearance > Themes' menu in WordPress

## Developing With npm, Webpack (Laravel Mix) and SASS

### Requirements

- [Node.js](https://nodejs.org/)

### Installing Dependencies

- Make sure you have installed Node.js and NPM, on your computer globally.
- Then open your terminal and browse to the location of your theme copy
- Run: `$ npm install`

### Running

To work with and compile your Sass files, and generate development assets on the fly run:

```
$ npm run watch
```

Watch may not function in some virtual environments, alternative:

```
$ npm run watch-poll
```

To compile asssets for development run:

```
$ npm run development
```

To compile assets for production run:

```
$ npm run production
```

### i18n for editor.js

Tranlation json files for editor.js generated with WP-CLI.
To generate json translations files, from theme root folter run:

```
$ wp i18n make-json languages --no-purge
```

##### NOTES:

Set `--no-purge` to prevent purge the strings that were extracted from the original po source file.

Generated files are in format `$textdomain-$locale-$md5.json`, but with current mix setup is not working. WP-CLI generates hashes using the path of the source files where it found the strings, while WP is using the path of the dist files which is of course different. To solve this issue, rename json generated files to format `$textdomain-$locale-$handle.json`, with $handle used in wp_register_script, in this case `folio-showroom-editor` in order to WP can find correct translations.
See: https://github.com/wp-cli/i18n-command/issues/177

### Changelog

#### v1.0.0

- Initial release
