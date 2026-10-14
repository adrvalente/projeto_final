
 // ============================================================
 // ScoutGest 
 // ============================================================

document.addEventListener('DOMContentLoaded', function () {

    // Procura todos os formulários identificados
    const formularios = document.querySelectorAll('#form-principal');

    formularios.forEach(function (form) {

        form.addEventListener('submit', function (event) {
            const erros = [];

            // 1. Campos obrigatórios
            form.querySelectorAll('[required]').forEach(function (campo) {

                if (campo.disabled) {
                    return;
                }

                if (campo.type === 'checkbox') {
                    if (!campo.checked) {
                        erros.push('Tens de aceitar a política de privacidade.');
                    }
                } else if (campo.value.trim() === '') {
                    const label = form.querySelector(
                        'label[for="' + campo.id + '"]'
                    );
                    const nome = label ? label.textContent : campo.name;

                    erros.push('Preenche o campo "' + nome.trim() + '".');
                }
            });

            // 2. Email com formato válido
            const email = form.querySelector('#email');

            if (email && email.value.trim() !== '') {
                const formatoEmail = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

                if (!formatoEmail.test(email.value.trim())) {
                    erros.push('O email não parece válido.');
                }
            }

            // 3. Password com pelo menos 8 caracteres
            const password = form.querySelector('#password');

            if (password && password.dataset.minimo &&
                password.value !== '' && password.value.length < 8) {
                erros.push('A password tem de ter pelo menos 8 caracteres.');
            }

            // 4. Telefone do tutor
            const telefone = form.querySelector('#telefone');

            if (telefone && telefone.value.trim() !== '') {
                const numero = telefone.value.replace(/\s/g, '');

                if (!/^\d{9}$/.test(numero)) {
                    erros.push('O telefone deve ter 9 dígitos.');
                }
            }

            // 5. Data de nascimento
            const nascimento = form.querySelector('#data_nascimento');

            if (nascimento && nascimento.value !== '') {
                const data = new Date(nascimento.value + 'T00:00:00');
                const hoje = new Date();

                hoje.setHours(0, 0, 0, 0);

                if (isNaN(data.getTime()) || data > hoje) {
                    erros.push('A data de nascimento não pode ser futura.');
                }
            }

            // 6. Número de censo
            const censo = form.querySelector('#numero_censo');

            if (censo && censo.value.trim() !== '') {
                if (!/^\d+$/.test(censo.value.trim())) {
                    erros.push('O número de censo deve conter apenas números.');
                }
            }

            // 7. Apresentar erros
            let caixa = form.querySelector('#erros-js');

            if (erros.length > 0) {
                event.preventDefault();

                if (!caixa) {
                    caixa = document.createElement('ul');
                    caixa.id = 'erros-js';
                    form.prepend(caixa);
                }

                caixa.innerHTML = '';

                erros.forEach(function (texto) {
                    const li = document.createElement('li');
                    li.textContent = texto;
                    caixa.appendChild(li);
                });

                caixa.style.display = 'block';
                caixa.scrollIntoView({ behavior: 'smooth' });

            } else if (caixa) {
                caixa.innerHTML = '';
                caixa.style.display = 'none';
            }
        });
    });
});
