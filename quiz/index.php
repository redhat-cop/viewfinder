<?php
session_start();

$lb_file = 'data/leaderboard.json';
$q_file = 'questions.json';

if (!is_dir('data')) { mkdir('data', 0755); }

// 1. LOAD QUESTIONS
if (!file_exists($q_file)) { die("Error: questions.json not found."); }
$domains = json_decode(file_get_contents($q_file), true);

// 2. LEADERBOARD SAVE LOGIC
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['lb_optin'])) {
    $name = htmlspecialchars($_POST['name']);
    $score = intval($_POST['score']);
    $date = date('Y-m-d');

    $lb_data = [];
    if (file_exists($lb_file)) {
        $lb_data = json_decode(file_get_contents($lb_file), true) ?: [];
    }

    $lb_data[] = ['name' => $name, 'score' => $score, 'date' => $date];
    file_put_contents($lb_file, json_encode($lb_data, JSON_PRETTY_PRINT));
}

// 3. RESET LOGIC
if (isset($_GET['reset'])) {
    session_destroy();
    header("Location: index.php");
    exit;
}

// 4. STICKY SHUFFLE LOGIC
if (!isset($_SESSION['quiz_structure'])) {
    foreach ($domains as $key => $domain) {
        $q_keys = array_keys($domain['questions']); shuffle($q_keys);
        $randQ = []; foreach ($q_keys as $k) { $randQ[$k] = $domain['questions'][$k]; }
        $domains[$key]['questions'] = $randQ;
    }
    $d_keys = array_keys($domains); shuffle($d_keys);
    $randD = []; foreach ($d_keys as $dk) { $randD[$dk] = $domains[$dk]; }
    $_SESSION['quiz_structure'] = $randD;
    $domains = $randD;
} else {
    $domains = $_SESSION['quiz_structure'];
}

