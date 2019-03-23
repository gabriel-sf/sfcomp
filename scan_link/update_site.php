<?php
$csv = "site.csv";
$file = fopen($csv,"w+");
  fputcsv($file,array('Site Folder','Site Name'));
  $index = 1;
  //Read the folder names in the folder
foreach (glob("/var/www/vhosts/*",GLOB_ONLYDIR) as $full_path) {
  $full_path_ary = explode('/',$full_path); //full path => var/www/vhosts/xxx/
  $site_folder = end($full_path_ary); // get xxx folder name, eg:site_prd
  $site_names = glob($full_path . "/*",GLOB_ONLYDIR); //Read the next level folder names, which normally is the site name

  if(count($site_names)!=1){ //If there are more than 1 folder, site name equal to folder name
    $site_name = $site_folder;
      echo $index."). ".$site_name."<br/>";
  }else{
    $full_site_name = $site_names[0]; //else, store site name
    $site_name_ary = explode('/',$full_site_name);
    $site_name = end($site_name_ary);
    echo $index."). ".$site_name."<br/>";
  }
  fputcsv($file,array($site_folder,$site_name));
    //echo $full_path."<br />";
	$index = $index + 1;
}
fclose($file);
echo "Total ".$index." site are inserted to site.csv."
 ?>
