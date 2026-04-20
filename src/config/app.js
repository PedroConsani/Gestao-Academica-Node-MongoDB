export const config = {
  app: {
    name: process.env.APP_NAME || 'Sistema Académico',
    url: process.env.APP_URL || 'http://localhost:3000',
    port: process.env.PORT || 3000,
    env: process.env.NODE_ENV || 'development'
  },
  
  session: {
    secret: process.env.SESSION_SECRET || 'dev_secret_key',
    lifetime: parseInt(process.env.SESSION_LIFETIME) || 1800 // 30 minutos
  },

  upload: {
    maxSize: parseInt(process.env.UPLOAD_MAX_SIZE) || 2 * 1024 * 1024, // 2MB
    dir: process.env.UPLOAD_DIR || './public/uploads/photos/',
    allowedTypes: ['image/jpeg', 'image/png'],
    allowedExtensions: ['jpg', 'jpeg', 'png']
  },

  roles: {
    ALUNO: 'aluno',
    FUNCIONARIO: 'funcionario',
    GESTOR: 'gestor'
  },

  fichaStates: {
    RASCUNHO: 'rascunho',
    SUBMETIDA: 'submetida',
    APROVADA: 'aprovada',
    REJEITADA: 'rejeitada'
  },

  matriculaStates: {
    PENDENTE: 'pendente',
    APROVADA: 'aprovada',
    REJEITADA: 'rejeitada'
  },

  epocas: ['Normal', 'Recurso', 'Especial'],
  semestres: [1, 2],
  anosMax: 5
};
