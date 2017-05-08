<?php
/**
 * Queue
 *
 * @copyright 2008-2014 Vanilla Forums, Inc.
 * @license GNU GPLv2
 */
// Define the plugin:
$PluginInfo['queue'] = array(
	'Name' => 'Queue',
    'Description' => 'Queue by Jia for Leech BA ~ Runescape',
    'Version' => '1.0.9',
    //'RequiredApplications' => array('Vanilla' => '2.1'), //TODO update if necessary
    'License' => 'GNU GPL2',
    'SettingsUrl' => '/settings/queue',
	//view queue, confirm to be added to queue, edit/delete entries in queue
	'RegisterPermissions' => array('Plugins.Queue.View','Plugins.Queue.ClanView','Plugins.Queue.Confirm','Plugins.Queue.Edit'),
	'MobileFriendly' => true,
	'Author' => 'Jia'
);

/**
 * Class QueuePlugin
 */
class QueuePlugin extends Gdn_Plugin {
    /**
     * Plugin constructor
     * This fires once per page load, during execution of bootstrap.php.
     */
    public function __construct() {
    }

    /**
     * Queue controller - user view.
     * When a request is made towards this plugin, make the appropriate action (should display the relevant view)
     *
     * @param $Sender Sending controller instance
     */
	public function pluginController_queue_Create($Sender) {
		$Sender->Form = new Gdn_Form();
		/**
		 * Following 3 lines (case sensitive) makes the template for the website use the theme template
		 */
		$Sender->clearCssFiles();
		$Sender->addCssFile('style.css');
		$Sender->MasterView = 'default';
		
		//sets breadcrumb to current lcoation
		$Sender->SetData('Breadcrumbs', array(array('Name' => $title, 'Url' => 'queue')));
		/**
		 * Additional imports
		 */
		//datatables
		$Sender->addJsFile("https://cdn.datatables.net/1.10.15/js/jquery.dataTables.min.js");
		$Sender->addJsFile("https://cdn.datatables.net/rowreorder/1.2.0/js/dataTables.rowReorder.min.js");
		$Sender->addJsFile("https://cdn.datatables.net/responsive/2.1.1/js/dataTables.responsive.min.js");
		$Sender->Head->addCss("https://cdn.datatables.net/1.10.15/css/jquery.dataTables.min.css");
		$Sender->Head->addCss("https://cdn.datatables.net/rowreorder/1.2.0/css/rowReorder.dataTables.min.css");
		$Sender->Head->addCss("https://cdn.datatables.net/responsive/2.1.1/css/responsive.dataTables.min.css");
		
		//bootstrap
		$Sender->Head->addCss("https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css");
		$Sender->addJsFile("https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js");
		
		//my stylings
		$Sender->addJsFile('scripts-0.1.3.js', 'plugins/queue');
		$Sender->addCssFile('queue-0.1.1.css', 'plugins/queue');
		
		$this->dispatch($Sender, $Sender->RequestArgs);
    }
	
	//on /queue landing
	public function Controller_index($Sender){
		$Session = Gdn::Session();
		//check the url
		
		if (CheckPermission('Plugins.Queue.View')) {//sanity check
			//render relevant page, also works: $Sender->Render('queue', '', 'plugins/queue');
			$Sender->addLeech = new Gdn_Form();
			$Sender->Render($this->GetView('queue.php'));
			
			//TODO create a better view of no access
		}else echo Wrap(Anchor(Img('/plugins/MembersListEnh/design/AccessDenied.png',array('width'=>'100%'), array('title' => T('You Have No Permission To View This Page Go Back'))), '/discussions',array('target' => '_self')), 'h1');
	}
	public function Controller_test($Sender){
		//$Sender->Form = new Gdn_Form();
		$view = 'test.php';
		$Session = Gdn::Session();
		$Sender->Render($this->GetView($view));
	}
	public function Controller_process($Sender){
		if ($Sender->addLeech->authenticatedPostBack()) {
			$post = $Sender->addLeech->formValues();
			if (isset($post['Cancel'])) {
				//$view = 'queue.php';
				Redirect('queue');
			} else {
				//validate
				//put the values back into a temp form
				$sender->temp->setData($post);
				$validation = new Gdn_Validation();
			}
		}
		//$Sender->Render($this->GetView($view));
	}
    /**
     * Settings interface
     *
     * @param $Sender
     */
    //public function controller_index($Sender) {
	public function SettingsController_queue_Create($Sender) {
		//call sessions
		$Session = Gdn::Session();
		$Sender->title('Queue');
		$Sender->addSideMenu('plugin/queue');
		
        // Prevent non-admins from accessing this page
        $Sender->permission('Garden.Settings.Manage');
        $Sender->setData('PluginDescription',$this->getPluginKey('Description'));

		$Validation = new Gdn_Validation();
        $ConfigurationModel = new Gdn_ConfigurationModel($Validation);
        $ConfigurationModel->setField(array(
            'Plugin.Queue.RenderCondition'     => 'all',
            'Plugin.Queue.TrimSize'      => 100
        ));

        // Set the model on the form.
        $Sender->Form->setModel($ConfigurationModel);

        // If seeing the form for the first time...
        if ($Sender->Form->authenticatedPostBack() === false) {
            // Apply the config settings to the form.
            $Sender->Form->setData($ConfigurationModel->Data);
		} else {
            $ConfigurationModel->Validation->applyRule('Plugin.Queue.RenderCondition', 'Required');
            $ConfigurationModel->Validation->applyRule('Plugin.Queue.TrimSize', 'Required');
            $ConfigurationModel->Validation->applyRule('Plugin.Queue.TrimSize', 'Integer');
            $Saved = $Sender->Form->save();
            if ($Saved) {
                $Sender->StatusMessage = t("Your changes have been saved.");
            }
        }

        // GetView() looks for files inside plugins/PluginFolderName/views/ and returns their full path. Useful!
        $Sender->Render($this->GetView('queue-settings.php'));
    }
	
