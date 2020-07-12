# Refactor
I really want to refactor this into something more robust that is hopefully a little easier to grok how it works.

This would make maintaining a little easier, and be a good exercise.

I intend this to be the 1.0 release.

### Refactor Strategy

Looking at the code, it's rather procedural. Let's OOP it up. Backed by Tests...more better tests. And I think OOPing it, will make the testing ability better and easier.

To do that, I need a pretty good list of different commands that would be called.

1. `null` (if just `artisan`)
2. `make:model` for Model
3. `make:cast`
4. 

[X] Handle if `artisan` with no command is run
[X] First check should be if the command is a `make:` command, and if not, just bail.
    - CommandInfo->isOpenable() checks if it's a make: command
    - Also checks if it's not just `artisan` meaning no additional command provided

## The idea here is to break the code into modular parts.

[X] Move the code for if the Command is a `make:test` command to its own file.
    - CommandInfo ended up being where a lot of code should live. isMakeCommand lives here
[ ] `isSubClassOfGeneratorCommand` to its own file
[X] `Migration handler` to its own file
    - Ended up moving to the File class. Better, but might be temporary
[ ] Move `checkForFlags` code to its own file