# Sistema de Gestão Académica

Aplicação web em PHP com MySQL/MariaDB para suporte aos Serviços Académicos e Gestão Pedagógica.

---

## Requisitos

- PHP 8.1+
- MySQL 8.0+ / MariaDB 10.6+
- Apache com `mod_rewrite` ativo
- Extensões PHP: `pdo_mysql`, `fileinfo`, `mbstring`

---

## Instalação

### 1. Configurar Base de Dados

```bash
mysql -u root -p < database/schema.sql
```

### 2. Popular com dados iniciais

```bash
php database/seed.php
```

Utilizadores criados:

| Email                    | Password   | Perfil       |
|--------------------------|------------|--------------|
| gestor@academia.pt       | gestor123  | Gestor       |
| funcionario@academia.pt  | func123    | Funcionário  |
| aluno@academia.pt        | aluno123   | Aluno        |

### 3. Configurar ligação à BD

Editar `config/database.php`:

```php
define('DB_HOST', 'localhost');
define('DB_NAME', 'academic_system');
define('DB_USER', 'root');
define('DB_PASS', 'a_sua_password');
```

### 4. Configurar URL da aplicação

Editar `config/app.php`:

```php
define('APP_URL', 'http://localhost/academic_system/public');
```

### 5. Permissões da pasta de uploads

```bash
chmod 755 public/uploads/photos/
```

---

## Estrutura do Projeto

```
academic_system/
├── config/
│   ├── app.php             # Constantes e configurações gerais
│   ├── bootstrap.php       # Arranque: sessão, autoloader, helpers
│   └── database.php        # Ligação PDO à BD
│
├── database/
│   ├── schema.sql          # Script de criação das tabelas
│   └── seed.php            # Dados iniciais (utilizadores de teste)
│
├── src/
│   ├── Controllers/
│   │   └── AuthController.php
│   ├── Middleware/
│   │   ├── UploadHelper.php
│   │   └── Validator.php
│   └── Models/
│       ├── CursoModel.php
│       ├── FichaAlunoModel.php
│       ├── MatriculaModel.php
│       ├── PautaModel.php
│       ├── PlanoEstudosModel.php
│       ├── UCModel.php
│       └── UtilizadorModel.php
│
├── views/
│   ├── layouts/
│   │   └── main.php        # Layout HTML principal
│   ├── auth/
│   │   └── login.php
│   ├── aluno/
│   │   ├── dashboard.php
│   │   ├── ficha.php
│   │   ├── matriculas.php
│   │   └── matricula-nova.php
│   ├── funcionario/
│   │   ├── dashboard.php
│   │   ├── matriculas.php
│   │   ├── matricula-decidir.php
│   │   ├── pautas.php
│   │   ├── pauta-nova.php
│   │   └── pauta-notas.php
│   ├── gestor/
│   │   ├── dashboard.php
│   │   ├── cursos.php
│   │   ├── curso-form.php
│   │   ├── plano-estudos.php
│   │   ├── ucs.php (inline)
│   │   ├── uc-form.php
│   │   ├── fichas.php
│   │   └── ficha-validar.php
│   └── shared/
│       └── 403.php
│
└── public/                 # Único diretório exposto ao servidor web
    ├── .htaccess
    ├── index.php
    ├── login.php
    ├── logout.php
    ├── css/
    │   └── style.css
    ├── uploads/
    │   └── photos/         # Fotografias dos alunos (gitignore)
    ├── aluno/
    │   ├── dashboard.php
    │   ├── ficha.php
    │   ├── matriculas.php
    │   └── matricula-nova.php
    ├── funcionario/
    │   ├── dashboard.php
    │   ├── matriculas.php
    │   ├── matricula-decidir.php
    │   ├── pautas.php
    │   ├── pauta-nova.php
    │   └── pauta-notas.php
    └── gestor/
        ├── dashboard.php
        ├── cursos.php
        ├── curso-novo.php
        ├── curso-editar.php
        ├── curso-toggle.php
        ├── ucs.php
        ├── uc-nova.php
        ├── uc-editar.php
        ├── uc-toggle.php
        ├── plano-estudos.php
        ├── plano-remover.php
        ├── fichas.php
        └── ficha-validar.php
```

---

## Arquitetura

O projeto segue um padrão **MVC simplificado sem framework**:

- **`config/`** — configurações e bootstrap (inicialização da app)
- **`src/Models/`** — acesso à base de dados via PDO (uma classe por entidade)
- **`src/Controllers/`** — lógica de controlo (atualmente AuthController)
- **`src/Middleware/`** — validação de dados e gestão de uploads
- **`views/`** — templates PHP puros (nunca acedidos diretamente pelo browser)
- **`public/`** — único diretório exposto; cada ficheiro carrega o bootstrap, valida sessão/perfil, chama o modelo e inclui a view

---

## Fluxos Principais

### Ficha de Aluno
`Rascunho → Submetida → Aprovada | Rejeitada`

### Matrícula
`Pendente → Aprovada | Rejeitada`

### Pauta
`Criada (Aberta) → [notas lançadas] → Fechada`

---

## Segurança

- Passwords com `password_hash()` (bcrypt, cost 12)
- Sessões com expiração e regeneração de ID no login
- Acesso restringido por perfil em cada entry point
- Uploads validados por MIME type real (não apenas extensão)
- Toda a saída HTML escapada com `htmlspecialchars()`
- Queries parametrizadas com PDO (sem SQL injection)
- Cabeçalhos HTTP de segurança via `.htaccess`
