import FichaAluno from '../models/FichaAluno.js';
import Matricula from '../models/Matricula.js';
import Pauta from '../models/Pauta.js';
import Nota from '../models/Nota.js';
import Utilizador from '../models/Utilizador.js';

export async function dashboard(req, res) {
  try {
    const userId = req.session.user.id;

    const ficha = await FichaAluno.findOne({ aluno_id: userId });
    const matriculas = await Matricula.find({ aluno_id: userId })
      .populate('curso_id', 'nome codigo');
    const notas = await Nota.find({ aluno_id: userId })
      .populate({
        path: 'pauta_id',
        select: 'uc_id ano_letivo epoca',
        populate: {
          path: 'uc_id',
          select: 'nome codigo'
        }
      });

    res.render('aluno/dashboard', {
      title: 'Dashboard do Aluno',
      ficha,
      matriculas,
      notas
    });
  } catch (error) {
    console.error('Erro ao carregar dashboard:', error);
    res.status(500).render('error', { 
      title: 'Erro', 
      message: 'Erro ao carregar dashboard' 
    });
  }
}

export async function showFicha(req, res) {
  try {
    const userId = req.session.user.id;
    let ficha = await FichaAluno.findOne({ aluno_id: userId })
      .populate('aluno_id', 'nome email')
      .populate('curso_id', 'nome codigo')
      .populate('validada_por', 'nome');

    if (!ficha) {
      ficha = await FichaAluno.create({ aluno_id: userId });
    }

    res.render('aluno/ficha', {
      title: 'Minha Ficha',
      ficha
    });
  } catch (error) {
    console.error('Erro ao carregar ficha:', error);
    res.status(500).render('error', { 
      title: 'Erro', 
      message: 'Erro ao carregar ficha' 
    });
  }
}

export async function updateFicha(req, res) {
  try {
    const userId = req.session.user.id;
    const { 
      curso_id, data_nascimento, nacionalidade, nif, cc, 
      telefone, morada, codigo_postal, localidade 
    } = req.body;

    let ficha = await FichaAluno.findOne({ aluno_id: userId });
    if (!ficha) {
      ficha = new FichaAluno({ aluno_id: userId });
    }

    // Atualizar dados
    ficha.curso_id = curso_id || ficha.curso_id;
    ficha.data_nascimento = data_nascimento || ficha.data_nascimento;
    ficha.nacionalidade = nacionalidade || ficha.nacionalidade;
    ficha.nif = nif || ficha.nif;
    ficha.cc = cc || ficha.cc;
    ficha.telefone = telefone || ficha.telefone;
    ficha.morada = morada || ficha.morada;
    ficha.codigo_postal = codigo_postal || ficha.codigo_postal;
    ficha.localidade = localidade || ficha.localidade;

    // Atualizar foto se enviada
    if (req.file) {
      ficha.foto_path = `/uploads/photos/${req.file.filename}`;
    }

    ficha.atualizado_em = new Date();

    await ficha.save();

    req.session.flash = { success: 'Ficha atualizada com sucesso!' };
    res.redirect('/aluno/ficha');
  } catch (error) {
    console.error('Erro ao atualizar ficha:', error);
    res.status(500).render('error', { 
      title: 'Erro', 
      message: 'Erro ao atualizar ficha' 
    });
  }
}

export async function submitFicha(req, res) {
  try {
    const userId = req.session.user.id;
    const ficha = await FichaAluno.findOne({ aluno_id: userId });

    if (!ficha) {
      return res.status(404).json({ error: 'Ficha não encontrada' });
    }

    ficha.estado = 'submetida';
    ficha.submetida_em = new Date();
    await ficha.save();

    req.session.flash = { success: 'Ficha submetida para validação!' };
    res.redirect('/aluno/ficha');
  } catch (error) {
    console.error('Erro ao submeter ficha:', error);
    res.status(500).json({ error: 'Erro ao submeter ficha' });
  }
}

export async function listMatriculas(req, res) {
  try {
    const userId = req.session.user.id;
    const matriculas = await Matricula.find({ aluno_id: userId })
      .populate('curso_id', 'nome codigo')
      .populate('decidido_por', 'nome')
      .sort({ criado_em: -1 });

    res.render('aluno/matriculas', {
      title: 'Minhas Matrículas',
      matriculas
    });
  } catch (error) {
    console.error('Erro ao listar matrículas:', error);
    res.status(500).render('error', { 
      title: 'Erro', 
      message: 'Erro ao listar matrículas' 
    });
  }
}

export async function showMatriculaNova(req, res) {
  try {
    const Curso = await import('../models/Curso.js').then(m => m.default);
    const cursos = await Curso.find({ ativo: true }).select('nome codigo');

    res.render('aluno/matricula-nova', {
      title: 'Nova Matrícula',
      cursos
    });
  } catch (error) {
    console.error('Erro ao carregar form matrícula:', error);
    res.status(500).render('error', { 
      title: 'Erro', 
      message: 'Erro ao carregar formulário' 
    });
  }
}

export async function createMatricula(req, res) {
  try {
    const userId = req.session.user.id;
    const { curso_id, ano_letivo, observacoes_aluno } = req.body;

    // Verificar se já tem matrícula para este ano
    const existing = await Matricula.findOne({ 
      aluno_id: userId, 
      ano_letivo,
      curso_id 
    });

    if (existing) {
      return res.render('aluno/matricula-nova', {
        title: 'Nova Matrícula',
        error: 'Você já tem uma matrícula para este ano neste curso.'
      });
    }

    await Matricula.create({
      aluno_id: userId,
      curso_id,
      ano_letivo,
      observacoes_aluno,
      estado: 'pendente'
    });

    req.session.flash = { success: 'Matrícula solicitada com sucesso!' };
    res.redirect('/aluno/matriculas');
  } catch (error) {
    console.error('Erro ao criar matrícula:', error);
    res.status(500).render('error', { 
      title: 'Erro', 
      message: 'Erro ao criar matrícula' 
    });
  }
}
