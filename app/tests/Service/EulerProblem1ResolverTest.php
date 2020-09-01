<?php

namespace App\Tests\Service;

use App\Exceptions\EulerProblemException;
use App\Service\EulerProblem1Resolver;
use TestCase;

class EulerProblem1ResolverTest extends TestCase
{
    /**
     * @dataProvider eulerProblem1IsResolvedCorrectlyDataProvider
     *
     * @param int $x
     * @param int $expectedResult
     *
     * @return void
     */
    public function testEulerProblem1IsResolvedCorrectly(
        int $x,
        int $expectedResult
    ): void {
        // Arrange
        $eulerProblemResolver = new EulerProblem1Resolver(10000);

        // Act
        $actualResult = $eulerProblemResolver->resolve($x);

        // Assert
        $this->assertEquals(
            $expectedResult,
            $actualResult,
            sprintf(
                'Actual value %d should be equal to %d',
                $actualResult,
                $expectedResult
            )
        );
    }

    /**
     * @dataProvider eulerProblem1CanNotBeResolvedDataProvider
     *
     * @param int $x
     *
     * @return void
     */
    public function testEulerProblem1CanNotBeResolved(int $x): void
    {
        // Arrange
        $eulerProblemResolver = new EulerProblem1Resolver(10000);

        // Act
        $this->expectException(EulerProblemException::class);
        $eulerProblemResolver->resolve($x);
    }

    /**
     * Provides test data.
     *
     * @return int[][]
     */
    public function eulerProblem1IsResolvedCorrectlyDataProvider(): array
    {
        return [
            '10' => [10, 23],
            '9' => [9, 14],
            '6' => [6, 8],
            '1' => [1, 0],
            '1000' => [1000, 233168],
        ];
    }

    /**
     * Provides test data.
     *
     * @return int[][]
     */
    public function eulerProblem1CanNotBeResolvedDataProvider(): array
    {
        return [
            '0' => [0],
            '-100' => [-100],
            '10001' => [10001],
        ];
    }
}
