<?php
$name = htmlspecialchars($_GET['name'] ?? 'Participant');
$score = htmlspecialchars($_GET['score'] ?? '0');
$level = htmlspecialchars($_GET['level'] ?? 'Foundation');
$ts = $_GET['ts'] ?? time();
$date = date('F d, Y');

// Handle Domain Data
$domain_ratings = [];
if (isset($_GET['domain_data'])) {
    $decoded = json_decode(base64_decode($_GET['domain_data']), true);
    if ($decoded) {
        foreach ($decoded as $title => $data) {
            $domain_ratings[$title] = $data['score'];
        }
        arsort($domain_ratings);
    }
}

$hash = strtoupper(substr(md5($name . $ts), 0, 8));
$cert_id = "VF-DS-QUIZ-" . substr($hash, 0, 4) . "-" . substr($hash, 4, 4);

// Color for level badge
$levelColors = [
    'Foundation' => '#c9190b',
    'Strategic' => '#f0ab00',
    'Advanced' => '#2aaa04'
];
$levelColor = $levelColors[$level] ?? '#0d60f8';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Certificate - <?php echo $cert_id; ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;700;800&family=Libre+Baskerville:ital@1&display=swap" rel="stylesheet">
    <style>
        body {
            background: #151515;
            font-family: 'Inter', sans-serif;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            margin: 0;
            padding: 20px;
        }

        .cert-container {
            background: white;
            padding: 40px;
            width: 900px;
            max-width: 100%;
            min-height: 650px;
            border: 15px solid #2a2a2a;
            outline: 4px solid #0d60f8;
            position: relative;
            text-align: center;
            color: #1b1d21;
            box-sizing: border-box;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.5);
        }

        .cert-id {
            position: absolute;
            top: 20px;
            right: 30px;
            font-family: monospace;
            font-size: 11px;
            color: #c9190b;
            font-weight: 700;
        }

        .logo {
            max-width: 120px;
            margin-bottom: 15px;
        }

        h1 {
            font-size: 36px;
            font-weight: 800;
            text-transform: uppercase;
            margin: 10px 0;
            letter-spacing: 1.5px;
            color: #0d60f8;
        }

        .award-line {
            font-family: 'Libre Baskerville', serif;
            font-style: italic;
            font-size: 18px;
            margin: 10px 0;
            color: #4b5563;
        }

        .participant-name {
            font-size: 32px;
            font-weight: 800;
            color: #0d60f8;
            border-bottom: 3px solid #e5e7eb;
            display: inline-block;
            padding: 0 40px 5px 40px;
            margin: 15px 0;
        }

        /* Profile Section */
        .profile-section {
            margin-top: 25px;
            text-align: left;
            background: #f9fafb;
            padding: 30px;
            border-radius: 12px;
            border: 2px solid #e5e7eb;
        }

        .score-summary-box {
            display: flex;
            justify-content: space-around;
            align-items: center;
            padding-bottom: 25px;
            border-bottom: 2px solid #e5e7eb;
            margin-bottom: 25px;
        }

        .score-stat {
            text-align: center;
        }

        .score-label {
            font-size: 11px;
            text-transform: uppercase;
            color: #6b7280;
            letter-spacing: 1px;
            margin-bottom: 8px;
            font-weight: 700;
        }

        .score-val {
            font-size: 32px;
            font-weight: 800;
            color: #1b1d21;
            line-height: 1;
        }

        .level-val {
            color: <?php echo $levelColor; ?>;
            padding: 8px 20px;
            border-radius: 8px;
            background: <?php echo $levelColor; ?>20;
            border: 2px solid <?php echo $levelColor; ?>;
        }

        /* Chart Container */
        .chart-container {
            width: 100%;
        }

        .chart-title {
            text-align: center;
            margin-bottom: 20px;
            font-size: 12px;
            text-transform: uppercase;
            color: #374151;
            letter-spacing: 1px;
            font-weight: 700;
        }

        .bar-row {
            display: flex;
            align-items: center;
            margin-bottom: 10px;
            font-size: 12px;
        }

        .bar-label {
            width: 220px;
            font-weight: 700;
            color: #374151;
            text-align: left;
        }

        .bar-bg {
            flex-grow: 1;
            background: #e5e7eb;
            height: 16px;
            border-radius: 8px;
            overflow: hidden;
            margin: 0 15px;
        }

        .bar-fill {
            height: 100%;
            background: linear-gradient(135deg, #0d60f8 0%, #004cbf 100%);
            border-radius: 8px;
            transition: width 0.3s ease;
        }

        .bar-val {
            width: 50px;
            text-align: right;
            font-weight: 800;
            color: #0d60f8;
        }

        /* Footer */
        .footer {
            margin-top: 35px;
            padding-top: 20px;
            border-top: 2px solid #e5e7eb;
            display: flex;
            justify-content: space-between;
            font-size: 12px;
            color: #6b7280;
        }

        .footer strong {
            color: #374151;
        }

        /* Download Button */
        .download-btn {
            margin-top: 30px;
            background: linear-gradient(135deg, #0d60f8 0%, #004cbf 100%);
            color: white;
            padding: 14px 30px;
            border: none;
            border-radius: 8px;
            font-weight: 700;
            cursor: pointer;
            font-size: 16px;
            transition: all 0.3s ease;
        }

        .download-btn:hover {
            background: linear-gradient(135deg, #4d90fe 0%, #0d60f8 100%);
            box-shadow: 0 6px 20px rgba(13, 96, 248, 0.4);
            transform: translateY(-2px);
        }

        /* Print Styles */
        @media print {
            .download-btn {
                display: none;
            }
            body {
                background: white;
                padding: 0;
            }
            .cert-container {
                border: 15px solid #eee;
                outline: 4px solid #0d60f8;
                box-shadow: none;
            }
        }

        /* Responsive */
        @media (max-width: 768px) {
            .cert-container {
                padding: 25px;
                border-width: 10px;
            }

            h1 {
                font-size: 28px;
            }

            .participant-name {
                font-size: 24px;
                padding: 0 20px 5px 20px;
            }

            .profile-section {
                padding: 20px;
            }

            .score-val {
                font-size: 24px;
            }

            .bar-label {
                width: 150px;
                font-size: 10px;
            }
        }
    </style>
</head>
<body>
    <div class="cert-container">
        <div class="cert-id">VERIFICATION ID: <?php echo $cert_id; ?></div>
        <img src="../images/viewfinder-logo.png" class="logo" alt="Viewfinder">
        <h1>Certificate of Achievement</h1>
        <div class="award-line">Digital Sovereignty Proficiency Assessment</div>

        <div class="participant-name"><?php echo $name; ?></div>

        <div class="profile-section">
            <div class="score-summary-box">
                <div class="score-stat">
                    <div class="score-label">Readiness Level</div>
                    <div class="score-val level-val"><?php echo $level; ?></div>
                </div>
                <div class="score-stat">
                    <div class="score-label">Overall Proficiency</div>
                    <div class="score-val"><?php echo $score; ?>%</div>
                </div>
            </div>

            <?php if (!empty($domain_ratings)): ?>
            <div class="chart-container">
                <div class="chart-title">Competency Profile Breakdown</div>
                <?php foreach ($domain_ratings as $title => $val): ?>
                <div class="bar-row">
                    <div class="bar-label"><?php echo strtoupper($title); ?></div>
                    <div class="bar-bg">
                        <div class="bar-fill" style="width: <?php echo $val; ?>%;"></div>
                    </div>
                    <div class="bar-val"><?php echo $val; ?>%</div>
                </div>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>
        </div>

        <div class="footer">
            <div>Issue Date: <strong><?php echo $date; ?></strong></div>
            <div>Verified by <strong>Viewfinder Assessment Platform</strong></div>
        </div>
    </div>

    <button class="download-btn" onclick="window.print()">
        <i class="fa-solid fa-download"></i> Download / Print Certificate
    </button>
</body>
</html>
