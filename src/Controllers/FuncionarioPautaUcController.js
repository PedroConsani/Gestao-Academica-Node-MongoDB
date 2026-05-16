import PlanoEstudos from '../models/PlanoEstudos.js';

export async function ucsPorCurso(req, res) {
  try {
    const { curso_id } = req.query;

    if (!curso_id) {
      return res.status(400).json({ error: 'curso_id é obrigatório' });
    }

    const planos = await PlanoEstudos.find({ curso_id })
      .populate({
        path: 'uc_id',
        select: 'nome codigo'
      })
      .lean();

    const ucs = (planos || [])
      .map(p => p.uc_id)
      .filter(Boolean)
      .reduce((acc, uc) => {
        const key = String(uc._id);
        acc[key] = uc;
        return acc;
      }, {});

    res.json({
      success: true,
      ucs: Object.values(ucs)
    });
  } catch (error) {
    console.error('Erro ao obter UCs por curso:', error);
    res.status(500).json({ error: 'Erro ao obter UCs por curso' });
  }
}

