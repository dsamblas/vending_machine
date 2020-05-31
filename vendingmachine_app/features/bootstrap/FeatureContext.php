<?php

use Behat\Behat\Context\Context;
use Symfony\Component\HttpKernel\Kernel;

/**
 * Defines application features from the specific context.
 */
class FeatureContext implements Context
{

    private $output;
    private $kernel;

    public function __construct(Kernel $kernel)
    {
        $this->kernel = $kernel;
    }

    /**
     * @When I run vending machine command with arguments :args
     */
    public function iRun($args)
    {
        $this->output = shell_exec("php bin/console vending_machine:shell_input_command " . $args);
    }

    /**
     * @Then I should see :string in the output
     */
    public function iShouldSeeInTheOutput($string)
    {
        if (strpos($this->output, $string) === false) {
            throw new Exception(sprintf('Did not see "%s" in output "%s"', $string, $this->output));
        }
    }

    /**
     * @Then I should exactly see :string in the output
     */
    public function iShouldExactlySeeInTheOutput($string)
    {
        if ($this->output !== $string) {
            throw new Exception(sprintf('Non expected result "%s" in output "%s"', $string, $this->output));
        }
    }

    /**
     * @When a demo scenario sends a request to :arg1
     */
    public function aDemoScenarioSendsARequestTo($arg1)
    {

    }

    /**
     * @Then the response should be received
     */
    public function theResponseShouldBeReceived()
    {

    }

}
