# Behat MutlilingualExtension

### Write tests once, test on multiple languages
 
Behat extension to help you do less work when you have multilanguage environment.

MultilingualExtension is a Behat extension designed to ease the work with multilingual sites. English language is used as a base and translations for each string are stored in a user defined file.
The main idea is to write your tests in English language and then run them against sites with different language than English. The website language is defined in YML profile.

Note that it is in very early version so some features may not work properly.

[![Latest Stable Version](https://poser.pugx.org/kolev/multilingual-extension/v/stable)](https://packagist.org/packages/kolev/multilingual-extension)
[![License](https://poser.pugx.org/kolev/multilingual-extension/license)](https://packagist.org/packages/kolev/multilingual-extension)
[![Coverage Status](https://scrutinizer-ci.com/g/byKolev/MultilingualExtension/badges/build.png?b=master)](https://scrutinizer-ci.com/g/byKolev/MultilingualExtension/build-status/master)
[![Quality Score](https://img.shields.io/scrutinizer/g/byKolev/MultilingualExtension.svg?style=flat)](https://scrutinizer-ci.com/g/byKolev/MultilingualExtension)
[![Build Status](https://travis-ci.org/toni-kolev/MultilingualExtension.svg?branch=master)](https://travis-ci.org/toni-kolev/MultilingualExtension)
[![Total Downloads](https://poser.pugx.org/kolev/multilingual-extension/downloads)](https://packagist.org/packages/kolev/multilingual-extension)
## Installation

- `curl -sS https://getcomposer.org/installer | php`
- `vim composer.json`

```
{
  "require": {
    "kolev/multilingual-extension": "dev-master"
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
german_profile:
  suites:
    default:
      contexts: {}
  extensions:
    Behat\MinkExtension:
      files_path: %paths.base%/files
    kolev\MultilingualExtension:
      default_language: de
      translations: translations.yml
```

The `default_language` variable is used to define the website's default language.
The `translations` variable is used to define the path to the translations file. The path is relative to the `files_path` variable. So in this case the `translations.yml` file should be placed in `/files` folder.

The `translations.yml` file structure is easy to read too.

```
"carrot":
  de: "karrote"
  fr: "carrote"
"cabbage":
  de: "kohl"
  fr: "chou"
```

The user can list as many words as he/she wants. Also many different languages for each word can be added.

It is important to use the same language prefix in `translations.yml` file and when configuring the profile. For example define site's language as `de` and add translations with `de`. 

Then it's time to write your test porperly in order to use the localized version of the string. For example:

```
Feature: Multilingual Extension example feature

  Scenario: Example of a Scenario for testing multilingual extension
    Given I go to "/"
    And I should see localized "carrot"
```

In this case if your run the test with `german_profile` it will open the homepage and look for `de` version of the word `carrot` which in our case is `karrote`.

## FAQ

#### What if I have more than one language installed?

Language detection based on URL is introduced with version `0.0.2`. The extension tries to detect site's language based on URL. For example if you go to page http://example.com/de/ it will look for German translation of the string, if no language code found it will use the `default_language`. It works for both clean and non-clean URLs.

#### What are the availabe languages supported?

List of all languages ISO codes can be found [here](docs/IsoLanguageCodes.json) 

## Author

- [Toni Kolev](https://github.com/byKolev)

## Contributors
- [Alexei Gorobets](https://github.com/asgorobets)
