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
            $username = null; //session info
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