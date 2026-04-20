import mongoose from 'mongoose';

const unidadeCurricularSchema = new mongoose.Schema({
  nome: {
    type: String,
    required: [true, 'Nome da UC é obrigatório'],
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
  creditos: {
    type: Number,
    default: 6.0,
    min: 0
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
  }
}, { collection: 'unidades_curriculares', timestamps: true });

export default mongoose.model('UnidadeCurricular', unidadeCurricularSchema);
