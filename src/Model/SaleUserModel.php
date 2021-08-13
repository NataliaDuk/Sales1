<?php


namespace App\Model;

use W1020\Table as ORMTable;

class SaleUserModel extends ORMTable
{
    protected int $userId;

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
    `users`.`id` = `sale`.`users_id` 
  AND `sale`.`countries_id` = `countries`.`id` 
  AND `sale`.`produkt_id1` = `produkt`.`id` 
   AND `customers`.`id` = `sale`.`customers_id` 
  AND `users`.`id` = $this->userId

SQL;
//echo $sql;
        return $this->query(
            "$sql LIMIT " . (($page - 1) * $this->pageSize) . ",$this->pageSize;"
        );
    }

    public function rowCount(): int
    {

        return $this->query("SELECT COUNT(*) as COUNT FROM `$this->tableName` WHERE `users_id`=$this->userId")[0]["COUNT"];
    }

    /**
     * @param int $userId
     * @return $this
     */
    public function setUserId(int $userId): static
    {
        $this->userId = $userId;
        return $this;
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
}