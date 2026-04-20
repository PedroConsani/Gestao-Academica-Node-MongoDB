import mongoose from 'mongoose';

const cursoSchema = new mongoose.Schema({
  nome: {
    type: String,
    required: [true, 'Nome do curso é obrigatório'],
    trim: true,
    maxlength: 200
  },
  codigo: {
    type: String,
    required: [true, 'Código é obrigatório'],
    unique: true,
    uppercase: true,
    trim: true,
    maxlength: 20
  },
  descricao: String,
  duracao_anos: {
    type: Number,
    default: 3,
    min: 1,
    max: 5
  },
  ativo: {
    type: Boolean,
    default: true
  },
  criado_por: {
    type: mongoose.Schema.Types.ObjectId,
    ref: 'Utilizador',
    required: true
  },
  criado_em: {
    type: Date,
    default: Date.now
  },
  atualizado_em: {
    type: Date,
    default: Date.now
  }
}, { collection: 'cursos', timestamps: true });

export default mongoose.model('Curso', cursoSchema);
