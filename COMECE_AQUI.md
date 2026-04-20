# 🎯 COMECE AQUI - Guia de Início

## Bem-vindo! 👋

Seu projeto PHP foi totalmente migrado para **Node.js + MongoDB**.

### ⚠️ Importante: Leia Primeiro!

Tem **3 opções de qual documentação ler**, dependendo do que quer fazer:

---

## 📖 Escolha Seu Caminho

### 🚀 **Opção 1: Quero começar AGORA**
→ Leia **[SETUP.md](SETUP.md)** (Guia passo-a-passo)
- Instalação de dependências
- Configuração do .env
- Iniciar o servidor
- ⏱️ Tempo: **5-10 minutos**

---

### 📚 **Opção 2: Quero entender a estrutura**
→ Leia **[MIGRATION_GUIDE.md](MIGRATION_GUIDE.md)** (Documentação completa)
- Estrutura do projeto
- Documentação de modelos
- API endpoints
- Segurança e deployment
- ⏱️ Tempo: **20-30 minutos**

---

### ⚡ **Opção 3: Quero ter referência rápida**
→ Leia **[COMANDOS.md](COMANDOS.md)** (Cheat sheet)
- Comandos npm
- Comandos MongoDB
- Troubleshooting rápido
- ⏱️ Tempo: **2-3 minutos**

---

## 🏃 TL;DR - Muito Rápido

Se está apressado, execute isto:

```bash
# 1. Instalar
npm install

# 2. Seed (criar dados de teste)
npm run seed

# 3. Iniciar
npm run dev

# 4. Abrir navegador
# http://localhost:3000

# 5. Login
# Email: gestor@academia.pt
# Password: gestor123
```

**Pronto!** ✅

---

## 📋 Checklist - O Que Vai Encontrar

### ✅ Código Pronto
- [x] 8 Modelos MongoDB (Schemas)
- [x] 4 Controllers com toda lógica
- [x] 4 Rotas organizadas
- [x] 15+ Views (Templates EJS)
- [x] 3 Middleware (Auth, Validation, Upload)

### ✅ Dados de Teste
- [x] 5 Utilizadores (Gestor, Funcionário, 3 Alunos)
- [x] 3 Cursos
- [x] 6 Unidades Curriculares
- [x] Relacionamentos entre todos

### ✅ Documentação
- [x] SETUP.md - Como instalar
- [x] MIGRATION_GUIDE.md - Documentação técnica
- [x] COMANDOS.md - Referência rápida
- [x] Este arquivo - Índice

---

## 🔍 Estrutura Principal

```
src/
├── models/      → Definição de dados (MongoDB)
├── controllers/ → Lógica da aplicação
├── routes/      → Rotas e URLs
└── middleware/  → Autenticação, validação, upload

views/
├── auth/        → Login e Registro
├── aluno/       → Dashboard e resquições do aluno
├── funcionario/ → Gerir matrículas e pautas
└── gestor/      → Gerenciar cursos e fichas

database/
└── seed.js      → Script que popula com dados
```

---

## 🎓 3 Papéis de Utilizador

### 1. Gestor Pedagógico (gestor@academia.pt)
- Criar cursos e disciplinas
- Gerir plans de estudos
- Validar fichas de alunos
- Ver dashboard com estatísticas

### 2. Funcionário (func@academia.pt)
- Aprovar/rejeitar matrículas
- Lançar notas
- Ver dashboard

### 3. Aluno (pedro/sofia/joao)
- Preencher ficha académica
- Solicitar matrícula
- Ver notas

---

## 🚢 Próximos Passos Recomendados

1. **Hoje**: Execute [SETUP.md](SETUP.md) e teste no local
2. **Amanhã**: Leia [MIGRATION_GUIDE.md](MIGRATION_GUIDE.md) para entender detalhes
3. **Depois**: Customizar views e lógica conforme necessário
4. **Produção**: Fazer deploy (Heroku, Railway, etc.)

---

## ❓ FAQ Rápido

**P: Preciso do MySQL?**
→ Não! MongoDB is incluído.

**P: Preciso do Apache/XAMPP?**
→ Não! Node.js é standalone.

**P: Qual é a senha padrão?**
→ Ver tabela de utilizadores acima.

**P: Como mudo para outra porta?**
→ Edit `.env`: `PORT=3001`

**P: Como resetar dados?**
→ `npm run seed` (limpa e repopula)

---

## 🆘 Não Consegue Começar?

**Problema**: Node.js não está instalado
→ Download em https://nodejs.org (versão 18+)

**Problema**: MongoDB não está instalado
→ Download em https://www.mongodb.com/try/download

**Problema**: npm install falha
→ Tente: `npm cache clean --force && npm install`

**Problema**: Porta 3000 já está em uso
→ Mude em `.env` para `PORT=3001`

Mais detalhes: Ver [SETUP.md](SETUP.md) > Troubleshooting

---

## 💾 Ficheiros Novos Criados

```
src/                     → Novo (código Node.js)
views/                   → Novo (templates EJS)
.env.example             → Novo
package.json             → Novo (dependencies)
SETUP.md                 → Novo (instruções)
MIGRATION_GUIDE.md       → Novo (documentação)
COMANDOS.md              → Novo (referência)
README_NODEJS.md         → Novo (readme)
COMECE_AQUI.md           → Este arquivo
database/seed.js         → Novo (populate DB)
```

---

## 🎉 Último passo

Leia **[SETUP.md](SETUP.md)** agora mesmo! 

É só 10 minutos de leitura + 5 minutos de instalação.

Depois, seu projeto está 100% funcional.

---

**Boa sorte!** 🚀✨

_Qualquer dúvida, consulte MIGRATION_GUIDE.md ou COMANDOS.md_
