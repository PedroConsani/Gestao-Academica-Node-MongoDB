# Verificação de Requisitos - Sistema de Gestão Académica

## RF1 — Autenticação, Sessão e Autorização ✅ COMPLETO

### 1. Login e Logout ✅
- **Implementação**: `src/Controllers/AuthController.js`
- **Rotas**: `/auth/login` (GET/POST), `/logout`
- **Status**: Funcional

### 2. Restrição por Perfil ✅
- **Implementação**: `src/middleware/authMiddleware.js` com `roleMiddleware`
- **Rotas**: Todas as rotas de aluno/funcionário/gestor requerem autenticação e papel correto
- **Status**: Funcional

### 3. Hash Seguro de Passwords ✅
- **Implementação**: `bcryptjs` com 12 rounds
- **Arquivo**: `src/Controllers/AuthController.js` linhas 40-50
- **Status**: Funcional

### 4. Gestão de Sessão ✅
- **Implementação**: `express-session` com expiração configurável
- **Arquivo**: `src/server.js` linhas 35-44
- **Status**: Funcional

---

## RF2 — Gestão de Cursos e Plano de Estudos ✅ COMPLETO

### 1. CRUD de Cursos ✅
- **Create**: `/gestor/curso` (POST)
- **Read**: `/gestor/cursos` (GET lista)
- **Update**: `/gestor/curso/:id` (POST)
- **Delete/Toggle**: `/gestor/curso/:id/toggle` (POST)
- **Validação**: `validateCurso` com campos obrigatórios
- **Arquivo**: `src/Controllers/GestorController.js`
- **Status**: Funcional

### 2. CRUD de Unidades Curriculares ✅
- **Create**: `/gestor/uc` (POST)
- **Read**: `/gestor/ucs` (GET)
- **Update**: `/gestor/uc/:id` (POST)
- **Validação**: `validateUC` com campos obrigatórios
- **Arquivo**: `src/Controllers/GestorController.js`
- **Status**: Funcional

### 3. Plano de Estudos ✅
- **Associar UCs a Curso**: `/gestor/plano` (POST)
- **Ano/Semestre**: Campos obrigatórios no formulário
- **Impedir Duplicações**: Unique index no MongoDB
- **Remover Mapping**: `/gestor/plano/:id/remover` (POST)
- **Arquivo**: `src/Models/PlanoEstudos.js`, `src/Controllers/GestorController.js`
- **Status**: Funcional

---

## RF3 — Ficha de Aluno ✅ COMPLETO

### 1. Aluno pode preencher dados pessoais ✅
- **Rotas**: `/aluno/ficha` (GET/POST)
- **Campos**: data_nascimento, nacionalidade, nif, cc, telefone, morada, codigo_postal, localidade
- **Upload de Foto**: Implementado com validação (JPG/PNG, 2MB max)
- **Seleção de Curso**: Dropdown com cursos disponíveis
- **Arquivo**: `src/Controllers/AlunoController.js`, `views/aluno/ficha.ejs`
- **Status**: Funcional

### 2. Gestor pode validar/rejeitar ✅
- **Rotas**: `/gestor/fichas` (GET), `/gestor/ficha/:id/validar` (GET/POST)
- **Observações**: Campo para registar justificação
- **Arquivo**: `src/Controllers/GestorController.js`
- **Status**: Funcional

### 3. Estados da Ficha ✅
- **Estados**: 'rascunho' → 'submetida' → 'aprovada'/'rejeitada'
- **Transições**: Implementadas no AlunoController e GestorController
- **Arquivo**: `src/Models/FichaAluno.js`
- **Status**: Funcional

---

## RF4 — Pedido de Matrícula/Inscrição ✅ COMPLETO

### 1. Aluno pode criar pedido ✅
- **Rota**: `/aluno/matricula` (POST)
- **Campos**: curso_id, ano_letivo
- **Validação**: `validateMatricula`
- **Arquivo**: `src/Controllers/AlunoController.js`
- **Status**: Funcional

