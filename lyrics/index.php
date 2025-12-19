<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CBBC - FILES</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
    <style>
         @import url('https://fonts.googleapis.com/css2?family=Libre+Baskerville:ital,wght@0,400;0,700;1,400&display=swap');
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            min-height: 100vh;
            padding: 20px;
        }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
            background: white;
            border-radius: 20px;
            padding: 30px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
        }
        
        h1 {
            text-align: center;
            color: #333;
            margin-bottom: 30px;
            font-family: 'Libre Baskerville', serif;
        }
        
        .form-container {
            display: flex;
            gap: 30px;
            flex-wrap: wrap;
        }
        
        .input-section {
            flex: 1;
            min-width: 300px;
        }
        
        .preview-section {
            flex: 1;
            min-width: 300px;
        }
        
        label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #555;
        }
        
        input[type="text"],
        input[type="url"],
        textarea {
            width: 100%;
            padding: 12px;
            border: 2px solid #e0e0e0;
            border-radius: 10px;
            font-size: 16px;
            margin-bottom: 20px;
            transition: border-color 0.3s;
        }
        
        input[type="text"]:focus,
        textarea:focus {
            outline: none;
            border-color: #667eea;
        }
        
        textarea {
            min-height: 300px;
            resize: vertical;
            font-family: monospace;
            line-height: 1.5;
        }
        
       
        
        .btn:hover {
            transform: translateY(-2px);
        }
        
        .btn-generate {
            background: linear-gradient(135deg, #4CAF50 0%, #2E7D32 100%);
        }
        
        .slide-preview {
            background: #f5f5f5;
            border-radius: 15px;
            padding: 20px;
            min-height: 400px;
            border: 3px dashed #ddd;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow: hidden;
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
        }
        
        .slide-preview.with-bg .slide-content {
            text-shadow: 2px 2px 4px rgba(0,0,0,0.7);
            color: black;
            
            padding: 20px;
            border-radius: 10px;
        }
        
        .slide-content {
            font-family: 'Libre Baskerville', serif;
            font-size: 50px;
            text-align: center;
            color: #333;
            line-height: 1.4;
            max-width: 90%;
            word-wrap: break-word;
            transition: all 0.3s;
        }
        
        .slide-number {
            position: absolute;
            top: 10px;
            right: 10px;
            background: rgba(0,0,0,0.7);
            color: white;
            padding: 5px 10px;
            border-radius: 5px;
            font-size: 12px;
            z-index: 2;
        }
        
        .slides-container {
            display: flex;
            gap: 15px;
            overflow-x: auto;
            padding: 20px 0;
            margin-top: 20px;
        }
        
        .mini-slide {
            min-width: 200px;
            height: 150px;
            background: #f5f5f5;
            border-radius: 10px;
            padding: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            border: 2px solid transparent;
            transition: all 0.3s;
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            position: relative;
        }
        
        .mini-slide.with-bg::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0,0,0,0.3);
            border-radius: 10px;
        }
        
        .mini-slide:hover {
            border-color: #667eea;
            transform: scale(1.05);
        }
        
        .mini-slide.active {
            border-color: #4CAF50;
            box-shadow: 0 0 10px rgba(76, 175, 80, 0.5);
        }
        
        .mini-content {
            font-family: 'Libre Baskerville', serif;
            font-size: 14px;
            text-align: center;
            max-width: 90%;
            position: relative;
            z-index: 1;
            color: #333;
            font-weight: bold;
        }
        
        .mini-slide.with-bg .mini-content {
            color: white;
            text-shadow: 1px 1px 3px rgba(0,0,0,0.8);
        }
        
        .generated-slides {
            display: none;
            margin-top: 30px;
            background: #f9f9f9;
            border-radius: 15px;
            padding: 20px;
        }
        
        .generated-slide {
            background: white;
            border-radius: 10px;
            padding: 40px;
            margin-bottom: 20px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            text-align: center;
            min-height: 500px;
            display: flex;
            align-items: center;
            justify-content: center;
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            position: relative;
        }
        
        .generated-slide.with-bg::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0,0,0,0.2);
            border-radius: 10px;
        }
        
        .generated-content {
            font-family: 'Libre Baskerville', serif;
            font-size: 120px;
            line-height: 1.3;
            color: #333;
            position: relative;
            z-index: 1;
            max-width: 90%;
            word-wrap: break-word;
        }
        
        .generated-slide.with-bg .generated-content {
            color: white;
            text-shadow: 2px 2px 6px rgba(0,0,0,0.9);
        }
        
        .controls {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
            margin-top: 20px;
        }
        
        .file-input-wrapper {
            position: relative;
            overflow: hidden;
            display: inline-block;
        }
        
        .file-input-wrapper input[type=file] {
            position: absolute;
            left: 0;
            top: 0;
            opacity: 0;
            cursor: pointer;
            width: 100%;
            height: 100%;
        }
        
        .selected-file {
            margin-top: 10px;
            font-size: 14px;
            color: #666;
        }
        
        .clear-bg-btn {
            background: #ff4444;
            color: white;
            border: none;
            padding: 8px 15px;
            border-radius: 5px;
            font-size: 12px;
            cursor: pointer;
            margin-left: 10px;
        }
        
        .clear-bg-btn:hover {
            background: #cc0000;
        }

         .notification {
        position: fixed;
        bottom: 20px;
        right: 20px;
        padding: 15px 20px;
        border-radius: 8px;
        color: white;
        font-weight: 600;
        z-index: 1000;
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        transform: translateY(100px);
        opacity: 0;
        transition: transform 0.3s ease, opacity 0.3s ease;
        max-width: 300px;
    }
    
    .notification.show {
        transform: translateY(0);
        opacity: 1;
    }
    
    .notification.success {
        background: linear-gradient(135deg, #4CAF50 0%, #2E7D32 100%);
    }
    
    .notification.error {
        background: linear-gradient(135deg, #f44336 0%, #c62828 100%);
    }
    
    .notification.info {
        background: linear-gradient(135deg, #2196F3 0%, #0d47a1 100%);
    }
    
    .notification-content {
        display: flex;
        align-items: center;
        gap: 10px;
    }
    
    .notification-icon {
        font-size: 20px;
    }
    
    .notification-close {
        background: none;
        border: none;
        color: white;
        font-size: 20px;
        cursor: pointer;
        margin-left: 15px;
        opacity: 0.8;
        padding: 0;
    }
    
    .notification-close:hover {
        opacity: 1;
    }
    
    /* Loading overlay styles */
    .loading-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(255, 255, 255, 0.8);
        display: none;
        justify-content: center;
        align-items: center;
        z-index: 1001;
    }
    
    .spinner {
        width: 50px;
        height: 50px;
        border: 5px solid #f3f3f3;
        border-top: 5px solid #667eea;
        border-radius: 50%;
        animation: spin 1s linear infinite;
    }
    
    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }
        .modal-xl {
            max-width: 1400px;
        }
        
        .modal-body {
            padding: 0;
        }
        
        .modal-content {
            border-radius: 20px;
            overflow: hidden;
        }
        
        /* New styles for PPT grid */
        .ppt-grid-section {
            margin-top: 50px;
            padding-top: 30px;
            border-top: 2px solid #eee;
        }
        
        .ppt-grid-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }
        
        .ppt-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }
        
        .ppt-card {
            background: white;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
            border: 1px solid #e0e0e0;
            cursor: pointer;
        }

        .ppt-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.15);
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        }
        
        .ppt-card-header {
   background: linear-gradient(135deg, #e74c3c 0%, #a01515ff 100%);
    color: white;
    padding: 20px;
    text-align: center;
    display: flex;
    align-items: center;
    justify-content: center;
    }

    .ppt-card-title {
        font-size: 20px;
        font-weight: 600;
        margin: 0;
        padding: 10px;
        display: -webkit-box;
        -webkit-line-clamp: 3;
        -webkit-box-orient: vertical;
        overflow: hidden;
        text-overflow: ellipsis;
        line-height: 1.4;
    }

    .ppt-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
        gap: 20px;
        margin-top: 20px;
    }
        
        .ppt-card-body {
            padding: 20px;
        }
        
        .ppt-info {
            margin-bottom: 15px;
        }
        
        .ppt-info-item {
            display: flex;
            margin-bottom: 8px;
            font-size: 14px;
        }
        
        .ppt-info-label {
            font-weight: 600;
            color: #666;
            min-width: 80px;
        }
        
        .ppt-info-value {
            color: #333;
            flex: 1;
        }
        
        .ppt-card-footer {
            padding: 15px 20px;
            background: #f9f9f9;
            border-top: 1px solid #eee;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .ppt-date {
            font-size: 12px;
            color: #888;
        }
        
        .btn-download {
            background: linear-gradient(135deg, #4CAF50 0%, #2E7D32 100%);
            color: white;
            border: none;
            padding: 8px 15px;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            text-decoration: none;
            display: inline-block;
        }
        
        .btn-download:hover {
            transform: scale(1.05);
            box-shadow: 0 4px 12px rgba(76, 175, 80, 0.3);
            color: white;
            text-decoration: none;
        }
        
        .no-ppt {
            grid-column: 1 / -1;
            text-align: center;
            padding: 40px;
            color: #666;
        }
        
        .ppt-icon {
            font-size: 24px;
            margin-right: 10px;
        }
        
        .modal-xl {
            max-width: 1400px;
        }
        
        .modal-body {
            padding: 0;
        }
        
        .modal-content {
            border-radius: 20px;
            overflow: hidden;
        }
        
      
        
        
        /* Loading overlay styles */
        .loading-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(255, 255, 255, 0.8);
            display: none;
            justify-content: center;
            align-items: center;
            z-index: 1001;
        }
        
        .spinner {
            width: 50px;
            height: 50px;
            border: 5px solid #f3f3f3;
            border-top: 5px solid #667eea;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }
        
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        
        /* Notification styles */
        .notification {
            position: fixed;
            bottom: 20px;
            right: 20px;
            padding: 15px 20px;
            border-radius: 8px;
            color: white;
            font-weight: 600;
            z-index: 1000;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
            transform: translateY(100px);
            opacity: 0;
            transition: transform 0.3s ease, opacity 0.3s ease;
            max-width: 300px;
        }
        
        .notification.show {
            transform: translateY(0);
            opacity: 1;
        }
        
        .notification.success {
            background: linear-gradient(135deg, #4CAF50 0%, #2E7D32 100%);
        }
        
        .notification.error {
            background: linear-gradient(135deg, #f44336 0%, #c62828 100%);
        }
        
        .notification.info {
            background: linear-gradient(135deg, #2196F3 0%, #0d47a1 100%);
        }
        
        .notification-content {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .notification-icon {
            font-size: 20px;
        }
        
        .notification-close {
            background: none;
            border: none;
            color: white;
            font-size: 20px;
            cursor: pointer;
            margin-left: 15px;
            opacity: 0.8;
            padding: 0;
        }
        
        .notification-close:hover {
            opacity: 1;
        }
        
        /* Search bar styles */
        .search-container {
            position: relative;
            max-width: 300px;
        }
        
        .search-input {
            width: 100%;
            padding: 10px 15px;
            padding-left: 40px;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            font-size: 14px;
            transition: all 0.3s;
        }
        
        .search-input:focus {
            outline: none;
            border-color: #667eea;
        }
        
        .search-icon {
            position: absolute;
            left: 12px;
            top: 50%;
            transform: translateY(-50%);
            color: #999;
        }
    </style>
</head>
<body>
    <div class="loading-overlay" id="loadingOverlay">
        <div class="spinner"></div>
    </div>
    <div id="notificationContainer"></div>
    
    <!-- Floating Add Button -->
  
    
    <!-- Bootstrap Modal -->
    <div class="modal fade" id="pptModal" tabindex="-1" aria-labelledby="pptModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="pptModalLabel">Create New PowerPoint</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Moved the entire container content inside modal -->
                    <div class="container" style="max-width: 100%; border-radius: 0; padding: 20px; box-shadow: none;">
                        <h1>CBBC SPECIAL NUMBER</h1>
                        
                        <div class="form-container">
                            <div class="input-section">
                                <!-- Form for downloading PowerPoint -->
                                <form id="pptForm" action="submit.php" method="POST" enctype="multipart/form-data">
                                    <div>
                                        <label for="title">Presentation Title:</label>
                                        <input type="text" id="title" name="title" placeholder="Enter presentation title" required>
                                    </div>
                                    <div> <label for="singer">Singer:</label> <input type="text" id="singer" name="singer" placeholder="Enter singer name"> </div>
                                    <div>
                                        <label for="youtube">YouTube URL:</label>
                                        <input type="url" id="youtube" name="youtube" placeholder="Enter YouTube video link">
                                    </div>
                                    
                                    <div>
                                        <label for="background">Background Image (Optional):</label>
                                        <div style="display: flex; align-items: center; gap: 10px;">
                                            <div class="file-input-wrapper">
                                                <button type="button" class="btn btn-success">Choose Background Image</button>
                                                <input type="file" id="background" name="background" accept="image/*">
                                            </div>
                                            <button type="button" class="clear-bg-btn" onclick="clearBackground()">Clear Background</button>
                                        </div>
                                        <div class="selected-file" id="fileName">No file chosen</div>
                                        <div id="bgPreviewContainer" style="margin-top: 10px; display: none;">
                                            <img id="bgPreview" src="" alt="Background Preview" style="max-width: 200px; max-height: 150px; border-radius: 8px; border: 2px solid #ddd;">
                                        </div>
                                        
                                        <!-- Default background images -->
                                        <div style="margin-top: 15px;">
                                            <label style="font-size: 14px; color: #666; margin-bottom: 5px;">Saved Backgrounds:</label>
                                            <div class="default-bg-container" style="display: flex; gap: 10px; flex-wrap: wrap;">
                                                <div class="default-bg" data-bg="bg-1.png" style="width: 60px; height: 40px; border-radius: 5px; overflow: hidden; cursor: pointer; border: 2px solid #ddd; transition: all 0.2s;">
                                                    <img src="bg-1.png" alt="Background 1" style="width: 100%; height: 100%; object-fit: cover;">
                                                </div>
                                                <div class="default-bg" data-bg="bg-2.png" style="width: 60px; height: 40px; border-radius: 5px; overflow: hidden; cursor: pointer; border: 2px solid #ddd; transition: all 0.2s;">
                                                    <img src="bg-2.png" alt="Background 2" style="width: 100%; height: 100%; object-fit: cover;">
                                                </div>
                                                <div class="default-bg" data-bg="bg-3.png" style="width: 60px; height: 40px; border-radius: 5px; overflow: hidden; cursor: pointer; border: 2px solid #ddd; transition: all 0.2s;">
                                                    <img src="bg-3.png" alt="Background 3" style="width: 100%; height: 100%; object-fit: cover;">
                                                </div>
                                                <div class="default-bg" data-bg="bg-4.png" style="width: 60px; height: 40px; border-radius: 5px; overflow: hidden; cursor: pointer; border: 2px solid #ddd; transition: all 0.2s;">
                                                    <img src="bg-4.png" alt="Background 4" style="width: 100%; height: 100%; object-fit: cover;">
                                                </div>
                                                <div class="default-bg" data-bg="bg-5.png" style="width: 60px; height: 40px; border-radius: 5px; overflow: hidden; cursor: pointer; border: 2px solid #ddd; transition: all 0.2s;">
                                                    <img src="bg-5.png" alt="Background 5" style="width: 100%; height: 100%; object-fit: cover;">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div>
                                        <label for="lyrics">Enter Lyrics (One line per slide):</label>
                                        <textarea id="lyrics" name="lyrics" placeholder="Enter your lyrics here, one line per slide...
Example:
First line
Second line
Third line" required></textarea>
                                    </div>
                                    
                                    <!-- Hidden fields -->
                                    <input type="hidden" id="selected_default_bg" name="selected_default_bg" value="">
                                    <input type="hidden" id="selected_text_color" name="selected_text_color" value="">
                                    
                                    <div class="controls">
                                        <button type="submit" class="btn btn-success" id="submitBtn">Submit PPT</button>
                                    </div>
                                </form>
                            </div>
                            
                            <div class="preview-section">
                                <label>Slide Preview:</label>
                                <div class="slide-preview" id="slidePreview">
                                    <div class="slide-content" id="previewText">
                                        Type lyrics to see preview...
                                    </div>
                                    <div class="slide-number" id="slideNumber">Slide 1</div>
                                </div>
                                
                                <div class="slides-container" id="slidesContainer">
                                    <!-- Mini slides will be added here -->
                                </div>
                            </div>
                        </div>
                        
                        <div class="generated-slides" id="generatedSlides">
                            <h2>Generated Slides (Editable)</h2>
                            <div id="slidesDisplay">
                                <!-- Generated slides will appear here -->
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Main Container with PPT Grid -->
    <div class="container">
        <h1>CBBC FILES</h1>
        <div class="d-flex justify-content-end">
            <button class="add-ppt-btn btn btn-primary btn-md" data-bs-toggle="modal" data-bs-target="#pptModal">
            + Add New PPT
            </button>
        </div>
        <!-- PPT Grid Section -->
        <div class="ppt-grid-section">
            <div class="ppt-grid-header">
                <h3>PPT FILES</h3>
                <div class="search-container">
                    <input type="text" class="search-input" id="searchPPT" placeholder="Search ppt...">
                </div>
            </div>
            
            <div class="ppt-grid" id="pptGrid">
                <!-- PPT cards will be loaded here via AJAX -->
                <div class="no-ppt">
                    <i class="fas fa-file-powerpoint" style="font-size: 48px; color: #ddd; margin-bottom: 15px;"></i>
                    <h4>No PowerPoints Yet</h4>
                    <p>Click the "Add New PPT" button to create your first presentation!</p>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Function to show notification
        function showNotification(message, type = 'success', duration = 5000) {
            let container = document.getElementById('notificationContainer');
            if (!container) {
                container = document.createElement('div');
                container.id = 'notificationContainer';
                container.style.cssText = 'position: fixed; bottom: 20px; right: 20px; z-index: 1000;';
                document.body.appendChild(container);
            }
            
            const notification = document.createElement('div');
            notification.className = `notification ${type}`;
            notification.innerHTML = `
                <div class="notification-content">
                    <span class="notification-icon">${type === 'success' ? '✓' : '!'}</span>
                    <span>${message}</span>
                    <button class="notification-close" onclick="this.parentElement.parentElement.remove()">×</button>
                </div>
            `;
            
            container.appendChild(notification);
            
            setTimeout(() => {
                notification.classList.add('show');
            }, 10);
            
            if (duration > 0) {
                setTimeout(() => {
                    if (notification.parentNode) {
                        notification.classList.remove('show');
                        setTimeout(() => {
                            if (notification.parentNode) {
                                notification.parentNode.removeChild(notification);
                            }
                        }, 300);
                    }
                }, duration);
            }
            
            return notification;
        }

        // Function to show/hide loading overlay
        function showLoading() {
            let overlay = document.getElementById('loadingOverlay');
            if (overlay) {
                overlay.style.display = 'flex';
            } else {
                overlay = document.createElement('div');
                overlay.id = 'loadingOverlay';
                overlay.className = 'loading-overlay';
                overlay.innerHTML = '<div class="spinner"></div>';
                document.body.appendChild(overlay);
                overlay.style.display = 'flex';
            }
        }

        function hideLoading() {
            const overlay = document.getElementById('loadingOverlay');
            if (overlay) {
                overlay.style.display = 'none';
            }
        }

      function downloadPPT(filePath, fileName) {
    showLoading();
    
    console.log('Downloading:', filePath, 'as', fileName);
    
    // Create a temporary anchor element
    const link = document.createElement('a');
    link.href = filePath;
    
    // Ensure the file has a proper name with .pptx extension
    let finalFileName = fileName;
    if (!finalFileName.toLowerCase().endsWith('.pptx')) {
        finalFileName += '.pptx';
    }
    
    link.download = finalFileName;
    link.target = '_blank';
    
    // Append to body, click, and remove
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
    
    setTimeout(() => {
        hideLoading();
        showNotification('Download started!', 'success', 3000);
    }, 500);
}

// Function to load all PPTs from database
function loadAllPPTs(searchQuery = '') {
    showLoading();
    
    $.ajax({
        url: 'get_ppts.php',
        type: 'GET',
        data: { search: searchQuery },
        dataType: 'json',
        success: function(response) {
            hideLoading();
            
            const pptGrid = document.getElementById('pptGrid');
            
            if (response.success && response.data.length > 0) {
                let html = '';
                
                response.data.forEach(ppt => {
                    console.log('PPT Object:', ppt); // Debug log
                    
                    // Check if file_path exists in the database
                    if (!ppt.file_path || ppt.file_path === '') {
                        // If no file_path exists, we need to generate the file first
                        html += `
                           <div class="ppt-card" onclick="generateAndDownloadPPT(${ppt.id}, '${ppt.title.replace(/'/g, "\\'")}')" style="cursor: pointer;">
                                <div class="ppt-card-header">
                                    <h4 class="ppt-card-title">${ppt.title || 'Untitled Presentation'}</h4>
                                    <small style="font-size: 12px; opacity: 0.8;">Click to generate file</small>
                                </div>
                                <div class="ppt-card-body">
                                    <div class="ppt-youtube-section">
                                        <strong>YouTube:</strong>
                                        ${ppt.youtube ? 
                                            `<a href="${ppt.youtube}" target="_blank" onclick="event.stopPropagation();" style="color: #0066cc; text-decoration: none;">
                                                ${ppt.youtube.length > 40 ? ppt.youtube.substring(0, 40) + '...' : ppt.youtube}dasdas
                                            </a>` : 
                                            '<span style="color: #666; font-style: italic;">No YouTube link</span>'
                                        }
                                    </div>
                                </div>
                            </div>
                        `;
                    } else {
                        // If file_path exists, create download link
                        let fileName = ppt.file_name || ppt.title + '.pptx';
                        if (!fileName.toLowerCase().endsWith('.pptx')) {
                            fileName += '.pptx';
                        }
                        
                        const cleanFileName = fileName.replace(/[^a-zA-Z0-9._-]/g, '_');
                        
                        html += `
                            <div class="ppt-card" onclick="downloadPPT('${ppt.file_path}', '${cleanFileName}')" style="cursor: pointer;">
                                <div class="ppt-card-header" style="height: 10vh; padding: 5px;">
                                    <h4 class="ppt-card-title">${ppt.title || 'Untitled Presentation'}.pptx</h4>
                                 
                                </div>
                                 <div class="ppt-card-body">
        <div class="ppt-youtube-section">
            <strong>YouTube:</strong>
            ${ppt.youtube ? 
                `<a href="${ppt.youtube}" target="_blank" onclick="event.stopPropagation();" style="color: #0066cc; text-decoration: none;">
                    ${ppt.youtube.length > 40 ? ppt.youtube.substring(0, 40) + '...' : ppt.youtube}
                </a>` : 
                '<span style="color: #666; font-style: italic;">No YouTube link</span>'
            }
        </div>
    </div>
                            </div>
                        `;
                    }
                });
                
                pptGrid.innerHTML = html;
            } else {
                pptGrid.innerHTML = `
                    <div class="no-ppt">
                        <i class="fas fa-file-powerpoint" style="font-size: 48px; color: #ddd; margin-bottom: 15px;"></i>
                        <h4>No PowerPoints Found</h4>
                        <p>${searchQuery ? 'No presentations match your search. Try a different keyword!' : 'Click the "Add New PPT" button to create your first presentation!'}</p>
                    </div>
                `;
            }
        },
        error: function(xhr, status, error) {
            hideLoading();
            console.error('Error loading PPTs:', error);
            showNotification('Error loading presentations!', 'error', 5000);
        }
    });
}

function generateAndDownloadPPT(pptId, pptTitle) {
    showLoading();
    showNotification('Generating PowerPoint file...', 'info', 3000);
    
    $.ajax({
        url: 'generate_ppt.php',
        type: 'POST',
        data: {
            ppt_id: pptId
        },
        dataType: 'json',
        success: function(response) {
            hideLoading();
            
            if (response.success) {
                showNotification('PowerPoint generated successfully!', 'success', 3000);
                
                // Now download the file
                setTimeout(() => {
                    let fileName = response.file_name || pptTitle + '.pptx';
                    if (!fileName.toLowerCase().endsWith('.pptx')) {
                        fileName += '.pptx';
                    }
                    
                    const cleanFileName = fileName.replace(/[^a-zA-Z0-9._-]/g, '_');
                    
                    // Trigger download
                    const link = document.createElement('a');
                    link.href = response.file_path;
                    link.download = cleanFileName;
                    link.target = '_blank';
                    
                    document.body.appendChild(link);
                    link.click();
                    document.body.removeChild(link);
                    
                    // Reload the PPT grid
                    setTimeout(() => {
                        loadAllPPTs();
                    }, 1000);
                }, 500);
            } else {
                showNotification(response.message || 'Failed to generate PowerPoint', 'error', 5000);
            }
        },
        error: function(xhr, status, error) {
            hideLoading();
            console.error('Error generating PPT:', error);
            showNotification('Error generating PowerPoint file!', 'error', 5000);
        }
    });
}


        // Search functionality
        let searchTimeout;
        document.getElementById('searchPPT').addEventListener('input', function(e) {
            clearTimeout(searchTimeout);
            const searchQuery = e.target.value.trim();
            
            searchTimeout = setTimeout(() => {
                loadAllPPTs(searchQuery);
            }, 300);
        });

        // Handle form submission for creating new PPT
        $(document).ready(function() {
            // Load PPTs on page load
            loadAllPPTs();
            
            $('#pptForm').on('submit', function(e) {
                e.preventDefault();
                
                const title = $('#title').val().trim();
                const lyrics = $('#lyrics').val().trim();
                
                if (!title) {
                    showNotification('Please enter a presentation title!', 'error', 3000);
                    $('#title').focus();
                    return;
                }
                
                if (!lyrics) {
                    showNotification('Please enter some lyrics!', 'error', 3000);
                    $('#lyrics').focus();
                    return;
                }
                
                const submitBtn = $(this).find('button[type="submit"]');
                const originalText = submitBtn.text();
                submitBtn.prop('disabled', true).text('Submitting...');
                showLoading();
                
                const formData = new FormData(this);
                
                $.ajax({
                    url: 'submit.php',
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    dataType: 'json',
                    success: function(response) {
                        hideLoading();
                        submitBtn.prop('disabled', false).text(originalText);
                        
                        if (response.success) {
                            showNotification('PPT created successfully!', 'success', 5000);
                            
                            // Close modal
                            $('#pptModal').modal('hide');
                            
                            // Reset form
                            $('#pptForm')[0].reset();
                            clearBackground();
                            updatePreview();
                            
                            // Reload PPT grid
                            setTimeout(() => {
                                loadAllPPTs();
                            }, 500);
                            
                        } else {
                            showNotification(response.message || 'Submission failed!', 'error', 5000);
                        }
                    },
                    error: function(xhr, status, error) {
                        hideLoading();
                        submitBtn.prop('disabled', false).text(originalText);
                        
                        let errorMessage = 'Submission failed! ';
                        
                        if (xhr.responseText) {
                            try {
                                const errorData = JSON.parse(xhr.responseText);
                                errorMessage += errorData.message || '';
                            } catch (e) {
                                if (xhr.responseText.includes('error') || xhr.responseText.includes('Error')) {
                                    errorMessage += 'Server error occurred.';
                                } else if (xhr.status === 500) {
                                    errorMessage += 'Internal server error (500).';
                                } else if (xhr.status === 404) {
                                    errorMessage += 'Server not found (404).';
                                }
                            }
                        } else if (xhr.status === 0) {
                            errorMessage += 'Network error or server unreachable.';
                        }
                        
                        showNotification(errorMessage, 'error', 5000);
                        console.error('AJAX Error:', status, error, xhr);
                    }
                });
            });
            
            // Initialize preview when modal is shown
            $('#pptModal').on('shown.bs.modal', function () {
                updatePreview();
                $('#title').focus();
            });
            
            // Reset form when modal is hidden
            $('#pptModal').on('hidden.bs.modal', function () {
                $('#pptForm')[0].reset();
                clearBackground();
                currentSlides = [];
                currentSlideIndex = 0;
                updatePreview();
            });
        });

        // Your existing JavaScript variables and functions remain the same
        let currentSlides = [];
        let currentSlideIndex = 0;
        let currentBackground = null;
        let bgPreviewUrl = null;
        let selectedDefaultBg = null;
        let textColor = '#333';
        let textShadow = 'none';
        
        const defaultBgColors = {
            'bg-1.png': '#000000',
            'bg-2.png': '#F3D6A1', 
            'bg-3.png': '#6F4104',
            'bg-4.png': '#6F4104',
            'bg-5.png': '#1B3C00'
        };
        
        // Function to convert hex color to ARGB format for PHP
        function hexToArgb(hexColor) {
            hexColor = hexColor.replace('#', '');
            if (hexColor.length === 3) {
                hexColor = hexColor.split('').map(char => char + char).join('');
            }
            return 'FF' + hexColor.toUpperCase();
        }
        
        // Function to calculate luminance and determine text color
        function getTextColorForBackground(imageUrl) {
            return new Promise((resolve) => {
                if (!imageUrl) {
                    resolve({ color: '#333', shadow: 'none' });
                    return;
                }
                
                const img = new Image();
                img.crossOrigin = 'Anonymous';
                img.src = imageUrl;
                
                img.onload = function() {
                    const canvas = document.createElement('canvas');
                    const ctx = canvas.getContext('2d');
                    canvas.width = img.width;
                    canvas.height = img.height;
                    ctx.drawImage(img, 0, 0);
                    
                    const samplePoints = [
                        { x: 0.25, y: 0.25 },
                        { x: 0.75, y: 0.25 },
                        { x: 0.25, y: 0.75 },
                        { x: 0.75, y: 0.75 },
                        { x: 0.5, y: 0.5 }
                    ];
                    
                    let totalLuminance = 0;
                    samplePoints.forEach(point => {
                        const pixelX = Math.floor(point.x * canvas.width);
                        const pixelY = Math.floor(point.y * canvas.height);
                        const pixelData = ctx.getImageData(pixelX, pixelY, 1, 1).data;
                        const luminance = (0.299 * pixelData[0] + 0.587 * pixelData[1] + 0.114 * pixelData[2]) / 255;
                        totalLuminance += luminance;
                    });
                    
                    const averageLuminance = totalLuminance / samplePoints.length;
                    
                    if (averageLuminance > 0.6) {
                        resolve({ 
                            color: '#333', 
                            shadow: '2px 2px 4px rgba(255,255,255,0.8)' 
                        });
                    } else if (averageLuminance > 0.3) {
                        resolve({ 
                            color: 'white', 
                            shadow: '2px 2px 6px rgba(0,0,0,0.9)' 
                        });
                    } else {
                        resolve({ 
                            color: 'white', 
                            shadow: '2px 2px 8px rgba(0,0,0,1)' 
                        });
                    }
                };
                
                img.onerror = function() {
                    resolve({ color: 'white', shadow: '2px 2px 6px rgba(0,0,0,0.9)' });
                };
            });
        }
        
        // Function to update text styles based on background
        async function updateTextStyles() {
            if (bgPreviewUrl) {
                if (selectedDefaultBg && defaultBgColors[selectedDefaultBg]) {
                    textColor = defaultBgColors[selectedDefaultBg];
                    textShadow = '2px 2px 4px rgba(255,255,255,0.5)';
                } else {
                    const styles = await getTextColorForBackground(bgPreviewUrl);
                    textColor = styles.color;
                    textShadow = styles.shadow;
                }
                
                const previewText = document.getElementById('previewText');
                previewText.style.color = textColor;
                previewText.style.textShadow = textShadow;
                
                document.querySelectorAll('.mini-content').forEach(content => {
                    content.style.color = textColor;
                    content.style.textShadow = textShadow;
                });
                
                document.querySelectorAll('.generated-content').forEach(content => {
                    content.style.color = textColor;
                    content.style.textShadow = textShadow;
                });
            } else {
                textColor = '#333';
                textShadow = 'none';
                
                const previewText = document.getElementById('previewText');
                previewText.style.color = textColor;
                previewText.style.textShadow = textShadow;
                
                document.querySelectorAll('.mini-content').forEach(content => {
                    content.style.color = '#333';
                    content.style.textShadow = 'none';
                });
                
                document.querySelectorAll('.generated-content').forEach(content => {
                    content.style.color = '#333';
                    content.style.textShadow = 'none';
                });
            }
        }
        
        function selectDefaultBackground(bgName) {
            document.getElementById('background').value = '';
            document.getElementById('fileName').textContent = 'No file chosen';
            document.getElementById('bgPreviewContainer').style.display = 'none';
            currentBackground = null;
            
            selectedDefaultBg = bgName;
            bgPreviewUrl = bgName;
            
            document.getElementById('selected_default_bg').value = bgName;
            
            if (defaultBgColors[bgName]) {
                const argbColor = hexToArgb(defaultBgColors[bgName]);
                document.getElementById('selected_text_color').value = argbColor;
            }
            
            document.querySelectorAll('.default-bg').forEach(bg => {
                if (bg.getAttribute('data-bg') === bgName) {
                    bg.style.border = '2px solid #4CAF50';
                    bg.style.boxShadow = '0 0 5px rgba(76, 175, 80, 0.5)';
                } else {
                    bg.style.border = '2px solid #ddd';
                    bg.style.boxShadow = 'none';
                }
            });
            
            updatePreviewWithBackground();
        }
        
        document.getElementById('background').addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                document.getElementById('fileName').textContent = file.name;
                
                selectedDefaultBg = null;
                document.querySelectorAll('.default-bg').forEach(bg => {
                    bg.style.border = '2px solid #ddd';
                    bg.style.boxShadow = 'none';
                });
                
                const reader = new FileReader();
                reader.onload = function(e) {
                    bgPreviewUrl = e.target.result;
                    
                    document.getElementById('bgPreview').src = bgPreviewUrl;
                    document.getElementById('bgPreviewContainer').style.display = 'block';
                    
                    updatePreviewWithBackground();
                };
                reader.readAsDataURL(file);
                
                currentBackground = file;
            }
        });
        
        function clearBackground() {
            document.getElementById('background').value = '';
            document.getElementById('fileName').textContent = 'No file chosen';
            document.getElementById('bgPreviewContainer').style.display = 'none';
            bgPreviewUrl = null;
            currentBackground = null;
            selectedDefaultBg = null;
            
            document.getElementById('selected_default_bg').value = '';
            
            document.querySelectorAll('.default-bg').forEach(bg => {
                bg.style.border = '2px solid #ddd';
                bg.style.boxShadow = 'none';
            });
            
            updatePreviewWithBackground();
        }
        
        async function updatePreviewWithBackground() {
            const slidePreview = document.getElementById('slidePreview');
            const previewText = document.getElementById('previewText');
            
            if (bgPreviewUrl) {
                slidePreview.style.backgroundImage = `url('${bgPreviewUrl}')`;
                slidePreview.classList.add('with-bg');
                await updateTextStyles();
            } else {
                slidePreview.style.backgroundImage = 'none';
                slidePreview.classList.remove('with-bg');
                previewText.style.color = '#333';
                previewText.style.textShadow = 'none';
                textColor = '#333';
                textShadow = 'none';
            }
            
            updateMiniSlides();
            updateGeneratedSlidesBackground();
        }
        
        document.querySelectorAll('.default-bg').forEach(bg => {
            bg.addEventListener('click', function() {
                const bgName = this.getAttribute('data-bg');
                selectDefaultBackground(bgName);
            });
        });
        
        document.getElementById('lyrics').addEventListener('input', updatePreview);
        
        function updatePreview() {
            const lyrics = document.getElementById('lyrics').value;
            const lines = lyrics.split('\n').filter(line => line.trim() !== '');
            const title = document.getElementById('title').value;
            const singer = document.getElementById('singer').value;

            currentSlides = [
                { type: 'title', title: title || 'Presentation Title', singer: singer || '' },
                ...lines.map(line => ({ type: 'lyric', text: line }))
            ];

            currentSlideIndex = Math.min(currentSlideIndex, currentSlides.length - 1);
            
            if (currentSlides.length === 1 && !title && lines.length === 0) {
                document.getElementById('previewText').textContent = 'Type lyrics to see preview...';
                document.getElementById('slideNumber').textContent = 'Slide 1';
                document.getElementById('slidesContainer').innerHTML = '';
                return;
            }

            const slide = currentSlides[currentSlideIndex];
            const previewText = document.getElementById('previewText');

            if (slide.type === 'title') {
                previewText.innerHTML = `
                    <div style="color: ${textColor}; text-shadow: ${textShadow};">${slide.title || 'Presentation Title'}</div>
                    ${slide.singer ? `<div style="font-size:15%; margin-top:10px; color: ${textColor}; text-shadow: ${textShadow};">${slide.singer}</div>` : ''}
                `;
            } else {
                previewText.textContent = slide.text;
                previewText.style.color = textColor;
                previewText.style.textShadow = textShadow;
            }

            document.getElementById('slideNumber').textContent = 
                `Slide ${currentSlideIndex + 1} of ${currentSlides.length}`;

            updateMiniSlides();
        }
        
        function updateMiniSlides() {
            const container = document.getElementById('slidesContainer');
            container.innerHTML = '';
            
            currentSlides.forEach((slide, index) => {
                const miniSlide = document.createElement('div');
                miniSlide.className = `mini-slide ${index === currentSlideIndex ? 'active' : ''}`;
                
                if (bgPreviewUrl) {
                    miniSlide.style.backgroundImage = `url('${bgPreviewUrl}')`;
                    miniSlide.classList.add('with-bg');
                }
                
                miniSlide.onclick = () => showSlide(index);
                
                const content = document.createElement('div');
                content.className = 'mini-content';
                content.style.color = textColor;
                content.style.textShadow = textShadow;
                
                if (slide.type === 'title') {
                    content.textContent = slide.title || '🎵 Title Slide';
                } else {
                    const text = slide.text || '';
                    content.textContent = text.length > 30 ? text.substring(0, 30) + '...' : text;
                }
                
                miniSlide.appendChild(content);
                container.appendChild(miniSlide);
            });
        }
        
        function showSlide(index) {
            currentSlideIndex = index;
            const slide = currentSlides[index];
            const previewText = document.getElementById('previewText');

            if (slide.type === 'title') {
                previewText.innerHTML = `
                    <div style="color: ${textColor}; text-shadow: ${textShadow};">${slide.title || 'Presentation Title'}</div>
                    ${slide.singer ? `<div style="font-size:15%; margin-top:10px; color: ${textColor}; text-shadow: ${textShadow};">${slide.singer}</div>` : ''}
                `;
            } else {
                previewText.textContent = slide.text;
                previewText.style.color = textColor;
                previewText.style.textShadow = textShadow;
            }

            document.getElementById('slideNumber').textContent = `Slide ${index + 1} of ${currentSlides.length}`;
            updateMiniSlides();
        }
        
        // Add Font Awesome for icons
        const fontAwesome = document.createElement('link');
        fontAwesome.rel = 'stylesheet';
        fontAwesome.href = 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css';
        document.head.appendChild(fontAwesome);
    </script>
</body>
</html>