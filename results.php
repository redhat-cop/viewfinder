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
<link rel="stylesheet" href="css/results-dark.css">


<script src="https://cdnjs.cloudflare.com/ajax/libs/d3/3.5.6/d3.min.js" charset="utf-8"></script>
<script src="https://kit.fontawesome.com/8a8c57f9cf.js" crossorigin="anonymous"></script>

<style>
  /* Dark Theme Body */
  body {
    background-color: #151515 !important;
    color: #ccc !important;
  }

  /* Header Top Padding */
  .pf-c-page__header {
    padding-top: 1.5rem;
  }

  /* Header Button Spacing */
  .pf-c-page__header-tools button {
    margin-right: 1rem;
  }

  /* Override jQuery UI default styles */
  .ui-widget {
    font-family: inherit !important;
  }

  .ui-widget-content {
    background: transparent !important;
    border: none !important;
    color: #ccc !important;
  }

  .ui-state-default {
    background: transparent !important;
    border: none !important;
  }
</style>

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
                  <div class="pf-c-page__header-brand-toggle">
                  </div>
                </div>
                <div class="pf-c-page__header-tools">
                  <div class="widget">
                    <a href="index.php"><button><i class="fa fa-home"></i> Home</button></a>
                    <a href="export-results.php?<?php echo htmlspecialchars($_SERVER['QUERY_STRING'], ENT_QUOTES, 'UTF-8'); ?>"><button><i class="fa fa-download"></i> Export Results</button></a>
                  </div>
                </div>
</header>
<?php
require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/error-pages/error-handler.php';
require_once __DIR__ . '/includes/Security.php';
require_once __DIR__ . '/includes/MaturityRating.php';
require_once __DIR__ . '/includes/Logger.php';
require_once __DIR__ . '/includes/Config.php';
require_once __DIR__ . '/includes/Exceptions/ResultsException.php';

use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel;
use Endroid\QrCode\RoundBlockSizeMode;
use Endroid\QrCode\Writer\PngWriter;

// Register error handlers
ErrorHandler::register();

try {
    // Start session for import handling
    session_start();

    Logger::info('Results page loaded', ['page' => 'results.php']);

    // Check if this is an imported result
    if (isset($_GET['imported']) && isset($_SESSION['imported_results'])) {
        $importedData = $_SESSION['imported_results'];

        // Validate import type
        if ($importedData['viewfinder_export']['type'] !== 'security_assessment') {
            throw ResultsException::wrongType(
                'security_assessment',
                $importedData['viewfinder_export']['type']
            );
        }

        // Extract assessment data and flatten structure
        $assessment = $importedData['assessment'];

        // Start with base data (profile, lob)
        $data = [
            'profile' => $assessment['profile'],
            'lob' => $assessment['lob'] ?? ''
        ];

        // Handle frameworks - set 'framework' param if frameworks exist
        if (isset($assessment['frameworks']) && !empty($assessment['frameworks'])) {
            // Set framework parameter for tab display (expects singular)
            $data['framework'] = $assessment['frameworks'];
        }

        // Flatten controls to root level (expected by results.php)
        if (isset($assessment['controls'])) {
            foreach ($assessment['controls'] as $controlId => $controlValue) {
                // Cast to integer to ensure proper type
                $data[$controlId] = (int)$controlValue;
            }
        }

        // Include domain notes if present
        if (isset($assessment['domain_notes'])) {
            foreach ($assessment['domain_notes'] as $noteKey => $noteValue) {
                $data[$noteKey] = $noteValue;
            }
        }

        // Clear session
        unset($_SESSION['imported_results']);

        Logger::info('Imported Security results displayed', ['profile' => $data['profile']]);
    } else {
        // Normal flow - parse query string
        parse_str($_SERVER["QUERY_STRING"] ?? '', $data);
    }

    // Validate profile parameter
    $profile = Security::validateProfile($data['profile'] ?? '');
    $data['profile'] = $profile; // Update with validated value
    Logger::info('Profile selected', ['profile' => $profile]);

    // Safely load controls JSON
    $controlsFile = Security::getControlsFilePath($profile);
    $json = Security::loadJSON($controlsFile);

    // Build safe URL for detailed output
    $urlData = "./report/index.php?" . http_build_query($data);

    // Generate QR code for current page URL
    // Get the full current page URL with query string
    $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
    $currentPageUrl = $protocol . "://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];

    // Build the QR code using fluent builder pattern (v5.x API)
    $qrCodeResult = Builder::create()
        ->writer(new PngWriter())
        ->data($currentPageUrl)
        ->encoding(new Encoding('UTF-8'))
        ->errorCorrectionLevel(ErrorCorrectionLevel::High)
        ->size(300)
        ->margin(10)
        ->roundBlockSizeMode(RoundBlockSizeMode::Margin)
        ->validateResult(false)
        ->build();

    // Convert to base64 for inline display
    $qrCodeDataUri = $qrCodeResult->getDataUri();

} catch (ViewfinderException $e) {
    Logger::logException($e);
    throw $e; // Re-throw for error handler to display error page
} catch (\Throwable $e) {
    Logger::error('Unexpected error in results.php', [
        'exception' => get_class($e),
        'message' => $e->getMessage(),
        'file' => $e->getFile(),
        'line' => $e->getLine()
    ]);
    throw new ViewfinderException(
        'Unexpected error: ' . $e->getMessage(),
        'An unexpected error occurred. Please contact support.',
        ['original_exception' => get_class($e)],
        0,
        $e
    );
}
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

