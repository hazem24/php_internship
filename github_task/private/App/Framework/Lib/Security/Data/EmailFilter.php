<?php

        namespace Framework\Lib\Security\Data;
        use Framework\Exception\SecurityException as SecurityException;
        use Framework\Lib\Security\Data\FilterData as FilterData;

        Class EmailFilter extends FilterData 
        {
            
            protected function validateData():bool{
                    /**
                    * Vaildate Type Of Data String 
                    * Length Of Data 
                    * Pattern Data If Can 
                    * Use Filter Var For Vaildation No Filter Var For Vaildation In String 
                    */
                    
                    parent::validateData();
                    $this->dirtyData = (string)$this->trimData($this->dirtyData);
                   
                    if(!empty($this->dirtyData))
                    {
                        $validateString = (filter_var($this->dirtyData , FILTER_VALIDATE_EMAIL ,'FILTER_FLAG_EMAIL_UNICODE') === (false || 0  )) ? $this->errorMsg[$this->htmlAttribute][] = [$this->htmlAttribute.'@error@InvailedData'=>'Please Enter Vailed Email Address In ' . $this->htmlAttribute . ' Field ']: true;
                        $vailedMaxLength = (strlen($this->dirtyData) > $this->maxLength) ? $this->errorMsg[ $this->htmlAttribute][] = [$this->htmlAttribute.'@error@MaxLength'=>'Max Length Required ' . $this->maxLength . ' Characters']:true;
                        $vailedMinLength = (strlen($this->dirtyData) < $this->minLength) ? $this->errorMsg[ $this->htmlAttribute][] = [$this->htmlAttribute.'@error@MinLength'=>'Min Length Required ' . $this->minLength . ' Characters']:true;

                    }else{
                        $this->errorMsg[$this->htmlAttribute] = [$this->htmlAttribute.'@error@EmptyData'=>'Please Fill  Data At ' . $this->htmlAttribute . ' Can Not Be Empty']; 
                    }
                    
                    return true;

            }

            protected function santizeData():array{
                /**
                * santize Data As String
                */
                $clearData = filter_var($this->dirtyData , FILTER_SANITIZE_EMAIL);
                return [$this->htmlAttribute=>$clearData]; //Data That Can Be Used In DataBase Or View To User
            }


        }

