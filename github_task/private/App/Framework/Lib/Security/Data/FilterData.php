<?php 

        namespace Framework\Lib\Security\Data;

        use Framework\Exception\SecurityException as SecurityException;
        use Framework\ConstructorClass as ConstructorClass;
        /**
        *@class 
        *This Class Abstract Class For All Data Type Filter : json - string - integer - ip - float - email
        */
        Abstract Class FilterData extends ConstructorClass
        {

            protected $dirtyData;
            protected $htmlAttribute;
            protected $maxLength;
            protected $minLength;
            protected $pattern;
            protected $errorMsg = [];
            protected $clearData= [];
            protected $option = [];
            /**
            *['Name'=>'value','max'=>'length','min'=>'length',[option]]
            *option set for drop down menu or select value as Constant For integer or float or string or anything
            */
            public function setDirtyData(string $htmlAttribute , array $dirtyData , array $option = null){
                $this->htmlAttribute = $htmlAttribute;
                $this->option = $option;
                $this->dirtyData = $dirtyData['value'];
                $this->maxLength = $dirtyData['max'];
                $this->minLength = $dirtyData['min'];
                return $this;
            }
            protected function trimData($data){
                    return trim($data);
            }

             public function proccessFilter(){
                /**
                * Create Two Steps :-
                        1- validateData from its specific method
                        2- santizeData  from its specific method
                 * check if there is any error in the errorMsg
                        if Yes  Create Web Error View To See By User
                        if not return the clearedData      
                */
                        $this->validateData();
                        
                        if(!array_key_exists($this->htmlAttribute , $this->errorMsg)){
                                
                                //Santize Data
                                $clearData = $this->santizeData(); // Good Data At This Property
                                //Return Good Data
                                return $clearData[$this->htmlAttribute];

                        }else{
                                // Return WebView Error
                               
                                return  $this->errorMsg[$this->htmlAttribute];
                        }

                        throw new SecurityException("No Data Returned In Process Filter @ Class " . get_class($this));
                        

            }

            protected function validateData():bool{
                   if(isset($this->option) && !is_null($this->option)){
                            $valuesFound = (in_array($this->dirtyData , $this->option)) ? true : false;
                            if(!$valuesFound){
                                    $this->errorMsg[$this->htmlAttribute][] = [$this->htmlAttribute.'@error@option'=>'This Information Only Allowed (' . implode(',',$this->option) .')']; 
                            }
                    }
                    return true; 
            }
            Abstract protected function santizeData():array;
            // i think for web view error msg function
        }
