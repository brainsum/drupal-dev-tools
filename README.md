# Brainsum dev tools for Drupal 8

Package / Composer plugin for standardizing development tools.

## Setup
### Preparation

If you already have a ```grumphp.yml``` and/or ```phpcs.xml``` file in your project, you should rename them. E.g. ```mv grumphp.yml grumphp.yml.backup```
If you want to add new tasks and rules on a case-by-case basis, check the files in the ```distfiles``` folder of this package.
Note: This package is not going to overwrite existing ones, but it might not work with them either.

### Installation

In your project, simply use

```composer require --dev brainsum/drupal-dev-tools```

### Settings

The newly created ```grumphp.yml``` and ```phpcs.xml``` should now be created. They should be good to go, but you should take a look at them and customize them for the current project as necessary.
E.g, phpcs.xml descriptions, namings, checked folders, etc.; grumphp.yml php version, etc.

## GrumPHP
### Commit message check

Commit messages have to conform to the following regexp:
> `/^([A-Z]+-[\d]+ )+\| [A-Za-z\d\s\.]+([^.])+\.{1}$/s`

This should be equivalent to the following convention:

- One or more JIRA Task IDs separated with a space
- One pipe (`|`) surrounded on both sides with a single space
- The description of the task starting with an uppercase letter or number, ending with a single dot (`.`)

#### Examples

- Valid
    - CAT-123 | My description.
    - CAT-112 DOG-323 | 22 items added.
- Invalid
    - cAt-001 | My description.
    - CAT-asd | Apple.
    - CAT-111|description
    - CAT-112 |description
    - CAT-113| description

### Branch naming

Branch naming has to conform to the following regexp:
> `/([a-z]+-[A-Z]+-[\d]+)(-[a-zA-Z\d]+)*$/s`

This should be equivalent to the following convention:

- parent branch name
- JIRA task ID
- optional hyphenated description

#### Examples

- Valid
    - master-JIRA-000
    - master-JIRA-000-feature-update
- Invalid
    - JIRA-001
    - my-feature
    - master-JIRa-000
