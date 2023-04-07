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

        $stmt = $this->conn->prepare($sql);
        $stmt->execute();

        $result = $stmt->get_result();

        $data = [];

        while ($row = $result->fetch_assoc()){
            $data[] = $row;
        }

        return $data;
    }

    public function deleteAll() : string
    {
        $sql = "DELETE FROM orders"; // verwijderd alles in de "orders" tabel

        $stmt = $this->conn->prepare($sql);

        if($stmt->execute()){
            return "data deleted successfully";
        }
        else{
            return "Error: " . $sql . "<br>" . $stmt->error;
        }
    }

    public function create(array $data): string
    {
        $ID = $data["orderID"];
        $pizzaType = $data["type"];

        $sql = "INSERT INTO orders (orderID, type)
                VALUES (?, ?)";

        $stmt = $this->conn->prepare($sql);

        $stmt->bind_param("is", $ID, $pizzaType);

        if($stmt->execute()){ // query
            return "order placed successfully";
        } else {
            return "Error: " . $sql . "<br>" . $stmt->error;
        }
    }

    public function get(int $orderID) : array | false
    {
        $sql = "SELECT * 
                FROM orders 
                WHERE orderID = ?";

        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $orderID);
        $stmt->execute();

        $result = $stmt->get_result();

        $row = $result->fetch_assoc();

        if(empty($row)){
            return false;
        }

        return $row;
    }

    public function update(int $id, string $type): string
    {
        $sql = "UPDATE orders SET type=? WHERE id=?";

        $stmt = $this->conn->prepare($sql);

        $stmt->bind_param("si", $type, $id);

        if($stmt->execute()){
            return "record updated successfully";
        }
        else{
            return "Error: " . $sql . "<br>" . $stmt->error;
        }
    }

    public function delete(int $orderID): string
    {
        $sql = "DELETE FROM orders WHERE orderID=?";

        $stmt = $this->conn->prepare($sql);

        $stmt->bind_param("i", $orderID);

        if($stmt->execute()){
            return "record deleted successfully";
        }
        else{
            return "Error: " . $sql . "<br>" . $stmt->error;
        }
    }
}
