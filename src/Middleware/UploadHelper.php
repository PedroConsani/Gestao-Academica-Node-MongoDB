<?php
// src/Middleware/UploadHelper.php

class UploadHelper {
    /**
     * Processa upload de fotografia.
     * @return array ['success'=>bool, 'path'=>string|null, 'error'=>string|null]
     */
    public static function processarFoto(array $file): array {
        if ($file['error'] === UPLOAD_ERR_NO_FILE) {
            return ['success' => true, 'path' => null, 'error' => null];
        }

        if ($file['error'] !== UPLOAD_ERR_OK) {
            return ['success' => false, 'path' => null, 'error' => 'Erro no upload do ficheiro.'];
        }

        // Validar tamanho
        if ($file['size'] > UPLOAD_MAX_SIZE) {
            return ['success' => false, 'path' => null, 'error' => 'A fotografia não pode exceder 2MB.'];
        }

        // Validar tipo MIME real (não apenas extensão)
        $finfo = new finfo(FILEINFO_MIME_TYPE);
        $mimeType = $finfo->file($file['tmp_name']);
        if (!in_array($mimeType, UPLOAD_ALLOWED_TYPES)) {
            return ['success' => false, 'path' => null, 'error' => 'Apenas são aceites ficheiros JPG e PNG.'];
        }

        // Validar extensão
        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        if (!in_array($ext, UPLOAD_ALLOWED_EXTENSIONS)) {
            return ['success' => false, 'path' => null, 'error' => 'Extensão de ficheiro inválida.'];
        }

        // Gerar nome único
        $novoNome = uniqid('foto_', true) . '.' . $ext;
        $destino  = UPLOAD_DIR . $novoNome;

        if (!is_dir(UPLOAD_DIR)) {
            mkdir(UPLOAD_DIR, 0755, true);
        }

        if (!move_uploaded_file($file['tmp_name'], $destino)) {
            return ['success' => false, 'path' => null, 'error' => 'Não foi possível guardar a fotografia.'];
        }

        return ['success' => true, 'path' => $novoNome, 'error' => null];
    }

    public static function fotoUrl(?string $path): string {
        if (!$path) return APP_URL . '/css/placeholder-avatar.png';
        return APP_URL . '/uploads/photos/' . $path;
    }
}
