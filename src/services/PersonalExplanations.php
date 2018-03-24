<?php declare(strict_types=1);

namespace Kirkaracha\PropublicaCongressApi\Services;

use Kirkaracha\PropublicaCongressApi\PropublicaCongressApi;
use Kirkaracha\PropublicaCongressApi\Validators\CongressValidator;
use Kirkaracha\PropublicaCongressApi\Validators\FormatValidator;
use Kirkaracha\PropublicaCongressApi\Validators\PaginationValidator;
use Kirkaracha\PropublicaCongressApi\Validators\PersonalExplanationValidator;

class PersonalExplanations extends PropublicaCongressApi
{
    /** @var int $earliestCongress */
    private $earliestCongress;

    /** @var CongressValidator $congressValidator */
    private $congressValidator;

    /** @var FormatValidator $formatValidator */
    private $formatValidator;

    /** @var PaginationValidator $paginationValidator */
    private $paginationValidator;

    /** @var PersonalExplanationValidator $personalExplanationValidator */
    private $personalExplanationValidator;

    public function __construct(
        CongressValidator $congressValidator,
        FormatValidator $formatValidator,
        PaginationValidator $paginationValidator,
        PersonalExplanationValidator $personalExplanationValidator
    )
    {
        $this->congressValidator            = $congressValidator;
        $this->formatValidator              = $formatValidator;
        $this->paginationValidator          = $paginationValidator;
        $this->personalExplanationValidator = $personalExplanationValidator;

        $this->earliestCongress = 110;

        parent::__construct();
    }

    /**
     * Get lists of personal explanations for missed or mistaken votes in the Congressional Record
     *
     * https://projects.propublica.org/api-docs/congress-api/votes/#get-recent-personal-explanations
     *
     * @param int $congress
     * @param int $offset
     * @return mixed
     */
    public function getRecentPersonalExplanations(int $congress, int $offset = 0)
    {
        $earliestCongress = 107;

        $this->congressValidator->isValidCongress($congress, $earliestCongress);
        $this->paginationValidator->isValidOffset($offset);

        $uriStub = "{$congress}/explanations.json";

        return $this->performApiRequest($uriStub, $offset);
    }

    /**
     * Get explanations parsed to individual votes and have an additional
     *
     * https://projects.propublica.org/api-docs/congress-api/votes/#get-recent-personal-explanation-votes
     *
     * @param int $congress
     * @param int $offset
     * @return mixed
     */
    public function getRecentPersonalExplanationVotes(int $congress, int $offset = 0)
    {
        $this->congressValidator->isValidCongress($congress, $this->earliestCongress);
        $this->paginationValidator->isValidOffset($offset);

        $uriStub = "{$congress}/explanations/votes.json";

        return $this->performApiRequest($uriStub, $offset);
    }

    /**
     * https://projects.propublica.org/api-docs/congress-api/votes/#get-recent-personal-explanation-votes-by-category
     *
     * @param int $congress
     * @param string $category
     * @param int $offset
     * @return mixed
     */
    public function getRecentPersonalExplanationVotesByCategory(int $congress, string $category, int $offset = 0)
    {
        $this->congressValidator->isValidCongress($congress, $this->earliestCongress);
        $this->personalExplanationValidator->isValidPersonalExplanationCategory($category);
        $this->paginationValidator->isValidOffset($offset);

        $uriStub = "{$congress}/explanations/votes/{$category}.json";

        return $this->performApiRequest($uriStub, $offset);
    }

    /**
     * Get recent personal explanations by a specific member
     *
     * https://projects.propublica.org/api-docs/congress-api/votes/#get-recent-personal-explanations-by-a-specific-member
     *
     * @param string $memberId
     * @param int $congress
     * @param int $offset
     * @return mixed
     */
    public function getRecentPersonalExplanationsBySpecificMember(string $memberId, int $congress, int $offset = 0)
    {
        $this->formatValidator->isValidMemberIdFormat($memberId);
        $this->congressValidator->isValidCongress($congress, $this->earliestCongress);
        $this->paginationValidator->isValidOffset($offset);

        $uriStub = "members/{$memberId}/explanations/{$congress}.json";

        return $this->performApiRequest($uriStub, $offset);
    }

    /**
     * https://projects.propublica.org/api-docs/congress-api/votes/#get-recent-personal-explanation-votes-by-a-specific-member
     *
     * @param string $memberId
     * @param int $congress
     * @param int $offset
     * @return mixed
     */
    public function getRecentPersonalExplanationVotesBySpecificMember(string $memberId, int $congress, int $offset = 0)
    {
        $this->formatValidator->isValidMemberIdFormat($memberId);
        $this->congressValidator->isValidCongress($congress, $this->earliestCongress);
        $this->paginationValidator->isValidOffset($offset);

        $uriStub = "members/{$memberId}/explanations/{$congress}/votes.json";

        return $this->performApiRequest($uriStub, $offset);
    }

    /**
     * https://projects.propublica.org/api-docs/congress-api/votes/#get-recent-personal-explanation-votes-by-a-specific-member-by-category
     *
     * @param string $memberId
     * @param int $congress
     * @param string $category
     * @param int $offset
     * @return mixed
     */
    public function getRecentPersonalExplanationVotesBySpecificMemberByCategory(string $memberId, int $congress, string $category, int $offset = 0)
    {
        $this->formatValidator->isValidMemberIdFormat($memberId);
        $this->congressValidator->isValidCongress($congress, $this->earliestCongress);
        $this->personalExplanationValidator->isValidPersonalExplanationCategory($category);
        $this->paginationValidator->isValidOffset($offset);

        $uriStub = "members/{$memberId}/explanations/{$congress}/votes/{$category}.json";

        return $this->performApiRequest($uriStub, $offset);
    }
}
