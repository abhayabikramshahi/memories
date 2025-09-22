<?php
$dir = "uploads/";
$imageExt = ['jpg','jpeg','png','gif','webp'];
$videoExt = ['mp4','webm','mov'];
$msg = "";

// Handle Upload
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['memory'])) {
    $file = $_FILES['memory'];
    $target = $dir . basename($file['name']);
    if (move_uploaded_file($file['tmp_name'], $target)) {
        $msg = "✅ Memory uploaded successfully!";
    } else {
        $msg = "❌ Upload failed!";
    }
}

// Handle Delete
if (isset($_GET['delete'])) {
    $fileToDelete = basename($_GET['delete']);
    $filePath = $dir . $fileToDelete;
    if (file_exists($filePath)) {
        unlink($filePath);
        header("Location: ".$_SERVER['PHP_SELF']);
        exit;
    }
}

// Scan folder
$files = array_diff(scandir($dir), array('.', '..'));
$images = array_filter($files, fn($f) => in_array(strtolower(pathinfo($f, PATHINFO_EXTENSION)), $imageExt));
$videos = array_filter($files, fn($f) => in_array(strtolower(pathinfo($f, PATHINFO_EXTENSION)), $videoExt));
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>❤️ Our Album ❤️</title>

<!-- PWA -->
<link rel="manifest" href="manifest.json">
<meta name="theme-color" content="#e91e63">
<link rel="apple-touch-icon" href="icons/icon-192.png">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="default">

<!-- Tailwind + Fancybox -->
<script src="https://cdn.tailwindcss.com"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fancyapps/ui/dist/fancybox.css" />
<script src="https://cdn.jsdelivr.net/npm/@fancyapps/ui/dist/fancybox.umd.js"></script>

<style>
body { font-family: 'Poppins', sans-serif; background-color: #f3f4f6; }
.image-container { position: relative; overflow: hidden; border-radius: 12px; }
.image-container img { transition: transform 0.3s; width: 100%; height: 100%; object-fit: cover; }
.image-container:hover img { transform: scale(1.05); }
.delete-btn {
  position: absolute; top: 8px; right: 8px;
  background: rgba(255,255,255,0.8);
  color: red; padding: 2px 6px; border-radius: 6px; font-size: 14px; cursor: pointer; display: none;
}
.image-container:hover .delete-btn { display: block; }

.video-container { position: relative; display: flex; gap: 20px; flex-wrap: wrap; align-items: center; }
.video-container video { border-radius: 12px; box-shadow: 0 8px 20px rgba(0,0,0,0.1); transition: transform 0.3s; }
.video-container video:hover { transform: scale(1.02); }
.video-info { max-width: 300px; }
</style>
</head>
<body>

<div class="container mx-auto px-6 py-10">

  <!-- Upload Box -->
  <div class="bg-white p-6 rounded-xl shadow-md max-w-md mx-auto mb-10">
    <h2 class="text-xl font-semibold text-gray-700 mb-4">📤 Upload a Memory</h2>
    <?php if($msg) echo "<p class='mb-2 text-sm text-green-600'>$msg</p>"; ?>
    <form action="" method="POST" enctype="multipart/form-data" class="flex items-center gap-3">
      <input type="file" name="memory" class="border p-2 rounded-lg w-full text-sm" required>
      <button type="submit" class="bg-pink-500 hover:bg-pink-600 text-white px-4 py-2 rounded-lg">Upload</button>
    </form>
  </div>

  <!-- Counters -->
  <div class="flex justify-center gap-8 mb-12 flex-wrap">
    <div class="text-center">
      <p class="text-3xl font-bold text-pink-600"><?= count($images) ?></p>
      <p class="text-gray-600">Photos</p>
    </div>
    <div class="text-center">
      <p class="text-3xl font-bold text-pink-600"><?= count($videos) ?></p>
      <p class="text-gray-600">Videos</p>
    </div>
  </div>

  <!-- Photo Album -->
  <div class="mb-16">
    <h2 class="text-2xl font-semibold text-gray-700 mb-6 border-b pb-2">📸 Photo Album</h2>
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
      <?php foreach($images as $img): ?>
        <div class="image-container rounded-xl shadow-md overflow-hidden hover:shadow-xl transition">
          <a href="<?= $dir.$img ?>" data-fancybox="photos" data-caption="❤️ Memory">
            <img src="<?= $dir.$img ?>" class="w-full h-80"/>
          </a>
          <a href="?delete=<?= $img ?>" class="delete-btn" onclick="return confirm('Delete this memory?')">🗑️ Delete</a>
        </div>
      <?php endforeach; ?>
    </div>
  </div>

  <!-- Video Album -->
  <div>
    <h2 class="text-2xl font-semibold text-gray-700 mb-6 border-b pb-2">🎥 Video Album</h2>
    <div class="flex flex-col gap-12">
      <?php $i=0; foreach($videos as $vid): $i++; $zigzag=$i%2==0?"md:flex-row-reverse":"md:flex-row"; ?>
        <div class="flex flex-col <?= $zigzag ?> md:items-center gap-6 video-container">
          <a href="<?= $dir.$vid ?>" data-fancybox="videos" class="w-full md:w-2/3">
            <video class="w-full rounded-xl shadow-md hover:shadow-xl transition" controls>
              <source src="<?= $dir.$vid ?>" type="video/mp4">
            </video>
          </a>
          <a href="?delete=<?= $vid ?>" class="delete-btn" onclick="return confirm('Delete this video?')">🗑️ Delete</a>
          <div class="video-info text-gray-600">
            <h3 class="font-semibold text-lg">Video Memory <?= $i ?></h3>
            <p class="text-sm">Unforgettable moment 💕</p>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </div>

</div>

<script>
Fancybox.bind('[data-fancybox]', {});

// PWA Install prompt
let deferredPrompt;
window.addEventListener('beforeinstallprompt', (e) => {
  e.preventDefault();
  deferredPrompt = e;
  setTimeout(async () => {
    if (!deferredPrompt) return;
    deferredPrompt.prompt();
    const choice = await deferredPrompt.userChoice;
    console.log('Install result:', choice.outcome);
    deferredPrompt = null;
  }, 1000);
});

// Service Worker
if ('serviceWorker' in navigator) {
  navigator.serviceWorker.register('sw.js')
  .then(reg => console.log('SW registered!', reg))
  .catch(err => console.log('SW failed', err));
}
</script>

</body>
</html>
