<!doctype html>
<html lang="en-us" class="pf-theme-dark">
  <head>
  <title>Viewfinder - Results</title>
  <link rel="stylesheet" href="//code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
  <script src="https://code.jquery.com/jquery-3.6.0.js"></script>
  <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>
<link rel="stylesheet" href="css/table.css">
<link rel="stylesheet" href="css/style.css">

<link rel="stylesheet" href="css/patternfly.css" />
<link rel="stylesheet" href="css/patternfly-addons.css" />
<link rel="stylesheet" href="css/tab.css">
<link rel="stylesheet" href="css/table2.css">


<script src="https://cdnjs.cloudflare.com/ajax/libs/d3/3.5.6/d3.min.js" charset="utf-8"></script>
<script src="https://kit.fontawesome.com/8a8c57f9cf.js" crossorigin="anonymous"></script>
<script>
  $( function() {
    $( "#accordion" ).accordion({
      heightStyle: "content",
      collapsible: true,
      active : 'none'
    });
  } );
  </script>

</head>
<body>
  <header class="pf-c-page__header">
                <div class="pf-c-page__header-brand">
                  <a class="pf-c-page__header-brand-link" href="index.php">
                  <img class="pf-c-brand" src="images/viewfinder-logo.png" alt="Viewfinder logo" />
                  </a>
                </div>
</header>
<?php
require_once __DIR__ . '/includes/Security.php';
require_once __DIR__ . '/includes/MaturityRating.php';

// Parse and validate input data
parse_str($_SERVER["QUERY_STRING"] ?? '', $data);

// Validate profile parameter
$profile = Security::validateProfile($data['profile'] ?? '');
$data['profile'] = $profile; // Update with validated value

// Safely load controls JSON
$controlsFile = Security::getControlsFilePath($profile);
$json = Security::loadJSON($controlsFile);

if ($json === null) {
    die('Error: Unable to load assessment controls. Please contact support.');
}

// Build safe URL for detailed output
$urlData = "./report/index.php?" . http_build_query($data);
$nextSteps = array();
$nextStepsHow = array();
$nextDomain = array();
$controls = array();
foreach($json as $key => $value) {
	array_push($controls,$key);
	}
$controlTotal = array_fill(0,8,0);
$controlDetails = array(array_fill(0,8,0));

foreach($data as $field=>$value){
	if (strpos($field,"control") !== false){
    $controlNumber = substr($field,7,1);
	$controlTotal[$controlNumber] += $value;
}
}

// Functions moved to MaturityRating class

$totalScore = 0;

?>


<div class="container">

<div class="tab">
  <button class="tablinks" onclick="openTab(event, 'Radar')" id="defaultOpen">Radar Chart & Maturity Levels</button>
  <button class="tablinks" onclick="openTab(event, 'Recommendations')">Recommendations</button>
  <button class="tablinks" onclick="openTab(event, 'TableOutput')">Maturity Table</button> 
  <?php
  if (isset($_REQUEST['framework'])) {
	print '<button class="tablinks" onclick="openTab(event, \'Frameworks\')">Security Frameworks</button>';
}
  ?>
  <?php
  // Validate and display LOB tab
  $lob = Security::validateLOB($_REQUEST['lob'] ?? '');
  if ($lob !== null && $lob !== 'Other') {
      print '<button class="tablinks" onclick="openTab(event, \'LineOfBusiness\')">' . Security::escape($lob) . ' Specifics</button>';
  }
  ?>  
  <button class="tablinks""><a href="<?php print $urlData; ?>" target= _blank>Detailed Output</a>&nbsp; <i class='fas fa-external-link-alt'></i></button> 

</div>

<div id="Radar" class="tabcontent">

<div class="htmlChart">
<div class="radarChart"></div>
</div>

<div class="bigtableLeft">
<h1 class="profileHeader">Profile: <?php print Security::escape($data['profile']);?> </h1>

<table class="spacedTable">
	<thead>
		<tr>
			<th>Control</th>
			<th>Rating</th>
			</tr>
		</tr>
</thead>


