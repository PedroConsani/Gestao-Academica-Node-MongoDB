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
      .sort({ criada_em: -1 })
      .lean();

    // Alguns registos podem estar com referências inválidas (uc_id/curso_id ausentes).
    // Mantemos todas as pautas e deixamos o EJS mostrar "N/A" quando o populate não resolver.
    res.render('funcionario/pautas', {
      title: 'Pautas',
      pautas: pautas || []
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

    const pautaResumo = req.session.pautaResumo;
    delete req.session.pautaResumo;

    res.render('funcionario/pauta-notas', {
      title: 'Ver Notas',
      pauta,
      notas,
      pautaResumo: pautaResumo || null
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
    console.log('[createPauta] body:', { uc_id, curso_id, ano_letivo, epoca });


    // Validação básica (também cobre null/undefined)
    if (
      uc_id == null ||
      curso_id == null ||
      ano_letivo == null ||
      epoca == null ||
      String(uc_id).trim() === '' ||
      String(curso_id).trim() === '' ||
      String(ano_letivo).trim() === '' ||
      String(epoca).trim() === ''
    ) {
      console.error('[createPauta] campos inválidos (raw req.body):', {
        uc_id,
        curso_id,
        ano_letivo,
        epoca,
        keys: req?.body ? Object.keys(req.body) : [],
        types: {
          uc_id: typeof uc_id,
          curso_id: typeof curso_id,
          ano_letivo: typeof ano_letivo,
          epoca: typeof epoca
        }
      });
      req.session.flash = { error: 'Todos os campos são obrigatórios' };
      return res.redirect('/funcionario/pauta/nova');
    }


    // Normalizar para strings (evita valores inesperados vindos do formulário)
    const ucIdNorm = String(uc_id);
    const cursoIdNorm = String(curso_id);
    const anoLetivoNorm = String(ano_letivo);
    const epocaNorm = String(epoca);

    // Validações explícitas para impedir que chegue null ao índice unique
    const anoRegex = /^\d{4}\/\d{4}$/;
    const epocasValidas = ['Normal', 'Recurso', 'Especial'];

    if (!anoRegex.test(anoLetivoNorm) || !epocasValidas.includes(epocaNorm)) {
      req.session.flash = { error: 'Ano letivo ou época inválidos' };
      return res.redirect('/funcionario/pauta/nova');
    }

    // Usar valores normalizados daqui para a frente
    const uc_id_norm = ucIdNorm;
    const curso_id_norm = cursoIdNorm;
    const ano_letivo_norm = anoLetivoNorm;
    const epoca_norm = epocaNorm;



    // Verificar se UC e Curso existem
    const uc = await UnidadeCurricular.findById(uc_id);
    const curso = await Curso.findById(curso_id);

    if (!uc || !curso) {
      req.session.flash = { error: 'UC ou Curso não encontrados' };
      return res.redirect('/funcionario/pauta/nova');
    }

    // Validar epocas (já validada acima com normalização)
    if (!['Normal', 'Recurso', 'Especial'].includes(epocaNorm)) {
      req.session.flash = { error: 'Época inválida' };
      return res.redirect('/funcionario/pauta/nova');
    }


    // Verificar se pauta já existe
    // Importante: usa os valores normalizados (evita null/undefined parar no índice unique)
    const pautaExistente = await Pauta.findOne({
      uc_id: uc_id_norm,
      curso_id: curso_id_norm,
      ano_letivo: ano_letivo_norm,
      epoca: epoca_norm
    }).lean();


    // Se já existe, não bloqueamos o fluxo: garantimos/atualizamos apenas as notas.
    if (pautaExistente) {
      const pauta = await Pauta.findById(pautaExistente._id);
    const matriculas = await Matricula.find({
      curso_id,
      ano_letivo,
      estado: { $in: ['pendente', 'aprovada'] }
    }).populate('aluno_id');

      // Resumo para visualização imediata após criar/garantir notas
      req.session.pautaResumo = {
        pautaId: String(pauta._id),
        ucId: String(pauta.uc_id ?? uc_id_norm),
        cursoId: String(pauta.curso_id ?? curso_id_norm),
        anoLetivo: pauta.ano_letivo,
        epoca: pauta.epoca,
        alunos: matriculas.length
      };

      for (const matricula of matriculas) {
        if (!matricula?.aluno_id?._id) {
          throw new Error('Matrícula sem aluno_id populado ao criar notas');
        }

        await Nota.findOneAndUpdate(
          { pauta_id: pauta._id, aluno_id: matricula.aluno_id._id },
          { $setOnInsert: { nota_final: null } },
          { upsert: true, new: false }
        );
      }

      req.session.flash = {
        success: `Pauta já existia. Notas garantidas para ${matriculas.length} alunos.`
      };
      return res.redirect(`/funcionario/pauta/${pauta._id}/notas`);
    }



    // Criar pauta
    // Proteção extra: nunca inserir no índice unique com uc/curso/ano_letivo inválidos.
    if (!uc_id_norm || !curso_id_norm || !ano_letivo_norm) {
      req.session.flash = { error: 'Dados inválidos para criar pauta' };
      return res.redirect('/funcionario/pauta/nova');
    }

    const pauta = new Pauta({
      uc_id: uc_id_norm,
      curso_id: curso_id_norm,
      ano_letivo: ano_letivo_norm,
      epoca: epoca_norm,

      criada_por: req.session.user.id,
      criada_em: new Date(),
      fechada: false
    });

    await pauta.save();


    // Buscar alunos inscritos neste curso (só os que têm matrícula aprovada)
    const matriculas = await Matricula.find({
      curso_id,
      ano_letivo,
      estado: { $in: ['pendente', 'aprovada'] }
    }).populate('aluno_id');

    // Criar registros de notas para cada aluno (idempotente para evitar colisões do índice unique)
    for (const matricula of matriculas) {
      if (!matricula?.aluno_id?._id) {
        throw new Error('Matrícula sem aluno_id populado ao criar notas');
      }

      await Nota.findOneAndUpdate(
        { pauta_id: pauta._id, aluno_id: matricula.aluno_id._id },
        { $setOnInsert: { nota_final: null } },
        { upsert: true, new: false }
      );
    }


    // Resumo para visualização imediata após criação
    req.session.pautaResumo = {
      pautaId: String(pauta._id),
      ucId: String(pauta.uc_id ?? uc_id_norm),
      cursoId: String(pauta.curso_id ?? curso_id_norm),
      anoLetivo: pauta.ano_letivo,
      epoca: pauta.epoca,
      alunos: matriculas.length
    };

    req.session.flash = { 
      success: `Pauta criada com sucesso! ${matriculas.length} alunos adicionados.` 
    };
    res.redirect(`/funcionario/pauta/${pauta._id}/notas`);
  } catch (error) {
    // Normaliza mensagem para facilitar diagnóstico (principalmente erros de validação/duplicidade Mongo)
    const message = error?.message || 'Erro desconhecido ao criar pauta';
    console.error('Erro ao criar pauta:', { message, stack: error?.stack, code: error?.code });

    // Se for erro de chave duplicada, dá um feedback melhor
    // Inclui também o message original (curto) para identificarmos qual coleção/índice está duplicando.
    const flashError = message.toLowerCase().includes('duplicate key')
      ? `Duplicado: ${message}`
      : `Erro ao criar pauta: ${message}`;


    req.session.flash = { error: flashError };
    res.redirect('/funcionario/pauta/nova');
  }
}

