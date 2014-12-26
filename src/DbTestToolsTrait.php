<?php namespace Tests\Tools;


trait DbTestToolsTrait {

    protected $db;


    protected function setUpDatabaseTools()
    {
        $this->db = $this->app->make('db');
        $this->db->beginTransaction();
    }

    protected function tearDownDatabaseTools()
    {
        $this->db->rollback();
    }


    protected function checkRecordInDatabase($table, array $criteria)
    {
        $this->countRecordInDatabase(1, $table, $criteria);
    }

    protected function checkRecordNotInDatabase($table, array $criteria)
    {
        $this->countRecordInDatabase(0, $table, $criteria);
    }

    protected function countRecordInDatabase($count, $table, array $criteria)
    {
        $query = $this->createQueryForCheckOfRecordInDatabase($table, $criteria);
        $this->assertCount($count, $query->get());
    }

    private function createQueryForCheckOfRecordInDatabase($table, $criteria)
    {
        $query = $this->db->table($table);

        foreach ($criteria as $column => $value) {
            $query = $query->where($column, $value);
        }

        return $query;
    }

}