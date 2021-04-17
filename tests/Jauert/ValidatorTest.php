<?php

declare(strict_types=1);

namespace Jauert;

class ValidatorTest extends \PHPUnit\Framework\TestCase
{
    public function testValidation()
    {
        $validator = new Validator();
        $this->assertEmpty($validator->validate(['foo' => 'bar']));
    }

    public function testIsRequiredField()
    {
        $validator = new Validator();
        $validator->requiredField('test');

        $this->assertEquals(['test' => ['Field is required.']], $validator->validate(['foo' => 'bar']));
    }

    public function testIsRequiredAndRegExField()
    {
        $validator = new Validator();
        $validator
            ->requiredField('test')
            ->regex('test', '/^.*(?=.*\d)(?=.*[a-zA-ZäöüÄÖÜß]).*$/');

        $this->assertEquals(['test' => ['Field is invalid.']], $validator->validate(['test' => 'bar']));
    }

    public function testRegexIsNotScalar()
    {
        $validator = new Validator();
        $validator
            ->requiredField('test')
            ->regex('test', '/^.*(?=.*\d)(?=.*[a-zA-ZäöüÄÖÜß]).*$/', 'invalid');

        $this->assertEquals(['test' => ['invalid']], $validator->validate(['test' => new \stdClass()]));
    }

    public function testNoBlank()
    {
        $validator = new Validator();
        $validator->notBlank('test', 'custom message');

        $this->assertEquals(
            ['test' => ['custom message']],
            $validator->validate(['test' => '     ']),
            'expect that a string with only blanks will fail.'
        );
    }

    public function testNotBlankPositive()
    {
        $validator = new Validator();
        $validator->notBlank('test');

        $this->assertEquals([], $validator->validate(['test' => ' x    ']));
    }

    public function testEmail()
    {
        $validator = new Validator();
        $validator->email('test');

        $this->assertEquals([], $validator->validate(['test' => 'xx@xx.de']));
    }

    public function testNotEmail()
    {
        $validator = new Validator();
        $validator->email('test');

        $this->assertEquals([], $validator->validate(['test' => 'xx@xx.de']));
        $this->assertEquals(['test' => ['Field is invalid.']], $validator->validate(['test' => '@xx']));
        $this->assertNotEmpty($validator->validate(['test' => 'xx@xx.e']));
        $this->assertNotEmpty($validator->validate(['test' => '.@xx.de']));
    }

    public function testCustomMessage()
    {
        $validator = new Validator();
        $validator->email('test', 'This is not a valid email');

        $this->assertEquals(['test' => ['This is not a valid email']], $validator->validate(['test' => '@xx.de']));
    }

    public function testAlphaNumeric()
    {
        $validator = new Validator();
        $validator->alphaNumeric('test');

        $this->assertEquals([], $validator->validate(['test' => '110abc']));
    }

    public function testAlphaNumericFailsOnWhitespace()
    {
        $validator = new Validator();
        $validator->alphaNumeric('test', 'Numbers and letters only');

        $this->assertEquals(['test' => ['Numbers and letters only']], $validator->validate(['test' => '11 0abc']));
    }

    public function testAlphaNumericFailsOnEmpty()
    {
        $validator = new Validator();
        $validator->alphaNumeric('test', 'Numbers and letters only');

        $this->assertEquals(['test' => ['Numbers and letters only']], $validator->validate(['test' => '']));
    }

    public function testNotAlphaNumeric()
    {
        $validator = new Validator();
        $validator->notAlphaNumeric('test', 'Numbers and letters only');

        $this->assertEquals(['test' => ['Numbers and letters only']], $validator->validate(['test' => '123']));
        $this->assertEquals(['test' => ['Numbers and letters only']], $validator->validate(['test' => 'abcäüöß']));
        $this->assertEquals([], $validator->validate(['test' => true]));
        $this->assertEmpty($validator->validate(['test' => '+']));
    }

