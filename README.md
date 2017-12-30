![Logo](https://cdn.rawgit.com/ubermanu/flamingo/master/icons/flamingo.png)

**Flamingo** is a task runner oriented on data processing.<br>
Like other task runners, it runs its own configuration file, `flamingo.yml`.<br>
For more information, check out the [wiki](https://github.com/ubermanu/flamingo/wiki).

Usage
-----

Create a configuration file `flamingo.yml`.

> This configuration converts a \*.csv file into a \*.json one.

    Default():
        - Source: file.csv
        - Destination: result.json

Then run flamingo in the same folder.

    $ flamingo

Build
-----

To build `flamingo.phar` you will need to clone this project and [Box2](https://github.com/box-project/box2).

> You can find some information about how to install it on their github page.

Then you can go in project folder and run.

    $ box build -v
