<?php

class Database
{
private string $host;
private string $name;
private string $user;
private string $password;

    public function __construct(){
        $this->host = "";
        $this->user = "";
        $this->name = "";
        $this->password = "";
    }

    public function getConnection(): mysqli
    {
    return new mysqli($this->host, $this->user, $this->password, $this->name); // maakt connectie
}
}