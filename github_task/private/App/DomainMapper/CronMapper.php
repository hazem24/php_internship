<?php
            namespace App\DomainMapper;
            use Framework\Shared\Model;
            use Framework\Lib\Database\DataMapper\AbstractDataMapper;
            use Framework\Helper\ArrayHelper;
            use Framework\Lib\DataBase\DataMapper\Collections\AbstractGeneratorCollection as Collection;
            use Framework\Lib\DataBase\Query\QueryBuilder\InsertQueryBuilder;
            use Framework\Lib\DataBase\Query\QueryBuilder\SelectQueryBuilder;




            Class CronMapper extends AbstractDataMapper
            {   
                /**
                *@param userData Must Be Assoc. Array ['userIndentifier' => 'val']
                * userIndentifier => email Or UserName
                */
                public function insertNewCron(string $cron){
                                $stm = new InsertQueryBuilder;
                                $cron_data = ['tweet_cron'=>$cron];
                                $stm = $stm->insert('gotrend_cron',$cron_data)->createQuery();
                                $insertCron = $this->pdo->prepare($stm['query']);
                                $this->bindParamCreator(1,$insertCron,$stm['data']);
                                $insertCron->execute();
                }
                public function cronExists(string $tweet_id){
                        $stm = new SelectQueryBuilder;
                        $stm = $stm->select()->from('gotrend_cron')->where([' tweet_cron = ? '=>$tweet_id])->createQuery();
                        $cron_exists = $this->pdo->prepare($stm['query']);
                        $this->bindParamCreator(1,$cron_exists , $stm['data']);
                        $cron_exists->execute();
                        $result = $cron_exists->fetch(\PDO::FETCH_ASSOC);
                        if(is_array($result) && $result !== false){
                                
                                return true;
                        }
                        return false; // cron Not Found                     
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