<?php
// config/app.php

define('APP_NAME', 'Sistema Académico');
define('APP_URL', 'http://localhost/academic_system/public');
define('SESSION_LIFETIME', 1800); // 30 minutos

// Upload settings
define('UPLOAD_DIR', __DIR__ . '/../public/uploads/photos/');
define('UPLOAD_MAX_SIZE', 2 * 1024 * 1024); // 2MB
define('UPLOAD_ALLOWED_TYPES', ['image/jpeg', 'image/png']);
define('UPLOAD_ALLOWED_EXTENSIONS', ['jpg', 'jpeg', 'png']);

// Perfis
define('ROLE_ALUNO', 'aluno');
define('ROLE_FUNCIONARIO', 'funcionario');
define('ROLE_GESTOR', 'gestor');

// Estados da Ficha de Aluno
define('FICHA_RASCUNHO', 'rascunho');
define('FICHA_SUBMETIDA', 'submetida');
define('FICHA_APROVADA', 'aprovada');
define('FICHA_REJEITADA', 'rejeitada');

// Estados do Pedido de Matrícula
define('MATRICULA_PENDENTE', 'pendente');
define('MATRICULA_APROVADA', 'aprovada');
define('MATRICULA_REJEITADA', 'rejeitada');

// Épocas de avaliação
define('EPOCAS', ['Normal', 'Recurso', 'Especial']);

// Semestres
define('SEMESTRES', [1, 2]);
define('ANOS_MAX', 5);
