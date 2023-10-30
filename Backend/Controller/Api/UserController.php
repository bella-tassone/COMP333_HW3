<?php
class UserController extends BaseController
{
    public function getAction()
    {
        $strErrorDesc = '';
        $requestMethod = $_SERVER["REQUEST_METHOD"];
        $arrQueryStringParams = $this->getQueryStringParams();
        if (strtoupper($requestMethod) == 'GET') {
            try {
                $userModel = new UserModel();
                $intLimit = 10;
                if (isset($arrQueryStringParams['limit']) && $arrQueryStringParams['limit']) {
                    $intLimit = $arrQueryStringParams['limit'];
                }
                $arrUser = $userModel->getUserList($intLimit);
                $responseData = json_encode($arrUser);
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
            // instatiate a UserModel to create the user
            $userModel = new UserModel();
            $userModel->createUser($postData);
        }
    }

    public function loginAction()
    {
        $strErrorDesc = '';
        $requestMethod = $_SERVER["REQUEST_METHOD"];
        if (strtoupper($requestMethod) == 'GET') {
            try {
                $userModel = new UserModel();
                $arrUser = [];

                $postData = json_decode(file_get_contents('php://input'), true);
                $username = $postData[0]['username'];
                $password = $postData[0]['password'];

                $rows = $userModel->getUserPassword($username);
                //if username doesn't match, row will be null
                $row = $rows[0];

                if(!is_null($row)) {
                    $hashed_pass = $row["password"];
                    $password_match = password_verify($password, $hashed_pass);

                    if($password_match) {
                        // success! user should be logged in
                        $_SESSION["loggedin"] = true;
                        $_SESSION["username"] = $user;
                        $arrUser['message'] = 'login successful!';
                    }
                }

                $responseData = json_encode($arrUser);
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