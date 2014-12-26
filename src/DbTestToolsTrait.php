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


    protected function checkRecordInDatabase($table, $criteria)
    {
        $query = $this->createQueryForCheckOfRecordInDatabase($table, $criteria);
        $this->assertCount(1, $query->get());
    }

    protected function checkRecordNotInDatabase($table, $criteria)
    {
        $query = $this->createQueryForCheckOfRecordInDatabase($table, $criteria);
        $this->assertCount(0, $query->get());
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