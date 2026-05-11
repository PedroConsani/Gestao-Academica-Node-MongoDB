import express from 'express';
import * as funcionarioController from '../controllers/FuncionarioController.js';
import { validatePauta, validateNota, handleValidationErrors } from '../middleware/validator.js';

const router = express.Router();

router.get('/dashboard', funcionarioController.dashboard);

router.get('/matriculas', funcionarioController.listMatriculas);
router.post('/matricula/:id/decidir', funcionarioController.decidirMatricula);

router.get('/pautas', funcionarioController.listPautas);
router.get('/pauta/:id/notas', funcionarioController.showPautaNotas);
router.post('/nota/:id', validateNota, handleValidationErrors, funcionarioController.updateNota);
router.get('/pauta/nova', funcionarioController.showPautaNova);
router.post('/pauta', validatePauta, handleValidationErrors, funcionarioController.createPauta);

export default router;
