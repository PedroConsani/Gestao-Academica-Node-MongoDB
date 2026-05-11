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
    .isLength({ max: 200 }).withMessage('Nome não pode exceder 200 caracteres')
    .escape(),
  body('codigo')
    .trim()
    .notEmpty().withMessage('Código é obrigatório')
    .isLength({ max: 20 }).withMessage('Código não pode exceder 20 caracteres')
    .matches(/^[A-Z0-9]+$/).withMessage('Código deve conter apenas letras maiúsculas e números'),
  body('descricao')
    .optional()
    .trim()
    .isLength({ max: 500 }).withMessage('Descrição não pode exceder 500 caracteres')
    .escape(),
  body('duracao_anos')
    .isInt({ min: 1, max: 5 }).withMessage('Duração deve estar entre 1 e 5 anos')
];

export const validateUC = [
  body('nome')
    .trim()
    .notEmpty().withMessage('Nome é obrigatório')
    .isLength({ max: 200 }).withMessage('Nome não pode exceder 200 caracteres')
    .escape(),
  body('codigo')
    .trim()
    .notEmpty().withMessage('Código é obrigatório')
    .isLength({ max: 20 }).withMessage('Código não pode exceder 20 caracteres')
    .matches(/^[A-Z0-9]+$/).withMessage('Código deve conter apenas letras maiúsculas e números'),
  body('descricao')
    .optional()
    .trim()
    .isLength({ max: 500 }).withMessage('Descrição não pode exceder 500 caracteres')
    .escape(),
  body('creditos')
    .isFloat({ min: 0, max: 100 }).withMessage('Créditos devem estar entre 0 e 100')
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
    .isIn(['1', '2']).withMessage('Semestre deve ser 1 ou 2')
];

export const validateMatricula = [
  body('curso_id')
    .notEmpty().withMessage('Curso é obrigatório')
    .isMongoId().withMessage('Curso inválido'),
  body('ano_letivo')
    .notEmpty().withMessage('Ano letivo é obrigatório')
    .matches(/^\d{4}\/\d{4}$/).withMessage('Ano letivo deve estar no formato YYYY/YYYY')
];

export const validateFicha = [
  body('curso_id')
    .notEmpty().withMessage('Curso é obrigatório')
    .isMongoId().withMessage('Curso inválido'),
  body('data_nascimento')
    .optional()
    .isISO8601().withMessage('Data de nascimento inválida'),
  body('nacionalidade')
    .optional()
    .trim()
    .isLength({ max: 100 }).withMessage('Nacionalidade não pode exceder 100 caracteres')
    .escape(),
  body('nif')
    .optional()
    .trim()
    .matches(/^\d{9}$/).withMessage('NIF deve ter 9 dígitos'),
  body('telefone')
    .optional()
    .trim()
    .matches(/^[0-9\s\-\+]+$/).withMessage('Telefone inválido'),
  body('morada')
    .optional()
    .trim()
    .isLength({ max: 300 }).withMessage('Morada não pode exceder 300 caracteres')
    .escape(),
  body('codigo_postal')
    .optional()
    .trim()
    .matches(/^[0-9]{4}-[0-9]{3}$/).withMessage('Código postal inválido (formato: XXXX-XXX)'),
  body('localidade')
    .optional()
    .trim()
    .isLength({ max: 100 }).withMessage('Localidade não pode exceder 100 caracteres')
    .escape()
];

export const validateNota = [
  body('nota_final')
    .notEmpty().withMessage('Nota é obrigatória')
    .isFloat({ min: 0, max: 20 }).withMessage('Nota deve estar entre 0 e 20')
];

export const validatePauta = [
  body('uc_id')
    .notEmpty().withMessage('UC é obrigatória')
    .isMongoId().withMessage('UC inválida'),
  body('curso_id')
    .notEmpty().withMessage('Curso é obrigatório')
    .isMongoId().withMessage('Curso inválido'),
  body('ano_letivo')
    .notEmpty().withMessage('Ano letivo é obrigatório')
    .matches(/^\d{4}\/\d{4}$/).withMessage('Ano letivo deve estar no formato YYYY/YYYY'),
  body('epoca')
    .notEmpty().withMessage('Época é obrigatória')
    .isIn(['Normal', 'Recurso', 'Especial']).withMessage('Época inválida')
];

export function handleValidationErrors(req, res, next) {
  const errors = validationResult(req);
  if (!errors.isEmpty()) {
    req.session.flash = { error: 'Existem erros no formulário. Por favor revise.' };
    return res.status(422).redirect(req.get('referer') || '/');
  }
  next();
}
