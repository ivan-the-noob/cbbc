<?php
require_once '../db.php';

header('Content-Type: application/json');

// Function to force download
function forceDownload($filePath, $fileName) {
    if (file_exists($filePath)) {
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="' . $fileName . '"');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize($filePath));
        readfile($filePath);
        exit;
    }
    return false;
}

// Function to download YouTube audio on demand
function downloadYouTubeAudioOnDemand($url, $pptId) {
    global $conn;
    
    // Create mp3 directory
    $mp3Dir = 'mp3/';
    if (!file_exists($mp3Dir)) {
        mkdir($mp3Dir, 0777, true);
    }
    
    // Generate filename based on PPT title
    $stmt = $conn->prepare("SELECT title FROM ppt_submissions WHERE id = ?");
    $stmt->bind_param("i", $pptId);
    $stmt->execute();
    $result = $stmt->get_result();
    $ppt = $result->fetch_assoc();
    $stmt->close();
    
    $cleanTitle = preg_replace('/[^a-zA-Z0-9_-]/', '_', $ppt['title'] ?? 'audio');
    $filename = $cleanTitle . '_' . date('Ymd_His') . '.mp3';
    $filepath = $mp3Dir . $filename;
    
    // Update status to downloading
    $updateStmt = $conn->prepare("UPDATE ppt_submissions SET audio_status = 'downloading' WHERE id = ?");
    $updateStmt->bind_param("i", $pptId);
    $updateStmt->execute();
    $updateStmt->close();
    
    try {
        // Try multiple download methods
        $success = false;
        
        // Method 1: Python yt-dlp
        $command = sprintf(
            'python -m yt_dlp --extract-audio --audio-format mp3 --audio-quality 0 --output "%s" "%s" 2>&1',
            escapeshellarg($filepath),
            escapeshellarg($url)
        );
        
        exec($command, $output, $returnCode);
        
        if ($returnCode === 0 && file_exists($filepath) && filesize($filepath) > 0) {
            $success = true;
        }
        
        // Method 2: yt-dlp executable
        if (!$success && file_exists('C:/Python310/Scripts/yt-dlp.exe')) {
            $command = sprintf(
                'C:\\Python310\\Scripts\\yt-dlp.exe --extract-audio --audio-format mp3 --audio-quality 0 --output "%s" "%s" 2>&1',
                escapeshellarg($filepath),
                escapeshellarg($url)
            );
            
            exec($command, $output, $returnCode);
            
            if ($returnCode === 0 && file_exists($filepath) && filesize($filepath) > 0) {
                $success = true;
            }
        }
        
        // Method 3: Direct yt-dlp
        if (!$success) {
            $command = sprintf(
                'yt-dlp --extract-audio --audio-format mp3 --audio-quality 0 --output "%s" "%s" 2>&1',
                escapeshellarg($filepath),
                escapeshellarg($url)
            );
            
            exec($command, $output, $returnCode);
            
            if ($returnCode === 0 && file_exists($filepath) && filesize($filepath) > 0) {
                $success = true;
            }
        }
        
        if ($success) {
            // Update database with audio info
            $updateStmt = $conn->prepare("UPDATE ppt_submissions SET audio_filename = ?, audio_path = ?, audio_status = 'completed', downloaded_at = NOW() WHERE id = ?");
            $updateStmt->bind_param("ssi", $filename, $filepath, $pptId);
            $updateStmt->execute();
            $updateStmt->close();
            
            return [
                'success' => true,
                'filename' => $filename,
                'filepath' => $filepath,
                'filesize' => filesize($filepath)
            ];
        } else {
            // Update status to failed
            $updateStmt = $conn->prepare("UPDATE ppt_submissions SET audio_status = 'failed' WHERE id = ?");
            $updateStmt->bind_param("i", $pptId);
            $updateStmt->execute();
            $updateStmt->close();
            
            return [
                'success' => false,
                'message' => 'Failed to download audio',
                'error' => implode("\n", $output)
            ];
        }
        
    } catch (Exception $e) {
        return [
            'success' => false,
            'message' => 'Error: ' . $e->getMessage()
        ];
    }
}

// Handle download request
if (isset($_GET['id'])) {
    $pptId = intval($_GET['id']);
    
    // Get PPT info
    $stmt = $conn->prepare("SELECT * FROM ppt_submissions WHERE id = ?");
    $stmt->bind_param("i", $pptId);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 0) {
        echo json_encode(['success' => false, 'message' => 'PPT not found']);
        exit;
    }
    
    $ppt = $result->fetch_assoc();
    $stmt->close();
    
    // Check if PPT file exists
    $pptFilePath = $ppt['saved_path'] ?? ('uploads/' . $ppt['ppt_filename']);
    $pptFileName = $ppt['ppt_filename'] ?? ($ppt['title'] . '.pptx');
    
    if (!file_exists($pptFilePath)) {
        echo json_encode(['success' => false, 'message' => 'PPT file not found']);
        exit;
    }
    
    // Prepare response
    $response = [
        'success' => true,
        'ppt' => [
            'title' => $ppt['title'],
            'filename' => $pptFileName,
            'filepath' => $pptFilePath,
            'filesize' => filesize($pptFilePath)
        ],
        'audio' => null
    ];
    
    // Check if YouTube audio needs to be downloaded
    if (!empty($ppt['youtube'])) {
        if (!empty($ppt['audio_path']) && file_exists($ppt['audio_path'])) {
            // Audio already exists
            $response['audio'] = [
                'filename' => $ppt['audio_filename'],
                'filepath' => $ppt['audio_path'],
                'filesize' => filesize($ppt['audio_path']),
                'status' => 'existing'
            ];
        } else {
            // Try to download audio
            $audioResult = downloadYouTubeAudioOnDemand($ppt['youtube'], $pptId);
            $response['audio'] = $audioResult;
        }
    }
    
    echo json_encode($response);
    exit;
}

// Handle direct file download
if (isset($_GET['download_ppt'])) {
    $pptId = intval($_GET['download_ppt']);
    
    $stmt = $conn->prepare("SELECT ppt_filename, saved_path, title FROM ppt_submissions WHERE id = ?");
    $stmt->bind_param("i", $pptId);
    $stmt->execute();
    $result = $stmt->get_result();
    $ppt = $result->fetch_assoc();
    $stmt->close();
    
    $filePath = $ppt['saved_path'] ?? ('uploads/' . $ppt['ppt_filename']);
    $fileName = $ppt['ppt_filename'] ?? ($ppt['title'] . '.pptx');
    
    if (file_exists($filePath)) {
        forceDownload($filePath, $fileName);
    } else {
        echo "PPT file not found";
    }
    exit;
}

// Handle audio download
if (isset($_GET['download_audio'])) {
    $pptId = intval($_GET['download_audio']);
    
    $stmt = $conn->prepare("SELECT audio_filename, audio_path FROM ppt_submissions WHERE id = ?");
    $stmt->bind_param("i", $pptId);
    $stmt->execute();
    $result = $stmt->get_result();
    $ppt = $result->fetch_assoc();
    $stmt->close();
    
    if (!empty($ppt['audio_path']) && file_exists($ppt['audio_path'])) {
        forceDownload($ppt['audio_path'], $ppt['audio_filename']);
    } else {
        echo "Audio file not found";
    }
    exit;
}

echo json_encode(['success' => false, 'message' => 'Invalid request']);
?>