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

}