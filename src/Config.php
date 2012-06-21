<?php

$rootPath = dirname(__FILE__) . '/';
define('APP_Root', $rootPath);

// Configure these language to change what is displayed.  They should match the 'lang' tags in your LIFT file.
define('LANG_Vernacular', 'th');
define('LANG_IPA',        'th-fonipa');
define('LANG_Other',      'en');

// Path to the LIFT file
define('APP_LiftFilePath', '/var/www/dictionaryview.local/SampleData/tha-food.lift');
define('APP_LiftImagesFolder', 'pictures/');
define('APP_LiftImageURL', 'SampleData/');

/* You shouldn't need to change anything below this line. */

define('SGF_CORE', APP_Root.'Core/');
define('ERROR_PathFilter', APP_Root);
//define('ERROR_PathFilter', '/var/www/host/languageforge/LFDictionaryView/src/');

?>