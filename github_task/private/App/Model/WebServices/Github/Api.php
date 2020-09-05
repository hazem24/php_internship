<?php

    namespace App\Model\WebServices\Github;
    use Milo\Github;

    /**
     * Login Class Provide Tools To Interact With Login With Twitter Api.
     */
    Class Api
    {
        private $app_url      = "http://127.0.0.1/php_internship/github_task";
        private $CALL_BACK    = "http://127.0.0.1/php_internship/github_task/~index/githubCallBack?redirect=1";

        private $connection;
        private $login;

        private $api;
        public  function __construct()
        {
                $config = new Github\OAuth\Configuration(
                        CLIENT_ID, 
                        CLIENT_SECRET,
                        ['user']
                );
                $storage = new Github\Storages\SessionStorage;

                $this->login = new Github\OAuth\Login($config, $storage);
                $this->api   = new Github\Api;
        }
        /**
         *@method generateUrl.
         *@return array of the generated Url AND Token's From Twitter. 
         */
         public function generateUrl():boolean{ 
                $this->login->askPermissions($this->CALL_BACK);
                exit(1);
                return 0;
         }

         public function tokenExists()
         {
              if($this->login->hasToken())
              {
                /** token used in each following requests. */
                $this->api->setToken($this->login->getToken());
                return true;
              }
              else
              {
                return false;
              }   
         }
         public function saveTokens(string $code, string $state)
         {
                return $this->login->obtainToken($code, $state);
         }
        
         public function requestUserData()
         {
                return ($this->api->decode($this->api->get("/user")));
         }
    }
