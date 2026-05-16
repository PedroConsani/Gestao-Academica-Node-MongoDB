import FichaAluno from '../models/FichaAluno.js';
import Matricula from '../models/Matricula.js';
import Pauta from '../models/Pauta.js';
import Nota from '../models/Nota.js';
import Utilizador from '../models/Utilizador.js';
import Curso from '../models/Curso.js';

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

    // Buscar cursos ativos para o dropdown
    const cursos = await Curso.find({ ativo: true }).select('_id nome codigo descricao');

    res.render('aluno/ficha', {
      title: 'Minha Ficha',
      ficha,
      cursos
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

    // Se for via fetch, devolve JSON para o front-end não depender de redirect
    const wantsJson = (req.headers.accept || '').includes('application/json');
    if (wantsJson) {
      return res.status(200).json({
        success: true,
        redirectUrl: '/aluno/ficha',
        ficha: {
          _id: ficha._id,
          estado: ficha.estado,
          curso_id: ficha.curso_id
        }
      });
    }

    req.session.flash = { success: 'Ficha atualizada com sucesso!' };
    res.redirect('/aluno/ficha');
  } catch (error) {
    console.error('Erro ao atualizar ficha:', error);
    const wantsJson = (req.headers.accept || '').includes('application/json');
    if (wantsJson) {
      return res.status(500).json({ error: 'Erro ao atualizar ficha' });
    }

    res.status(500).render('error', { 
      title: 'Erro', 
      message: 'Erro ao atualizar ficha' 
    });
  }
}


