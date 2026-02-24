<!doctype html>
<html lang="en-us" class="pf-theme-dark">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Import Assessment Results - Viewfinder</title>

  <link rel="stylesheet" href="css/bootstrap.min.css">
  <link rel="stylesheet" href="css/brands.css" />
  <link rel="stylesheet" href="css/style.css" />
  <link rel="stylesheet" href="css/patternfly.css" />
  <link rel="stylesheet" href="css/patternfly-addons.css" />

  <script src="https://kit.fontawesome.com/8a8c57f9cf.js" crossorigin="anonymous"></script>

  <style>
    body {
      background-color: #151515 !important;
      color: #ccc !important;
      padding: 2rem;
    }
    .import-container {
      max-width: 800px;
      margin: 2rem auto;
      padding: 2rem;
      background-color: #1f1f1f;
      border-radius: 8px;
      border: 1px solid #333;
    }
    .import-header {
      margin-bottom: 2rem;
      padding-bottom: 1rem;
      border-bottom: 2px solid #333;
    }
    .import-header h1 {
      color: #fff;
      margin: 0 0 0.5rem 0;
      font-size: 2rem;
    }
    .import-header p {
      color: #999;
      margin: 0;
    }
    .upload-area {
      padding: 1.5rem;
      border: 2px dashed #555;
      border-radius: 8px;
      text-align: center;
      margin: 1.5rem 0;
      background-color: #151515;
    }
    .upload-area i {
      font-size: 2rem;
      color: #0066cc;
      margin-bottom: 0.5rem;
    }
    .upload-area h3 {
      margin: 0.5rem 0;
      font-size: 1.25rem;
    }
    .upload-area p {
      margin: 0.25rem 0;
    }
    .file-input {
      display: none;
    }
    .upload-button {
      padding: 0.75rem 2rem;
      background-color: #0066cc;
      color: white;
      border: none;
      border-radius: 4px;
      cursor: pointer;
      font-size: 1rem;
      margin-top: 1rem;
    }
    .upload-button:hover {
      background-color: #0052a3;
    }
    .file-selected {
      margin-top: 1rem;
      padding: 1rem;
      background-color: #1f1f1f;
      border-radius: 4px;
      border: 1px solid #333;
    }
    .error-message {
      padding: 1rem;
      background-color: #3c1f1f;
      border-left: 4px solid #c9190b;
      color: #f0ab00;
      margin: 1rem 0;
      border-radius: 4px;
    }
    .success-message {
      padding: 1rem;
      background-color: #1f3c1f;
      border-left: 4px solid #3e8635;
      color: #92d400;
      margin: 1rem 0;
      border-radius: 4px;
    }
    .info-box {
      padding: 1.5rem;
      background-color: #1a1a2e;
      border-left: 4px solid #0066cc;
      margin: 1.5rem 0;
      border-radius: 4px;
    }
    .info-box h3 {
      margin: 0 0 0.5rem 0;
      color: #0066cc;
    }
    .info-box ul {
      margin: 0.5rem 0 0 1.5rem;
      color: #ccc;
    }
    .button-group {
      margin-top: 2rem;
      text-align: center;
    }
    .button-group button {
      margin: 0 0.5rem;
    }
    .home-button {
      padding: 0.75rem 2rem;
      background-color: #444;
      color: white;
      border: none;
      border-radius: 4px;
      cursor: pointer;
      font-size: 1rem;
      text-decoration: none;
      display: inline-block;
    }
    .home-button:hover {
      background-color: #555;
    }
  </style>
</head>

