import express from 'express';
import * as alunoController from '../controllers/AlunoController.js';
import { upload, handleUploadError } from '../middleware/uploadHelper.js';

const router = express.Router();

router.get('/dashboard', alunoController.dashboard);

router.get('/ficha', alunoController.showFicha);
router.post('/ficha', upload.single('foto'), handleUploadError, alunoController.updateFicha);
router.post('/ficha/submit', alunoController.submitFicha);

router.get('/matriculas', alunoController.listMatriculas);
router.get('/matricula/nova', alunoController.showMatriculaNova);
router.post('/matricula', alunoController.createMatricula);

router.get('/notas', alunoController.listMatriculas); // Rota para ver notas
router.get('/notas-curso/:id', alunoController.listMatriculas); // Detalhes de notas por UC

export default router;
