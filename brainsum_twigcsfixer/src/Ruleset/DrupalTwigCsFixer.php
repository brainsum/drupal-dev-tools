<?php

namespace Brainsum\TwigCsFixer\Ruleset;

use TwigCsFixer\Ruleset\Ruleset;
use TwigCsFixer\Standard\TwigCsFixer;
use Brainsum\TwigCsFixer\Rule\NoRawFilterRule;
use Brainsum\TwigCsFixer\Rule\NoQuotationMarkAttributeRule;
use Brainsum\TwigCsFixer\Rule\ForbiddenFunctionRule;

/**
 * Drupal twig-cs-fixer ruleset - based on the TwigCsFixer standard.
 *
 * Added rules:
 *  - NoRawFilterRule
 *  - NoQuotationMarkAttributeRule
 *  - ForbiddenFunctionRule (dump, dpm, kint, print_r, var_dump, var_export, vardumper)
 */
class DrupalTwigCsFixer
{
    /**
     * @var \TwigCsFixer\Ruleset\Ruleset
     */
    private $ruleset;

    /**
     * DrupalTwigCsFixer constructor.
     */
    public function __construct()
    {
        // Create a new ruleset
        $this->ruleset = new Ruleset();

        // Add the default TwigCsFixer standard rules
        $this->ruleset->addStandard(new TwigCsFixer());

        // Add our custom rules
        $this->ruleset->addRule(new NoRawFilterRule());
        $this->ruleset->addRule(new NoQuotationMarkAttributeRule());
        $this->ruleset->addRule(new ForbiddenFunctionRule([
            'dump', 'dpm', 'kint', 'print_r', 'var_dump', 'var_export', 'vardumper'
        ]));
    }

    /**
     * Get the configured ruleset.
     *
     * @return \TwigCsFixer\Ruleset\Ruleset
     *   The configured ruleset.
     */
    public function getRuleset(): Ruleset
    {
        return $this->ruleset;
    }
}
