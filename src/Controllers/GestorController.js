import Curso from '../models/Curso.js';
import UnidadeCurricular from '../models/UnidadeCurricular.js';
import PlanoEstudos from '../models/PlanoEstudos.js';
import FichaAluno from '../models/FichaAluno.js';
import Utilizador from '../models/Utilizador.js';

export async function dashboard(req, res) {
  try {
    const cursos = await Curso.countDocuments({ ativo: true });
    const ucs = await UnidadeCurricular.countDocuments({ ativo: true });
    const fichasPendentes = await FichaAluno.countDocuments({ estado: 'submetida' });

    res.render('gestor/dashboard', {
      title: 'Dashboard do Gestor',
      stats: { cursos, ucs, fichasPendentes }
    });
  } catch (error) {
    console.error('Erro ao carregar dashboard:', error);
    res.status(500).render('error', { 
      title: 'Erro', 
      message: 'Erro ao carregar dashboard' 
    });
  }
}

// ─────────── CURSOS ───────────
export async function listCursos(req, res) {
  try {
    const cursos = await Curso.find()
      .populate('criado_por', 'nome')
      .sort({ criado_em: -1 });

    res.render('gestor/cursos', {
      title: 'Cursos',
      cursos
    });
  } catch (error) {
    console.error('Erro ao listar cursos:', error);
    res.status(500).render('error', { 
      title: 'Erro', 
      message: 'Erro ao listar cursos' 
    });
  }
}

export async function showCursoNovo(req, res) {
  res.render('gestor/curso-form', {
    title: 'Novo Curso',
    curso: null
  });
}

export async function createCurso(req, res) {
  try {
    const { nome, codigo, descricao, duracao_anos } = req.body;

    // Verificar se código já existe
    const existing = await Curso.findOne({ codigo: codigo.toUpperCase() });
    if (existing) {
      return res.render('gestor/curso-form', {
        title: 'Novo Curso',
        curso: null,
        error: 'Código de curso já existe'
      });
    }

    await Curso.create({
      nome,
      codigo: codigo.toUpperCase(),
      descricao,
      duracao_anos,
      criado_por: req.session.user.id
    });

    req.session.flash = { success: 'Curso criado com sucesso!' };
    res.redirect('/gestor/cursos');
  } catch (error) {
    console.error('Erro ao criar curso:', error);
    res.status(500).render('error', { 
      title: 'Erro', 
      message: 'Erro ao criar curso' 
    });
  }
}

export async function showCursoEditar(req, res) {
  try {
    const { id } = req.params;
    const curso = await Curso.findById(id).populate('criado_por', 'nome');

    if (!curso) {
      return res.status(404).render('error', { 
        title: 'Erro', 
        message: 'Curso não encontrado' 
      });
    }

    res.render('gestor/curso-form', {
      title: 'Editar Curso',
      curso
    });
  } catch (error) {
    console.error('Erro ao carregar curso:', error);
    res.status(500).render('error', { 
      title: 'Erro', 
      message: 'Erro ao carregar curso' 
    });
  }
}

export async function updateCurso(req, res) {
  try {
    const { id } = req.params;
    const { nome, codigo, descricao, duracao_anos } = req.body;

    const curso = await Curso.findById(id);
    if (!curso) {
      return res.status(404).json({ error: 'Curso não encontrado' });
    }

    curso.nome = nome;
    curso.codigo = codigo.toUpperCase();
    curso.descricao = descricao;
    curso.duracao_anos = duracao_anos;
    curso.atualizado_em = new Date();

    await curso.save();

    req.session.flash = { success: 'Curso atualizado com sucesso!' };
    res.redirect('/gestor/cursos');
  } catch (error) {
    console.error('Erro ao atualizar curso:', error);
    res.status(500).json({ error: 'Erro ao atualizar curso' });
  }
}

