import mongoose from 'mongoose';

const pautaSchema = new mongoose.Schema({
  uc_id: {
    type: mongoose.Schema.Types.ObjectId,
    ref: 'UnidadeCurricular',
    required: true
  },
  curso_id: {
    type: mongoose.Schema.Types.ObjectId,
    ref: 'Curso',
    required: true
  },
  ano_letivo: {
    type: String,
    required: true,
    match: /^\d{4}\/\d{4}$/
  },
  epoca: {
    type: String,
    enum: ['Normal', 'Recurso', 'Especial'],
    required: true
  },
  criada_por: {
    type: mongoose.Schema.Types.ObjectId,
    ref: 'Utilizador',
    required: true
  },
  criada_em: {
    type: Date,
    default: Date.now
  },
  fechada: {
    type: Boolean,
    default: false
  }
}, { collection: 'pautas', timestamps: true });

// Índice único para evitar múltiplas pautas iguais
pautaSchema.index({ uc_id: 1, curso_id: 1, ano_letivo: 1, epoca: 1 }, { unique: true });

export default mongoose.model('Pauta', pautaSchema);
