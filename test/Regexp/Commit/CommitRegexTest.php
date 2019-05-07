<?php

declare(strict_types = 1);

namespace Brainsum\DrupalDevTools\Test\Regexp\Commit;

use GrumPHP\Util\Regex;
use PHPUnit\Framework\TestCase;
use RuntimeException;
use function preg_match;

/**
 * Test for commit message regular expressions.
 *
 * @package Brainsum\DrupalDevToolsTest\Regexp\Commit
 */
final class CommitRegexTest extends TestCase {

  private const REGEX = '/^([A-Z]+-[\d]+ )+\| [A-Za-z\d\s\.]+([^.])+\.{1}$/s';

  /**
   * Returns valid commit messages.
   *
   * @return array
   *   Array (of arrays) of valid messages.
   */
  public function validCommitMessageProvider(): array {
    return [
      ['EL-717 | Update Drupal core 8.6.3 to 8.6.5 in Composer lockfile.'],
      ['CAT-123 | My description.'],
      ['CAT-112 DOG-323 | 22 items added.'],
      ['QAS-184 | Update commit message pattern.'],
      ['QAS-18 QAS-4 | Update commit message pattern.'],
      ['QAS-18 CAT-4 EL-11 | Update! Commit message pattern.'],
    ];
  }

  /**
   * Test valid commit messages.
   *
   * @param string $message
   *   The message.
   *
   * @dataProvider validCommitMessageProvider
   */
  public function testValidCommitRegex(string $message): void {
    $this->assertTrue($this->regexMatcher($message), "'$message' does not match the regex, when it should.");
  }

  /**
   * Returns invalid commit messages.
   *
   * @return array
   *   Array (of arrays) of invalid messages.
   */
  public function invalidCommitMessageProvider(): array {
    return [
      ['cAt-001 | My description.'],
      ['CAT-asd | Apple.'],
      ['CAT-111|description'],
      ['CAT-112 |description'],
      ['CAT-113| description'],
    ];
  }

  /**
   * Test invalid commit messages.
   *
   * @param string $message
   *   The message.
   *
   * @dataProvider invalidCommitMessageProvider
   */
  public function testInvalidCommitRegex($message): void {
    $this->assertNotTrue($this->regexMatcher($message), "'$message' matches the regex, when it should not.");
  }

  /**
   * Match a message with the pattern the same way GrumPHP does.
   *
   * @param string $message
   *   The message.
   *
   * @return bool
   *   TRUE, if it's a match, FALSE if it's not.
   *
   * @throws \RuntimeException
   *   When there's a preg_match error.
   */
  protected function regexMatcher($message): bool {
    $regex = new Regex(static::REGEX);
    // @todo: Read this from the grumphp.yml?
    $regex->addPatternModifier('m');

    $result = preg_match((string) $regex, $message);
    if ($result === FALSE) {
      throw new RuntimeException("Error while matching '$message' with pattern '$regex");
    }

    return (bool) $result;
  }

}
