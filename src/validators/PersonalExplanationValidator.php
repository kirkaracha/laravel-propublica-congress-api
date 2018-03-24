<?php declare(strict_types=1);

namespace Kirkaracha\PropublicaCongressApi\Validators;

use InvalidArgumentException;

class PersonalExplanationValidator
{
    /**
     * @param string $category
     * @throws InvalidArgumentException
     */
    public function isValidPersonalExplanationCategory(string $category)
    {
        $validCategories = [
            'ambiguous',
            'claims-voted',
            'election-related',
            'leave-of-absence',
            'medical',
            'memorial',
            'military-service',
            'misunderstanding',
            'official-business',
            'other',
            'personal',
            'prior-commitment',
            'travel-difficulties',
            'voted-incorrectly',
            'weather'
        ];

        if (! in_array($category, $validCategories))
        {
            throw new InvalidArgumentException("{$category} isn't a valid personal explanation category");
        }
    }
}
