<?php

declare(strict_types=1);

namespace Tfrana\NameSplitter;

class SplittedName
{
    public function __construct(
        public readonly ?string $firstName,
        public readonly ?string $middleName,
        public readonly string $familyName,
        public readonly ?string $titleBeforeName,
        public readonly ?string $titleAfterName,
    ) {
    }

    public function getGivenNames(): ?string
    {
        if ($this->middleName === null) {
            return $this->firstName;
        }

        return $this->firstName . ' ' . $this->middleName;
    }
}
