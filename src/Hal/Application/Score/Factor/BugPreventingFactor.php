<?php

/*
* (c) Jean-François Lépine <https://twitter.com/Halleck45>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/

namespace Hal\Application\Score\Factor;

use Hal\Application\Score\Calculator;
use Hal\Component\Bounds\Result\ResultInterface;
use Hal\Component\Result\ResultCollection;

/**
 * Is the code complex ?
 *
 * @author Jean-François Lépine <https://twitter.com/Halleck45>
 */
class BugPreventingFactor implements FactorInterface
{

    private const GOOD_BOUND = 0.09;

    private const BAD_BOUND = 0.70;

    /**
     * Bounds
     *
     * @var Calculator
     */
    private $calculator;

    /**
     * Constructor
     *
     * @param Calculator $calculator
     */
    public function __construct(Calculator $calculator)
    {
        $this->calculator = $calculator;
    }

    /**
     * @inheritdoc
     */
    public function calculate(ResultCollection $collection, ResultCollection $groupedResults, ResultInterface $bound)
    {
        $badBound = getenv('BUG_PREVENTING_FACTOR_BAD_BOUND') ?: self::BAD_BOUND;
        return round($this->calculator->lowIsBetter(self::GOOD_BOUND, $badBound, $bound->getAverage('bugs')), 2);
    }

    /**
     * @inheritedDoc
     */
    public function getName()
    {
        return 'Reducing bug\'s probability';
    }
}
