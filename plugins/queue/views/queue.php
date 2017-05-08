<?php if (!defined('APPLICATION')) exit();
	//import session
	$Session = Gdn::Session();
	?>

<h1>Queue</h1>
<div class='queue'>
<table id='queue' class="display nowrap" cellspacing="0" width="100%">
	<thead>
		<tr>
			<th>Customer ID</th>
			<th>Date</th>
			<th>RSN</th>
			<th>Leech</th>
			<th>Access</th>
			<th>Notes</th>
			<th>Action</th>
		</tr>
	</thead>
	<tbody>
<?php
	//call database query to get queue
	$this->Queue = QueueModel::getQueue();
	$queue = $this->Queue;
	
	foreach ($queue as $entry) {
		//generate a view here
		/**
		 * Table
		 * ID	|	Date		|	RSN		|	Needs		| UNLOCK		| NOTES		| action
		 */
		echo "<tr>";
		//customer id
		echo "<td>".$entry->customerID."</td>";
		
		//date added
		$format = 'Y-m-d H:i:s';
		$date = DateTime::createFromFormat($format, $entry->DateInserted);
		echo "<td>".$date->format('d/m')."</td>";
		echo "<td>".$entry->Rsn."</td>";
		
		/**
		 * browse for their needs/maybe use join TODO
		 */
		echo "<td>placeholder</td>";
		//unlock => can access up to and including this wave
		$wave="NM";
		if($entry->Unlock>10){
			$wave="HM";
		}
		$wave=$wave.($entry->Unlock%10);
		echo "<td>".$wave."</td>";
		echo "<td>".$entry->Notes."</td>";
		echo "<td><span class='glyphicon glyphicon-remove'></span></td>";
		echo "</tr>";
	}
	?>
	</tbody>
</table>
<div class='queue-options'>
	TODO
</div>
</div>
