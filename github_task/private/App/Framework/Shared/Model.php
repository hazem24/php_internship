<?php

        namespace Framework\Shared;
        use  Framework\Lib\Cache\ObjectWatcher\ObjectWatcher as ObjectWatcher;
        use Framework\ConstructorClass;

        /**
        *This Class Provide And Interface Of All Model Child 
        *Each Model Must Extends From This Class
        */
        Abstract class Model extends ConstructorClass
        {

                public function markNew(){
                        ObjectWatcher::addNew($this);
                }

                public function markDeleted(){
                        ObjectWatcher::addDelete($this);
                }


                 public function markDirty(){
                        ObjectWatcher::addDirty($this);
                }

                public function markClean(){
                        ObjectWatcher::addClean($this);
                }

            
        }