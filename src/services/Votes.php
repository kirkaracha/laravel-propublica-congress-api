<?php declare(strict_types=1);

namespace Kirkaracha\PropublicaCongressApi\Services;

use Kirkaracha\PropublicaCongressApi\PropublicaCongressApi;
use Kirkaracha\PropublicaCongressApi\Validators\CongressValidator;
use Kirkaracha\PropublicaCongressApi\Validators\DateValidator;
use Kirkaracha\PropublicaCongressApi\Validators\PaginationValidator;
use Kirkaracha\PropublicaCongressApi\Validators\VoteValidator;

class Votes extends PropublicaCongressApi
{
    /** @var CongressValidator $congressValidator */
    private $congressValidator;

    /** @var DateValidator $dateValidator */
    private $dateValidator;

    /** @var PaginationValidator $paginationValidator */
    private $paginationValidator;

    /** @var VoteValidator $voteValidator */
    private $voteValidator;

    public function __construct(
        CongressValidator $congressValidator,
        DateValidator $dateValidator,
        PaginationValidator $paginationValidator,
        VoteValidator $voteValidator
    )
    {
        $this->congressValidator   = $congressValidator;
        $this->dateValidator       = $dateValidator;
        $this->paginationValidator = $paginationValidator;
        $this->voteValidator       = $voteValidator;


        parent::__construct();
    }

    /**
     * Get recent votes from the House, Senate or both chambers
     *
     * https://projects.propublica.org/api-docs/congress-api/votes/#get-recent-votes
     *
     * @param string $chamber
     * @param int|null $offset
     * @return mixed
     */
    public function getRecentVotes(string $chamber, int $offset = 0)
    {
        $this->congressValidator->isValidChamber($chamber, true);
        $this->paginationValidator->isValidOffset($offset);

        $uriStub = "{$chamber}/votes/recent.json";

        return $this->performApiRequest($uriStub, $offset);
    }

    /**
     * Get a specific roll-call vote, including a complete list of member positions
     *
     * https://projects.propublica.org/api-docs/congress-api/votes/#get-a-specific-roll-call-vote
     *
     * @param int $congress
     * @param string $chamber
     * @param int $sessionNumber
     * @param int $rollCallNumber
     * @return mixed
     */
    public function getSpecificRollCallVote(int $congress, string $chamber, int $sessionNumber, int $rollCallNumber)
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
        $this->congressValidator->isValidSessionNumber($sessionNumber);

        $uriStub = "{$congress}/{$chamber}/sessions/{$sessionNumber}/votes/{$rollCallNumber}.json";

        return $this->performApiRequest($uriStub);
    }

    /**
     * Get vote information in four categories: missed votes, party votes, lone no votes and perfect votes
     *
     * https://projects.propublica.org/api-docs/congress-api/votes/#get-votes-by-type
     *
     * @param int $congress
     * @param string $chamber
     * @param string $type
     * @return mixed
     */
    public function getVotesByType(int $congress, string $chamber, string $type)
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
        $this->voteValidator->isValidVoteType($type);

        $uriStub = "{$congress}/{$chamber}/votes/{$type}.json";

        return $this->performApiRequest($uriStub);
    }

    /**
     * Get all votes for one or both chambers in a particular month
     *
     * https://projects.propublica.org/api-docs/congress-api/votes/#get-votes-by-date
     *
     * @param string $chamber
     * @param string $year
     * @param string $month
     * @return mixed
     */
    public function getVotesByYearAndMonth(string $chamber, string $year, string $month)
    {
        $this->congressValidator->isValidChamber($chamber, true);
        $this->dateValidator->isValidYearFormat($year);
        $this->dateValidator->isValidMonthFormat($month);

        $uriStub = "{$chamber}/votes/{$year}/{$month}.json";

        return $this->performApiRequest($uriStub);
    }

    /**
     * Get all votes for one or both chambers in a particular date range (fewer than 30 days)
     *
     * https://projects.propublica.org/api-docs/congress-api/votes/#get-votes-by-date
     *
     * @param string $chamber
     * @param string $startDate
     * @param string $endDate
     * @return mixed
     */
    public function getVotesByDateRange(string $chamber, string $startDate, string $endDate)
    {
        $this->congressValidator->isValidChamber($chamber);
        $this->dateValidator->isValidDateRange($startDate, $endDate);

        $uriStub = "{$chamber}/votes/{$startDate}/{$endDate}.json";

        return $this->performApiRequest($uriStub);
    }

    /**
     * Get Senate votes on presidential nominations
     *
     * https://projects.propublica.org/api-docs/congress-api/votes/#get-senate-nomination-votes
     *
     * @param int $congress
     * @return mixed
     */
    public function getSenateNominationVotes(int $congress)
    {
        $earliestCongress = 101;

        $this->congressValidator->isValidCongress($congress, $earliestCongress);

        $uriStub = "{$congress}/nominations.json";

        return $this->performApiRequest($uriStub);
    }
}
