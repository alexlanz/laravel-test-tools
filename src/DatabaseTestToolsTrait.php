<?php namespace Krumer\Test\Tools;

trait DatabaseTestToolsTrait {

    /**
     * Begin a new database transaction.
     *
     * @setUp
     */
    public function setUpDatabaseTools()
    {
        $this->app['db']->beginTransaction();
    }

    /**
     * Rollback the transaction.
     *
     * @tearDown
     */
    public function tearDownDatabaseTools()
    {
        $this->app['db']->rollback();
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

    protected function getRecordsInDatabase($table, array $criteria)
    {
        $query = $this->createQueryForCheckOfRecordInDatabase($table, $criteria);
        return $query->get();
    }

    protected function getFirstRecordInDatabase($table, array $criteria)
    {
        $query = $this->createQueryForCheckOfRecordInDatabase($table, $criteria);
        return $query->first();
    }

    private function createQueryForCheckOfRecordInDatabase($table, $criteria)
    {
        $query = $this->app['db']->table($table);

        foreach ($criteria as $column => $value) {
            $query = $query->where($column, $value);
        }

        return $query;
    }

}