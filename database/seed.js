import dotenv from 'dotenv';
import { connectDB, disconnectDB } from '../src/config/database.js';
import Utilizador from '../src/models/Utilizador.js';
import Curso from '../src/models/Curso.js';
import UnidadeCurricular from '../src/models/UnidadeCurricular.js';
import PlanoEstudos from '../src/models/PlanoEstudos.js';
import FichaAluno from '../src/models/FichaAluno.js';
import Matricula from '../src/models/Matricula.js';
import bcrypt from 'bcryptjs';

dotenv.config();

async function seed() {
  try {
    console.log('🌱 Iniciando seed do banco de dados...');
    
    await connectDB();

    // Limpar dados existentes - APENAS SE AMBIENTE FOR "RESET"
    const shouldClean = process.argv.includes('--reset');
    if (shouldClean) {
      console.log('🗑️  Limpando dados existentes...');
      await Promise.all([
        Utilizador.deleteMany({}),
        Curso.deleteMany({}),
        UnidadeCurricular.deleteMany({}),
        PlanoEstudos.deleteMany({}),
        FichaAluno.deleteMany({}),
        Matricula.deleteMany({})
      ]);
    } else {
      console.log('📝 Modo preservação: Dados existentes serão mantidos');
      console.log('💡 Use: npm run seed -- --reset (para limpar e repovoar)');
    }

    // ==================== UTILIZADORES ====================
    console.log('👥 Criando utilizadores...');
    
    const gestorHash = await bcrypt.hash('gestor123', 12);
    const funcHash = await bcrypt.hash('func123', 12);
    const alunoHash = await bcrypt.hash('aluno123', 12);

    const gestor = await Utilizador.create({
      nome: 'Dr. João Pedagógico',
      email: 'gestor@academia.pt',
      password_hash: gestorHash,
      role: 'gestor'
    });

    const funcionario = await Utilizador.create({
      nome: 'Maria Secretária',
      email: 'func@academia.pt',
      password_hash: funcHash,
      role: 'funcionario'
    });

    const aluno1 = await Utilizador.create({
      nome: 'Pedro Silva',
      email: 'pedro@academia.pt',
      password_hash: alunoHash,
      role: 'aluno'
    });

    const aluno2 = await Utilizador.create({
      nome: 'Sofia Costa',
      email: 'sofia@academia.pt',
      password_hash: alunoHash,
      role: 'aluno'
    });

    const aluno3 = await Utilizador.create({
      nome: 'João Santos',
      email: 'joao@academia.pt',
      password_hash: alunoHash,
      role: 'aluno'
    });

    // ==================== CURSOS ====================
    console.log('📚 Criando cursos...');
    
    const cursos = await Curso.create([
      {
        nome: 'Licenciatura em Informática',
        codigo: 'LIC-INFO',
        descricao: 'Programa de 3 anos em Ciências da Computação e Tecnologia',
        duracao_anos: 3,
        criado_por: gestor._id
      },
      {
        nome: 'Licenciatura em Engenharia Electrotécnica',
        codigo: 'LIC-ENG',
        descricao: 'Programa de 3 anos em Engenharia Electrotécnica',
        duracao_anos: 3,
        criado_por: gestor._id
      },
      {
        nome: 'Mestrado em Data Science',
        codigo: 'MES-DS',
        descricao: 'Programa de 2 anos em Data Science',
        duracao_anos: 2,
        criado_por: gestor._id
      }
    ]);

    // ==================== UNIDADES CURRICULARES ====================
    console.log('📖 Criando unidades curriculares...');
    
    const ucs = await UnidadeCurricular.create([
      {
        nome: 'Programação I',
        codigo: 'PROG1',
        descricao: 'Introdução à Programação',
        creditos: 6,
        criado_por: gestor._id
      },
      {
        nome: 'Programação II',
        codigo: 'PROG2',
        descricao: 'Programação Avançada',
        creditos: 6,
        criado_por: gestor._id
      },
      {
        nome: 'Bases de Dados',
        codigo: 'BD',
        descricao: 'Sistemas de Gestão de Bases de Dados',
        creditos: 6,
        criado_por: gestor._id
      },
      {
        nome: 'Redes de Computadores',
        codigo: 'REDES',
        descricao: 'Fundamentos de Redes e Telecomunicações',
        creditos: 6,
        criado_por: gestor._id
      },
      {
        nome: 'Engenharia de Software',
        codigo: 'SE',
        descricao: 'Metodologias e Processos de Desenvolvimento',
        creditos: 6,
        criado_por: gestor._id
      },
      {
        nome: 'Matemática para Engenharia',
        codigo: 'MAT-ENG',
        descricao: 'Aplicações de Matemática em Engenharia',
        creditos: 6,
        criado_por: gestor._id
      }
    ]);

    // ==================== PLANO DE ESTUDOS ====================
    console.log('📅 Criando plano de estudos...');
    
    // Para Licenciatura em Informática
    await PlanoEstudos.create([
      // 1º Ano
      { curso_id: cursos[0]._id, uc_id: ucs[0]._id, ano: 1, semestre: 1, obrigatoria: true },
      { curso_id: cursos[0]._id, uc_id: ucs[5]._id, ano: 1, semestre: 1, obrigatoria: true },
      { curso_id: cursos[0]._id, uc_id: ucs[1]._id, ano: 1, semestre: 2, obrigatoria: true },
      { curso_id: cursos[0]._id, uc_id: ucs[2]._id, ano: 1, semestre: 2, obrigatoria: true },
      // 2º Ano
      { curso_id: cursos[0]._id, uc_id: ucs[3]._id, ano: 2, semestre: 1, obrigatoria: true },
      { curso_id: cursos[0]._id, uc_id: ucs[4]._id, ano: 2, semestre: 2, obrigatoria: true },
      // Para Engenharia
      { curso_id: cursos[1]._id, uc_id: ucs[5]._id, ano: 1, semestre: 1, obrigatoria: true },
      { curso_id: cursos[1]._id, uc_id: ucs[2]._id, ano: 1, semestre: 2, obrigatoria: true }
    ]);

    // ==================== FICHAS DE ALUNO ====================
    console.log('📝 Criando fichas de aluno...');
    
    await FichaAluno.create([
      {
        aluno_id: aluno1._id,
        curso_id: cursos[0]._id,
        estado: 'aprovada',
        data_nascimento: new Date('2003-05-15'),
        nacionalidade: 'Portuguesa',
        nif: '123456789',
        cc: '12345678',
        telefone: '911234567',
        morada: 'Rua A, 123',
        codigo_postal: '4000-001',
        localidade: 'Porto',
        validada_em: new Date(),
        validada_por: gestor._id
      },
      {
        aluno_id: aluno2._id,
        curso_id: cursos[1]._id,
        estado: 'submetida',
        data_nascimento: new Date('2003-08-20'),
        nacionalidade: 'Portuguesa',
        nif: '987654321',
        cc: '87654321',
        telefone: '912345678',
        morada: 'Rua B, 456',
        codigo_postal: '2700-000',
        localidade: 'Amadora',
        submetida_em: new Date()
      },
      {
        aluno_id: aluno3._id,
        curso_id: cursos[0]._id,
        estado: 'rascunho',
        telefone: '913345678'
      }
    ]);

    // ==================== MATRICULAS ====================
    console.log('🎓 Criando matrículas...');
    
    await Matricula.create([
      {
        aluno_id: aluno1._id,
        curso_id: cursos[0]._id,
        ano_letivo: '2024/2025',
        estado: 'aprovada',
        decidido_por: funcionario._id,
        decidido_em: new Date()
      },
      {
        aluno_id: aluno2._id,
        curso_id: cursos[1]._id,
        ano_letivo: '2024/2025',
        estado: 'pendente'
      },
      {
        aluno_id: aluno3._id,
        curso_id: cursos[0]._id,
        ano_letivo: '2024/2025',
        estado: 'pendente'
      },
      {
        aluno_id: aluno1._id,
        curso_id: cursos[0]._id,
        ano_letivo: '2023/2024',
        estado: 'aprovada',
        decidido_por: funcionario._id,
        decidido_em: new Date(Date.now() - 365 * 24 * 60 * 60 * 1000)
      }
    ]);

    console.log('✅ Seed completado com sucesso!');
    console.log('\n📋 Utilizadores de teste:');
    console.log('👤 Gestor: gestor@academia.pt / gestor123');
    console.log('👤 Funcionário: func@academia.pt / func123');
    console.log('👤 Aluno 1: pedro@academia.pt / aluno123');
    console.log('👤 Aluno 2: sofia@academia.pt / aluno123');
    console.log('👤 Aluno 3: joao@academia.pt / aluno123');
    
  } catch (error) {
    console.error('❌ Erro durante seed:', error);
    process.exit(1);
  } finally {
    await disconnectDB();
  }
}

seed();
