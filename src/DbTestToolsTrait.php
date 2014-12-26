<?php namespace Tests\Tools;


trait DbTestToolsTrait {

    protected $db;

    public function setUpDatabaseTools()
    {
        $this->db = $this->app->make('db');
        $this->db->beginTransaction();

        $this->setupMailcatcher();
        $this->clearMails();
    }

    public function tearDownDatabaseTools()
    {
        $this->db->rollback();
    }

}