<?php
$site_file = "site.csv"; //csv file path

 ?>
<!DOCTYPE html>
<!--[if lt IE 7 ]> <html lang="en" class="no-js ie6"> <![endif]-->
<!--[if IE 7 ]>    <html lang="en" class="no-js ie7"> <![endif]-->
<!--[if IE 8 ]>    <html lang="en" class="no-js ie8"> <![endif]-->
<!--[if IE 9 ]>    <html lang="en" class="no-js ie9"> <![endif]-->
<!--[if (gt IE 9)|!(IE)]><!-->
<html lang="en" class="no-js">
<!--<![endif]-->
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <script
  src="https://code.jquery.com/jquery-3.3.1.min.js"
  integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8="
  crossorigin="anonymous"></script>
    <title>Scan Staging Link</title>
</head>
<body>
<header>
<div style="text-align:center">
  <h1>Salt and Fuessel Staging Link Scanner<h1>
  </div>
</header>
<div class="content">
  <?php
  $sites = array();
  //Read site folder and site name from csv and save into sites variable
  if(file_exists($site_file)){
    if (($handle = fopen("$site_file", "r")) !== FALSE) {
        while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
            $num = count($data);
            for ($c=0; $c < $num; $c++) {
              //  echo $data[$c] . "<br />\n";
            }
    				array_push($sites,$data);
        }
        fclose($handle);
    }
  }else{
    echo "Site sheet is missing, please contact Salt and Fuessel Technician";
  }
  ?>
<div class="email_div" style="margin:10px">
<p>Email :<span><i> Separate email by comma for multiple emails</i></span></p> 
<input type-"text" name="email" id="email" size="150" required/>
</div>
<div class="site_div" style="margin:10px">
Site :
<select name="site" id="site1">
<?php
foreach($sites as $site){
  echo "<option value='".$site[0]."'>".$site[1]."</option>";
}
 ?>
</select>
</div>
<button onclick="scan(); return false;">Scan</button>
<div id="result">

</div>
</div>
<script>
function scan(){
  var email = $('#email').val();
  var site = $('#site1').val();
  var site_name = $('#site1 option:selected').text();
  //alert(site_name);
  if(email.indexOf("@") < 0 || site=="Site Folder"){ //If empty email or no site selected
    alert("Please input your email and select a site");
  }else{
	$(".content").css("opacity","0.3");  //ajax loading
	$('#result').html("scanning");
    $.ajax({
    'url': 'scan_ajax.php',
    'type': 'POST',
    'data' : {
    'email' : email,
    'site' : site,
    'site_name' : site_name,
    'action' : 'scan'},
      'success': function(result){
      //alert(result);
      $('#result').html(result);
	  $(".content").css("opacity","1.0");  
    },
    error: function(data)
        {
          //  alert( 'Sorry.' );
        }
  });
  }

}
</script>
</body>
</html>
