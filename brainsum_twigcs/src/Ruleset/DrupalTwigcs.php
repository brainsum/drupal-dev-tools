<?php

namespace Brainsum\Twigcs\Ruleset;

use FriendsOfTwig\Twigcs\Rule;
use FriendsOfTwig\Twigcs\Ruleset\Official;
use FriendsOfTwig\Twigcs\Validator\Violation;
use Brainsum\Twigcs\Rule\NoRawFilterRule;
use Brainsum\Twigcs\Rule\NoQuotationMarkAttributeRule;

/**
 * Drupal twigcs ruleset - based on the official ruleset.
 *
 * Added rules:
 *  - NoRawFilterRule
 *  - NoQuotationMarkAttributeRule
 */
class DrupalTwigcs extends Official
{

  public function getRules()
    {
        $rules = parent::getRules();

        return array_merge($rules, [
          new Rule\ForbiddenFunctions(Violation::SEVERITY_ERROR, ['dump', 'dpm', 'kint', 'print_r', 'var_dump', 'var_export', 'vardumper']),
          new NoRawFilterRule(Violation::SEVERITY_ERROR),
          new NoQuotationMarkAttributeRule(Violation::SEVERITY_ERROR),
        ]);
    }

}
