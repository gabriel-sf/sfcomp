
<?php

if(isset($_POST['action'])){
  $action = $_POST['action'];
  if($action=="scan"){
    $email = $_POST['email'];
    $site = $_POST['site'];
    $site_name = $_POST['site_name'];
    $result = scan($email,$site,$site_name);
    echo $result;
  }
}

function scan($email,$site,$site_name){
	//Save bash script in a variable	
	//Find string with pattern "saltandfuessel.com.au"
	//exclude @saltandfuessel.com.au. feedback.saltandfuessel.com.au, automation.saltandfuessel.com.au, www.saltandfuessel.com.au, https:\/\/saltandfuessel.com.au
	//http://saltandfuessel.com.au
	//And print the result
  $scan_script = "
  grep --recursive -n -F \"saltandfuessel.com.au\" --include=\"*.css\" --include=\"*.php\" --include=\"*.js\" /var/www/vhosts/".$site."/*/httpdocs/wp-content/themes/ | awk '!/@saltandfuessel.com.au/' |  awk '!/feedback.saltandfuessel.com.au/'  |  awk '!/automation.saltandfuessel.com.au/' |awk '!/www.saltandfuessel.com.au/' | awk '!/https:\/\/saltandfuessel.com.au/' | awk '!/http:\/\/saltandfuessel.com.au/' | awk -F: 'BEGIN {counter=0;} {counter++; print $1\" Line \"$2 \"=>\" $3$4$5$6$7$8$9$10 } END { print \"<br/>Total staging link found: \" counter}'
";
  exec($scan_script,$outputs); //exclude the bash script
  $output = implode("\n", $outputs); // convert output array to string
  // exec("awk '{print \"counter\"}'",$counters);
     $counters = explode(":",$output); //Get the counter value which is the number of string that contain the pattern ->[ Total staging link found : counter ]
     $counter = end($counters);
	 //Bash script that send result email
  $email_script = "
  /usr/sbin/sendmail alerts@saltandfuessel.com.au << EOM
From: alerts@saltandfuessel.com.au
To: ".$email."
Subject: ".$site_name." contain ".$counter." staging link ( saltandfuessel.com.au)
".$output."
";
 exec($email_script);
  return $output;
}


 ?>
