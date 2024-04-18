<?php

declare(strict_types=1);

namespace Tfrana\NameSplitter;

class NameSplitter
{
    private const USUAL_INTERNATIONAL_TITLES = ['BA', 'MBA', 'PhD'];

    public function splitName(string $name): SplittedName
    {
        $explodedName = \explode(' ', $name);

        $titleBeforeName = $this->getTitlesBeforeName($explodedName);
        $titleAfterName = $this->getTitlesAfterName($explodedName);

        $namesArray = \array_values($explodedName);

        if (\count($namesArray) === 1) {
            return new SplittedName(
                null,
                null,
                $namesArray[0],
                $titleBeforeName,
                $titleAfterName,
            );
        }

        $firstName = \array_shift($namesArray);
        $familyName = \trim(\array_pop($namesArray), ',');
        $middleName = $namesArray === [] ? null : \implode(' ', $namesArray);

        return new SplittedName($firstName, $middleName, $familyName, $titleBeforeName, $titleAfterName);
    }

    private function getTitlesBeforeName(array &$explodedName): ?string
    {
        $titles = [];

        foreach ($explodedName as $key => $value) {
            if (\str_ends_with($value, '.')) {
                $titles[] = $value;

                unset($explodedName[$key]);
            } else {
                break;
            }
        }

        if ($titles === []) {
            return null;
        }

        return \implode(' ', $titles);
    }

    private function getTitlesAfterName(array &$explodedName): ?string
    {
        $reversedArray = \array_reverse($explodedName, true);

        $titles = [];

        foreach ($reversedArray as $key => $value) {
            if (\str_ends_with($value, '.') || \in_array($value, self::USUAL_INTERNATIONAL_TITLES)) {
                $titles[] = $value;

                unset($explodedName[$key]);
            } else {
                break;
            }
        }

        if ($titles === []) {
            return null;
        }

        return \implode(' ', \array_reverse($titles));
    }
}
