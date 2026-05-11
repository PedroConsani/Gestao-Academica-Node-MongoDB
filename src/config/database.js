import mongoose from 'mongoose';

export async function connectDB() {
  try {
    const mongoUri = process.env.MONGODB_URI;
    
    if (!mongoUri) {
      throw new Error('MONGODB_URI não configurada em .env');
    }
    
    console.log('🔌 Conectando ao MongoDB...');
    console.log('   URL:', mongoUri.substring(0, 50) + '...');
    
    await mongoose.connect(mongoUri);

    console.log('✅ MongoDB conectado com sucesso');
    return true;
  } catch (error) {
    console.error('❌ Erro ao conectar MongoDB:', error.message);
    process.exit(1);
  }
}

export async function disconnectDB() {
  try {
    await mongoose.disconnect();
    console.log('✅ MongoDB desconectado');
  } catch (error) {
    console.error('❌ Erro ao desconectar MongoDB:', error.message);
  }
}