export async function toggleCurso(req, res) {
  try {
    const { id } = req.params;
    const curso = await Curso.findById(id);

    if (!curso) {
      return res.status(404).json({ error: 'Curso não encontrado' });
    }

    curso.ativo = !curso.ativo;
    await curso.save();

    res.json({ success: true, ativo: curso.ativo });
  } catch (error) {
    console.error('Erro ao toggle curso:', error);
    res.status(500).json({ error: 'Erro ao alterar status' });
  }
}

// ─────────── UNIDADES CURRICULARES ───────────
export async function listUCs(req, res) {
  try {
    const ucs = await UnidadeCurricular.find()
      .populate('criado_por', 'nome')
      .sort({ criado_em: -1 });

    res.render('gestor/ucs', {
      title: 'Unidades Curriculares',
      ucs
    });
  } catch (error) {
    console.error('Erro ao listar UCs:', error);
    res.status(500).render('error', { 
      title: 'Erro', 
      message: 'Erro ao listar UCs' 
    });
  }
}

export async function showUCNova(req, res) {
  res.render('gestor/uc-form', {
    title: 'Nova UC',
    uc: null
  });
}

export async function showUCEditar(req, res) {
  try {
    const { id } = req.params;

    const uc = await UnidadeCurricular.findById(id).populate('criado_por', 'nome');

    if (!uc) {
      return res.status(404).render('error', {
        title: 'Erro',
        message: 'UC não encontrada'
      });
    }

    return res.render('gestor/uc-form', {
      title: 'Editar UC',
      uc
    });
  } catch (error) {
    console.error('Erro ao carregar form UC:', error);
    return res.status(500).render('error', {
      title: 'Erro',
      message: 'Erro ao carregar formulário'
    });
  }
}


export async function createUC(req, res) {
  try {
    const { nome, codigo, descricao, creditos } = req.body;

    const existing = await UnidadeCurricular.findOne({ codigo: codigo.toUpperCase() });
    if (existing) {
      return res.render('gestor/uc-form', {
        title: 'Nova UC',
        uc: null,
        error: 'Código de UC já existe'
      });
    }

    await UnidadeCurricular.create({
      nome,
      codigo: codigo.toUpperCase(),
      descricao,
      creditos,
      criado_por: req.session.user.id
    });

    req.session.flash = { success: 'UC criada com sucesso!' };
    res.redirect('/gestor/ucs');
  } catch (error) {
    console.error('Erro ao criar UC:', error);
    res.status(500).render('error', { 
      title: 'Erro', 
      message: 'Erro ao criar UC' 
    });
  }
}

export async function updateUC(req, res) {
  try {
    const { id } = req.params;
    const { nome, codigo, descricao, creditos } = req.body;

    const uc = await UnidadeCurricular.findById(id);
    if (!uc) {
      return res.status(404).json({ error: 'UC não encontrada' });
    }

    uc.nome = nome;
    uc.codigo = codigo.toUpperCase();
    uc.descricao = descricao;
    uc.creditos = creditos;

    await uc.save();

    req.session.flash = { success: 'UC atualizada com sucesso!' };
    res.redirect('/gestor/ucs');
  } catch (error) {
    console.error('Erro ao atualizar UC:', error);
    res.status(500).json({ error: 'Erro ao atualizar UC' });
  }
}

// ─────────── PLANO DE ESTUDOS ───────────
export async function listPlanoEstudos(req, res) {
  try {
    const planos = await PlanoEstudos.find()
      .populate('curso_id', 'nome codigo')
      .populate('uc_id', 'nome codigo')
      .sort({ 'curso_id': 1, 'ano': 1, 'semestre': 1 });

    res.render('gestor/plano-estudos', {
      title: 'Plano de Estudos',
      planos
    });
  } catch (error) {
    console.error('Erro ao listar plano:', error);
    res.status(500).render('error', { 
      title: 'Erro', 
      message: 'Erro ao listar plano de estudos' 
    });
  }
}

