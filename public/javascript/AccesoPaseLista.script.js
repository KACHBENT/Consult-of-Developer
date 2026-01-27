(() => {
  const input = document.getElementById('passInput');
  const btnToggle = document.getElementById('btnToggle');
  const keypadButtons = document.querySelectorAll('.btn-key');
  const btnSubmit = document.getElementById('btnSubmit');
  const btnClearAll = document.getElementById('btnClearAll');
  const msg = document.getElementById('msg');
  const dot = document.getElementById('strengthDot');
  const lengthHint = document.getElementById('lengthHint');
   const ICON_VIEW = btnToggle?.dataset.iconView
    || (window.ICONS && window.ICONS.view)
    || '/images/icons/view.svg';

  const ICON_HIDE = btnToggle?.dataset.iconHide
    || (window.ICONS && window.ICONS.hide)
    || '/images/icons/visibility_off.svg';

 
  function updateStrength() {
    const len = input.value.length;
    dot.classList.remove('dot-weak', 'dot-mid', 'dot-strong');
    if (len >= 6) { dot.classList.add('dot-strong'); lengthHint.textContent = 'Buena longitud'; }
    else if (len >= 4) { dot.classList.add('dot-mid'); lengthHint.textContent = 'Aceptable (4+)'; }
    else { dot.classList.add('dot-weak'); lengthHint.textContent = 'Mínimo 4 dígitos'; }
  }

  function showMsg(text, type = 'info') {
    msg.className = 'alert mt-3 alert-' + type;
    msg.textContent = text;
    msg.classList.remove('d-none');
  }
  function clearMsg() { msg.classList.add('d-none'); }

    btnToggle.addEventListener('click', () => {
      const isPwd = input.type === 'password';
      input.type = isPwd ? 'text' : 'password';
      btnToggle.innerHTML = isPwd
        ? `<img src="${ICON_VIEW}" class="darken" alt="Ver" width="20" height="20">`
        : `<img src="${ICON_HIDE}" class="darken" alt="Ocultar" width="20" height="20">`;
      input.focus();
    });

  keypadButtons.forEach(btn => {
    btn.addEventListener('click', () => {
      const key = btn.dataset.key;
      clearMsg();

      if (key === 'back') {
        input.value = input.value.slice(0, -1);
      } else if (key === 'clear') {
        input.value = '';
      } else {
        
        if (/^\d$/.test(key)) {
          input.value += key;
        }
      }
      updateStrength();
    });
  });

  input.addEventListener('keydown', (e) => {
    clearMsg();
    const allowed = ['Backspace', 'Delete', 'ArrowLeft', 'ArrowRight', 'Tab', 'Enter'];
    if (allowed.includes(e.key)) return;
    if (!/^\d$/.test(e.key)) e.preventDefault();
  });
  input.addEventListener('input', () => {
    input.value = input.value.replace(/\D+/g, '');
    updateStrength();
  });

  btnClearAll.addEventListener('click', () => {
    input.value = '';
    updateStrength();
    clearMsg();
    input.focus();
  });

  function submitForm() {
    const val = input.value.trim();
    if (val.length < 4) {
      showMsg('La contraseña debe tener al menos 4 dígitos.', 'warning');
      return;
    }
 
    // Por ejemplo: fetch('/asistencia/login', {method:'POST', body: JSON.stringify({pass: val}) ... })
    showMsg('Validando contraseña...', 'info');

   
    setTimeout(() => {
     
      const ok = true;
      if (ok) {
        showMsg('¡Acceso concedido! Iniciando pase de lista...', 'success');
 
      } else {
        showMsg('Contraseña incorrecta. Inténtalo de nuevo.', 'danger');
      }
    }, 600);
  }

  btnSubmit.addEventListener('click', submitForm);
  input.addEventListener('keydown', (e) => {
    if (e.key === 'Enter') { e.preventDefault(); submitForm(); }
  });

  // Init
  updateStrength();
})();