<?php declare(strict_types=1);

namespace Kirkaracha\PropublicaCongressApi\Validators;

use InvalidArgumentException;

class VoteValidator
{
    /**
     * @param string $type
     * @throws InvalidArgumentException
     */
    public function isValidVoteType(string $type)
    {
        $validVoteTypes = [
            'missed',
            'party',
            'loneno',
            'perfect'
        ];

        if (! in_array($type, $validVoteTypes)){
            throw new InvalidArgumentException("{$type} isn't a valid recent vote type");
        }
    }
}
