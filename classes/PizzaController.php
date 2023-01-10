<?php

class PizzaController
{
    public function __construct(private PizzaGateway $gateway) // zorgt ervoor dat je de gateway kan gebruiken
    {
    }

    public function checkRequestKind(string $method, ?string $id, ?string $remove) :void // kijkt naar wat je wilt bereiken
    {

        if($remove=="removeAll"){ // als je alles wilt verwijderen
            $this->gateway->deleteAll();
        }
        else if($id&&$remove!=null){
           $this->specific($method, $id); // verwijder item met dit specifiek id
        }
        else if($id!=null){
            $this->specific($method, $id); // doe iets anders met de order die bij het gegeven id hoort
        }
        else{
           $this->broad($method); // alles waar je geen specifiek id voor nodig hebt, nieuwe data/alle orders opvragen
        }
    }

    private function deleteItem(string $id)
    {
        if(strlen($id)==5){ // gegeven id moet 5 karakters lang zijn
            echo json_encode($this->gateway->delete($id)); // verwijder als het id in de database staat
        }
        else{ // errormessage
            echo "make sure it's the correct orderID";
            exit();
        }
    }

    private function specific(string $method, string $id){
        switch ($method){
            case "GET": // geeft je de data die bij een specifiek id hoort
                echo json_encode($this->gateway->get($id));
                break;
            case "PATCH":
                $data = (array) json_decode(file_get_contents("php://input")); // store alle gegeven data in var "data"

                $errors = $this->getUpdateErrors($data); // kijk of er verkeerde data is gegeven
                if(! empty($errors)){ // als er errors zijn
                    http_response_code(422);
                    echo json_encode(["errors"=> $errors]);
                    break;
                }

                $id = $this->gateway->update($id, $data["type"]); // zorgt voor het opslaan van de data

                http_response_code(201); // respond code voor wanneer een nieuw iets is gecreëerd
                echo json_encode([
                    "message" => "order is opgeslagen",
                    "id" => $id
                ]);
                break;
            case "DELETE":
                $this->deleteItem($id); // delete a specific item
                break;
            default:
                http_response_code(405);
                header("ALLOW: GET, DELETE, PATCH");
        }
    }

    private function broad($method){
        switch ($method){
            case "GET":
                echo json_encode($this->gateway->getAll());
                break;
            case "POST":
                $data = (array) json_decode(file_get_contents("php://input")); // store alle gegeven data in var "data"

                $errors = $this->getPostErrors($data); // kijk of er verkeerde data is gegeven
                if(! empty($errors)){
                    http_response_code(422);
                    echo json_encode(["errors"=> $errors]);
                    break;
                }

                $id = $this->gateway->create($data); // zorgt voor het opslaan van de data

                http_response_code(201); // respond code voor wanneer er een order is geplaatst
                echo json_encode([
                    "message" => "order is opgeslagen",
                    "id" => $id
                ]);
                break;
            case "DELETE":
                $this->gateway->deleteAll(); // verwijder alle orders
                break;
            default:
                http_response_code(405);
                header("ALLOW: GET, POST, DELETE");
        }
    }

    private function getPostErrors(array $data): array
    {
        $errors = [];

        if(empty($data['type'])) { // als de gegeven data geen type pizza bevat
            $errors[]="make sure to specify a type"; // error message
        }

        if(empty($data["orderID"])) { // als de gegeven data geen orderID heeft
            $errors[] = "no order ID"; // error message
        }

        if(array_key_exists("orderID", $data)) { // als de gegeven data een orderID heeft
            if(filter_var($data["orderID"], FILTER_VALIDATE_INT) === false){ // check of het gegeven ID een int is
                $errors[] = "orderID must be an integer"; //error message
            }
        }

        return $errors;
    }

    private function getUpdateErrors(array $data): array
    {
        $errors = [];

        if(empty($data)) { // als de data geen type pizza bevat
            $errors[]="make sure to specify the correct type of pizza"; // error message
        }

        return $errors; // return alle errors
    }
}