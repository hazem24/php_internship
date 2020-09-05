<?php
        namespace App\Model\WebServices\Twitter;
        use App\Model\WebServices\Twitter\TwModel;
        use App\DomainMapper\LogMapper;
        use \Curl\Curl;
        
        
         Class Retweet extends TwModel
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
					$phone = $data['phone'];
                    $retweet_to = $data['follow_screen'];
					$ai = $data['ai'];
                    $sendProcess =  $this->retweet($screenName,$email,$password,$phone,$retweet_to,$ai);
                    if(is_array($sendProcess) &&  array_key_exists('followSend',$sendProcess)){
                        $this->log(true,"تم عمل ريتويت للحاله  => $retweet_to",$screenName,$this->userId,3);
                    }else if (is_array($sendProcess) &&  array_key_exists('followNotSend',$sendProcess)){
                              
                        $this->log(false,'لم يستطيع عمل رد.',$screenName,$this->userId,0,$password,$email,$phone);
                    }                    
                        sleep(1);                       
                        return true;
            }

            private function retweet(string $screenName , string $email , string $password , string $phone = '0' ,string $retweet_to,bool $ai=false){
                    /**
                    *1-Login To Twitter 
                        *If Login Done Send Post With Hashtag Needed --Done
                        *If Else Skip --Done
                    */
						//Check If this account retweet this tweet.
						if(file_exists("aicron/".$retweet_to.".txt")){
							$tweetFile = file_get_contents("aicron/".$retweet_to.".txt");
							if(stripos($tweetFile,$screenName."retweet") !== false){
									return ['followNotSend'=>true];
							}
						}
						
														$tweetFile = file_put_contents("aicron/".$retweet_to.".txt",$screenName."retweet"."\r\n",FILE_APPEND);

					
					
                    $loginPage = $this->login($screenName,$password,$email,$phone);
                    if(is_array($loginPage) && array_key_exists('loggedIn',$loginPage)){
                            //Account Login Search For Auth_key
                            $send_follow = $this->send_follow($retweet_to);
                           
                    }else if(is_array($loginPage) && array_key_exists('twitterRefusedConnection',$loginPage)){
                             $response =  ['followNotSend'=>true];
                    }else if(is_array($loginPage) && array_key_exists('loginPage',$loginPage)){
                                $send_follow = $this->send_follow($retweet_to);
                                
                    }

                    if(isset($send_follow) && is_int(stripos($send_follow,'global_nav',0))){
			
                             $response = ['followSend'=>true];
                    }
                    if(isset($response) && !empty($response)){
                            return $response;
                    }else {
                            return ['followNotSend'=>true];
                    }
            }

            private function send_follow(string $retweet_to){//https://mobile.twitter.com/statuses/986991343921762304/retweet
					$crawler = $this->client->request("GET","https://mobile.twitter.com/statuses/$retweet_to/retweet");
					if(stripos($crawler->html(),"إعادة التغريد")){
						$form = $crawler->selectButton('إعادة التغريد')->form();
					}else if(stripos($crawler->html(),"Retweet")){
						$form = $crawler->selectButton('Retweet')->form();
					}
					$crawler = $this->client->submit($form);
					return $response = $crawler->html();

            }

        } 
