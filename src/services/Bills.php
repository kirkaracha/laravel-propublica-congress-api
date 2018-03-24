<?php declare(strict_types=1);

namespace Kirkaracha\PropublicaCongressApi\Services;

use Kirkaracha\PropublicaCongressApi\PropublicaCongressApi;
use Kirkaracha\PropublicaCongressApi\Validators\BillValidator;
use Kirkaracha\PropublicaCongressApi\Validators\CongressValidator;
use Kirkaracha\PropublicaCongressApi\Validators\DateValidator;
use Kirkaracha\PropublicaCongressApi\Validators\FormatValidator;
use Kirkaracha\PropublicaCongressApi\Validators\PaginationValidator;
use Kirkaracha\PropublicaCongressApi\Validators\SearchValidator;

class Bills extends PropublicaCongressApi
{
    /** @var int $earliestCongress */
    private $earliestCongress;

    /** @var BillValidator $billValidator */
    private $billValidator;

    /** @var CongressValidator $congressValidator */
    private $congressValidator;

    /** DateValidator $dateValidator */
    private $dateValidator;

    /** @var FormatValidator $formatValidator */
    private $formatValidator;

    /** @var PaginationValidator $paginationValidator */
    private $paginationValidator;

    /** @var SearchValidator $searchValidator */
    private $searchValidator;

    public function __construct(
        BillValidator $billValidator,
        CongressValidator $congressValidator,
        DateValidator $dateValidator,
        FormatValidator $formatValidator,
        PaginationValidator $paginationValidator,
        SearchValidator $searchValidator
    )
    {
        $this->billValidator       = $billValidator;
        $this->congressValidator   = $congressValidator;
        $this->dateValidator       = $dateValidator;
        $this->formatValidator     = $formatValidator;
        $this->paginationValidator = $paginationValidator;
        $this->searchValidator     = $searchValidator;

        $this->earliestCongress = 105;

        parent::__construct();
    }

    /**
     * Search the title and full text of legislation by keyword or phrase to get the 20 most recent bills
     *
     * https://projects.propublica.org/api-docs/congress-api/bills/#search-bills
     *
     * @param string $query
     * @param string $searchType
     * @param string $sort
     * @param string $dir
     * @return mixed
     */
    public function searchBills(string $query, string $searchType = 'keyword', string $sort = 'date', string $dir = 'asc')
    {
        $this->searchValidator->isValidSearchType($searchType);
        $this->searchValidator->isValidSearchSortBy($sort);
        $this->searchValidator->isValidSearchSortDirection($dir);

        $uriStub = 'bills/search.json';

        if ($searchType === 'phrase') {
            $query = "'{$query}'";
        }

        $queries = [
            'query' => $query,
            'sort'  => $sort,
            'dir'   => $dir
        ];

        return $this->performApiRequest($uriStub, 0, $queries);
    }

    /**
     * Get summaries of the 20 most recent bills by type
     *
     * https://projects.propublica.org/api-docs/congress-api/bills/#get-recent-bills
     *
     * @param int $congress
     * @param string $chamber
     * @param string $type
     * @param int $offset
     * @return mixed
     */
    public function getRecentBills(int $congress, string $chamber, string $type, int $offset = 0)
    {
        $this->congressValidator->isValidCongress($congress, $this->earliestCongress);
        $this->congressValidator->isValidChamber($chamber, true);
        $this->billValidator->isValidBillType($type);
        $this->paginationValidator->isValidOffset($offset);

        $uriStub = "{$congress}/{$chamber}/bills/{$type}.json";

        return $this->performApiRequest($uriStub, $offset);
    }

    /**
     * Get the 20 bills most recently introduced or updated by a particular member
     *
     * https://projects.propublica.org/api-docs/congress-api/bills/#get-recent-bills-by-a-specific-member
     *
     * @param string $memberId
     * @param string $type
     * @return mixed
     */
    public function getRecentBillsByMember(string $memberId, string $type)
    {
        $this->formatValidator->isValidMemberIdFormat($memberId);
        $this->billValidator->isValidMemberRecentBillType($type);

        $uriStub = "{$memberId}/bills/{$type}.json";

        return $this->performApiRequest($uriStub);
    }

    /**
     * Get the 20 most recently updated bills for a specific legislative subject
     *
     * https://projects.propublica.org/api-docs/congress-api/bills/#get-recent-bills-by-a-specific-subject
     *
     * @param string $subject
     * @return mixed
     */
    public function getRecentBillsBySubject(string $subject)
    {
        $uriStub = "bills/subjects/{$subject}.json";

        return $this->performApiRequest($uriStub);
    }

