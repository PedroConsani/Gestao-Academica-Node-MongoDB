-- database/schema.sql
-- Sistema de Gestão Académica

CREATE DATABASE IF NOT EXISTS academic_system
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

USE academic_system;

-- ============================================================
-- UTILIZADORES E PERFIS
-- ============================================================
CREATE TABLE utilizadores (
    id           INT AUTO_INCREMENT PRIMARY KEY,
    nome         VARCHAR(150) NOT NULL,
    email        VARCHAR(150) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    role         ENUM('aluno','funcionario','gestor') NOT NULL,
    ativo        TINYINT(1) NOT NULL DEFAULT 1,
    criado_em    DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    atualizado_em DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- ============================================================
-- CURSOS
-- ============================================================
CREATE TABLE cursos (
    id          INT AUTO_INCREMENT PRIMARY KEY,
    nome        VARCHAR(200) NOT NULL,
    codigo      VARCHAR(20)  NOT NULL UNIQUE,
    descricao   TEXT,
    duracao_anos TINYINT NOT NULL DEFAULT 3,
    ativo       TINYINT(1) NOT NULL DEFAULT 1,
    criado_por  INT NOT NULL,
    criado_em   DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    atualizado_em DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (criado_por) REFERENCES utilizadores(id)
) ENGINE=InnoDB;

-- ============================================================
-- UNIDADES CURRICULARES
-- ============================================================
CREATE TABLE unidades_curriculares (
    id          INT AUTO_INCREMENT PRIMARY KEY,
    nome        VARCHAR(200) NOT NULL,
    codigo      VARCHAR(20)  NOT NULL UNIQUE,
    descricao   TEXT,
    creditos    DECIMAL(4,1) NOT NULL DEFAULT 6.0,
    ativo       TINYINT(1) NOT NULL DEFAULT 1,
    criado_por  INT NOT NULL,
    criado_em   DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (criado_por) REFERENCES utilizadores(id)
) ENGINE=InnoDB;

-- ============================================================
-- PLANO DE ESTUDOS (Curso <-> UC)
-- ============================================================
CREATE TABLE plano_estudos (
    id          INT AUTO_INCREMENT PRIMARY KEY,
    curso_id    INT NOT NULL,
    uc_id       INT NOT NULL,
    ano         TINYINT NOT NULL COMMENT 'Ano curricular (1,2,3...)',
    semestre    TINYINT NOT NULL COMMENT '1 ou 2',
    obrigatoria TINYINT(1) NOT NULL DEFAULT 1,
    UNIQUE KEY uq_curso_uc (curso_id, uc_id),
    UNIQUE KEY uq_curso_ano_sem_uc (curso_id, ano, semestre, uc_id),
    FOREIGN KEY (curso_id) REFERENCES cursos(id),
    FOREIGN KEY (uc_id)    REFERENCES unidades_curriculares(id)
) ENGINE=InnoDB;

-- ============================================================
-- FICHAS DE ALUNO
-- ============================================================
CREATE TABLE fichas_aluno (
    id              INT AUTO_INCREMENT PRIMARY KEY,
    aluno_id        INT NOT NULL UNIQUE,
    curso_id        INT,
    -- Dados pessoais
    data_nascimento DATE,
    nacionalidade   VARCHAR(80),
    nif             VARCHAR(20),
    cc              VARCHAR(20),
    telefone        VARCHAR(20),
    morada          VARCHAR(255),
    codigo_postal   VARCHAR(10),
    localidade      VARCHAR(100),
    foto_path       VARCHAR(255),
    -- Fluxo
    estado          ENUM('rascunho','submetida','aprovada','rejeitada') NOT NULL DEFAULT 'rascunho',
    observacoes     TEXT COMMENT 'Preenchido pelo Gestor Pedagógico',
    submetida_em    DATETIME,
    validada_por    INT COMMENT 'FK -> utilizadores (gestor)',
    validada_em     DATETIME,
    criado_em       DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    atualizado_em   DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (aluno_id)    REFERENCES utilizadores(id),
    FOREIGN KEY (curso_id)    REFERENCES cursos(id),
    FOREIGN KEY (validada_por) REFERENCES utilizadores(id)
) ENGINE=InnoDB;

-- ============================================================
-- PEDIDOS DE MATRÍCULA / INSCRIÇÃO
-- ============================================================
CREATE TABLE matriculas (
    id              INT AUTO_INCREMENT PRIMARY KEY,
    aluno_id        INT NOT NULL,
    curso_id        INT NOT NULL,
    ano_letivo      VARCHAR(9) NOT NULL COMMENT 'ex: 2024/2025',
    observacoes_aluno TEXT,
    -- Fluxo
    estado          ENUM('pendente','aprovada','rejeitada') NOT NULL DEFAULT 'pendente',
    observacoes_func TEXT COMMENT 'Preenchido pelo Funcionário',
    decidido_por    INT COMMENT 'FK -> utilizadores (funcionario)',
    decidido_em     DATETIME,
    criado_em       DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (aluno_id)   REFERENCES utilizadores(id),
    FOREIGN KEY (curso_id)   REFERENCES cursos(id),
    FOREIGN KEY (decidido_por) REFERENCES utilizadores(id)
) ENGINE=InnoDB;

-- ============================================================
-- PAUTAS DE AVALIAÇÃO
-- ============================================================
CREATE TABLE pautas (
    id          INT AUTO_INCREMENT PRIMARY KEY,
    uc_id       INT NOT NULL,
    curso_id    INT NOT NULL,
    ano_letivo  VARCHAR(9) NOT NULL,
    epoca       ENUM('Normal','Recurso','Especial') NOT NULL,
    criada_por  INT NOT NULL,
    criada_em   DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    fechada     TINYINT(1) NOT NULL DEFAULT 0,
    UNIQUE KEY uq_pauta (uc_id, curso_id, ano_letivo, epoca),
    FOREIGN KEY (uc_id)      REFERENCES unidades_curriculares(id),
    FOREIGN KEY (curso_id)   REFERENCES cursos(id),
    FOREIGN KEY (criada_por) REFERENCES utilizadores(id)
) ENGINE=InnoDB;

-- ============================================================
-- NOTAS (linhas da pauta)
-- ============================================================
CREATE TABLE notas (
    id          INT AUTO_INCREMENT PRIMARY KEY,
    pauta_id    INT NOT NULL,
    aluno_id    INT NOT NULL,
    nota_final  DECIMAL(4,1),
    editado_por INT,
    editado_em  DATETIME,
    UNIQUE KEY uq_pauta_aluno (pauta_id, aluno_id),
    FOREIGN KEY (pauta_id)   REFERENCES pautas(id),
    FOREIGN KEY (aluno_id)   REFERENCES utilizadores(id),
    FOREIGN KEY (editado_por) REFERENCES utilizadores(id)
) ENGINE=InnoDB;

-- ============================================================
-- DADOS INICIAIS (seed)
-- ============================================================
INSERT INTO utilizadores (nome, email, password_hash, role) VALUES
('Admin Gestor',    'gestor@academia.pt',     '$2y$12$exampleHashGestor111111111111111111111111111111111111u', 'gestor'),
('Ana Funcionária', 'funcionario@academia.pt', '$2y$12$exampleHashFunc1111111111111111111111111111111111111u', 'funcionario'),
('João Aluno',      'aluno@academia.pt',       '$2y$12$exampleHashAluno111111111111111111111111111111111111u', 'aluno');
-- NOTA: As passwords de exemplo são placeholders. Use seed.php para gerar hashes reais.
