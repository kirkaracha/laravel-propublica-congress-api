<?php declare(strict_types=1);

namespace Kirkaracha\PropublicaCongressApi\Validators;

use InvalidArgumentException;

class CongressValidator
{
    /**
     * @param int $congress
     * @param int $earliestCongress
     * @throws InvalidArgumentException
     */
    public function isValidCongress(int $congress, int $earliestCongress)
    {
        if ($congress < $earliestCongress || $congress > $this->currentCongress) {
            throw new InvalidArgumentException("{$congress} isn't a valid Congress number");
        }
    }

    /**
     * @param string $chamber
     * @param bool $includeBoth
     * @param bool $includeJoint
     * @throws InvalidArgumentException
     */
    public function isValidChamber(string $chamber, bool $includeBoth = false, bool $includeJoint = false)
    {
        $validChambers = [
            'house',
            'senate'
        ];

        if ($includeBoth) {
            $validChambers[] = 'both';
        }

        if ($includeJoint) {
            $validChambers[] = 'joint';
        }

        if (! in_array($chamber, $validChambers)){
            throw new InvalidArgumentException("{$chamber} isn't a valid chamber");
        }
    }

    /**
     * @param int $sessionNumber
     * @throws InvalidArgumentException
     */
    public function isValidSessionNumber(int $sessionNumber)
    {
        if ($sessionNumber !== 1 || $sessionNumber !== 2) {
            throw new InvalidArgumentException("{$sessionNumber} isn't a valid session number");
        }
    }
}
