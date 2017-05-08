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
    'Version' => '1.0.2',
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
	public function PluginController_queue_Create($Sender) {
		$Session = Gdn::Session();
		//clear css files, must to get rid of vanilla
		if (CheckPermission('Plugins.Queue.View')) {
			/**
			 * Following 3 lines (case sensitive) makes the template for the website use the theme template
			 */
			$Sender->clearCssFiles();
			$Sender->addCssFile('style.css');
			$Sender->MasterView = 'default';
			
			/**
			 * Additional imports
			 */
			$Sender->addJsFile('jQuery.dataTables.min.js', 'plugins/queue');
			$Sender->addCssFile('dataTables.min.css', 'plugins/queue');
			//my stylings
			$Sender->addJsFile('scripts.js', 'plugins/queue');
			$Sender->addCssFile('style.css', 'plugins/queue');
			
			
			//render relevant page, also works: $Sender->Render('queue', '', 'plugins/queue');
			$Sender->Render($this->GetView('queue.php'));
			
			//TODO create a better view of no access
		}else echo Wrap(Anchor(Img('/plugins/MembersListEnh/design/AccessDenied.png',array('width'=>'100%'), array('title' => T('You Have No Permission To View This Page Go Back'))), '/discussions',array('target' => '_self')), 'h1');
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
		$target = 'plugin/Queue$1';
		
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
			->column('customerID', 'INT',FALSE,'UNIQUE')
            ->column('Rsn', 'varchar(20)',FALSE)
            ->column('Unlock', 'TINYINT',FALSE)
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
		->column('Skill','TINYINT',FALSE)
		->column('Amount','INT',FALSE)
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
