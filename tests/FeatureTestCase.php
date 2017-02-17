<?php

use Tests\TestsHelper;
use Tests\CreatesApplication;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class FeatureTestCase extends \Laravel\BrowserKitTesting\TestCase
{
    use CreatesApplication, TestsHelper, DatabaseTransactions;

    public function seeErrors(array $fields)
    {
        foreach ($fields as $name => $errors) {
            foreach ((array) $errors as $message) {
                $this->seeInElement(
                    "#field_{$name}.has-error .help-block", $message
                );
            }
        }
    }
}
