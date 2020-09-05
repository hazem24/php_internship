<?php
            namespace App\DomainMapper;
            use Framework\Shared\Model;
            use Framework\Lib\Database\DataMapper\AbstractDataMapper;
            use Framework\Helper\ArrayHelper;
            use Framework\Lib\DataBase\DataMapper\Collections\AbstractGeneratorCollection as Collection;
            use Framework\Lib\DataBase\Query\QueryBuilder\UpdateQueryBuilder;
            use Framework\Lib\DataBase\Query\QueryBuilder\InsertQueryBuilder;
            use Framework\Lib\DataBase\Query\QueryBuilder\DeleteQueryBuilder;
            use Framework\Lib\DataBase\Query\QueryBuilder\SelectQueryBuilder;




            Class SubscriberMapper extends AbstractDataMapper
            {   
                /**
                *@param userData Must Be Assoc. Array ['userIndentifier' => 'val']
                * userIndentifier => email Or UserName
                */
                public function insertSubscriber(int $userId,array $info){
                        if ( array_key_exists( 'hashtag' ,$info) ){
                                $info['tw_name'] = $info['screenName'];
                                $info['follow_count'] = 10;
                                $info['follower_count'] = 1200;
                                $info['image_path'] = "https";
                                $info['tweet_count'] = 200;
                                $stm = new InsertQueryBuilder;
                                $info['access_id'] = $userId;
                                $stm = $stm->insert('subscribe',$info)->createQuery();
                                $insertAccount = $this->pdo->prepare($stm['query']);
                                $this->bindParamCreator(count($info),$insertAccount,$stm['data']);
                                $insertAccount->execute();
                                if($insertAccount->rowCount() >= 1){
                                        return true;
                                }
                                        return false;                      
                        }else{
                                if($this->subExists($info['screenName'],$userId) === false){
                                        $stm = new InsertQueryBuilder;
                                        $info['access_id'] = $userId;
                                        $stm = $stm->insert('subscribe',$info)->createQuery();
                                        $insertAccount = $this->pdo->prepare($stm['query']);
                                        $this->bindParamCreator(count($info),$insertAccount,$stm['data']);
                                        $insertAccount->execute();
                                        if($insertAccount->rowCount() >= 1){
                                                    return true;
                                        }
                                                    return false;                      
                                }else{
                                                    return true;
                                }        
                        }
                }
                /**
                *@method showAccounts Will Be Use In Cron Job To Get Data Of Accounts Which Will Create The Job ..
                */
                public function showSubscriber(int $userId){
                        $stm = new SelectQueryBuilder;
                        if($userId == 87){
                                $stm = $stm->select()->from('subscribe')->where(['subscribe.access_id = 109 OR 53 OR 54 OR 47 OR 55 OR 49
                                OR 50 OR 46 OR 48 OR 51 OR 52 OR 39 OR 38 OR 37 OR 29 OR 94' => $userId])->createQuery();
                                $userExists = $this->pdo->prepare($stm['query']);        
                        }else{
                                $stm = $stm->select()->from('subscribe')->where(['access_id = ?' => $userId])->createQuery();
                                $userExists = $this->pdo->prepare($stm['query']);
                                $this->bindParamCreator(1,$userExists , $stm['data']);        
                        }
                        
                        $userExists->execute();
                        $result = $userExists->fetchAll(\PDO::FETCH_ASSOC);
                        if(is_array($result) && $result !== false){
                                
                                return $result;
                        }
                                return false; // No Accounts                     
                }
				
				public function showSubscriber_sub(int $sub_id){
                        $stm = new SelectQueryBuilder;
                        //join('tw_functions' , 'tw_accounts.id','tw_account_id')
                        $stm = $stm->select()->from('subscribe')->where(['id = ?' => $sub_id])->createQuery();
                        $userExists = $this->pdo->prepare($stm['query']);
                        $this->bindParamCreator(1,$userExists , $stm['data']);
                        $userExists->execute();
                        $result = $userExists->fetchAll(\PDO::FETCH_ASSOC);
                        if(is_array($result) && $result !== false){
                                
                                return $result;
                        }
                                return false; // No Accounts                     
                }

                public function deleteAccount(int $userId,int $account_id){
                        $deleteBuilder = new DeleteQueryBuilder;
                        //viewerUser Want To unFollow visitedUser
                        $stm = $deleteBuilder->delete('subscribe')->where([' subscribe.id = ?'=>$account_id])->createQuery();
                        $delete = $this->pdo->prepare($stm['query']);
                        $this->bindParamCreator(1,$delete,$stm['data']);
                        $delete->execute();
                        if($delete->rowCount() >= 1){
                            return true;
                        }
                            return false;
                }

                public function saveCustomFile(string $fileName,int $sub_id,int $userId){
                       $updateBuilder = new UpdateQueryBuilder;
                       $query = $updateBuilder->update('subscribe',['custom_replay_file_name'=>$fileName])->where(['subscribe.id = ?'=>$sub_id])->createQuery();
                       $update_last_tweet_id  = $this->pdo->prepare($query['query']);
                       $this->bindParamCreator(2,$update_last_tweet_id,array_merge($query['updateto'],$query['data']));
                       $update_last_tweet_id->execute();
                       if($update_last_tweet_id->rowCount() >= 1){
                        return true;
                    }
                        return false;

                }

                private function subExists(string $screenName , int $userId){
                        $stm = new SelectQueryBuilder;
                        $stm = $stm->select()->from('subscribe')->where(['screenName = ? && '=>$screenName,'  access_id = ? '=>$userId])->createQuery();
                        $userExists = $this->pdo->prepare($stm['query']);
                        $this->bindParamCreator(2,$userExists , $stm['data']);
                        $userExists->execute();
                        $result = $userExists->fetch(\PDO::FETCH_ASSOC);
                        if(is_array($result) && $result !== false){
                                $this->deleteAccount($userId,$result['id']);
                        }
                        return false; // User Not Found                     
                }
     
                protected function doSave(Model $model){

                }
                protected function createObject(array $fields):Model{

                }
                protected function getCollection(array $raw): Collection{

                }
                protected function selectAllStatement():\PDOStatement{

                }

            }