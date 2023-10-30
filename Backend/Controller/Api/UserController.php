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

                if (!(array_key_exists('username', $postData) && array_key_exists('password', $postData))) {
                    $strErrorDesc = "Error: empty data";
                    $strErrorHeader = 'HTTP/1.1 400 Bad Request';
                    } 
                
                else {
                    $username = $postData["username"];
                    $password = $postData["password"];

                    if ($username == "" || $password == "") {
                        $strErrorDesc = "Error: empty data";
                        $strErrorHeader = 'HTTP/1.1 400 Bad Request';
                    } elseif (strlen($password) < 10) {
                        $strErrorDesc = "Password is less than 10 characters";
                        $strErrorHeader = 'HTTP/1.1 400 Bad Request';
                    } elseif ($userModel->checkUserExists($username)) {
                        $strErrorDesc = "Username already exists";
                        $strErrorHeader = 'HTTP/1.1 400 Bad Request';
                    }
                    else{
                        $userModel->createUser($username, $password);
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

                $postData = json_decode(file_get_contents('php://input'), true);
                $username = $postData['username'];
                $password = $postData['password'];

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
                            $_SESSION["loggedin"] = true;
                            $_SESSION["username"] = $user;
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