export async function submitFicha(req, res) {
  try {
    const userId = req.session.user.id;
    const { 
      curso_id, data_nascimento, nacionalidade, nif, cc, 
      telefone, morada, codigo_postal, localidade 
    } = req.body;

    let ficha = await FichaAluno.findOne({ aluno_id: userId });

    if (!ficha) {
      return res.status(404).json({ error: 'Ficha não encontrada' });
    }

    // Garantir mínimo para não submeter ficha incompleta
    const cursoIdFinal = curso_id || String(ficha.curso_id || '');
    if (!cursoIdFinal || String(cursoIdFinal).trim() === '') {
      const wantsJson = (req.headers.accept || '').includes('application/json');
      if (wantsJson) {
        return res.status(422).json({ error: 'Curso é obrigatório para submeter a ficha.' });
      }
      req.session.flash = { error: 'Curso é obrigatório para submeter a ficha.' };
      return res.redirect('/aluno/ficha');
    }

    // Atualizar dados da ficha ANTES de submeter
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

    // Marcar como submetida
    ficha.estado = 'submetida';
    ficha.submetida_em = new Date();
    
    await ficha.save();


    // Se for via fetch, devolve JSON para o front-end não depender de redirect
    const wantsJson = (req.headers.accept || '').includes('application/json');
    if (wantsJson) {
      return res.status(200).json({
        success: true,
        redirectUrl: '/aluno/ficha'
      });
    }

    req.session.flash = { success: 'Ficha submetida para validação!' };
    res.redirect('/aluno/ficha');
  } catch (error) {
    console.error('Erro ao submeter ficha:', error);
    const wantsJson = (req.headers.accept || '').includes('application/json');
    if (wantsJson) {
      return res.status(500).json({ error: 'Erro ao submeter ficha' });
    }

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

export async function showMatricula(req, res) {
  try {
    const userId = req.session.user.id;
    const { id } = req.params;

    const matricula = await Matricula.findOne({ _id: id, aluno_id: userId })
      .populate('curso_id', 'nome codigo')
      .populate('decidido_por', 'nome');

    if (!matricula) {
      return res.status(404).render('shared/404', { title: 'Página não encontrada' });
    }

    // Buscar o plano de estudos do curso
    const PlanoEstudos = await import('../models/PlanoEstudos.js').then(m => m.default);
    const planoEstudos = await PlanoEstudos.find({ curso_id: matricula.curso_id._id })
      .populate('uc_id', 'nome codigo creditos descricao')
      .sort({ ano: 1, semestre: 1 });

    // Buscar as notas do aluno para as disciplinas deste curso
    const notasMap = {};
    if (planoEstudos.length > 0) {
      const ucIds = planoEstudos.map(pe => pe.uc_id._id);
      
      // Buscar pautas para as UCs do curso
      const Pauta = await import('../models/Pauta.js').then(m => m.default);
      const pautas = await Pauta.find({ 
        uc_id: { $in: ucIds },
        curso_id: matricula.curso_id._id,
        ano_letivo: matricula.ano_letivo 
      });

      if (pautas.length > 0) {
        const pautaIds = pautas.map(p => p._id);
        
        // Buscar notas do aluno para essas pautas
        const notas = await Nota.find({
          aluno_id: userId,
          pauta_id: { $in: pautaIds }
        }).populate('pauta_id', 'uc_id epoca');

        // Organizar notas por UC
        notas.forEach(nota => {
          const ucId = nota.pauta_id.uc_id.toString();
          if (!notasMap[ucId]) {
            notasMap[ucId] = [];
          }
          notasMap[ucId].push({
            epoca: nota.pauta_id.epoca,
            nota: nota.nota_final
          });
        });
      }
    }

    res.render('aluno/matricula-detalhes', {
      title: 'Detalhes da Matrícula',
      matricula,
      planoEstudos,
      notasMap
    });
  } catch (error) {
    console.error('Erro ao mostrar matrícula:', error);
    return res.status(500).render('error', {
      title: 'Erro',
      message: 'Erro ao mostrar matrícula'
    });
  }
}

export async function showMatriculaNova(req, res) {
  try {
    const userId = req.session.user.id;
    
    // Verificar se a ficha do aluno está aprovada
    const ficha = await FichaAluno.findOne({ aluno_id: userId });
    
    if (!ficha || ficha.estado !== 'aprovada') {
      return res.render('aluno/matricula-nova', {
        title: 'Nova Matrícula',
        cursos: [],
        error: ficha ? 
          `Sua ficha está em estado "${ficha.estado}". Aguarde até que seja aprovada para solicitar matrícula.` :
          'Crie sua ficha de aluno primeiro para poder solicitar matrícula.'
      });
    }

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

    // Verificar se a ficha do aluno está aprovada
    const ficha = await FichaAluno.findOne({ aluno_id: userId });
    
    if (!ficha || ficha.estado !== 'aprovada') {
      const Curso = await import('../models/Curso.js').then(m => m.default);
      const cursos = await Curso.find({ ativo: true }).select('nome codigo');
      
      return res.render('aluno/matricula-nova', {
        title: 'Nova Matrícula',
        cursos,
        error: ficha ? 
          `Sua ficha está em estado "${ficha.estado}". Aguarde até que seja aprovada para solicitar matrícula.` :
          'Crie sua ficha de aluno primeiro para poder solicitar matrícula.'
      });
    }

    // Verificar se já tem matrícula para este ano
    const existing = await Matricula.findOne({ 
      aluno_id: userId, 
      ano_letivo,
      curso_id 
    });

    if (existing) {
      const Curso = await import('../models/Curso.js').then(m => m.default);
      const cursos = await Curso.find({ ativo: true }).select('nome codigo');
      
      return res.render('aluno/matricula-nova', {
        title: 'Nova Matrícula',
        cursos,
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

export async function listNotas(req, res) {
  try {
    const userId = req.session.user.id;
    
    // Buscar todas as notas do aluno
    const notas = await Nota.find({ aluno_id: userId })
      .populate({
        path: 'pauta_id',
        select: 'uc_id ano_letivo epoca',
        populate: {
          path: 'uc_id',
          select: 'nome codigo creditos'
        }
      })
      .sort({ 'pauta_id.ano_letivo': -1 });

    // Agrupar por UC para melhor visualização
    const notasPorUC = {};
    notas.forEach(nota => {
      if (nota.pauta_id && nota.pauta_id.uc_id) {
        const ucId = nota.pauta_id.uc_id._id;
        if (!notasPorUC[ucId]) {
          notasPorUC[ucId] = {
            uc: nota.pauta_id.uc_id,
            notas: []
          };
        }
        notasPorUC[ucId].notas.push(nota);
      }
    });

    res.render('aluno/notas', {
      title: 'Minhas Notas',
      notas,
      notasPorUC,
      totalNotas: notas.length
    });
  } catch (error) {
    console.error('Erro ao listar notas:', error);
    res.status(500).render('error', { 
      title: 'Erro', 
      message: 'Erro ao carregar notas' 
    });
  }
}

export async function showNotasCurso(req, res) {
  try {
    const userId = req.session.user.id;
    const { id: cursoId } = req.params;

    // Verificar se aluno está matriculado neste curso
    const matricula = await Matricula.findOne({
      aluno_id: userId,
      curso_id: cursoId,
      estado: 'aprovada'
    }).populate('curso_id', 'nome codigo');

    if (!matricula) {
      return res.status(404).render('error', { 
        title: 'Erro', 
        message: 'Você não está matriculado neste curso' 
      });
    }

    // Buscar notas deste aluno neste curso
    const notas = await Nota.find({ aluno_id: userId })
      .populate({
        path: 'pauta_id',
        select: 'uc_id ano_letivo epoca curso_id',
        populate: {
          path: 'uc_id',
          select: 'nome codigo creditos'
        }
      })
      .sort({ 'pauta_id.ano_letivo': -1 });

    // Filtrar notas só do curso atual
    const notasCurso = notas.filter(nota => 
      nota.pauta_id && nota.pauta_id.curso_id && 
      nota.pauta_id.curso_id.toString() === cursoId
    );

    // Calcular média
    const notasValidas = notasCurso.filter(n => n.nota_final !== null && n.nota_final !== undefined);
    const media = notasValidas.length > 0 
      ? (notasValidas.reduce((sum, n) => sum + n.nota_final, 0) / notasValidas.length).toFixed(2)
      : null;

    res.render('aluno/notas-curso', {
      title: `Notas - ${matricula.curso_id.nome}`,
      curso: matricula.curso_id,
      notas: notasCurso,
      media,
      totalNotas: notasValidas.length
    });
  } catch (error) {
    console.error('Erro ao carregar notas do curso:', error);
    res.status(500).render('error', { 
      title: 'Erro', 
      message: 'Erro ao carregar notas do curso' 
    });
  }
}
