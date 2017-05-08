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
	
	}
?>
