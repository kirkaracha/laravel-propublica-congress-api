<?php declare(strict_types=1);

namespace Kirkaracha\PropublicaCongressApi\Services;

use Kirkaracha\PropublicaCongressApi\PropublicaCongressApi;
use Kirkaracha\PropublicaCongressApi\Validators\CongressValidator;
use Kirkaracha\PropublicaCongressApi\Validators\DateValidator;
use Kirkaracha\PropublicaCongressApi\Validators\FormatValidator;

class Statements extends PropublicaCongressApi
{
    /** @var CongressValidator $congressValidator */
    private $congressValidator;

    /** @var DateValidator $dateValidator */
    private $dateValidator;

    /** @var FormatValidator $formatValidator */
    private $formatValidator;

    public function __construct(
        CongressValidator $congressValidator,
        DateValidator $dateValidator,
        FormatValidator $formatValidator
    )
    {
        $this->congressValidator = $congressValidator;
        $this->dateValidator     = $dateValidator;
        $this->formatValidator   = $formatValidator;

        parent::__construct();
    }

    /**
     * Get lists of recent statements published on congressional websites
     *
     * https://projects.propublica.org/api-docs/congress-api/other/#get-recent-congressional-statements
     *
     * @return mixed
     */
    public function getRecentCongressionalStatements()
    {
        $uriStub = "statements/latest.json";

        return $this->performApiRequest($uriStub);
    }

    /**
     * Get lists of statements published on congressional websites on a particular date
     *
     * https://projects.propublica.org/api-docs/congress-api/other/#get-congressional-statements-by-date
     *
     * @param string $date
     * @return mixed
     */
    public function getCongressionalStatementsByDate(string $date)
    {
        $this->dateValidator->isValidDate($date);

        $uriStub = "statements/date/{$date}.json";

        return $this->performApiRequest($uriStub);
    }

    /**
     * Get lists of statements published on congressional websites using a search term
     *
     * @param $term
     * @return mixed
     */
    public function getCongressionalStatementsBySearchTerm(string $term)
    {
        $uriStub = "statements/search.json?query={$term}";

        return $this->performApiRequest($uriStub);
    }

    /**
     * Get lists of subjects used to categorize congressional statements
     *
     * https://projects.propublica.org/api-docs/congress-api/other/#get-statement-subjects
     *
     * @return mixed
     */
    public function getCongressionalStatementSubjects()
    {
        $uriStub = "statements/subjects.json";

        return $this->performApiRequest($uriStub);
    }

    /**
     * Get lists of statements published on congressional websites for a particular subject
     *
     * https://projects.propublica.org/api-docs/congress-api/other/#get-congressional-statements-by-subject
     *
     * @param string $subject
     * @return mixed
     */
    public function getCongressionalStatementsBySubject(string $subject)
    {
        $uriStub = "statements/subject/{$subject}.json";

        return $this->performApiRequest($uriStub);
    }

    /**
     * Get lists of statements published on a specific member’s congressional website during a particular congress
     *
     * https://projects.propublica.org/api-docs/congress-api/other/#get-congressional-statements-by-member
     *
     * @param string $memberId
     * @param int $congress
     * @return mixed
     */
    public function getCongressionalStatementsByMember(string $memberId, int $congress)
    {
        $this->formatValidator->isValidMemberIdFormat($memberId);

        $earliestCongress = 113;

        $this->congressValidator->isValidCongress($congress, $earliestCongress);

        $uriStub = "members/{$memberId}/statements/{$congress}.json";

        return $this->performApiRequest($uriStub);
    }
}