    /**
     * Get details on bills that may be considered by the House or Senate in the near future
     *
     * https://projects.propublica.org/api-docs/congress-api/bills/#get-upcoming-bills
     *
     * @param string $chamber
     * @return mixed
     */
    public function getUpcomingBills(string $chamber)
    {
        $this->congressValidator->isValidChamber($chamber);

        $uriStub = "bills/upcoming/{$chamber}.json";

        return $this->performApiRequest($uriStub);
    }

    /**
     * Get details about a particular bill, including actions taken and votes
     *
     * https://projects.propublica.org/api-docs/congress-api/bills/#get-a-specific-bill
     *
     * @param int $congress
     * @param string $billId
     * @return mixed
     */
    public function getSpecificBill(int $congress, string $billId)
    {
        $this->congressValidator->isValidCongress($congress, $this->earliestCongress);
        $this->formatValidator->isAlphanumeric($billId, 'bill ID');

        $uriStub = "{$congress}/bills/{$billId}.json";

        return $this->performApiRequest($uriStub);
    }

    /**
     * Get amendments for a specific bill
     *
     * https://projects.propublica.org/api-docs/congress-api/bills/#get-amendments-for-a-specific-bill
     *
     * @param int $congress
     * @param string $billId
     * @param int $offset
     * @return mixed
     */
    public function getSpecificBillAmendments(int $congress, string $billId, int $offset = 0)
    {
        $this->congressValidator->isValidCongress($congress, $this->earliestCongress);
        $this->formatValidator->isAlphanumeric($billId, 'bill ID');
        $this->paginationValidator->isValidOffset($offset);

        $uriStub = "{$congress}/bills/{$billId}/amendments.json";

        return $this->performApiRequest($uriStub, $offset);
    }

    /**
     * Get Library of Congress-assigned subjects about a particular bill
     *
     * https://projects.propublica.org/api-docs/congress-api/bills/#get-subjects-for-a-specific-bill
     *
     * @param int $congress
     * @param string $billId
     * @param int $offset
     * @return mixed
     */
    public function getSpecificBillSubjects(int $congress, string $billId, int $offset = 0)
    {
        $this->congressValidator->isValidCongress($congress, $this->earliestCongress);
        $this->formatValidator->isAlphanumeric($billId, 'bill ID');
        $this->paginationValidator->isValidOffset($offset);

        $uriStub = "{$congress}/bills/{$billId}/subjects.json";

        return $this->performApiRequest($uriStub, $offset);
    }

    /**
     * @param int $congress
     * @param string $billId
     * @param int $offset
     * @return mixed
     */
    public function getRelatedBillsForSpecificBill(int $congress, string $billId, int $offset = 0)
    {
        $this->congressValidator->isValidCongress($congress, $this->earliestCongress);
        $this->formatValidator->isAlphanumeric($billId, 'bill ID');
        $this->paginationValidator->isValidOffset($offset);

        $uriStub = "{$congress}/bills/{$billId}/related.json";

        return $this->performApiRequest($uriStub, $offset);
    }

    /**
     * Search for bill subjects that contain a specified term
     *
     * https://projects.propublica.org/api-docs/congress-api/bills/#get-a-specific-bill-subject
     *
     * @param string $query
     * @param string $searchType
     * @return mixed
     */
    public function searchForSpecificBillSubject(string $query, string $searchType = 'keyword')
    {
        $this->searchValidator->isValidSearchType($searchType);

        $uriStub = 'bills/subjects/search.json';

        if ($searchType === 'phrase') {
            $query = "'{$query}'";
        }

        $queries = [
            'query' => $query
        ];

        return $this->performApiRequest($uriStub, 0, $queries);
    }

    /**
     * Get information about the cosponsors of a particular bill
     *
     * https://projects.propublica.org/api-docs/congress-api/bills/#get-cosponsors-for-a-specific-bill
     *
     * @param int $congress
     * @param string $billId
     * @return mixed
     */
    public function getSpecificBillCosponsors(int $congress, string $billId)
    {
        $this->congressValidator->isValidCongress($congress, $this->earliestCongress);
        $this->formatValidator->isAlphanumeric($billId, 'bill ID');

        $uriStub = "{$congress}/bills/{$billId}/cosponsors.json";

        return $this->performApiRequest($uriStub);
    }
}
