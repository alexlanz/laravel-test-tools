<?php namespace Krumer\Test\Tools;

trait DatabaseTestToolsTrait {

    /**
     * Begin a new database transaction before each test.
     *
     * @setUp
     */
    public function beginTransaction()
    {
        $this->app['db']->beginTransaction();
    }

    /**
     * Rollback the transaction after each test.
     *
     * @tearDown
     */
    public function rollbackTransaction()
    {
        $this->app['db']->rollback();
    }


    protected function verifyInDatabase($table, array $criteria)
    {
        $this->assertRecordsInDatabase(1, $table, $criteria);
    }

    protected function verifyNotInDatabase($table, array $criteria)
    {
        $this->assertRecordsInDatabase(0, $table, $criteria);
    }

    protected function assertRecordsInDatabase($count, $table, array $criteria = [])
    {
        $query = $this->createDatabaseQuery($table, $criteria);

        $this->assertCount($count, $query->get());
    }

    protected function getDatabaseRecords($table, array $criteria = [])
    {
        $query = $this->createDatabaseQuery($table, $criteria);

        return $query->get();
    }

    protected function getFirstDatabaseRecord($table, array $criteria = [])
    {
        $query = $this->createDatabaseQuery($table, $criteria);

        return $query->first();
    }

    private function createDatabaseQuery($table, $criteria)
    {
        $query = $this->app['db']->table($table);

        foreach ($criteria as $column => $value)
        {
            $query = $query->where($column, $value);
        }

        return $query;
    }

}