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
        $strErrorDesc = '';
        $requestMethod = $_SERVER["REQUEST_METHOD"];
        if (strtoupper($requestMethod) == 'POST') {
            try {
                $postData = json_decode(file_get_contents('php://input'), true);

                if (!(array_key_exists('username', $postData) && array_key_exists('artist', $postData) && array_key_exists('song', $postData) && array_key_exists('rating', $postData))) {
                    $strErrorDesc = "Not all data entered";
                    $strErrorHeader = 'HTTP/1.1 400 Bad Request';
                    }

                else{
                    $username = $postData["username"];
                    $artist = $postData["artist"];
                    $song = $postData["song"];
                    $rating = $postData["rating"];

                    if (($username == "") || ($artist == "") || ($song == "") || ($rating == "")) {
                        $strErrorDesc = "Not all fields have value";
                        $strErrorHeader = 'HTTP/1.1 400 Bad Request';
                    }

                else{
                    $userModel = new UserModel();
                    $userExist = $userModel->checkUserExists($username);

                    if (!$existsResult) {
                        $strErrorDesc = "User not in database";
                        $strErrorHeader = 'HTTP/1.1 400 Bad Request'; 
                    }

                    else{

                        if ($rating > 5 || $rating < 0) {
                            $strErrorDesc = "Rating must be between 1 and 5";
                            $strErrorHeader = 'HTTP/1.1 400 Bad Request';
                        }

                        

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
            $user = null; //session variable

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
            $user = null; //session variable

            if ($username == $user) {
                $userModel->updateRating($id);
            }
        }
    }
}
?>