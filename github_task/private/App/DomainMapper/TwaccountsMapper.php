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




            Class TwaccountsMapper extends AbstractDataMapper
            {   
                /**
                *@param userData Must Be Assoc. Array ['userIndentifier' => 'val']
                * userIndentifier => email || UserName
                */
                public function insertAccount(array $info,int $userId){
                        if($this->accountExists($info['screenName'],$info['email']) === false){
                                $stm = new InsertQueryBuilder;
                                $info['access_id'] = $userId;
                                $stm = $stm->insert('tw_accounts',$info)->createQuery();
                                $insertAccount = $this->pdo->prepare($stm['query']);
                                $this->bindParamCreator(count($info),$insertAccount,$stm['data']);
                                $insertAccount->execute();
                                if($insertAccount->rowCount() >= 1){
                                            $id = $this->pdo->lastInsertId();
                                            $this->addFunctions($id,$userId);    
                                            return true;
                                }
                                            return false;                      
                        }else{
                                            return true;
                        }
    
                }
                /**
                *@method showAccounts Will Be Use In Cron Job To Get Data Of Accounts Which Will Create The Job ..
                */
                public function showAccounts(int $userId){
                        $stm = new SelectQueryBuilder;
                        //join('tw_functions' , 'tw_accounts.id','tw_account_id')
                        if($userId == 87){
                                $stm = $stm->select(['tw_accounts.id','phone','auto_tweet','auto_follow','auto_reaction','tweet_count','follow_count','follower_count','image_path','screenName','email','password','add_at','tw_name'])->from('tw_accounts')->join('tw_functions' , 'tw_accounts.id','tw_account_id')->createQuery();
                                $userExists = $this->pdo->prepare($stm['query']);
                        }else{
                                $stm = $stm->select(['tw_accounts.id','phone','auto_tweet','auto_follow','auto_reaction','tweet_count','follow_count','follower_count','image_path','screenName','email','password','add_at','tw_name'])->from('tw_accounts')->join('tw_functions' , 'tw_accounts.id','tw_account_id')->
                                where(['tw_accounts.access_id = ?' =>$userId])->createQuery();
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

                public function deleteAccount(int $userId,int $account_id){
                        $deleteBuilder = new DeleteQueryBuilder;
                        //viewerUser Want To unFollow visitedUser
                        $stm = $deleteBuilder->delete('tw_accounts')->where(['tw_accounts.id = ?'=>$account_id])->createQuery();
                        $delete = $this->pdo->prepare($stm['query']);
                        $this->bindParamCreator(1,$delete,$stm['data']);
                        $delete->execute();
                        if($delete->rowCount() >= 1){
                            return true;
                        }
                            return false;
                }

                public function editAccounts($userId,$auto_follow,$auto_tweet,$auto_reaction){
                        $updateBuilder = new UpdateQueryBuilder;
                        $stm = $updateBuilder->update('tw_functions' , ['auto_follow'=>$auto_follow , 'auto_tweet'=>$auto_tweet,'auto_reaction'=>$auto_reaction])->where(['tw_account_access_id = ? '=>$userId])->createQuery();
                        $publishPost = $this->pdo->prepare($stm['query']);
                        $stm['data'] = array_merge($stm['updateto'],$stm['data']);
                        $this->bindParamCreator(4,$publishPost,$stm['data']); 
                        $publishPost->execute();
                        if($publishPost->rowCount() >= 1){
                                 return ['edited'=>true];
                        }
                                 return false;
  
                }

                private function accountExists(string $screenName , string $email){
                        $stm = new SelectQueryBuilder;
                        $stm = $stm->select()->from('tw_accounts')->where(['screenName = ? || '=>$screenName,'  email = ? '=>$email])->createQuery();
                        $userExists = $this->pdo->prepare($stm['query']);
                        $this->bindParamCreator(2,$userExists , $stm['data']);
                        $userExists->execute();
                        $result = $userExists->fetch(\PDO::FETCH_ASSOC);
                        if(is_array($result) && $result !== false){
                                
                                return true;
                        }
                        return false; // User Not Found                     
                }

                private function addFunctions(int $account_id , int $userId){
                        $stm = new InsertQueryBuilder;
                        $stm = $stm->insert('tw_functions',['tw_account_id'=>$account_id,'tw_account_access_id'=>$userId])->createQuery();
                        $insertAccount = $this->pdo->prepare($stm['query']);
                        $this->bindParamCreator(2,$insertAccount,$stm['data']);
                        $insertAccount->execute();
                        if($insertAccount->rowCount() >= 1){    
                                    return true;
                        }
                                    return false;                      
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