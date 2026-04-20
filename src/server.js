import dotenv from 'dotenv';
import express from 'express';
import session from 'express-session';
import path from 'path';
import { fileURLToPath } from 'url';
import { connectDB } from './config/database.js';
import { config } from './config/app.js';

// Carregar variáveis de ambiente
dotenv.config();
import authRoutes from './routes/authRoutes.js';
import alunoRoutes from './routes/alunoRoutes.js';
import funcionarioRoutes from './routes/funcionarioRoutes.js';
import gestorRoutes from './routes/gestorRoutes.js';
import { authMiddleware, roleMiddleware } from './middleware/authMiddleware.js';

const __filename = fileURLToPath(import.meta.url);
const __dirname = path.dirname(__filename);

const app = express();

// ==================== CONFIGURAÇÃO ====================
// View engine (EJS)
app.set('view engine', 'ejs');
app.set('views', path.join(__dirname, '../views'));

// Middleware
app.use(express.static(path.join(__dirname, '../public')));
app.use(express.urlencoded({ extended: true }));
app.use(express.json());

// Sessions
app.use(session({
  secret: config.session.secret,
  resave: false,
  saveUninitialized: true,
  cookie: { 
    secure: config.app.env === 'production',
    httpOnly: true,
    maxAge: config.session.lifetime * 1000
  }
}));

// Middleware para passar dados ao template
app.use((req, res, next) => {
  res.locals.appName = config.app.name;
  res.locals.appUrl = config.app.url;
  res.locals.user = req.session.user || null;
  res.locals.flash = req.session.flash || {};
  delete req.session.flash;
  next();
});

// ==================== ROTAS ====================
app.use('/auth', authRoutes);
app.use('/aluno', authMiddleware, roleMiddleware(['aluno']), alunoRoutes);
app.use('/funcionario', authMiddleware, roleMiddleware(['funcionario']), funcionarioRoutes);
app.use('/gestor', authMiddleware, roleMiddleware(['gestor']), gestorRoutes);

// Rota inicial
app.get('/', (req, res) => {
  if (!req.session.user) {
    return res.redirect('/auth/login');
  }
  
  const role = req.session.user.role;
  res.redirect(`/${role}/dashboard`);
});

app.get('/logout', (req, res) => {
  req.session.destroy((err) => {
    if (err) console.error(err);
    res.redirect('/auth/login');
  });
});

// Rota 404
app.use((req, res) => {
  res.status(404).render('shared/404', { title: 'Página não encontrada' });
});

// ==================== INICIAR SERVIDOR ====================
async function start() {
  try {
    await connectDB();
    
    app.listen(config.app.port, () => {
      console.log(`🚀 Servidor rodando em ${config.app.url}`);
    });
  } catch (error) {
    console.error('❌ Erro ao iniciar servidor:', error);
    process.exit(1);
  }
}

start();

export default app;
