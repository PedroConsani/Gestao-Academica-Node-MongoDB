import express from 'express';
import * as alunoController from '../controllers/AlunoController.js';
import { upload, handleUploadError } from '../middleware/uploadHelper.js';
import { validateFicha, validateMatricula, handleValidationErrors } from '../middleware/validator.js';

const router = express.Router();

router.get('/dashboard', alunoController.dashboard);

router.get('/ficha', alunoController.showFicha);
router.post('/ficha', upload.single('foto'), handleUploadError, validateFicha, handleValidationErrors, alunoController.updateFicha);
router.post('/ficha/submit', upload.single('foto'), handleUploadError, validateFicha, handleValidationErrors, alunoController.submitFicha);

router.get('/matriculas', alunoController.listMatriculas);
router.get('/matriculas/:id', alunoController.showMatricula);
router.get('/matricula/nova', alunoController.showMatriculaNova);
router.post('/matricula', validateMatricula, handleValidationErrors, alunoController.createMatricula);

router.get('/notas', alunoController.listNotas);
router.get('/notas-curso/:id', alunoController.showNotasCurso);

export default router;
