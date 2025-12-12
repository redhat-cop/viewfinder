<!doctype html>

<html lang="en-us" class="pf-theme-dark">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Viewfinder Maturity Assessment</title>
<link rel="stylesheet" href="//code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
<link rel="stylesheet" href="css/bootstrap.min.css">
<link rel="stylesheet" href="css/brands.css" />
<link rel="stylesheet" href="css/consent.css" />
      <link rel="stylesheet" href="css/style.css" />
      <link rel="stylesheet" href="css/tab.css" />
      <link rel="stylesheet" href="css/patternfly.css" />
      <link rel="stylesheet" href="css/patternfly-addons.css" />
      
      <script src="https://code.jquery.com/jquery-3.6.0.js"></script>
  <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>
  <script src="https://kit.fontawesome.com/8a8c57f9cf.js" crossorigin="anonymous"></script>
  <link href="https://unpkg.com/boxicons@2.1.2/css/boxicons.min.css" rel="stylesheet" />
  <script src="js/consent.js" defer></script>    

<script>
	//style all the dialogue
	$( function() {
		$(".dialog_help").dialog({
			modal: true,
			autoOpen: false,
			width: 500,
			height: 300,
			dialogClass: 'ui-dialog-osx'
		});
	});
	
	//opens the appropriate dialog
	$( function() {
		$(".opener").click(function () {
			//takes the ID of appropriate dialogue
			var id = $(this).data('id');
		   //open dialogue
			$(id).dialog("open");
		});
	});
</script>

    </head>

<body>




  <header class="pf-c-page__header">
                <div class="pf-c-page__header-brand">
                  <div class="pf-c-page__header-brand-toggle">
                  </div>
                  <a class="pf-c-page__header-brand-link" href="index.php">
                  <img class="pf-c-brand" src="images/viewfinder-logo.png" alt="Viewfinder logo" />
                  </a>
                </div>
                <div class="widget">
                  <?php
                  require_once __DIR__ . '/includes/Security.php';

                  // Validate and sanitize profile input
                  $profile = Security::validateProfile($_REQUEST['profile'] ?? '');

                  // Safely load controls JSON
                  $controlsFile = Security::getControlsFilePath($profile);
                  $json = Security::loadJSON($controlsFile);

                  if ($json === null) {
                      die('Error: Unable to load assessment controls. Please contact support.');
                  }
                  ?>
              <a href="index.php?profile=Security"><button>Security</button></a>&nbsp
<!--              <a href="index.php?profile=AI"><button>AI Readiness (WiP)</button></a>&nbsp -->
              <a href="index.php?profile=DigitalSovereignty"><button>Digital Sovereignty</button></a>&nbsp
<!--              <a href="index.php?profile=OpenShift"><button>OpenShift</button></a>&nbsp
              <a href="index.php?profile=AI"><button>AI</button></a>&nbsp -->
            </div>
</header>
<div class="container">
<?php
$controls = array();
foreach($json as $key => $value) {
	array_push($controls,$key);
	}

function getControls ($area,$json) {
$i=1;
$qnum = $json[$area]['qnum'];
$infoId = $qnum . "-" . $i;
$title = $json[$area]['title'];
$control = $area;
print "<p>" . $json[$area]['overview'] . "</p>";
print "<ul class='ks-cboxtags'>\n";
while( $i < 9) {
  //$infoButton = '<i class="fa-solid fa-circle-info"></i>';
  $summary= $i . '-summary';
   ## If a summary in there, use it as a tooltip
  if ($json[$area][$summary] != "") {
  
  $itemSummary = '&nbsp; <i class="fa-solid fa-circle-info" style="display: inline-block;max-width: 100px;" title="' . $json[$area][$summary] . '"></i>';
  } else {
    $itemSummary = "";
  }
  $tier = $i . '-tier';
  $tierClass = "smallText" . $json[$area][$tier];
  $points = $i . "-points";
  print '<li><input type="checkbox" name="' . "control" . $qnum . "-" . $i . "\" id=\"" . "control" . "$qnum" . "-" . $i . '" value="' . $json[$area][$points] . '"><label for="' . "control" . $qnum . "-" . $i . '"><p class="' . $tierClass. '">'  . $json[$area][$tier] . '</p>' . $json[$area][$i] . "$itemSummary &nbsp </label></li>". "\n";
  $i++;
}
print "</ul>";
}
?>
<div class="tab">
  <div id="centerDivLine">
