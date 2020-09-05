<?php

        namespace Framework\Lib\Security\Data;
        use Framework\Exception\SecurityException as SecurityException;
        use Framework\Lib\Security\Data\FilterData as FilterData;

        Class UrlFilter extends FilterData 
        {


            protected function validateData():bool{
                    /**
                    * Vaildate Type Of Data String 
                    * Length Of Data 
                    * Pattern Data If Can 
                    * Use Filter Var For Vaildation No Filter Var For Vaildation In String 
                    */
                    
                    parent::validateData();
                    $this->dirtyData = $this->trimData($this->dirtyData);
                    if(!empty($this->dirtyData))
                    {
                        /**
                        *@forget Don't Forget This Note 
                        *If Bussiness Model Need Flag Like 
                        * FILTER_FLAG_SCHEME_REQUIRED || FILTER_FLAG_HOST_REQUIRED || FILTER_FLAG_PATH_REQUIRED ||FILTER_FLAG_QUERY_REQUIRED
                        *I Must Add It ! 
                        */
                        $validateString = (filter_var($this->dirtyData , FILTER_VALIDATE_URL ,FILTER_FLAG_SCHEME_REQUIRED) === false) ? $this->errorMsg[$this->htmlAttribute][] = [$this->htmlAttribute.'@error@InvailedData'=>'Please Enter Vailed Data In ' . $this->htmlAttribute . ' Field Url(Links) Only Allowed']: true;
                        $vailedMaxLength = (strlen($this->dirtyData) > $this->maxLength) ? $this->errorMsg[ $this->htmlAttribute][] = [$this->htmlAttribute.'@error@MaxLength'=>'Max Length Required ' . $this->maxLength . ' Characters']:true;
                        $vailedMinLength = (strlen($this->dirtyData) < $this->minLength) ? $this->errorMsg[ $this->htmlAttribute][] = [$this->htmlAttribute.'@error@MinLength'=>'Min Length Required ' . $this->minLength . ' Characters']:true;

                    }else{
                        $this->errorMsg[$this->htmlAttribute][] = [$this->htmlAttribute.'@error@EmptyData'=>'Please Fill  Data At ' . $this->htmlAttribute . ' Can Not Be Empty']; 
                    }
                    
                    return true;

            }

            protected function santizeData():array{
                /**
                * santize Data As Url
                */
                $clearData = filter_var($this->dirtyData , FILTER_SANITIZE_URL);
                return [$this->htmlAttribute=>$clearData]; //Data That Can Be Used In DataBase Or View To User
            }


        }

