<?php declare(strict_types=1);

namespace Kirkaracha\PropublicaCongressApi\Validators;

use InvalidArgumentException;

class NominationValidator
{
    /**
     * @param string $type
     * @throws InvalidArgumentException
     */
    public function isValidNominationType(string $type)
    {
        $validTypes = [
            'received',
            'updated',
            'confirmed',
            'withdrawn'
        ];

        if (! in_array($type, $validTypes))
        {
            throw new InvalidArgumentException("{$type} isn't a valid nomination type");
        }

    }
}
