# Brainsum dev tools for Drupal 10

[![Build Status](https://travis-ci.org/brainsum/drupal-dev-tools.svg?branch=master)](https://travis-ci.org/brainsum/drupal-dev-tools)

## About

Package / Composer plugin for standardizing development tools.

## Setup
### Preparation

If you already have a ```grumphp.yml``` and/or ```phpcs.xml``` file in your project, you should rename them. E.g. ```mv grumphp.yml grumphp.yml.backup```
If you want to add new tasks and rules on a case-by-case basis, check the files in the ```distfiles``` folder of this package.
Note: This package is not going to overwrite existing ones, but it might not work with them either.

### Installation

In your project, simply use

```composer require --dev brainsum/drupal-dev-tools:^4.0```

### Settings
#### Extend
In the ```grumphp.yml``` file in your project, add the following:
```
imports:
    - { resource: vendor/brainsum/drupal-dev-tools/convention/grumphp.yml }
```

This will signal grumphp to import everything from this package.

You can customize the rules when needed, too:
```
parameters:
    convention.git_commit_message_whitelist: ['/^([A-Z]+-[\d]+ )+\| [A-Za-z\d\s\.]+([^.])+\.{1}$/s']
```

For more information, see:
- [GrumPHP Conventions](https://github.com/phpro/grumphp/blob/master/doc/conventions.md)


#### Override
The files ```grumphp.yml``` and ```phpcs.xml``` should now be created in your project root.
They should be good to go, but you should take a look at them and customize them for the current project as necessary.
E.g, phpcs.xml descriptions, namings, checked folders, etc.; grumphp.yml php version, etc.

If they, for some reason, don't get created, you can copy their contents from the files from ```vendor/brainsum/drupal-dev-tools/distfiles``` as needed.

If you just want to use some settings from ```grumphp.yml```, not the whole thing, see ```convention/grumphp.yml```, 
as ```distfiles/grumphp.yml``` is only an import file and doesn't contain any other directives.

## GrumPHP
### Settings

For the pre-defined settings see ```distfiles/grumphp.yml``` and ```convention/grumphp.yml```.

Note, we are also trying to create a ```phpcs.xml``` file in the project root. The source for that file is also in the ```distfiles``` folder.
