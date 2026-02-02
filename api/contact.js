export default async function handler(req, res) {
  res.setHeader("Content-Type", "application/json");

  // 🔒 Método permitido
  if (req.method !== "POST") {
    return res.status(405).json({
      success: false,
      message: "Método não permitido.",
    });
  }

  // 📥 Dados
  const { name, email, message } = req.body || {};

  // ✅ Validações
  if (!name || !email || !message) {
    return res.status(400).json({
      success: false,
      message: "Por favor, preencha todos os campos.",
    });
  }

  if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
    return res.status(400).json({
      success: false,
      message: "Email inválido.",
    });
  }

  try {
    // 📤 Envio para o Resend
    const response = await fetch("https://api.resend.com/emails", {
      method: "POST",
      headers: {
        Authorization: `Bearer ${process.env.RESEND_API_KEY}`,
        "Content-Type": "application/json",
      },
      body: JSON.stringify({
        from: "Site iVip <onboarding@resend.dev>", // ✅ domínio verificado
        to: ["v.emanuel.pacheco@gmail.com"],
        subject: "Novo contato do site iVip",
        html: `
          <p><strong>Nome:</strong> ${escapeHtml(name)}</p>
          <p><strong>Email:</strong> ${escapeHtml(email)}</p>
          <p><strong>Mensagem:</strong><br>${escapeHtml(message)}</p>
        `,
      }),
    });

    const data = await response.json();

    // ❌ Falha real do Resend
    if (!response.ok) {
      console.error("❌ RESEND ERROR:", data);

      return res.status(500).json({
        success: false,
        message: "Falha ao enviar email.",
        error: data,
      });
    }

    // ✅ Sucesso real
    return res.status(200).json({
      success: true,
      message: "Mensagem enviada com sucesso!",
    });
  } catch (err) {
    console.error("🔥 SERVER ERROR:", err);

    return res.status(500).json({
      success: false,
      message: "Erro interno no servidor.",
    });
  }
}

// 🧼 Sanitização básica (anti-XSS)
function escapeHtml(str) {
  return String(str).replace(
    /[&<>"']/g,
    (s) =>
      ({
        "&": "&amp;",
        "<": "&lt;",
        ">": "&gt;",
        '"': "&quot;",
        "'": "&#39;",
      })[s],
  );
}
