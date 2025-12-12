

<!DOCTYPE html>
<html lang="en">
   <head>
      <!-- basic -->
      <meta charset="utf-8">
      <meta http-equiv="X-UA-Compatible" content="IE=edge">
      <!-- mobile metas -->
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <meta name="viewport" content="initial-scale=1, maximum-scale=1">
      <!-- site metas -->
      <title>Viewfinder Detailed Report</title>
      <meta name="keywords" content="">
      <meta name="description" content="">
      <meta name="author" content="">
      <!-- bootstrap css -->
      <link rel="stylesheet" href="css/bootstrap.min.css">
      <!-- style css -->
      <link rel="stylesheet" href="css/style.css">
      <!-- Responsive-->
      <link rel="stylesheet" href="css/responsive.css">
      <!-- Scrollbar Custom CSS -->
      <link rel="stylesheet" href="css/jquery.mCustomScrollbar.min.css">

      <link rel="stylesheet" href="css/table.css">
      <link rel="stylesheet" href="css/table2.css">

      <script src="https://kit.fontawesome.com/8a8c57f9cf.js" crossorigin="anonymous"></script>
      <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fancybox/2.1.5/jquery.fancybox.min.css" media="screen">
      <link rel="stylesheet" href="//code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
  <script src="https://code.jquery.com/jquery-3.6.0.js"></script>
  <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/d3/3.5.6/d3.min.js" charset="utf-8"></script>

   </head>
   <!-- body -->
   <body class="main-layout">

<!--
Images:
<a href="https://www.freepik.com/search">Icon by Freepik</a>



-->

<?php
require_once __DIR__ . '/../includes/Security.php';
require_once __DIR__ . '/../includes/MaturityRating.php';

// Parse and validate input data
parse_str($_SERVER["QUERY_STRING"] ?? '', $data);

// Validate profile parameter
$profile = Security::validateProfile($data['profile'] ?? $_REQUEST['profile'] ?? '');
$data['profile'] = $profile; // Update with validated value

// Safely load controls JSON
$controlsFile = dirname(__DIR__) . "/controls-{$profile}.json";
$json = Security::loadJSON($controlsFile);

