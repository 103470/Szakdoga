<?php

namespace Tests\Unit;

use Tests\TestCase;

class EmailValidationUnitTest extends TestCase
{
    public function test_email_format_is_valid()
    {
        $email = 'teszt@example.com';

        $this->assertMatchesRegularExpression('/^[\w\.-]+@[\w\.-]+\.\w+$/', $email);
    }

    public function test_email_format_is_invalid()
    {
        $email = 'rosszemail@com';

        $this->assertDoesNotMatchRegularExpression('/^[\w\.-]+@[\w\.-]+\.\w+$/', $email);
    }
}
