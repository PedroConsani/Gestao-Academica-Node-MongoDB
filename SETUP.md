# 🚀 Guia de Instalação e Configuração

## Passo-a-Passo para Iniciar o Projeto Node.js

### ✅ Pré-requisitos

Antes de começar, certifique-se de que tem:

1. **Node.js versão 18+** - [Descarregar](https://nodejs.org)
   ```bash
   node --version  # Verificar versão
   ```

2. **MongoDB versão 6+** - [Guia de instalação](https://docs.mongodb.com/manual/installation/)
   ```bash
   mongod --version  # Verificar versão
   ```

3. **Git** (opcional, para controle de versão)

---

## 📋 Passo 1: Instalar Dependências

Abra a terminal/PowerShell na pasta do projeto:

```bash
cd gestao-academica
npm install
```

Isto irá instalar os pacotes listados em `package.json`:
- Express (framework web)
- Mongoose (acesso a MongoDB)
- EJS (templates HTML)
- bcryptjs (segurança de passwords)
- E outros...

**Pode levar alguns minutos.** Espere até ver `added XX packages`.

---

## 🔧 Passo 2: Configurar Variáveis de Ambiente

### 2.1 Criar arquivo `.env`

Na raiz da pasta, crie um arquivo chamado `.env` (sem extensão):

```bash
# Windows PowerShell
New-Item .env

# Linux/Mac
touch .env
```

Ou copie do arquivo exemplo:

```bash
cp .env.example .env
```

### 2.2 Editar `.env`

Abra o arquivo `.env` em um editor de texto e configure:

```env
# Conexão MongoDB
MONGODB_URI=mongodb://localhost:27017/academic_system

# Servidor
PORT=3000
NODE_ENV=development

# Segurança
SESSION_SECRET=seu_secret_super_seguro_123456789

# Diretório de uploads
UPLOAD_DIR=./public/uploads/photos/
UPLOAD_MAX_SIZE=2097152

# Configurações da app
APP_NAME=Sistema Académico
APP_URL=http://localhost:3000
SESSION_LIFETIME=1800
```

⚠️ **É essencial alterar `SESSION_SECRET` para algo único e seguro!**

---

## 🗄️ Passo 3: Iniciar MongoDB

### Opção A: MongoDB local (recomendado para desenvolvimento)

```bash
# Ubuntu/Linux
sudo systemctl start mongod
sudo systemctl status mongod

# macOS (com Homebrew)
brew services start mongodb-community

# Windows (serviço)
net start MongoDB

# Ou iniciar manualmente
mongod
```

Deve ver: **waiting for connections on port 27017**

### Opção B: MongoDB Atlas (cloud)

Se usar MongoDB na cloud, atualize `MONGODB_URI`:

```env
MONGODB_URI=mongodb+srv://usuario:password@cluster.mongodb.net/academic_system
```

---

## 🌱 Passo 4: Popular Base de Dados

Execute o script de seed para criar dados de teste:

```bash
npm run seed
```

Isto irá:
✅ Criar 5 utilizadores de teste  
✅ Criar 3 cursos  
✅ Criar 6 unidades curriculares  
✅ Criar relacionamentos entre cursos e UCs  

**Utilizadores de teste (após seed):**

| Email | Password | Função |
|-------|----------|--------|
| gestor@academia.pt | gestor123 | Gestor Pedagógico |
| func@academia.pt | func123 | Funcionário |
| pedro@academia.pt | aluno123 | Aluno |
| sofia@academia.pt | aluno123 | Aluno |
| joao@academia.pt | aluno123 | Aluno |

---

## 🚀 Passo 5: Iniciar o Servidor

### Modo Desenvolvimento (recomendado)

```bash
npm run dev
```

Isto inicia o servidor com **auto-reload** quando edita ficheiros.

Deve ver:
```
✅ MongoDB conectado com sucesso
🚀 Servidor rodando em http://localhost:3000
```

### Modo Produção

```bash
npm start
```

---

## 🏠 Passo 6: Acessar a Aplicação

Abra o navegador e vá para:

```
http://localhost:3000
```

Será redirecionado para **login**.

### Teste com um utilizador:

1. Email: `gestor@academia.pt`
2. Password: `gestor123`
3. Clique em **Entrar**

Pronto! 🎉

---

## 📚 O que pode fazer agora?

### Gestor Pedagógico (gestor@academia.pt)
- ✅ Criar e editar cursos
- ✅ Criar e editar unidades curriculares
- ✅ Gerenciar plano de estudos
- ✅ Validar fichas de alunos

### Funcionário (func@academia.pt)
- ✅ Gerir matrículas (aprovar/rejeitar)
- ✅ Lançar notas em pautas

### Aluno (pedro/sofia/joao)
- ✅ Preencher ficha académica
- ✅ Solicitar matrícula
- ✅ Ver minhas notas

---

## 🔍 Verificação Rápida

### Verificar se MongoDB está conectado

1. Abra outro terminal
2. Conecte ao MongoDB:

```bash
mongosh  # ou mongo para versões antigas
```

3. Veja as colecções criadas:

```javascript
use academic_system
show collections
db.utilizadores.find().limit(3)
```

---

## 🐛 Problemas Comuns

### 1. "Erro: Cannot find module 'mongoose'"

**Solução:**
```bash
rm -rf node_modules package-lock.json
npm install
```

### 2. "Erro: ECONNREFUSED 127.0.0.1:27017"

MongoDB não está rodando!

**Solução:**
```bash
# Linux
sudo service mongod start

# macOS
brew services start mongodb-community

# Windows
net start MongoDB
```

### 3. "Erro: Port 3000 already in use"

Outro processo está usando a porta 3000.

**Solução:**
```bash
# Mudar a porta em .env
PORT=3001

# Ou matar o processo (Linux/Mac)
lsof -i :3000
kill -9 <PID>
```

### 4. "Ficheiros de upload não funcionam"

Criar diretório:
```bash
mkdir -p public/uploads/photos
```

---

## 💾 Estrutura de Ficheiros Criada

```
gestao-academica/
├── src/
│   ├── config/          → Configurações
│   ├── controllers/      → Lógica da app
│   ├── middleware/       → Middlewares
│   ├── models/           → Schemas MongoDB
│   ├── routes/           → Rotas
│   └── server.js         → Arquivo principal
├── views/                → Templates HTML (EJS)
├── public/               → CSS, imagens, uploads
├── database/
│   └── seed.js           → Script de populate
├── package.json          → Dependências
├── .env                  → Variáveis (não versionado)
└── README.md             → Documentação
```

---

## 🎯 Próximos Passos

1. ✅ **Instalar e rodar** - Siga este guia
2. 📖 **Explorar código** - Veja `src/server.js` para entender a estrutura
3. 🎨 **Customizar views** - Edite ficheiros em `views/`
4. 🔒 **Produção** - Leia `MIGRATION_GUIDE.md` > Segurança
5. 🚀 **Deploy** - Considere Heroku, Render, Railway, etc.

---

## 📮 Suporte

Se encontrar problemas:

1. Verifique os logs do console
2. Consulte `MIGRATION_GUIDE.md` para mais info
3. Verifique se MongoDB está rodando: `sudo systemctl status mongod`
4. Limpe npm: `npm cache clean --force`

---

**Bom desenvolvimento!** 🚀
