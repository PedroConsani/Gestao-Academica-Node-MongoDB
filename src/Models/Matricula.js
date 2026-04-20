import mongoose from 'mongoose';

const matriculaSchema = new mongoose.Schema({
  aluno_id: {
    type: mongoose.Schema.Types.ObjectId,
    ref: 'Utilizador',
    required: true
  },
  curso_id: {
    type: mongoose.Schema.Types.ObjectId,
    ref: 'Curso',
    required: true
  },
  ano_letivo: {
    type: String,
    required: [true, 'Ano letivo é obrigatório'],
    // ex: 2024/2025
    match: /^\d{4}\/\d{4}$/
  },
  observacoes_aluno: String,
  // Fluxo
  estado: {
    type: String,
    enum: ['pendente', 'aprovada', 'rejeitada'],
    default: 'pendente'
  },
  observacoes_func: String,
  decidido_por: {
    type: mongoose.Schema.Types.ObjectId,
    ref: 'Utilizador'
  },
  decidido_em: Date,
  criado_em: {
    type: Date,
    default: Date.now
  }
}, { collection: 'matriculas', timestamps: true });

// Índice para buscar matrículas por aluno e ano letivo
matriculaSchema.index({ aluno_id: 1, ano_letivo: 1 });

export default mongoose.model('Matricula', matriculaSchema);
