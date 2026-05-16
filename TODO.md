# TODO - Correção de regras da Ficha do Aluno e Filtro de UCs na Criação de Pautas

## Já feito
- [x] Ficha do Aluno: corrigir flow fetch/JSON e evitar submission incompleta
  - updateFicha / submitFicha devolvem JSON quando `Accept: application/json`
  - submitFicha bloqueia se `curso_id` estiver vazio
  - front-end `views/aluno/ficha.ejs` não depende de `response.redirected`
- [x] Ficha do Aluno: corrigir parse JSON em erros HTML
  - front-end usa `response.text()` e tenta `JSON.parse` apenas se possível
- [x] Ficha do Aluno: corrigir `handleValidationErrors` para responder JSON em modo fetch

## Próximo (novo pedido)
- [x] Pautas: ao selecionar `curso_id`, filtrar as UCs para mostrar apenas UCs presentes no `PlanoEstudos` desse curso
  - [x] atualizar `FuncionarioController.showPautaNova` para não carregar UCs fixas (JS faz o filtro)
  - [x] atualizar `views/funcionario/pauta-nova.ejs` para filtrar dinamicamente quando o curso mudar
  - [x] criar endpoint `GET /funcionario/ucs-por-curso` para obter UCs permitidas via `PlanoEstudos`


