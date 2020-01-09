# Open On Make

A package that makes it easy to have the `artisan make:` commands open the newly created file in your editor of choice.

## Installation

`composer require --dev ahuggins/open-on-make`

This package defaults to VS Code using the `code` command, this can be changed by using the Artisan command:

`php artisan open:install`

It will ask you which editor you want, enter the corresponding number and hit enter. It will update your env file with the CLI command.

> You can still set this manually, this was intended to streamline the process, make it a little faster and one less thing to look up.

> Feel free to suggest or open a PR with additional editors if anything is missing.

## Set env Editor manually

```
OPEN_ON_MAKE_EDITOR=nameOfCliCommandForEditor
OPEN_ON_MAKE_FLAGS='-a' // Flags you need passed to the above Command
```

> Most people will probably only need to add `OPEN_ON_MAKE_EDITOR` to their .env file.

#### Publish the config

- publish the package config with

  `php artisan vendor:publish --tag=open-on-make`

#### Disable the package

> Some team members may want to disable this feature.

You can explicitly disable this package by setting the `OPEN_ON_MAKE_ENABLED` environment variable:

```
OPEN_ON_MAKE_ENABLED=false
```

## Example Editor values

Sublime - `OPEN_ON_MAKE_EDITOR=subl`

PHPStorm - `OPEN_ON_MAKE_EDITOR=pstorm` Setup Instructions: https://www.jetbrains.com/help/phpstorm/opening-files-from-command-line.html

Atom - `OPEN_ON_MAKE_EDITOR=atom` Provided you have shell commands installed: https://user-images.githubusercontent.com/1791228/38758555-814eb602-3f3f-11e8-8071-3c9690bb0374.png

VS Code = `OPEN_ON_MAKE_EDITOR=code` Provided you have the `code` shell command installed: https://code.visualstudio.com/docs/setup/mac

## License

Licensed under the [MIT](license) license
