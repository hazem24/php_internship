<?php
        namespace App\DomainHelper;
        use Framework\Registry;
        use Framework\Request\RequestHandler;

        
        /**
        *Provide Some Helper Function Can Be Used In The Domain Logic App
        */

        Class Helper
        {
            public static function redirectOutSide(string $sessionName = 'id' , string $direction = ""){
                   $sessionInstance = Registry::getInstance('session');
                   if($sessionInstance->getSession($sessionName) === false){
                        // Redirect User 
                        $sessionInstance->saveAndCloseSession();
                        header("Location:$direction");
                        exit;
                   }     
            }

            public static function redirectInside(string $sessionName = 'id' , string $direction = ""){
                        $sessionInstance = Registry::getInstance('session');
                        if($sessionInstance->getSession($sessionName)){
                        // Redirect User 
                        $sessionInstance->saveAndCloseSession();
                        header("Location:$direction");
                        exit;
                   }

            }
            /**
            *@method ajaxRequest This Method Handle The Logic To Check If The Coming Request Is Ajax Or Normal 
            *@return bool 
            *If @return true This Mean The InComing Request Is Ajax Request 
            *If @return false This Mean The Incoming Request Without Ajax 
            */
            public static function ajaxRequest():bool{
                   if(!empty(RequestHandler::post('ajax'))){
                        return true;
                   }
                   if(!empty(RequestHandler::get('ajax'))){
                        return true;
                   }

                   return false; 

            }
            /**
            *@method noJs This Function Stop Execute The Script In Case Of User Stop JavaScript In Website 
            */
            public static function noJs(){
                      require(VIEWS_PATH . "NoJs/noJs.html");
                      exit;
            }

            /**
            *@method getMailerService This Method Just Take The Library Name And Return Full Path 
            *To Reach This Library  Service
            *@used At Registery Command
            */
            public static function getMailerService($lib){

                $serviceName =    "Framework\\Lib\\Mailer\\Service\\". get_class($lib) . "Service";
                return $serviceName;
            }

            /**
            *@method smartResponse This Function Handle The Response Will Be For Ajax Or Pure Php
            *Not Now @writeen 05/07/2017 @06:51
            */

            /*public  static function smartResponse(){

            }*/
        }