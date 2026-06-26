<!doctype html>

<html lang="en-us" class="pf-theme-dark">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Maturity Assessment</title>
  <link rel="icon" type="image/svg+xml" href="favicon.svg">
  <link rel="alternate icon" href="favicon.ico">
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

  /* Custom Capability Tooltips with HTML support */
  .custom-capability-tooltip {
    position: absolute;
    display: none;
    max-width: 500px;
    min-width: 400px;
    background: #2a2a2a;
    border: 2px solid #0d60f8;
    border-radius: 6px;
    color: #e0e0e0;
    font-size: 0.9rem;
    padding: 1rem;
    box-shadow: 0 4px 20px rgba(13, 96, 248, 0.4);
    z-index: 10000;
    pointer-events: none;
  }

  .custom-capability-tooltip strong {
    color: #9ec7fc;
    display: block;
    margin-bottom: 0.5rem;
    font-size: 1rem;
  }

  .custom-capability-tooltip ul {
    margin: 0.5rem 0 0 0;
    padding-left: 1.5rem;
    list-style-type: disc;
  }

  .custom-capability-tooltip ul li {
    color: #e0e0e0;
    margin-bottom: 0.4rem;
    line-height: 1.5;
  }

  .tooltip-icon {
    color: #0d60f8;
    transition: color 0.2s ease;
  }

  .tooltip-icon:hover {
    color: #4d90fe;
  }

  /* Capability Slider Grid - 4 columns */
  .capability-slider-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 1rem;
    padding: 1.5rem;
    background: #1f1f1f;
    border-radius: 8px;
    margin-top: 1rem;
  }

  .capability-slider-item {
    background: #2a2a2a;
    padding: 0.85rem;
    border-radius: 6px;
    border: 1px solid #444;
    transition: border-color 0.3s ease;
  }

  .capability-slider-item:hover {
    border-color: #0d60f8;
  }

  .capability-name {
    color: #e0e0e0;
    font-weight: 500;
    margin-bottom: 0.75rem;
    font-size: 0.95rem;
    display: flex;
    align-items: center;
  }

  /* Maturity Slider Styling */
  .maturity-slider {
    width: 100%;
    height: 6px;
    border-radius: 3px;
    background: linear-gradient(to right,
      #6a6e73 0%,
      #6a6e73 33%,
      #f0ab00 33%,
      #f0ab00 66%,
      #ec7a08 66%,
      #ec7a08 100%);
    outline: none;
    -webkit-appearance: none;
    margin: 0.5rem 0;
    cursor: pointer;
  }

  /* Slider thumb styling for WebKit browsers */
  .maturity-slider::-webkit-slider-thumb {
    -webkit-appearance: none;
    appearance: none;
    width: 18px;
    height: 18px;
    border-radius: 50%;
    background: var(--slider-color, #6a6e73);
    cursor: pointer;
    border: 2px solid #fff;
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.4);
    transition: all 0.2s ease;
  }

  .maturity-slider::-webkit-slider-thumb:hover {
    transform: scale(1.15);
    box-shadow: 0 3px 10px rgba(0, 0, 0, 0.5);
  }

  /* Slider thumb styling for Firefox */
  .maturity-slider::-moz-range-thumb {
    width: 18px;
    height: 18px;
    border-radius: 50%;
    background: var(--slider-color, #6a6e73);
    cursor: pointer;
    border: 2px solid #fff;
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.4);
    transition: all 0.2s ease;
  }

  .maturity-slider::-moz-range-thumb:hover {
    transform: scale(1.15);
    box-shadow: 0 3px 10px rgba(0, 0, 0, 0.5);
  }

  /* Slider label */
  .slider-label {
    text-align: center;
    font-size: 0.85rem;
    margin-top: 0.5rem;
    color: #6a6e73;
    font-weight: 400;
  }

  /* Responsive: Adjust columns on smaller screens */
  @media (max-width: 1400px) {
    .capability-slider-grid {
      grid-template-columns: repeat(2, 1fr);
    }
  }

  @media (max-width: 768px) {
    .capability-slider-grid {
      grid-template-columns: 1fr;
    }
  }

  /* Hide progress counter on small screens */
  @media (max-width: 768px) {
    #progressCounter {
      display: none !important;
    }
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
        <i class="fa-solid fa-compass"></i> Assessment Tools
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
// Build controls array - filter out sub-domains from main navigation
foreach($json as $key => $value) {
	// Only include domains that should display in main navigation
	// Sub-domains (Domain-5, Domain-7) are integrated into their parent domains
	if (!isset($value['display_in_main_nav']) || $value['display_in_main_nav'] !== false) {
		array_push($controls,$key);
	}
}

function getControls ($area,$json) {
$i=1;
$qnum = $json[$area]['qnum'];
$infoId = $qnum . "-" . $i;
$title = $json[$area]['title'];
$control = $area;
print "<div>" . $json[$area]['overview'] . "</div>";

// Check if this domain includes sub-domains
$hasSubdomains = isset($json[$area]['includes_subdomains']) && $json[$area]['includes_subdomains'] === true;

// If domain has sub-domains, show section header for core capabilities
if ($hasSubdomains && isset($json[$area]['section_1_label'])) {
	print '<div style="margin: 1.5rem 0 1rem 0; padding: 0.75rem 1rem; background: linear-gradient(90deg, #0d60f8 0%, transparent 100%); border-left: 4px solid #0d60f8; border-radius: 4px;">';
	print '<h3 style="color: #fff; margin: 0; font-size: 1.1rem; font-weight: 600;">';
	print '<i class="fa-solid fa-layer-group" style="margin-right: 0.5rem;"></i>';
	print $json[$area]['section_1_label'];
	print '</h3>';
	print '</div>';
}

// Create 2-column grid for sliders
print "<div class='capability-slider-grid'>\n";

while( $i < 9) {
  $summary= $i . '-summary';
   ## If a summary in there, use it as a tooltip
  if ($json[$area][$summary] != "") {

  $tooltipContent = htmlspecialchars($json[$area][$summary], ENT_QUOTES, 'UTF-8');
  $itemSummary = '&nbsp; <i class="fa-solid fa-circle-info tooltip-icon" style="cursor: help;" data-tooltip="' . $tooltipContent . '"></i>';
  } else {
    $itemSummary = "";
  }

  $points = $i . "-points";
  $controlId = "control" . $qnum . "-" . $i;

  print '<div class="capability-slider-item">';
  print '<div class="capability-name">' . $json[$area][$i] . $itemSummary . '</div>';
  print '<input type="range"
         min="0"
         max="3"
         value="0"
         step="1"
         class="maturity-slider"
         name="' . $controlId . '"
         id="' . $controlId . '"
         data-points="' . $json[$area][$points] . '"
         oninput="updateSliderLabel(this)">';
  print '<div class="slider-label" id="label-' . $controlId . '">No Capability (0)</div>';
  print '</div>'. "\n";
  $i++;
}
print "</div>"; // Close grid

// If this domain includes sub-domains, render them now
if ($hasSubdomains && isset($json[$area]['section_2_source'])) {
	$subdomainKey = $json[$area]['section_2_source'];

	// Section header for sub-domain
	if (isset($json[$area]['section_2_label'])) {
		print '<div style="margin: 2rem 0 1rem 0; padding: 0.75rem 1rem; background: linear-gradient(90deg, #12bbd4 0%, transparent 100%); border-left: 4px solid #12bbd4; border-radius: 4px;">';
		print '<h3 style="color: #fff; margin: 0; font-size: 1.1rem; font-weight: 600;">';
		print '<i class="fa-solid fa-puzzle-piece" style="margin-right: 0.5rem;"></i>';
		print $json[$area]['section_2_label'];
		print '</h3>';
		print '</div>';
	}

	// Render sub-domain capabilities
	if (isset($json[$subdomainKey])) {
		$subQnum = $json[$subdomainKey]['qnum'];

		print "<div class='capability-slider-grid'>\n";
		$j = 1;
		while ($j < 9) {
			$summary = $j . '-summary';
			if ($json[$subdomainKey][$summary] != "") {
				$tooltipContent = htmlspecialchars($json[$subdomainKey][$summary], ENT_QUOTES, 'UTF-8');
				$itemSummary = '&nbsp; <i class="fa-solid fa-circle-info tooltip-icon" style="cursor: help;" data-tooltip="' . $tooltipContent . '"></i>';
			} else {
				$itemSummary = "";
			}

			$points = $j . "-points";
			$controlId = "control" . $subQnum . "-" . $j;

			print '<div class="capability-slider-item">';
			print '<div class="capability-name">' . $json[$subdomainKey][$j] . $itemSummary . '</div>';
			print '<input type="range"
				   min="0"
				   max="3"
				   value="0"
				   step="1"
				   class="maturity-slider"
				   name="' . $controlId . '"
				   id="' . $controlId . '"
				   data-points="' . $json[$subdomainKey][$points] . '"
				   oninput="updateSliderLabel(this)">';
			print '<div class="slider-label" id="label-' . $controlId . '">No Capability (0)</div>';
			print '</div>'. "\n";
			$j++;
		}
		print "</div>"; // Close sub-domain grid
	}
}

// Add facilitator notes textarea for this domain
print '<div style="margin-top: 1.5rem; padding: 1rem; background: #1f1f1f; border-left: 3px solid #0d60f8; border-radius: 4px;">';
print '<label for="domain_notes_' . $qnum . '" style="display: block; color: #9ec7fc; font-weight: 600; margin-bottom: 0.5rem;">';
print '</i> Facilitator Notes for ' . $title . ':</label>';
print '<textarea name="domain_notes_' . $qnum . '" id="domain_notes_' . $qnum . '" rows="2" style="width: 100%; padding: 0.5rem; background: #2a2a2a; border: 1px solid #444; border-radius: 4px; color: #ccc; font-family: inherit; resize: vertical;" placeholder="Add workshop notes, observations, or context for this domain..."></textarea>';
print '</div>';
}
?>
<div class="tab">
  <div id="centerDivLine">
<h2 style="display: flex; justify-content: space-between; align-items: center;">
  <span><?php echo Security::escape(Config::getProfileDisplayName($profile));?> Profile</span>
  <span id="progressCounter" style="font-size: 0.85rem; color: #9ec7fc; font-weight: 500;">
    <i class="fa-solid fa-chart-line" style="margin-right: 0.5rem;"></i>
    <span id="ratedCount">0</span> of <span id="totalCount">56</span> capabilities rated
  </span>
</h2>

<?php if ($profile === 'DigitalSovereignty'):
  // Detect if running on localhost and set appropriate QR code URL
  $currentHost = $_SERVER['HTTP_HOST'] ?? 'localhost';
  $isLocalhost = (strpos($currentHost, 'localhost') !== false ||
                  strpos($currentHost, '127.0.0.1') !== false ||
                  strpos($currentHost, '::1') !== false);

  if ($isLocalhost) {
    // Running on localhost - use environment variable or fall back to current host
    // Set VIEWFINDER_PUBLIC_URL environment variable for custom public URL
    $publicUrl = getenv('VIEWFINDER_PUBLIC_URL');

    if ($publicUrl) {
      // Use configured public URL
      $qrCodeUrl = rtrim($publicUrl, '/') . '/dig-sov-domains.php';
      $qrCodeDisplayUrl = parse_url($publicUrl, PHP_URL_HOST) . '/dig-sov-domains.php';
    } else {
      // No public URL configured - use localhost (QR code will only work locally)
      $protocol = 'http';
      $qrCodeUrl = $protocol . '://' . $currentHost . '/dig-sov-domains.php';
      $qrCodeDisplayUrl = $currentHost . '/dig-sov-domains.php';
    }
  } else {
    // Running on public server - use current domain
    $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
    $qrCodeUrl = $protocol . '://' . $currentHost . '/dig-sov-domains.php';
    $qrCodeDisplayUrl = $currentHost . '/dig-sov-domains.php';
  }
?>
<div style="margin: 1rem 0;">
  <button id="toggleQRCode" type="button" style="width: 100%; padding: 0.75rem 1rem; background: #2a2a2a; border: 1px solid #444; border-radius: 6px; color: #9ec7fc; cursor: pointer; font-size: 0.95rem; font-weight: 600; display: flex; align-items: center; justify-content: space-between; transition: all 0.3s ease;" onmouseover="this.style.background='#333'; this.style.borderColor='#0d60f8';" onmouseout="this.style.background='#2a2a2a'; this.style.borderColor='#444';">
    <span style="display: flex; align-items: center; gap: 0.5rem;">
      <i class="fa-solid fa-qrcode" style="color: #0d60f8;"></i>
      Explore Digital Sovereignty Domains
    </span>
    <i class="fa-solid fa-chevron-down" id="qrChevron" style="transition: transform 0.3s ease;"></i>
  </button>

  <div id="qrCodeContent" style="display: none; margin-top: 0.5rem; padding: 1.5rem; background: linear-gradient(135deg, #1f1f1f 0%, #2a2a2a 100%); border: 1px solid #0d60f8; border-radius: 8px; box-shadow: 0 4px 12px rgba(13, 96, 248, 0.2); overflow: hidden; max-height: 0; opacity: 0; transition: max-height 0.4s ease, opacity 0.4s ease, margin-top 0.4s ease;">
    <div style="display: flex; align-items: center; gap: 2rem;">
      <div style="flex: 1;">
        <p style="color: #ccc; margin: 0 0 0.75rem 0; line-height: 1.6;">
          Scan this QR code to access detailed information about each Digital Sovereignty domain, including all capabilities and key considerations.
        </p>
        <a href="<?php echo htmlspecialchars($qrCodeUrl); ?>" target="_blank" style="color: #0d60f8; text-decoration: none; font-weight: 600; display: inline-flex; align-items: center; gap: 0.5rem; transition: color 0.3s ease;">
          <?php # echo htmlspecialchars($qrCodeDisplayUrl); ?>
        </a>
      </div>
      <div style="background: white; padding: 1rem; border-radius: 8px; box-shadow: 0 2px 8px rgba(0, 0, 0, 0.3); flex-shrink: 0;">
        <img src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=<?php echo urlencode($qrCodeUrl); ?>"
             alt="QR Code for Digital Sovereignty Domains"
             style="display: block; width: 150px; height: 150px;">
      </div>
    </div>
  </div>
</div>

<script>
(function() {
  const toggleBtn = document.getElementById('toggleQRCode');
  const qrContent = document.getElementById('qrCodeContent');
  const chevron = document.getElementById('qrChevron');
  let isOpen = false;

  if (toggleBtn && qrContent && chevron) {
    toggleBtn.addEventListener('click', function() {
      isOpen = !isOpen;

      if (isOpen) {
        qrContent.style.display = 'block';
        setTimeout(() => {
          qrContent.style.maxHeight = '500px';
          qrContent.style.opacity = '1';
          qrContent.style.marginTop = '0.5rem';
        }, 10);
        chevron.style.transform = 'rotate(180deg)';
      } else {
        qrContent.style.maxHeight = '0';
        qrContent.style.opacity = '0';
        qrContent.style.marginTop = '0';
        setTimeout(() => {
          qrContent.style.display = 'none';
        }, 400);
        chevron.style.transform = 'rotate(0deg)';
      }
    });
  }
})();
</script>
<?php endif; ?>

</div>
<?php
$first=0;
foreach ($controls as $control) {
	$title = $json[$control]['title'];
	$badge = '';
	$subtitle = '';

	// Add cross-cutting badge for Executive Oversight
	if (isset($json[$control]['group']) && $json[$control]['group'] === 'cross_cutting') {
		$badge = ' <span style="font-size: 0.7rem; background: #f0ab00; color: #000; padding: 0.2rem 0.5rem; border-radius: 3px; margin-left: 0.5rem; font-weight: 600;">CROSS-CUTTING</span>';
	}

	// Add subtitle for domains with sub-pillars
	if (isset($json[$control]['subtitle'])) {
		$subtitle = '<br><span style="font-size: 0.75rem; font-weight: 400; color: #9ec7fc; opacity: 0.8;">' . $json[$control]['subtitle'] . '</span>';
	}

  if ($first < 2) {
	  print '<button class="tablinks" onclick="openCity(event, \'' . $control . '\')" id="defaultOpen">' . $title . $badge . $subtitle .'</button>';
  } else {
	  print '<button class="tablinks" onclick="openCity(event, \'' . $control . '\')">' . $title . $badge . $subtitle .'</button>';

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

// Handle custom weights from URL parameters
$customWeights = [];
if ($selectedLobFromUrl === 'Custom' && isset($_GET['weight']) && is_array($_GET['weight'])) {
    foreach ($_GET['weight'] as $domain => $weight) {
        $cleanDomain = Security::escape($domain);
        $cleanWeight = floatval($weight);
        // Validate weight is between 1.0 and 2.0
        if ($cleanWeight >= 1.0 && $cleanWeight <= 2.0) {
            $customWeights[$cleanDomain] = $cleanWeight;
        }
    }
}
?>
<input type="hidden" name="lob" value="<?php echo Security::escape($selectedLob); ?>">
<?php if (!empty($customWeights)): ?>
  <?php foreach ($customWeights as $domain => $weight): ?>
    <input type="hidden" name="weight[<?php echo Security::escape($domain); ?>]" value="<?php echo $weight; ?>">
  <?php endforeach; ?>
<?php endif; ?>

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

// Update slider label and visual state based on value
function updateSliderLabel(slider) {
  const labels = ['No Capability (0)', 'In Planning (1)', 'Work in Progress (2)', 'Fully Complete (3)'];
  const colors = ['#6a6e73', '#f0ab00', '#ec7a08', '#2aaa04'];
  const value = parseInt(slider.value);
  const labelId = 'label-' + slider.id;
  const labelElement = document.getElementById(labelId);

  console.log('Updating slider:', slider.id, 'value:', value, 'labelId:', labelId);

  if (labelElement) {
    labelElement.textContent = labels[value];
    labelElement.style.setProperty('color', colors[value], 'important');
    labelElement.style.setProperty('font-weight', value > 0 ? '600' : '400', 'important');
  } else {
    console.error('Label element not found:', labelId);
  }

  // Update slider color
  slider.style.setProperty('--slider-color', colors[value]);

  // Update progress indicator
  updateProgressIndicator();
}

// Update progress counter in header
function updateProgressIndicator() {
  const sliders = document.querySelectorAll('.maturity-slider');
  const totalCapabilities = sliders.length;

  // Count how many sliders have been moved from 0 (rated)
  let ratedCount = 0;
  sliders.forEach(slider => {
    const value = parseInt(slider.value);
    if (value > 0) {
      ratedCount++;
    }
  });

  // Update the counter in the header
  const ratedCountElement = document.getElementById('ratedCount');
  const totalCountElement = document.getElementById('totalCount');

  if (ratedCountElement) {
    ratedCountElement.textContent = ratedCount;
  }

  if (totalCountElement) {
    totalCountElement.textContent = totalCapabilities;
  }
}

// Initialize all sliders on page load
window.addEventListener('DOMContentLoaded', function() {
  const sliders = document.querySelectorAll('.maturity-slider');
  sliders.forEach(slider => {
    updateSliderLabel(slider);
  });

  // Initialize progress indicator
  updateProgressIndicator();
});
</script>
<script type="text/javascript" >
document.getElementById("defaultOpen").click();

// Custom tooltip implementation with HTML support
$(document).ready(function() {
	console.log("Found tooltip icons:", $(".tooltip-icon").length);

	// Create tooltip container
	var $tooltip = $('<div class="custom-capability-tooltip"></div>').appendTo('body');

	$(".tooltip-icon").on('mouseenter', function(e) {
		var htmlContent = $(this).attr('data-tooltip');
		if (!htmlContent) return;

		// Decode HTML entities
		var txt = document.createElement("textarea");
		txt.innerHTML = htmlContent;
		var decoded = txt.value;

		// Set tooltip content and show it
		$tooltip.html(decoded).fadeIn(200);

		// Position tooltip
		var iconOffset = $(this).offset();
		$tooltip.css({
			top: iconOffset.top + 25,
			left: iconOffset.left - 200
		});
	});

	$(".tooltip-icon").on('mouseleave', function() {
		$tooltip.fadeOut(200);
	});

	// Move tooltip with mouse for better UX
	$(".tooltip-icon").on('mousemove', function(e) {
		$tooltip.css({
			top: e.pageY + 15,
			left: e.pageX - 250
		});
	});
});
</script>
<?php endif; ?>

<footer class="disclaimer-footer">
  <p><strong>Red Hat Disclaimer:</strong> This Cloud Sovereignty Framework Self-Assessment Tool is provided by Red Hat to help organizations review their sovereign posture. It is not endorsed by any regulatory authority, and its findings or recommendations do not constitute legal advice. Red Hat bears no legal responsibility or liability for the results or its use.</p>
</footer>

</body>
</html>
