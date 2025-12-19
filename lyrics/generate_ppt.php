<?php
require_once '../db.php';
header('Content-Type: application/json');
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Function to send JSON error response
function sendError($message, $code = 500, $details = null) {
    http_response_code($code);
    $response = [
        'success' => false,
        'message' => $message
    ];
    if ($details !== null) {
        $response['details'] = $details;
    }
    echo json_encode($response);
    exit;
}

// Function to send JSON success response
function sendSuccess($data, $message = '') {
    $response = [
        'success' => true,
        'data' => $data,
        'message' => $message
    ];
    echo json_encode($response);
    exit;
}

try {
    // Check database connection
    if (!$conn) {
        sendError('Database connection failed', 500, mysqli_connect_error());
    }
    
    // Get PPT ID
    $pptId = isset($_POST['ppt_id']) ? intval($_POST['ppt_id']) : 0;
    
    if ($pptId <= 0) {
        sendError('Invalid PPT ID', 400);
    }
    
    // Fetch PPT data from database
    $query = "SELECT * FROM ppt_submissions WHERE id = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, 'i', $pptId);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    
    if (!$result || mysqli_num_rows($result) === 0) {
        sendError('PPT not found', 404);
    }
    
    $ppt = mysqli_fetch_assoc($result);
    mysqli_stmt_close($stmt);
    
    // Check if PPT already has a file
    if (!empty($ppt['ppt_filename'])) {
        // Check if file exists
        $existingFilePath = 'uploads/' . $ppt['ppt_filename'];
        if (file_exists(__DIR__ . '/' . $existingFilePath)) {
            sendSuccess([
                'file_name' => $ppt['ppt_filename'],
                'file_path' => $existingFilePath,
                'message' => 'PPT already exists'
            ], 'PPT already generated');
        }
    }
    
    // Generate a unique filename
    $timestamp = time();
    $cleanTitle = preg_replace('/[^a-zA-Z0-9]/', '_', $ppt['title']);
    $fileName = $cleanTitle . '_' . $timestamp . '.pptx';
    $filePath = 'uploads/' . $fileName;
    $fullPath = __DIR__ . '/' . $filePath;
    
    // Create uploads directory if it doesn't exist
    $uploadsDir = __DIR__ . '/uploads/';
    if (!is_dir($uploadsDir)) {
        if (!mkdir($uploadsDir, 0777, true)) {
            sendError('Failed to create uploads directory', 500);
        }
    }
    
    // Check if uploads directory is writable
    if (!is_writable($uploadsDir)) {
        sendError('Uploads directory is not writable. Please check permissions.', 500);
    }
    
    // Create a simple ZIP-based PPTX file
    $result = createSimplePPTX($fullPath, $ppt);
    
    if (!$result['success']) {
        sendError($result['message'], 500, $result['details'] ?? null);
    }
    
    // Check if file was created
    if (!file_exists($fullPath)) {
        sendError('PowerPoint file creation failed. File not found.', 500);
    }
    
    if (filesize($fullPath) === 0) {
        unlink($fullPath); // Delete empty file
        sendError('PowerPoint file creation failed. File is empty.', 500);
    }
    
    // Update database with file name and status
    $updateQuery = "UPDATE ppt_submissions SET 
                    ppt_filename = ?, 
                    status = 'generated', 
                    timestamp = NOW() 
                    WHERE id = ?";
    
    $updateStmt = mysqli_prepare($conn, $updateQuery);
    mysqli_stmt_bind_param($updateStmt, 'si', $fileName, $pptId);
    
    if (!mysqli_stmt_execute($updateStmt)) {
        // Try to delete the created file if DB update fails
        if (file_exists($fullPath)) {
            unlink($fullPath);
        }
        sendError('Failed to update database', 500, mysqli_error($conn));
    }
    
    mysqli_stmt_close($updateStmt);
    
    // Return success response
    sendSuccess([
        'file_name' => $fileName,
        'file_path' => $filePath,
        'full_path' => $fullPath,
        'download_url' => $filePath,
        'file_size' => filesize($fullPath)
    ], 'PowerPoint generated successfully');
    
} catch (Exception $e) {
    sendError('An unexpected error occurred', 500, [
        'exception' => $e->getMessage(),
        'trace' => $e->getTraceAsString()
    ]);
} finally {
    if (isset($conn) && $conn) {
        mysqli_close($conn);
    }
}