// 5. CALCULATION LOGIC
$results = null; $best_domains = [];
if (isset($_POST['quiz_submit'])) {
    $results = []; $total_q = 0; $total_correct = 0; $max_score = -1;
    foreach ($domains as $dKey => $dData) {
        $d_correct = 0; $d_total = 0;
        foreach ($dData['questions'] as $qKey => $qData) {
            $uAns = $_POST[$qKey] ?? ''; $isCorrect = ($uAns === $qData['a']);
            if ($isCorrect) { $total_correct++; $d_correct++; }
            $total_q++; $d_total++;
            $results[$dData['title']]['items'][] = ["is_correct" => $isCorrect, "exp" => $qData['e'], "s" => $qData['s']];
        }
        $d_perc = round(($d_correct / $d_total) * 100); $results[$dData['title']]['score'] = $d_perc;
        if ($d_perc > $max_score) { $max_score = $d_perc; $best_domains = [$dData['title']]; }
        elseif ($d_perc == $max_score && $max_score > 0) { $best_domains[] = $dData['title']; }
    }
    $final_score = round(($total_correct / $total_q) * 100);
    $readiness = $final_score <= 33 ? ["Level" => "Foundation", "Color" => "#c9190b", "Icon" => "🏗️"] : ($final_score <= 66 ? ["Level" => "Strategic", "Color" => "#f0ab00", "Icon" => "📈"] : ["Level" => "Advanced", "Color" => "#2aaa04", "Icon" => "🚀"]);
}
?>
<!DOCTYPE html>
<html lang="en-GB" class="pf-theme-dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Digital Sovereignty Quiz - Viewfinder</title>

    <!-- Reuse existing CSS from parent directory -->
    <link rel="stylesheet" href="../css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/brands.css" />
    <link rel="stylesheet" href="../css/style.css" />
    <link rel="stylesheet" href="../css/tab-dark.css" />
    <link rel="stylesheet" href="../css/patternfly.css" />
    <link rel="stylesheet" href="../css/patternfly-addons.css" />

    <script src="https://code.jquery.com/jquery-3.6.0.js"></script>
    <script src="https://kit.fontawesome.com/8a8c57f9cf.js" crossorigin="anonymous"></script>

    <style>
        /* PatternFly Dark Theme Override */
        body {
            background-color: #151515 !important;
            color: #ccc !important;
            margin: 0;
            padding: 0;
            min-height: 100vh;
        }

        .quiz-wrapper {
            min-height: calc(100vh - 200px);
            padding: 2rem 0;
        }

        .quiz-container {
            max-width: 800px;
            margin: 0 auto;
            padding: 0 20px;
        }

        /* App Container Card */
        .app-container {
            background-color: #2a2a2a;
            border: 1px solid #444;
            border-radius: 12px;
            padding: 2.5rem;
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.3);
            color: #ccc;
            margin-bottom: 2rem;
            transition: all 0.3s ease;
        }

        .app-container:hover {
            border-color: #0d60f8;
            box-shadow: 0 6px 20px rgba(13, 96, 248, 0.2);
        }

        /* View Transitions */
        .view-content {
            transition: opacity 0.3s ease-in-out;
        }

        .hidden-view {
            display: none;
            opacity: 0;
        }

        .slide-left-out {
            opacity: 0;
        }

        .slide-right-out {
            opacity: 0;
        }

        /* Headers */
        h1 {
            color: #9ec7fc;
            font-size: 2rem;
            margin-bottom: 1rem;
            text-align: center;
        }

        h2 {
            color: #12bbd4;
            font-size: 1.3rem;
            margin: 1.5rem 0 1rem 0;
        }

        .subtitle {
            color: #ccc;
            line-height: 1.6;
            font-size: 1rem;
            margin-bottom: 1.5rem;
            text-align: center;
        }

        /* Landing Grid */
        .landing-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1.5rem;
            margin: 2rem 0;
        }

        .landing-item {
            background: #1a1a1a;
            padding: 1.5rem;
            border-radius: 8px;
            border: 1px solid #555;
            transition: all 0.2s ease;
        }

        .landing-item:hover {
            border-color: #0d60f8;
            background: #252525;
        }

        .landing-item.full-width {
            grid-column: span 2;
        }

        .landing-item b {
            color: #12bbd4;
            display: block;
            margin-bottom: 0.75rem;
            font-size: 1.1rem;
        }

        /* Domain Labels */
        .domain-labels-container {
            display: flex;
            flex-wrap: wrap;
            gap: 0.5rem;
            justify-content: center;
            margin-top: 0.5rem;
        }

        .domain-label {
            background: #0d60f8;
            color: #fff;
            padding: 0.4rem 0.8rem;
            border-radius: 6px;
            font-size: 0.85rem;
            font-weight: 600;
            white-space: nowrap;
        }

        /* Privacy List */
        .privacy-list {
            list-style: none;
            padding: 0;
            margin: 0.5rem 0 0 0;
        }

        .privacy-item {
            position: relative;
            padding-left: 1.5rem;
            margin-bottom: 0.5rem;
            font-size: 0.9rem;
            color: #ccc;
        }

        .privacy-item::before {
            content: "✓";
            position: absolute;
            left: 0;
            color: #2aaa04;
            font-weight: 900;
        }

        /* Leaderboard */
        .sort-pills {
            display: flex;
            gap: 0.5rem;
            margin-bottom: 1rem;
            justify-content: center;
        }

        .sort-pill {
            display: inline-block;
            padding: 0.4rem 1rem;
            border-radius: 6px;
            background: #1a1a1a;
            color: #9ca3af;
            text-decoration: none;
            font-size: 0.85rem;
            font-weight: 700;
            border: 1px solid #444;
            transition: all 0.2s ease;
        }

        .sort-pill.active {
            background: #0d60f8;
            color: #fff;
            border-color: #0d60f8;
        }

        .sort-pill:hover {
            background: #0d60f8;
            color: #fff;
        }

        .lb-table {
            width: 100%;
            font-size: 0.9rem;
            border-collapse: collapse;
            margin-top: 0.5rem;
        }

        .lb-table td {
            padding: 0.6rem 0;
            border-bottom: 1px solid #444;
            color: #ccc;
        }

        .lb-table tr:hover {
            background: #252525;
        }

        /* Buttons */
        .btn-primary {
            background: linear-gradient(135deg, #0d60f8 0%, #004cbf 100%);
            color: white;
            padding: 1rem 2rem;
            border-radius: 8px;
            font-weight: 700;
            border: none;
            cursor: pointer;
            transition: all 0.3s ease;
            font-size: 1rem;
            display: inline-block;
        }

        .btn-primary:hover {
            background: linear-gradient(135deg, #4d90fe 0%, #0d60f8 100%);
            box-shadow: 0 4px 12px rgba(13, 96, 248, 0.4);
            transform: translateY(-2px);
        }

        .btn-secondary {
            background: #444;
            color: #ccc;
            padding: 0.8rem 1.5rem;
            border-radius: 8px;
            font-weight: 700;
            border: 2px solid #555;
            cursor: pointer;
            transition: all 0.2s ease;
            font-size: 0.95rem;
        }

        .btn-secondary:hover {
            background: #555;
            color: #fff;
            border-color: #666;
        }

        /* Progress Dots */
        .progress-dots {
            display: flex;
            gap: 0.75rem;
            justify-content: center;
            margin-bottom: 2rem;
        }

        .dot {
            width: 12px;
            height: 12px;
            border-radius: 50%;
            background: #444;
            transition: all 0.3s ease;
        }

        .dot.active {
            background: #0d60f8;
            transform: scale(1.4);
            box-shadow: 0 0 10px rgba(13, 96, 248, 0.6);
        }

        /* Quiz Steps */
        .step {
            display: none;
            opacity: 0;
            transition: opacity 0.4s ease-in-out;
        }

        .step.active {
            display: block;
            opacity: 1;
        }

        .step h2 {
            text-align: center;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 1.5rem;
        }

        /* Question Rows */
        .q-row {
            position: relative;
            margin-bottom: 1.5rem;
            padding: 1.25rem;
            background: #1a1a1a;
            border-radius: 8px;
            border: 1px solid #444;
        }

        .q-row:hover {
            border-color: #0d60f8;
        }

        .q-text {
            font-weight: 600;
            font-size: 1rem;
            color: #ccc;
            display: block;
            margin-bottom: 0.75rem;
        }

        .hint-trigger {
            font-size: 0.85rem;
            color: #12bbd4;
            cursor: help;
            font-weight: 700;
            text-decoration: underline;
            margin-left: 0.5rem;
        }

        .q-hint-box {
            visibility: hidden;
            width: 300px;
            background: #333;
            color: #fff;
            text-align: center;
            border-radius: 6px;
            padding: 0.75rem;
            position: absolute;
            z-index: 10;
            bottom: 110%;
            left: 50%;
            transform: translateX(-50%);
            opacity: 0;
            transition: opacity 0.2s;
            font-size: 0.9rem;
            pointer-events: none;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.5);
        }

        .q-hint-box::after {
            content: "";
            position: absolute;
            top: 100%;
            left: 50%;
            margin-left: -5px;
            border-width: 5px;
            border-style: solid;
            border-color: #333 transparent transparent transparent;
        }

        .hint-trigger:hover + .q-hint-box {
            visibility: visible;
            opacity: 1;
        }

        /* Button Group for True/False */
        .btn-group {
            display: flex;
            gap: 1rem;
            margin-top: 0.75rem;
        }

        .btn-group input {
            display: none;
        }

        .btn-group label {
            flex: 1;
            text-align: center;
            padding: 0.75rem;
            border: 2px solid #444;
            border-radius: 8px;
            cursor: pointer;
            font-weight: 700;
            background: #1a1a1a;
            color: #ccc;
            font-size: 1rem;
            transition: all 0.2s ease;
        }

        .btn-group label:hover {
            border-color: #0d60f8;
            background: #252525;
        }

        .btn-group input:checked + label {
            background: #0d60f8;
            color: white;
            border-color: #0d60f8;
        }

        /* Navigation Buttons */
        .nav-btns {
            display: flex;
            justify-content: space-between;
            margin-top: 2rem;
            gap: 1rem;
        }

        /* Results */
        .results-score {
            text-align: center;
            margin-bottom: 2rem;
        }

        .results-score .score-display {
            font-size: 4rem;
            font-weight: 800;
            color: #0d60f8;
            line-height: 1;
        }

        .results-level {
            display: inline-block;
            padding: 0.75rem 2rem;
            border-radius: 10px;
            font-weight: 800;
            margin-top: 1rem;
            font-size: 1.2rem;
            color: #fff;
        }

        .best-domains-box {
            background: linear-gradient(135deg, #1a1a1a 0%, #2a2a2a 100%);
            border: 2px solid #0d60f8;
            border-radius: 8px;
            padding: 1rem;
            margin: 1.5rem 0;
            text-align: center;
            color: #12bbd4;
            font-weight: 600;
        }

        .results-details {
            max-height: 500px;
            overflow-y: auto;
            margin-top: 2rem;
            border-top: 2px solid #444;
            padding-top: 1.5rem;
        }

        .domain-results {
            margin-bottom: 2rem;
        }

        .domain-results h3 {
            font-weight: 800;
            font-size: 1rem;
            color: #12bbd4;
            text-transform: uppercase;
            margin-bottom: 1rem;
            letter-spacing: 1px;
        }

        .result-item {
            padding: 1rem;
            border-radius: 8px;
            margin-bottom: 1rem;
            background: #1a1a1a;
            border-left: 5px solid;
            font-size: 0.9rem;
        }

        .result-item.correct {
            border-left-color: #2aaa04;
        }

        .result-item.incorrect {
            border-left-color: #c9190b;
        }

        .result-item .statement {
            font-style: italic;
            color: #999;
            margin-bottom: 0.5rem;
        }

        .result-item .explanation {
            color: #ccc;
        }

        .result-item .icon {
            font-weight: 800;
            font-size: 1.1rem;
            margin-right: 0.5rem;
        }

        .result-item.correct .icon {
            color: #2aaa04;
        }

        .result-item.incorrect .icon {
            color: #c9190b;
        }

        /* Certificate Form */
        .certificate-box {
            background: #1a1a1a;
            border: 2px dashed #0d60f8;
            border-radius: 12px;
            padding: 2rem;
            text-align: center;
            margin-top: 2rem;
        }

        .certificate-box input[type="text"] {
            width: 70%;
            padding: 0.75rem;
            border-radius: 6px;
            border: 1px solid #444;
            background: #2a2a2a;
            color: #ccc;
            font-family: inherit;
            font-size: 1rem;
            margin-bottom: 1rem;
        }

        .certificate-box input[type="text"]:focus {
            outline: none;
            border-color: #0d60f8;
            box-shadow: 0 0 0 3px rgba(13, 96, 248, 0.2);
        }

        .certificate-box label {
            color: #ccc;
            font-size: 0.9rem;
        }

        .certificate-box input[type="checkbox"] {
            width: 18px;
            height: 18px;
            accent-color: #0d60f8;
            margin-right: 0.5rem;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .landing-grid {
                grid-template-columns: 1fr;
            }

            .landing-item.full-width {
                grid-column: span 1;
            }

            .quiz-container {
                padding: 0 15px;
            }

            .app-container {
                padding: 1.5rem;
            }

            h1 {
                font-size: 1.5rem;
            }
        }
    </style>
</head>
<body>
    <header class="pf-c-page__header no-print">
        <div class="pf-c-page__header-brand">
            <div class="pf-c-page__header-brand-toggle"></div>
            <a class="pf-c-page__header-brand-link" href="../index.php">
                <img class="pf-c-brand" src="../images/viewfinder-logo.png" alt="Viewfinder logo" />
            </a>
        </div>
        <div class="pf-c-page__header-tools">
            <div class="widget">
                <a href="../index.php"><button><i class="fa-solid fa-home"></i> Home</button></a>
            </div>
        </div>
    </header>

    <div class="quiz-wrapper">
        <div class="quiz-container">
            <div class="app-container">

                <?php if (!isset($_POST['quiz_submit'])): ?>
                <div id="view-landing" class="view-content">
                    <h1><i class="fa-solid fa-graduation-cap"></i> Sovereignty Readiness Assessment</h1>
                    <p class="subtitle">Evaluate your knowledge on digital independence. This 7-domain assessment identifies your current knowledge on reliance and technical autonomy.</p>

                    <div class="landing-grid">
                        <div class="landing-item full-width">
                            <b><i class="fa-solid fa-sitemap"></i> 7 Critical Domains</b>
                            <div class="domain-labels-container">
                                <?php foreach($domains as $d): ?>
                                    <span class="domain-label"><?php echo $d['title']; ?></span>
                                <?php endforeach; ?>
                            </div>
                        </div>

                        <div class="landing-item">
                            <b>🏆 Leaderboard (Top 5)</b>
                            <?php
                            $lb_list = [];
                            $sort_type = isset($_GET['sort']) ? $_GET['sort'] : 'score';

                            if (file_exists($lb_file)) {
                                $json_data = file_get_contents($lb_file);
                                $decoded = json_decode($json_data, true);

                                if (is_array($decoded)) {
                                    $lb_list = $decoded;

                                    if ($sort_type === 'date') {
                                        usort($lb_list, function($a, $b) {
                                            $t1 = isset($a['date']) ? strtotime($a['date']) : 0;
                                            $t2 = isset($b['date']) ? strtotime($b['date']) : 0;
                                            return $t2 <=> $t1;
                                        });
                                    } else {
                                        usort($lb_list, function($a, $b) {
                                            $s1 = isset($a['score']) ? (int)$a['score'] : 0;
                                            $s2 = isset($b['score']) ? (int)$b['score'] : 0;
                                            return $s2 <=> $s1;
                                        });
                                    }
                                    $lb_list = array_slice($lb_list, 0, 5);
                                }
                            }
                            ?>

                            <div class="sort-pills">
                                <a href="index.php?sort=score" class="sort-pill <?php echo ($sort_type === 'score' ? 'active' : ''); ?>">By Score</a>
                                <a href="index.php?sort=date" class="sort-pill <?php echo ($sort_type === 'date' ? 'active' : ''); ?>">By Date</a>
                            </div>

                            <?php if (!empty($lb_list)): ?>
                            <table class="lb-table">
                                <?php foreach($lb_list as $e): ?>
                                <tr>
                                    <td style="max-width: 150px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                                        <?php echo htmlspecialchars($e['name']); ?>
                                    </td>
                                    <td style="text-align:right; font-size: 0.8rem; color: #9ca3af; padding-right: 0.5rem;">
                                        <?php echo $e['date']; ?>
                                    </td>
                                    <td style="text-align:right; font-weight:800; color:#0d60f8;">
                                        <?php echo $e['score']; ?>%
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </table>
                            <?php else: ?>
                            <div style="margin-top:1rem; color:#9ca3af; text-align:center; font-size: 0.9rem;">No scores yet!</div>
                            <?php endif; ?>
                        </div>

                        <div class="landing-item">
                            <b>🔒 Privacy Policy</b>
                            <div class="privacy-list">
                                <div class="privacy-item">Data processed locally</div>
                                <div class="privacy-item">No results saved unless opted-in</div>
                                <div class="privacy-item">Leaderboard stores Name & Score</div>
                                <div class="privacy-item">Certificates are private</div>
                            </div>
                        </div>
                    </div>

                    <div style="text-align: center; margin-top: 2rem;">
                        <button type="button" class="btn-primary" onclick="startQuiz()">
                            <i class="fa-solid fa-play"></i> Begin Assessment
                        </button>
                    </div>
                </div>

                <div id="view-quiz" class="view-content hidden-view">
                    <form id="quizForm" method="POST">
                        <div class="progress-dots">
                            <?php $i=0; foreach($domains as $d): ?>
                                <div class="dot <?php echo $i==0?'active':''; ?>" id="dot_<?php echo $i++; ?>"></div>
                            <?php endforeach; ?>
                        </div>

                        <?php $step=0; foreach ($domains as $dKey => $dData): ?>
                            <div class="step <?php echo $step==0?'active':''; ?>" id="step_<?php echo $step; ?>">
                                <h2><?php echo $dData['title']; ?></h2>
                                <?php foreach ($dData['questions'] as $qKey => $qData): ?>
                                    <div class="q-row">
                                        <span class="q-text"><?php echo $qData['s']; ?></span>
                                        <span class="hint-trigger">(Hint)</span>
                                        <span class="q-hint-box"><?php echo $qData['h']; ?></span>
                                        <div class="btn-group">
                                            <input type="radio" id="<?php echo $qKey; ?>_t" name="<?php echo $qKey; ?>" value="true">
                                            <label for="<?php echo $qKey; ?>_t">TRUE</label>
                                            <input type="radio" id="<?php echo $qKey; ?>_f" name="<?php echo $qKey; ?>" value="false">
                                            <label for="<?php echo $qKey; ?>_f">FALSE</label>
                                        </div>
                                    </div>
                                <?php endforeach; ?>

                                <div class="nav-btns">
                                    <?php if($step > 0): ?>
                                        <button type="button" onclick="changeStep(<?php echo $step-1; ?>, 'back')" class="btn-secondary">
                                            <i class="fa-solid fa-arrow-left"></i> Back
                                        </button>
                                    <?php else: ?>
                                        <div></div>
                                    <?php endif; ?>

                                    <?php if($step < 6): ?>
                                        <button type="button" class="btn-primary" onclick="changeStep(<?php echo $step+1; ?>, 'next')">
                                            Next <i class="fa-solid fa-arrow-right"></i>
                                        </button>
                                    <?php else: ?>
                                        <button type="submit" name="quiz_submit" class="btn-primary" style="background: linear-gradient(135deg, #2aaa04 0%, #1b7003 100%);">
                                            <i class="fa-solid fa-check"></i> Get Results
                                        </button>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php $step++; endforeach; ?>
                    </form>
                </div>
                <?php endif; ?>

                <?php if (isset($_POST['quiz_submit'])): ?>
                <div class="view-content" style="opacity:1;">
                    <div class="results-score">
                        <div class="score-display"><?php echo $final_score; ?>%</div>
                        <div class="results-level" style="background:<?php echo $readiness['Color']; ?>;">
                            <?php echo $readiness['Icon']; ?> <?php echo $readiness['Level']; ?>
                        </div>
                    </div>

                    <?php if(!empty($best_domains)): ?>
                        <div class="best-domains-box">
                            ⭐ <strong>Core Strength:</strong> <?php echo implode(", ", $best_domains); ?>
                        </div>
                    <?php endif; ?>

                    <div class="results-details">
                        <?php foreach ($results as $title => $data): ?>
                            <div class="domain-results">
                                <h3><?php echo $title; ?> (<?php echo $data['score']; ?>%)</h3>
                                <?php foreach ($data['items'] as $item): ?>
                                    <div class="result-item <?php echo $item['is_correct']?'correct':'incorrect'; ?>">
                                        <div class="statement">"<?php echo $item['s']; ?>"</div>
                                        <div class="explanation">
                                            <span class="icon"><?php echo $item['is_correct']?'✓':'✗'; ?></span>
                                            <?php echo $item['exp']; ?>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <div class="certificate-box">
                        <form action="index.php" method="POST" id="lbForm">
                            <input type="hidden" name="score" value="<?php echo $final_score; ?>">
                            <input type="text" id="cert_name" name="name" placeholder="Full Name for Certificate" required>
                            <div style="margin-top: 1rem;">
                                <input type="checkbox" id="lb_optin" name="lb_optin" value="yes">
                                <label for="lb_optin">Opt-in: Display score on Leaderboard 🏆</label>
                            </div>
                            <button type="submit" class="btn-primary" style="margin-top:1.5rem;" onclick="generateCertAndSave()">
                                <i class="fa-solid fa-certificate"></i> Get Certificate & Save Score
                            </button>
                        </form>
                    </div>

                    <div style="text-align: center; margin-top: 1.5rem;">
                        <button onclick="window.location.href='index.php?reset=1'" class="btn-secondary" style="width: 100%;">
                            <i class="fa-solid fa-rotate-right"></i> Restart Quiz
                        </button>
                    </div>
                </div>
                <script>
                function generateCertAndSave() {
                    const name = document.getElementById('cert_name').value;
                    const score = "<?php echo $final_score; ?>";
                    const level = "<?php echo $readiness['Level']; ?>";
                    const data = "<?php echo base64_encode(json_encode($results)); ?>";
                    if(name.trim() === "") return;
                    window.open(`certificate.php?name=${encodeURIComponent(name)}&score=${score}&level=${encodeURIComponent(level)}&domain_data=${data}&ts=${Date.now()}`, '_blank');
                }
                </script>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <footer class="disclaimer-footer">
        <p><strong>Red Hat Disclaimer:</strong> This application is provided for informational purposes only. The information is provided "as is" with no guarantee or warranty of accuracy, completeness, or fitness for a particular purpose.</p>
    </footer>

    <script>
    function startQuiz() {
        const landing = document.getElementById('view-landing');
        const quiz = document.getElementById('view-quiz');
        landing.classList.add('slide-left-out');
        setTimeout(() => {
            landing.style.display = 'none';
            quiz.style.display = 'block';
            setTimeout(() => quiz.classList.remove('hidden-view'), 50);
        }, 400);
    }

    function changeStep(s, direction) {
        const active = document.querySelector('.step.active');
        if(direction === 'next') {
            const inputs = active.querySelectorAll('input[type="radio"]');
            const names = [...new Set([...inputs].map(i => i.name))];
            let ok = true;
            names.forEach(n => { if(!document.querySelector(`input[name="${n}"]:checked`)) ok = false; });
            if(!ok) { alert("Please answer all questions before proceeding!"); return; }
            active.classList.add('slide-left-out');
        } else { active.classList.add('slide-right-out'); }

        setTimeout(() => {
            document.querySelectorAll('.step').forEach(el => {
                el.classList.remove('active', 'slide-left-out', 'slide-right-out');
                el.style.opacity = '0';
            });
            document.querySelectorAll('.dot').forEach(el => el.classList.remove('active'));
            const nextStep = document.getElementById('step_' + s);
            nextStep.classList.add('active');
            nextStep.style.opacity = '1';
            document.getElementById('dot_' + s).classList.add('active');
        }, 200);
    }
    </script>
</body>
</html>
