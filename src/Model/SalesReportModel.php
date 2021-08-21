<?php

namespace App\Model;

use W1020\Table as ORMTable;


class SalesReportModel extends ORMTable
{

    /**
     * @param int $page
     * @return array<array>
     * @throws \Exception
     */
    public function getFilter(string $startData, string $endData): array
    {
        $sql1 = <<<SQL
SELECT
    `countries`.`name` AS countries_id,
    `produkt`.`name` AS `produkt_id1`,
    SUM(`weight`) AS weight,
    SUM(`cost`) AS cost
  
FROM
    `sale`,
    `countries`,
    `produkt`
WHERE
    `sale`.`countries_id` = `countries`.`id` AND `sale`.`produkt_id1` = `produkt`.`id` 
    AND 
      `data` >= '$startData' AND `data` <= '$endData'


GROUP BY
--     `sale`.`data`,
    `countries`.`name`,
    `produkt`.`name`

ORDER BY
     `countries`.`name` ASC, `produkt`.`name` ASC

SQL;
        return $this->query($sql1);
    }

        public function getFilter2(string $startData, string $endData, string $countries): array
    {
        $sql = <<<SQL
SELECT
    `countries`.`name` AS countries_id,
    `produkt`.`name` AS `produkt_id1`,
    SUM(`weight`) AS weight,
    SUM(`cost`) AS cost
  
FROM
    `sale`,
    `countries`,
    `produkt`
WHERE
    `sale`.`countries_id` = `countries`.`id` AND `sale`.`produkt_id1` = `produkt`.`id` 
    AND 
      `data` >= '$startData' AND `data` <= '$endData'
 AND 
     `countries`.`name` = '$countries'
GROUP BY
--     `sale`.`data`,
    `countries`.`name`,
    `produkt`.`name`

ORDER BY
     `countries`.`name` ASC, `produkt`.`name` ASC

SQL;
        return $this->query($sql);
    }
    public function getFilter3(string $startData, string $endData, string $produkt): array
    {
        $sql = <<<SQL
SELECT
    `countries`.`name` AS countries_id,
    `produkt`.`name` AS `produkt_id1`,
    SUM(`weight`) AS weight,
    SUM(`cost`) AS cost
  
FROM
    `sale`,
    `countries`,
    `produkt`
WHERE
    `sale`.`countries_id` = `countries`.`id` AND `sale`.`produkt_id1` = `produkt`.`id` 
    AND 
      `data` >= '$startData' AND `data` <= '$endData'
 AND 
     `produkt`.`name` = '$produkt'
GROUP BY
    `countries`.`name`,
    `produkt`.`name`

ORDER BY
     `countries`.`name` ASC, `produkt`.`name` ASC

SQL;
        return $this->query($sql);
    }

}