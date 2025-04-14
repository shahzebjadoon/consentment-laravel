<?php
// Updated CSV to SQL Converter for dps_library table
// Handles cookie_id and performs INSERT ON DUPLICATE KEY UPDATE
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('memory_limit', '512M');
ini_set('max_execution_time', 300);

// Define valid categories
$validCategories = ['analytics', 'marketing', 'functional', 'essential', 'social_media', 'other'];

// Function to map category
function mapCategory($category) {
    global $validCategories;
    $category = strtolower(trim($category));
    
    if (in_array($category, $validCategories)) {
        return $category;
    }
    
    // Basic mapping
    if (strpos($category, 'analytic') !== false) return 'analytics';
    if (strpos($category, 'market') !== false) return 'marketing';
    if (strpos($category, 'ad') !== false) return 'marketing';
    if (strpos($category, 'function') !== false) return 'functional';
    if (strpos($category, 'essential') !== false) return 'essential';
    if (strpos($category, 'necessary') !== false) return 'essential';
    if (strpos($category, 'social') !== false) return 'social_media';
    
    return 'other';
}

// Process form submission
$message = '';
$sqlOutput = '';
$downloadReady = false;

// First, output the SQL to add the cookie_id column if it doesn't exist
$sqlOutput = "-- SQL to add cookie_id column if it doesn't exist\n";
$sqlOutput .= "ALTER TABLE `dps_library` \n";
$sqlOutput .= "DROP INDEX IF EXISTS `domain_pattern`,\n";
$sqlOutput .= "ADD COLUMN IF NOT EXISTS `cookie_id` VARCHAR(255) NULL AFTER `id`,\n";
$sqlOutput .= "ADD INDEX IF NOT EXISTS `idx_cookie_id` (`cookie_id`);\n\n";

$sqlOutput .= "-- Generated SQL statements for importing/updating data\n";
$sqlOutput .= "-- Generated on: " . date('Y-m-d H:i:s') . "\n\n";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['csvfile'])) {
    try {
        $file = $_FILES['csvfile'];
        
        if ($file['error'] !== UPLOAD_ERR_OK) {
            $message = "Upload error: " . $file['error'];
        } else {
            $handle = fopen($file['tmp_name'], 'r');
            
            if ($handle !== false) {
                // Read header row
                $header = fgetcsv($handle, 0, ',');
                
                $rowCount = 0;
                
                while (($data = fgetcsv($handle, 0, ',')) !== false) {
                    $rowCount++;
                    
                    // Skip incomplete rows
                    if (count($data) < 9) continue;
                    
                    // Escape data for SQL
                    $cookie_id = addslashes(trim($data[0])); // ID from CSV
                    $name = addslashes(trim($data[1])); // Platform
                    $domain = trim($data[4]); // Domain
                    
                    // Generate domain if empty
                    if (empty($domain)) {
                        $domain = strtolower(preg_replace('/[^a-zA-Z0-9]/', '', $name)) . '.com';
                    }
                    $domain = addslashes($domain);
                    
                    $category = mapCategory($data[2]); // Category
                    $description = addslashes(trim($data[5])); // Description
                    $provider = addslashes(trim($data[7])); // Data Controller
                    $privacy_url = addslashes(trim($data[8])); // Privacy URL
                    $cookie = addslashes(trim($data[3])); // Cookie name
                    $retention = addslashes(trim($data[6])); // Retention
                    $official = (int)(trim($data[9]) == '1' || strtolower(trim($data[9])) == 'true');
                    
                    // Generate SQL with INSERT ON DUPLICATE KEY UPDATE
                    $sqlOutput .= "INSERT INTO `dps_library` 
                        (`cookie_id`, `name`, `domain_pattern`, `category`, `description`, `provider_name`, 
                        `privacy_policy_url`, `cookie_patterns`, `data_retention`, `data_sharing`, 
                        `is_official`, `updated_at`) VALUES 
                        ('$cookie_id', '$name', '$domain', '$category', '$description', '$provider', 
                        '$privacy_url', '$cookie', '$retention', 0, 
                        $official, NOW())\n";
                    
                    $sqlOutput .= "ON DUPLICATE KEY UPDATE 
                        `name` = '$name', 
                        `domain_pattern` = '$domain', 
                        `category` = '$category', 
                        `description` = '$description', 
                        `provider_name` = '$provider', 
                        `privacy_policy_url` = '$privacy_url', 
                        `cookie_patterns` = '$cookie', 
                        `data_retention` = '$retention', 
                        `data_sharing` = 0, 
                        `is_official` = $official, 
                        `updated_at` = NOW();\n\n";
                }
                
                fclose($handle);
                $message = "Processed $rowCount rows successfully.";
                $downloadReady = true;
            } else {
                $message = "Failed to open uploaded file.";
            }
        }
    } catch (Exception $e) {
        $message = "Error: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CSV to SQL Converter</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .container { max-width: 800px; margin: 0 auto; padding: 20px; background: #f5f5f5; border-radius: 5px; }
        .message { padding: 10px; margin-bottom: 15px; border-radius: 4px; }
        .success { background-color: #d4edda; color: #155724; }
        .error { background-color: #f8d7da; color: #721c24; }
        pre { background: #fff; padding: 10px; border: 1px solid #ddd; overflow: auto; max-height: 300px; }
        button { background: #4CAF50; color: white; border: none; padding: 10px 15px; cursor: pointer; margin-top: 10px; }
        .notes { background: #e7f3fe; padding: 10px; border-left: 4px solid #2196F3; margin: 15px 0; }
    </style>
</head>
<body>
    <div class="container">
        <h1>CSV to SQL Converter</h1>
        
        <div class="notes">
            <h3>Important Notes:</h3>
            <p>This script will:</p>
            <ol>
                <li>Add a <code>cookie_id</code> column to your table if it doesn't exist</li>
                <li>Use the ID from your CSV as the <code>cookie_id</code></li>
                <li>Update existing records if the same <code>cookie_id</code> already exists</li>
                <li>Create new records if the <code>cookie_id</code> doesn't exist</li>
            </ol>
            <p>Your CSV should have the following columns:</p>
            <ol>
                <li>ID (will be stored as cookie_id)</li>
                <li>Platform (mapped to name)</li>
                <li>Category</li>
                <li>Cookie/Data Key name</li>
                <li>Domain</li>
                <li>Description</li>
                <li>Retention period</li>
                <li>Data Controller</li>
                <li>User Privacy & GDPR Rights Portals</li>
                <li>Wildcard match</li>
            </ol>
        </div>
        
        <?php if (!empty($message)): ?>
            <div class="message <?php echo $downloadReady ? 'success' : 'error'; ?>">
                <?php echo $message; ?>
            </div>
        <?php endif; ?>
        
        <form method="post" enctype="multipart/form-data">
            <p>Select your CSV file with the cookie data:</p>
            <input type="file" name="csvfile" required>
            <button type="submit">Process CSV</button>
        </form>
        
        <?php if ($downloadReady): ?>
            <h3>Generated SQL:</h3>
            <pre><?php echo htmlspecialchars($sqlOutput); ?></pre>
            
            <button onclick="downloadSQL()">Download SQL</button>
            
            <script>
                function downloadSQL() {
                    const text = <?php echo json_encode($sqlOutput); ?>;
                    const blob = new Blob([text], { type: 'text/plain' });
                    const a = document.createElement('a');
                    a.download = 'dps_library_import.sql';
                    a.href = URL.createObjectURL(blob);
                    a.click();
                }
            </script>
        <?php endif; ?>
    </div>
</body>
</html>