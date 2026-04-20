import mongoose from 'mongoose';

const notaSchema = new mongoose.Schema({
  pauta_id: {
    type: mongoose.Schema.Types.ObjectId,
    ref: 'Pauta',
    required: true
  },
  aluno_id: {
    type: mongoose.Schema.Types.ObjectId,
    ref: 'Utilizador',
    required: true
  },
  nota_final: {
    type: Number,
    min: 0,
    max: 20,
    default: null
  },
  editado_por: {
    type: mongoose.Schema.Types.ObjectId,
    ref: 'Utilizador'
  },
  editado_em: Date,
  criado_em: {
    type: Date,
    default: Date.now
  }
}, { collection: 'notas', timestamps: true });

// Índice único para evitar duplicatas
notaSchema.index({ pauta_id: 1, aluno_id: 1 }, { unique: true });

export default mongoose.model('Nota', notaSchema);
