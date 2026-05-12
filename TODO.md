- [ ] (já feito) Investigar fluxo do formulário e rota de criação de pauta
- [ ] Atualizar `src/Controllers/FuncionarioController.js#createPauta`
  - [x] Logar motivo real do erro no `catch` e melhorar flash
  - [x] Tornar criação de `Nota`s idempotente (evitar erro por índice unique)
  - [x] Adicionar validação defensiva para `matricula.aluno_id`
- [ ] Testar: criar pauta (1ª vez e repetição imediata)
- [ ] Se necessário, ajustar modelos/índices


