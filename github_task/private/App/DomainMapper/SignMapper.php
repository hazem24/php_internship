<?php
            namespace App\DomainMapper;
            use Framework\Shared\Model;
            use Framework\Lib\Database\DataMapper\AbstractDataMapper;
            use Framework\Helper\ArrayHelper;
            use Framework\Lib\DataBase\Query\QueryBuilder\InsertQueryBuilder;            
            use Framework\Lib\DataBase\DataMapper\Collections\AbstractGeneratorCollection as Collection;





            Class SignMapper extends AbstractDataMapper
            {   
                /**
                *@param userData Must Be Assoc. Array ['userIndentifier' => 'val']
                * userIndentifier => email Or UserName
                */
                public function newUser(string $accessCode , string $ip){
                    if($this->userExists($ip) !== true){
                            $stm = new InsertQueryBuilder;
                            $stm = $stm->insert('access',['ip'=>$ip,'ac_code'=>$accessCode])->createQuery();
                            $addUser = $this->pdo->prepare($stm['query']);
                            $this->bindParamCreator(2,$addUser,$stm['data']);
                            $addUser->execute();
                            if($addUser->rowCount() >= 1){
                                        return ['accessCode'=>$accessCode,'userAdd'=>true]; 
                            }            
                                        return ['userNotAdd'=>true];
                    }else{
                        return ["ipFound"=>true];
                    }
                }
                
                private function userExists(string $ip){
                    $stm = $this->selectBuilder->select()->from('access')->where(['ip = ?'=>$ip])->createQuery();
                    $userExists = $this->pdo->prepare($stm['query']);
                    $this->bindParamCreator(1,$userExists , [$ip]);
                    $userExists->execute();
                    $result = $userExists->fetch(\PDO::FETCH_ASSOC);
                    if(is_array($result) && $result !== false){
                            
                            return true;
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