<h2>Profile: <?php echo Security::escape(Config::getProfileDisplayName($profile));?> </h2>

</div>
<?php
$first=0;
foreach ($controls as $control) {
	$title = $json[$control]['title'];
  if ($first < 2) {
	  print '<button class="tablinks" onclick="openCity(event, \'' . $control . '\')" id="defaultOpen">' . $title .'</button>';
  } else {
	  print '<button class="tablinks" onclick="openCity(event, \'' . $control . '\')">' . $title .'</button>';

  }
$first++;
}
?>  

</div>
</div>
<form action="results.php">

<div class="lob">
<h3 class="lobFont">Line of Business</h3>
<input type="radio" name="lob" value="Finance">
<span class="lobItem">Finance</span>
<br>
<input type="radio" name="lob" value="Government">
<span class="lobItem">Government</span>
<br>
<input type="radio" name="lob" value="Manufacturing">
<span class="lobItem">Manufacturing</span>
<br>
<input type="radio" name="lob" value="Telecommunications">
<span class="lobItem">Telecommunications</span>
<br>
<input type="radio" name="lob" value="Healthcare">
<span class="lobItem">Healthcare</span>
<br>
<input type="radio" name="lob" value="Other">
<span class="lobItem">Other</span>

</div>

<div class="container">
  
<fieldset>
<!-- Tab content -->
<?php
foreach ($controls as $control) {
print '<div id="' . $control . '" class="tabcontent">';
getControls($control,$json);
print '</div>';
}
?>
  </fieldset>
  <br>
  <input type="hidden" name="profile" value="<?php echo Security::escape($profile);?>">
  <div id="centerDivLine">
  <?php
## Compliance Frameworks
$jsonFrameworks = Security::loadJSON(__DIR__ . '/compliance.json');
if ($jsonFrameworks === null) {
    die('Error: Unable to load compliance frameworks. Please contact support.');
}

## Add checklist for compliance frameworks
print '<div class="form-group horizontal-checkboxes">
<p class="smallTextFramework">To which of the following compliance frameworks do you have to adhere?</p>';
foreach ($jsonFrameworks as $framework) {
  print "<input id='" . $framework['name'] . "' name='framework[]' value='" . $framework['name'] . "' type='checkbox'>&nbsp <label class='smallTextFramework'  id='" . $framework['name'] . "' for='framework'>" . $framework['name'] . "</label>&nbsp &nbsp";
}

print '</div>';
?>
</div>
<br>
<input class='ui-button ui-widget ui-corner-all' id='submitButton' type='submit' name='Submit' value='Submit'>
</form>
</div>

<script type="text/javascript" >
function openCity(evt, cityName) {
  // Declare all variables
  var i, tabcontent, tablinks;

  // Get all elements with class="tabcontent" and hide them
  tabcontent = document.getElementsByClassName("tabcontent");
  for (i = 0; i < tabcontent.length; i++) {
    tabcontent[i].style.display = "none";
  }

  // Get all elements with class="tablinks" and remove the class "active"
  tablinks = document.getElementsByClassName("tablinks");
  for (i = 0; i < tablinks.length; i++) {
    tablinks[i].className = tablinks[i].className.replace(" active", "");
  }

  // Show the current tab, and add an "active" class to the button that opened the tab
  document.getElementById(cityName).style.display = "block";
  evt.currentTarget.className += " active";
}
</script>
<script type="text/javascript" >
document.getElementById("defaultOpen").click();
</script>


<div class="bannerWrapper">
      <header>
      <h2>Red Hat Disclaimer</h2>
      </header>

      <div class="data">
        <p>This application is provided for informational purposes only. The information is provided “as is” with no guarantee or warranty of accuracy, completeness, or fitness for a particular purpose.</p>
      </div>

      <div class="buttons">
        <button class="button" id="acceptBtn">Accept</button>
      </div>
</div>

</body>
</html>