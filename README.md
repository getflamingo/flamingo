![Logo](https://cdn.rawgit.com/ubermanu/flamingo/master/icons/flamingo.png)

**Flamingo** is a task runner oriented on data processing.<br>
Like other task runners, it runs its own configuration file, `flamingo.yml`.

Usage
-----

Create a configuration file `flamingo.yml`.
> This configuration talks about itself, just a basic import/export.

    Flamingo/Task/Default:
        - Source: file.csv
        - Destination: result.json

Then run flamingo in the same folder.

    $ flamingo

Build
-----

To build `flamingo.phar` you will need to clone this project and  [Box2](https://github.com/box-project/box2).
> You can find some information about how to install it on their github page.

Then you can go in project folder and run.

    $ box build -v
