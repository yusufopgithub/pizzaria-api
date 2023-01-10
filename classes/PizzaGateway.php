<?php

class PizzaGateway
{
    private mysqli $conn; // is de connectie met de database

    public function __construct(Database $database)
    {
        $this->conn = $database->getConnection();
    }

    public function getAll() : array
    {
        $sql = "SELECT *
                FROM orders"; // selecteerd alle orders in de database

        $result = $this->conn->query($sql);

        $data = [];

        while ($row = $result->fetch_assoc()){
            $data[] = $row;
        }

        return $data;
    }

    public function deleteAll() : string
    {
        $sql = "DELETE FROM orders"; // verwijderd alles in de "orders" tabel

        if($this->conn->query($sql)===true){
            return "data deleted successfully";
        }
        else{
            return "Error: " . $sql . "<br>" . $this->conn->error;
        }
    }

    public function create(array $data): string
    {
        $ID = $data["orderID"];
        $pizzaType = $data["type"];

        $sql = "INSERT INTO orders (orderID, type)
                VALUES ($ID, '$pizzaType')";

        if($this->conn->query($sql) === true){ // query
            return "order placed successfully";
        } else {
            return "Error: " . $sql . "<br>" . $this->conn->error;
        }
}

    public function get(int $orderID) : array | false
    {
        $sql = "SELECT * 
                FROM orders 
                WHERE orderID = $orderID";

        $result = $this->conn->query($sql);

       $row = $result->fetch_assoc();

        if(empty($row)){
            return false;
        }

        return $row;
    }

    public function update(int $id, string $type): string
    {
        $sql = "UPDATE orders SET type='$type' WHERE id=$id";

        if($this->conn->query($sql)===true){
            return "record updated successfully";
        }
        else{
            return "Error: " . $sql . "<br>" . $this->conn->error;
        }
    }

    public function delete(int $orderID): string
    {
        $sql = "DELETE FROM orders WHERE orderID=$orderID";

        if($this->conn->query($sql)===true){
            return "record deleted successfully";
        }
        else{
            return "Error: " . $sql . "<br>" . $this->conn->error;
        }
    }
}