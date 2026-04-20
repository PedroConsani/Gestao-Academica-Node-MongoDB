import mongoose from 'mongoose';

const fichaAlunoSchema = new mongoose.Schema({
  aluno_id: {
    type: mongoose.Schema.Types.ObjectId,
    ref: 'Utilizador',
    required: true,
    unique: true
  },
  curso_id: {
    type: mongoose.Schema.Types.ObjectId,
    ref: 'Curso'
  },
  // Dados pessoais
  data_nascimento: Date,
  nacionalidade: {
    type: String,
    maxlength: 80
  },
  nif: {
    type: String,
    maxlength: 20
  },
  cc: {
    type: String,
    maxlength: 20
  },
  telefone: {
    type: String,
    maxlength: 20
  },
  morada: {
    type: String,
    maxlength: 255
  },
  codigo_postal: {
    type: String,
    maxlength: 10
  },
  localidade: {
    type: String,
    maxlength: 100
  },
  foto_path: String,
  // Fluxo
  estado: {
    type: String,
    enum: ['rascunho', 'submetida', 'aprovada', 'rejeitada'],
    default: 'rascunho'
  },
  observacoes: String,
  submetida_em: Date,
  validada_por: {
    type: mongoose.Schema.Types.ObjectId,
    ref: 'Utilizador'
  },
  validada_em: Date,
  criado_em: {
    type: Date,
    default: Date.now
  },
  atualizado_em: {
    type: Date,
    default: Date.now
  }
}, { collection: 'fichas_aluno', timestamps: true });

export default mongoose.model('FichaAluno', fichaAlunoSchema);
