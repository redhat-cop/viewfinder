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
      <link rel="stylesheet" href="css/tab-dark.css" />
      <link rel="stylesheet" href="css/patternfly.css" />
      <link rel="stylesheet" href="css/patternfly-addons.css" />
      
      <script src="https://code.jquery.com/jquery-3.6.0.js"></script>
  <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>
  <script src="https://kit.fontawesome.com/8a8c57f9cf.js" crossorigin="anonymous"></script>
  <link href="https://unpkg.com/boxicons@2.1.2/css/boxicons.min.css" rel="stylesheet" />

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

  /* Left padding for profile navigation buttons */
  .pf-c-page__header .widget {
    padding-left: 1.5rem;
  }

  /* Header Button Spacing */
  .pf-c-page__header-tools button {
    margin-right: 1rem;
  }

  /* Reduce gap between tabcontent and horizontal checkboxes */
  .horizontal-checkboxes {
    margin-top: 1rem;
  }

  /* Ensure consistent button height and icon display */
  .pf-c-page__header-tools button,
  .widget button {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    line-height: 1.5;
  }

  /* Icon alignment in header buttons */
  .pf-c-page__header-tools button i,
  .widget button i {
    font-size: 1em;
    vertical-align: middle;
    margin-right: 0.5rem;
    display: inline-block;
    color: inherit;
    opacity: 1;
  }
