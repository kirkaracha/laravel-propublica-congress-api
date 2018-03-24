<?php declare(strict_types=1);

namespace Kirkaracha\PropublicaCongressApi\Validators;

use DateTime;
use InvalidArgumentException;

class DateValidator
{
    /**
     * @param string $month
     * @throws InvalidArgumentException
     */
    public function isValidMonthFormat(string $month)
    {
        $regex = '^0[1-9]|1[012]$^';

        if (! preg_match_all($regex, $month)){
            throw new InvalidArgumentException("{$month} isn't a valid month format");
        }
    }

    /**
     * @param string $year
     * @throws InvalidArgumentException
     */
    public function isValidYearFormat(string $year)
    {
        $regex = '^(?:19|20)\d{2}$^';

        if (! preg_match_all($regex, $year)){
            throw new InvalidArgumentException("{$year} isn't a valid year format");
        }
    }

    /**
     * @param string $date
     * @throws InvalidArgumentException
     */
    public function isValidDate(string $date)
    {
        if (! $this->validateDate($date)) {
            throw new InvalidArgumentException("{$date} isn't a valid date format");
        }
    }

    /**
     * @param string $startDate
     * @param string $endDate
     * @throws InvalidArgumentException
     */
    public function isValidDateRange(string $startDate, string $endDate)
    {
        $this->isValidDate($startDate);
        $this->isValidDate($endDate);

        $startDateTime = new DateTime($startDate);
        $endDateTime   = new DateTime($endDate);

        if ($startDateTime->diff($endDateTime)->days > 30) {
            throw new InvalidArgumentException("The date range must be 30 days or less");
        }
    }

    /**
     * @param $date
     * @param string $format
     * @return bool
     */
    private function validateDate($date, $format = 'Y-m-d'): bool
    {
        $formattedDate = DateTime::createFromFormat($format, $date);

        return $formattedDate && $formattedDate->format($format) === $date;
    }
}
