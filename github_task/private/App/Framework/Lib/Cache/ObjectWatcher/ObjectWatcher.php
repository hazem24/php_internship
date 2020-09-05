<?php 

        namespace Framework\Lib\Cache\ObjectWatcher;
        use Framework\Shared\Model as Model;

        Class ObjectWatcher 
        {
            private static $_instances = null;
            private  $object = [];
            private  $dirty = [];
            private  $new = [];
            private  $delete = [];

            public static function watcherInstance():self{
                if(is_null(self::$_instances)){
                        self::$_instances = new ObjectWatcher;
                }
                    return self::$_instances;
            }

           
            public static function addObject(Model $model){
                        $unique = self::globalKey($model);
                        $ins =  self::watcherInstance();
                        $ins->object[$unique] = $model;
            }

            public static function addDirty(Model $model){
                    $instance = self::watcherInstance();
                    if(!in_array($model,$instance->dirty,true)){
                            $instance->dirty[$instance->globalKey($model)] = $model;

                    }

            }

            public static function addNew(Model $model){
                    $instance = self::watcherInstance();
                    $instance->new[] = $model;
            }

            public static function addDelete(Model $model){
                    $instance = self::watcherInstance();
                    $instance->delete[$instance->globalKey($model)] = $model;
            }

            public static function clean(Model $model){
                    $instance = ObjectWatcher::watcherInstance();
                    unset($instance->delete[$instance->globalKey($model)]);
                    unset($instance->dirty[$instance->globalKey($model)]);
                    //Closure Need Refactor
                    $instance->new = array_filter(
                                    $instance->new,
                                    function ($a) use ($model) {
                                    return !($a === $obj);
                                    }
                        );


            }
            /**
            *@method performOpertion This Function Must Be Call From Controller Or Helper
            *When Create Bussiness Logic I Must Add delete To It 
            *It Must Provide Delete Statement 
            */
            public function performOperation(){
                    if(!empty($this->dirty)){
                              foreach($this->dirty as $key => $model){
                                    /**
                                    *When Create Bussiness Model I Must Put Here Checks For Success Of Opertion Or Not
                                    *And Return Msg To User For Success Or Fail
                                    */
                                            $model->getFinder()->save($model); // Get Finder is method Found Inside model To Determine Each Mapper Must Use
                                }
                    }
                  
                    if(!empty($this->new)){
                         foreach($this->new as $key => $model){
                        /**
                        *When Create Bussiness Model I Must Put Here Checks For Success Of Opertion Or Not
                        *And Return Msg To User For Success Or Fail
                        */
                                $model->getFinder()->save($model);
                        }

                    }
                   
                    $this->dirty = [];
                    $this->new = [];

            }
            private static function globalKey(Model $model):string{
                            return get_class($model) ."::". $model->getId();
            }
            public static function exists(string $className , int $id){
                       $ins =  self::watcherInstance();
                       $objectHere = (array_key_exists($className . "::" . $id ,$ins->object)) ? $ins->object[$className . "::" . $id] : null;
                       return $objectHere;
            }

            private  function  __construct(){

            }

            private function __clone(){

            }




        }