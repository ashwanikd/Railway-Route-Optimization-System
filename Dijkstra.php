<?php
	//defining the node
	class Node{
		var $connection;
		var $vertex;
		function __construct($n){
				$this->connection = array();
				$this->vertex = $n;
		}
	}
	// defining the list node
	class listNode{
		var $distance;
		var $vertex;
		function __construct($name,$distance){
			$this->vertex = $name;
			$this->distance = $distance;
		}
	}
	//defining the algorithm
	class Dijkstra{
		var $Graph = array();
		var $visited = array();
		var $parentnode = array();
		var $weight = array();
		function __construct($graph){
			$this->Graph = $graph;
			$this->visited = $graph;
			$this->weight = $graph;
			foreach($this->visited as &$key){
				$key = 0;
			}
			foreach($this->weight as &$key){
				$key = 'inf';
			}
		}
		var $startnode;
		var $endnode;
		function findShortestPath($start,$end){
			$this->visited[$start] = true;
			$this->weight[$start] = 0;
			$this->endnode = $end;
			$this->startnode = $start;
			$this->algorithm($start);
		}
		function algorithm($start){
			$this->visited[$start] = true;
			if($this->visited[$this->endnode]) {
				return;
			}
			foreach($this->Graph[$start]->connection as &$key){
				if(!$this->visited[$key->vertex]){
					if($this->compare($this->weight[$key->vertex],$this->weight[$start] + $key->distance)) {
						$this->weight[$key->vertex] = $this->weight[$start] + $key->distance;
						$this->parentnode[$key->vertex] = $start; 
					}
				}
			}
			$min = 'inf';
			$i = '';
			foreach($this->Graph as &$key){
				if(!$this->visited[$key->vertex]){
					if($this->compare($min,$this->weight[$key->vertex])) {
						$i = $key->vertex;
						$min = $this->weight[$key->vertex];
					}
				}
			}
			$this->algorithm($i);
		}
		function printGraph(){
			echo 'graph is <br>';
			foreach($this->Graph as &$key){
				echo $key->vertex.' ==> ';
				foreach($key->connection as &$k){
					echo $k->vertex.'('.$k->distance.') , ';
				}
				echo '<br>';
			}
		}
		function getPath(){
			$path = array();
			$b = $this->endnode;
			$path[0] = $b;
			$i = 1;
			$a = $this->startnode;
			while(true) {
				$b = $this->parentnode[$b];
				if($b == $a){
					$path[$i++] = $b;
					break;
				}
				$path[$i++] = $b;
			}
			$path = array_reverse($path, false);
			return $path;
		}
		function compare($a,$b){
			if($a == 'inf') {
				return(1);
			}else if($b == 'inf') {
				return(0);
			}else if($a >= $b){
				return(1);
			}else return(0);
		}
	}
?>