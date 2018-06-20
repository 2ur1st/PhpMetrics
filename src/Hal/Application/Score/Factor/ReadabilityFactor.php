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
 * Is the code accessible for new developers ?
 *
 * @author Jean-François Lépine <https://twitter.com/Halleck45>
 */
class ReadabilityFactor implements FactorInterface
{
    private const DIFFICULTY_GOOD_BOUND = 5.8;

    private const DIFFICULTY_BAD_BOUND = 18;

    private const COMMENT_WEIGHT_GOOD_BOUND = 42;

    private const COMMENT_WEIGHT_BAD_BOUND = 32;

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
        $difficultyBadBound = getenv('READABILITY_FACTOR_DIFFICULTY_BAD_BOUND') ?: self::DIFFICULTY_BAD_BOUND;
        $commentWeightBadBound = getenv('READABILITY_FACTOR_COMMENT_WEIGHT_BAD_BOUND') ?: self::COMMENT_WEIGHT_BAD_BOUND;
        $notes = array(
            $this->calculator->lowIsBetter(self::DIFFICULTY_GOOD_BOUND, $difficultyBadBound, $bound->getAverage('difficulty')),
            $this->calculator->highIsBetter(self::COMMENT_WEIGHT_GOOD_BOUND, $commentWeightBadBound, $bound->getAverage('commentWeight'))
        );
        return round(array_sum($notes) / count($notes, COUNT_NORMAL), 2);
    }

    /**
     * @inheritedDoc
     */
    public function getName()
    {
        return 'Accessibility for new developers';
    }
}