// ==========================================
// WEIGHTED SCORING IMPLEMENTATION
// ==========================================

// Load LOB weights
$lobWeights = require_once __DIR__ . '/lob-weights.php';

// Get selected LOB (default to 'General' if not set)
$selectedLob = Security::validateLOB($_REQUEST['lob'] ?? '');
if ($selectedLob === null) {
    $selectedLob = 'General';
}

// Get weights for this profile and LOB
$domainWeights = [];
if (isset($lobWeights[$profile]) && isset($lobWeights[$profile][$selectedLob])) {
    $domainWeights = $lobWeights[$profile][$selectedLob]['weights'];
} else {
    // Fallback to balanced weights (all 1.0)
    foreach ($controls as $control) {
        $title = $json[$control]['title'];
        $domainWeights[$title] = 1.0;
    }
}

// Calculate raw total score (unweighted, for reference)
$totalScore = array_sum($controlTotal);

// Calculate weighted score
$weightedSum = 0;
$totalWeight = 0;
$maxPossiblePerDomain = 36; // Each domain has max 36 points (9 questions × 4 levels)

foreach ($controls as $control) {
    $title = $json[$control]['title'];
    $qnum = $json[$control]['qnum'];
    $domainScore = $controlTotal[$qnum];

    // Get weight for this domain (default 1.0 if not found)
    $weight = isset($domainWeights[$title]) ? $domainWeights[$title] : 1.0;

    // Calculate weighted contribution
    // Normalize domain score to 0-1 range, apply weight, then scale back
    $domainPercentage = $domainScore / $maxPossiblePerDomain;
    $weightedDomainScore = $domainPercentage * $weight;

    $weightedSum += $weightedDomainScore;
    $totalWeight += $weight;
}

// Normalize weighted score to 0-252 scale (7 domains × 36 max points)
$totalScore = $totalWeight > 0 ? ($weightedSum / $totalWeight) * (count($controls) * $maxPossiblePerDomain) : 0;

// Check if any workshop notes exist
$hasNotes = false;
$workshopNotes = [];
foreach ($controls as $control) {
    $qnum = $json[$control]['qnum'];
    $notesFieldName = 'domain_notes_' . $qnum;
    if (isset($_REQUEST[$notesFieldName]) && !empty(trim($_REQUEST[$notesFieldName]))) {
        $hasNotes = true;
        $workshopNotes[$qnum] = [
            'title' => $json[$control]['title'],
            'notes' => $_REQUEST[$notesFieldName]
        ];
    }
}

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
  <?php
  // Display Workshop Notes tab if notes exist
  if ($hasNotes) {
      print '<button class="tablinks" onclick="openTab(event, \'WorkshopNotes\')"></i> Workshop Notes</button>';
  }
  ?>
  <button class="tablinks""><a href="<?php print $urlData; ?>" target= _blank>Detailed Output</a>&nbsp; <i class='fas fa-external-link-alt'></i></button>

</div>

<div id="Radar" class="tabcontent">

<div class="htmlChart">
<div class="radarChart"></div>
</div>

<div class="bigtableLeft">
<h1 class="profileHeader">Profile: <?php print Security::escape(Config::getProfileDisplayName($data['profile']));?> </h1>

<?php
// Display selected LOB profile info
if (isset($lobWeights[$profile][$selectedLob])) {
    $lobData = $lobWeights[$profile][$selectedLob];
    print '<div style="background: #1a1a1a; border-left: 3px solid #0d60f8; padding: 0.75rem; margin-bottom: 1rem; border-radius: 4px;">';
    print '<i class="fa-solid ' . htmlspecialchars($lobData['icon']) . '" style="color: #0d60f8; margin-right: 0.5rem;"></i>';
    print '<strong style="color: #9ec7fc;">Industry Profile:</strong> ';
    print '<span style="color: #fff;">' . Security::escape($lobData['name']) . '</span><br>';
    print '<span style="color: #999; font-size: 0.9rem; margin-left: 1.5rem;">' . Security::escape($lobData['description']) . '</span>';
    print '</div>';
}
?>

<table class="spacedTable">
	<thead>
		<tr>
			<th>Control</th>
			<th style="text-align: center;">Weight</th>
			<th>Rating</th>
			</tr>
		</tr>
</thead>


