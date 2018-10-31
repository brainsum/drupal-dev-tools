# Brainsum dev tools for Drupal 8

Package / Composer plugin for standardizing development tools.

## GrumPHP
### Commit message check

Commit messages have to conform to the following regexp:
> `/([A-Z]+-[\d]+ )+\| [A-Z\d]([^.])+\./s`

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
