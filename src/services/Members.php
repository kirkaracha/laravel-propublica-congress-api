<?php declare(strict_types=1);

namespace Kirkaracha\PropublicaCongressApi\Services;

use Kirkaracha\PropublicaCongressApi\PropublicaCongressApi;
use Kirkaracha\PropublicaCongressApi\Validators\BillValidator;
use Kirkaracha\PropublicaCongressApi\Validators\CongressValidator;
use Kirkaracha\PropublicaCongressApi\Validators\FormatValidator;
use Kirkaracha\PropublicaCongressApi\Validators\StateValidator;

class Members extends PropublicaCongressApi
{
    /** @var BillValidator $billValidator */
    private $billValidator;

    /** @var CongressValidator $congressValidator */
    private $congressValidator;

    /** @var FormatValidator $formatValidator */
    private $formatValidator;

    /** @var StateValidator $stateValidator */
    private $stateValidator;

    public function __construct(
        BillValidator $billValidator,
        CongressValidator $congressValidator,
        FormatValidator $formatValidator,
        StateValidator $stateValidator
    )
    {
        $this->billValidator     = $billValidator;
        $this->congressValidator = $congressValidator;
        $this->formatValidator   = $formatValidator;
        $this->stateValidator    = $stateValidator;

        parent::__construct();
    }

    /**
     * Get a list of members of a particular chamber in a particular Congress
     *
     * https://projects.propublica.org/api-docs/congress-api/members/#lists-of-members
     *
     * @param int $congress
     * @param string $chamber
     * @return mixed
     */
    public function listMembers(int $congress, string $chamber)
    {
        $this->congressValidator->isValidChamber($chamber);

        switch ($chamber) {
            case 'senate':
                $earliestCongress = 80;
                break;
            case 'house':
            default:
                $earliestCongress = 102;
                break;
        }

        $this->congressValidator->isValidCongress($congress, $earliestCongress);

        $uriStub = "{$congress}/{$chamber}/members.json";

        return $this->performApiRequest($uriStub);
    }

    /**
     *  Get biographical and congressional role information for a particular member of Congress
     *
     * https://projects.propublica.org/api-docs/congress-api/members/#get-a-specific-member
     *
     * @param string $memberId
     * @return mixed
     */
    public function getSpecificMember(string $memberId)
    {
        $this->formatValidator->isValidMemberIdFormat($memberId);

        $uriStub = "members/{$memberId}.json";

        return $this->performApiRequest($uriStub);
    }

    /**
     * Get a list of the most recent new members of the current Congress
     *
     * https://projects.propublica.org/api-docs/congress-api/members/#get-new-members
     *
     * @return mixed
     */
    public function getNewMembers()
    {
        $uriStub = 'members/new.json';

        return $this->performApiRequest($uriStub);
    }

    /**
     * Get current senators by state or current representatives by state and district
     *
     * https://projects.propublica.org/api-docs/congress-api/members/#get-current-members-by-statedistrict
     *
     * @param string $chamber
     * @param string $state
     * @param int $district
     * @return mixed
     */
    public function getCurrentMembersByStateOrStateAndDistrict(string $chamber, string $state, int $district = null)
    {
        $this->congressValidator->isValidChamber($chamber);
        $this->stateValidator->isValidStateAbbreviation($state);

        $uriStub = "members/{$chamber}/{$state}/";

        if (! is_null($district)) {
            $uriStub .= "{$district}/";
        }

        $uriStub .= 'current.json';

        return $this->performApiRequest($uriStub);
    }

    /**
     * Get a list of members who have left the Senate or House or have announced plans to do so
     *
     * https://projects.propublica.org/api-docs/congress-api/members/#get-members-leaving-office
     *
     * @param int $congress
     * @param string $chamber
     * @return mixed
     */
    public function getMembersLeavingOffice(int $congress, string $chamber)
    {
        $earliestCongress = 111;

        $this->congressValidator->isValidCongress($congress, $earliestCongress);
        $this->congressValidator->isValidChamber($chamber);

        $uriStub = "{$congress}/{$chamber}/members/leaving.json";

        return $this->performApiRequest($uriStub);
    }

    /**
     * Get the most recent vote positions for a specific member of the House of Representatives or Senate
     *
     * https://projects.propublica.org/api-docs/congress-api/members/#get-a-specific-members-vote-positions
     *
     * @param string $memberId
     * @return mixed
     */
    public function getSpecificMemberVotePositions(string $memberId)
    {
        $this->formatValidator->isValidMemberIdFormat($memberId);

        $uriStub = "{$memberId}/votes.json";

        return $this->performApiRequest($uriStub);
    }

    /**
     * Compare two members’ vote positions in a particular Congress and chamber
     *
     * https://projects.propublica.org/api-docs/congress-api/members/#compare-two-members-vote-positions
     *
     * @param string $firstMemberId
     * @param string $secondMemberId
     * @param int $congress
     * @param string $chamber
     * @return mixed
     */
    public function compareTwoMembersVotePositions(string $firstMemberId, string $secondMemberId, int $congress, string $chamber)
    {
        $this->formatValidator->isValidMemberIdFormat($firstMemberId);
        $this->formatValidator->isValidMemberIdFormat($secondMemberId);
        $this->congressValidator->isValidChamber($chamber);

        switch ($chamber) {
            case 'senate':
                $earliestCongress = 101;
                break;
            case 'house':
            default:
                $earliestCongress = 102;
                break;
        }

        $this->congressValidator->isValidCongress($congress, $earliestCongress);

        $uriStub = "members/{$firstMemberId}/votes/{$secondMemberId}/{$congress}/{$chamber}.json";

        return $this->performApiRequest($uriStub);
    }

    /**
     * Compare bill sponsorship between two members who served in the same Congress and chamber
     *
     * https://projects.propublica.org/api-docs/congress-api/members/#compare-two-members-bill-sponsorships
     *
     * @param string $firstMemberId
     * @param string $secondMemberId
     * @param int $congress
     * @param string $chamber
     * @return mixed
     */
    public function compareTwoMembersBillSponsorships(string $firstMemberId, string $secondMemberId, int $congress, string $chamber)
    {
        $this->formatValidator->isValidMemberIdFormat($firstMemberId);
        $this->formatValidator->isValidMemberIdFormat($secondMemberId);
        $this->congressValidator->isValidChamber($chamber);

        switch ($chamber) {
            case 'senate':
                $earliestCongress = 101;
                break;
            case 'house':
            default:
                $earliestCongress = 102;
                break;
        }

        $this->congressValidator->isValidCongress($congress, $earliestCongress);

        $uriStub = "members/{$firstMemberId}/bills/{$secondMemberId}/{$congress}/{$chamber}.json";

        return $this->performApiRequest($uriStub);
    }

    /**
     * Get the 20 most recent bill cosponsorships for a particular member
     *
     * https://projects.propublica.org/api-docs/congress-api/members/#get-bills-cosponsored-by-a-specific-member
     *
     * @param string $memberId
     * @param string $type
     * @return mixed
     */
    public function getSpecificMemberCosponsoredBills(string $memberId, string $type)
    {
        $this->formatValidator->isValidMemberIdFormat($memberId);
        $this->billValidator->isValidCosponsoredBillType($type);

        $uriStub = "{$memberId}/bills/{$type}.json";

        return $this->performApiRequest($uriStub);
    }
}
