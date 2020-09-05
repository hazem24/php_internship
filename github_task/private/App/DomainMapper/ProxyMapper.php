<?php 


      
        namespace App\DomainMapper;
        use Framework\Lib\Database\DataMapper\AbstractDataMapper;
        use Framework\Shared\Model as Model;
        use Framework\Lib\DataBase\Query\QueryBuilder\InsertQueryBuilder;
        use Framework\Lib\DataBase\Query\QueryBuilder\SelectQueryBuilder;
        use Framework\Lib\DataBase\Query\QueryBuilder\DeleteQueryBuilder;
        use Framework\Lib\DataBase\Query\QueryBuilder\UpdateQueryBuilder;
        use Framework\Lib\DataBase\DataMapper\Collections\AbstractGeneratorCollection as Collection;

        class ProxyMapper extends AbstractDataMapper
        {
            protected $tableName = "proxy";
            protected $columns = ["primarykey"=>"id",
                                "proxy",
                                "timer","counter","users_id"];
            private $modelClass;//Until Now No Modal For This @Written 23/08/2017 @03:15Am
           
            

            public function proxyList(int $id){
                   $stm = new SelectQueryBuilder;
                   $stm = $stm->select()->from($this->tableName)->where([$this->columns[3].'=?'=>$id])->createQuery();
                   $proxyList = $this->pdo->prepare($stm['query']);
                   $this->bindParamCreator(1,$proxyList,$stm['data']);
                   $proxyList->execute();
                   $proxyList = $proxyList->fetchAll(\PDO::FETCH_ASSOC);
                   if($proxyList !== false && !empty($proxyList)){
                                return $proxyList;
                    } 
                                return false;   
            }


            public function insertProxyList(int $userId,string $proxy){
                if(!empty($proxy)){
                        $stm = new InsertQueryBuilder;
                        $stm = $stm->insert($this->tableName,[$this->columns[0]=>$proxy,$this->columns[3]=>$userId])->createQuery();
                        $newMailList = $this->pdo->prepare($stm['query']);
                        $this->bindParamCreator(2,$newMailList,$stm['data']);
                        $newMailList->execute();
                        if($newMailList->rowCount() >= 1){
                                    return true; 
                        }            
                }    
                                   return false;     
  
            }


            public function deleteProxy(int $userId , string $proxy){
                   $stm = new DeleteQueryBuilder;
                   $stm = $stm->delete($this->tableName)->where([$this->columns[3].'=?'=>$userId,'and '.$this->columns[0].'=?'=>$proxy])->createQuery();
                   $deleteProxy = $this->pdo->prepare($stm['query']);
                   $this->bindParamCreator(2,$deleteProxy,$stm['data']);
                   $deleteProxy->execute();
                   if($deleteProxy->rowCount() >= 1){
                                return true;
                   } 
                                return false;    
            }
            /**
            *@method lessThreeProxy This Method Used To Return Proxy List That Can Register Which Used Less Than Three Time In 24 Hour
            */
            public function lessThreeProxy(int $userId){
                   $stm = new SelectQueryBuilder;
                   $stm = $stm->select([$this->columns['primarykey'],$this->columns[0],$this->columns[2]])->from($this->tableName)->where([$this->columns[3].'=?'=>$userId ,'and '.$this->columns[2].'<?'=>3])->createQuery();
                   $proxy = $this->pdo->prepare($stm['query']);
                   $this->bindParamCreator(2,$proxy,$stm['data']);
                   $proxy->execute();
                   $proxyList = $proxy->fetchAll(\PDO::FETCH_ASSOC);
                   if($proxyList !== false && !empty($proxyList)){
                                return $proxyList;
                   }
                                return null;     

            }

            public function incrementProxy(int $userId , string $proxy , int $incrBy){
                   $stm = new UpdateQueryBuilder;
                   $stm = $stm->update($this->tableName,[$this->columns[2]=>$incrBy])
                   ->where([$this->columns[3].'=?'=>$userId,' and '.$this->columns[0] .'=?' => $proxy])->createQuery();
                   $increment = $this->pdo->prepare($stm['query']);
                   $this->bindParamCreator(3,$increment,array_merge($stm['updateto'],$stm['data']));
                   $increment->execute();
                   return true;     
            }

            public function updateProxy(){
                $stm = new UpdateQueryBuilder;
                $stm = $stm->update($this->tableName,[$this->columns[2]=>0])
                ->where(['DATEDIFF(NOW(),timer) >=?'=>1 ])->createQuery();
                $updateProxy = $this->pdo->prepare($stm['query']);
                $this->bindParamCreator(2,$updateProxy,array_merge($stm['updateto'],$stm['data']));
                $updateProxy->execute();
                return true;     

            }
            
            protected function createObject(array $fields):Model{
                    //Until Now No Modal For This @Written 23/08/2017 @03:13Am

            }
           
            protected function doSave(Model $model){

            }
            protected function getCollection(array $raw): Collection{

            }
            protected function selectAllStatement():\PDOStatement{

            }
        }
