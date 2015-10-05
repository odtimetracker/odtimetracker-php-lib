# odTimeTracker PHP Library

[![Build Status](https://api.travis-ci.org/ondrejd/odtimetracker-php-lib.svg)](https://travis-ci.org/ondrejd/odtimetracker-php-lib)

Is used by [odtimetracker-php-cgi](https://github.com/odTimeTracker/odtimetracker-php-cgi) and [odtimetracker-php-gtk](https://github.com/odTimeTracker/odtimetracker-php-gtk).

## Usage

### Installation

Here is an example of `composer.json` file for project that is using __odtimetracker-php-lib__:

```json
{
    "name": "your_name/your_project",
    "description": "Your project description.",
    "repositories": [{
        "type": "vcs",
        "url": "https://github.com/odTimeTracker/odtimetracker-php-lib"
    }],
    "autoload": {
        "psr-0": {
            "YourProject\\": "src/"
        },
        "classmap": [
            "./"
        ]
    },
    "require": {
        "php": ">=5.3",
        "ondrejd/odtimetracker-php-lib": "*"
    }
}
```
