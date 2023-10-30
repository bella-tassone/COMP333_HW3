<?php
class RatingController extends BaseController
{
    // to be populated with rating-related info

    public function getAction()
    {
        $strErrorDesc = '';
        $requestMethod = $_SERVER["REQUEST_METHOD"];
        $arrQueryStringParams = $this->getQueryStringParams();
        if (strtoupper($requestMethod) == 'GET') {
            try {
                $ratingModel = new RatingModel();
                $intLimit = 10;
                if (isset($arrQueryStringParams['limit']) && $arrQueryStringParams['limit']) {
                    $intLimit = $arrQueryStringParams['limit'];
                }
                $arrRatings = $ratingModel->getRatingList($intLimit);
                $responseData = json_encode($arrRatings);
            } catch (Error $e) {
                $strErrorDesc = $e->getMessage().'Something went wrong! Please contact support.';
                $strErrorHeader = 'HTTP/1.1 500 Internal Server Error';
            }
        } else {
            $strErrorDesc = 'Method not supported';
            $strErrorHeader = 'HTTP/1.1 422 Unprocessable Entity';
        }
        // send output 
        if (!$strErrorDesc) {
            $this->sendOutput(
                $responseData,
                array('Content-Type: application/json', 'HTTP/1.1 200 OK')
            );
        } else {
            $this->sendOutput(json_encode(array('error' => $strErrorDesc)), 
                array('Content-Type: application/json', $strErrorHeader)
            );
        }
    }
    
    public function createAction()
    {
        // Get the request method (GET, POST, DELETE, etc.)
        $requestMethod = $_SERVER["REQUEST_METHOD"];

        // Check if request method is POST
        if (strtoupper($requestMethod) == 'POST') {

            // retrieve user registration data from request body
            $postData = json_decode(file_get_contents('php://input'), true);
            $username = //session info
            $song = $postData[1];
            $artist = $postData[2];
            // instatiate a RatingModel to create the rating
            $userModel = new RatingModel();
            $row = $userModel->getRating([$username, $song, $artist]);
            if (is_null($row)) {
                $userModel->createRating($postData);
            }
        }
    }

    public function deleteAction()
    {
        // Get the request method (GET, POST, DELETE, etc.)
        $requestMethod = $_SERVER["REQUEST_METHOD"];
        $arrQueryStringParams = $this->getQueryStringParams();

        // Check if request method is POST
        if (strtoupper($requestMethod) == 'DELETE') {
            $ratingModel = new RatingModel();

            $id = null;
            if (isset($arrQueryStringParams['id']) && $arrQueryStringParams['id']) {
                $id = $arrQueryStringParams['id'];
            }

            $row = $ratingModel->getRatingFromID($id);
            $username = $row['username'];
            $user = //session variable

            if ($username == $user) {
                $userModel->deleteRating($id);
            }
        }
    }

    public function updateAction()
    {
        // Get the request method (GET, POST, DELETE, etc.)
        $requestMethod = $_SERVER["REQUEST_METHOD"];
        $arrQueryStringParams = $this->getQueryStringParams();

        // Check if request method is POST
        if (strtoupper($requestMethod) == 'PUT') {
            $ratingModel = new RatingModel();

            $id = null;
            if (isset($arrQueryStringParams['id']) && $arrQueryStringParams['id']) {
                $id = $arrQueryStringParams['id'];
            }

            $row = $ratingModel->getRatingFromID($id);
            $username = $row['username'];
            $user = //session variable

            if ($username == $user) {
                $userModel->updateRating($id);
            }
        }
    }
}
?>