<?php
$dir = "uploads/";
$imageExt = ['jpg','jpeg','png','gif','webp'];
$videoExt = ['mp4','webm','mov'];

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['memory'])) {
    $file = $_FILES['memory'];
    $target = $dir . basename($file['name']);
    if (move_uploaded_file($file['tmp_name'], $target)) {
        $uploadMsg = "✅ Memory uploaded successfully!";
    } else {
        $uploadMsg = "❌ Upload failed!";
    }
}

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

  <!-- Tailwind -->
  <script src="https://cdn.tailwindcss.com"></script>
  <!-- Fancybox -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fancyapps/ui/dist/fancybox.css" />
  <script src="https://cdn.jsdelivr.net/npm/@fancyapps/ui/dist/fancybox.umd.js"></script>

  <style>
    body { font-family: 'Poppins', sans-serif; }
    video::-webkit-media-controls {
      background: rgba(0,0,0,0.5);
      border-radius: 10px;
    }
  </style>
</head>
<body class="bg-gray-100">

<div class="container mx-auto px-6 py-10">

  <!-- Upload Box -->
  <div class="bg-white p-6 rounded-xl shadow-md max-w-md mx-auto mb-10">
    <h2 class="text-xl font-semibold text-gray-700 mb-4">📤 Upload a Memory</h2>
    <?php if(isset($uploadMsg)) echo "<p class='mb-2 text-sm text-green-600'>$uploadMsg</p>"; ?>
    <form action="" method="POST" enctype="multipart/form-data" class="flex items-center gap-3">
      <input type="file" name="memory" class="border p-2 rounded-lg w-full text-sm">
      <button type="submit" class="bg-pink-500 hover:bg-pink-600 text-white px-4 py-2 rounded-lg">Upload</button>
    </form>
  </div>

  <!-- Counters -->
  <div class="flex justify-center gap-8 mb-12">
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
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-3 gap-6">
      <?php foreach($images as $img): ?>
        <a href="<?= $dir.$img ?>" data-fancybox="photos" data-caption="❤️ Memory">
          <img src="<?= $dir.$img ?>" 
            class="w-full h-80 object-cover rounded-xl shadow-md hover:shadow-xl transition" />
        </a>
      <?php endforeach; ?>
    </div>
  </div>

  <!-- Video Album -->
  <div>
    <h2 class="text-2xl font-semibold text-gray-700 mb-6 border-b pb-2">🎥 Video Album</h2>
    <div class="space-y-12">
      <?php 
      $i = 0;
      foreach($videos as $vid): 
        $i++;
        $zigzag = $i % 2 == 0 ? "md:flex-row-reverse" : "md:flex-row";
      ?>
        <div class="flex flex-col <?= $zigzag ?> md:items-center gap-6">
          <a href="<?= $dir.$vid ?>" data-fancybox="videos" class="w-full md:w-2/3">
            <video class="w-full rounded-xl shadow-md hover:shadow-xl transition" controls>
              <source src="<?= $dir.$vid ?>" type="video/mp4">
            </video>
          </a>
          <div class="md:w-1/3 text-gray-600">
            <h3 class="font-semibold text-lg">Video Memory <?= $i ?></h3>
            <p class="text-sm">One of our unforgettable moments 💕</p>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </div>

</div>

</body>
</html>
