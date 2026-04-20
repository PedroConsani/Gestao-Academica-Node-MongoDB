import { body, validationResult } from 'express-validator';

export const validateLogin = [
  body('email')
    .trim()
    .notEmpty().withMessage('Email é obrigatório')
    .isEmail().withMessage('Email inválido'),
  body('password')
    .notEmpty().withMessage('Password é obrigatória')
];

export const validateRegister = [
  body('nome')
    .trim()
    .notEmpty().withMessage('Nome é obrigatório')
    .isLength({ max: 150 }).withMessage('Nome não pode exceder 150 caracteres'),
  body('email')
    .trim()
    .notEmpty().withMessage('Email é obrigatório')
    .isEmail().withMessage('Email inválido')
    .isLength({ max: 150 }).withMessage('Email não pode exceder 150 caracteres'),
  body('password')
    .notEmpty().withMessage('Password é obrigatória')
    .isLength({ min: 8 }).withMessage('Password deve ter no mínimo 8 caracteres'),
  body('password_confirm')
    .notEmpty().withMessage('Confirmação de password é obrigatória')
    .custom((value, { req }) => {
      if (value !== req.body.password) {
        throw new Error('As passwords não coincidem');
      }
      return true;
    })
];

export const validateCurso = [
  body('nome')
    .trim()
    .notEmpty().withMessage('Nome é obrigatório')
    .isLength({ max: 200 }).withMessage('Nome não pode exceder 200 caracteres'),
  body('codigo')
    .trim()
    .notEmpty().withMessage('Código é obrigatório')
    .isLength({ max: 20 }).withMessage('Código não pode exceder 20 caracteres')
    .matches(/^[A-Z0-9]+$/).withMessage('Código deve conter apenas letras maiúsculas e números'),
  body('duracao_anos')
    .isInt({ min: 1, max: 5 }).withMessage('Duração deve estar entre 1 e 5 anos')
];

export const validateUC = [
  body('nome')
    .trim()
    .notEmpty().withMessage('Nome é obrigatório')
    .isLength({ max: 200 }).withMessage('Nome não pode exceder 200 caracteres'),
  body('codigo')
    .trim()
    .notEmpty().withMessage('Código é obrigatório')
    .isLength({ max: 20 }).withMessage('Código não pode exceder 20 caracteres')
    .matches(/^[A-Z0-9]+$/).withMessage('Código deve conter apenas letras maiúsculas e números'),
  body('creditos')
    .isFloat({ min: 0 }).withMessage('Créditos devem ser um número válido')
];

export const validatePlanoEstudos = [
  body('curso_id')
    .notEmpty().withMessage('Curso é obrigatório')
    .isMongoId().withMessage('Curso inválido'),
  body('uc_id')
    .notEmpty().withMessage('UC é obrigatória')
    .isMongoId().withMessage('UC inválida'),
  body('ano')
    .isInt({ min: 1, max: 5 }).withMessage('Ano deve estar entre 1 e 5'),
  body('semestre')
    .isIn([1, 2]).withMessage('Semestre deve ser 1 ou 2')
];

export function handleValidationErrors(req, res, next) {
  const errors = validationResult(req);
  if (!errors.isEmpty()) {
    return res.status(422).render('error', {
      title: 'Erro de Validação',
      errors: errors.mapped()
    });
  }
  next();
}
