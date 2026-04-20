import express from 'express';
import * as funcionarioController from '../controllers/FuncionarioController.js';

const router = express.Router();

router.get('/dashboard', funcionarioController.dashboard);

router.get('/matriculas', funcionarioController.listMatriculas);
router.post('/matricula/:id/decidir', funcionarioController.decidirMatricula);

router.get('/pautas', funcionarioController.listPautas);
router.get('/pauta/:id/notas', funcionarioController.showPautaNotas);
router.post('/nota/:id', funcionarioController.updateNota);
router.get('/pauta/nova', funcionarioController.showPautaNova);

export default router;