</style>

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
                </div>
                <?php
                require_once __DIR__ . '/vendor/autoload.php';
                require_once __DIR__ . '/error-pages/error-handler.php';
                require_once __DIR__ . '/includes/Security.php';
                require_once __DIR__ . '/includes/Logger.php';
                require_once __DIR__ . '/includes/Config.php';

                // Register error handlers
                ErrorHandler::register();

                // Check if this is the landing page (no profile parameter) or an assessment
                $isLandingPage = empty($_REQUEST['profile']);

                if (!$isLandingPage) {
                    // Assessment mode - load profile data
                    try {
                        Logger::info('Index page loaded', ['page' => 'index.php']);

                        // Validate and sanitize profile input
                        $profile = Security::validateProfile($_REQUEST['profile'] ?? '');
                        Logger::info('Profile selected', ['profile' => $profile]);

                        // Safely load controls JSON
                        $controlsFile = Security::getControlsFilePath($profile);
                        $json = Security::loadJSON($controlsFile);

                    } catch (ViewfinderException $e) {
                        Logger::logException($e);
                        throw $e; // Re-throw for error handler to display error page
                    } catch (\Throwable $e) {
                        Logger::error('Unexpected error in index.php', [
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
                }
                ?>
                <?php if (!$isLandingPage): ?>
                <div class="widget">
              <?php
              // Dynamically generate navigation buttons for enabled profiles
              $enabledProfiles = Config::getEnabledProfiles();
              foreach ($enabledProfiles as $profileKey => $profileData) {
                  $profileName = htmlspecialchars($profileKey, ENT_QUOTES, 'UTF-8');
                  $displayName = htmlspecialchars($profileData['display_name'], ENT_QUOTES, 'UTF-8');
                  echo '<a href="index.php?profile=' . $profileName . '"><button>' . $displayName . '</button></a>&nbsp;' . "\n              ";
              }
              ?>
            </div>
            <div class="pf-c-page__header-tools">
              <div class="widget">
                <a href="index.php"><button><i class="fa-solid fa-home"></i> Home</button></a>
                <a href="ds-qualifier/"><button>DS Readiness Assessment</button></a>
                <a href="import-results.php"><button><i class="fa fa-upload"></i> Import Results</button></a>
                <a href="profile-admin.php"><button>Manage Profiles</button></a>
              </div>
            </div>
            <?php endif; ?>
</header>

<?php if ($isLandingPage): ?>
<!-- ========================================
     LANDING PAGE
     ======================================== -->
<div class="landing-page-wrapper">
  <div class="container" style="max-width: 1200px; margin: 3rem auto;">
    <div style="text-align: center; margin-bottom: 3rem;">
      <h1 style="color: #9ec7fc; font-size: 2.5rem; margin-bottom: 1rem;">
        <i class="fa-solid fa-compass"></i> Viewfinder Assessment Tools
      </h1>
      <p style="color: #ccc; font-size: 1.2rem; max-width: 800px; margin: 0 auto;">
        Red Hat technology maturity assessments and digital sovereignty qualification tools
      </p>
    </div>

  <div class="landing-cards-grid">
    <!-- Digital Sovereignty Readiness Assessment Card -->
    <div class="landing-card">
      <div class="landing-card-header">
        <i class="fa-solid fa-clipboard-check"></i>
        <h2>Digital Sovereignty Readiness Assessment</h2>
      </div>
      <p class="landing-card-description">
        Quick 10-15 minute assessment to evaluate your organization's digital sovereignty readiness across 7 key domains
      </p>
      <div class="landing-card-buttons">
        <a href="ds-qualifier/" class="landing-button landing-button-success">
          <i class="fa-solid fa-rocket"></i> Start Assessment
        </a>
      </div>
    </div>

    <!-- EU Cloud Sovereignty Framework Card -->
    <div class="landing-card">
      <div class="landing-card-header">
        <div class="eu-flag">🇪🇺</div>
        <h2>EU Cloud Sovereignty Assessment</h2>
      </div>
      <p class="landing-card-description">
        Evaluate your organization against the European Commission's Cloud Sovereignty Framework (v1.2.1). 24 questions across 8 SOV objectives with SEAL 0-4 rating levels.
      </p>
      <div class="landing-card-buttons">
        <a href="eu-sovereignty/" class="landing-button" style="background: linear-gradient(135deg, #003399 0%, #0051A5 100%); color: #fff;">
          <i class="fa-solid fa-certificate"></i> Start EU Assessment
        </a>
      </div>
    </div>

    <!-- Digital Sovereignty Quiz Card -->
    <div class="landing-card">
      <div class="landing-card-header">
        <i class="fa-solid fa-graduation-cap"></i>
        <h2>Digital Sovereignty Quiz</h2>
      </div>
      <p class="landing-card-description">
        Interactive knowledge assessment with 7 domains, instant results, certificates, and leaderboard rankings
      </p>
      <div class="landing-card-buttons">
        <a href="quiz/" class="landing-button landing-button-primary">
          <i class="fa-solid fa-brain"></i> Take the Quiz
        </a>
      </div>
    </div>

    <!-- Full Maturity Assessments Card -->
    <div class="landing-card">
      <div class="landing-card-header">
        <i class="fa-solid fa-chart-line"></i>
        <h2>Full Maturity Assessments</h2>
      </div>
      <p class="landing-card-description">
        Comprehensive 5-Level maturity assessments weighted for your industry's priorities. Choose from Security or Digital Sovereignty assessments.
      </p>
      <div class="landing-card-buttons">
        <a href="maturity-assessment-landing.php" class="landing-button landing-button-primary">
          <i class="fa-solid fa-rocket"></i> Start Full Assessment
        </a>
      </div>
    </div>

    <!-- Escape Room Card - COMMENTED OUT TO SAVE SCREEN SPACE
    <div class="landing-card">
      <div class="landing-card-header">
        <i class="fa-solid fa-shield-halved"></i>
        <h2>Operation Sovereign Shield</h2>
      </div>
      <p class="landing-card-description">
        Immersive Digital Sovereignty Escape Room - An executive challenge for strategic decision-making under pressure
      </p>
      <div class="landing-card-buttons">
        <a href="escape-room.php" class="landing-button landing-button-warning">
          <i class="fa-solid fa-puzzle-piece"></i> View Escape Room Overview
        </a>
      </div>
    </div>
    -->
  </div>
</div>
</div>

<style>
/* Landing Page Styles */
.landing-page-wrapper {
  min-height: calc(100vh - 200px);
  display: flex;
  flex-direction: column;
}

.landing-cards-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
  gap: 1.5rem;
  margin: 2rem 0;
}

.landing-card {
  background: #2a2a2a;
  border: 1px solid #444;
  border-radius: 8px;
  padding: 1.1rem;
  transition: all 0.3s ease;
  display: flex;
  flex-direction: column;
}

.landing-card:hover {
  border-color: #0d60f8;
  box-shadow: 0 4px 16px rgba(13, 96, 248, 0.3);
  transform: translateY(-4px);
}

.landing-card-header {
  text-align: center;
  margin-bottom: 1.5rem;
  padding-bottom: 1rem;
  border-bottom: 2px solid #444;
}

.landing-card-header i {
  font-size: 3rem;
  color: #12bbd4;
  margin-bottom: 0.5rem;
  display: block;
}

.landing-card-header h2 {
  color: #9ec7fc;
  font-size: 1.5rem;
  margin: 0;
}

.landing-card-description {
  color: #ccc;
  line-height: 1.6;
  margin-bottom: 1.5rem;
  text-align: center;
  min-height: 60px;
}

.landing-card-buttons {
  display: flex;
  flex-direction: column;
  gap: 0.75rem;
  margin-top: auto;
}

.landing-button {
  display: inline-block;
  padding: 1rem 1.0rem;
  border-radius: 4px;
  text-decoration: none;
  font-weight: 600;
  text-align: center;
  transition: all 0.2s ease;
  border: none;
  cursor: pointer;
  font-size: 1rem;
}

.landing-button i {
  margin-right: 0.5rem;
}

.landing-button-primary {
  background: linear-gradient(135deg, #0d60f8 0%, #004cbf 100%);
  color: #fff;
}

.landing-button-primary:hover {
  background: linear-gradient(135deg, #4d90fe 0%, #0d60f8 100%);
  box-shadow: 0 4px 12px rgba(13, 96, 248, 0.4);
}

.landing-button-success {
  background: linear-gradient(135deg, #2aaa04 0%, #1b7003 100%);
  color: #fff;
}

.landing-button-success:hover {
  background: linear-gradient(135deg, #3fcc00 0%, #2aaa04 100%);
  box-shadow: 0 4px 12px rgba(42, 170, 4, 0.4);
}

.landing-button-warning {
  background: linear-gradient(135deg, #f0ab00 0%, #c58c00 100%);
  color: #fff;
}

.landing-button-warning:hover {
  background: linear-gradient(135deg, #ffc425 0%, #f0ab00 100%);
  box-shadow: 0 4px 12px rgba(240, 171, 0, 0.4);
}

.landing-button-secondary {
  background: #444;
  color: #ccc;
}

.landing-button-secondary:hover {
  background: #555;
  color: #fff;
}

@media (max-width: 768px) {
  .landing-cards-grid {
    grid-template-columns: 1fr;
  }
}

/* Sticky Footer Styles */
body {
  display: flex;
  flex-direction: column;
  min-height: 100vh;
  margin: 0;
}

.landing-page-wrapper {
  flex: 1 0 auto;
}

.disclaimer-footer {
  flex-shrink: 0;
  background-color: #1a1a1a;
  border-top: 1px solid #444;
  padding: 1.5rem 2rem;
  text-align: center;
  margin-top: auto;
}

.disclaimer-footer p {
  color: #999;
  margin: 0;
  font-size: 0.9rem;
}

.disclaimer-footer strong {
  color: #ccc;
}

/* EU Flag */
.eu-flag {
  text-align: center;
  font-size: 3rem;
  margin-bottom: 0.5rem;
  line-height: 1;
}
</style>

<?php else: ?>
<!-- ========================================
     ASSESSMENT MODE
     ======================================== -->
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
  // Removed tier display (Foundation/Strategic/Advanced) - now using 5-Level Maturity Model
  $points = $i . "-points";
  print '<li><input type="checkbox" name="' . "control" . $qnum . "-" . $i . "\" id=\"" . "control" . "$qnum" . "-" . $i . '" value="' . $json[$area][$points] . '"><label for="' . "control" . $qnum . "-" . $i . '">' . $json[$area][$i] . "$itemSummary &nbsp </label></li>". "\n";
  $i++;
}
print "</ul>";

// Add facilitator notes textarea for this domain
print '<div style="margin-top: 1.5rem; padding: 1rem; background: #1f1f1f; border-left: 3px solid #0d60f8; border-radius: 4px;">';
print '<label for="domain_notes_' . $qnum . '" style="display: block; color: #9ec7fc; font-weight: 600; margin-bottom: 0.5rem;">';
print '</i> Facilitator Notes for ' . $title . ':</label>';
print '<textarea name="domain_notes_' . $qnum . '" id="domain_notes_' . $qnum . '" rows="4" style="width: 100%; padding: 0.5rem; background: #2a2a2a; border: 1px solid #444; border-radius: 4px; color: #ccc; font-family: inherit; resize: vertical;" placeholder="Add workshop notes, observations, or context for this domain..."></textarea>';
print '</div>';
}
?>
<div class="tab">
  <div id="centerDivLine">
<h2><?php echo Security::escape(Config::getProfileDisplayName($profile));?> Profile</h2>

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

<?php
// Get LOB from URL parameter and pass it as hidden input
$selectedLobFromUrl = isset($_GET['lob']) ? $_GET['lob'] : 'General';

// Map "Balanced" to "General" for compatibility
if ($selectedLobFromUrl === 'Balanced') {
    $selectedLob = 'General';
} else {
    $selectedLob = Security::validateLOB($selectedLobFromUrl);
    if ($selectedLob === null) {
        $selectedLob = 'General';
    }
}
?>
<input type="hidden" name="lob" value="<?php echo Security::escape($selectedLob); ?>">

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
  <?php
## Compliance Frameworks
try {
    $jsonFrameworks = Security::loadJSON(__DIR__ . '/compliance.json');
    Logger::debug('Compliance frameworks loaded', ['count' => count($jsonFrameworks)]);
} catch (ViewfinderException $e) {
    Logger::logException($e);
    throw $e;
}

// Get selected frameworks from URL parameters and pass them through as hidden fields
$selectedFrameworks = isset($_GET['framework']) ? $_GET['framework'] : [];
if (!is_array($selectedFrameworks)) {
    $selectedFrameworks = [$selectedFrameworks];
}

// Pass selected frameworks through as hidden fields (no need to show them again)
foreach ($selectedFrameworks as $framework) {
    print '<input type="hidden" name="framework[]" value="' . Security::escape($framework) . '">';
}
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
<?php endif; ?>

<footer class="disclaimer-footer">
  <p><strong>Red Hat Disclaimer:</strong> This Cloud Sovereignty Framework Self-Assessment Tool is provided by Red Hat to help organizations review their sovereign posture. It is not endorsed by any regulatory authority, and its findings or recommendations do not constitute legal advice. Red Hat bears no legal responsibility or liability for the results or its use.</p>
</footer>

</body>
</html>