<?php
        namespace App\Model\WebServices\Twitter;
        use App\Model\WebServices\Twitter\TwModel;
        use Abraham\TwitterOAuth\TwitterOAuth;
        use \Curl\Curl;
        use Goutte\Client;
        use GuzzleHttp\Cookie;


        
        //This Class Only Connect To Twitter Api And Get Account Data Info name - image .. etc
        Class TwitterApi extends TwModel
        {
            public $CONSUMER_KEY1 ='w5pCBsgdc7XqJesoY9spBDk8s';
            public $CONSUMER_SECRET1 ='CsWLiATdYL1zpPkyPNeFagkHpWpqNjilBsv0GnYTBMmiA25wyk';

            //public $CONSUMER_KEY2 ='o6IZzFBP8kIRuUNpXmfvVx0X7';
           // public $CONSUMER_SECRET2 ='nJ49A9tMSEFVZDo6yGykUrCsFhuqA828h5IeaXyj0uW9V5Sa4M';

           // public $CONSUMER_KEY3 ='AtuosAMUKBHn8LVLyMt3DD1LK';
           // public $CONSUMER_SECRET3 ='p9ZqeqiLPhXmDkA1lYjgXmUHtyvBrHqlSguBRafxFIIjDUY9JR';


            private $connection;

            /**
            *@method startProcess This method Have Any Logic Of 
            */
            public function startProcess(array $data = []){
                    //Constant For Any Process
                    $this->initilization();
                    if(method_exists($this,$data['processName'])){
                        $method_name = $data['processName'];
                        if(!isset($data['setting'])){
                            return $this->$method_name((string)array_values($data)[1]);
                        }
                        return $this->$method_name($data['setting']);
                    }
                    
            }

            private function lastTweets( $hashtag ){
                $info = $this->connection->get('search/tweets',['q'=>$hashtag,'count'=>2,'result_type'=>'recent']);
                if(is_object($info) && isset($info->statuses) && !empty( $info->statuses )){
                     return $info->statuses;
                }
                     return false;
            }

            //GET https://api.twitter.com/1.1/users/show.json?screen_name=twitterdev
            private function getInformation (array $setting){
                   $info = $this->connection->get('users/show',['screen_name'=>$setting['screenName']]);
                   if(is_object($info) && isset($info->screen_name)){
                        return $this->returnInformation($info);
                   }
                        return false;
            }
            //GET https://api.twitter.com/1.1/statuses/user_timeline.json?screen_name=twitterapi&count=2

            private function getLastTweetId(array $setting){
                    $lastTweetId = $this->connection->get('statuses/user_timeline',['screen_name'=>$setting['screenName'],'count'=>1,'exclude_replies'=>true,'include_rts'=>$setting['retweet_retweet']]);
                    //In Case Of user account is private Twitter Return Object And Not Array.
                    if(is_array($lastTweetId) && !empty($lastTweetId)){ //In Case The Screen Name Not Found || Empty Tweet So twitter @return empty array.
                        return $lastTweetId[0]->id_str;
                    }
                        return 0;    
            }
            private function searchTweets(string $content){
                //Sub_str here !
                $content = explode(" ",$content);
                $counter = count($content);
                if(!empty($content)){
                    foreach ($content as $key => $value) {
                        if($counter-1 == $key){
                                    break;
                        }
                        if(mb_strlen($value,'utf8') > 2){
                            $lastTweetId = $this->connection->get('search/tweets',['q'=>$content[$key].(isset($content[$key+1]))?$content[$key+1]:'','tweet_mode'=>'extended','result_type'=>'recent']);
                            if(isset($lastTweetId->statuses)){
        
                                $ai_replies[] = $lastTweetId->statuses;
                            }    
                        }
                    }
                   
                }else{
                    $ai_replies = $this->connection->get('search/tweets',['q'=>$content,'result_type'=>'recent']);
                    if(isset($ai_replies->statuses)){
                        $ai_replies[] = $ai_replies->statuses;
                    }   
                }
                //In Case Of user account is private Twitter Return Object And Not Array.
                if(!empty($ai_replies)){
                        foreach ($ai_replies as $key => $value) {
                            if(is_array($value)){
                                    foreach ($value as $key => $replay) {
                                        $replay = $this->extractTweetData($replay)['org_text'];
                                        if(stripos($replay,'@') === false && stripos($replay,"سكس") === false){
                                            $replies[] = $replay;
                                        }
                                    }
                            }
                        }
                }
                //Filter Replies.

                if(isset($replies)&&is_array($replies) && !empty($replies)){ //In Case The Screen Name Not Found || Empty Tweet So twitter @return empty array.
                        foreach ($replies as $key => $value) {
                            if($this->strposa($value,$content,1)){
                                    $replays[] = $value;
                            }
                        }
                    return $replays;
                }
                    return ['?','??','??','???','?!','!','لم افهم'];    

            }
            private function show(string $tweet_id){
                $lastTweetId = $this->connection->get('statuses/show',['id'=>$tweet_id]);
                //In Case Of user account is private Twitter Return Object And Not Array.
                if( is_object($lastTweetId) && !empty($lastTweetId) && isset($lastTweetId->text)){ //In Case The Screen Name Not Found || Empty Tweet So twitter @return empty array.
                        return (string)$lastTweetId->text;
                }
                    return '';    
            }

            private function initilization(){
               // $rand = rand(1,3);
                $cons_key = "CONSUMER_KEY1";
                $cons_secret = "CONSUMER_SECRET1";
                $this->connection = new TwitterOAuth($this->$cons_key, $this->$cons_secret);
                $this->connection->setTimeouts(100, 150);  
            }

            private function returnInformation($info){
                    $name = (string)$info->name;
                    $follower_count = (int)$info->followers_count;
                    $statuses_count = (int)$info->statuses_count; 
                    $follow_count = (int)$info->friends_count; 
                    $image_https_path = (string)$info->profile_image_url_https;
                    return ['tw_name'=>$name,'image_path'=>$image_https_path,'follower_count'=>$follower_count,'tweet_count'=>$statuses_count,'follow_count'=>$follow_count];     
            }

            function strposa($haystack, $needle, $offset=0) {
                if(!is_array($needle)) $needle = array($needle);
                foreach($needle as $query) {
                    if(strpos($haystack, $query, $offset) !== false) return true; // stop on first true result
                }
                return false;
            }
          

            /**
             * @method extractTweetData responsable for extract needed data from tweet that seshat need .. organize tweet to appear to user.
             * @param tweet object of the tweet.
             * @param analytic just flag to extract data that for user that retweet or reacted to tweet not owner of tweet itself.
             * @return array.
             */
            public  function extractTweetData($tweet,bool $analytic = false){
                /**
                 * global variables.
                */
                $links_in_this_tweet=[];//reset links tweet global array.
                $mentions_users = [];//Reset global mentions users.
                $mentions_in_tweet = [];//Reset hashtag global array.
                $hash_tag_in_tweets = [];
                //End  global variables.
                //Some Logic Changed When The Tweet Is Retweeted From Another User.
                $retweeted = isset($tweet->retweeted_status) ? true:false;
                if($retweeted && $analytic === false):
                        //var_dump($tweet);
                        //exit;
                        $like_count = $tweet->retweeted_status->favorite_count;
                        $retweet_count = $tweet->retweeted_status->retweet_count;
                        $org_text   = $tweet->retweeted_status->full_text;
                        $full_text  = $tweet->retweeted_status->full_text;
                        $screen_name = $tweet->retweeted_status->user->screen_name;//orgin screenName which tweet specific tweets.
                        $user_retweeted_tweet = " @".$tweet->user->screen_name; //User Which retweeted this tweet.
                        $name = $tweet->retweeted_status->user->name;
                        $user_profile = $tweet->retweeted_status->user->profile_image_url;
                        $media =  isset($tweet->retweeted_status->extended_entities->media[0]) ? $tweet->retweeted_status->extended_entities->media[0] : null;
                        $hash_tag_tweets = (isset($tweet->retweeted_status->entities->hashtags) && !empty($tweet->retweeted_status->entities->hashtags))?$tweet->retweeted_status->entities->hashtags:false;
                        $mentions_in_this_tweet = (isset($tweet->retweeted_status->entities->user_mentions) && !empty($tweet->retweeted_status->entities->user_mentions))?$tweet->retweeted_status->entities->user_mentions:false;
                        $links_in_tweet = (isset($tweet->retweeted_status->entities->urls) && !empty($tweet->retweeted_status->entities->urls))?$tweet->retweeted_status->entities->urls:false;
                        $following = $tweet->retweeted_status->user->following;
                        $tweet_id = $tweet->retweeted_status->id_str;
                        $user_id  = $tweet->retweeted_status->user->id_str;
                        $lang = $tweet->retweeted_status->lang;
                        
                else:  
                        $like_count = $tweet->favorite_count;
                        $retweet_count = $tweet->retweet_count;
                        $org_text = $tweet->full_text;
                        $full_text = $tweet->full_text;
                        $screen_name = $tweet->user->screen_name;
                        $name = $tweet->user->name;
                        $user_profile = $tweet->user->profile_image_url;
                        $media = isset($tweet->extended_entities->media[0]) ? $tweet->extended_entities->media[0]: null;
                        $hash_tag_tweets = (isset($tweet->entities->hashtags) && !empty($tweet->entities->hashtags))?$tweet->entities->hashtags:false;
                        $mentions_in_this_tweet = (isset($tweet->entities->user_mentions) && !empty($tweet->entities->user_mentions))?$tweet->entities->user_mentions:false;
                        $links_in_tweet = (isset($tweet->entities->urls) && !empty($tweet->entities->urls)) ? $tweet->entities->urls : false;
                        $following = $tweet->user->following;
                        $tweet_id  = $tweet->id_str;
                        $user_id   = $tweet->user->id_str;
                        $lang = $tweet->lang;

                endif;

                $ar = ($lang == 'ar') ? true : false;  
                $dir = ($ar)? "dir=rtl" :"";
                $replay_screen_name = "@" . $screen_name;//For replay logic.
                $screenName = ($ar) ? $screen_name."@":"@".$screen_name;
                $retweet_button_style  = ($tweet->retweeted)?'class="btn  btn-link btn-success retweet_unretweet"':'class="btn btn-link retweet_unretweet"';//User retweeted This tweet. class="btn  btn-link btn-success tweet_retweet"
                $retweet_type = ($tweet->retweeted)?"unretweet":"retweet";
                $like_status   = ($tweet->favorited)?'btn btn-danger btn-link like_unlike':'btn btn-link  like_unlike';//User Liked This Tweet or not.
                $like_type = ($tweet->favorited)?"unlike":"like";
                
                /**
                 * links color section.
                */


                //add expanded links if found.
                if($links_in_tweet !== false):
                        foreach ($links_in_tweet as $key => $link) :
                                /*if($screen_name == "YinneAdrianaM" && $link->expanded_url != "https://twitter.com/canalspace/status/992539401287684096"){
                                                var_dump($key,$link);
                                                exit;
                                                
                                }*/
                                $links_in_this_tweet[] =  [$link->url,"<a class='link-danger' target='_blank' href=\"$link->expanded_url\">$link->display_url</a>"];//Links In text.
                        endforeach;

                       
                        
                endif;
                //End links section.
                /**
                 * Hashtag color section.
                 */
                if($hash_tag_tweets !== false):
                        foreach ($hash_tag_tweets as $key => $hashtag) :
                                $colored_tag = '#'.$hashtag->text;
                                $hash_tag_in_tweets[] =  [$hashtag->text,"<a href='' style='color:DarkViolet;'>".$colored_tag."</a>"];//hashtag In text.
                        endforeach;
                endif;
                //End Hashtag section. 
                        $full_text = str_ireplace(['@','#'],'',$full_text);
                /**
                 * Mentions color section.
                 */
                if($mentions_in_this_tweet !== false):
                        foreach ($mentions_in_this_tweet as $key => $mention) :
                                $colored_mentions = ($ar) ? $mention->screen_name.'@': '@'.$mention->screen_name;
                                $mentions_in_tweet[] =  [$mention->screen_name,"<a href='' class='link-info'>".$colored_mentions."</a>"];//hashtag In text.
                        endforeach;
                endif;
                //End mentions color sesction.
                //mention section. 
                if(isset($mentions_in_tweet) && is_array($mentions_in_tweet) && !empty($mentions_in_tweet)):
                        foreach ($mentions_in_tweet as $key => $user) :
                                $full_text  = str_ireplace($user[0],$user[1],$full_text);
                        endforeach;
                endif;
                //End mention.
                //Links.
                if(isset($links_in_this_tweet) && is_array($links_in_this_tweet) && !empty($links_in_this_tweet)):
                        
                        foreach ($links_in_this_tweet as $key => $link) :
                                $full_text  = str_ireplace($link[0],$link[1],$full_text);
                        endforeach;
                endif;

                //Remove media links.
                $links_inside_tweet_full_text = (preg_match_all('#https:\/\/t.co\/.+\S+#',$full_text,$links))? true : false;
                if($links_inside_tweet_full_text === true){
                        //Remove links.
                        foreach ($links[0] as $key => $media_link) :
                                $full_text = str_ireplace($media_link,'',$full_text); //This links is image or media links not wanted.     
                        endforeach;
                }
                //End Links.

                //Hashtags.
                if(isset($hash_tag_in_tweets) && is_array($hash_tag_in_tweets) && !empty($hash_tag_in_tweets)):
                        foreach ($hash_tag_in_tweets as $key => $hashtag) :
                                $full_text  = str_ireplace($hashtag[0],$hashtag[1],$full_text);
                        endforeach;
                endif;
                //End Hashtags.


                //Media section.
                
                /**
                 * This must be refactor to display all images Twitter can upload 4 media type image in every tweet so must be refactor.
                 * Display One Media Only Type Video Or Image.
                 * Note : media_url Must Be Changed To media_url_https In production to support https.
                 */
                if(is_null($media) === false):
                        if($media->type == 'video'):
                            $video_link = $media->video_info->variants[0]->url;
                            $poster = $media->media_url;
                             $media = "<video class ='afterglow'  width=\"720\" height=\"400\" class=\"col-md-12\" poster=\"$poster\" controls>
                                    <source src=\"$video_link\" type=\"video/mp4\">
                                Your browser does not support HTML5 video.
                                </video>";
                        else:
                        
                            $media = "<a data-fancybox href=\"$media->media_url\" class=\"fancybox\"  data-caption=\"$org_text\" >
                                    <img  src=\"$media->media_url\"    alt=\"twitter_image\" class=\"img-rounded img-tweet\"/>
                                    </a>";
                        //End if of stripos Condition. 
                        endif;
                //End if of media is_null condition.   
                endif;
                //End Media section.

                        

                //End Following status.
                
                //Uses for analtyic only.
                $followers_count = $tweet->user->followers_count;
                $friends_count   = $tweet->user->friends_count;//Number of person the user follows.
                //End uses of analtyic only.

                //Fake impression beacuse really impression data about 3000$ per month in paid api of twitter.
                $impression = (int)(($like_count + $retweet_count + ($followers_count * (rand(5,20)/100))));

                $total_reacted   = (int)($retweet_count + $like_count);
                $reacted_times = ($total_reacted <= 0)? 1 : $total_reacted; //To prevent divide by zero.
                    
                /**
                 * This calculation must be in another helper.
                 */

                $statics = (object)['total_reacted'=>$total_reacted,'retweet_precent'=>ceil(($retweet_count/$reacted_times)*100) , 'like_precent'=>floor(($like_count/$reacted_times)*100)];
                return ['like_count'=>(int)$like_count,'org_text'=> $org_text,'full_text'=>$full_text,'retweeted'=>$retweeted,'screenName'=>$screenName,
                        'name'=>$name,'screen_name'=>$screen_name,'user_profile'=>$user_profile,'media'=>$media,'retweet_count'=>(int)$retweet_count,
                        'dir'=>$dir,'lang'=>$lang,'user_id'=>$user_id,'following'=>'','replay_screen_name'=>$replay_screen_name,'retweet_button_style'=>$retweet_button_style,'retweet_type'=>$retweet_type,'like_status'=>$like_status,
                        'like_type'=>$like_type,'tweet_id'=>$tweet_id,'links_in_this_tweet'=>$links_in_this_tweet,'hash_tag_in_tweets'=>$hash_tag_in_tweets,
                        'mentions_in_tweet'=>$mentions_in_tweet,'statics'=>$statics,'impression'=>$impression,'followers_count'=>$followers_count,'friends_count'=>$friends_count,'favourites_count'=>$tweet->user->favourites_count,'statuses_count'=>$tweet->user->statuses_count,'user_retweeted_tweet'=>(isset($user_retweeted_tweet))?$user_retweeted_tweet:''];        
            }


            /**
             * بعد كل الذي حدث ويحدث في #سوريا الأمل بالله ثم بثوار #درعا فقط يمكن الأعتماد عليهم لما هو قادم
             */
            private function mobileSearchTweets(string $content){

                $content_as_array = explode(" ",$content);
                $counter = count($content); //Used to search for 5 words only from it.
                
                /**
                 * 1 - Search for hashtag if found to create search with it and end the proccess.
                 * 2 - If no hastag Search || replies < 3 twitter for whole content if found get replies and end the proccess.
                 * 3 - If no replies Search for  || replies < 3 Search for first word and concate with two after words and search.
                 * 4 - if no replies get tweets by twitter api.
                 */
                if(!empty($content_as_array)){
                        foreach ($content_as_array as $key => $value) {
                                if(stripos($value,'#') !== false){//Search for hashtag.
                                        $hashTags[] = $value;
                                }
                        }
                }

                //Search in hashtag.
                if(isset($hashTags) && !empty($hashTags)){
                        foreach ($hashTags as $key => $hashtag) {
                                $check = $this->searchTweetLegecy($hashtag);
                                if($check !== false){
                                          $ai_replies[] = $check;      //Multi-D [].
                                }
                        }
                        if(isset($ai_replies) && is_array($ai_replies)){
                              return $this->multiArray($ai_replies);          
                        }
                }

                
                //Search whole the content whole string.
                $ai_replies = $this->searchTweetLegecy($content);
                
                if(is_array($ai_replies)){
                        return $ai_replies;
                }
                //Logic Search Step.
                if(is_array($content_as_array)){
                        //Re-arrange.
                        foreach ($content_as_array as $key => $string) {
                                $letters_keys[mb_strlen($string)] = $string;
                        }

                        foreach ($letters_keys as $key_one => $value) {
                                $ai_replies[] = $this->searchTweetLegecy($value);
                        }
                }

                if(is_array($ai_replies)){
                        return $this->multiArray($ai_replies);
                }

                //Final Step Api search.
return false;

            }


            private function searchTweetLegecy(string $content){
                        //F0Anp:a0966qwe540611:w.a.jd.i.t.agge.d11@gmail.com:790036584
                        $loginPage = $this->login("F0Anp","a0966qwe540611","w.a.jd.i.t.agge.d11@gmail.com","790036584");
                        if(is_array($loginPage) && array_key_exists('loggedIn',$loginPage)){
                                $client = $this->client;
                                $crawler = $client->request('GET', 'https://mobile.twitter.com/search?q='.urlencode($content));
                                $html = $crawler->html();
								if(stripos($html,"تحديث")){
									//$crawler = 
									$link = $crawler->selectLink('تحديث')->link();
									$crawler = $client->click($link);
									$html    = $crawler->html();
								}
                                $filter_replies = $this->filterReplies($html);
                                //file_put_contents("tweets.html",$crawler->html());
                                if(is_array($filter_replies)){
                                        if(count($filter_replies) == 1){ //One search in whole string.
                                                return $this->oneRepliesFoundWithNextButton($html,$client,$crawler);
                                        }
                                        return $filter_replies;
                                }   
        
                        }
                        else if(is_array($loginPage) && array_key_exists('loginPage',$loginPage)){
                                $client = $this->client;
                                $crawler = $client->request('GET', 'https://mobile.twitter.com/search?q='.urlencode($content));
                                $html = $crawler->html();
								if(stripos($html,"تحديث")){
									//$crawler = 
									$link = $crawler->selectLink('تحديث')->link();
									$crawler = $client->click($link);
									$html    = $crawler->html();
								}
                                $filter_replies = $this->filterReplies($html);
                                //file_put_contents("tweets.html",$crawler->html());
                                if(is_array($filter_replies)){
                                        if(count($filter_replies) == 1){ //One search in whole string.
                                                return $this->oneRepliesFoundWithNextButton($html,$client,$crawler);
                                        }
                                        return $filter_replies;
                                }
                        }
                        return false;//No Search Found.
            }
            //strip_tags($string)
            private function filterReplies(string $html){
                $replies_array = null;    
                $replies = preg_match_all('/<div class="tweet-text".+\n.+\n.+/',$html,$matches);
                if(is_array($matches) && !empty($matches[0])){
                        foreach ($matches[0] as $key => $replay) {
                                        $replies_array[] = strip_tags($replay);       
                        }
                }
                return $replies_array;
            }

            private function oneRepliesFoundWithNextButton($html,&$client,&$crawler){
                
                //Enter the only tweet to find replies.
                if(stripos($html,'class="metadata"')){
                        if(stripos($html,'عرض التفاصيل')){
                                $link = $crawler->selectLink('عرض التفاصيل')->link();
                        }else {
                                $link = $crawler->selectLink('View details')->link();
                        }
                        $crawler = $client->click($link);
                        $html = $crawler->html();
                        $replies =  $this->filterReplies($html);
                        
                        if(is_array($replies) && count($replies) == 0){
                                return $this->oneRepliesFoundWithNextButton($html,$client,$crawler);
                        }
                        return $replies;
                }
                
                if(stripos($html,"w-button-more")){
                        if(stripos($html," Load older Tweets ")){
                                $find_more = $crawler->selectLink('Load older Tweets')->link();
                        }else{
                                //تحميل التغريدات الأقدم
                                $find_more = $crawler->selectLink('تحميل التغريدات الأقدم')->link();
                        }
                        $crawler = $client->click($find_more);
                        $html = $crawler->html();
                        $replies =  $this->filterReplies($html);
                        
                        if(is_array($replies) && count($replies) == 0){
                                return $this->oneRepliesFoundWithNextButton($html,$client,$crawler);
                        }
                        return $replies;
                }   

                return false; 
            }

            private function multiArray(array $array){
                        foreach ($array as $key => $value) {
                                if(is_array($value)){
                                        $replies = $this->multiArray($value);
                                }else{
                                        $replies[] = $value;
                                }
                                
                        }

                        return ($replies);
            }

        } 
