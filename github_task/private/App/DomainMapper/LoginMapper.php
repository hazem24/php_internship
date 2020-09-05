<?php
            namespace App\DomainMapper;
            use Framework\Shared\Model;
            use Framework\Lib\Database\DataMapper\AbstractDataMapper;
            use Framework\Helper\ArrayHelper;
            use Framework\Lib\DataBase\DataMapper\Collections\AbstractGeneratorCollection as Collection;
            use Framework\Lib\DataBase\Query\QueryBuilder\InsertQueryBuilder;




            Class LoginMapper extends AbstractDataMapper
            {   
                /**
                *@param userData Must Be Assoc. Array ['userIndentifier' => 'val']
                * userIndentifier => email Or UserName
                */
                public function checkLoginData(int $id){
                        $stm = $this->selectBuilder->select()->from('user')->where(['github_id = ?'=>$id])->createQuery();
                        $checkLogin = $this->pdo->prepare($stm['query']);
                        $this->bindParamCreator(1,$checkLogin , [$id]);
                        $checkLogin->execute();
                        $result = $checkLogin->fetch(\PDO::FETCH_ASSOC);
                        if(is_array($result) && $result !== false){
                                return $result;               
                        }
                        return false; // userNot Found Not Found 
                } 

                public function getUserByEmail(string $email)
                {
                        $stm = $this->selectBuilder->select()->from('user')->where(['email = ?'=>$email])->createQuery();
                        $checkLogin = $this->pdo->prepare($stm['query']);
                        $this->bindParamCreator(1,$checkLogin , [$email]);
                        $checkLogin->execute();
                        $result = $checkLogin->fetch(\PDO::FETCH_ASSOC);
                        if(is_array($result) && $result !== false){
                                return $result;               
                        }
                        return false; // userNot Found Not Found 
                }

                public function getUserByUserName(string $username)
                {
                        $stm = $this->selectBuilder->select()->from('user')->where(['username = ?'=>$username])->createQuery();
                        $checkLogin = $this->pdo->prepare($stm['query']);
                        $this->bindParamCreator(1,$checkLogin , [$username]);
                        $checkLogin->execute();
                        $result = $checkLogin->fetch(\PDO::FETCH_ASSOC);
                        if(is_array($result) && $result !== false){
                                return $result;               
                        }
                        return false; // userNot Found Not Found
                }
                public function addNewUser(string $username, string $password, string $email, string $github_id)
                {
                        //Insert New Publish Model.
                        $insertBuilder = new InsertQueryBuilder;
                        $stm = $insertBuilder->insert('user',['github_id'=>$github_id,
                        'password'=>$password,'username'=>$username,
                        'email'=>$email])->createQuery();
                        $newPublish  = $this->pdo->prepare($stm['query']);
                        $this->bindParamCreator(4,$newPublish,$stm['data']);
                        $newPublish->execute();
                        if($newPublish->rowCount() > 0)
                        {
                                return true;  //Saved Succesfully.
                        }
                                return false; //Not Saved.
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