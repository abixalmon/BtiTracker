<?php

// test file to see how it work...

require("../../include/class.update_hacks.php");

$CURRENT_FOLDER=dirname(__FILE__);

// create object
$newhack=new update_hacks();

// we open the work definition file
$hstring=$newhack->open_hack(dirname(__FILE__)."/modification.xml");

// all structure is now in an array
$new_hack_array=$newhack->hack_to_array($hstring);

// we will install the hack or we can just test if installation will run fine, in this case we just test.
if ($newhack->uninstall_hack($new_hack_array))
    print_r($newhack->file);
else
    print_r($newhack->errors);

?>