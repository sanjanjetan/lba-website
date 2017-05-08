<?php if (!defined('APPLICATION')) exit();
	//import session
	$Session = Gdn::Session();
	?>

<h1>Queue</h1>
<div class='queue'>
<table id='queue'>
	<tr>
		<th>Customer ID</th>
		<th>Date</th>
		<th>RSN</th>
		<th>Leech</th>
		<th>Unlock</th>
		<th>Notes</th>
		<th>Action</th>
	</tr>
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
		echo "<td>".$entry->customerID."</td>";
		echo "<td>place</td>";
		echo "<td>".$entry->Rsn."</td>";
		
		/**
		 * browse for their needs/maybe use join TODO
		 */
		echo "<td>placeholder</td>";
		echo "<td>".$entry->Unlock."</td>";
		echo "<td>".$entry->Notes."</td>";
		echo "<td>placeholder</td>";
		echo "</tr>";
	}
	?>
</table>
</div>
