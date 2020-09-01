<?php

namespace App\Service;

use App\Exceptions\EulerProblemException;

/**
 * Class EulerProblem1Resolver to resolve Euler problem number 1.
 *
 * @link   https://projecteuler.net/problem=1
 * @author Dmitriy Svetlichniy
 */
class EulerProblem1Resolver
{
    /**
     * Maximum possible number to resolve the problem for.
     *
     * @var int
     */
    protected int $maxNumber;

    /**
     * EulerProblem1Resolver constructor.
     *
     * @param int $maxNumber
     */
    public function __construct(int $maxNumber)
    {
        $this->maxNumber = $maxNumber;
    }

    /**
     * @return int
     */
    public function getMaxNumber(): int
    {
        return $this->maxNumber;
    }

    /**
     * Returns answer.
     *
     * @param int $x
     *
     * @throws EulerProblemException
     *
     * @return int
     */
    public function resolve(int $x): int
    {
        if ($x <= 0) {
            throw new EulerProblemException(
                sprintf('Value of X=%d should be greater than 0', $x)
            );
        }
        if ($x >= $this->getMaxNumber()) {
            throw new EulerProblemException(
                sprintf(
                    'Value of X=%d should be lower than %d',
                    $x,
                    $this->getMaxNumber()
                )
            );
        }

        $multiples = [];

        for ($number = 0; $number < $x; $number++) {
            if (($number % 3) === 0 || ($number % 5) === 0) {
                $multiples[] = $number;
            }
        }

        return array_sum($multiples);
    }
}
