<?php declare(strict_types=1);

namespace Kirkaracha\PropublicaCongressApi\Validators;

use InvalidArgumentException;

class StateValidator
{
    /**
     * @param string $state
     * @throws InvalidArgumentException
     */
    public function isValidStateAbbreviation(string $state)
    {
        $uspsAbbreviations = ['AK', 'AL', 'AR', 'AZ', 'CA', 'CO', 'CT', 'DC', 'DE', 'FL', 'GA', 'HI', 'IA', 'ID', 'IL', 'IN', 'KS', 'KY', 'LA', 'MA', 'MD', 'ME', 'MI', 'MN', 'MO', 'MS', 'MT', 'NC', 'ND', 'NE', 'NH', 'NJ', 'NM', 'NV', 'NY', 'OH', 'OK', 'OR', 'PA', 'RI', 'SC', 'SD', 'TN', 'TX', 'UT', 'VA', 'VT', 'WA', 'WI', 'WV', 'WY'];

        if (! in_array($state, $uspsAbbreviations))
        {
            throw new InvalidArgumentException("{$state} isn't a valid USPS state abbreviation");
        }
    }
}