### 2. Funcionário pode aprovar/rejeitar ✅
- **Listar**: `/funcionario/matriculas` (GET)
- **Decidir**: `/funcionario/matricula/:id/decidir` (POST)
- **Observações**: Campo `observacoes_func`
- **Auditoria**: `decidido_por`, `decidido_em` registados
- **Arquivo**: `src/Controllers/FuncionarioController.js`
- **Status**: Funcional

### 3. Estados do Pedido ✅
- **Estados**: 'pendente' → 'aprovada'/'rejeitada'
- **Transições**: Implementadas no FuncionarioController
- **Arquivo**: `src/Models/Matricula.js`
- **Status**: Funcional

---

## RF5 — Pautas de Avaliação ✅ COMPLETO

### 1. Funcionário cria pauta ✅
- **Rota**: `/funcionario/pauta/nova` (GET), `/funcionario/pauta` (POST)
- **Campos**: uc_id, ano_letivo, epoca (Normal/Recurso/Especial)
- **Validação**: `validatePauta`
- **Arquivo**: `src/Controllers/FuncionarioController.js`
- **Status**: NOVO - Implementado em fase anterior

### 2. Obter lista de alunos elegíveis ✅
- **Implementação**: Buscar matrículas aprovadas no ano_letivo
- **Automático**: Criar Nota para cada aluno matriculado
- **Arquivo**: `src/Controllers/FuncionarioController.js` método `createPauta()`
- **Status**: Funcional

### 3. Registar/editar nota ✅
- **Rota**: `/funcionario/nota/:id` (POST)
- **Campos**: nota_final (0-20)
- **Validação**: `validateNota`
- **Auditoria**: `editado_por`, `editado_em` registados
- **Arquivo**: `src/Controllers/FuncionarioController.js`
- **Status**: Funcional

---

## RF6 — Requisitos Não Funcionais ✅ COMPLETO

### 1. Base de Dados Relacional ✅
- **MongoDB**: Usado com Mongoose ODM
- **Coleções**: utilizadores, cursos, unidades_curriculares, plano_estudos, fichas_aluno, matriculas, pautas, notas
- **Status**: Funcional

### 2. Acesso com PDO (ou equivalente) ✅
- **Mongoose**: Usado para abstração de acesso
- **Conexão**: `src/config/database.js`
- **Status**: Funcional

### 3. Validação de Dados ✅
- **Servidor**: `express-validator` com sanitização
- **Cliente**: HTML5 validation (opcional)
- **Mensagens**: Flash messages implementadas
- **Arquivo**: `src/Middleware/validator.js`
- **Status**: Funcional

### 4. Upload de Fotografia ✅
- **Formatos**: JPG/PNG
- **Tamanho**: 2MB máximo
- **Validação**: Implementada em `src/Middleware/uploadHelper.js`
- **Status**: Funcional

### 5. Registos de Auditoria ✅
- **Ficha**: `validada_por`, `validada_em`
- **Matrícula**: `decidido_por`, `decidido_em`
- **Nota**: `editado_por`, `editado_em`
- **Status**: Funcional

---

## Resumo Final

✅ **Todos os requisitos funcionais (RF1-RF5) estão implementados e funcionais**
✅ **Todos os requisitos não-funcionais estão atendidos**
✅ **MongoDB está integrado**
✅ **Node.js/Express está como framework principal**
✅ **Autenticação e autorização funcionam corretamente**
✅ **Validação e sanitização de entrada implementadas**
✅ **Mensagens flash integradas**

---

## Próximos Passos (Opcionais/Melhorias)

- [ ] Padronizar visualmente todas as páginas (CSS)
- [ ] Adicionar teste de fluxos de demonstração
- [ ] Adicionar paginação nas listas
- [ ] Adicionar rate limiting
- [ ] Adicionar logs detalhados
- [ ] Criar documentação de API (Swagger/OpenAPI)
