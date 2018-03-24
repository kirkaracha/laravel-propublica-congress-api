<?php declare(strict_types=1);

namespace Kirkaracha\PropublicaCongressApi\Services;

use Kirkaracha\PropublicaCongressApi\PropublicaCongressApi;
use Kirkaracha\PropublicaCongressApi\Validators\CongressValidator;
use Kirkaracha\PropublicaCongressApi\Validators\DateValidator;

class FloorActions extends PropublicaCongressApi
{
    /** @var CongressValidator $congressValidator */
    private $congressValidator;

    /** @var DateValidator $dateValidator */
    private $dateValidator;

    public function __construct(
        CongressValidator $congressValidator,
        DateValidator $dateValidator
    )
    {
        $this->congressValidator = $congressValidator;
        $this->dateValidator     = $dateValidator;

        parent::__construct();
    }


    /**
     * Get the latest actions from the House or Senate floor
     *
     * https://projects.propublica.org/api-docs/congress-api/other/#get-recent-house-and-senate-floor-actions
     *
     * @param int $congress
     * @param string $chamber
     * @return mixed
     */
    public function getRecentHouseAndSenateFloorActions(int $congress, string $chamber)
    {
        $earliestCongress = 113;

        $this->congressValidator->isValidCongress($congress, $earliestCongress);
        $this->congressValidator->isValidChamber($chamber);

        $uriStub = "{$congress}/{$chamber}/floor_updates.json";

        return $this->performApiRequest($uriStub);
    }

    /**
     * Get actions from the House or Senate floor on a particular date
     *
     * https://projects.propublica.org/api-docs/congress-api/other/#get-house-and-senate-floor-actions-by-date
     *
     * @param string $chamber
     * @param int $year
     * @param int $month
     * @param int $day
     * @return mixed
     */
    public function getRecentHouseAndSenateFloorActionsByDate(string $chamber, int $year, int $month, int $day)
    {
        $this->congressValidator->isValidChamber($chamber);

        $date = "{$year}-{$month}-{$day}";

        $this->dateValidator->isValidDate($date);

        $uriStub = "{$chamber}/floor_updates/{$year}/{$month}/{$day}.json";

        return $this->performApiRequest($uriStub);
    }
}
