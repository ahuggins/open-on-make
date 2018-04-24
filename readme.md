# Open On Make

A package that makes it easy to have the `artisan make:` commands open the newly created file in your editor of choice. Developed for Laravel 5.6. Some of the paths may be incorrect for other versions.

## Installation

`composer require ahuggins/open-on-make`

This package defaults to sublime using the `subl` command, if you have aliased it to something else OR wish to use a different editor, you will need to add the following to your .env file.

```
OPEN_ON_MAKE_EDITOR=nameOfCliCommandForEditor
OPEN_ON_MAKE_FLAGS='-a' // Flags you need passed to the above Command
```

> Most people will probably only need to add `OPEN_ON_MAKE_EDITOR` to their .env file.

## Example Editor values

Sublime - `OPEN_ON_MAKE_EDITOR=subl`
Sublime - `OPEN_ON_MAKE_EDITOR=sublime`

PHPStorm - `OPEN_ON_MAKE_EDITOR=pstorm`

Atom - `OPEN_ON_MAKE_EDITOR=atom` Provided you have shell commands installed: https://user-images.githubusercontent.com/1791228/38758555-814eb602-3f3f-11e8-8071-3c9690bb0374.png

VS Code = `OPEN_ON_MAKE_EDITOR=code` Provided you have the `code` shell command installed: https://code.visualstudio.com/docs/setup/mac

## License

MIT




