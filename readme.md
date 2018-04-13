# Open On Make

A package that makes it easy to have the `artisan make:` commands open the newly created file in your editor of choice. Developed for Laravel 5.6. Some of the paths may be incorrect for other versions.

## Installation

`composer require ahuggins/open-on-make`

This package defaults to sublime using the `subl` command, if you have aliased it to something else OR wish to use a different editor, you will need to add the following to your .env file.

```
OPEN_ON_MAKE_EDITOR=nameOfCliCommandForEditor
OPEN_ON_MAKE_FLAGS='-a' // Flags you need passed to the above Command
```

## License

MIT




