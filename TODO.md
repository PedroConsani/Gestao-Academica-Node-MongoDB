# TODO - Visualização de Dados após Criar Nova Pauta

- [ ] Entender como a navegação ocorre atualmente (create pauta -> redirect para /funcionario/pauta/:id/notas) e identificar o melhor local para exibir o resumo da pauta.
- [ ] Criar/ajustar uma visualização (feed/resumo) na página de "Editar Notas" logo após criar a pauta.
- [ ] Exibir no EJS (pauta-notas.ejs) um bloco com: UC, Curso, Ano letivo, Época e quantidade de alunos/notas criadas.
- [ ] Ajustar o controller (createPauta) para guardar na sessão um payload com o resumo (ou então calcular no showPautaNotas) e limpar após render.
- [ ] Garantir que também funciona quando a pauta já existia (pautaExistente): mostrar quantas notas foram garantidas.
- [ ] Validar fluxos: pauta criada nova, pauta já existia, pauta com 0 alunos.

