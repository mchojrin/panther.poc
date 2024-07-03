# Panther Proof of Concept

This repository presents a Proof of Concept to illustrate the usage of the [symfony/panther](https://github.com/symfony/panther) library for testing purposes.

It checks a few expectations about an instance of [Mantis bug tracker](https://mantisbt.org/) deployed at a staging server.

## Requirements

* PHP 8.3+
* ChromeDriver and geckodriver

## Installation

1. Install the dependencies with composer: `composer install`

## Running

The entry point is the script `test.php` which expects two arguments:

* The url to be checked
* (Optional) The browser. At the moment only `chrome` and `firefox` are available

<u>Example</u>:

`php test.php https://my_mantis.com firefox`

The script will write the results to the standard output as they are produced.

## Docker

If you have [docker](https://www.docker.com/) available, you may want to use the `Dockerfile` to build an image and run the script.

Also, there are two bash scripts available to make running the project easier:

* `build.sh` to build the Docker image
* `run.sh` to run the test

If you want to use these scripts, change the url definition inside the `run.sh` to point to your own server
