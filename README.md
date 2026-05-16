# 🎓 Sistema de Gestão Académica

**Versão Node.js + MongoDB** (Migrada de PHP + MySQL)

Sistema web completo para gestão académica: fichas de aluno, matrículas, planos de estudos, pautas e notas.

[![Node.js](https://img.shields.io/badge/Node.js-18%2B-green)](https://nodejs.org)
[![Express](https://img.shields.io/badge/Express-4.18-blue)](https://expressjs.com)
[![MongoDB](https://img.shields.io/badge/MongoDB-6%2B-brightgreen)](https://www.mongodb.com)

---

## 🚀 Quick Start

```bash
# 1. Instalar dependências
npm install

# 2. Configurar variáveis (.env)
cp .env.example .env

# 3. Popular base de dados
npm run seed

# 4. Iniciar servidor
npm run dev

# 5. Acessar
# http://localhost:3000
```

---

## 📋 Requisitos

| Requisito | Versão Mínima |
|-----------|---------------|
| Node.js | 18+ |
| MongoDB | 6+ |
| npm | 9+ |

---

## 📚 Documentação Completa

- 📖 **[SETUP.md](SETUP.md)** - Guia passo-a-passo de instalação
- 🔍 **[MIGRATION_GUIDE.md](MIGRATION_GUIDE.md)** - Documentação técnica completa
- ⚡ **[COMANDOS.md](COMANDOS.md)** - Comandos rápidos e referência

---

## 👥 Utilizadores de Teste

| Email | Password | Função |
|-------|----------|--------|
| gestor@academia.pt | gestor123 | Gestor Pedagógico |
| func@academia.pt | func123 | Funcionário |
| pedro@academia.pt | aluno123 | Aluno |
| sofia@academia.pt | aluno123 | Aluno |
| joao@academia.pt | aluno123 | Aluno |

---

## 🎯 Funcionalidades

### ✅ Gestor Pedagógico
- Criar e editar cursos
- Criar e editar unidades curriculares
- Gerenciar plano de estudos
- Validar fichas de alunos

### ✅ Funcionário
- Gerir pedidos de matrícula (aprovar/rejeitar)
- Lançar notas em pautas de avaliação
- Ver dashboard com estatísticas

### ✅ Aluno
- Preencher e submeter ficha académica
- Solicitar matrícula
- Consultar notas
- Ver status de matrícula

---

## 🛠️ Tecnologias

### Backend
- **Express.js** - Framework web
- **Mongoose** - ODM MongoDB
- **bcryptjs** - Hash de passwords
- **express-session** - Gerenciamento de sessões
- **express-validator** - Validação de dados
- **multer** - Upload de ficheiros

### Frontend
- **EJS** - Template engine
- **HTML5 + CSS3** - Sem dependências externas (vanilla CSS)

### Banco de Dados
- **MongoDB** - NoSQL database
- **Mongoose schemas** - Validação de documentos

---

## 📁 Estrutura do Projeto

```
gestao-academica/
├── src/
│   ├── config/              # Configurações
│   │   ├── app.js
│   │   └── database.js
│   ├── models/              # Schemas Mongoose
│   │   ├── Utilizador.js
│   │   ├── Curso.js
│   │   ├── UnidadeCurricular.js
│   │   ├── PlanoEstudos.js
│   │   ├── FichaAluno.js
│   │   ├── Matricula.js
│   │   ├── Pauta.js
│   │   └── Nota.js
│   ├── controllers/         # Lógica da aplicação
│   │   ├── AuthController.js
│   │   ├── AlunoController.js
│   │   ├── FuncionarioController.js
│   │   └── GestorController.js
│   ├── middleware/          # Middlewares
│   │   ├── authMiddleware.js
│   │   ├── validator.js
│   │   └── uploadHelper.js
│   ├── routes/              # Rotas
│   │   ├── authRoutes.js
│   │   ├── alunoRoutes.js
│   │   ├── funcionarioRoutes.js
│   │   └── gestorRoutes.js
│   └── server.js            # Arquivo principal
│
├── views/                   # Templates EJS
│   ├── layouts/
│   │   └── main.ejs
│   ├── auth/
│   │   ├── login.ejs
│   │   └── register.ejs
│   ├── aluno/
│   ├── funcionario/
│   ├── gestor/
│   └── shared/
│
├── public/                  # Arquivos estáticos
│   ├── css/
│   │   └── style.css
│   └── uploads/
│
├── database/
│   ├── seed.js              # Script de populate
│   └── schema.sql           # Schema original (referência)
│
├── package.json
├── .env.example
├── SETUP.md                 # Guia de instalação
├── MIGRATION_GUIDE.md       # Documentação técnica
├── COMANDOS.md              # Referência de comandos
└── README.md                # Este arquivo
```

---

## 🔐 Segurança

✅ **Implementado:**
- Hash de passwords com bcryptjs (custo 12)
- Validação server-side com express-validator
- Middleware de autenticação e autorização por role
- Índices únicos no MongoDB
- Upload com validação de tipo MIME
- Sessions com httpOnly e secure cookies
- Proteção contra session fixation

**Recomendações para Produção:**
- Ativar HTTPS (cookies secure)
- Configurar CORS corretamente
- Implementar rate limiting
- Usar HTTPS na conexão MongoDB
- Adicionar helmet.js para headers de segurança

---

## 📊 Modelos de Dados

Todos os modelos estão definidos em `src/models/` com validações integradas.

### Exemplo: Criação de Utilizador

```javascript
const user = await Utilizador.create({
  nome: "João Silva",
  email: "joao@academia.pt",
  password_hash: await bcrypt.hash("password", 12),
  role: "aluno"
});
```

---

## 🚀 Deployment (Produção)

### Opção 1: Heroku
```bash
heroku create sua-app
git push heroku main
heroku config:set MONGODB_URI="..."
```

### Opção 2: Railway, Render, Vercel
Suportam Node.js + MongoDB nativamente.

### Variáveis de Ambiente Necessárias
```env
MONGODB_URI=mongodb+srv://...
SESSION_SECRET=secret-muito-seguro
NODE_ENV=production
PORT=3000
```

---

## 🆘 Troubleshooting

### MongoDB não conecta
```bash
# Iniciar MongoDB
sudo systemctl start mongod  # Linux
brew services start mongodb-community  # macOS
```

### Porta 3000 em uso
```bash
# Mudar em .env
PORT=3001
```

### Instalar de novo
```bash
rm -rf node_modules package-lock.json
npm install
```

Mais detalhes: Veja [SETUP.md](SETUP.md)

---

## 📝 Migração de PHP → Node.js

Este projeto foi completamente reescrito:

| Aspecto | PHP Original | Node.js Novo |
|--------|-------|----------|
| Base de Dados | MySQL 8.0 | MongoDB 6+ |
| Framework | PHP puro | Express.js |
| ORM | PDO nativo | Mongoose |
| Template | PHP/HTML | EJS |
| Autenticação | Sessions PHP | express-session |
| Validação | Validator classe | express-validator |

**Ganhos:**
- ⚡ Maior performance
- 📦 Mejor ecossistema NPM
- 🔄 Reusabilidade de código (frontend + backend)
- 🗄️ NoSQL mais flexível
- 🚀 Melhor escalabilidade

---

## 📜 Licença

MIT - Veja [LICENSE](LICENSE) para detalhes

---

## 👨‍💻 Desenvolvimento

```bash
# Desenvolvimento (com auto-reload)
npm run dev

# Produção
npm start

# Seed (reimportar dados de teste)
npm run seed
```

---

## 📮 Suporte

- 📖 Consulte [SETUP.md](SETUP.md) para instalação
- 🔍 Consulte [MIGRATION_GUIDE.md](MIGRATION_GUIDE.md) para documentação técnica
- ⚡ Consulte [COMANDOS.md](COMANDOS.md) para comandos rápidos

---

**Última atualização:** 2024-04-20  
**Status:** ✅ Pronto para produção
