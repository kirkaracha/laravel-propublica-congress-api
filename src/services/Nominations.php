<?php declare(strict_types=1);

namespace Kirkaracha\PropublicaCongressApi\Services;

use Kirkaracha\PropublicaCongressApi\PropublicaCongressApi;
use Kirkaracha\PropublicaCongressApi\Validators\CongressValidator;
use Kirkaracha\PropublicaCongressApi\Validators\NominationValidator;
use Kirkaracha\PropublicaCongressApi\Validators\StateValidator;

class Nominations extends PropublicaCongressApi
{
    /** @var int $earliestCongress */
    private $earliestCongress;

    /** @var CongressValidator $congressValidator */
    private $congressValidator;

    /** @var NominationValidator $nominationValidator */
    private $nominationValidator;

    /** @var StateValidator $stateValidator */
    private $stateValidator;

    public function __construct(
        CongressValidator $congressValidator,
        NominationValidator $nominationValidator,
        StateValidator $stateValidator
    )
    {
        $this->congressValidator   = $congressValidator;
        $this->nominationValidator = $nominationValidator;
        $this->stateValidator      = $stateValidator;

        $this->earliestCongress = 107;

        parent::__construct();
    }

    /**
     * Get lists of presidential nominations for civilian positions
     *
     * https://projects.propublica.org/api-docs/congress-api/other/#get-recent-nominations-by-category
     *
     * @param int $congress
     * @param string $type
     * @return mixed
     */
    public function getRecentNominationsByCategory(int $congress, string $type)
    {
        $this->congressValidator->isValidCongress($congress, $this->earliestCongress);
        $this->nominationValidator->isValidNominationType($type);

        $uriStub = "{$congress}/nominees/{$type}.json";

        return $this->performApiRequest($uriStub);
    }

    /**
     * Get details about a particular presidential civilian nomination
     *
     * https://projects.propublica.org/api-docs/congress-api/other/#get-a-specific-nomination
     *
     * @param int $congress
     * @param string $nomineeId
     * @return mixed
     */
    public function getSpecificNomination(int $congress, string $nomineeId)
    {
        $this->congressValidator->isValidCongress($congress, $this->earliestCongress);

        $uriStub = "{$congress}/nominees/{$nomineeId}.json";

        return $this->performApiRequest($uriStub);
    }

    /**
     * Get a list of presidential civilian nominations of individuals from a specific state
     *
     * https://projects.propublica.org/api-docs/congress-api/other/#get-nominees-by-state
     *
     * @param int $congress
     * @param string $state
     * @return mixed
     */
    public function getNomineesByState(int $congress, string $state)
    {
        $this->congressValidator->isValidCongress($congress, $this->earliestCongress);
        $this->stateValidator->isValidStateAbbreviation($state);

        $uriStub = "{$congress}/nominees/state/{$state}.json";

        return $this->performApiRequest($uriStub);
    }
}