<body>
  <div class="import-container">
    <?php
    require_once __DIR__ . '/error-pages/error-handler.php';
    require_once __DIR__ . '/includes/Security.php';
    require_once __DIR__ . '/includes/Logger.php';
    require_once __DIR__ . '/includes/Config.php';
    require_once __DIR__ . '/includes/ResultsImporter.php';
    require_once __DIR__ . '/includes/Exceptions/ResultsException.php';

    // Register error handlers
    ErrorHandler::register();

    // Start session for storing imported data
    session_start();

    // Handle POST request (file upload)
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        try {
            // Check if file was uploaded
            if (!isset($_FILES['results_file'])) {
                throw ResultsException::uploadFailed('No file uploaded', []);
            }

            $file = $_FILES['results_file'];

            // Read and parse file to determine type
            $jsonContent = file_get_contents($file['tmp_name']);
            if ($jsonContent === false) {
                throw ResultsException::uploadFailed('Unable to read uploaded file', []);
            }

            $data = ResultsImporter::parseJSON($jsonContent);
            ResultsImporter::validateResultsStructure($data);

            $assessmentType = $data['viewfinder_export']['type'];

            // Import based on type
            if ($assessmentType === 'security_assessment') {
                $importedData = ResultsImporter::importSecurityResults($file);
                $_SESSION['imported_results'] = $importedData;

                Logger::info('Security results imported via upload', [
                    'profile' => $importedData['assessment']['profile']
                ]);

                // Redirect to Security results page
                header('Location: results.php?imported=1');
                exit;

            } elseif ($assessmentType === 'ds_qualifier') {
                $importedData = ResultsImporter::importDSQualifierResults($file);
                $_SESSION['imported_results'] = $importedData;

                Logger::info('DS-Qualifier results imported via upload');

                // Redirect to DS-Qualifier results page
                header('Location: ds-qualifier/results.php?imported=1');
                exit;

            } else {
                throw ResultsException::invalidFormat(
                    'Unknown assessment type: ' . $assessmentType,
                    ['type' => $assessmentType]
                );
            }

        } catch (ResultsException $e) {
            Logger::logException($e);
            $error = $e->getUserMessage();
        } catch (\Throwable $e) {
            Logger::error('Unexpected error in import-results.php', [
                'exception' => get_class($e),
                'message' => $e->getMessage()
            ]);
            $error = 'An unexpected error occurred during import.';
        }
    }
    ?>

    <div class="import-header">
      <h1><i class="fa-solid fa-upload"></i> Import Assessment Results</h1>
      <p>Upload a previously exported Viewfinder assessment results file</p>
    </div>

    <?php if (isset($error)): ?>
      <div class="error-message">
        <strong><i class="fa-solid fa-exclamation-triangle"></i> Import Failed</strong><br>
        <?php echo Security::escape($error); ?>
      </div>
    <?php endif; ?>

    <div class="info-box">
      <h3><i class="fa-solid fa-info-circle"></i> Supported File Types</h3>
      <ul>
        <li>Security Assessment results (JSON format)</li>
        <li>Digital Sovereignty Readiness Assessment results (JSON format)</li>
      </ul>
      <p style="margin-top: 1rem; color: #999;">
        Maximum file size: 5MB. Only files exported from Viewfinder are supported.
      </p>
    </div>

    <form method="POST" enctype="multipart/form-data" id="import-form">
      <div class="upload-area" id="upload-area">
        <i class="fa-solid fa-cloud-upload-alt"></i>
        <h3 style="color: #fff;">Choose a file to upload</h3>
        <p style="color: #999;">Click the button below to select a JSON file</p>
        <input type="file"
               name="results_file"
               id="results_file"
               class="file-input"
               accept=".json,application/json"
               required>
        <label for="results_file" class="upload-button">
          <i class="fa-solid fa-folder-open"></i> Select File
        </label>
        <div id="file-selected" class="file-selected" style="display: none;">
          <i class="fa-solid fa-file-alt"></i>
          <span id="filename"></span>
          <span style="color: #999;" id="filesize"></span>
        </div>
      </div>

      <div class="button-group">
        <button type="submit" class="upload-button" id="import-button" disabled>
          <i class="fa-solid fa-upload"></i> Import Results
        </button>
        <a href="index.php" class="home-button">
          <i class="fa-solid fa-home"></i> Cancel
        </a>
      </div>
    </form>
  </div>

  <script>
    // File input handling
    const fileInput = document.getElementById('results_file');
    const fileSelected = document.getElementById('file-selected');
    const filename = document.getElementById('filename');
    const filesize = document.getElementById('filesize');
    const importButton = document.getElementById('import-button');

    fileInput.addEventListener('change', function(e) {
      if (this.files && this.files[0]) {
        const file = this.files[0];
        filename.textContent = file.name;

        // Format file size
        const size = file.size;
        const sizeKB = (size / 1024).toFixed(2);
        const sizeMB = (size / 1024 / 1024).toFixed(2);
        filesize.textContent = size < 1024 * 1024
          ? `(${sizeKB} KB)`
          : `(${sizeMB} MB)`;

        fileSelected.style.display = 'block';
        importButton.disabled = false;

        // Validate file type
        if (!file.name.endsWith('.json')) {
          alert('Please select a JSON file');
          this.value = '';
          fileSelected.style.display = 'none';
          importButton.disabled = true;
        }

        // Validate file size (5MB)
        if (size > 5 * 1024 * 1024) {
          alert('File is too large. Maximum size is 5MB.');
          this.value = '';
          fileSelected.style.display = 'none';
          importButton.disabled = true;
        }
      }
    });

    // Drag and drop support
    const uploadArea = document.getElementById('upload-area');

    uploadArea.addEventListener('dragover', function(e) {
      e.preventDefault();
      this.style.borderColor = '#0066cc';
      this.style.backgroundColor = '#1a1a2e';
    });

    uploadArea.addEventListener('dragleave', function(e) {
      e.preventDefault();
      this.style.borderColor = '#555';
      this.style.backgroundColor = '#151515';
    });

    uploadArea.addEventListener('drop', function(e) {
      e.preventDefault();
      this.style.borderColor = '#555';
      this.style.backgroundColor = '#151515';

      const files = e.dataTransfer.files;
      if (files.length > 0) {
        fileInput.files = files;
        fileInput.dispatchEvent(new Event('change'));
      }
    });
  </script>
</body>
</html>
