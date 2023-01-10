
# api for a pizzaria

This API allows clients to interact with a database of pizza orders. The API provides a set of endpoints that clients can send HTTP requests to in order to retrieve, create, update, and delete orders. The API uses a PizzaController class to handle incoming requests and determine what actions to take based on the request method and parameters. The PizzaController class then uses a PizzaGateway class to interact with the database and perform the necessary actions.


When the client sends a request to the API, the PizzaController class checks the request method and parameters to determine what action to take. Depending on the request method and parameters, the PizzaController class may call the appropriate method of the PizzaGateway class to interact with the database. The PizzaGateway class uses prepared statements and mysqli to interact with the database and perform the necessary actions.

The "specific" function handles the following HTTP methods:

GET: Retrieves the data that corresponds to a specific id, and returns it as a JSON encoded string.

PATCH: Updates the data that corresponds to a specific id. It first decodes the JSON data that was sent in the request body, and then checks for any errors in the data using the "getUpdateErrors" function. If there are any errors, it returns a JSON encoded string with an HTTP status code of 422 (Unprocessable Entity). If there are no errors, it saves the data, and returns a JSON encoded string with a message and the id of the updated data, along with an HTTP status code of 201 (Created).

DELETE: Deletes a specific item

Default: Returns an HTTP status code of 405 (Method Not Allowed) and a "ALLOW" header with the allowed methods.

The "broad" function handles the following HTTP methods:

GET: Retrieves all data and returns it as a JSON encoded string.

POST: Creates a new resource. It first decodes the JSON data that was sent in the request body, and then checks for any errors in the data using the "getPostErrors" function. If there are any errors, it returns a JSON encoded string with an HTTP status code of 422 (Unprocessable Entities). If there are no errors, it saves the data, and returns a JSON encoded string with a message and the id of the new data, along with an HTTP status code of 201 (Created).

DELETE: Deletes all resources

Default: Returns an HTTP status code of 405 (Method Not Allowed) and a "ALLOW" header with the allowed methods.


This API may be useful in a scenario where you want to manage a database of pizza orders, it could be integrated in a website, mobile app or even in a restaurant management system. It allows you to read and manipulate data in a database with a consistent format, giving more flexibility to your application.