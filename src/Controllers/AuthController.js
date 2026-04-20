import bcrypt from 'bcryptjs';
import Utilizador from '../models/Utilizador.js';

export async function showLogin(req, res) {
  const error = req.query.error || null;
  res.render('auth/login', { 
    title: 'Login', 
    error
  });
}

export async function processLogin(req, res) {
  try {
    const { email, password } = req.body;

    const user = await Utilizador.findOne({ email: email.toLowerCase(), ativo: true });

    if (!user || !(await bcrypt.compare(password, user.password_hash))) {
      return res.render('auth/login', { 
        title: 'Login', 
        error: 'Email ou password incorretos.',
        layout: 'layouts/wrapper'
      });
    }

    // Regenerar ID de sessão
    req.session.regenerate((err) => {
      if (err) throw err;

      req.session.user = {
        id: user._id.toString(),
        nome: user.nome,
        email: user.email,
        role: user.role
      };
      req.session.lastActivity = Date.now();

      res.redirect(`/${user.role}/dashboard`);
    });
  } catch (error) {
    console.error('Erro ao fazer login:', error);
    res.render('auth/login', { 
      title: 'Login', 
      error: 'Erro ao processar login. Tente novamente.',
      layout: 'layouts/wrapper'
    });
  }
}

export async function showRegister(req, res) {
  res.render('auth/register', { 
    title: 'Criar Conta',
    layout: 'layouts/wrapper'
  });
}

export async function processRegister(req, res) {
  try {
    const { nome, email, password, password_confirm } = req.body;

    // Verificar se email já existe
    const existingUser = await Utilizador.findOne({ email: email.toLowerCase() });
    if (existingUser) {
      return res.render('auth/register', {
        title: 'Criar Conta',
        error: 'Este email já está registado.',
        layout: 'layouts/wrapper'
      });
    }

    // Verificar se passwords coincidem
    if (password !== password_confirm) {
      return res.render('auth/register', {
        title: 'Criar Conta',
        error: 'As passwords não coincidem.',
        layout: 'layouts/wrapper'
      });
    }

    // Hash da password
    const hash = await bcrypt.hash(password, 12);

    // Criar utilizador
    const newUser = await Utilizador.create({
      nome,
      email: email.toLowerCase(),
      password_hash: hash,
      role: 'aluno'
    });

    req.session.flash = { success: 'Conta criada com sucesso! Faça login.' };
    res.redirect('/auth/login');
  } catch (error) {
    console.error('Erro ao registar:', error);
    res.render('auth/register', {
      title: 'Criar Conta',
      error: 'Erro ao criar conta. Tente novamente.'
    });
  }
}
