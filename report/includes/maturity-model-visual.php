<?php
/**
 * 5-Level Maturity Model Visualization
 * HTML/CSS replacement for the old maturity assessment image
 * Shows 5 maturity levels across domains from the current profile
 */

// Extract domains and capabilities from the $json variable (passed from parent scope)
$domains = [];
$capabilities = [];

// Map control names from controls JSON to maturity levels
// Control 1 -> Level 1 (Initial)
// Control 2 -> Level 2 (Managed)
// Control 3 -> Level 3 (Defined)
// Control 4 -> Level 4 (Quantitatively Managed)
// Control 5 -> Level 5 (Optimizing)
foreach ($json as $key => $domainData) {
    // Skip non-domain entries
    if (!isset($domainData['title']) || !is_array($domainData)) {
        continue;
    }

    $domainTitle = $domainData['title'];
    $domains[] = $domainTitle;

    // Extract controls 1-5 for this domain
    $capabilities[$domainTitle] = [];
    for ($i = 1; $i <= 5; $i++) {
        if (isset($domainData[(string)$i])) {
            $capabilities[$domainTitle][$i] = $domainData[(string)$i];
        }
    }
}

// Define the 5 maturity levels (bottom to top)
$maturityLevels = [
    5 => ['name' => 'Optimizing', 'color' => '#2aaa04', 'textColor' => '#ffffff'],
    4 => ['name' => 'Quantitatively Managed', 'color' => '#8bc34a', 'textColor' => '#000000'],
    3 => ['name' => 'Defined', 'color' => '#ffc107', 'textColor' => '#000000'],
    2 => ['name' => 'Managed', 'color' => '#ec7a08', 'textColor' => '#ffffff'],
    1 => ['name' => 'Initial', 'color' => '#e57373', 'textColor' => '#ffffff']
];
?>

<style>
.maturity-model-container {
    margin: 2rem auto;
    max-width: 1400px;
    padding: 2rem;
    background: #fff;
    border-radius: 8px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    clear: both;
    overflow-x: auto;
}

.maturity-model-grid {
    display: grid;
    grid-template-columns: 150px repeat(<?php echo count($domains); ?>, 1fr);
    gap: 2px;
    background: #ddd;
    border: 2px solid #333;
    position: relative;
}

.maturity-level-label {
    background: #2c3e50;
    color: #fff;
    padding: 1rem 0.5rem;
    display: flex;
    align-items: center;
    justify-content: center;
    text-align: center;
    font-weight: 600;
    font-size: 0.85rem;
    writing-mode: horizontal-tb;
    border-right: 2px solid #333;
}

