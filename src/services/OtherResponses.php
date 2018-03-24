<?php declare(strict_types=1);

namespace Kirkaracha\PropublicaCongressApi\Services;

use Kirkaracha\PropublicaCongressApi\PropublicaCongressApi;
use Kirkaracha\PropublicaCongressApi\Validators\CongressValidator;
use Kirkaracha\PropublicaCongressApi\Validators\FormatValidator;

class OtherResponses extends PropublicaCongressApi
{
    /** @var int $earliestCongress */
    private $earliestCongress;

    /** @var CongressValidator $congressValidator */
    private $congressValidator;

    /** @var FormatValidator $formatValidator  */
    private $formatValidator;

    public function __construct(
        CongressValidator $congressValidator,
        FormatValidator $formatValidator
    )
    {
        $this->congressValidator = $congressValidator;
        $this->formatValidator   = $formatValidator;

        $this->earliestCongress = 114;

        parent::__construct();
    }

    /**
     * Get party membership counts for all states (current Congress only),
     *
     * https://projects.propublica.org/api-docs/congress-api/other/#get-state-party-counts
     *
     * @return mixed
     */
    public function getStatePartyCounts()
    {
        $uriStub = "states/members/party.json";

        return $this->performApiRequest($uriStub);
    }

    /**
     * Get a list of Senate, House or joint committees, including their subcommittees
     *
     * https://projects.propublica.org/api-docs/congress-api/other/#lists-of-committees
     *
     * @param int $congress
     * @param string $chamber
     * @return mixed
     */
    public function getListOfCommittees(int $congress, string $chamber)
    {
        $earliestCongress = 110;

        $this->congressValidator->isValidCongress($congress, $earliestCongress);
        $this->congressValidator->isValidChamber($chamber, false, true);

        $uriStub = "{$congress}/{$chamber}/committees.json";

        return $this->performApiRequest($uriStub);
    }

    /**
     * Get information about a single Senate or House committee, including the members of that committee
     *
     * https://projects.propublica.org/api-docs/congress-api/other/#get-a-specific-committee
     *
     * @param int $congress
     * @param string $chamber
     * @param string $committeeId
     * @return mixed
     */
    public function getSpecificCommittee(int $congress, string $chamber, string $committeeId)
    {
        $earliestCongress = 110;

        $this->congressValidator->isValidCongress($congress, $earliestCongress);
        $this->congressValidator->isValidChamber($chamber, false, true);
        $this->formatValidator->isAlphanumeric($committeeId, 'committee ID');

        $uriStub = "{$congress}/{$chamber}/committees/{$committeeId}.json";

        return $this->performApiRequest($uriStub);
    }

    /**
     * Get a list of 20 upcoming Senate or House committee meetings
     * Previous congresses will return the 20 latest by date
     *
     * https://projects.propublica.org/api-docs/congress-api/other/#get-recent-committee-hearings
     *
     * @param int $congress
     * @return mixed
     */
    public function getRecentCommitteeHearings(int $congress)
    {
        $this->congressValidator->isValidCongress($congress, $this->earliestCongress);

        $uriStub = "{$congress}/committees/hearings.json";

        return $this->performApiRequest($uriStub);
    }

    /**
     * Get a list of hearings for a specific Senate or House committee
     *
     * https://projects.propublica.org/api-docs/congress-api/other/#get-hearings-for-a-specific-committees
     *
     * @param int $congress
     * @param string $chamber
     * @param string $committeeId
     * @return mixed
     */
    public function getHearingsForSpecificCommittee(int $congress, string $chamber, string $committeeId)
    {
        $this->congressValidator->isValidCongress($congress, $this->earliestCongress);
        $this->congressValidator->isValidChamber($chamber);
        $this->formatValidator->isAlphanumeric($committeeId, 'committee ID');

        $uriStub = "{$congress}/{$chamber}/committees/{$committeeId}/hearings.json";

        return $this->performApiRequest($uriStub);
    }

    /**
     * Get information about a single Senate or House subcommittee, including the members of that subcommittee
     *
     * https://projects.propublica.org/api-docs/congress-api/other/#get-a-specific-subcommittee
     *
     * @param int $congress
     * @param string $chamber
     * @param string $committeeId
     * @param string $subcommitteeId
     * @return mixed
     */
    public function getSpecificSubcommittee(int $congress, string $chamber, string $committeeId, string $subcommitteeId)
    {
        $this->congressValidator->isValidCongress($congress, $this->earliestCongress);
        $this->congressValidator->isValidChamber($chamber, false, true);
        $this->formatValidator->isAlphanumeric($committeeId, 'committee ID');
        $this->formatValidator->isAlphanumeric($subcommitteeId, 'subcommittee ID');

        $uriStub = "{$congress}/{$chamber}/committees/{$committeeId}/subcommittees/{$subcommitteeId}.json";

        return $this->performApiRequest($uriStub);
    }
}
