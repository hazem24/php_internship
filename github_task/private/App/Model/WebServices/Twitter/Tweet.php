<?php
        namespace App\Model\WebServices\Twitter;
        use App\Model\WebServices\Twitter\TwModel;
        use App\DomainMapper\LogMapper;
        use \Curl\Curl;
		use Goutte\Client;
        
        
         Class Tweet extends TwModel
        {
            CONST TWEET_SENDER = TWEET_SENDER;
            protected $userAgent;
            protected $userId;
            protected $logMapper;
			protected $client;

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
                    $hashtag = (is_null($data['follow_screen']) === false) ? $data['follow_screen']:'';
                    //End Constant
                                //bool $success,$message,$screenName,$userId,$type
                                if($email !== null && $screenName !== null && $password !== null){
                                    $tweet = $this->getRandomResponse($hashtag);
                                    $sendProcess =  $this->tweet($screenName,$email,$password,$tweet,$phone);
                                    if(is_array($sendProcess) &&  array_key_exists('tweetSend',$sendProcess)){
                                            /**
                                            *1- Send Success Log Inside The Log DataBase With log access_id For User.
                                            */
                                            $this->log(true,"تم ارسال تغريده : $tweet",$screenName,$this->userId,3);
                                    }else if (is_array($sendProcess) &&  array_key_exists('tweetNotSend',$sendProcess)){
                                            /**
                                            *1- Send Error Log Inside The Log DataBase With log access_id For User.
                                            */
                                            $this->log(false,'لم يستطيع عمل رد.',$screenName,$this->userId,0,$password,$email,$phone);
                                    }
                                }

                                sleep(1);                       
                                return true;
            }

            private function tweet(string $screenName , string $email , string $password , string $tweet , string $phone = '0'){
                    /**
                    *1-Login To Twitter 
                        *If Login Done Send Post With Hashtag Needed --Done
                        *If Else Skip --Done
                    */
                    $loginPage = $this->login($screenName,$password,$email,$phone);
					
                    if(is_array($loginPage) && array_key_exists('loggedIn',$loginPage)){
                            $send_tweet = $this->sendTweet($tweet,$this->client);
                    }else if(is_array($loginPage) && array_key_exists('twitterRefusedConnection',$loginPage)){
                             $response =  ['tweetNotSend'=>true];
                    }else if(is_array($loginPage) && array_key_exists('loginPage',$loginPage)){
                                /**
                                *1-Login Success Send Post To Twitter Compose.
                                */
                                $send_tweet = $this->sendTweet($tweet);
                    }

                    if(isset($send_tweet) && is_int(stripos($send_tweet,'global_nav',0))){
                             $response = ['tweetSend'=>true];
                    }
                    if(isset($response) && !empty($response)){
                            return $response;
                    }else {
                            return ['tweetNotSend'=>true];
                    }
            }

            private function sendTweet(string $tweet){
					$crawler = $this->client->request("GET",self::TWEET_SENDER);//Tweet
					if(stripos($crawler->html(),"تغريد")){
						$form = $crawler->selectButton('تغريد')->form();
					}else if(stripos($crawler->html(),"Tweet")){
						$form = $crawler->selectButton('Tweet')->form();
					}
					$crawler = $this->client->submit($form, array('tweet[text]' => $tweet));
					return $response = $crawler->html();

            }


        } 