    public function testChainValidation()
    {
        $validator = new Validator();
        $validator
            ->email('mail', 'This is not a valid mail address.')
            ->notBlank('username', 'The username is not allowed to have empty spaces.')
            ->alphaNumeric('username', 'The username must be alphanumeric.')
            ->requiredField('mail', 'Please provide a mail address.');

        $this->assertEquals([
            'mail' => [
                'Please provide a mail address.'
            ],
            'username' => [
                'The username must be alphanumeric.'
            ]
        ], $validator->validate([
            'username' => 'Max Mustermann'
        ]));
    }

    public function testAsciiAlphaNumeric()
    {
        $validator = new Validator();
        $validator->asciiAlphaNumeric('test', 'Must be ascii');
        $this->assertEquals([], $validator->validate(['test' => 'abc123']));
        $this->assertEquals(['test' => ['Must be ascii']], $validator->validate(['test' => 'abcä']));
        $this->assertEquals(['test' => ['Must be ascii']], $validator->validate(['test' => true]));
    }

    public function testNotAsciiAlphaNumeric()
    {
        $validator = new Validator();
        $validator->notAsciiAlphaNumeric('test', 'Must not be ascii');
        $this->assertEquals([], $validator->validate(['test' => 'abcäü']));
        $this->assertEquals([], $validator->validate(['test' => true]));
        $this->assertEquals(['test' => ['Must not be ascii']], $validator->validate(['test' => 'abc']));
    }

    public function testLengthBetween()
    {
        $validator = new Validator();
        $validator->lengthBetween('test', 1, 4, 'Length must be between 1 and 4 characters.');
        $this->assertEmpty($validator->validate(['test' => 'test']));

        $this->assertEquals(
            ['test' => ['Length must be between 1 and 4 characters.']],
            $validator->validate(['test' => ''])
        );
        $this->assertEquals(
            ['test' => ['Length must be between 1 and 4 characters.']],
            $validator->validate(['test' => 'testA'])
        );
        $this->assertEquals(
            ['test' => ['Length must be between 1 and 4 characters.']],
            $validator->validate(['test' => 'testA'])
        );
        $this->assertEquals(
            ['test' => ['Length must be between 1 and 4 characters.']],
            $validator->validate(['test' => null])
        );
    }

    public function testEqualTo()
    {
        $validator = new Validator();
        $validator->equalTo('test', true, 'Is not true!');

        $this->assertEquals(
            ['test' => ['Is not true!']],
            $validator->validate(['test' => 1])
        );
    }

    public function testMinLength()
    {
        $validator = new Validator();
        $errorMessage = 'Minimum length is 3';
        $validator->minLength('test', 3, $errorMessage);

        $this->assertEquals(['test' => [$errorMessage]], $validator->validate(['test' => 12]));
        $this->assertEmpty($validator->validate(['test' => 123]));
        $this->assertEmpty($validator->validate(['test' => 1234]));
        $this->assertEquals(['test' => [$errorMessage]], $validator->validate(['test' => 'ab']));
        $this->assertEquals(['test' => [$errorMessage]], $validator->validate(['test' => null]));
        $this->assertEmpty($validator->validate(['test' => 'abcde']));
    }

    public function testMaxLength()
    {
        $validator = new Validator();
        $errorMessage = 'Maximum length is 3';
        $validator->maxLength('test', 3, $errorMessage);

        $this->assertEquals(['test' => [$errorMessage]], $validator->validate(['test' => 1234]));
        $this->assertEmpty($validator->validate(['test' => 123]));
        $this->assertEmpty($validator->validate(['test' => 'abc']));
        $this->assertEquals(['test' => [$errorMessage]], $validator->validate(['test' => 'abcd']));
        $this->assertEquals(['test' => [$errorMessage]], $validator->validate(['test' => null]));
    }

    public function testIsNumeric()
    {
        $validator = new Validator();
        $validator->numeric('test', 'must be numeric');
        $this->assertEquals(['test' => ['must be numeric']], $validator->validate(['test' => 'abc']));
        $this->assertEmpty($validator->validate(['test' => 123]));
        $this->assertEmpty($validator->validate(['test' => '123']));
    }
}