<?php
$totalScore = 0;
## Work out all the stuff for the table
foreach ($controls as $control) {
	print "<tr>";
	$title = $json[$control]['title'];
	$qnum = $json[$control]['qnum'];
	$score = $controlTotal[$qnum];
	$totalScore += $score;
	#print "<td><i class='fa-regular fa-" . $qnum . "'>&nbsp; &nbsp; </i>" . $title . "</td>";
	print "<td>" . $title . "</td>";
	$rating = MaturityRating::getRating($score);
	print "<td class='cell" . $rating . "'>" . $rating . " ($score out of 36)</td>";
	print "</tr>";
}
print '</table>';
$overallRating = MaturityRating::getTotalRating($totalScore);
print "<br><table><td class='cell" . $overallRating . "'>Overall rating: " . $overallRating . " ($totalScore out of 252)</td></tr></table>";

?>
</div>
</div>
<!-- Detailed Output -->
<div id="Recommendations" class="tabcontent">
<div id="accordion">
<?php
foreach ($controls as $control) {
    $highest=0;
    $qnum = $json[$control]['qnum'];
	$score = $controlTotal[$qnum];
	$title = $json[$control]['title'];
	array_push($nextDomain, $title);
	$rating = MaturityRating::getRating($score);
    print "<h3>$title <span class='cellHeader" . $rating . "'>". $rating . "</span></h3><div>";

    
    $qnum = $json[$control]['qnum'];
    $levelArray = array();
    ## Get the highest score per capability & keep the results
    foreach ($data as $key => $value) {
    if (preg_match("/^control$qnum-[0-9]*/", $key)) {
        array_push($levelArray, substr($key, -1));
        $highest++;
          }
    }
    $nextLevel = $highest + 1;
    if ($nextLevel < 9) {
        ## Check if there is a recommendation for the next level
        $nextRecommendation = $nextLevel . '-recommendation';
        $nextSummary = $nextLevel . '-summary';
        print "<h4 class=title-text>Recommendation</h4>"; 
        print "<p>Start to work on preparing for actions concerning " . $json[$control][$nextLevel] . " (Level $nextLevel)<p>";
        print "<br><p class=why-what>What is " . $json[$control][$nextLevel] . " ?</p><p>" . $json[$control][$nextSummary] . "</p>";

        if ($json[$control][$nextRecommendation] != "") {
            print "<br>";
            print "<p>" . $json[$control][$nextRecommendation] . "<p>";
			array_push($nextSteps,$json[$control][$nextLevel]);
			array_push($nextStepsHow,$json[$control][$nextSummary]);
        } else {
        print "<p>You're doing great as you are!</p>";
    }
}


## Check for any gaps
if ($levelArray) {
	#print "Max: " . max($levelArray) . "<br>";
	$allLevels = range(1,max($levelArray));
	$missing = array_diff($allLevels,$levelArray);
	if ($missing) {
		print "<br><br><h4 class=why-what>Skipped Level(s)</h4>";
		foreach ($missing as $notthere) {
			$skippedRecommendation = $notthere . '-recommendation';
			print "Level $notthere - ";
			if ($json[$control][$skippedRecommendation] != "") {
			print $json[$control][$skippedRecommendation] . ". ";
			} else {
                $notthereComment = $notthere . "-summary";
#				print_r($json[$control][$notthere]);
                print $json[$control][$notthereComment];
			}
			print "<br>";
		}
	}
	}
    
    print "</div>";

}
?>

</div>
<!-- End of Detailed Output -->

</div>

<!-- Start of table output  -->

<div id="TableOutput" class="tabcontent">

<?php
  // Functions moved to MaturityRating class

  $controlDetail = array_fill(1,8,0);
  $controlDetails = array_fill(1,8,$controlDetail);
  
  foreach($data as $field=>$value){
	  if (strpos($field,"control") !== false){
	  $controlNumber = substr($field,7,1);
	  $controlDetails[$controlNumber][$value] = 1;
  }
  }   
?>

<div class="bigtable">

<table class="tableMaturity"><thead><tr>
<th class="table-header">Rating</th>

<?php
foreach ($controls as $control) {
	$title = $json[$control]['title'];
print '<th class="table-header">' . $title .'</th>';
}

?>

</tr></thead>
<tr>
<td class="advanced"></td>
<?php
MaturityRating::putDomainStatus("8",$controlDetails,$json);
?>
</tr>

<tr>
<td class="advanced">Advanced</td>

<?php
MaturityRating::putDomainStatus("7",$controlDetails,$json);
?>
</tr>

<tr>
<td class="advanced"></td>
<?php
MaturityRating::putDomainStatus("6",$controlDetails,$json);
?>
</tr>

