# Behat MutlilingualExtension

MultilingualExtension is a Behat extension designed to ease the work with multilingual sites. English language is used as a base and translations for each string are stored in a user defined file.
The main idea is to write your tests in English language and then run them against sites with different language than English. The website language is defined in YML profile.

[![Build Status](https://travis-ci.org/byKolev/MultilingualExtension.svg?branch=master)](https://travis-ci.org/byKolev/MultilingualExtension)
[![Total Downloads](https://poser.pugx.org/kolev/multilingual-extension/downloads)](https://packagist.org/packages/behat/soap-extension)
## Installation

- `curl -sS https://getcomposer.org/installer | php`
- `vim composer.json`

```
{
  "require": {
    kolev/multilingual-extension": "dev-master"
  },
  "config": {
    "bin-dir": "bin"
  }
}
```

- `composer install`

## Usage

MultilingualExtension is easy to use. First you need to add the extension to your profile in `behat.yml` configuration file.

```
profile:
  suites:
    default:
      contexts: {}
  extensions:
    Behat\MinkExtension:
      files_path: %paths.base%/files
    kolev\MultilingualExtension:
      language: en
      translations: translations.yml
```

The `language` variable is used to define the website's default language.
The `translations` variable is used to define the path to the translations file. The path is relative to the `files_path` variable. So in this case the `translations.yml` file should be placed in `/files` folder.

## Author

- [Toni Kolev](https://github.com/byKolev)

## Contributors
- [Alexei Gorobets](https://github.com/asgorobets)