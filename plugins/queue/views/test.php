<?php if(!defined('APPLICATION')) exit();
?>
<h1>hi</hi>
test
<?php
	// This "starts" our form.
	echo $this->Form->Open(array('action'=>Url('queue/process',true)));
	// Show errors here, at the top of the form.
	echo $this->Form->Errors();
	echo $this->Form->Label('RSN', 'test');
	echo $this->Form->TextBox('test', array('class' => 'TextBox'));
?>
<div>
	<?php
		echo $this->Form->Button('Submit');
		echo $this->Form->Button('Cancel');
		echo $this->Form->Close();
	?>
</div>
