<?php

declare(strict_types=1);

function admin_upload_root(): string
{
    return dirname(__DIR__) . '/assets/uploads';
}

function admin_upload_public_prefix(): string
{
    return '/assets/uploads/';
}

function admin_image_fields(): array
{
    return ['cover_image','before_image','after_image','og_image'];
}

function admin_upload_image(string $field): ?string
{
    $key = $field . '_file';
    if (!isset($_FILES[$key]) || !is_array($_FILES[$key])) return null;

    $file = $_FILES[$key];
    $error = (int)($file['error'] ?? UPLOAD_ERR_NO_FILE);
    if ($error === UPLOAD_ERR_NO_FILE) return null;
    if ($error !== UPLOAD_ERR_OK) throw new RuntimeException('Ошибка загрузки файла для поля «' . $field . '».');

    $tmp = (string)($file['tmp_name'] ?? '');
    $size = (int)($file['size'] ?? 0);
    if ($tmp === '' || !is_uploaded_file($tmp)) throw new RuntimeException('Некорректный загруженный файл.');
    if ($size <= 0 || $size > 8 * 1024 * 1024) throw new RuntimeException('Изображение должно быть не больше 8 МБ.');

    $finfo = new finfo(FILEINFO_MIME_TYPE);
    $mime = (string)$finfo->file($tmp);
    $allowed = [
        'image/jpeg' => 'jpg',
        'image/png' => 'png',
        'image/webp' => 'webp',
    ];
    if (!isset($allowed[$mime])) throw new RuntimeException('Разрешены только JPG, PNG и WebP.');
    if (@getimagesize($tmp) === false) throw new RuntimeException('Файл не распознан как изображение.');

    $dir = admin_upload_root();
    if (!is_dir($dir) && !mkdir($dir, 0775, true) && !is_dir($dir)) {
        throw new RuntimeException('Не удалось создать папку assets/uploads.');
    }

    $name = date('Ymd-His') . '-' . bin2hex(random_bytes(8)) . '.' . $allowed[$mime];
    $target = $dir . '/' . $name;
    if (!move_uploaded_file($tmp, $target)) throw new RuntimeException('Не удалось сохранить изображение.');
    @chmod($target, 0644);

    return admin_upload_public_prefix() . $name;
}
