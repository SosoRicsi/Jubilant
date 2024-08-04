<?php 
    namespace Jubilant;

    use Jubilant\Database;
    use Jubilant\RandomString;
    use Jubilant\PasswordHash;
    use Jubilant\EmailSender;
    use Jubilant\Superglobals\Session;
    use Jubilant\Superglobals\Cookie;
    use Jubilant\Lang;

    class UserAuth {
        private $con;


        public function __construct() {
            require_once __DIR__.'/../settings.php';
            $this->con = new Database($DatabaseConnection[0], $DatabaseConnection[1], $DatabaseConnection[2], $DatabaseConnection[3]);
            $this->con->connect();
        }

        public function registerUser(string $username, string $email, string $password, string $sessionid,) {
            require_once __DIR__.'/../settings.php';
            Session::init();
            if($sessionid == $_COOKIE['PHPSESSID']) {
                if(empty($username)) {
                    echo Lang::trans('emptyUsernameInput');
                } else if(empty($email)) {
                    echo Lang::trans('emptyEmailInput');
                } else if(empty($password)) {
                    echo $emptyPasswordInput;
                } else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    echo Lang::trans('notValidEmail');
                } else {
                    if($this->con->select("users", "*","`email`='$email'")) {
                        echo Lang::trans('emailAlredyUsed');
                    } else {
                        $PasswordHash = new PasswordHash();
                        $RandomString = new RandomString();
            
                        $hashedPassword = $PasswordHash->passwordHash($password);
                        $customID = $RandomString->generateCustomString('ucid',3,5);

                        $username = htmlspecialchars($customID);
                        $email = htmlspecialchars($email);
            
                        if($this->con->insert("users",array("$customID","$username","$email","$hashedPassword","pending"),array("customID","username","email","password","status"))) {
                            $EmailSender = new EmailSender($EmailServer[0],$EmailServer[1],$EmailServer[2],$EmailServer[3],$EmailServer[4]);
                            $Template = new Template(__DIR__.'/../public/templates/emailTemplates/welcomeNewUser.php');
                            $date = date("Y-m-d H:i:s");
                            $Template->var([
                                'username'      => $username,
                                'email'         => $email,
                                'registerDate'  => $date,
                                'customID'      => $customID,
                                'subject'       => Lang::trans('subjectForRegistration')
                            ]);
                            if($EmailSender->sendMail($email,Lang::trans('subjectForRegistration'),$Template->render())) {
                                echo Lang::trans('registeredSuccessfully');
                            } else {
                                $this->con->delete("users","`email`='$email' AND `customID`='$customID'");
                                echo Lang::trans('registeredUnsuccessfully');
                            }
    
                            return true;
                        }
                    }
                }
            } else {
                echo Lang::trans('invalidPHPsessionID');
            }

            Session::regenerate();

        }

        public function loginUser(string $email, string $password, string $sessionID) {
            require_once __DIR__.'/../settings.php';
            Session::init();
            if($sessionID === $_COOKIE['PHPSESSID']) {
                $PasswordHash = new PasswordHash();
                    
                $selectUserDatas = $this->con->select("users","*","`email`='$email' AND `status`='confirmed'");
                    
                if($selectUserDatas) {
                    if($PasswordHash->passwordVerify($password, $selectUserDatas[0]['password'])) {
        
                        Session::set('customID', $selectUserDatas[0]['customID']);
                        Session::set('username', $selectUserDatas[0]['username']);
                        Session::set('email', $email);
                        Session::set('registered', $selectUserDatas[0]['registered']);
                        Session::set('accountStatus', $selectUserDatas[0]['status']);
                        Session::set('loggedin', true);
    
                        $customID = Session::get('customID');
                        $userBrowser = $_SERVER['HTTP_USER_AGENT'];
    
                        if($this->con->select("lastLogins","*","`userCustomID`='$customID' AND `sessionBrowser`='$userBrowser'")) {
                            echo Lang::trans('userLoginSuccessfully');
                            return true;
                        } else {
                            $userIP = $_SERVER['REMOTE_ADDR'];
                            $sessionID = Session::getSessionID();
    
                            if($this->con->insert("lastLogins",array($customID,$sessionID,$userIP,$userBrowser),array("userCustomID","sessionID","sessionIP","sessionBrowser"))) {
                                if($EmailServer != null) {
                                    $EmailSender = new EmailSender($EmailServer[0],$EmailServer[1],$EmailServer[2],$EmailServer[3],$EmailServer[4]);
    
                                    $Template = new Template(__DIR__.'/../public/templates/emailTemplates/loginFromNewDevice.php');
                                    $Template->var([
                                        'username'  => $selectUserDatas[0]['username'],
                                        'browser'   => $userBrowser,
                                        'ip'        => $userIP,
                                        'date'      => date("Y-m-d H:i:s"),
                                        'subject'   => Lang::trans('subjectForLoginFromNewDevice')
                                    ]);

                                    $subject = Lang::trans('subjectForLoginFromNewDevice');
                                    if($EmailSender->sendMail($email, $subject, $Template->render())) {
                                        echo $userLoginSuccessfully;
                                        $Session->regenerate();
                                        return true;
                                    }
                                }
                            }
                        }
                    } else {
                        echo Lang::trans('invalidPassword');
                        return false;
                    }
                } else {
                    echo Lang::trans('userNotFound');
                    return false;
                }
            } else {
                echo Lang::trans('invalidPHPsessionID');
                return false;
            }
    
            return true;

            Session::regenerate();
    
        }
        
        public function changePassword(string $email, string $newPassword, string $sessionID,) {
            require_once __DIR__.'/../settings.php';
            Session::init();
            if ($sessionID == $_COOKIE['PHPSESSID']) {
                $PasswordHash = new PasswordHash();
                $hashedPassword = $PasswordHash->passwordHash($newPassword);
                if($this->con->update("users",array("password"=>$hashedPassword),array("email"=>$email))) {
                        $EmailSender = new EmailSender($EmailServer[0],$EmailServer[1],$EmailServer[2],$EmailServer[3],$EmailServer[4]);
                    $Template = new Template(__DIR__.'/../public/templates/emailTemplates/passwordChange.php');
                    $Template->var([
                        'email'     => $email,
                        'date'      => date("Y-m-d H:i:s"),
                        'subject'   => Lang::trans('subjectForPasswordchange')
                    ]);

                    $EmailSender->sendMail($email, Lang::trans('subjectForPasswordchange'), $Template->render());
                    echo Lang::trans('passwordSuccessfullyChanged');
                    return true;
                } else {
                    echo Lang::trans('passwordUnsuccessfullyChanged');
                    return false;
                }
            } else {
                echo Lang::trans('invalidPHPsessionID');
                return false;
            }

            $Session->regenerate();
            return true;
        }

        public function confAuth(string $customID) {
            require_once __DIR__.'/../settings.php';
            if($this->con->select("users","*","`customID`='$customID' AND `status`='pending'")) {
                if($this->con->update("users",array('status'=>"confirmed"),array('customID'=>$customID))) {
                    echo Lang::trans('userConfirmedSuccessfully');
                } else {
                    echo Lang::trans('userConfirmedUnsuccessfully');
                    return false;
                }
            } else {
                echo Lang::trans('userNotFound');
                return false;
            }

            return true;
        }

    }
?>