<?php

        namespace Framework\Lib\Security\Data;
        use Framework\Exception\SecurityException as SecurityException;
        use Framework\Lib\Security\Data\FilterData as FilterData;

        Class StringFilter extends FilterData 
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
                            $vailedDataType  = (!is_string($this->dirtyData))? $this->errorMsg[$this->htmlAttribute][] = [$this->htmlAttribute.'@error@InvailedData'=>'Please Enter Vailed Data in ' . $this->htmlAttribute . ' Field']:true;
                            $vailedMaxLength = (strlen($this->dirtyData) > $this->maxLength) ? $this->errorMsg[ $this->htmlAttribute][] = [$this->htmlAttribute.'@error@MaxLength'=>'Max Length Required ' . $this->maxLength . ' Characters At ' . $this->htmlAttribute . ' Field']:true;
                            $vailedMinLength = (strlen($this->dirtyData) < $this->minLength) ? $this->errorMsg[ $this->htmlAttribute][] = [$this->htmlAttribute.'@error@MinLength'=>'Min Length Required ' . $this->minLength . ' Characters At ' . $this->htmlAttribute . ' Field']:true;
                            // I Think This Pattern Can Be Replaced By The Above
                            //^[a-z](?:_?[a-z0-9]+){"+min+","+max+"}$
                            //$isString = (!preg_match ('/^[A-Z \'.-]{'.$this->minLength.','.$this->maxLength.'}$/i', $this->dirtyData)) ? $this->errorMsg[$this->htmlAttribute][] = [$this->htmlAttribute.'@error@InvailedData'=>'Please Enter Vailed Data in ' . $this->htmlAttribute . ' Field']:true;
                            //$isString = (!preg_match ("/^[a-z](?:_?[a-z0-9]+){".$this->minLength.",".$this->maxLength."}$/i", $this->dirtyData)) ? $this->errorMsg[$this->htmlAttribute][] = [$this->htmlAttribute.'@error@InvailedData'=>'Please Enter Vailed Data in ' . $this->htmlAttribute . ' Field']:true;

                    }else{
                        $this->errorMsg[$this->htmlAttribute] = [$this->htmlAttribute.'@error@EmptyData'=>EMPTY_FEILD]; 
                    }
                    
                    return true;

            }

            protected function santizeData():array{
                /**
                * santize Data As String
                */
                $clearData = filter_var($this->dirtyData , FILTER_SANITIZE_STRING);
                return [$this->htmlAttribute=>$clearData]; //Data That Can Be Used In DataBase Or View To User
            }


        }