function createSimplePPTX($filePath, $pptData) {
    try {
        // Create a minimal PPTX structure using ZIP
        $zip = new ZipArchive();
        
        if ($zip->open($filePath, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== TRUE) {
            return [
                'success' => false,
                'message' => 'Cannot create PPTX file'
            ];
        }
        
        // Add required folders
        $zip->addEmptyDir('_rels/');
        $zip->addEmptyDir('ppt/');
        $zip->addEmptyDir('ppt/_rels/');
        $zip->addEmptyDir('ppt/slides/');
        $zip->addEmptyDir('ppt/slides/_rels/');
        $zip->addEmptyDir('docProps/');
        
        // Add [Content_Types].xml
        $contentTypes = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<Types xmlns="http://schemas.openxmlformats.org/package/2006/content-types">
    <Default Extension="rels" ContentType="application/vnd.openxmlformats-package.relationships+xml"/>
    <Default Extension="xml" ContentType="application/xml"/>
    <Override PartName="/ppt/presentation.xml" ContentType="application/vnd.openxmlformats-officedocument.presentationml.presentation.main+xml"/>
</Types>';
        
        $zip->addFromString('[Content_Types].xml', $contentTypes);
        
        // Add .rels file
        $rels = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships">
    <Relationship Id="rId1" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/officeDocument" Target="ppt/presentation.xml"/>
</Relationships>';
        $zip->addFromString('_rels/.rels', $rels);
        
        // Parse lyrics (assuming lyrics are stored in the database)
        // Note: Your database might not have a 'lyrics' column based on your columns list
        // If you have lyrics in another table or column, adjust this accordingly
        
        // For now, let's create a simple presentation with title and singer
        $title = htmlspecialchars($pptData['title'] ?? 'Presentation Title', ENT_XML1, 'UTF-8');
        $singer = htmlspecialchars($pptData['singer'] ?? '', ENT_XML1, 'UTF-8');
        
        // Create a very simple presentation with one slide
        $presentationXml = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<p:presentation xmlns:a="http://schemas.openxmlformats.org/drawingml/2006/main"
    xmlns:r="http://schemas.openxmlformats.org/officeDocument/2006/relationships"
    xmlns:p="http://schemas.openxmlformats.org/presentationml/2006/main">
    <p:sldMasterIdLst>
        <p:sldMasterId id="2147483648" r:id="rId1"/>
    </p:sldMasterIdLst>
    <p:sldIdLst>
        <p:sldId id="256" r:id="rId2"/>
    </p:sldIdLst>
    <p:sldSz cx="9144000" cy="6858000"/>
    <p:notesSz cx="6858000" cy="9144000"/>
</p:presentation>';
        
        $zip->addFromString('ppt/presentation.xml', $presentationXml);
        
        // Create a simple slide
        $slideXml = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<p:sld xmlns:a="http://schemas.openxmlformats.org/drawingml/2006/main"
    xmlns:r="http://schemas.openxmlformats.org/officeDocument/2006/relationships"
    xmlns:p="http://schemas.openxmlformats.org/presentationml/2006/main">
    <p:cSld>
        <p:spTree>
            <p:nvGrpSpPr>
                <p:cNvPr id="1" name=""/>
                <p:cNvGrpSpPr/>
                <p:nvPr/>
            </p:nvGrpSpPr>
            <p:grpSpPr>
                <a:xfrm>
                    <a:off x="0" y="0"/>
                    <a:ext cx="9144000" cy="6858000"/>
                </a:xfrm>
            </p:grpSpPr>
            <p:sp>
                <p:nvSpPr>
                    <p:cNvPr id="2" name="Title"/>
                    <p:cNvSpPr>
                        <a:spLocks noGrp="1"/>
                    </p:cNvSpPr>
                    <p:nvPr>
                        <p:ph type="title"/>
                    </p:nvPr>
                </p:nvSpPr>
                <p:spPr/>
                <p:txBody>
                    <a:bodyPr/>
                    <a:lstStyle/>
                    <a:p>
                        <a:r>
                            <a:rPr lang="en-US" sz="7200" b="1"/>
                            <a:t>' . $title . '</a:t>
                        </a:r>
                    </a:p>';
        
        if ($singer) {
            $slideXml .= '
                    <a:p>
                        <a:r>
                            <a:rPr lang="en-US" sz="3600"/>
                            <a:t>' . $singer . '</a:t>
                        </a:r>
                    </a:p>';
        }
        
        $slideXml .= '
                </p:txBody>
            </p:sp>
        </p:spTree>
    </p:cSld>
</p:sld>';
        
        $zip->addFromString('ppt/slides/slide1.xml', $slideXml);
        
        // Add presentation relationships
        $presentationRels = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships">
    <Relationship Id="rId1" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/slideMaster" Target="slideMasters/slideMaster1.xml"/>
    <Relationship Id="rId2" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/slide" Target="slides/slide1.xml"/>
</Relationships>';
        $zip->addFromString('ppt/_rels/presentation.xml.rels', $presentationRels);
        
        // Add a minimal slide master
        $slideMasterXml = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<p:sldMaster xmlns:a="http://schemas.openxmlformats.org/drawingml/2006/main"
    xmlns:r="http://schemas.openxmlformats.org/officeDocument/2006/relationships"
    xmlns:p="http://schemas.openxmlformats.org/presentationml/2006/main">
    <p:cSld>
        <p:spTree>
            <p:nvGrpSpPr>
                <p:cNvPr id="1" name=""/>
                <p:cNvGrpSpPr/>
                <p:nvPr/>
            </p:nvGrpSpPr>
            <p:grpSpPr>
                <a:xfrm>
                    <a:off x="0" y="0"/>
                    <a:ext cx="9144000" cy="6858000"/>
                </a:xfrm>
            </p:grpSpPr>
        </p:spTree>
    </p:cSld>
    <p:clrMap bg1="lt1" tx1="dk1" bg2="lt2" tx2="dk2" accent1="accent1" accent2="accent2" accent3="accent3" accent4="accent4" accent5="accent5" accent6="accent6" hlink="hlink" folHlink="folHlink"/>
</p:sldMaster>';
        
        $zip->addFromString('ppt/slideMasters/slideMaster1.xml', $slideMasterXml);
        
        // Add slide master relationships
        $slideMasterRels = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships">
</Relationships>';
        $zip->addFromString('ppt/slideMasters/_rels/slideMaster1.xml.rels', $slideMasterRels);
        
        // Add slide relationships
        $slideRels = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships">
</Relationships>';
        $zip->addFromString('ppt/slides/_rels/slide1.xml.rels', $slideRels);
        
        // Add document properties
        $coreProps = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<cp:coreProperties xmlns:cp="http://schemas.openxmlformats.org/package/2006/metadata/core-properties"
    xmlns:dc="http://purl.org/dc/elements/1.1/"
    xmlns:dcterms="http://purl.org/dc/terms/"
    xmlns:dcmitype="http://purl.org/dc/dcmitype/"
    xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance">
    <dc:title>' . $title . '</dc:title>
    <dc:creator>CBBC PowerPoint Generator</dc:creator>
    <cp:lastModifiedBy>CBBC System</cp:lastModifiedBy>
    <dcterms:created xsi:type="dcterms:W3CDTF">' . date('Y-m-d\TH:i:s\Z') . '</dcterms:created>
    <dcterms:modified xsi:type="dcterms:W3CDTF">' . date('Y-m-d\TH:i:s\Z') . '</dcterms:modified>
</cp:coreProperties>';
        
        $zip->addFromString('docProps/core.xml', $coreProps);
        
        // Close the ZIP file
        if (!$zip->close()) {
            return [
                'success' => false,
                'message' => 'Failed to close ZIP file'
            ];
        }
        
        return [
            'success' => true,
            'message' => 'PPTX created successfully'
        ];
        
    } catch (Exception $e) {
        return [
            'success' => false,
            'message' => 'Exception in PPTX creation',
            'details' => $e->getMessage()
        ];
    }
}
?>