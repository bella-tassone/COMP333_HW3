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
        // Get the request method (GET, POST, DELETE, etc.)
        $requestMethod = $_SERVER["REQUEST_METHOD"];
        if (strtoupper($requestMethod) == 'POST') {
            try {
                $RatingModel = new RatingModel();
                $postData = json_decode(file_get_contents('php://input'), true);

                //make sure that keys exist before assigning
                if (!(array_key_exists('artist', $postData) && array_key_exists('song', $postData) && array_key_exists('rating', $postData))) {
                    $strErrorDesc = "Not all data entered";
                    $strErrorHeader = 'HTTP/1.1 400 Bad Request';
                    }

                else {
                    $username = $postData["username"]; //to be changed to session variable
                    $artist = $postData["artist"];
                    $song = $postData["song"];
                    $rating = $postData["rating"];

                    //check to make sure fields are filled out
                    if (($artist == "") || ($song == "") || ($rating == "")) {
                        $strErrorDesc = "Not all fields filled out";
                        $strErrorHeader = 'HTTP/1.1 400 Bad Request';
                    }
                    //check to make sure rating is an int between 1 and 5
                    elseif ((!is_int($rating)) || ($rating > 5 || $rating < 1)) {

                        $strErrorDesc = "Rating must be an integer between 1 and 5";
                        $strErrorHeader = 'HTTP/1.1 400 Bad Request';

                    //check to make sure that song hasn't already been rated
                    } elseif (!($RatingModel->checkUserCanRate($username, $artist, $song))) {

                        $strErrorDesc = "User already rated this song";
                        $strErrorHeader = 'HTTP/1.1 400 Bad Request';

                    //if all checks pass
                    } else {

                        $RatingModel->createRating($username, $artist, $song, $rating);
                        $responseData = json_encode(["message" => "Rating added successfully"]);
                    }
                }
            } catch (Error $e) {
                $strErrorDesc = $e->getMessage() . ' Something went wrong! Please contact support.';
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

    public function deleteAction()
    {
        $strErrorDesc = '';
        // Get the request method (GET, POST, DELETE, etc.)
        $requestMethod = $_SERVER["REQUEST_METHOD"];
        $arrQueryStringParams = $this->getQueryStringParams();

        // Check if request method is DELETE
        if (strtoupper($requestMethod) == 'DELETE') {
            try {
                $ratingModel = new RatingModel();
                $postData = json_decode(file_get_contents('php://input'), true);


                $id = null;
                if (isset($arrQueryStringParams['id']) && $arrQueryStringParams['id']) {

                    $id = $arrQueryStringParams['id'];
                    $rows = $ratingModel->getRatingFromID($id);
                    //should only be one row since ids are unique
                    $row = $rows[0];
                    $username = $row['username'];
                    $user = $postData['username'];
                    $arrRating = [];
    
                    //make sure rating in question belongs to the logged-in user
                    //conditional currently always returns true, to be compared against session info eventually
                    if ($username == $user) {
                        $arrRating['code'] = $ratingModel->deleteRating($id);
                        $arrRating['message'] = 'Rating deleted successfully';
                        $responseData = json_encode($arrRating);
                    }
                    else {
                        $strErrorDesc = "Not user's rating, update aborted";
                        $strErrorHeader = 'HTTP/1.1 400 Bad Request';
                    }
                }
                else {
                    $strErrorDesc = 'Rating ID not found';
                    $strErrorHeader = 'HTTP/1.1 400 Bad Request';
                }

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

    public function updateAction()
    {
        $strErrorDesc = '';
        // Get the request method (GET, POST, DELETE, etc.)
        $requestMethod = $_SERVER["REQUEST_METHOD"];
        $arrQueryStringParams = $this->getQueryStringParams();

        // Check if request method is DELETE
        if (strtoupper($requestMethod) == 'PUT') {
            try {
                $ratingModel = new RatingModel();
                $postData = json_decode(file_get_contents('php://input'), true);


                $id = null;
                if (isset($arrQueryStringParams['id']) && $arrQueryStringParams['id']) {
                    $id = $arrQueryStringParams['id'];

                    $rows = $ratingModel->getRatingFromID($id);
                    //should only be one row since ids are unique
                    $row = $rows[0];
                    $username = $row['username'];
                    $song = $row['song'];
                    $artist = $row['artist'];
                    $rating = $row['rating'];
                    $user = $postData['username'];
                    $arrRating = [];
    
                    $postData = json_decode(file_get_contents('php://input'), true);
                    $updatedRating = $postData['rating'];
    
                    //make sure rating in question belongs to the logged-in user
                    //conditional currently always returns true, to be compared against session info eventually
                    if ($username == $user) {
                        if ($rating == "") {
                            $strErrorDesc = "Not all fields filled out";
                            $strErrorHeader = 'HTTP/1.1 400 Bad Request';
                        }
                        // shouldn't be able to update rating if no changes are made
                        elseif ($updatedRating == $rating) {
                            $strErrorDesc = "Rating has not been changed";
                            $strErrorHeader = 'HTTP/1.1 400 Bad Request';
                        }
                        elseif (!is_numeric($updatedRating) || $updatedRating < 1 || $updatedRating > 5) {
                            $strErrorDesc = "Rating must be an integer between 1 and 5";
                            $strErrorHeader = 'HTTP/1.1 400 Bad Request';
                            }
                        else {
                            $arrRating['code'] = $ratingModel->updateRating([$song, $artist, $updatedRating, $id]);
                            $arrRating['message'] = 'Rating updated successfully';
                            $responseData = json_encode($arrRating);
                        }

                    }
                    else {
                        $strErrorDesc = "Not user's rating, update aborted";
                        $strErrorHeader = 'HTTP/1.1 400 Bad Request';
                    }
                }
                else {
                    $strErrorDesc = 'Rating ID not found';
                    $strErrorHeader = 'HTTP/1.1 400 Bad Request';
                }

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
}
?>