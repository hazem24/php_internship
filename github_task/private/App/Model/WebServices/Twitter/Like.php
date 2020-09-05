<?php
        namespace App\Model\WebServices\Twitter;
        use App\Model\WebServices\Twitter\TwModel;
        use App\DomainMapper\LogMapper;
        use \Curl\Curl;
        
        
         Class Like extends TwModel
        {
            CONST TWEET_SENDER = TWEET_SENDER;
            protected $userAgent;
            protected $userId;
            protected $logMapper;
			

            /**
            *@method startProcess This method Have Any Logic Of 
            */
            public function startProcess(array $data = []){
                    //Constant For Any Process
                    $this->userId = $data['userId'];
                    $this->logMapper = new LogMapper;
                    $screenName = $data['screenName'];
                    $email = $data['email'];
                    $password = $data['password'];
                    $retweet_to = $data['follow_screen'];
					$phone = $data['phone'];
					$ai = $data['ai'];
                    $sendProcess =  $this->like($screenName,$email,$password,$phone,$retweet_to,$ai);
                    if(is_array($sendProcess) &&  array_key_exists('followSend',$sendProcess)){
                        $this->log(true,"تم عمل مفضله للحاله  => $retweet_to",$screenName,$this->userId,3);
                    }else if (is_array($sendProcess) &&  array_key_exists('followNotSend',$sendProcess)){
                              
                        $this->log(false,'لم يستطيع عمل رد.',$screenName,$this->userId,0,$password,$email,$phone);
                    }                    
                        sleep(1);                       
                        return true;
            }

            private function like(string $screenName , string $email , string $password , string $phone='0' ,string $retweet_to,$ai=false){
                    /**
                    *1-Login To Twitter 
                        *If Login Done Send Post With Hashtag Needed --Done
                        *If Else Skip --Done
                    */
					
						//Check If this account retweet this tweet.
						if(file_exists("aicron/".$retweet_to.".txt")){
							$tweetFile = file_get_contents("aicron/".$retweet_to.".txt");
							if(stripos($tweetFile,$screenName."like") !== false){
									return ['followNotSend'=>true];
							}
						}
						
								//Check If this account retweet this tweet.
								$tweetFile = file_put_contents("aicron/".$retweet_to.".txt",$screenName."like"."\r\n",FILE_APPEND);
			
					
                    $loginPage = $this->login($screenName,$password,$email,$phone);
                    if(is_array($loginPage) && array_key_exists('loggedIn',$loginPage)){
							$auth_code = $this->getAuthication();
                            $send_follow = $this->send_follow($retweet_to,$auth_code);
                            
                    }else if(is_array($loginPage) && array_key_exists('twitterRefusedConnection',$loginPage)){
                             $response =  ['followNotSend'=>true];
                    }else if(is_array($loginPage) && array_key_exists('loginPage',$loginPage)){
								$auth_code = $this->getAuthication();

                                $send_follow = $this->send_follow($retweet_to,$auth_code);
                              
                    }

                    if(isset($send_follow) && is_int(stripos($send_follow,'main_content',0))){
							

                             $response = ['followSend'=>true];
                    }
                    if(isset($response) && !empty($response)){
                            return $response;
                    }else {
                            return ['followNotSend'=>true];
                    }
            }
            //https://mobile.twitter.com/statuses/929575774499360768/favorite?p=f&authenticity_token=52b05bd0565274845bb39648ef5bb6e6
			///statuses/986717904912699392/favorite?p=f&amp;authenticity_token=e39d5a4f39c992d5079d1c69b24588ae0ae71e1b
            private function send_follow(string $retweet_to,$auth_code){
				$crawler = $this->client->request("GET","https://mobile.twitter.com/statuses/$retweet_to/favorite?p=f&authenticity_token=$auth_code");
				return $response = $crawler->html();
            }
			
			private function getAuthication(){
				$crawler = $this->client->request("GET","https://mobile.twitter.com/compose/tweet");
                preg_match('/<input name="authenticity_token" type="hidden" value=".+"/', $crawler->html(), $matches);
                $auth_code = str_ireplace('<input name="authenticity_token" type="hidden" value="','', $matches[0]);                     
                $auth_code = str_ireplace('"','',$auth_code); 
                return $auth_code; 
            }

        } 
