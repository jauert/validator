<?php

declare(strict_types=1);

namespace Jauert;

class ValidatorUsageTest extends \PHPUnit\Framework\TestCase
{
    public function testValidation()
    {
        $validator = (new Validator())
            ->requiredField('subject')
            ->requiredField('content')
            ->requiredField('code')
            ->asciiAlphaNumeric('code')
            ->notBlank('subject', 'field is blank')
            ->notBlank('content', 'field is blank');

        $result = $validator->validate([
            'subject' => 'my subject',
            'code' => '12300',
            'content' => 'this is my mail content'
        ]);
        $this->assertEmpty($result, print_r($result, true));
    }
}
