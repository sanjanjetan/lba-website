<?php if(!defined('APPLICATION')) exit();
	
	class QueueModel extends VanillaModel{
		public function getQueue(){
			$queue = new Gdn_Model('User');
			$SQL = $queue->SQL
			->Select('*')
			->From('Queue q');
			
			$Sender->UserData = $SQL->Get();
			return $Sender->UserData;
		}
		
		public function getBxp($id){
			$leech = new Gdn_Model('User');
			$SQL = $leech->SQL
			->Select('*')
			->From('QueueBXP bxp')
			->Where('QueueID',$id);
			
			$Sender->UserData = $SQL->Get();
			return $Sender->UserData;
		}
		public function getPoints($id){
			$leech = new Gdn_Model('User');
			$SQL = $leech->SQL
			->Select('*')
			->From('QueueMisc misc')
			->Where('QueueID',$id);
			
			$Sender->UserData = $SQL->Get();
			return $Sender->UserData;
		}
	
	}
?>
