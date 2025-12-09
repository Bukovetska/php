<?php

require __DIR__ . '/inc/functions.inc.php';
require __DIR__ . '/inc/db-connect.inc.php';

$error = null; 

if (!empty($_POST)) {
    $title = (string) ($_POST['title'] ?? '');
    $date = (string) ($_POST['date'] ?? '');
    $message = (string) ($_POST['message'] ?? '');
    $imageName = null;

    if (empty($_FILES['image']) || $_FILES['image']['error'] === UPLOAD_ERR_NO_FILE) {
        $error = 'Будь ласка, завантажте зображення! Це поле є обов’язковим.';
    } 
    elseif ($_FILES['image']['error'] !== UPLOAD_ERR_OK) {
        $error = 'Помилка завантаження файлу.';
    }
    else {
        if ($_FILES['image']['size'] !== 0) {
            $nameWithoutExtension = pathinfo($_FILES['image']['name'], PATHINFO_FILENAME);
            $name = preg_replace('/[^a-zA-Z0-9]/', '', $nameWithoutExtension);
    
            $originalImage = $_FILES['image']['tmp_name'];
            $imageName = $name . '-' . time() . '.jpg';
            $destImage = __DIR__ . '/uploads/' . $imageName;
    
            $imageSize = getimagesize($originalImage);
            
            if (empty($imageSize)) {
                $error = 'Файл не є коректним зображенням.';
            } else {
                [$width, $height] = $imageSize;
    
                $maxDim = 400;
                $scaleFactor = $maxDim / max($width, $height);
        
                $newWidth = $width * $scaleFactor;
                $newHeight = $height * $scaleFactor;
        
                $im = imagecreatefromjpeg($originalImage);
                if (!empty($im)) {
                    $newImg = imagecreatetruecolor($newWidth, $newHeight);
                    imagecopyresampled($newImg, $im, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);
            
                    imagejpeg($newImg, $destImage);
                } else {
                    $error = 'Не вдалося обробити зображення (підтримується тільки JPG).';
                }
            }
        }
    }
    if ($error === null) {
        $stmt = $pdo->prepare('INSERT INTO `entries` (`title`, `date`, `message`, `image`) VALUES (:title, :date, :message, :image)');
        $stmt->bindValue(':title', $title);
        $stmt->bindValue(':date', $date);
        $stmt->bindValue(':message', $message);
        $stmt->bindValue(':image', $imageName);
        $stmt->execute();

        header('Location: index.php');
        die();
    }
}

?>
<?php require __DIR__ . '/views/header.view.php'; ?>
<h1 class="main-heading">New Entry</h1>

<div class="container">
    <?php if ($error): ?>
        <div style="background-color: #f8d7da; color: #721c24; padding: 1rem; margin-bottom: 1rem; border: 1px solid #f5c6cb; border-radius: 5px;">
            <?php echo e($error); ?>
        </div>
    <?php endif; ?>

    <form method="POST" action="form.php" enctype="multipart/form-data">
        <div class="form-group">
            <label class="from-group__label" for="title">Title:</label>
            <input class="from-group__input" type="text" id="title" name="title" value="<?php echo e($title ?? ''); ?>" required />
        </div>
        <div class="form-group">
            <label class="from-group__label" for="date">Date:</label>
            <input class="from-group__input" type="date" id="date" name="date" value="<?php echo e($date ?? ''); ?>" required/>
        </div>
        <div class="form-group">
            <label class="from-group__label" for="image">Image:</label>
            <input class="from-group__input" type="file" id="image" name="image" required />
        </div>
        <div class="form-group">
            <label class="from-group__label" for="message">Message:</label>
            <textarea class="from-group__input" id="message" name="message" rows="6" required><?php echo e($message ?? ''); ?></textarea>
        </div>
        <div class="form-submit">
            <button class="button">
                <svg class="button__icon" viewBox="0 0 34.7163912799 33.4350009649">
                    <g style="fill: none; stroke: currentColor; stroke-linecap: round; stroke-linejoin: round; stroke-width: 2px;">
                        <polygon points="20.6844359446 32.4350009649 33.7163912799 1 1 10.3610302393 15.1899978903 17.5208901631 20.6844359446 32.4350009649"/>
                        <line x1="33.7163912799" y1="1" x2="15.1899978903" y2="17.5208901631"/>
                    </g>
                </svg>
                Save!
            </button>
        </div>
    </form>
</div>
<?php require __DIR__ . '/views/footer.view.php'; ?>
