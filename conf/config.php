<?php if (!defined('APPLICATION')) exit();

// Conversations
$Configuration['Conversations']['Version'] = '2.3';

// Database
$Configuration['Database']['Name'] = 'lbax10ho_main';
$Configuration['Database']['Host'] = 'localhost';
$Configuration['Database']['User'] = 'lbax10ho_user';
$Configuration['Database']['Password'] = 'runescape1';

// EnabledApplications
$Configuration['EnabledApplications']['Conversations'] = 'conversations';
$Configuration['EnabledApplications']['Vanilla'] = 'vanilla';

// EnabledPlugins
$Configuration['EnabledPlugins']['GettingStarted'] = 'GettingStarted';
$Configuration['EnabledPlugins']['HtmLawed'] = 'HtmLawed';
$Configuration['EnabledPlugins']['cleditor'] = false;
$Configuration['EnabledPlugins']['ButtonBar'] = false;
$Configuration['EnabledPlugins']['Emotify'] = false;
$Configuration['EnabledPlugins']['FileUpload'] = false;
$Configuration['EnabledPlugins']['editor'] = true;
$Configuration['EnabledPlugins']['EmojiExtender'] = true;
$Configuration['EnabledPlugins']['Flagging'] = true;
$Configuration['EnabledPlugins']['VanillaInThisDiscussion'] = true;
$Configuration['EnabledPlugins']['SplitMerge'] = true;
$Configuration['EnabledPlugins']['Tagging'] = true;
$Configuration['EnabledPlugins']['GooglePrettify'] = true;
$Configuration['EnabledPlugins']['Quotes'] = true;
$Configuration['EnabledPlugins']['ProfileExtender'] = true;
$Configuration['EnabledPlugins']['PrivateCommunity'] = true;
$Configuration['EnabledPlugins']['Eventi'] = false;
$Configuration['EnabledPlugins']['Signatures'] = true;
$Configuration['EnabledPlugins']['Pockets'] = true;
$Configuration['EnabledPlugins']['MembersListEnh'] = true;
$Configuration['EnabledPlugins']['queue'] = true;
$Configuration['EnabledPlugins']['AllViewed'] = true;

// Garden
$Configuration['Garden']['Title'] = 'Leech BA';
$Configuration['Garden']['Cookie']['Salt'] = 'oSZ7Wo7P5NOcZm3x';
$Configuration['Garden']['Cookie']['Domain'] = '';
$Configuration['Garden']['Registration']['ConfirmEmail'] = true;
$Configuration['Garden']['Email']['SupportName'] = 'Leech BA';
$Configuration['Garden']['Email']['Format'] = 'text';
$Configuration['Garden']['SystemUserID'] = '1';
$Configuration['Garden']['InputFormatter'] = 'Markdown';
$Configuration['Garden']['Version'] = '2.3';
$Configuration['Garden']['Cdns']['Disable'] = false;
$Configuration['Garden']['CanProcessImages'] = true;
$Configuration['Garden']['Installed'] = true;
$Configuration['Garden']['Theme'] = 'CloudyPremium';
$Configuration['Garden']['MobileTheme'] = 'MobileVersion';
$Configuration['Garden']['MobileInputFormatter'] = 'TextEx';
$Configuration['Garden']['AllowFileUploads'] = true;
$Configuration['Garden']['InstallationID'] = 'CD40-CA52FDCE-FA95C3BD';
$Configuration['Garden']['InstallationSecret'] = '69d5fa04b2c40f2fb74566c3d5cc9630478bbbd8';
$Configuration['Garden']['HomepageTitle'] = 'Leech BA';
$Configuration['Garden']['Description'] = 'Leech Barbarian Assault ~ Runescape
For all your needs ranging from Fighter Torsos, Hats, role levels.';
$Configuration['Garden']['PrivateCommunity'] = false;

// Plugin
$Configuration['Plugin']['Queue']['TrimSize'] = 100;
$Configuration['Plugin']['Queue']['RenderCondition'] = 'all';

// Plugins
$Configuration['Plugins']['GettingStarted']['Dashboard'] = '1';
$Configuration['Plugins']['GettingStarted']['Plugins'] = '1';
$Configuration['Plugins']['GettingStarted']['Categories'] = '1';
$Configuration['Plugins']['GettingStarted']['Profile'] = '1';
$Configuration['Plugins']['GettingStarted']['Discussion'] = '1';
$Configuration['Plugins']['editor']['ForceWysiwyg'] = false;

// Routes
$Configuration['Routes']['DefaultController'] = 'discussions';
$Configuration['Routes']['Xm1lbWJlcnMoLy4qKT8k'] = array('plugin/MembersListEnh$1', 'Internal');
$Configuration['Routes']['XnF1ZXVlKC8uKik_JA=='] = array('plugin/Queue$1', 'Internal');

// Vanilla
$Configuration['Vanilla']['Version'] = '2.3';
$Configuration['Vanilla']['AdminCheckboxes']['Use'] = true;

// Last edited by admin (86.166.86.23)2017-05-08 11:35:15