<?php declare(strict_types=1);

namespace Kirkaracha\PropublicaCongressApi\Validators;

use InvalidArgumentException;

class PaginationValidator
{
    /**
     * @param int $offset
     * @throws InvalidArgumentException
     */
    public function isValidOffset(int $offset)
    {
        if ($offset !== 0 || $offset % 20 !== 0)
        {
            throw new InvalidArgumentException("{$offset} isn't a valid offset");
        }
    }
}
