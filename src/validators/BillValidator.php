<?php declare(strict_types=1);

namespace Kirkaracha\PropublicaCongressApi\Validators;

use InvalidArgumentException;

class BillValidator
{
    /**
     * @param string $type
     * @throws InvalidArgumentException
     */
    public function isValidBillType(string $type)
    {
        $validTypes = [
            'introduced',
            'passed',
            'updated',
            'major'
        ];

        if (! in_array($type, $validTypes))
        {
            throw new InvalidArgumentException("{$type} isn't a valid bill type");
        }
    }

    /**
     * @param string $type
     * @throws InvalidArgumentException
     */
    public function isValidCosponsoredBillType(string $type) {
        $validTypes = [
            'cosponsored',
            'withdrawn'
        ];

        if (! in_array($type, $validTypes))
        {
            throw new InvalidArgumentException("{$type} isn't a valid cosponsored bill type");
        }
    }

    /**
     * @param string $type
     * @throws InvalidArgumentException
     */
    public function isValidMemberRecentBillType($type)
    {
        $validRecentBillTypes = [
            'introduced',
            'updated'
        ];

        if (! in_array($type, $validRecentBillTypes)){
            throw new InvalidArgumentException("{$type} isn't a valid member recent bill type");
        }
    }

    /**
     * @param string $type
     * @throws InvalidArgumentException
     */
    public function isValidRecentBillType(string $type)
    {
        $validRecentBillTypes = [
            'introduced',
            'updated',
            'active',
            'passed',
            'enacted',
            'vetoed'
        ];

        if (! in_array($type, $validRecentBillTypes)){
            throw new InvalidArgumentException("{$type} isn't a valid recent bill type");
        }
    }

}
