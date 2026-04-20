import multer from 'multer';
import path from 'path';
import fs from 'fs';
import { config } from '../config/app.js';

// Criar diretório se não existir
if (!fs.existsSync(config.upload.dir)) {
  fs.mkdirSync(config.upload.dir, { recursive: true });
}

const storage = multer.diskStorage({
  destination: (req, file, cb) => {
    cb(null, config.upload.dir);
  },
  filename: (req, file, cb) => {
    const uniqueSuffix = Date.now() + '-' + Math.round(Math.random() * 1E9);
    const ext = path.extname(file.originalname);
    const name = path.basename(file.originalname, ext);
    cb(null, `${name}-${uniqueSuffix}${ext}`);
  }
});

const fileFilter = (req, file, cb) => {
  // Verificar tipo MIME
  if (!config.upload.allowedTypes.includes(file.mimetype)) {
    return cb(new Error(`Tipo de arquivo não permitido: ${file.mimetype}`));
  }

  // Verificar extensão
  const ext = path.extname(file.originalname).toLowerCase().substring(1);
  if (!config.upload.allowedExtensions.includes(ext)) {
    return cb(new Error(`Extensão não permitida: ${ext}`));
  }

  cb(null, true);
};

export const upload = multer({
  storage,
  fileFilter,
  limits: { fileSize: config.upload.maxSize }
});

export function handleUploadError(err, req, res, next) {
  if (err instanceof multer.MulterError) {
    if (err.code === 'LIMIT_FILE_SIZE') {
      return res.status(400).json({ 
        error: `Arquivo muito grande. Máximo: ${config.upload.maxSize / 1024 / 1024}MB` 
      });
    }
  } else if (err) {
    return res.status(400).json({ error: err.message });
  }
  next();
}
