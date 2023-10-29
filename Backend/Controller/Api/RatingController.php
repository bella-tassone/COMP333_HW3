<?php
class RatingController extends BaseController
{
    // to be populated with rating-related info
    public function createAction()
    {
        // Get the request method (GET, POST, DELETE, etc.)
        $requestMethod = $_SERVER["REQUEST_METHOD"];

        // Check if request method is POST
        if (strtoupper($requestMethod) == 'POST') {

            // retrieve user registration data from request body
            $postData = json_decode(file_get_contents('php://input'), true);
            // instatiate a RatingModel to create the RATING
            $userModel = new RatingModel();
            $userModel->createRating($postData);
        }
    }

    public function deleteAction()
    {
        // Get the request method (GET, POST, DELETE, etc.)
        $requestMethod = $_SERVER["REQUEST_METHOD"];

        // Check if request method is POST
        if (strtoupper($requestMethod) == 'POST') {

            $id = $_POST['id'];
            // instatiate a RatingModel to delete the rating
            $userModel = new RatingModel();
            $userModel->deleteRating($id);
        }
    }
}