	/**
	 * Hooks
	 
	public function assetModel_styleCss_handler($Sender) {
		//$Sender->addCssFile('style.css', 'plugins/queue');
		//$Sender->AddJsFile('scripts.js', 'plugins/queue');
	}
	 
	public function assetModel_dataTables_handler($Sender) {
		$Sender->addCssFile('dataTables.min.css');
	}*/
	
    /**
     * Add a link to the dashboard menu
     *
     * By grabbing a reference to the current SideMenu object we gain access to its methods, allowing us
     * to add a menu link to the newly created /plugin/Example method.
     *
     * @param $Sender Sending controller instance
     */
    public function base_getAppSettingsMenuItems_handler($Sender) {
        $Menu = &$Sender->EventArguments['SideMenu'];
        $Menu->addLink('Add-ons', 'Queue', 'settings/queue', 'Garden.Settings.Manage');
		//TODO create its own submenu
    }

    /**
     * Sets up plugin. Runs during enabling
     */
    public function setup() {

        // Set up the plugin's default values //don't know what these are
        saveToConfig('Plugin.Queue.TrimSize', 100);
        saveToConfig('Plugin.Queue.RenderCondition', "all");

        // Initate database changes
        $this->structure();
		
		// add routing to routes
		$matchroute = '^queue(/.*)?$';
		$target = 'plugin/queue$1';
		
		if(!Gdn::Router()->MatchRoute($matchroute))
			Gdn::Router()->SetRoute($matchroute,$target,'Internal');
    }

    /**
     * This is a special method name that will automatically trigger when a forum owner runs /utility/update.
     * It must be manually triggered if you want it to run on Setup().
     */
    public function structure() {
		
        // Create table GDN_Example, if it doesn't already exist
		/**
		 * schema
		 * ID: primary key for indexing
		 * customerID: what customers can see and be referred to
		 * Rsn: runescape username
		 * Unlock: waves, <10 = normal mode, >10 = hard mode, 11 = wave 1 hardmode
		 * Ironman: 0=no, 1=yes
		 * Notes: for ranks to add notes regarding leech
		 * DateInserted: etc
		 * DateLastLeeched: last time they leeched
		 */
        $St=Gdn::Structure();
		$St->table('Queue')
			->primaryKey('ID')
            ->column('Rsn', 'varchar(20)',FALSE)
            ->column('Unlock', 'TINYINT',FALSE)
			->column('Queen', 'TINYINT',FALSE)
			->column('King', 'TINYINT',FALSE)
			->column('Ironman', 'TINYINT','0')
			->column('Notes', 'varchar(255)',TRUE)
            ->column('DateInserted', 'datetime',null)
			->column('DateLastLeeched', 'datetime',null)
            ->set();
		
		/**
		 * BXP schema
		 * QueueID: references queue.id
		 * Skill: 1=agility, 2=mining, 3=fm
		 * Amount: amount of xp they need
		 */
		$St=Gdn::Structure();
		$St->table('QueueBXP')
		->column('QueueID','INT',FALSE,'KEY')
		->column('Skill','TINYINT',FALSE)
		->column('Amount','INT',FALSE)
		->set();
		
		/**
		 * Misc schema
		 * QueueID: references queue.id
		 * Skill: 1=agility, 2=mining, 3=fm
		 * Amount: amount of xp they need
		 */
		$St=Gdn::Structure();
		$St->table('QueueMisc')
		->column('QueueID','INT',FALSE,'KEY')
		->column('att','SMALLINT',FALSE)
		->column('def','SMALLINT',FALSE)
		->column('heal','SMALLINT',FALSE)
		->column('col','SMALLINT',FALSE)
		->set();

		
    }

    /**
     * Plugin cleanup
     *
     * This method is fired only once, immediately before the plugin is disabled, and is a great place to
     * perform cleanup tasks such as deletion of unsued files and folders.
     */
    public function onDisable() {
        removeFromConfig('Plugin.Queue.TrimSize');
        removeFromConfig('Plugin.Queue.RenderCondition');

        // Never delete from the database OnDisable.
        // Usually, you want re-enabling a plugin to be as if it was never off.
		
		//delete routing
		$matchroute = '^queue(/.*)?$';
		
		Gdn::Router()-> DeleteRoute($matchroute);
    }

}
