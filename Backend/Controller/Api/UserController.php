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
        $strErrorDesc = '';
        $requestMethod = $_SERVER["REQUEST_METHOD"];

        if (strtoupper($requestMethod) == 'POST') {
            try {
                $userModel = new UserModel();
                $postData = json_decode(file_get_contents('php://input'), true);

                if (!(array_key_exists('username', $postData) && array_key_exists('password1', $postData) && array_key_exists('password2', $postData))) {
                    $strErrorDesc = "Error: empty data";
                    $strErrorHeader = 'HTTP/1.1 400 Bad Request';
                    } 
                
                else {
                    $username = $postData["username"];
                    $password1 = $postData["password1"];
                    $password2 = $postData["password2"];


                    if ($username == "" || $password1 == "" || $password2 == "") {
                        $strErrorDesc = "Error: empty data";
                        $strErrorHeader = 'HTTP/1.1 400 Bad Request';
                    } elseif (strlen($password1) < 10) {
                        $strErrorDesc = "Password is less than 10 characters";
                        $strErrorHeader = 'HTTP/1.1 400 Bad Request';
                    } elseif ($userModel->checkUserExists($username)) {
                        $strErrorDesc = "Username already exists";
                        $strErrorHeader = 'HTTP/1.1 400 Bad Request';
                    }
                    elseif ($password1 != $password2) {
                        $strErrorDesc = "Passwords do not match!";
                        $strErrorHeader = 'HTTP/1.1 400 Bad Request';
                    }
                    else{
                        $userModel->createUser($username, $password1);
                        $userCreated = true;
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

        if (!$strErrorDesc) {
            $responseData = json_encode(["message" => "Data successfully processed"]);
            $this->sendOutput(
                $responseData,
                array('Content-Type: application/json', 'HTTP/1.1 200 OK')
            );
        } else {
            $this->sendOutput(json_encode(['error' => $strErrorDesc]), 
                array('Content-Type: application/json', $strErrorHeader)
            );
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

                $username = $_GET['username'];
                $password = $_GET['password'];

                if ($username == "" || $password == "") {
                    $strErrorDesc = "Not all fields filled out";
                    $strErrorHeader = 'HTTP/1.1 400 Bad Request';
                }
                else {
                    $rows = $userModel->getUserPassword($username);
                    //if username doesn't match, row will be null
                    $row = $rows[0];

                    if(!is_null($row)) {
                        $hashed_pass = $row["password"];
                        $password_match = password_verify($password, $hashed_pass);

                        if($password_match) {
                            // success! user should be logged in
                            $arrUser['message'] = 'login successful!';
                            $responseData = json_encode($arrUser);
                        }
                        else {
                            $strErrorDesc = "Username or password is incorrect.";
                            $strErrorHeader = 'HTTP/1.1 400 Bad Request';
                        }
                    }
                    else {
                        $strErrorDesc = "Username or password is incorrect.";
                        $strErrorHeader = 'HTTP/1.1 400 Bad Request';
                    }
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