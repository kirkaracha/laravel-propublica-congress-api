<?php declare(strict_types=1);

namespace Kirkaracha\PropublicaCongressApi\Validators;

use InvalidArgumentException;

class FormatValidator
{
    /**
     * @param string $string
     * @param string $type
     * @throws InvalidArgumentException
     */
    public function isAlphanumeric(string $string, string $type)
    {
        if (! ctype_alnum($string))
        {
            throw new InvalidArgumentException("{$string} isn't a valid {$type}");
        }
    }

    /**
     * @param string $memberId
     * @throws InvalidArgumentException
     */
    public function isValidMemberIdFormat(string $memberId)
    {
        $regex = '^[a-zA-Z][0-9]{6}$^';

        if (! preg_match_all($regex, $memberId))
        {
            throw new InvalidArgumentException("{$memberId} isn't a validly-formatted member ID");
        }
    }
}
