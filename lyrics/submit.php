<?php
require __DIR__ . '/../vendor/autoload.php';
require_once '../db.php';

use PhpOffice\PhpPresentation\PhpPresentation;
use PhpOffice\PhpPresentation\IOFactory;
use PhpOffice\PhpPresentation\Style\Alignment;
use PhpOffice\PhpPresentation\Style\Color;

// Set header for JSON response
header('Content-Type: application/json');

// Validate request
if (!isset($_POST['lyrics'], $_POST['title'])) {
    echo json_encode([
        'success' => false,
        'message' => 'Invalid request: Missing required fields'
    ]);
    exit;
}

$title = trim($_POST['title']);
$singer = trim($_POST['singer'] ?? '');
$lyrics = trim($_POST['lyrics']);
$youtube = trim($_POST['youtube'] ?? '');
$lines = array_filter(array_map('trim', explode("\n", $lyrics)));

try {
    // Create presentation
    $ppt = new PhpPresentation();
    $ppt->removeSlideByIndex(0); // Remove default slide

    // 16:9 size (EMU conversion)
    $ppt->getLayout()->setCX(1280 * 9525);
    $ppt->getLayout()->setCY(720 * 9525);

    // Determine background source
    $bgPath = null;
    $textColor = new Color('FF333333'); // Default dark gray text color

    // Check for uploaded background
    if (!empty($_FILES['background']['tmp_name']) && is_uploaded_file($_FILES['background']['tmp_name'])) {
        // Move uploaded file to uploads directory
        $uploadDir = 'uploads/';
        if (!file_exists($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }
        
        $bgFileName = uniqid() . '_' . basename($_FILES['background']['name']);
        $bgPath = $uploadDir . $bgFileName;
        
        if (move_uploaded_file($_FILES['background']['tmp_name'], $bgPath)) {
            if (isset($_POST['selected_text_color']) && !empty($_POST['selected_text_color'])) {
                $textColor = new Color($_POST['selected_text_color']);
            } else {
                $textColor = new Color('FFFFFFFF');
            }
        }
    } 
    // Check for default background selection
    elseif (isset($_POST['selected_default_bg']) && !empty($_POST['selected_default_bg'])) {
        $bgName = $_POST['selected_default_bg'];
        
        if (file_exists($bgName)) {
            $bgPath = $bgName;
            
            if (isset($_POST['selected_text_color']) && !empty($_POST['selected_text_color'])) {
                $textColor = new Color($_POST['selected_text_color']);
            } else {
                $textColor = new Color('FFFFFFFF');
            }
        }
    }

    // --- 1. Create Title Slide ---
    $titleSlide = $ppt->createSlide();

    if ($bgPath && file_exists($bgPath)) {
        try {
            $bg = $titleSlide->createDrawingShape();
            $bg->setPath($bgPath)
               ->setWidth(1280)
               ->setHeight(720)
               ->setOffsetX(0)
               ->setOffsetY(0);
        } catch (Exception $e) {
            error_log("Background image error: " . $e->getMessage());
        }
    }

    // Title text box
    $boxWidth  = 1100;
    $boxHeight = 300;
    $shape = $titleSlide->createRichTextShape()
        ->setWidth($boxWidth)
        ->setHeight($boxHeight)
        ->setOffsetX((1280 - $boxWidth) / 2)
        ->setOffsetY((720 - $boxHeight) / 2);

    $shape->getParagraphs()[0]->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

    $run = $shape->createTextRun($title);
    $run->getFont()->setName('Libre Baskerville')->setSize(70)->setColor($textColor);

    // Singer name (optional)
    if ($singer) {
        $singerShape = $titleSlide->createRichTextShape()
            ->setWidth($boxWidth)
            ->setHeight(100)
            ->setOffsetX((1280 - $boxWidth) / 2)
            ->setOffsetY((720 - $boxHeight) / 2 + 220);

        $singerShape->getParagraphs()[0]->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $singerRun = $singerShape->createTextRun($singer);
        $singerRun->getFont()->setName('Libre Baskerville')->setSize(25)->setColor($textColor);
    }

    // --- 2. Generate Lyric Slides ---
    foreach ($lines as $i => $text) {
        $slide = $ppt->createSlide();

        if ($bgPath && file_exists($bgPath)) {
            try {
                $bg = $slide->createDrawingShape();
                $bg->setPath($bgPath)
                   ->setWidth(1280)
                   ->setHeight(720)
                   ->setOffsetX(0)
                   ->setOffsetY(0);
            } catch (Exception $e) {
                error_log("Background image error for slide: " . $e->getMessage());
            }
        }

        $shape = $slide->createRichTextShape()
            ->setWidth($boxWidth)
            ->setHeight($boxHeight)
            ->setOffsetX((1280 - $boxWidth) / 2)
            ->setOffsetY((720 - $boxHeight) / 2);

        $shape->getParagraphs()[0]->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        $run = $shape->createTextRun($text);
        $run->getFont()->setName('Libre Baskerville')->setSize(70)->setColor($textColor);

        // Slide number
        $num = $slide->createRichTextShape()
            ->setWidth(200)
            ->setHeight(40)
            ->setOffsetX(1040)
            ->setOffsetY(20);

        $num->getParagraphs()[0]->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
    }

    // Generate unique filename
    $timestamp = date('Ymd_His');
    $cleanTitle = preg_replace('/[^a-zA-Z0-9_-]/', '_', $title);
    $filename = $timestamp . '_' . $cleanTitle . '.pptx';
    $savePath = 'uploads/' . $filename;

    // Ensure uploads directory exists
    if (!file_exists('uploads/')) {
        mkdir('uploads/', 0777, true);
    }

    // Save the presentation to file
    $writer = IOFactory::createWriter($ppt, 'PowerPoint2007');
    $writer->save($savePath);
    
    // Save to database
    $stmt = $conn->prepare("
    INSERT INTO ppt_submissions (title, singer, youtube, ppt_filename, saved_path, status) 
    VALUES (?, ?, ?, ?, ?, 'generated')
    ");
    $stmt->bind_param("sssss", $title, $singer, $youtube, $filename, $savePath);
    
    if ($stmt->execute()) {
        $submissionId = $conn->insert_id;
        
        echo json_encode([
            'success' => true,
            'message' => 'PowerPoint submitted successfully!',
            'submission_id' => $submissionId,
            'title' => $title,
            'filename' => $filename,
            'download_url' => $savePath,
            'file_size' => filesize($savePath)
        ]);
    } else {
        throw new Exception("Failed to save to database: " . $conn->error);
    }
    
    $stmt->close();
    
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Error: ' . $e->getMessage()
    ]);
}

$conn->close();
exit;