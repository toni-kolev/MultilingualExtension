# Behat MutlilingualExtension

### Write tests once, test on multiple languages
 
Behat extension to help you do less work when you have multilanguage environment.

MultilingualExtension is a Behat extension designed to ease the work with multilingual sites. English language is used as a base and translations for each string are stored in a user defined file.
The main idea is to write your tests in English language and then run them against sites with different language than English. The website language is defined in YML profile.

Note that it is in very early version so some features may not work properly.

[![Latest Stable Version](https://poser.pugx.org/kolev/multilingual-extension/v/stable)](https://packagist.org/packages/behat/soap-extension)
[![License](https://poser.pugx.org/kolev/multilingual-extension/license)](https://packagist.org/packages/kolev/multilingual-extension)
[![Coverage Status](https://scrutinizer-ci.com/g/byKolev/MultilingualExtension/badges/build.png?b=master)](https://scrutinizer-ci.com/g/byKolev/MultilingualExtension/build-status/master)
[![Quality Score](https://img.shields.io/scrutinizer/g/byKolev/MultilingualExtension.svg?style=flat)](https://scrutinizer-ci.com/g/byKolev/MultilingualExtension)
[![Build Status](https://travis-ci.org/byKolev/MultilingualExtension.svg?branch=master)](https://travis-ci.org/byKolev/MultilingualExtension)
[![Total Downloads](https://poser.pugx.org/kolev/multilingual-extension/downloads)](https://packagist.org/packages/behat/soap-extension)
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
      language: de
      translations: translations.yml
```

The `language` variable is used to define the website's default language.
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

## Author

- [Toni Kolev](https://github.com/byKolev)

## Contributors
- [Alexei Gorobets](https://github.com/asgorobets)
