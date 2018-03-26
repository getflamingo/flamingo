# Flamingo

![Logo](https://cdn.rawgit.com/ubermanu/flamingo/master/icons/flamingo.png)

**Flamingo** is a task runner oriented on data processing.<br>
For more information, check out the [wiki](https://github.com/ubermanu/flamingo/wiki).

### Usage

Create a Task using PHP.<br>
This example configuration converts a \*.csv file into a \*.json one.

    class ExampleTask extends \Flamingo\Task
    {
        function __invoke()
        {
            $data = $this->read('file.csv');
            $this->write($data, 'file.json');
        }
    }

Then run flamingo in the same folder:

    $ flamingo ExampleTask

### Build

To build the **flamingo.phar** file, you will need to clone this project and [Box](https://github.com/humbug/box).

> You can find some information about how to install it on the github page.

Then you can go in your project folder and run:

    $ box build
