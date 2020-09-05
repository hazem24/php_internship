<?php
        namespace App\Model\WebServices\Twitter;
        use App\Model\WebServices\__Services;
        use App\DomainMapper\UserMapper;
		use Goutte\Client;
        
        /**
        *This Class Responsable For Register Accounts In Twitter
        */
        Abstract Class TwModel extends __Services
        {
            CONST TW_LOGIN_LINK = TW_LOGIN;
            CONST COOKIES_FOLDER = COOKIES_FOLDER;
            CONST TW_POST_LOGIN = TWITTER_NEW_SESSION;
			protected $client;
			protected $cookieFilePath;
            /**
            *@method startProcess This method Have Any Logic Of 
            */
            Abstract function startProcess(array $data = []);
            

            protected function extractName(string $emailAdress){
                $pos = stripos($emailAdress, '@');
                return  substr($emailAdress, 0,$pos);
            }


            
            protected function login(string $screenName = '' , string $password = '' , string $email = '',int $phone = 0){
					$this->client = new Client();

                    /**
                    * 1- Get Login Page Of Twitter --Done
                    * 2- Get Authication Code --Done
                    * 3- Send Post To Twitter --Done
                    * 4- Save Cookies In File --Done
                    * 5- Login To Twitter     --Done
                    * 6- Solve Challenge If Found --Done
                    */
					$this->setCookieFilePath(self::COOKIES_FOLDER."/".$email . ".txt");

					$loginPage = $this->client->request('GET', self::TW_LOGIN_LINK);// Log in 
					$loginPage = $this->redirectLink($loginPage->html() , $loginPage);
                    if(stripos($loginPage->html(),'/sessions',0) !== false){
							//Login To Twitter.
						$loginForm = $loginPage->selectButton('Log in')->form();
						$submitx = $this->client->submit($loginForm, array('session[username_or_email]' => $screenName, 'session[password]' => $password));
						$submit = $submitx->html();

                        if(is_int(stripos($submit,'/account/login_challenge',0))){ 
                                //Solve challenge.
								$submit  = $this->solveChallenge($submit,$screenName,$email,$phone,$submitx);
                        }
							$this->saveCookies($email);
                    } 
					
					if (isset($submit) && is_object($submit) === false && is_int(stripos($submit,'tweet',0))){

                                return ['loggedIn'=>true];
                    }else if (is_object($loginPage->html()) === false && is_int(stripos($loginPage->html(),'tweet',0))){
								return ['loggedIn'=>true];
					}			
					else{
                                return ['twitterRefusedConnection'=>true];
                    }
                    return ['loginPage'=>$loginPage->html()];
            }




            protected function solveChallenge(string $output , string $screenName , string $email,string $phone = '0' , $solveChallenge){
                    //Send Another Request To Solve Challange
                    if(stripos($output,"RetypePhoneNumber") !== false){
                        $challenge_type = "RetypePhoneNumber";
                        $value = "$phone";
                    }else{
                        $challenge_type = "RetypeEmail";
                        $value = "$email";
                    }
					$form = $solveChallenge->selectButton('Submit')->form();
					$solveChallenge = $this->client->submit($form, array('challenge_response' => $value));
					$solveChallenge = $this->client->submit($form);
					return $solveChallenge->html();

            }
			
			protected function redirectLink($html , &$bot){
				//use this link
				if(stripos($html,"use this link")){
					$link    = $bot->selectLink('use this link')->link();
					$bot     = $this->client->click($link);
				}
				return $bot;
			}
            
            protected function accountCookie(string $email){
                if (!file_exists(self::COOKIES_FOLDER."/".$email . ".txt")) {
                    $fh = fopen(self::COOKIES_FOLDER."/".$email . ".txt", 'w');  
                     fclose($fh);
                }

            }
            /**
            *log type.
            *0 => fail
            *1 => warning
            *2 => info
            *3 => success
            */
            protected function log(bool $success,$message,$screenName,$userId,$type,$password='',$email='',$phone=''){
                if($success === true){
                        $this->logMapper->log('تنبيه من العضويه : ( '. $screenName . ' ) : ' . $message, $userId  , $type);
                }else {
                        $this->logMapper->log("($screenName) : ($password) : ($email) : ($phone) ",$userId , 0);
                }
        }
            /**
            *@method getRandomResponse This Function Must Handle Two Logic First Find If User Have Tweets In DataBase
            *Use it Else 
            *get from tweets.txt File
            *Updated @19/01/2018 To Use preg_split for all cases.
            */
            protected function getRandomResponse(string $hashtag = ''){
                //$tweets  = explode(PHP_EOL,file_get_contents('tweets.txt'));
                $tweets  = preg_split("/\\r\\n|\\r|\\n/",file_get_contents('tweets.txt'));
                $counter = count($tweets)-1;
                return $tweets[rand(0,$counter)] . "             
$hashtag";
            }
			
		public function setCookieFilePath($cookieFilePath)
		{
				$this->cookieFilePath = $cookieFilePath;
				if (is_file($cookieFilePath)) {
					// Load cookies and populate browserkit's cookie jar
					$cookieJar = $this->client->getCookieJar();
					$cookies = unserialize(file_get_contents($this->cookieFilePath));
					foreach ($cookies as $cookie) {
						$cookieJar->set($cookie);
					}
				}
		}

		// Call this whenever you need to save cookies. Maybe after a request has been made since BrowserKit repopulates it's CookieJar from the Response returned.
		protected function saveCookies(string $email)
		{
			$cookieFilePath = $this->getCookieFilePath($email);
			$cookieJar = $this->client->getCookieJar();
			$cookies = $cookieJar->all();
			if ($cookies) {
				file_put_contents($cookieFilePath, serialize($cookies));
			}
		}
		
		protected function getCookieFilePath(string $email){
			    $this->accountCookie($email);
				return 	self::COOKIES_FOLDER."/".$email  .".txt";
		}
	} 
