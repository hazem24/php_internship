<?php
        namespace App\Commands;
        use Framework\Shared\AbstractCommand;
        use App\DomainMapper\SignMapper;
        /**
        *@class SignupCommand This Class Handle The Logic Of Sign Up Users To System 
        **/

        Class LoginCommand extends AbstractCommand
        {
            private $signMapper;

            public function __construct(){
                $this->signMapper = new signMapper;
            }

            public function execute(array $userData = []){
                return $this->addUser($userData['ip']);
            }

            private function addUser(string $ipAddress){
                    $accessCode = $this->randAccessCode();
                    $result = $this->signMapper->newUser($accessCode,$accessCode);
                    return $result;
            }
        }