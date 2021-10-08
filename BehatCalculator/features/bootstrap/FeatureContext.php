<?php

use App\Calculator;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Exception;
use Behat\Behat\Context\Context;
use Behat\Gherkin\Node\PyStringNode;
use Behat\Gherkin\Node\TableNode;
use PhpParser\Node\Expr\Instanceof_;
use PHPUnit\Framework\Constraint\IsInstanceOf;

/**
 * Defines application features from the specific context.
 */
class FeatureContext implements Context
{
    private Calculator $calculator;

    /**
     * Initializes context.
     *
     * Every scenario gets its own context instance.
     * You can also pass arbitrary arguments to the
     * context constructor through behat.yml.
     */
    public function __construct()
    {
        $this->calculator = new calculator;
    }

    /**
     * @When j'additionne :arg1 :arg2
     */
    public function jadditionne($arg1, $arg2)
    {
        Assert::assertTrue(is_numeric($this->calculator->add($arg1, $arg2)));
    }

    /**
     * @Then le résultat :arg1 est mémorisé
     */
    public function leResultatEstMemorise($arg1)
    {

        $memory = $this->calculator->getMemory();

        if (end($memory) != $arg1) throw new Exception('Mauvaise addition');
        // autre méthode
        Assert::assertEquals(end($memory), $arg1);
    }

    /**
     * @Given the following memoryDatas:
     */
    public function theFollowingNumbers(TableNode $memoryDatas)
    {
        foreach ($memoryDatas as $datas) {
            $this->calculator->add($datas['nombre1'], $datas['nombre2']);
        }
    }

        /**
     * @When I ask the memory
     */
    public function iAskTheMemory()
    {
        Assert::assertTrue(is_array($this->calculator->getMemory()));
    }

    /**
     * @Then I get :arg1
     */
    public function iGet($arg1)
    {
        $memory = explode(",", $arg1);
        Assert::assertEquals($memory, $this->calculator->getMemory());
    }

    /**
     * @When I reset the memory
     */
    public function iResetTheMemory()
    {
        Assert::assertTrue(!empty($this->calculator->getMemory()));
        $this->calculator->reset();
    }

    /**
     * @Then I get an empty array
     */
    public function iGetAnEmptyArray()
    {
        Assert::assertTrue(empty($this->calculator->getMemory()));
    }




    /**
     * @When the result is greater than two hundred with :arg1 :arg2
     */
    public function theResultIsGreaterThanWith($arg1, $arg2)
    {
        
    }

    /**
     * @Then I get an exception
     */
    public function iGetAnException()
    {
        try {
            $this->calculator->add(100, 101);
            
        } catch (RangeException $e) {
            return;
        }
        
        return new Exception('execption non jetéé');
    }

}
