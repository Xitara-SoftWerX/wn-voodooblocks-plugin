# VoodooBlocks - Block layout plugin for WinterCMS

> [!IMPORTANT]
> VoodooBlocks has been superseded by
> [VoodooGrid](https://github.com/Xitara-SoftWerX/wn-voodoogrid-plugin).
> This repository is archived and receives no further development.

## Requirements

- PHP 8.2 or newer
- Winter CMS 1.2 or newer

Adds a blocklist to fill whole pages with content blocks

## Components

- Xitara\VoodooBlocks\Components\Blocklist

## Description

Create block layouts like a charm with sliders, lightbox, buttonlists, static text, time control and an extension system to integrate own modules with extended functionality, if you want.
The releases only got base styles. Feel free to change the look and feel in your theme-css or use the development version to generate your own CSS and JavaScript with sass, bootstrap and/or tailwind, typescript, babel and so on.

## Releases

You can find prebuild zip packages in [https://github.com/xitara/wn-voodooblocks-plugin/releases](https://github.com/xitara/wn-voodooblocks-plugin/releases)

## Dependencies for development

- `yarn`
- `bash` to run `zip`, `fly`, `deploy` and `ftp`, testet on debian/buster
- `lftp` to upload with ftp by `yarn ftp`, testet on debian/buster

## Optional

- `phpdoc` to generate docs with `yarn docs`. See [https://docs.phpdoc.org/3.0/](https://docs.phpdoc.org/3.0/) for details

## Quick start

- clone the repo via `git clone https://github.com/xitara/wn-voodooblocks-plugin.git plugins/xitara/voodooblocks`
- `cd plugins/xitara/voodooblocks`
- run `yarn` to fetch all the dependencies, runs bash/setup.sh and build the package
- change settings in `bash/config.sh` if possible
- run `yarn dwatch` to start webpack in watch-mode to generate dev-versions of js and css
- start developing
- when you are done, run `yarn build` to get the production version of your app
- optional run `yarn zip` to generate a zip-package with all nesessary files. The files can be adjusted in `bash/config.sh`

## Commands

- `start` - start the dev server
- `watch` - start webpack --watch
- `dwatch` - start webpack --watch in development-mode
- `build` - create build in `build` folder
- `dbuild` - create development build in `build` folder
- `zip` - build project and pack relevant folder/files to a zip-file one level down
- `deploy` - build project and deploy to a folder name in package.json one level down with backup if folder exists
- `ftp` - uploads build project per FTP. Configs in ./bash/config.sh
- `docs` - generates docs with `phpdoc` if installed
- `analyze` - analyze your production bundle
- `lint-code` - run an ESLint check
- `lint-style` - run a Stylelint check
- `check-eslint-config` - check if ESLint config contains any rules that are unnecessary or conflict with Prettier
- `check-stylelint-config` - check if Stylelint config contains any rules that are unnecessary or conflict with Prettier
- `cleanup` - delete build-folder, node_modulesand other generated files/folders. files in src and static stay untouched

## Including

- [webpack 5](https://github.com/webpack/webpack)
- [tailwindcss](https://tailwindcss.com)
- [glightbox](https://github.com/biati-digital/glightbox)
- [tiny-slider](https://github.com/ganlanyuan/tiny-slider)
- [simplebar](https://github.com/Grsmto/simplebar)
- [mark.js](https://markjs.io/)
- [bootstrap](https://getbootstrap.com)
- babel
- brotli / gzip compression for assets
- eslint / stylelint
- husky pre push tests
- sass
- purgecss (for sass and tailwindcss)

## WinterCMS specific commands

- `wn-init-theme` - adds folders to create a complete [WinterCMS](https://wintercms.com) theme boilerplate
- `wn-kill-theme` - removes folders for WinterCMS theme including `theme.yaml` and `conifg` from static. Handle with care, it's not recoverable

## WinterCMS specific settings

- add the following lines to your `.htaccess` to access the compiled index.html inside your theme dev:
- it works with and without translation-prefix
```
##
## enable display index.html for development
##
RewriteRule ^themes/.*/index\.html - [L,NC]
RewriteRule ^.*/themes/(.*)/index\.html /themes/$1/index\.html [L,NC]
RewriteRule ^.*/themes/(.*)/(assets|resources)/(.*) /themes/$1/$2/$3 [L,NC]
```

## Update 0.8.1

- Update to webpack 5
- Update all dependencies
- Add a fetch method to utils.js

## Update 0.8.2

- Add cross-env
- Switch to tailwind JIT-compiler
- Update all dependencies
