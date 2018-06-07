<?php
//Master table

$db['default']['hostname'] = "localhost";
$db['default']['username'] = "root";
$db['default']['password'] = "";
$db['default']['database'] = "database_name";
$db['default']['dbdriver'] = "mysql";
$db['default']['dbprefix'] = "";
$db['default']['pconnect'] = TRUE;
$db['default']['db_debug'] = FALSE;
$db['default']['cache_on'] = FALSE;
$db['default']['cachedir'] = "";
$db['default']['char_set'] = "utf8";
$db['default']['dbcollat'] = "utf8_general_ci";
$db['default']['swap_pre'] = "";
$db['default']['autoinit'] = TRUE;
$db['default']['stricton'] = FALSE;

//Secondary table (notice pconnect is set to false)

$db['otherdb']['hostname'] = "localhost";
$db['otherdb']['username'] = "root";
$db['otherdb']['password'] = "";
$db['otherdb']['database'] = "other_database_name";
$db['otherdb']['dbdriver'] = "mysql";
$db['otherdb']['dbprefix'] = "";
$db['otherdb']['pconnect'] = FALSE;
$db['otherdb']['db_debug'] = FALSE;
$db['otherdb']['cache_on'] = FALSE;
$db['otherdb']['cachedir'] = "";
$db['otherdb']['char_set'] = "utf8";
$db['otherdb']['dbcollat'] = "utf8_general_ci";
$db['otherdb']['swap_pre'] = "";
$db['otherdb']['autoinit'] = TRUE;
$db['otherdb']['stricton'] = FALSE;


// Then you can use secondary databases as database objects while using master database as usual :

// use master dataabse
$users = $this->db->get('users');

// connect to secondary database
$otherdb = $this->load->database('otherdb', TRUE);
$stuff = $otherdb->get('struff');
$otherdb->insert_batch('users', $users->result_array());

// keep using master database as usual, for example insert stuff from other database
$this->db->insert_batch('stuff', $stuff->result_array());
?>
