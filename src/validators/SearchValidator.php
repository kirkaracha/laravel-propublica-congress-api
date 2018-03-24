<?php declare(strict_types=1);

namespace Kirkaracha\PropublicaCongressApi\Validators;

use InvalidArgumentException;

class SearchValidator
{
    /**
     * @param string $sort
     * @throws InvalidArgumentException
     */
    public function isValidSearchSortBy(string $sort)
    {
        $validSortBys = [
            '_score',
            'date'
        ];

        if (! in_array($sort, $validSortBys))
        {
            throw new InvalidArgumentException("{$sort} isn't a valid sort by");
        }
    }

    /**
     * @param string $dir
     * @throws InvalidArgumentException
     */
    public function isValidSearchSortDirection(string $dir)
    {
        $validSortDirections = [
            'asc',
            'desc'
        ];

        if (! in_array($dir, $validSortDirections))
        {
            throw new InvalidArgumentException("{$dir} isn't a valid sort direction");
        }
    }

    /**
     * @param string $searchType
     * @throws InvalidArgumentException
     */
    public function isValidSearchType(string $searchType)
    {
        $validSortBys = [
            'keyword',
            'phrase'
        ];

        if (! in_array($searchType, $validSortBys))
        {
            throw new InvalidArgumentException("{$searchType} isn't a valid search type");
        }
    }
}