.domain-header {
    background: #34495e;
    color: #fff;
    padding: 0.75rem 0.5rem;
    text-align: center;
    font-weight: 600;
    font-size: 0.8rem;
    line-height: 1.2;
    min-height: 60px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.maturity-cell {
    padding: 0.75rem 0.5rem;
    min-height: 85px;
    display: flex;
    align-items: center;
    justify-content: center;
    text-align: center;
    font-size: 0.8rem;
    line-height: 1.4;
    font-weight: 600;
    color: #2c3e50;
}

.axis-label-x {
    position: absolute;
    bottom: -40px;
    left: 50%;
    transform: translateX(-50%);
    font-weight: 700;
    font-size: 1rem;
    color: #2c3e50;
}

.axis-label-y {
    position: absolute;
    left: -80px;
    top: 50%;
    transform: translateY(-50%) rotate(-90deg);
    font-weight: 700;
    font-size: 1rem;
    color: #2c3e50;
    white-space: nowrap;
    transform-origin: center center;
}

.model-title {
    text-align: center;
    font-size: 1.5rem;
    font-weight: 700;
    color: #2c3e50;
    margin-bottom: 1.5rem;
}

.inflection-point {
    position: absolute;
    right: -120px;
    top: 50%;
    font-weight: 700;
    font-size: 0.9rem;
    color: #c9190b;
    writing-mode: vertical-rl;
    transform: rotate(180deg);
    white-space: nowrap;
}

.inflection-line {
    position: absolute;
    left: 0;
    right: 0;
    border-top: 3px dashed #c9190b;
    z-index: 10;
    pointer-events: none;
}

.maturity-progression {
    margin-top: 2rem;
    padding: 0;
    background: transparent;
}

.maturity-progression h3 {
    margin-top: 0;
    margin-bottom: 1rem;
    color: #2c3e50;
    font-size: 1.2rem;
    text-align: center;
    font-weight: 700;
}

.progression-items {
    display: grid;
    grid-template-columns: repeat(5, 1fr);
    gap: 0.75rem;
}

.progression-item {
    padding: 1rem;
    border-radius: 6px;
    text-align: center;
    font-size: 0.85rem;
    line-height: 1.5;
}

.maturity-model-container .progression-item strong {
    display: block;
    margin-bottom: 0.5rem;
    font-size: 0.85rem !important;
    text-transform: none !important;
}

.maturity-model-container .progression-item span {
    display: block;
    color: #2c3e50 !important;
    font-size: 0.75rem !important;
    line-height: 1.4;
    text-transform: none !important;
}

.progression-item.level-1 {
    background: #e5737320;
    border: 2px solid #e57373;
}
.progression-item.level-1 strong {
    color: #e57373;
}

.progression-item.level-2 {
    background: #ec7a0820;
    border: 2px solid #ec7a08;
}
.progression-item.level-2 strong {
    color: #ec7a08;
}

.progression-item.level-3 {
    background: #ffc10720;
    border: 2px solid #ffc107;
}
.progression-item.level-3 strong {
    color: #c58c00;
}

.progression-item.level-4 {
    background: #8bc34a20;
    border: 2px solid #8bc34a;
}
.progression-item.level-4 strong {
    color: #5a8c2a;
}

.progression-item.level-5 {
    background: #2aaa0420;
    border: 2px solid #2aaa04;
}
.progression-item.level-5 strong {
    color: #2aaa04;
}

@media (max-width: 1024px) {
    .progression-items {
        grid-template-columns: 1fr;
    }
}

@media print {
    .maturity-model-container {
        page-break-inside: avoid;
    }
}
</style>

<div class="maturity-model-container">
    <div class="model-title">Cloud Sovereignty 5-Level Maturity Model</div>

    <div style="position: relative; margin: 0 60px 40px 100px;">
        <div class="axis-label-y">Maturity Level</div>

        <div style="position: relative;">
            <!-- Inflection line between Level 4 and Level 3 -->
            <div class="inflection-line" style="top: calc(60px + (85px * 2) + 42px);"></div>

            <div class="maturity-model-grid">
            <!-- Header Row -->
            <div style="background: #2c3e50;"></div>
            <?php foreach ($domains as $domain): ?>
                <div class="domain-header"><?php echo htmlspecialchars($domain); ?></div>
            <?php endforeach; ?>

            <!-- Maturity Level Rows (reverse order - highest at top) -->
            <?php foreach ($maturityLevels as $level => $levelData): ?>
                <!-- Level Label -->
                <div class="maturity-level-label" style="background: <?php echo $levelData['color']; ?>; color: <?php echo $levelData['textColor']; ?>;">
                    Level <?php echo $level; ?>:<br><?php echo $levelData['name']; ?>
                </div>

                <!-- Domain Cells for this level -->
                <?php foreach ($domains as $domain): ?>
                    <div class="maturity-cell" style="background: <?php echo $levelData['color']; ?>20; border: 1px solid <?php echo $levelData['color']; ?>;">
                        <?php
                        if (isset($capabilities[$domain][$level])) {
                            echo htmlspecialchars($capabilities[$domain][$level]);
                        }
                        ?>
                    </div>
                <?php endforeach; ?>
            <?php endforeach; ?>
        </div>
        </div>

        <div class="axis-label-x">Sovereignty Domains</div>
    </div>

    <div class="maturity-progression">
        <h3>Maturity Progression Path</h3>
        <div class="progression-items">
            <div class="progression-item level-1">
                <strong>Level 1 - Initial</strong>
                <span>Ad-hoc and chaotic processes with unpredictable results. Success depends on individual heroics rather than established processes.</span>
                <span style="margin-top: 0.5rem; font-weight: 600; color: #c9190b !important;"><em>Key Characteristics:</em> Ad-hoc approach, unpredictable processes, hero-dependent, reactive firefighting</span>
            </div>
            <div class="progression-item level-2">
                <strong>Level 2 - Managed</strong>
                <span>Basic processes established and managed at project level. Requirements and controls are tracked, but implementation varies by team.</span>
                <span style="margin-top: 0.5rem; font-weight: 600; color: #ec7a08 !important;"><em>Key Characteristics:</em> Requirements tracking, basic controls, project-level planning, reactive management</span>
            </div>
            <div class="progression-item level-3">
                <strong>Level 3 - Defined</strong>
                <span>Standardized processes documented and understood organization-wide. Consistent implementation across all teams and projects.</span>
                <span style="margin-top: 0.5rem; font-weight: 600; color: #c58c00 !important;"><em>Key Characteristics:</em> Documented procedures, standardized approach, organization-wide consistency, process tailoring</span>
            </div>
            <div class="progression-item level-4">
                <strong>Level 4 - Quantitatively Managed</strong>
                <span>Data-driven approach with measurable KPIs and metrics. Performance is quantitatively understood and controlled within defined limits.</span>
                <span style="margin-top: 0.5rem; font-weight: 600; color: #5a8c2a !important;"><em>Key Characteristics:</em> Statistical process control, metrics-driven decisions, predictable outcomes, variance analysis</span>
            </div>
            <div class="progression-item level-5">
                <strong>Level 5 - Optimizing</strong>
                <span>Continuous improvement culture with proactive optimization. Organization leads industry in sovereignty practices and innovates new approaches.</span>
                <span style="margin-top: 0.5rem; font-weight: 600; color: #2aaa04 !important;"><em>Key Characteristics:</em> Automated monitoring, predictive analytics, continuous innovation, industry leadership</span>
            </div>
        </div>
    </div>
</div>
