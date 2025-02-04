function initializePasswordToggles() {
    document.querySelectorAll('.password-input-group').forEach(group => {
        const input = group.querySelector('input[type="password"]');
        const button = group.querySelector('.password-toggle');
        const icon = button.querySelector('i');

        button.addEventListener('click', function() {
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.remove('bi-eye-slash');
                icon.classList.add('bi-eye');
            } else {
                input.type = 'password';
                icon.classList.remove('bi-eye');
                icon.classList.add('bi-eye-slash');
            }
        });
    });
}
