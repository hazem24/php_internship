<?php
        namespace App\Model\WebServices\Twitter;
        use App\Model\WebServices\Twitter\TwModel;
        use App\DomainMapper\LogMapper;
        use \Curl\Curl;
        
        
         Class Replay extends TwModel
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
                    $replay_to = $data['follow_screen'];
					$phone = $data['phone'];
                    $replay_text = (isset($data['replay_text'])&&$data['replay_text'] !== null)?$data['replay_text']:$this->getRandomResponse();
                    $sendProcess =  $this->replay($screenName,$email,$password,$phone,$replay_to,$replay_text);
                    if(is_array($sendProcess) &&  array_key_exists('replaySend',$sendProcess)){
                        $this->log(true,"تم الرد علي التغريده  => $replay_to",$screenName,$this->userId,3);
                    }else if (is_array($sendProcess) &&  array_key_exists('replayNotSend',$sendProcess)){
                              
                        $this->log(false,'لم يستطيع عمل رد.',$screenName,$this->userId,0,$password,$email,$phone);
                    }                    
                        sleep(1);                       
                        return true;
            }

            private function replay(string $screenName , string $email , string $password , string $phone = '0' ,string $replay_to , string $replay_text = 'gotrend.today'){
                    /**
                    *1-Login To Twitter 
                        *If Login Done Send Post With Hashtag Needed --Done
                        *If Else Skip --Done
                    */
                    $loginPage = $this->login($screenName,$password,$email,$phone);
                    if(is_array($loginPage) && array_key_exists('loggedIn',$loginPage)){
                            $send_follow = $this->send_replay($replay_to ,$replay_text);
                    }else if(is_array($loginPage) && array_key_exists('twitterRefusedConnection',$loginPage)){
                             $response =  ['replayNotSend'=>true];
                    }else if(is_array($loginPage) && array_key_exists('loginPage',$loginPage)){
                                $send_follow = $this->send_replay($replay_to);
                    }

                    if(isset($send_follow) && is_int(stripos($send_follow,'global_nav',0))){
                             $response = ['replaySend'=>true];
                    }
                    if(isset($response) && !empty($response)){
                            return $response;
                    }else {
                            return ['replayNotSend'=>true];
                    }
            }

            private function send_replay(string $replay_to , string $replay_text){//https://mobile.twitter.com/nkalanizy/reply/987040294389583872
					$crawler = $this->client->request("GET","https://mobile.twitter.com/nkalanizy/reply/$replay_to");
					if(stripos($crawler->html(),"ردّ")){
						$form = $crawler->selectButton('ردّ')->form();
					}else if(stripos($crawler->html(),"Replay")){
						$form = $crawler->selectButton('Replay')->form();
					}
					$crawler = $this->client->submit($form,['tweet[text]'=>$replay_text]);
					return $response = $crawler->html();
	
            }

        } 
