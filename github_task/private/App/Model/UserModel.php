<?php
            namespace App\Model;
            use Framework\Shared\Model;
            use App\DomainMapper\LoginMapper;

            Class UserModel extends Model
            {
                /**
                *Table Contents Of Post
                */
                private $id;
                private $email;

                public function getFinder():LoginMapper{
                     return new LoginMapper;
                }
                public function getPoints(){
                     return (int)$this->points;
                }
                public function getProxyNumber(){
                     return $this->proxyNumber; 
                }
                public function get(){
                     return (int)$this->UnUsedEmailsNumber; 
                }
                public function getTwAccounts(){
                     return (int)$this->tw_accountsNumber; 
                }
                public function getEmail(){
                     return (string)$this->email; 
                }
                public function getId(){
                     return (int)$this->id; 
                }
            }

