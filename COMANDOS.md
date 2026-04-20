# ⚡ Comandos Rápidos

## 1️⃣ Primeira Vez (Setup Inicial)

```bash
# Instalar dependências
npm install

# Criar arquivo .env
cp .env.example .env

# Editar .env (se necessário)
# nano .env  (Linux/Mac)
# notepad .env  (Windows)

# Popular banco de dados com dados de teste
npm run seed

# Iniciar servidor
npm run dev
```

## 2️⃣ Próximas Vezes (Desenvolvimento)

```bash
# Iniciar servidor (desenvolvimento com auto-reload)
npm run dev

# Iniciar servidor (produção)
npm start

# Rodar seed novamente (limpa e repopula)
npm run seed
```

## 3️⃣ Verificação e Manutenção

```bash
# Verificar se MongoDB está conectado
mongosh
use academic_system
show collections

# Ver utilizadores criados
db.utilizadores.find().pretty()

# Limpar colecções (CUIDADO!)
db.utilizadores.deleteMany({})
```

## 4️⃣ Troubleshooting

```bash
# Limpeza completa (reinstalar)
rm -rf node_modules package-lock.json
npm install

# Verificar MongoDB
mongod --version
sudo systemctl start mongod  # Linux

# Verificar porta 3000
lsof -i :3000  # Linux/Mac
netstat -ano | findstr :3000  # Windows

# Matar processo na porta 3000
kill -9 <PID>  # Linux/Mac
taskkill /PID <PID> /F  # Windows
```

## 5️⃣ URLs Importantes

- 🏠 **Home**: http://localhost:3000
- 🔐 **Login**: http://localhost:3000/auth/login
- 📝 **Register**: http://localhost:3000/auth/register

## 6️⃣ Credenciais de Teste

```
Gestor: gestor@academia.pt / gestor123
Funcionário: func@academia.pt / func123
Aluno: pedro@academia.pt / aluno123 (ou sofia/joao)
```

---

**Ler mais em:** SETUP.md e MIGRATION_GUIDE.md
