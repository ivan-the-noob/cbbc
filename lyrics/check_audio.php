<?php
require_once '../db.php';

header('Content-Type: application/json');

if (isset($_GET['ppt_id'])) {
    $pptId = intval($_GET['ppt_id']);
    
    $stmt = $conn->prepare("SELECT youtube, audio_filename, audio_path FROM ppt_submissions WHERE id = ?");
    $stmt->bind_param("i", $pptId);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $ppt = $result->fetch_assoc();
        
        $hasYouTube = !empty($ppt['youtube']);
        $hasAudio = !empty($ppt['audio_filename']) && file_exists($ppt['audio_path']);
        
        echo json_encode([
            'success' => true,
            'has_youtube' => $hasYouTube,
            'audio_available' => $hasAudio,
            'audio_filename' => $ppt['audio_filename'],
            'audio_path' => $ppt['audio_path']
        ]);
    } else {
        echo json_encode(['success' => false, 'message' => 'PPT not found']);
    }
    
    $stmt->close();
} else {
    echo json_encode(['success' => false, 'message' => 'No PPT ID provided']);
}
?>