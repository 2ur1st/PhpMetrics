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
class VolumeFactor implements FactorInterface
{

    private const LOC_GOOD_BOUND = 65;

    private const LOC_BAD_BOUND = 154;

    private const LOGICAL_LOC_GOOD_BOUND = 9;

    private const LOGICAL_LOC_BAD_BOUND = 30;

    private const VOCABULARY_LOC_GOOD_BOUND = 27;

    private const VOCABULARY_LOC_BAD_BOUND = 59;

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
        $locBadBound = getenv('VOLUME_FACTOR_LOC_BAD_BOUND') ?: self::LOC_BAD_BOUND;
        $logicalLocBadBound = getenv('VOLUME_FACTOR_LOGICAL_LOC_BAD_BOUND') ?: self::LOGICAL_LOC_BAD_BOUND;
        $vocabularyBadBound = getenv('VOLUME_FACTOR_VOCABULARY_BAD_BOUND') ?: self::VOCABULARY_LOC_BAD_BOUND;

        $notes = array(
            $this->calculator->lowIsBetter(self::LOC_GOOD_BOUND, $locBadBound, $bound->getAverage('loc')),
            $this->calculator->highIsBetter(self::LOGICAL_LOC_GOOD_BOUND, $logicalLocBadBound, $bound->getAverage('logicalLoc')),
            $this->calculator->highIsBetter(self::VOCABULARY_LOC_GOOD_BOUND, $vocabularyBadBound, $bound->getAverage('vocabulary'))
        );
        return round(array_sum($notes) / count($notes, COUNT_NORMAL), 2);
    }

    /**
     * @inheritedDoc
     */
    public function getName()
    {
        return 'Volume';
    }
}
