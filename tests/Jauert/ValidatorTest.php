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

    public function testRegexIsntScalar()
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
}
