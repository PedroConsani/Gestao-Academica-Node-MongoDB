import express from 'express';
import * as gestorController from '../controllers/GestorController.js';

const router = express.Router();

router.get('/dashboard', gestorController.dashboard);

// Cursos
router.get('/cursos', gestorController.listCursos);
router.get('/curso/novo', gestorController.showCursoNovo);
router.post('/curso', gestorController.createCurso);
router.get('/curso/:id/editar', gestorController.showCursoEditar);
router.post('/curso/:id', gestorController.updateCurso);
router.post('/curso/:id/toggle', gestorController.toggleCurso);

// Unidades Curriculares
router.get('/ucs', gestorController.listUCs);
router.get('/uc/nova', gestorController.showUCNova);
router.post('/uc', gestorController.createUC);
router.post('/uc/:id', gestorController.updateUC);

// Plano de Estudos
router.get('/plano-estudos', gestorController.listPlanoEstudos);
router.get('/plano/novo', gestorController.showPlanoNovo);
router.post('/plano', gestorController.createPlanoEstudos);
router.post('/plano/:id/remover', gestorController.removePlanoEstudos);

// Fichas de Aluno
router.get('/fichas', gestorController.listFichas);
router.get('/ficha/:id/validar', gestorController.showFichaValidar);
router.post('/ficha/:id/validar', gestorController.validarFicha);

export default router;
