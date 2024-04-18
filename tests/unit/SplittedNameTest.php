<?php

namespace Tfrana\NameSplitter;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class SplittedNameTest extends TestCase
{
    #[DataProvider('provideSplittedNames')]
    public function testShouldReturnAllGivenNames(SplittedName $splittedName, ?string $expectedResult): void
    {
        $actualResult = $splittedName->getGivenNames();

        self::assertSame($expectedResult, $actualResult);
    }

    public static function provideSplittedNames(): iterable
    {
        yield 'first name only' => [
            'splittedName' => new SplittedName(
                firstName: 'Jan',
                middleName: null,
                familyName: 'Komenský',
                titleBeforeName: null,
                titleAfterName: null,
            ),
            'expectedResult' => 'Jan'
        ];

        yield 'middle name only' => [
            'splittedName' => new SplittedName(
                firstName: 'Jan',
                middleName: 'Amos',
                familyName: 'Komenský',
                titleBeforeName: null,
                titleAfterName: null,
            ),
            'expectedResult' => 'Jan Amos'
        ];

        yield 'last name only' => [
            'splittedName' => new SplittedName(
                firstName: null,
                middleName: null,
                familyName: 'Komenský',
                titleBeforeName: null,
                titleAfterName: null,
            ),
            'expectedResult' => null,
        ];
    }
}
