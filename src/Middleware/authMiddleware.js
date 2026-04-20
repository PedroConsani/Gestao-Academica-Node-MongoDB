export function authMiddleware(req, res, next) {
  if (!req.session.user) {
    req.session.flash = { error: 'Sessão expirada. Faça login novamente.' };
    return res.redirect('/auth/login?expired=true');
  }

  // Verificar inatividade
  const now = Date.now();
  const lastActivity = req.session.lastActivity || now;
  const sessionLifetime = (process.env.SESSION_LIFETIME || 1800) * 1000;

  if (now - lastActivity > sessionLifetime) {
    req.session.destroy();
    req.session.flash = { error: 'Sessão expirada. Faça login novamente.' };
    return res.redirect('/auth/login?expired=true');
  }

  req.session.lastActivity = now;
  next();
}

export function roleMiddleware(allowedRoles = []) {
  return (req, res, next) => {
    if (!allowedRoles.includes(req.session.user.role)) {
      return res.status(403).render('shared/403', { 
        title: 'Acesso Proibido',
        message: 'Você não tem permissão para acessar esta página.' 
      });
    }
    next();
  };
}

export function guestMiddleware(req, res, next) {
  if (req.session.user) {
    return res.redirect(`/${req.session.user.role}/dashboard`);
  }
  next();
}
