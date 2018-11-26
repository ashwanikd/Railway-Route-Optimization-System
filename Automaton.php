<?php
    class FiniteAutomaton{
        var $states;
        var $currentstate;
        var $startstate;
        var $finalstate;
        var $n;
        function __construct($a){
            $this->states = $a;
            $this->startstate = 0;
            $this->n = count($this->states);
            $this->finalstate = $this->n-1;
        }
        function setCurrentState($x){
            $this->currentstate = $this->getState($x);
        }
        function getState($name){
            for($i=0;$i<count($this->states);$i++){
                if($name == $this->states[$i]){
                    return $i;
                }
            }
        }
        function transition($name){
                if($this->getState($name) == 0){
                    $this->currentstate--;
                    return false;
                }else if($this->getState($name) == $this->n-1){
                    $this->currentstate++;
                    return false;
                }else if($this->getState($name) == $this->currentstate+1){
                    $this->currentstate++;
                    return true;
                }else if($this->getState($name) == $this->currentstate-1){
                    $this->currentstate--;
                    return true;
                }
            
        }
    }
?>