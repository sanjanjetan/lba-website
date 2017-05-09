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
			<th><span class="glyphicon glyphicon-globe"></th>
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
			array_push($needs,"1-10NM");
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
			$str = number_format($i->Amount)." ".$skill." bxp<span style='display:none;'>6-9</span>";
			array_push($needs,$str);
		}
		//points
		$points = QueueModel::getPoints((int)$entry->ID);
		foreach($points as $i){ //sanity check but there can only be one anyway
			$temp=array();
			if((int)$i->att>0){
				array_push($temp,"<img src='/images/icn_att.png' height='16px' alt='att'> ".$i->att."&#09;<span style='display:none;'>att points 6-9</span>");
			}
			if((int)$i->col){
				array_push($temp,"<img src='/images/icn_col.png' height='16px'> ".$i->col."&#09;<span style='display:none;'>col points 6-9</span>");
			}
			if(count($temp)==2){
				array_push($needs,join('',$temp));
				$temp=array();
			}
			if((int)$i->def>0){
				array_push($temp,"<img src='/images/icn_def.png' height='16px'> ".$i->def."&#09;<span style='display:none;'>def points 6-9</span>");
			}
			if(count($temp)==2){
				array_push($needs,join('',$temp));
				$temp=array();
			}
			if((int)$i->heal>0){
				array_push($temp,"<img src='/images/icn_heal.png' height='16px'> ".$i->heal."&#09;<span style='display:none;'>heal points 6-9</span>");
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
		echo "<td><span class='glyphicon glyphicon-pencil'></span><span class='glyphicon glyphicon-remove'></span></td>";
		echo "</tr>";
	}
	?>
	</tbody>
</table>

<?php
//interface to be shown if member
	if (CheckPermission('Plugins.Queue.ClanView')) {
		echo "<div class='queue-options'>";
	
		echo "TODO <br> Hide this interface, only to be opened by some button";
		echo "<div class='queue-new'>";
				//init form, addLeech=form
				echo $this->addLeech->Open(array('action'=>Url('queue/process',true)));
				echo $this->addLeech->Errors();
			echo "<div>";
				echo $this->addLeech->Label('RSN: ', 'rsn');
				echo $this->addLeech->TextBox('rsn', array('class' => 'TextBox'));
			echo "</div>";
			echo "<div>";
				echo $this->addLeech->Label('Access: ', 'access');
				//generate the list of waves
				$access=array(
							  '1' => t('NM1'),
							  '2' => t('NM2'),
							  '3' => t('NM3'),
							  '4' => t('NM4'),
							  '5' => t('NM5'),
							  '6' => t('NM6'),
							  '7' => t('NM7'),
							  '8' => t('NM8'),
							  '9' => t('NM9'),
							  '10' => t('NM10'),
							  '11' => t('HM1'),
							  '12' => t('HM2'),
							  '13' => t('HM3'),
							  '14' => t('HM4'),
							  '15' => t('HM5'),
							  '16' => t('HM6'),
							  '17' => t('HM7'),
							  '18' => t('HM8'),
							  '19' => t('HM9'),
							  '20' => t('HM10')
						);
				echo $this->addLeech->DropDown('access',$access,array('value'=>'1'));
			echo "</div>";
			echo "<div>";
				echo $this->addLeech->Label('Ironman: ', 'ironman');
				$iron=array('1'=>t('Yes'),'0'=>t('No'));
				echo $this->addLeech->RadioList('ironman', $iron,array('default'=>'0'));
			echo "</div>";
			echo "<div>";
				echo $this->addLeech->Label('1L (if applicable): ', 'solo');
				$solo=array('1'=>t('Yes'),'0'=>t('No'));
				echo $this->addLeech->RadioList('solo', $solo,array('default'=>'0'));
			echo "</div>";
		
			//what leech they need, a lot of stuff here
			echo "<div>";
				echo $this->addLeech->Label('Leech: ', 'leech');
				$leech=array(
							'1' => t('1-10NM'),
							'2' => t('BXP'),
							'3' => t('Points')
						);
				echo $this->addLeech->DropDown('leech', $leech,array('value'=>'1'));
			echo "</div>";
		
		
			echo "<div>";
				echo $this->addLeech->Button('Add');
				echo $this->addLeech->Button('Cancel');
				echo $this->addLeech->Close();
			echo "</div>";
		echo "</div>";
		echo "</div>";
	}
?>
</div>
<br>
