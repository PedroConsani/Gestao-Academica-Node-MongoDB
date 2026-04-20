# 🎓 Sistema de Gestão Académica - Node.js + MongoDB

Migração completa de um projeto PHP/MySQL para **Node.js com Express** e **MongoDB**.

[![Node.js](https://img.shields.io/badge/Node.js-18%2B-green)](https://nodejs.org)
[![Express](https://img.shields.io/badge/Express-4.18-blue)](https://expressjs.com)
[![MongoDB](https://img.shields.io/badge/MongoDB-6%2B-brightgreen)](https://www.mongodb.com)

---

## 🚀 Início Rápido

### 1. Pré-requisitos

- **Node.js** 18+ ([download](https://nodejs.org))
- **MongoDB** 6+ ([install guide](https://docs.mongodb.com/manual/installation/))
- **npm** ou **yarn**

### 2. Instalar Dependências

```bash
cd gestao-academica
npm install
```

### 3. Configurar Variáveis de Ambiente

Crie um arquivo `.env` na raiz do projeto:

```bash
cp .env.example .env
```

Edite `.env` com sua configuração:

```env
# MongoDB
MONGODB_URI=mongodb://localhost:27017/academic_system

# Server
PORT=3000
NODE_ENV=development

# Session
SESSION_SECRET=seu_secret_muito_seguro_aqui_123456

# Upload
UPLOAD_MAX_SIZE=2097152
UPLOAD_DIR=./public/uploads/photos/

# App
APP_NAME=Sistema Académico
APP_URL=http://localhost:3000
SESSION_LIFETIME=1800
```

### 4. Inicializar Base de Dados

```bash
# Executar seed com dados de teste
npm run seed
```

**Utilizadores de teste criados:**

| Email | Senha | Função |
|-------|-------|--------|
| gestor@academia.pt | gestor123 | Gestor Pedagógico |
| func@academia.pt | func123 | Funcionário |
| pedro@academia.pt | aluno123 | Aluno |
| sofia@academia.pt | aluno123 | Aluno |
| joao@academia.pt | aluno123 | Aluno |

### 5. Iniciar Servidor

```bash
# Produção
npm start

# Desenvolvimento (com auto-reload)
npm run dev
```

Acesse em: **http://localhost:3000**

---

## 📁 Estrutura do Projeto

```
gestao-academica/
├── src/
│   ├── config/
│   │   ├── app.js              # Configurações da aplicação
│   │   └── database.js         # Conexão MongoDB
│   ├── models/                 # Schemas Mongoose
│   │   ├── Utilizador.js
│   │   ├── Curso.js
│   │   ├── UnidadeCurricular.js
│   │   ├── PlanoEstudos.js
│   │   ├── FichaAluno.js
│   │   ├── Matricula.js
│   │   ├── Pauta.js
│   │   └── Nota.js
│   ├── controllers/            # Lógica de negócio
│   │   ├── AuthController.js
│   │   ├── AlunoController.js
│   │   ├── FuncionarioController.js
│   │   └── GestorController.js
│   ├── middleware/             # Middlewares Express
│   │   ├── authMiddleware.js
│   │   ├── validator.js
│   │   └── uploadHelper.js
│   ├── routes/                 # Rotas
│   │   ├── authRoutes.js
│   │   ├── alunoRoutes.js
│   │   ├── funcionarioRoutes.js
│   │   └── gestorRoutes.js
│   └── server.js               # Arquivo principal
├── views/                      # Templates EJS
│   ├── layouts/
│   ├── auth/
│   ├── aluno/
│   ├── funcionario/
│   ├── gestor/
│   └── shared/
├── public/                     # Arquivos estáticos
│   ├── css/
│   └── uploads/
├── database/
│   ├── seed.js                 # Script de populate
│   └── schema.sql              # Schema original (referência)
├── package.json
├── .env.example
└── README.md
```

---

## 🗄️ Modelos (Colecções MongoDB)

### Utilizador
```javascript
{
  nome: String,
  email: String (único),
  password_hash: String,
  role: 'aluno' | 'funcionario' | 'gestor',
  ativo: Boolean,
  criado_em: Date,
  atualizado_em: Date
}
```

### Curso
```javascript
{
  nome: String,
  codigo: String (único),
  descricao: String,
  duracao_anos: Number,
  ativo: Boolean,
  criado_por: ObjectId (Utilizador),
  criado_em: Date,
  atualizado_em: Date
}
```

### UnidadeCurricular
```javascript
{
  nome: String,
  codigo: String (único),
  descricao: String,
  creditos: Number,
  ativo: Boolean,
  criado_por: ObjectId (Utilizador),
  criado_em: Date
}
```

### PlanoEstudos
```javascript
{
  curso_id: ObjectId (Curso),
  uc_id: ObjectId (UnidadeCurricular),
  ano: Number (1-5),
  semestre: Number (1|2),
  obrigatoria: Boolean
}
```

### FichaAluno
```javascript
{
  aluno_id: ObjectId (Utilizador),
  curso_id: ObjectId (Curso),
  data_nascimento: Date,
  nacionalidade: String,
  nif: String,
  cc: String,
  telefone: String,
  morada: String,
  codigo_postal: String,
  localidade: String,
  foto_path: String,
  estado: 'rascunho' | 'submetida' | 'aprovada' | 'rejeitada',
  observacoes: String,
  submetida_em: Date,
  validada_por: ObjectId (Utilizador),
  validada_em: Date,
  criado_em: Date,
  atualizado_em: Date
}
```

### Matricula
```javascript
{
  aluno_id: ObjectId (Utilizador),
  curso_id: ObjectId (Curso),
  ano_letivo: String,
  observacoes_aluno: String,
  estado: 'pendente' | 'aprovada' | 'rejeitada',
  observacoes_func: String,
  decidido_por: ObjectId (Utilizador),
  decidido_em: Date,
  criado_em: Date
}
```

---

## 📡 API Endpoints Principais

### Autenticação
- `POST /auth/login` - Fazer login
- `POST /auth/register` - Registar novo utilizador
- `GET /logout` - Fazer logout

### Aluno
- `GET /aluno/dashboard` - Dashboard
- `GET /aluno/ficha` - Ver/Editar ficha
- `POST /aluno/ficha` - Guardar ficha
- `GET /aluno/matriculas` - Listar matrículas
- `POST /aluno/matricula` - Solicitar matrícula

### Funcionário
- `GET /funcionario/dashboard` - Dashboard
- `GET /funcionario/matriculas` - Gerir matrículas
- `POST /funcionario/matricula/:id/decidir` - Aprovar/Rejeitar matrícula

### Gestor
- `GET /gestor/cursos` - Listar cursos
- `POST /gestor/curso` - Criar curso
- `GET /gestor/ucs` - Listar UCs
- `POST /gestor/uc` - Criar UC
- `GET /gestor/plano-estudos` - Gerir plano
- `GET /gestor/fichas` - Validar fichas do aluno

---

## 🔐 Segurança

✅ **Implementado:**
- ✓ Hashing de passwords com bcryptjs
- ✓ Validação server-side com express-validator
- ✓ Middleware de autenticação e autorização
- ✓ Índices únicos no MongoDB
- ✓ Upload de ficheiros com validação de tipo
- ✓ Sessions com segurança httpOnly

### Melhorias Recomendadas para Produção
```javascript
// Ativar força HTTPS
cookie: { 
  secure: true,      // Apenas HTTPS
  httpOnly: true,    // Sem acesso JavaScript
  sameSite: 'strict' // Proteção CSRF
}

// Rate limiting
import rateLimit from 'express-rate-limit';
const limiter = rateLimit({
  windowMs: 15 * 60 * 1000,
  max: 100
});
app.use('/auth/', limiter);

// CORS
import cors from 'cors';
app.use(cors({ origin: 'https://seu-dominio.pt' }));
```

---

## 🧪 Teste Rápido

```bash
# 1. Terminal 1: Iniciar MongoDB
mongod

# 2. Terminal 2: Iniciar servidor
npm run dev

# 3. Terminal 3: Testar login
curl -X POST http://localhost:3000/auth/login \
  -H "Content-Type: application/json" \
  -d '{"email":"gestor@academia.pt","password":"gestor123"}'
```

---

## 📦 Dependências

| Pacote | Versão | Uso |
|--------|--------|-----|
| express | ^4.18 | Framework web |
| mongoose | ^8.1 | ODM MongoDB |
| bcryptjs | ^2.4 | Hash de passwords |
| express-session | ^1.17 | Gerenciamento de sessões |
| express-validator | ^7.0 | Validação de dados |
| multer | ^1.4 | Upload de ficheiros |
| dotenv | ^16.4 | Variáveis de ambiente |
| ejs | ^3.1 | Template engine |
| nodemon | ^3.0 | Dev - auto-reload |

---

## 🔄 Migração do MySQL para MongoDB

### Diferenças principais

| MySQL | MongoDB |
|-------|---------|
| Tabelas | Colecções |
| Registos | Documentos |
| Foreign Keys | Referências (ObjectId) |
| JOIN | Populate (Mongoose) |
| ENUM | String com validação |
| AUTO_INCREMENT | ObjectId automático |

### Exemplo: Buscar com relacionamento

**MySQL (PHP):**
```php
$sql = "SELECT u.*, f.* FROM utilizadores u 
        LEFT JOIN fichas_aluno f ON u.id = f.aluno_id 
        WHERE u.id = ?";
```

**MongoDB (Node.js):**
```javascript
const user = await Utilizador.findById(userId)
  .populate('fichas_aluno');
```

---

## 🐛 Troubleshooting

### Erro: "MongooseError: Cannot find module"
```bash
npm install
```

### Erro: "ECONNREFUSED" (MongoDB não conecta)
```bash
# Verificar se MongoDB está rodando
mongod --version

# Ubuntu/Linux
sudo systemctl start mongod

# macOS
brew services start mongodb-community

# Windows
net start MongoDB
```

### Erro: "PORT already in use"
```bash
# Mudar porta em .env
PORT=3001
```

### Erro: "Upload failed"
```bash
# Criar diretório
mkdir -p public/uploads/photos
chmod 755 public/uploads/photos
```

---

## 📝 Licença

MIT

---

## 👨‍💻 Autor

Sistema de Gestão Académica - Versão Node.js
Migrado de PHP para Node.js + MongoDB em 2024