<?php
// Use the weighted totalScore calculated earlier, don't recalculate
$displayTotalScore = round($totalScore); // Round for display

## Work out all the stuff for the table
foreach ($controls as $control) {
	print "<tr>";
	$title = $json[$control]['title'];
	$qnum = $json[$control]['qnum'];
	$score = $controlTotal[$qnum];

	// Get weight for this domain
	$weight = isset($domainWeights[$title]) ? $domainWeights[$title] : 1.0;
	$isWeighted = $weight >= 1.5;

	// Split multi-word titles to reduce column width (e.g., "Data Sovereignty" → "Data<br>Sovereignty")
	$displayTitle = str_replace(' ', '<br>', $title);

	print "<td>" . $displayTitle . "</td>";

	// Weight column with badge
	print "<td style='text-align: center;'>";
	$weightBadgeClass = $isWeighted ? 'weight-badge weight-high' : 'weight-badge';
	print "<span class='" . $weightBadgeClass . "'>" . number_format($weight, 1) . "×</span>";
	print "</td>";

	$rating = MaturityRating::getRating($score);
	$ratingClass = MaturityRating::getRatingClass($rating);
	print "<td class='" . $ratingClass . "'>" . $rating . " ($score out of 36)</td>";
	print "</tr>";
}
print '</table>';
$overallRating = MaturityRating::getTotalRating($displayTotalScore);
$overallRatingClass = MaturityRating::getRatingClass($overallRating);
print "<br><table class='spacedTable' style='margin-top: 0.5rem;'><tr><td class='" . $overallRatingClass . "' style='padding: 0.5rem;'>Overall rating: " . $overallRating . " (" . $displayTotalScore . " weighted out of 252)</td></tr></table>";

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
	$ratingClass = MaturityRating::getRatingClass($rating);
	// Convert cell class to header class (e.g., cellInitial -> cellHeaderInitial)
	$headerClass = str_replace('cell', 'cellHeader', $ratingClass);
    print "<h3>$title <span class='" . $headerClass . "'>". $rating . "</span></h3><div>";

    
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
<td class="optimizing">Optimizing</td>
<?php
MaturityRating::putDomainStatus("8",$controlDetails,$json);
?>
</tr>

<tr>
<td class="quantitative"></td>

<?php
MaturityRating::putDomainStatus("7",$controlDetails,$json);
?>
</tr>

<tr>
<td class="quantitative">Quantitatively Managed</td>
<?php
MaturityRating::putDomainStatus("6",$controlDetails,$json);
?>
</tr>

<tr>
<td class="defined"></td>
<?php
MaturityRating::putDomainStatus("5",$controlDetails,$json);
?>
</tr>

<tr>
<td class="defined">Defined</td>
<?php
MaturityRating::putDomainStatus("4",$controlDetails,$json);
?>
</tr>

<tr>
<td class="managed"></td>
<?php
MaturityRating::putDomainStatus("3",$controlDetails,$json);
?>
</tr>

<tr>
<td class="managed">Managed</td>
<?php
MaturityRating::putDomainStatus("2",$controlDetails,$json);
?>
</tr>

<tr>
<td class="initial">Initial</td>
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
#        if ($profile === "DigitalSovereignty") {
#        $safeFilePath = Security::getLOBFilePath("DigitalSovereignty", $profile);
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

<!-- Start of Workshop Notes -->
<?php if ($hasNotes): ?>
<div id="WorkshopNotes" class="tabcontent">
    <h2 style="color: #9ec7fc; margin-bottom: 1.5rem;">
        Workshop Facilitator Notes
    </h2>
    <p style="color: #999; margin-bottom: 2rem;">
        Notes captured during the assessment workshop for each domain.
    </p>

    <?php foreach ($workshopNotes as $qnum => $noteData): ?>
        <div style="margin-bottom: 2rem; padding: 1rem 1.5rem 1rem 0; background: #1f1f1f; border-left: 4px solid #0d60f8; border-radius: 4px; border: 1px solid #444;">
            <h3 style="color: #9ec7fc; ">
                <?php echo Security::escape($noteData['title']); ?>
            </h3>
            <div style="color: #ccc; line-height: 1.6; margin: 0; padding: 0.5rem;">
                <?php echo Security::escape($noteData['notes']); ?>
            </div>
        </div>
    <?php endforeach; ?>
</div>
<?php endif; ?>

</div>


<script src="js/radarChart.js"></script>	
		<script>
      
      /* Radar chart design created by Nadieh Bremer - VisualCinnamon.com */
      
			////////////////////////////////////////////////////////////// 
			//////////////////////// Set-Up ////////////////////////////// 
			////////////////////////////////////////////////////////////// 

			var margin = {top: 120, right: 120, bottom: 120, left: 120},
				width = Math.min(500, window.innerWidth - 10) - margin.left - margin.right,
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
				.range(["#0d60f8","#0d60f8","#12bbd4"]);
				
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