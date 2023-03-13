<?php
declare(strict_types=1);

namespace Task3;

class RecordDateTrip
{
    private \DateTimeImmutable $departureDate;
    private \DateTimeZone $departureTimeZone;
    private \DateTimeImmutable $arrivalDate;
    private \DateTimeZone $arrivalTimeZone;
    private int $secondsTrip;

    public function __construct(string $data)
    {
        $explodeData = explode(" ", $data);
        if(count($explodeData) != 4)
        {
            throw new \Exception("Во входной строке не хватает параметров");
        }

        // добавление + в положительную тайм зону
        if((int)$explodeData[1] >= 0)
        {
            $explodeData[1] = "+" . $explodeData[1];
        }
        if((int)$explodeData[3] >= 0)
        {
            $explodeData[3] = "+" . $explodeData[3];
        }

        $this->departureTimeZone = new \DateTimeZone($explodeData[1]);
        $this->arrivalTimeZone = new \DateTimeZone($explodeData[3]);

        $tmpDepDate = implode(
            " ", 
            explode("_", $explodeData[0])
        );
        $tmpArrDate = implode(
            " ", 
            explode("_", $explodeData[2])
        );

        $this->departureDate = new \DateTimeImmutable($tmpDepDate, $this->departureTimeZone);
        $this->arrivalDate = new \DateTimeImmutable($tmpArrDate, $this->arrivalTimeZone);

        $diff = $this->arrivalDate
            ->setTimezone(new \DateTimeZone('UTC'))
            ->diff($this->departureDate->setTimezone(new \DateTimeZone('UTC')));

        $this->secondsTrip = ($diff->y * 365 * 24 * 60 * 60) +
            ($diff->m * 30 * 24 * 60 * 60) +
            ($diff->d * 24 * 60 * 60) +
            ($diff->h * 60 * 60) +
            ($diff->i * 60) +
            $diff->s;
    }

    public function getSeconds()
    {
        return $this->secondsTrip;
    }
}