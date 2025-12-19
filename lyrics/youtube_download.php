<?php
function downloadYouTubeMP3($url) {
    // Create mp3 directory
    $mp3Dir = 'mp3/';
    if (!file_exists($mp3Dir)) {
        mkdir($mp3Dir, 0777, true);
    }
    
    // Generate filename
    $timestamp = date('Ymd_His');
    $filename = 'audio_' . $timestamp . '.mp3';
    $filepath = $mp3Dir . $filename;
    
    // Path to yt-dlp (assuming Python is installed)
    $pythonPath = 'C:\\Python310\\Scripts\\yt-dlp.exe'; // Adjust if needed
    
    // Build command
    $command = sprintf(
        'yt-dlp --extract-audio --audio-format mp3 --audio-quality 0 --output "%s" "%s"',
        escapeshellarg($filepath),
        escapeshellarg($url)
    );
    
    // Execute command
    exec($command, $output, $returnCode);
    
    if ($returnCode === 0 && file_exists($filepath)) {
        return [
            'success' => true,
            'filename' => $filename,
            'filepath' => $filepath,
            'filesize' => filesize($filepath)
        ];
    } else {
        return [
            'success' => false,
            'message' => 'Failed to download audio',
            'error' => implode("\n", $output)
        ];
    }
}

// Test function
if (isset($_GET['test'])) {
    $result = downloadYouTubeMP3('https://www.youtube.com/watch?v=dQw4w9WgXcQ');
    print_r($result);
}
?>