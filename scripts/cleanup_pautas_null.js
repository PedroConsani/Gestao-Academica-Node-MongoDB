import dotenv from 'dotenv';
import mongoose from 'mongoose';
import PautaModel from '../src/Models/Pauta.js';

dotenv.config();

// Usa a mesma configuração do app quando possível.
// No projeto a variável usada no app é MONGODB_URI.
const uri = process.env.MONGODB_URI;



async function main() {
  if (!uri) {
    console.error('Definir process.env.MONGO_URI antes de executar este script.');
    process.exit(1);
  }

  await mongoose.connect(uri);
  // Remover qualquer pauta com campos nulos em *qualquer* uma das 3 chaves que entram no índice unique.
  // (O teu erro dupKey indica ucId/cursoId/anoLetivo a null, mas pode ocorrer apenas parte deles.)
  const res = await PautaModel.deleteMany({
    $or: [
      { uc_id: null },
      { curso_id: null },
      { ano_letivo: null }
    ]
  });

  console.log('Deleted:', res.deletedCount);
  await mongoose.disconnect();
}

main().catch(err => {
  console.error(err);
  process.exit(1);
});


