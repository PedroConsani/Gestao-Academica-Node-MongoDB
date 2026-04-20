import mongoose from 'mongoose';

const planoEstudosSchema = new mongoose.Schema({
  curso_id: {
    type: mongoose.Schema.Types.ObjectId,
    ref: 'Curso',
    required: true
  },
  uc_id: {
    type: mongoose.Schema.Types.ObjectId,
    ref: 'UnidadeCurricular',
    required: true
  },
  ano: {
    type: Number,
    required: [true, 'Ano curricular é obrigatório'],
    min: 1,
    max: 5
  },
  semestre: {
    type: Number,
    required: [true, 'Semestre é obrigatório'],
    enum: [1, 2]
  },
  obrigatoria: {
    type: Boolean,
    default: true
  }
}, { collection: 'plano_estudos', timestamps: true });

// Índice único para evitar duplicatas
planoEstudosSchema.index({ curso_id: 1, uc_id: 1 }, { unique: true });
planoEstudosSchema.index({ curso_id: 1, ano: 1, semestre: 1, uc_id: 1 }, { unique: true });

export default mongoose.model('PlanoEstudos', planoEstudosSchema);
