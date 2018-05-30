# Open On Make

A package that makes it easy to have the `artisan make:` commands open the newly created file in your editor of choice.

## Installation

`composer require --dev ahuggins/open-on-make`

This package defaults to sublime using the `subl` command, if you have aliased it to something else OR wish to use a different editor, you will need to add the following to your .env file.

#### Global (sort of)
> Note: You can now set a php.ini value...`open_on_make_editor="code"` this means that you will not have to set an ENV value in every project. Essentially means you can set your editor one time, in your local dev environment and then simply add this package to your project, and be good.
>
> It still means you can per project set an env, if for some reason you use certain editors for certain projects.

#### Per Project
```
OPEN_ON_MAKE_EDITOR=nameOfCliCommandForEditor
OPEN_ON_MAKE_FLAGS='-a' // Flags you need passed to the above Command
```

> Most people will probably only need to add `OPEN_ON_MAKE_EDITOR` to their .env file.

- publish the package config with 

  `php artisan vendor:publish --tag=open-on-make`

## Example Editor values

Sublime - `OPEN_ON_MAKE_EDITOR=subl`

PHPStorm - `OPEN_ON_MAKE_EDITOR=pstorm`

Atom - `OPEN_ON_MAKE_EDITOR=atom` Provided you have shell commands installed: https://user-images.githubusercontent.com/1791228/38758555-814eb602-3f3f-11e8-8071-3c9690bb0374.png

VS Code = `OPEN_ON_MAKE_EDITOR=code` Provided you have the `code` shell command installed: https://code.visualstudio.com/docs/setup/mac

## License

MIT