if ($json === null) {
    die('Error: Unable to load assessment controls. Please contact support.');
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

$totalScore = 0;


?>

      <!-- header -->
      <header>
         <!-- header inner -->
            <div class="header">
               <div class="container-fluid">
                  <div class="row">
                        <div class="full">
                           <div class="center-desk">
                              <div class="logo">
                           <a href="index.php"><img src="images/viewfinder-logo.png" alt="#" /></a>
                                 
                        <div class="text-bg">
                           <h1><?php
                           if ($_REQUEST['profile'] == "Security") {
                              $assessment = "Security Maturity Assessment";
                              }else {
                              $assessment = "Digital Sovereignty Readiness Assessment";
                              }
                              print $assessment
                              ?> </h1>
   
</div>
                              
                           </div>
                     </div>


                  </div>
               </div>
            </div>
            <!-- end header inner -->
            <!-- end header -->
            <!-- banner -->
            <section class="banner_main">
               <div class="container-fluid">
                     <div class="col-md-4">


                           <?php if ($profile == "Security") {
                              print '
                           <h3> How does this radar chart help me?</h3>
                           <ul>
                              <li><b>Identify Weaknesses</b> Pinpoint vulnerabilities and weaknesses in your security infrastructure.</li>
                              <li><b>Help with Risk Mitigation</b> Understand potential risks and how to mitigate them effectively.</li>
                              <li><b>Resource Optimisation</b> Allocate resources more effectively by focusing on areas that need improvement the most.</li>
                           </ul>';
                           }
                           ?>
                        </div>
                     </div>

                        <div class="text-img">
                           
                           <figure>
                           <div class="radarChart"></div>
                           </figure>
                        </div>
</div>
<div class="overviewBlock">
<?php if ($profile == "DigitalSovereignty") {
                              print '   
<h1>Digital Sovereignty Maturity Assessment: Introduction</h1>
    <p>Digital sovereignty is a complex strategic imperative that seeks to maximize an organization\'s <strong>control over its own digital destiny</strong>—its data, technology, and operations. This maturity assessment provides a clear, actionable framework for evaluating your current level of control and independence. Understanding the trade-offs between global efficiency and self-determination is critical for charting a successful digital future.</p>

    <h2>Benefits of Digital Sovereignty</h2>

    <p>The key advantages center on reducing systemic risk and fostering strategic independence. By achieving digital sovereignty, an organization gains <strong>greater control over data residency and access</strong>, ensuring sensitive information is stored, processed, and governed under its preferred legal jurisdiction. This is essential for meeting stringent national and sectoral compliance requirements (e.g., in healthcare or finance) and protecting data from foreign surveillance laws. Furthermore, a sovereign approach improves <strong>operational resilience and business continuity</strong>. By lessening dependence on a small number of foreign hyper-scalers or proprietary technology stacks, the organization mitigates the risk of vendor lock-in, supply chain disruptions, or service outages caused by geopolitical conflict or sanctions. This autonomy supports long-term <strong>cost control and flexibility</strong>, allowing the organization to tailor its IT architecture to its unique mission rather than being confined by a provider\'s global roadmap.</p>

    <h2>Challenges of Digital Sovereignty</h2>

    <p>The path to sovereignty is not without friction. One of the main challenges is the <strong>increase in complexity and potential cost</strong>. Achieving true independence often means moving away from the convenience and scale of global cloud platforms, which can lead to higher operational expenses, particularly for smaller organizations. Building or acquiring sovereign-compliant alternatives—such as local cloud infrastructure, bespoke software, or open-source solutions—requires significant upfront investment and specialized technical talent. Another major hurdle is the risk of <strong>digital fragmentation</strong>. As an organization prioritizes internal or local solutions, it may lose out on the latest global innovations or sacrifice the seamless interoperability required to participate in international digital supply chains, creating an "innovation gap." Finally, managing the <b>evolving and non-uniform regulatory landscape</b> across different jurisdictions adds a layer of complexity to compliance that requires constant monitoring and adaptation.</p>
';
                           }
                           ?>
                           </div>
                     </div>
            </section>
         </div>
      </header>
      <div class="pagebreak"> </div>
      <!-- end banner -->
      <!-- business -->
      <div class="business">
                                       <?php if ($profile == "DigitalSovereignty") {
                              print '   
                              <div class="overviewBlock">
                              <div class="titlepage">
<span>Executive Summary: Strategic Imperatives for Digital Sovereignty</span>
</div>
<p>The Digital Sovereignty Maturity Assessment reveals the organization\'s current control posture and identifies critical dependencies across its digital ecosystem. Moving from a reactive, compliant state to a proactive, sovereign state requires executive-level commitment to strategic investments in autonomy and resilience. Our findings are grouped into four strategic areas requiring immediate focus:</p>

    <div class="section-header">
        <h3>1. Data and Legal Autonomy (Immediate Priority)</h3>
        <p>The core risk lies in external jurisdictional control over critical data. Achieving data sovereignty requires moving beyond mere residency and establishing cryptographic and legal independence. This is increasingly vital as new laws like the <b>EU AI Act</b> mandate specific governance, transparency, and data quality requirements based on the legal jurisdiction of the AI system\'s output and training data.</p>
        
        <p class="imperative">Imperative: Mandate a formal, comprehensive Data Residency Policy and enforce it with technical controls, securing legal independence from foreign jurisdictions.</p>
        
        <ul class="action-list">
            <li>Secure explicit contractual clauses that assign <b>exclusive governing law and jurisdiction</b> to mitigate risks from foreign legal access (e.g., the CLOUD Act).</li>
            <li>Most critically, transition to a <b>Customer-Managed Key (CMK) solution</b> where the root encryption keys for all sensitive data are independently controlled, effectively neutralizing third-party access rights.</li>
            <li>Implement rigorous <b>Data Flow and Transfer Auditing</b> to track data used in high-risk AI models, ensuring compliance with <b>EU AI Act</b> transparency and traceability obligations.</li>
        </ul>
        
        <div class="result">Result: Data access is determined by organizational policy and domestic law, supporting compliance with strict AI and privacy regulations.</div>
    </div>

    <div class="section-header">
        <h3>2. Strategic Technical Independence</h3>
        <p>Reducing the reliance on deeply integrated, proprietary vendor ecosystems is crucial for long-term operational freedom, cost control, and preventing vendor lock-in risk.</p>
        
        <p class="imperative">Imperative: Strategically eliminate single-vendor lock-in through the mandatory adoption of open industry standards and platform-agnostic architectures.</p>
        
        <ul class="action-list">
            <li>Fund the development of a formal <b>Portability Strategy</b> and execute regular <b>"exit drills"</b> to maintain the technical capability to migrate critical workloads and data quickly.</li>
            <li>Prioritize technologies that grant full-stack <b>Technology Stack Ownership & Control</b>, including the use of <b>Open Source Software (OSS)</b> where feasible to reduce reliance on proprietary codebases.</li>
            <li>Mandate the use of <b>Standardised Technical Frameworks</b> (e.g., Kubernetes, open APIs) to decouple applications from specific cloud platforms.</li>
        </ul>
        
        <div class="result">Result: Technology choices are driven by business needs and sovereignty requirements, not vendor roadmaps.</div>
    </div>

    <div class="section-header">
        <h3>3. Operational Control and Resilience</h3>
        <p>Operational sovereignty dictates that critical business functions must be insulated from external geopolitical and supply chain risks to guarantee continuity.</p>
        
        <p class="imperative">Imperative: Achieve verifiable Operational Autonomy for critical functions and eliminate reliance on external managed services for system administration.</p>
        
        <ul class="action-list">
            <li>Develop an executive-approved, tested <b>Sovereign Incident Response Plan</b> detailing steps for reacting to politically motivated attacks or foreign legal demands.</li>
            <li>Invest strategically in building and maintaining <b>in-house expertise</b> (Internal Skills and Competency Development) to operate key sovereign infrastructure independently.</li>
            <li>Formalize a <b>Supply Chain Vetting Program</b> to assess and mitigate geopolitical risk for all Tier 1 suppliers.</li>
        </ul>
        
        <div class="result">Result: Business continuity is guaranteed, even in scenarios involving geopolitical isolation or external vendor failure.</div>
    </div>

    <div class="section-header">
        <h3>4. Executive Governance and Investment</h3>
        <p>Sovereignty requires dedicated, top-down governance, moving it from an IT checklist to a core strategic pillar integrated into the organization’s long-term strategy.</p>
        
        <p class="imperative">Imperative: Formally establish the leadership and resource allocation necessary to execute and measure the sovereignty roadmap.</p>
        
        <ul class="action-list">
            <li>Appoint a <b>Designated Executive Sponsor</b> and empower a <b>Sovereignty Governance Board</b> (Legal, IT, Procurement) to oversee compliance and risk.</li>
            <li>Link accountability by defining and tracking quantitative <b>Key Performance Indicators (KPIs)</b> (e.g., data localization percentage) that are reported to the Board monthly.</li>
            <li>Ensure a protected <b>Budget Allocation for Sovereignty Initiatives</b>, recognizing the strategic value of resilience over short-term costs.</li>
        </ul>
        
        <div class="result">Result: Sovereignty risk mitigation is prioritized, funded, and managed at the highest level of the organization.</div>
    </div>
 ';
                           }
                           ?>
            <div class="row">
               <div class="col-md-12">
                  <div class="titlepage">
                     <span>Maturity Levels</span>
                     <h3>As of <?php print date('l jS \of F Y'); ?> </h3>
                  </div>
               </div>
            </div>
            <div class="row">
               <div class="col-md-12">
                  <div class="row">
                     <div class="col-md-12">
                        <div class="titlepage">
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
   <img src='<?php print "images/" . Security::escape($profile) . "-Maturity-Assessment.png" ?>' alt="#"/>
                        </div>
                     </div>
                  </div>
               </div>
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

<?php
// putDomainStatus function moved to MaturityRating class
?>

<div class="bigtable">
<div class="titlepage">
<div class="pagebreak"> </div>


                           <div class="titlepage">
<span>Current Status</span>
                        </div>
<table><thead><tr>
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

<hr>

</div>
         </div>
      </div>
      
      <!-- end business -->
      <!-- Projects -->
   <div class="pagebreak"> </div>;

      <div class="container">
            <div class="row">
               <div class="col-md-12">
                  <div class="titlepageLeft">

                  <!-- Start of recomendations -->

                  <?php
if (isset($_REQUEST['lob'])) {
    // Validate LOB parameter
    $lob = Security::validateLOB($_REQUEST['lob']);

    if ($lob !== null && $lob !== 'Other') {
        print "<br><h2>General Advice for " . Security::escape($lob) . " Industries</h2>";

        // Safely get LOB file path
        $safeFilePath = Security::getLOBFilePath($lob, $profile);

        if ($safeFilePath !== null) {
            include $safeFilePath;
            print "<img class=smallImage src=images/info.jpg>";
        } else {
            print '<p>No specific information available for this industry.</p>';
        }
    }
}

foreach ($controls as $control) {
    $highest=0;	
    $qnum = $json[$control]['qnum'];
	$score = $controlTotal[$qnum];
	$title = $json[$control]['title'];
	array_push($nextDomain, $title);
   #print '<div class="pagebreak"> </div>';
	$rating = MaturityRating::getRating($score);
    print "<br><h2>$title - <span class='cellHeader" . $rating . "'>". $rating . " Level</span></h2><div>";

    
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
        #print "<h4 class=title-text>Recommendation</h4>"; 
        print "<p>Start to work on preparing for actions concerning " . $json[$control][$nextLevel] . " (Level $nextLevel)<p>";
        print "<br><p class=why-what>Definition of " . $json[$control][$nextLevel] . " </p><p>" . $json[$control][$nextSummary] . "</p>";

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
#if ($levelArray) {
#	#print "Max: " . max($levelArray) . "<br>";
#	$allLevels = range(1,max($levelArray));
#	$missing = array_diff($allLevels,$levelArray);
#	if ($missing) {
#		print "<br><br><h4 class=why-what>Skipped Level(s)</h4>";
#		foreach ($missing as $notthere) {
#			$skippedRecommendation = $notthere . '-recommendation';
#			print "<p class=why-what>Level $notthere </p>";
#			if ($json[$control][$skippedRecommendation] != "") {
#			print $json[$control][$skippedRecommendation] . ". ";
#			} else {
#                $notthereComment = $notthere . "-summary";
##				print_r($json[$control][$notthere]);
#                print $json[$control][$notthereComment];
#			}
#			print "<br>";
#		}
#	}
#	}
   $randomImage = rand(1, 9);
   print "<img class=smallImage src=images/tech-image-" . $randomImage . ".png>";
    print "</div>";

}
?>

                  <!-- End of recommendations -->





                  </div>
               </div>
            </div>
            <div class="row">
               <div class="col-md-10 offset-md-1">
                  <div class="row">
 
                  </div>
               </div>
            </div>
         </div>
      <!-- end projects -->
      <!-- Testimonial -->
      <div class="section">
         <div class="container">
            <div id="" class="Testimonial">
               <div class="row">
                  <div class="col-md-12">
                     <div class="titlepage">
                        <h2>Need more information ?</h2>
                     </div>
                  </div>
               </div>
               <div class="row">
                  <div class="col-md-3">
                     <div class="Testimonial_box">
                     </div>
                  </div>
                  <div class="col-md-9">
                     <div class="Testimonial_box">
                        <p>Don't wait until it's too late. Take proactive steps and empower yourselves with Project Viewfinder and enable proactive digital sovereignty within your customer’s organisation with the Viewfinder Maturity Assessment. Contact your Red Hat account team for more information and take the first step towards a more autonomous future. 
                        </p>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
     
      <!-- end Testimonial -->
      
      <!--  footer -->
      <footer>
         <div class="footer">
            <div class="container">
               <div class="row">

               </div>
            </div>
            <div class="copyright">
               <div class="container">
                  <div class="row">
                     <div class="col-md-12">
                        <p>Copyright 2025 All Right Reserved </p>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </footer>
      <!-- end footer -->
      <!-- Javascript files-->
      <script src="js/jquery.min.js"></script>
      <script src="js/popper.min.js"></script>
      <script src="js/bootstrap.bundle.min.js"></script>
      <script src="js/jquery-3.0.0.min.js"></script>
      <script src="js/plugin.js"></script>
      <!-- sidebar -->
      <script src="js/jquery.mCustomScrollbar.concat.min.js"></script>
      <script src="js/custom.js"></script>
      <script src="https:cdnjs.cloudflare.com/ajax/libs/fancybox/2.1.5/jquery.fancybox.min.js"></script>

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

/*			var data = [
					  [
						{axis:"Secure Infrastructure",value:<?php echo $controlTotal[1]; ?>},
						{axis:"Secure Data",value:<?php echo $controlTotal[2]; ?>},
						{axis:"Secure Identity",value:<?php echo $controlTotal[3]; ?>},
						{axis:"Secure Application",value:<?php echo $controlTotal[4]; ?>},
						{axis:"Secure Network",value:<?php echo $controlTotal[5]; ?>},
						{axis:"Secure Recovery",value:<?php echo $controlTotal[6]; ?>},
						{axis:"Secure Operations",value:<?php echo $controlTotal[7]; ?>}
					  ]
					]; */
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
   </body>
</html>

