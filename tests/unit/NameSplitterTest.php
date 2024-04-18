<?php

declare(strict_types=1);

namespace Tfrana\NameSplitter;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class NameSplitterTest extends TestCase
{
    private readonly NameSplitter $nameSplitter;

    public function setUp(): void
    {
        parent::setUp();

        $this->nameSplitter = new NameSplitter();
    }

    #[DataProvider('provideNamesToSplit')]
    public function testShouldSplitNameCorrectly(string $nameToSplit, SplittedName $expectedResult): void
    {
        $actualResult = $this->nameSplitter->splitName($nameToSplit);

        self::assertEquals($expectedResult, $actualResult);
    }

    public static function provideNamesToSplit(): iterable
    {
        yield 'Simple name' => [
            'nameToSplit' => 'Jan Komenský',
            'expectedResult' => new SplittedName(
                firstName: 'Jan',
                middleName: null,
                familyName: 'Komenský',
                titleBeforeName: null,
                titleAfterName: null,
            ),
        ];

        yield 'Title before name should be recoginez' => [
            'nameToSplit' => 'Mgr. Jan Komenský',
            'expectedResult' => new SplittedName(
                firstName: 'Jan',
                middleName: null,
                familyName: 'Komenský',
                titleBeforeName: 'Mgr.',
                titleAfterName: null,
            ),
        ];

        yield 'Middle name should be recognized' => [
            'nameToSplit' => 'Jan Amos Komenský',
            'expectedResult' => new SplittedName(
                firstName: 'Jan',
                middleName: 'Amos',
                familyName: 'Komenský',
                titleBeforeName: null,
                titleAfterName: null,
            ),
        ];

        yield 'Middle name and title before name should be recognized' => [
            'nameToSplit' => 'Mgr. Jan Amos Komenský',
            'expectedResult' => new SplittedName(
                firstName: 'Jan',
                middleName: 'Amos',
                familyName: 'Komenský',
                titleBeforeName: 'Mgr.',
                titleAfterName: null,
            ),
        ];

        yield 'Shortened middle name should be treated as middle name' => [
            'nameToSplit' => 'Jan A. Komenský',
            'expectedResult' => new SplittedName(
                firstName: 'Jan',
                middleName: 'A.',
                familyName: 'Komenský',
                titleBeforeName: null,
                titleAfterName: null,
            ),
        ];

        yield 'Single name should be treated as surname' => [
            'nameToSplit' => 'Komenský',
            'expectedResult' => new SplittedName(
                firstName: null,
                middleName: null,
                familyName: 'Komenský',
                titleBeforeName: null,
                titleAfterName: null,
            ),
        ];

        yield 'Single name should be treated as surname, title before name should be recognized' => [
            'nameToSplit' => 'Mgr. Komenský',
            'expectedResult' => new SplittedName(
                firstName: null,
                middleName: null,
                familyName: 'Komenský',
                titleBeforeName: 'Mgr.',
                titleAfterName: null,
            ),
        ];

        yield 'Surname with dash should be still marked as surname' => [
            'nameToSplit' => 'Jana Amosová-Komenská',
            'expectedResult' => new SplittedName(
                firstName: 'Jana',
                middleName: null,
                familyName: 'Amosová-Komenská',
                titleBeforeName: null,
                titleAfterName: null,
            ),
        ];

        yield 'Multiword title before name should be recognized as title' => [
            'nameToSplit' => 'akad. arch. Jan Amos Komenský',
            'expectedResult' => new SplittedName(
                firstName: 'Jan',
                middleName: 'Amos',
                familyName: 'Komenský',
                titleBeforeName: 'akad. arch.',
                titleAfterName: null,
            ),
        ];

        yield 'Multiword title before name should be recognized as title, shortened middle name should be treated as middle name' => [
            'nameToSplit' => 'akad. arch. Jan A. Komenský',
            'expectedResult' => new SplittedName(
                firstName: 'Jan',
                middleName: 'A.',
                familyName: 'Komenský',
                titleBeforeName: 'akad. arch.',
                titleAfterName: null,
            ),
        ];

        yield 'Many middle names sould be treated as middle name' => [
            'nameToSplit' => 'Jan Amos Učitel Národů Komenský',
            'expectedResult' => new SplittedName(
                firstName: 'Jan',
                middleName: 'Amos Učitel Národů',
                familyName: 'Komenský',
                titleBeforeName: null,
                titleAfterName: null,
            ),
        ];

        yield 'Title after name should be recognized' => [
            'nameToSplit' => 'Jan Amos Komenský ThDr.',
            'expectedResult' => new SplittedName(
                firstName: 'Jan',
                middleName: 'Amos',
                familyName: 'Komenský',
                titleBeforeName: null,
                titleAfterName: 'ThDr.',
            ),
        ];

        yield 'Multiple titles should be recognized and in correct order' => [
            'nameToSplit' => 'Ing. Mgr. Bc. Jan Amos Komenský ThDr. PhD.',
            'expectedResult' => new SplittedName(
                firstName: 'Jan',
                middleName: 'Amos',
                familyName: 'Komenský',
                titleBeforeName: 'Ing. Mgr. Bc.',
                titleAfterName: 'ThDr. PhD.',
            ),
        ];

        yield 'Known international titles, should be recognized' => [
            'nameToSplit' => 'Jan Amos Komenský MBA',
            'expectedResult' => new SplittedName(
                firstName: 'Jan',
                middleName: 'Amos',
                familyName: 'Komenský',
                titleBeforeName: null,
                titleAfterName: 'MBA',
            ),
        ];
    }
}
