<?php
class Core
{
    public $db;
    function __construct()
    {
        $this->db = db_connect();
    }
}
