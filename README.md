# Behat MutlilingualExtension

MultilingualExtension is a Behat extension designed to ease the work with multilingual sites. English language is used as a base and translations for each string are stored in a user defined file.
The main idea is to write your tests in English language and then run them against sites with different language than English. The website language is defined in YML profile.

Note that it is in very early version so some features may not work properly.

[![Latest Stable Version](https://poser.pugx.org/kolev/multilingual-extension/v/stable)](https://packagist.org/packages/behat/soap-extension)
[![License](https://poser.pugx.org/kolev/multilingual-extension/license)](https://packagist.org/packages/kolev/multilingual-extension)
[![Coverage Status](https://scrutinizer-ci.com/g/byKolev/MultilingualExtension/badges/build.png?b=master)](https://scrutinizer-ci.com/g/byKolev/MultilingualExtension/build-status/master)
[![Quality Score](https://img.shields.io/scrutinizer/g/byKolev/MultilingualExtension.svg?style=flat)](https://scrutinizer-ci.com/g/asgorobets/SoapExtension)
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

The `translations.yml` file structure is easy to read too.

```
"word":
  de: "German translation"
  fr: "French translation"
"word2":
  de: "German translation"
  fr: "French translation"
```

The user can list as many words as he/she wants. Also many different languages for each word can be added.

It is important to use the same language prefix in `translations.yml` file and when configuring the profile. For example define site's language as `de` and add translations with `de`. 


## Author

- [Toni Kolev](https://github.com/byKolev)

## Contributors
- [Alexei Gorobets](https://github.com/asgorobets)