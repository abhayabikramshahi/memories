<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Our Beautiful Memories</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fancyapps/ui@5.0/dist/fancybox/fancybox.css">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Our Beautiful Memories</h1>
            <?php
            $uploads_dir = 'uploads';
            $memory_count = 0;
            if (is_dir($uploads_dir)) {
                $files = scandir($uploads_dir);
                $memory_count = count(array_diff($files, array('.', '..')));
            }
            ?>
            <div class="memory-counter"><?php echo $memory_count; ?> Memories Shared ❤</div>
            <p>A collection of our precious moments together ❤</p>
        </div>
        <div class="upload-section">
            <h2>Add New Memory</h2>
            <form action="upload.php" method="post" enctype="multipart/form-data" class="upload-form">
                <div class="file-input-wrapper">
                    <input type="file" name="media" accept="image/*,video/*" class="file-input" required>
                    <div class="file-input-label">
                        <span>📸 Drop your photo or video here</span>
                        <small>or click to browse</small>
                    </div>
                </div>
                <button type="submit" class="upload-btn">Share Memory</button>
            </form>
            <?php
            if (isset($_GET['status'])) {
                if ($_GET['status'] == 'success') {
                    echo '<div class="message success">Memory uploaded successfully! ❤</div>';
                } else if ($_GET['status'] == 'error') {
                    $errorMessage = isset($_GET['message']) ? $_GET['message'] : 'Error uploading memory. Please try again.';
                    echo '<div class="message error">' . htmlspecialchars($errorMessage) . '</div>';
                }
            }
            ?>
        </div>

        <div class="media-grid">
            <?php
            $uploads_dir = 'uploads';
            if (is_dir($uploads_dir)) {
                $files = scandir($uploads_dir);
                foreach ($files as $file) {
                    if ($file != '.' && $file != '..') {
                        $file_path = 'uploads/' . $file;
                        $file_type = mime_content_type($file_path);
                        $is_video = strpos($file_type, 'video/') === 0;
                        
                        echo '<div class="media-item">';
                        if ($is_video) {
                            echo '<div class="video-player">';
                            echo '<video src="' . $file_path . '" preload="metadata" controls></video>';
                            echo '<div class="video-controls">';
                            echo '<button class="play-pause"><i class="fas fa-play"></i></button>';
                            echo '<div class="progress-bar">';
                            echo '<div class="progress"></div>';
                            echo '</div>';
                            echo '<div class="time"><span class="current">0:00</span> / <span class="duration">0:00</span></div>';
                            echo '<div class="volume-container">';
                            echo '<button class="volume"><i class="fas fa-volume-up"></i></button>';
                            echo '<input type="range" class="volume-slider" min="0" max="1" step="0.1" value="1">';
                            echo '</div>';
                            echo '<button class="fullscreen"><i class="fas fa-expand"></i></button>';
                            echo '</div>';
                            echo '</div>';
                        } else {
                            echo '<a href="' . $file_path . '" data-fancybox="gallery">';
                            echo '<img src="' . $file_path . '" alt="Memory">';
                            echo '</a>';
                        }
                        echo '<div class="media-overlay">';
                        echo '<span>' . date('F j, Y', filemtime($file_path)) . '</span>';
                        echo '</div>';
                        echo '</div>';
                    }
                }
            }
            ?>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/@fancyapps/ui@5.0/dist/fancybox/fancybox.umd.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script>
        Fancybox.bind('[data-fancybox]', {
            Toolbar: {
                display: ['zoom', 'slideshow', 'fullscreen', 'close']
            },
            Images: {
                Panzoom: {
                    maxScale: 4
                }
            },
            Html: {
                video: {
                    ratio: 16/9,
                    autoplay: true,
                    playsinline: true
                }
            },
            Slideshow: {
                autoStart: false,
                speed: 3000
            },
            fullscreen: {
                autoStart: false
            },
            Video: {
                autoplay: false,
                ratio: 16/9
            }
        });

        // Preview selected file
        const fileInput = document.querySelector('.file-input');
        const fileLabel = document.querySelector('.file-input-label');
        
        fileInput.addEventListener('change', function() {
            if (this.files && this.files[0]) {
                const fileName = this.files[0].name;
                fileLabel.querySelector('span').textContent = fileName;
            }
        });

        // Initialize video players
        document.querySelectorAll('.video-player').forEach(player => {
            const video = player.querySelector('video');
            const playPauseBtn = player.querySelector('.play-pause');
            const progress = player.querySelector('.progress');
            const progressBar = player.querySelector('.progress-bar');
            const currentTime = player.querySelector('.current');
            const duration = player.querySelector('.duration');
            const volumeBtn = player.querySelector('.volume');
            const volumeSlider = player.querySelector('.volume-slider');
            const fullscreenBtn = player.querySelector('.fullscreen');

            // Play/Pause
            playPauseBtn.addEventListener('click', () => {
                if (video.paused) {
                    video.play();
                    playPauseBtn.innerHTML = '<i class="fas fa-pause"></i>';
                } else {
                    video.pause();
                    playPauseBtn.innerHTML = '<i class="fas fa-play"></i>';
                }
            });

            // Progress bar
            video.addEventListener('timeupdate', () => {
                const percent = (video.currentTime / video.duration) * 100;
                progress.style.width = percent + '%';
                currentTime.textContent = formatTime(video.currentTime);
            });

            video.addEventListener('loadedmetadata', () => {
                duration.textContent = formatTime(video.duration);
            });

            progressBar.addEventListener('click', (e) => {
                const pos = (e.pageX - progressBar.offsetLeft) / progressBar.offsetWidth;
                video.currentTime = pos * video.duration;
            });

            // Volume
            volumeSlider.addEventListener('input', () => {
                video.volume = volumeSlider.value;
                updateVolumeIcon();
            });

            volumeBtn.addEventListener('click', () => {
                video.muted = !video.muted;
                updateVolumeIcon();
            });

            function updateVolumeIcon() {
                const icon = volumeBtn.querySelector('i');
                if (video.muted || video.volume === 0) {
                    icon.className = 'fas fa-volume-mute';
                } else if (video.volume < 0.5) {
                    icon.className = 'fas fa-volume-down';
                } else {
                    icon.className = 'fas fa-volume-up';
                }
            }

            // Fullscreen
            fullscreenBtn.addEventListener('click', () => {
                if (!document.fullscreenElement) {
                    player.requestFullscreen();
                    fullscreenBtn.innerHTML = '<i class="fas fa-compress"></i>';
                } else {
                    document.exitFullscreen();
                    fullscreenBtn.innerHTML = '<i class="fas fa-expand"></i>';
                }
            });

            // Format time
            function formatTime(seconds) {
                const minutes = Math.floor(seconds / 60);
                seconds = Math.floor(seconds % 60);
                return `${minutes}:${seconds.toString().padStart(2, '0')}`;
            }
        });
    </script>
</body>
</html>