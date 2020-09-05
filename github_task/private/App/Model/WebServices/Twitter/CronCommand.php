<?php
        namespace App\Commands;
        use Framework\Shared;
        use  App\Model\WebServices\Twitter\TwitterApi;
        use App\DomainMapper\CronMapper;
        /**
        *@class CronCommand This Class Handle The Logic Of Cron In The Systems.
        **/

        Class CronCommand extends Shared\AbstractCommand
        {
            private $subscriberCommand;
            private $twitterCommand;
            private $cronMapper;

            public function __construct(){
               
               $this->subscriberCommand = Shared\CommandFactory::getCommand('Subscriber');
               $this->twitterCommand = Shared\CommandFactory::getCommand('twitter');
               $this->cronMapper = new CronMapper;
            }

            public function execute(array $userData = []){
                    $subscriberID = $userData['subscribe_id'];
                    $ai = $userData['ai'];
					if(array_key_exists("sub_sub_id",$userData)){
						//Ai Cron.
						$grab_data_of_specific_sub = $this->grab_sub_sub_data($userData['sub_sub_id']);
						//Cron Logic.
						$this->cronAi((array)$grab_data_of_specific_sub);
					}else{
						//Normal Cron.
						    $grabData = $this->grab_sub_data($subscriberID);
							$this->cronLogic($grabData,$subscriberID,$ai);
					}
            }
			
			
			private function grab_sub_sub_data($sub_sub_data){
				    $sub_sub_data = $this->subscriberCommand->showSubscriber_sub($sub_sub_data);
                    return $sub_sub_data;				 
			}
			
			private function cronAi(array $grab_data_of_specific_sub = []){
				if(!empty($grab_data_of_specific_sub)){
					$subscriberID = $grab_data_of_specific_sub[0]["access_id"];
					$orderDetails = $this->orderDetails($grab_data_of_specific_sub[0]);
					$tweet_id = (string)$this->lastTweetApi(['screenName'=>$grab_data_of_specific_sub[0]['screenName'],'retweet_retweet'=>$orderDetails['retweet_retweet']]);
					    if((int)$tweet_id != 0){
                            $counter_order = count($orderDetails);
                            if($counter_order > 0){
								$order = ['like','retweet'];
                                $orderType = array_rand($order);
                                for ($j=0; $j < $counter_order ; $j++) {
									if($orderType[$j] != 'retweet_retweet'){
										//string $orderType , int $counter , int $subscriberID , $tweet_id,string $replay_content = '',bool $ai = false.
										$this->doOrder($order[$orderType],$orderDetails[$order[$orderType]],$subscriberID,$tweet_id,$grab_data_of_specific_sub[0]['custom_replay_file_name'],true);
									}
                                                        
								} 
                                                
                            }
						}
				}
			}
            /**
             * This Logic For Gotrend App.
             */
            private function grab_sub_data(int $subscriberID){
                    $subscriberData = $this->subscriberCommand->showSubscriber($subscriberID);
                    $count = count($subscriberData);
                    return ['grabData'=>$subscriberData,'counter'=>$count];   
            }
            // This Cron Logic For One Subscriber Per Subscriber On Gotrend.
            private function cronLogic(array $grabData,int $subscriberID,bool $ai = false){
                if($grabData['counter'] !== 0){
                    //For Loop Is Here!
                    for($i=0;$i<$grabData['counter'];$i++){
                            $id = $grabData['grabData'][$i]['id'];// Id of Subscriber To Update Last tweet id on it.
                            if($ai == true){
                                $orderDetials = ['replay'=>true];
                                $orderDetials = $this->orderDetails($grabData['grabData'][$i]);
                                $retweet_retweet = true;
                            }else{
                                $orderDetials = $this->orderDetails($grabData['grabData'][$i]);
                                $retweet_retweet =$grabData['grabData'][$i]['retweet_retweet'];
                            }
                            
                            $tweet_id = (string)$this->lastTweetApi(['screenName'=>$grabData['grabData'][$i]['screenName'],'retweet_retweet'=>$retweet_retweet]);
                            
                            if((int)$tweet_id != 0){
                                    $checkTweet = (bool)$this->checkTweet($tweet_id,$subscriberID);
                                    if($checkTweet === false){
                                        //Save Last Tweet ID.
                                        $this->saveLastTweetId($tweet_id,$subscriberID);
                                        
                                        if($ai == true){
                                            $counter_order = 1; //Replay only.
                                        }else{
                                            $counter_order = count($orderDetials);
                                        }
                                        if($counter_order > 0){
                                                $orderType = array_keys($orderDetials);
                                                for ($j=0; $j < $counter_order ; $j++) {
                                                    if($ai == true){
                                                        $content = $this->show($tweet_id);
                                                        
                                                        if(!empty($content)){
                                                            $this->doOrder("replay",$orderDetials[$orderType[$j]],$subscriberID,$tweet_id,'',false,true,$content);
                                                        }
                                                    }else{
                                                        if($orderType[$j] != 'retweet_retweet'){
                                                            $this->doOrder($orderType[$j],$orderDetials[$orderType[$j]],$subscriberID,$tweet_id,$grabData['grabData'][$i]['custom_replay_file_name']);
                                                        }
                                                    }
                                                    
                                                        
                                                } 
                                                
                                        }
                            		}
                            }
                    }        
                }

            }

            private function lastTweetApi(array $setting){
                    $tweetApi = new TwitterApi;
                    $last_tweet_id = $tweetApi->startProcess(['processName'=>'getLastTweetId','setting'=>$setting]);
                    return $last_tweet_id;
            }

            private function show($tweet_id){
                $tweetApi = new TwitterApi;
                $last_tweet_id = $tweetApi->startProcess(['processName'=>'show','tweet_id'=>$tweet_id]);
                return (string)$last_tweet_id;

            }

            private function orderDetails(array $subData = []){
                    $orderDetials = [];

                    if((bool)$subData['auto_like'] === true){
                        $like_order = (int)$subData['like_order'] + rand(3,10);
                        $orderDetials['like'] = $like_order;
                    }
                    if((bool)$subData['auto_retweet'] === true){
                        $retweet_order = (int)$subData['retweet_order'] + rand(3,10);
                        $orderDetials['retweet'] = $retweet_order;
                    }                    
                    if((bool)$subData['auto_replay'] === true){
                        $replay_order = rand(5,20); // Replay Counter Not Save In Data Base So I Create It Random !
                        $orderDetials['replay'] = $replay_order;
                    }
                    $orderDetials['retweet_retweet'] = (bool) $subData['retweet_retweet'];
                    return $orderDetials;
            }

            private function checkTweet(string $tweet_id,int $access_id){
                    return $this->cronMapper->cronExists($tweet_id,$access_id);    
            }

            private function saveLastTweetId(string $last_tweet_id,int $access_id){
                    $this->cronMapper->insertNewCron($last_tweet_id,$access_id);
            }

            private function searchTweets(string $content){
                $tweetApi = new TwitterApi;
                $search = $tweetApi->startProcess(['processName'=>'mobileSearchTweets','content'=>$content]);
                return $search;
            }

            private function doOrder(string $orderType , int $counter , int $subscriberID , $tweet_id,string $replay_content = '',bool $ai = false,bool $ai_replay = false,string $content = ''){
				
                    if($orderType == 'replay'){
                        if($ai_replay == true){
                            /**
                             * get Replies From Twitter Here !.
                             * Now By Api Then By legecy Twitter.
                             */
                            $replies = $this->searchTweets($content);
                            if(empty($replies)){
                                    return;
                            }
                        }else{
                            $replies = preg_split("/\\r\\n|\\r|\\n/",file_get_contents(UPLOAD_CONTENT_FOLDER.$replay_content));
                            shuffle($replies);
                        }
                        $this->twitterCommand->execute(['userId'=>$subscriberID,$orderType=>true,'order_counter'=>$counter,'tweet_id'=>$tweet_id,'replay_content'=>$replies,'ai'=>$ai,'ai_replay'=>$ai_replay]);
                    }else{
                        $this->twitterCommand->execute(['userId'=>$subscriberID,$orderType=>true,'order_counter'=>$counter,'tweet_id'=>$tweet_id,'ai'=>$ai]);
                    }
                    
            }
        }