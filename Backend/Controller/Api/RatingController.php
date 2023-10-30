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

                if (!(array_key_exists('username', $postData) && array_key_exists('artist', $postData) && array_key_exists('song', $postData) && array_key_exists('rating', $postData))) {
                    $strErrorDesc = "Not all data entered";
                    $strErrorHeader = 'HTTP/1.1 400 Bad Request';
                    }

                else {
                    $username = $postData["username"];
                    $artist = $postData["artist"];
                    $song = $postData["song"];
                    $rating = $postData["rating"];
                    $userExist = $RatingModel->checkUserExists($username);

                    if (($username == "") || ($artist == "") || ($song == "") || ($rating == "")) {
                        $strErrorDesc = "Not all fields filled out";
                        $strErrorHeader = 'HTTP/1.1 400 Bad Request';
                    }
                    elseif (!$userExist) {
                        $strErrorDesc = "User not in the database";
                        $strErrorHeader = 'HTTP/1.1 400 Bad Request';
                    } 
                    elseif ($rating > 5 || $rating < 0) {

                        $strErrorDesc = "Rating must be between 0 and 5";
                        $strErrorHeader = 'HTTP/1.1 400 Bad Request';

                    } elseif (!$RatingModel->checkUserCanRate($username, $artist, $song)) {

                        $strErrorDesc = "User already rated this song";
                        $strErrorHeader = 'HTTP/1.1 400 Bad Request';

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

                $id = null;
                if (isset($arrQueryStringParams['id']) && $arrQueryStringParams['id']) {
                    $id = $arrQueryStringParams['id'];
                }

                $rows = $ratingModel->getRatingFromID($id);
                //should only be one row since ids are unique
                $row = $rows[0];
                $username = $row['username'];
                $user = null; //session variable
                $arrRating = [];

                //conditional currently always returns true, to be compared against session info eventually
                if ($username == $username) {
                    $arrRating['code'] = $ratingModel->deleteRating($id);
                    if ($arrRating['code']) {
                        $arrRating['message'] = 'Rating deleted successfully';
                    } else {$arrRating['message'] = 'Rating deletion unsuccessful';}
                }
                else {
                    $arrRating['message'] = "Not user's rating, deletion aborted";
                }

                $responseData = json_encode($arrRating);

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

                $id = null;
                if (isset($arrQueryStringParams['id']) && $arrQueryStringParams['id']) {
                    $id = $arrQueryStringParams['id'];
                }

                $rows = $ratingModel->getRatingFromID($id);
                //should only be one row since ids are unique
                $row = $rows[0];
                $username = $row['username'];
                $song = $row['song'];
                $artist = $row['artist'];
                $user = null; //session variable
                $arrRating = [];

                $postData = json_decode(file_get_contents('php://input'), true);
                $updatedRating = $postData[0]['rating'];

                //conditional currently always returns true, to be compared against session info eventually
                if ($username == $username) {
                    $arrRating['code'] = $ratingModel->updateRating([$song, $artist, $updatedRating, $id]);
                    if ($arrRating['code']) {
                        $arrRating['message'] = 'Rating updated successfully';
                    } else {$arrRating['message'] = 'Rating update unsuccessful';}
                }
                else {
                    $arrRating['message'] = "Not user's rating, update aborted";
                }

                $arrRating['info'] = $postData;
                $responseData = json_encode($arrRating);

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