export async function showPlanoNovo(req, res) {
  try {
    const cursos = await Curso.find({ ativo: true }).select('nome codigo');
    const ucs = await UnidadeCurricular.find({ ativo: true }).select('nome codigo');

    res.render('gestor/plano-estudos-form', {
      title: 'Novo Plano',
      plano: null,
      cursos,
      ucs,
      semestres: [1, 2],
      anos: [1, 2, 3, 4, 5]
    });
  } catch (error) {
    console.error('Erro ao carregar form:', error);
    res.status(500).render('error', { 
      title: 'Erro', 
      message: 'Erro ao carregar formulário' 
    });
  }
}

export async function createPlanoEstudos(req, res) {
  try {
    const { curso_id, uc_id, ano, semestre, obrigatoria } = req.body;

    const existing = await PlanoEstudos.findOne({ curso_id, uc_id });
    if (existing) {
      return res.render('gestor/plano-estudos-form', {
        title: 'Novo Plano',
        error: 'Esta UC já está associada a este curso'
      });
    }

    await PlanoEstudos.create({
      curso_id,
      uc_id,
      ano,
      semestre,
      obrigatoria: obrigatoria === 'on' || obrigatoria === true
    });

    req.session.flash = { success: 'Plano de estudos criado!' };
    res.redirect('/gestor/plano-estudos');
  } catch (error) {
    console.error('Erro ao criar plano:', error);
    res.status(500).render('error', { 
      title: 'Erro', 
      message: 'Erro ao criar plano de estudos' 
    });
  }
}

export async function removePlanoEstudos(req, res) {
  try {
    const { id } = req.params;
    await PlanoEstudos.findByIdAndDelete(id);

    req.session.flash = { success: 'Plano removido!' };
    res.redirect('/gestor/plano-estudos');
  } catch (error) {
    console.error('Erro ao remover plano:', error);
    res.status(500).json({ error: 'Erro ao remover plano' });
  }
}

// ─────────── FICHAS DE ALUNO ───────────
export async function listFichas(req, res) {
  try {
    const estado = req.query.estado || null;
    let query = {};

    if (estado && ['rascunho', 'submetida', 'aprovada', 'rejeitada'].includes(estado)) {
      query.estado = estado;
    }

    const fichas = await FichaAluno.find(query)
      .populate('aluno_id', 'nome email')
      .populate('curso_id', 'nome codigo')
      .populate('validada_por', 'nome')
      .sort({ criado_em: -1 });

    res.render('gestor/fichas', {
      title: 'Fichas de Aluno',
      fichas,
      filtro: estado
    });
  } catch (error) {
    console.error('Erro ao listar fichas:', error);
    res.status(500).render('error', { 
      title: 'Erro', 
      message: 'Erro ao listar fichas' 
    });
  }
}

export async function showFichaValidar(req, res) {
  try {
    const { id } = req.params;
    const ficha = await FichaAluno.findById(id)
      .populate('aluno_id', 'nome email')
      .populate('curso_id', 'nome codigo')
      .populate('validada_por', 'nome');

    if (!ficha) {
      return res.status(404).render('error', { 
        title: 'Erro', 
        message: 'Ficha não encontrada' 
      });
    }

    res.render('gestor/ficha-validar', {
      title: 'Validar Ficha',
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

export async function validarFicha(req, res) {
  try {
    const { id } = req.params;
    const { acao, observacoes } = req.body;

    const ficha = await FichaAluno.findById(id);
    if (!ficha) {
      return res.status(404).json({ error: 'Ficha não encontrada' });
    }

    ficha.estado = acao === 'aprovar' ? 'aprovada' : 'rejeitada';
    ficha.observacoes = observacoes;
    ficha.validada_por = req.session.user.id;
    ficha.validada_em = new Date();

    await ficha.save();

    req.session.flash = { success: `Ficha ${ficha.estado} com sucesso!` };
    res.redirect('/gestor/fichas');
  } catch (error) {
    console.error('Erro ao validar ficha:', error);
    res.status(500).json({ error: 'Erro ao validar ficha' });
  }
}
