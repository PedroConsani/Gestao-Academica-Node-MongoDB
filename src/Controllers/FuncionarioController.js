import Matricula from '../models/Matricula.js';
import Pauta from '../models/Pauta.js';
import Nota from '../models/Nota.js';
import UnidadeCurricular from '../models/UnidadeCurricular.js';
import Curso from '../models/Curso.js';
import Utilizador from '../models/Utilizador.js';

export async function dashboard(req, res) {
  try {
    const pendentes = await Matricula.countDocuments({ estado: 'pendente' });
    const pautasAbertas = await Pauta.countDocuments({ fechada: false });

    res.render('funcionario/dashboard', {
      title: 'Dashboard do Funcionário',
      stats: { pendentes, pautasAbertas }
    });
  } catch (error) {
    console.error('Erro ao carregar dashboard:', error);
    res.status(500).render('error', { 
      title: 'Erro', 
      message: 'Erro ao carregar dashboard' 
    });
  }
}

export async function listMatriculas(req, res) {
  try {
    const estado = req.query.estado || null;
    let query = {};

    if (estado && ['pendente', 'aprovada', 'rejeitada'].includes(estado)) {
      query.estado = estado;
    }

    const matriculas = await Matricula.find(query)
      .populate('aluno_id', 'nome email')
      .populate('curso_id', 'nome codigo')
      .populate('decidido_por', 'nome')
      .sort({ criado_em: -1 });

    res.render('funcionario/matriculas', {
      title: 'Matrículas',
      matriculas,
      filtro: estado
    });
  } catch (error) {
    console.error('Erro ao listar matrículas:', error);
    res.status(500).render('error', { 
      title: 'Erro', 
      message: 'Erro ao listar matrículas' 
    });
  }
}

export async function decidirMatricula(req, res) {
  try {
    const { id } = req.params;
    const { acao, observacoes } = req.body;

    const matricula = await Matricula.findById(id);
    if (!matricula) {
      return res.status(404).json({ error: 'Matrícula não encontrada' });
    }

    if (!['aprovada', 'rejeitada'].includes(acao)) {
      return res.status(400).json({ error: 'Ação inválida' });
    }

    matricula.estado = acao;
    matricula.observacoes_func = observacoes;
    matricula.decidido_por = req.session.user.id;
    matricula.decidido_em = new Date();

    await matricula.save();

    req.session.flash = { success: `Matrícula ${acao} com sucesso!` };
    res.redirect('/funcionario/matriculas');
  } catch (error) {
    console.error('Erro ao decidir matrícula:', error);
    res.status(500).render('error', { 
      title: 'Erro', 
      message: 'Erro ao processar decisão' 
    });
  }
}

export async function listPautas(req, res) {
  try {
    const pautas = await Pauta.find()
      .populate('uc_id', 'nome codigo')
      .populate('curso_id', 'nome codigo')
      .populate('criada_por', 'nome')
      .sort({ criada_em: -1 });

    res.render('funcionario/pautas', {
      title: 'Pautas',
      pautas
    });
  } catch (error) {
    console.error('Erro ao listar pautas:', error);
    res.status(500).render('error', { 
      title: 'Erro', 
      message: 'Erro ao listar pautas' 
    });
  }
}

export async function showPautaNotas(req, res) {
  try {
    const { id } = req.params;
    const pauta = await Pauta.findById(id)
      .populate('uc_id', 'nome codigo')
      .populate('curso_id', 'nome codigo')
      .populate('criada_por', 'nome');

    if (!pauta) {
      return res.status(404).render('error', { 
        title: 'Erro', 
        message: 'Pauta não encontrada' 
      });
    }

    const notas = await Nota.find({ pauta_id: id })
      .populate('aluno_id', 'nome email')
      .populate('editado_por', 'nome');

    res.render('funcionario/pauta-notas', {
      title: 'Ver Notas',
      pauta,
      notas
    });
  } catch (error) {
    console.error('Erro ao carregar pauta:', error);
    res.status(500).render('error', { 
      title: 'Erro', 
      message: 'Erro ao carregar pauta' 
    });
  }
}

export async function updateNota(req, res) {
  try {
    const { id } = req.params;
    const { nota_final } = req.body;

    const nota = await Nota.findById(id);
    if (!nota) {
      return res.status(404).json({ error: 'Nota não encontrada' });
    }

    nota.nota_final = nota_final;
    nota.editado_por = req.session.user.id;
    nota.editado_em = new Date();

    await nota.save();

    res.json({ success: true, message: 'Nota atualizada!' });
  } catch (error) {
    console.error('Erro ao atualizar nota:', error);
    res.status(500).json({ error: 'Erro ao atualizar nota' });
  }
}

export async function showPautaNova(req, res) {
  try {
    const ucs = await UnidadeCurricular.find({ ativo: true }).select('nome codigo');
    const cursos = await Curso.find({ ativo: true }).select('nome codigo');

    res.render('funcionario/pauta-nova', {
      title: 'Nova Pauta',
      ucs,
      cursos,
      epocas: ['Normal', 'Recurso', 'Especial']
    });
  } catch (error) {
    console.error('Erro ao carregar form pauta:', error);
    res.status(500).render('error', { 
      title: 'Erro', 
      message: 'Erro ao carregar formulário' 
    });
  }
}

export async function createPauta(req, res) {
  try {
    const { uc_id, curso_id, ano_letivo, epoca } = req.body;

    // Validação básica
    if (!uc_id || !curso_id || !ano_letivo || !epoca) {
      req.session.flash = { error: 'Todos os campos são obrigatórios' };
      return res.redirect('/funcionario/pauta/nova');
    }

    // Verificar se UC e Curso existem
    const uc = await UnidadeCurricular.findById(uc_id);
    const curso = await Curso.findById(curso_id);

    if (!uc || !curso) {
      req.session.flash = { error: 'UC ou Curso não encontrados' };
      return res.redirect('/funcionario/pauta/nova');
    }

    // Validar epocas
    const epocasValidas = ['Normal', 'Recurso', 'Especial'];
    if (!epocasValidas.includes(epoca)) {
      req.session.flash = { error: 'Época inválida' };
      return res.redirect('/funcionario/pauta/nova');
    }

    // Verificar se pauta já existe
    const pautaExistente = await Pauta.findOne({
      uc_id,
      curso_id,
      ano_letivo,
      epoca
    });

    if (pautaExistente) {
      req.session.flash = { error: 'Pauta para esta UC, curso, ano e época já existe' };
      return res.redirect('/funcionario/pauta/nova');
    }

    // Criar pauta
    const pauta = new Pauta({
      uc_id,
      curso_id,
      ano_letivo,
      epoca,
      criada_por: req.session.user.id,
      criada_em: new Date(),
      fechada: false
    });

    await pauta.save();

    // Buscar alunos inscritos neste curso (só os que têm matrícula aprovada)
    const matriculas = await Matricula.find({
      curso_id,
      ano_letivo,
      estado: 'aprovada'
    }).populate('aluno_id');

    // Criar registros de notas para cada aluno
    for (const matricula of matriculas) {
      await Nota.create({
        pauta_id: pauta._id,
        aluno_id: matricula.aluno_id._id,
        nota_final: null
      });
    }

    req.session.flash = { 
      success: `Pauta criada com sucesso! ${matriculas.length} alunos adicionados.` 
    };
    res.redirect(`/funcionario/pauta/${pauta._id}/notas`);
  } catch (error) {
    console.error('Erro ao criar pauta:', error);
    req.session.flash = { error: 'Erro ao criar pauta' };
    res.redirect('/funcionario/pauta/nova');
  }
}
