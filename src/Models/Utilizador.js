import mongoose from 'mongoose';

const utilizadorSchema = new mongoose.Schema({
  nome: {
    type: String,
    required: [true, 'Nome é obrigatório'],
    trim: true,
    maxlength: 150
  },
  email: {
    type: String,
    required: [true, 'Email é obrigatório'],
    unique: true,
    lowercase: true,
    trim: true,
    maxlength: 150
  },
  password_hash: {
    type: String,
    required: [true, 'Password é obrigatória'],
    minlength: 8
  },
  role: {
    type: String,
    enum: ['aluno', 'funcionario', 'gestor'],
    default: 'aluno'
  },
  ativo: {
    type: Boolean,
    default: true
  },
  criado_em: {
    type: Date,
    default: Date.now
  },
  atualizado_em: {
    type: Date,
    default: Date.now
  }
}, { collection: 'utilizadores', timestamps: true });

export default mongoose.model('Utilizador', utilizadorSchema);
