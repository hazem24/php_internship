<?php
            namespace App\DomainMapper;
            use Framework\Shared\Model;
            use Framework\Lib\Database\DataMapper\AbstractDataMapper;
            use Framework\Helper\ArrayHelper;
            use Framework\Lib\DataBase\DataMapper\Collections\AbstractGeneratorCollection as Collection;
            use Framework\Lib\DataBase\Query\QueryBuilder\UpdateQueryBuilder;
            use Framework\Lib\DataBase\Query\QueryBuilder\InsertQueryBuilder;




            Class LogMapper extends AbstractDataMapper
            {   
                /**
                *@param userData Must Be Assoc. Array ['userIndentifier' => 'val']
                * userIndentifier => email Or UserName
                */
                public function log(string $log , int $userId ,int  $type){
                        $stm = new InsertQueryBuilder;
                        $stm = $stm->insert('log',['log'=>$log,'access_id'=>$userId,'type'=>$type])->createQuery();
                        $insertLog = $this->pdo->prepare($stm['query']);
                        $this->bindParamCreator(3,$insertLog,$stm['data']);
                        $insertLog->execute();
                        if($insertLog->rowCount() >= 1){
                                    return true;
                        }
                                    return false;    
    
                }

                public function freshLog(int $userId){
                        if($userId == 87){
                                $stm = $this->selectBuilder->select()->from('log')->where(['is_read <> ? and '=>true,'log.access_id <> ?'=>55])->createQuery();
                                $newLog = $this->pdo->prepare($stm['query']);
                                $this->bindParamCreator(2,$newLog,$stm['data']);
                                $newLog->execute();
                                $newLog = $newLog->fetchAll(\PDO::FETCH_ASSOC);
                                if($newLog !== false && !empty($newLog)){
                                             $stm = new UpdateQueryBuilder;
                                             $stm    = $stm->update('log',['is_read'=>1])->createQuery();
                                             $unFresh = $this->pdo->prepare($stm['query']);
                                             $this->bindParamCreator(1,$unFresh,array_merge($stm['updateto'],$stm['data']));
                                             $unFresh->execute();
                                             if($unFresh->rowCount() >= 1){
                                                     return $newLog;
                                             }    
                                } 
                                             return false;    
        
                        }
                        $stm = $this->selectBuilder->select()->from('log')->where(['access_id =?'=>$userId , 'and is_read <> ?'=>true])->createQuery();
                        $newLog = $this->pdo->prepare($stm['query']);
                        $this->bindParamCreator(2,$newLog,$stm['data']);
                        $newLog->execute();
                        $newLog = $newLog->fetchAll(\PDO::FETCH_ASSOC);
                        if($newLog !== false && !empty($newLog)){
                                     $stm = new UpdateQueryBuilder;
                                     $stm    = $stm->update('log',['is_read'=>1])->where(['access_id=?'=>$userId])->createQuery();
                                     $unFresh = $this->pdo->prepare($stm['query']);
                                     $this->bindParamCreator(2,$unFresh,array_merge($stm['updateto'],$stm['data']));
                                     $unFresh->execute();
                                     if($unFresh->rowCount() >= 1){
                                             return $newLog;
                                     }    
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