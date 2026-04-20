import express from 'express';
import * as authController from '../controllers/AuthController.js';
import { validateLogin, validateRegister, handleValidationErrors } from '../middleware/validator.js';
import { guestMiddleware } from '../middleware/authMiddleware.js';

const router = express.Router();

router.get('/login', guestMiddleware, authController.showLogin);
router.post('/login', validateLogin, handleValidationErrors, authController.processLogin);

router.get('/register', guestMiddleware, authController.showRegister);
router.post('/register', validateRegister, handleValidationErrors, authController.processRegister);

export default router;
