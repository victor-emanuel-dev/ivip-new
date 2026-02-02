export default async function handler(req, res) {
  res.setHeader("Content-Type", "application/json");

  if (req.method !== "POST") {
    return res
      .status(405)
      .json({ success: false, message: "Método não permitido." });
  }

  const { name, email, message } = req.body || {};

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
    await fetch("https://api.resend.com/emails", {
      method: "POST",
      headers: {
        Authorization: `Bearer ${process.env.RESEND_API_KEY}`,
        "Content-Type": "application/json",
      },
      body: JSON.stringify({
        from: "Site iVip 'iVip <onboarding@resend.dev>'",
        to: ["v.emanuel.pacheco@gmail.com"],
        subject: "Novo contato do site iVip",
        html: `
          <p><strong>Nome:</strong> ${escape(name)}</p>
          <p><strong>Email:</strong> ${escape(email)}</p>
          <p><strong>Mensagem:</strong><br>${escape(message)}</p>
        `,
      }),
    });

    return res.status(200).json({
      success: true,
      message: "Mensagem enviada com sucesso!",
    });
  } catch (err) {
    console.error(err);
    return res.status(500).json({
      success: false,
      message: "Erro ao enviar mensagem. Tente novamente.",
    });
  }
}

function escape(str) {
  return String(str).replace(
    /[&<>"']/g,
    (s) =>
      ({ "&": "&amp;", "<": "&lt;", ">": "&gt;", '"': "&quot;", "'": "&#39;" })[
        s
      ],
  );
}
