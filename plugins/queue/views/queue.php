<?php if (!defined('APPLICATION')) exit();
	//import session
	$Session = Gdn::Session();
	?>

<h1>Queue</h1>
<div class='queue'>
<table id='queue' class="display nowrap" cellspacing="0" width="100%">
	<thead>
		<tr>
			<th>&#35;</th>
			<th>ID</th>
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
	$count=0;
	foreach ($queue as $entry) {
		//generate a view here
		/**
		 * Table
		 * ID	|	Date		|	RSN		|	Needs		| UNLOCK		| NOTES		| action
		 */
		$count+=1;
		echo "<tr>";
		echo "<td>".$count."</td>";
		//customer id
		echo "<td>".$entry->ID."</td>";
		
		//date added
		$format = 'Y-m-d H:i:s';
		$date = DateTime::createFromFormat($format, $entry->DateInserted);
		echo "<td>".$date->format('d/m')."</td>";
		echo "<td>".$entry->Rsn."</td>";
		
		/**
		 * browse for their needs/maybe use join TODO
		 */
		$needs=array();
		
		//normal mode
		if($entry->Queen=='1'){
			array_push($needs,"Queen");
		}
		//king
		if((int)$entry->King>0){
			array_push($needs,"King kill");
		}
		//bxp
		$bxp = QueueModel::getBxp((int)$entry->ID);
		foreach($bxp as $i){ //sanity check
			$skill="unknown";
			switch($i->Skill){
				case '1':
					$skill="agility";
					break;
				case '2':
					$skill="mining";
					break;
				case '3':
					$skill="firemaking";
					break;
			}
			$str = number_format($i->Amount)." ".$skill." bxp";
			array_push($needs,$str);
		}
		//points
		$points = QueueModel::getPoints((int)$entry->ID);
		foreach($points as $i){ //sanity check but there can only be one anyway
			$temp=array();
			if((int)$i->att>0){
				array_push($temp,"<img src='/images/icn_att.png' height='16px' alt='att'> ".$i->att."&#09;<span style='display:none;'>att points</span>");
			}
			if((int)$i->col){
				array_push($temp,"<img src='/images/icn_col.png' height='16px'> ".$i->col."&#09;<span style='display:none;'>col points</span>");
			}
			if(count($temp)==2){
				array_push($needs,join('',$temp));
				$temp=array();
			}
			if((int)$i->def>0){
				array_push($temp,"<img src='/images/icn_def.png' height='16px'> ".$i->def."&#09;<span style='display:none;'>def points</span>");
			}
			if(count($temp)==2){
				array_push($needs,join('',$temp));
				$temp=array();
			}
			if((int)$i->heal>0){
				array_push($temp,"<img src='/images/icn_heal.png' height='16px'> ".$i->heal."&#09;<span style='display:none;'>heal points</span>");
			}
			if(count($temp)>1){
				array_push($needs,join('',$temp));
				$temp=array();
			}
		}
		echo "<td>";
		echo join('<br>', $needs);
		echo "</td>";
		
		
		//unlock => can access up to and including this wave
		$wave="NM";
		if($entry->Unlock>10){
			$wave="HM";
		}
		$w =($entry->Unlock%10);
		if($w==0) $w=10;
		$wave=$wave.$w;
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
	<div class='queue-new'>
		<?php

		?>
</div>
</div>
</div>
