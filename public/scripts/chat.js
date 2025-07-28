
const windowConfig = document.getElementById("configBtn");

function toggleChat() {
  const chat = document.getElementById("chatWindow");
  const isOpen = chat.style.display === "flex";

  windowConfig.style.display = "none";

  chat.style.display = isOpen ? "none" : "flex";

  // Limpiar mensajes si se cierra el chat
  if (isOpen) {
    clearMessages();
      windowConfig.style.display = "block";
  }
}

async function sendMessage() {
  const input = document.getElementById("userInput");
  const msg = input.value.trim();
  if (!msg) return;

  appendMessage("user", msg);
  input.value = "";

  const formData = new FormData();
  formData.append('msg_user', msg);

  try {
    const response = await axios.post('/asistenteChatBot', formData);

    const reply = response.data.reply || "No se pudo obtener respuesta.";
    appendMessage("bot", reply);

  } catch (error) {
    console.error('Hubo un problema al contactar al asistente:', error);
    appendMessage("bot", "Hubo un error al procesar tu solicitud.");
  }
}

function appendMessage(sender, text) {
  const box = document.getElementById("chatBox");
  const div = document.createElement("div");

  div.className = "message " + sender;
  div.innerText = text;
  box.appendChild(div);
  box.scrollTop = box.scrollHeight;
}

function clearMessages() {
  const box = document.getElementById("chatBox");
  box.innerHTML = "";

   const saludo = `Hola, soy tu asistente de BeizaNet Gestión de Lotes. 👋
Hasta la fecha hay 1922 lotes registrados.
Si tienes alguna duda que no pueda responder, puedes escribir a joan@gmail.com.`;

  appendMessage("chatBox", saludo);
}

// ventana configuracion

 const configBtn = document.getElementById("configBtn");
  const configPanel = document.getElementById("configPanel");

  configBtn.addEventListener("click", () => {
    const visible = configPanel.style.display === "block";
    configPanel.style.display = visible ? "none" : "block";
  });

  function guardarConfiguracion() {
    const porcentaje = document.getElementById("porcentaje").value;
    const operacion = document.querySelector('input[name="operacion"]:checked').value;
    alert(`Configuración guardada:\nOperación: ${operacion}\nPorcentaje: ${porcentaje}%`);
  }

  function cerrarPanel() {
    configPanel.style.display = "none";
  }
