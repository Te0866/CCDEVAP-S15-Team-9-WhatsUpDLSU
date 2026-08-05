<?php

class ImageUploader
{
    public static function storeIfPresent($file, $uploadDir)
    {
        if (!isset($file) || $file['error'] !== 0) {
            return null;
        }

        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $originalName = basename($file['name']);
        $newFileName = str_replace('.', '', sprintf('%.6f', microtime(true))) . "_" . $originalName;
        $targetPath = $uploadDir . $newFileName;

        if (move_uploaded_file($file['tmp_name'], $targetPath)) {
            return $newFileName;
        }

        return null;
    }
}
