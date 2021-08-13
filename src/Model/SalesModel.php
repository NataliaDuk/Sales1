<?php


namespace App\Model;

use W1020\Table as ORMTable;

class SalesModel extends ORMTable
{
    /**
     * @param int $page
     * @return array<array>
     * @throws \Exception
     */
    public function getPage(int $page = 1): array
    {
        $sql = <<<SQL
SELECT
    `sale`.`id`,
    `sale`.`data`,
    `customers`.`name` AS `customers_id`,
    `users`.`name` AS `users_id`,
    `countries`.`name` AS `countries_id`,
    `produkt`.`name` AS `produkt_id1`,
    `sale`.`weight`,
    `sale`.`cost`
FROM
    `sale`,
     `users`,
     `customers`,
     `countries`,
    `produkt`
WHERE
    `users`.`id` = `sale`.`users_id` AND `sale`.`countries_id` = `countries`.`id` AND `sale`.`produkt_id1` = `produkt`.`id` AND `customers`.`id` = `sale`.`customers_id`

SQL;

        return $this->query(
            "$sql LIMIT " . (($page - 1) * $this->pageSize) . ",$this->pageSize;"
        );
    }

    /**
     * @return array <int|string, array>
     * @throws \Exception
     */
    public function getGroupListUsers() : array
    {
        $data = $this->query("SELECT `id`,`name` FROM `users`");
        $arr = [];
        foreach ($data as $row) {
            $arr[$row['id']] = $row['name'];
        }
        return $arr;
    }
    public function getGroupListCountries() : array
    {
        $data = $this->query("SELECT `id`,`name` FROM `countries`");
        $arr = [];
        foreach ($data as $row) {
            $arr[$row['id']] = $row['name'];
        }
        return $arr;
    }
    public function getGroupListProdukt() : array
    {
        $data = $this->query("SELECT `id`,`name` FROM `produkt`");
        $arr = [];
        foreach ($data as $row) {
            $arr[$row['id']] = $row['name'];
        }
        return $arr;
    }
    public function getGroupListCustomers() : array
    {
        $data = $this->query("SELECT `id`,`name` FROM `customers`");
        $arr = [];
        foreach ($data as $row) {
            $arr[$row['id']] = $row['name'];
        }
        return $arr;
    }
//    public function getFormCountries();
}