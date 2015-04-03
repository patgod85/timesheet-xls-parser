<?php

namespace Patgod85;


use Patgod85\Entity\Employee;

class Repository
{
    /** @var \PDO */
    private $dbh;

    /** @var array */
    private $dayTypes;

    function __construct($databaseFilePath)
    {
        $this->dbh = new \PDO("sqlite:{$databaseFilePath}");

        $this->setDayTypes();
    }

    private function setDayTypes()
    {
        $query = <<<eot
SELECT UPPER(name), id
FROM day_type
eot;

        $sth = $this->dbh->prepare($query);

        $sth->execute();

        $this->dayTypes = $sth->fetchAll(\PDO::FETCH_KEY_PAIR);
    }

    private function getTypeId($name)
    {
        return $this->dayTypes[
            strtoupper($name)
        ];
    }

    private function clearEmployeesForTeam($teamId)
    {
        $query = <<<eot
DELETE FROM employee
WHERE team_id = ?
eot;

        $sth = $this->dbh->prepare($query);

        $sth->execute([
            $teamId
        ]);
    }

    private function clearDaysForTeam($teamId)
    {
        $query = <<<eot
DELETE FROM employee_day
WHERE employee_id IN (
    SELECT id
    FROM employee
    WHERE team_id = ?
)
eot;

        $sth = $this->dbh->prepare($query);

        $sth->execute([
            $teamId
        ]);
    }

    private function getTeamIdByCode($code)
    {
        $query = <<<eot
SELECT id
FROM team
WHERE code = ?
eot;

        $sth = $this->dbh->prepare($query);

        $sth->execute([
            $code
        ]);

        return $sth->fetchAll(\PDO::FETCH_COLUMN)[0];
    }

    /**
     * @param int $teamId
     * @param Employee[] $employees
     */
    private function insertEmployees($teamId, $employees)
    {
        foreach($employees as $employee)
        {
            $query = <<<eot
INSERT INTO employee (name, surname, work_start, team_id)
VALUES (?, ?, ?, ?)
eot;

            $sth = $this->dbh->prepare($query);

            $sth->execute([
                $employee->getName(),
                $employee->getSurname(),
                $employee->getWorkStart()->format('Y-m-d'),
                $teamId
            ]);

            $employee->setId($this->dbh->lastInsertId());
        }
    }

    /**
     * @param Employee[] $employees
     */
    private function insertDays($employees)
    {
        foreach($employees as $employee)
        {
            $query = <<<eot
INSERT INTO employee_day (employee_id, date, day_type_id)
VALUES
eot;

            $params = [];

            $conditions = [];

            foreach($employee->getDays() as $day)
            {
                $conditions[] = ' (?, ?, ?) ';
                $params = array_merge($params, [
                    $employee->getId(),
                    $day->getDate()->format('Y-m-d'),
                    $this->getTypeId(
                        $day->getType()
                    )
                ]);
            }

            if(!$conditions)
            {
                continue;
            }

            $query .= implode(',', $conditions);

            $sth = $this->dbh->prepare($query);

            $sth->execute($params);
        }
    }

    /**
     * @param $teamCode
     * @param Employee[] $employees
     */
    public function storeEmployees($teamCode, $employees)
    {
        $teamId = $this->getTeamIdByCode($teamCode);

        $this->clearDaysForTeam($teamId);
        $this->clearEmployeesForTeam($teamId);

        $this->insertEmployees($teamId, $employees);

        $this->insertDays($employees);
    }

    /**
     * @param \DateTime[] $publicHolidays
     */
    public function storePublicHolidays($publicHolidays)
    {
        if(!$publicHolidays)
        {
            return;
        }

        $query = <<<eot
INSERT OR IGNORE INTO public_holiday (date)
VALUES
eot;

        $params = [];

        $conditions = [];

        foreach($publicHolidays as $day)
        {
            $conditions[] = ' (?) ';
            $params[] = $day->format('Y-m-d');
        }

        $query .= implode(',', $conditions);

        $sth = $this->dbh->prepare($query);

        $sth->execute($params);
    }
}