<tr>
<td class="strategic"></td>
<?php
MaturityRating::putDomainStatus("5",$controlDetails,$json);
?>
</tr>

<tr>
<td class="strategic">Strategic</td>
<?php
MaturityRating::putDomainStatus("4",$controlDetails,$json);
?>
</tr>

<tr>
<td class="strategic"></td>
<?php
MaturityRating::putDomainStatus("3",$controlDetails,$json);
?>
</tr>

<tr>
<td class="foundation"></td>
<?php
MaturityRating::putDomainStatus("2",$controlDetails,$json);
?>
</tr>

<tr>
<td class="foundation">Foundation</td>
<?php
MaturityRating::putDomainStatus("1",$controlDetails,$json);
?>
  
</tr>

</table>

</div>


</div>
<!-- End of table output  -->




<!-- Start of Security Frameworks -->
<div id="Frameworks" class="tabcontent">


<?php
if (isset($_REQUEST['framework'])) {
    // Safely load compliance frameworks
    $jsonFrameworks = Security::loadJSON(__DIR__ . '/compliance.json');

    if ($jsonFrameworks !== null) {
        // Build list of valid framework names
        $validFrameworks = array_column($jsonFrameworks, 'name');

        // Validate user-provided frameworks
        $userFrameworks = Security::validateFrameworks($_REQUEST['framework'], $validFrameworks);

        foreach ($userFrameworks as $selectedFramework) {
            foreach ($jsonFrameworks as $framework) {
                if ($framework['name'] === $selectedFramework) {
                    $linkFile = $framework['link'];
                    print "<br><div class='niceList'>";
                    print "<ul>";

                    // Safely get framework file path
                    $safeFilePath = Security::getFrameworkFilePath($linkFile);

                    if ($safeFilePath !== null) {
                        include $safeFilePath;
                    } else {
                        print "<h3 class='frameworkHeader'>No current information for " . Security::escape($framework['name']) . "</h3>";
                    }
                    print "</ul></div>";
                }
            }
        }
    }
}
?>
</div>


<!-- Start of LOB -->
<?php
if (isset($_REQUEST['lob'])) {
    // Validate LOB parameter
    $lob = Security::validateLOB($_REQUEST['lob']);

    if ($lob !== null) {
        print '<div id="LineOfBusiness" class="tabcontent"><p class="category-large">Advice for ' . Security::escape($lob) . ' industries</p>';
        // Safely get LOB file path
#        if ($profile === "DigSov") {
#        $safeFilePath = Security::getLOBFilePath("DigSov", $profile);
#        } else {
        $safeFilePath = Security::getLOBFilePath($lob, $profile);
        #        }  
        if ($safeFilePath !== null) {
            include $safeFilePath;
        } else {
            print '<p>No current information available for this industry.</p>';
        }
    }
}
?>


</div>


<script src="js/radarChart.js"></script>	
		<script>
      
      /* Radar chart design created by Nadieh Bremer - VisualCinnamon.com */
      
			////////////////////////////////////////////////////////////// 
			//////////////////////// Set-Up ////////////////////////////// 
			////////////////////////////////////////////////////////////// 

			var margin = {top: 100, right: 100, bottom: 100, left: 100},
				width = Math.min(700, window.innerWidth - 10) - margin.left - margin.right,
				height = Math.min(width, window.innerHeight - margin.top - margin.bottom - 20);
					
			////////////////////////////////////////////////////////////// 
			////////////////////////// Data ////////////////////////////// 
			////////////////////////////////////////////////////////////// 

			var data = [
					  [
						<?php
						$numControls = 1;
						foreach ($controls as $control) {
							$title = $json[$control]['title'];
							print '{axis:"' . $title . '",value: ' . $controlTotal[$numControls]. '},';		
							$numControls++;
						}
						?>

					  ]
					];
			////////////////////////////////////////////////////////////// 
			//////////////////// Draw the Chart ////////////////////////// 
			////////////////////////////////////////////////////////////// 

			var color = d3.scale.ordinal()
				.range(["#CC333F","#CC333F","#00A0B0"]);
				
			var radarChartOptions = {
			  w: width,
			  h: height,
			  margin: margin,
			  maxValue: 0.5,
			  roundStrokes: true,
			  color: color,
			};
			//Call function to draw the Radar chart
			RadarChart(".radarChart", data, radarChartOptions);
</script>


<script type="text/javascript" >
function openTab(evt, cityName) {
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
</body>
  </html>