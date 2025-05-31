<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Our Beautiful Memories</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fancyapps/ui@5.0/dist/fancybox/fancybox.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#e91e63',
                        secondary: '#ff4081',
                    },
                    fontFamily: {
                        sans: ['Poppins', 'sans-serif'],
                    },
                }
            }
        }
    </script>
    <style>
        .image-container {
            position: relative;
            overflow: hidden;
            background: #f8f9fa;
            border-radius: 0.5rem;
            margin: 0.5rem;
        }
        .image-container img {
            transition: transform 0.3s ease;
            width: 100%;
            height: auto;
            display: block;
        }
        .image-container:hover img {
            transform: scale(1.02);
        }
        .image-overlay {
            position: absolute;
            inset: 0;
            background: linear-gradient(to bottom, rgba(0,0,0,0) 0%, rgba(0,0,0,0.4) 100%);
            opacity: 0;
            transition: opacity 0.3s ease;
            border-radius: 0.5rem;
        }
        .image-container:hover .image-overlay {
            opacity: 1;
        }
        .post-media {
            background: #f8f9fa;
            padding: 0.5rem;
            border-radius: 0.5rem;
            margin: 0.5rem;
        }
    </style>
</head>
<body class="bg-gray-100 min-h-screen font-sans text-gray-800">
    <!-- Header -->
    <header class="bg-white shadow-sm sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <h1 class="text-2xl font-semibold text-primary">Our Beautiful Memories</h1>
                <div class="flex items-center gap-4">
                    <div class="bg-primary/10 px-4 py-2 rounded-full text-primary font-medium">
                        <i class="fas fa-heart mr-2"></i>
                        <?php
                        $uploads_dir = 'uploads';
                        $memory_count = 0;
                        if (is_dir($uploads_dir)) {
                            $files = scandir($uploads_dir);
                            $memory_count = count(array_diff($files, array('.', '..')));
                        }
                        echo $memory_count . ' Memories';
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <main class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <!-- Account Selection -->
        <div class="bg-white rounded-xl shadow-sm mb-6 overflow-hidden">
            <div class="p-4 border-b border-gray-100">
                <h2 class="font-medium text-gray-800 text-sm mb-3">Select Account</h2>
                <div class="flex gap-4">
                    <button onclick="selectAccount('hangma')" class="flex-1 flex items-center gap-3 p-3 rounded-lg border-2 border-primary bg-primary/5 hover:bg-primary/10 transition-colors duration-300">
                        <div class="w-10 h-10 bg-primary/20 rounded-full flex items-center justify-center">
                            <span class="text-primary font-medium">H</span>
                        </div>
                        <div class="text-left">
                            <div class="font-medium text-gray-800">Hangma</div>
                            <div class="text-xs text-gray-500">@hangma</div>
                        </div>
                    </button>
                    <button onclick="selectAccount('abhaya')" class="flex-1 flex items-center gap-3 p-3 rounded-lg border-2 border-gray-200 hover:border-primary/50 hover:bg-gray-50 transition-colors duration-300">
                        <div class="w-10 h-10 bg-secondary/20 rounded-full flex items-center justify-center">
                            <span class="text-secondary font-medium">A</span>
                        </div>
                        <div class="text-left">
                            <div class="font-medium text-gray-800">Abhaya</div>
                            <div class="text-xs text-gray-500">@abhaya</div>
                        </div>
                    </button>
                </div>
            </div>
        </div>

        <!-- Upload Section -->
        <div class="bg-white rounded-xl shadow-sm mb-6 overflow-hidden">
            <div class="p-4 border-b border-gray-100">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-primary/10 rounded-full flex items-center justify-center">
                        <span class="text-primary font-medium" id="currentAccountInitial">H</span>
                    </div>
                    <div class="flex-1">
                        <h2 class="font-medium text-gray-800">Share a Memory</h2>
                        <p class="text-xs text-gray-500">as <span id="currentAccountName">Hangma</span></p>
                    </div>
                </div>
            </div>

            <form action="upload.php" method="post" enctype="multipart/form-data" class="space-y-4" id="uploadForm">
                <input type="hidden" name="account" id="selectedAccount" value="hangma">
                <div class="flex items-center space-x-4">
                    <div class="flex-1">
                        <label for="media" class="sr-only">Choose file</label>
                        <input type="file" name="media" id="media" accept="image/*,video/mp4" class="hidden" required>
                        <div class="relative">
                            <input type="text" id="file-name" readonly placeholder="Choose a file..." class="w-full px-4 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-pink-500 focus:border-pink-500">
                            <button type="button" onclick="document.getElementById('media').click()" class="absolute right-2 top-1/2 -translate-y-1/2 px-4 py-1 text-sm font-medium text-pink-600 hover:text-pink-700">
                                Browse
                            </button>
                        </div>
                        <p class="mt-1 text-xs text-gray-500">JPG, PNG, GIF or MP4 up to 10MB</p>
                    </div>
                    <button type="submit" class="px-6 py-2 text-sm font-medium text-white bg-gradient-to-r from-pink-500 to-purple-500 rounded-lg hover:from-pink-600 hover:to-purple-600 focus:outline-none focus:ring-2 focus:ring-pink-500 focus:ring-offset-2 transition-all duration-200">
                        Share Memory
                    </button>
                </div>
            </form>

            <?php
            if (isset($_GET['status'])) {
                if ($_GET['status'] == 'success') {
                    echo '<div class="px-4 pb-4"><div class="p-3 bg-green-50 text-green-700 rounded-lg border border-green-200 flex items-center gap-3"><i class="fas fa-check-circle"></i><div><p class="font-medium text-sm">Success!</p><p class="text-xs">Memory uploaded successfully!</p></div></div></div>';
                } else if ($_GET['status'] == 'error') {
                    $errorMessage = isset($_GET['message']) ? $_GET['message'] : 'Error uploading memory. Please try again.';
                    echo '<div class="px-4 pb-4"><div class="p-3 bg-red-50 text-red-700 rounded-lg border border-red-200 flex items-center gap-3"><i class="fas fa-exclamation-circle"></i><div><p class="font-medium text-sm">Error</p><p class="text-xs">' . htmlspecialchars($errorMessage) . '</p></div></div></div>';
                }
            }
            ?>
        </div>

        <!-- Feed Section -->
        <div class="space-y-4">
            <?php
            $uploads_dir = 'uploads';
            if (is_dir($uploads_dir)) {
                $files = scandir($uploads_dir);
                $files = array_diff($files, array('.', '..'));
                $files = array_reverse($files); // Show newest first
                
                foreach ($files as $file) {
                    $file_path = 'uploads/' . $file;
                    $file_type = mime_content_type($file_path);
                    $is_video = strpos($file_type, 'video/') === 0;
                    $date = date('F j, Y', filemtime($file_path));
                    $time = date('g:i A', filemtime($file_path));
                    
                    // Determine account based on file name or metadata
                    $account = strpos($file, 'abhaya') !== false ? 'abhaya' : 'hangma';
                    $accountName = $account === 'abhaya' ? 'Abhaya' : 'Hangma';
                    $accountInitial = $account === 'abhaya' ? 'A' : 'H';
                    $accountColor = $account === 'abhaya' ? 'secondary' : 'primary';
                    
                    echo '<div class="bg-white rounded-xl shadow-sm overflow-hidden">';
                    // Post Header
                    echo '<div class="p-3 border-b border-gray-100">';
                    echo '<div class="flex items-center gap-3">';
                    echo '<div class="w-8 h-8 bg-' . $accountColor . '/10 rounded-full flex items-center justify-center">';
                    echo '<span class="text-' . $accountColor . ' font-medium text-sm">' . $accountInitial . '</span>';
                    echo '</div>';
                    echo '<div>';
                    echo '<div class="font-medium text-gray-800 text-sm">' . $accountName . '</div>';
                    echo '<div class="text-xs text-gray-500">' . $date . ' at ' . $time . '</div>';
                    echo '</div>';
                    echo '</div>';
                    echo '</div>';
                    
                    // Media Content
                    echo '<div class="post-media">';
                    if ($is_video) {
                        echo '<div class="relative w-full rounded-lg overflow-hidden">';
                        echo '<video src="' . $file_path . '" preload="metadata" controls class="w-full rounded-lg"></video>';
                        echo '<div class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black/80 to-transparent p-4 flex items-center gap-3 opacity-0 group-hover:opacity-100 transition-opacity duration-300">';
                        echo '<button class="play-pause bg-white/10 hover:bg-white/20 text-white p-2 rounded-full transition-colors duration-300"><i class="fas fa-play"></i></button>';
                        echo '<div class="flex-1 h-1 bg-white/20 rounded-full cursor-pointer">';
                        echo '<div class="progress h-full bg-primary rounded-full w-0"></div>';
                        echo '</div>';
                        echo '<div class="text-white text-sm font-medium min-w-[100px]"><span class="current">0:00</span> / <span class="duration">0:00</span></div>';
                        echo '<div class="flex items-center gap-2">';
                        echo '<button class="volume bg-white/10 hover:bg-white/20 text-white p-2 rounded-full transition-colors duration-300"><i class="fas fa-volume-up"></i></button>';
                        echo '<input type="range" class="volume-slider w-0 h-1 bg-white/20 rounded-full transition-all duration-300 hover:w-20" min="0" max="1" step="0.1" value="1">';
                        echo '</div>';
                        echo '<button class="fullscreen bg-white/10 hover:bg-white/20 text-white p-2 rounded-full transition-colors duration-300"><i class="fas fa-expand"></i></button>';
                        echo '</div>';
                        echo '</div>';
                    } else {
                        echo '<div class="image-container">';
                        echo '<a href="' . $file_path . '" data-fancybox="gallery" class="block w-full">';
                        echo '<img src="' . $file_path . '" alt="Memory" class="w-full h-auto object-contain rounded-lg">';
                        echo '<div class="image-overlay"></div>';
                        echo '</a>';
                        echo '</div>';
                    }
                    echo '</div>';
                    
                    // Post Footer
                    echo '<div class="p-3">';
                    echo '<div class="flex items-center gap-4">';
                    echo '<button class="flex items-center gap-1.5 text-gray-500 hover:text-primary transition-colors duration-300 text-sm">';
                    echo '<i class="fas fa-heart"></i>';
                    echo '<span>Like</span>';
                    echo '</button>';
                    echo '<button class="flex items-center gap-1.5 text-gray-500 hover:text-primary transition-colors duration-300 text-sm">';
                    echo '<i class="fas fa-comment"></i>';
                    echo '<span>Comment</span>';
                    echo '</button>';
                    echo '<button class="flex items-center gap-1.5 text-gray-500 hover:text-primary transition-colors duration-300 text-sm">';
                    echo '<i class="fas fa-share"></i>';
                    echo '<span>Share</span>';
                    echo '</button>';
                    echo '</div>';
                    echo '</div>';
                    
                    echo '</div>';
                }
            }
            ?>
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/@fancyapps/ui@5.0/dist/fancybox/fancybox.umd.js"></script>
    <script>
        Fancybox.bind('[data-fancybox]', {
            Toolbar: {
                display: {
                    left: ['infobar'],
                    middle: ['zoomIn', 'zoomOut', 'toggle1to1', 'rotateCCW', 'rotateCW', 'flipX', 'flipY'],
                    right: ['slideshow', 'thumbs', 'close']
                }
            },
            Images: {
                Panzoom: {
                    maxScale: 5,
                    wheel: 'zoom',
                    doubleClick: 'toggleZoom',
                    infinite: false,
                    rubberband: true
                }
            },
            Carousel: {
                transition: 'slide',
                friction: 0.96,
                Dots: false,
                Navigation: {
                    prevTpl: '<svg><path d="M19 3l2 2-8 8 8 8-2 2-10-10z"/></svg>',
                    nextTpl: '<svg><path d="M5 3l-2 2 8 8-8 8 2 2 10-10z"/></svg>',
                }
            },
            Html: {
                video: {
                    ratio: 16/9,
                    autoplay: false,
                    playsinline: true,
                    controls: true
                }
            },
            Slideshow: {
                autoStart: false,
                speed: 3000,
                progress: true
            },
            fullscreen: {
                autoStart: false
            },
            Video: {
                autoplay: false,
                clickToPlay: true,
                controls: true,
                ratio: 16/9
            },
            Thumbs: {
                type: 'modern',
                Carousel: {
                    center: true,
                    fillSlide: true
                }
            },
            dragToClose: false,
            preload: 3,
            animated: true,
            idle: 3000,
            showClass: 'fancybox-zoomIn',
            hideClass: 'fancybox-zoomOut'
        });

        // Update file input handling
        document.getElementById('media').addEventListener('change', function(e) {
            const fileName = e.target.files[0]?.name || '';
            document.getElementById('file-name').value = fileName;
            
            // Validate file size
            if (e.target.files[0]) {
                const fileSize = e.target.files[0].size;
                const maxSize = 10 * 1024 * 1024; // 10MB
                
                if (fileSize > maxSize) {
                    alert('File is too large. Maximum size is 10MB.');
                    e.target.value = '';
                    document.getElementById('file-name').value = '';
                    return;
                }
            }
        });

        // Update form submission
        document.getElementById('uploadForm').addEventListener('submit', function(e) {
            const fileInput = document.getElementById('media');
            if (!fileInput.files.length) {
                e.preventDefault();
                alert('Please select a file to upload.');
                return;
            }
            
            const file = fileInput.files[0];
            const maxSize = 10 * 1024 * 1024; // 10MB
            
            if (file.size > maxSize) {
                e.preventDefault();
                alert('File is too large. Maximum size is 10MB.');
                return;
            }
            
            const allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'video/mp4'];
            if (!allowedTypes.includes(file.type)) {
                e.preventDefault();
                alert('Only JPG, PNG, GIF & MP4 files are allowed.');
                return;
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

        // Account selection functionality
        function selectAccount(account) {
            const buttons = document.querySelectorAll('[onclick^="selectAccount"]');
            buttons.forEach(button => {
                if (button.getAttribute('onclick').includes(account)) {
                    button.classList.remove('border-gray-200', 'hover:border-primary/50', 'hover:bg-gray-50');
                    button.classList.add('border-primary', 'bg-primary/5', 'hover:bg-primary/10');
                } else {
                    button.classList.remove('border-primary', 'bg-primary/5', 'hover:bg-primary/10');
                    button.classList.add('border-gray-200', 'hover:border-primary/50', 'hover:bg-gray-50');
                }
            });

            // Update current account display
            document.getElementById('selectedAccount').value = account;
            document.getElementById('currentAccountName').textContent = account === 'abhaya' ? 'Abhaya' : 'Hangma';
            document.getElementById('currentAccountInitial').textContent = account === 'abhaya' ? 'A' : 'H';
        }
    </script>
</body>
</html>