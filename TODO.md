# Fix Matrículas "aluno_email" NULL Error - Progress Tracker

## ✅ TODO Steps (Priority 1 - Approved Plan)

- [x] **1. Create TODO.md** - Track progress (current)
- [x] **2. Edit views/funcionario/matriculas.php** - Add null coalescing `e($m['aluno_email'] ?? '')`
</xai:function_call >

<xai:function_call name="edit_file">
<parameter name="path">c:/xampp/htdocs/TP1-Gestao-Academica/TODO.md
- [x] **3. Edit src/Models/MatriculaModel.php** - Add `COALESCE(a.email, '')` to all() and allPendentes() queries  
- [ ] **4. Test: Navigate to /funcionario/matriculas.php?filtro=pendente** - Verify no crash
- [ ] **5. Priority 2: Add logging to controller + make e() null-safe**
- [ ] **6. DB inspection: Check for orphan aluno_id**
- [ ] **7. Complete: attempt_completion**

**Next Step:** Test /funcionario/matriculas.php?filtro=pendente

