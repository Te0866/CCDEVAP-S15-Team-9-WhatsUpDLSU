(function () {
    try {
        document.addEventListener('DOMContentLoaded', function () {
            // Show/hide password toggles
            document.querySelectorAll('.toggle-visibility').forEach(function (btn) {
                btn.addEventListener('click', function () {
                    const targetId = btn.getAttribute('data-target');
                    const input = document.getElementById(targetId);
                    const isHidden = input.type === 'password';
                    input.type = isHidden ? 'text' : 'password';
                    btn.textContent = isHidden ? 'Hide' : 'Show';
                });
            });

            // Confirm password live match check
            const password = document.getElementById('password');
            const confirmPassword = document.getElementById('confirmPassword');
            const matchMessage = document.getElementById('matchMessage');

            function checkMatch() {
                if (confirmPassword.value.length === 0) {
                    matchMessage.textContent = 'Must match the password above.';
                    matchMessage.className = 'hint';
                    confirmPassword.classList.remove('input-error');
                    return;
                }
                if (password.value === confirmPassword.value) {
                    matchMessage.textContent = 'Passwords match.';
                    matchMessage.className = 'hint hint-success';
                    confirmPassword.classList.remove('input-error');
                } else {
                    matchMessage.textContent = 'Passwords do not match.';
                    matchMessage.className = 'hint hint-error';
                    confirmPassword.classList.add('input-error');
                }
            }

            password.addEventListener('input', checkMatch);
            confirmPassword.addEventListener('input', checkMatch);

            // Modal helper stuff
            function showSuccess(message) {
                document.getElementById("successMessage").textContent = message;
                document.getElementById("successModal").classList.add("show");
            }

            function showError(message) {
                document.getElementById("errorMessage").textContent = message;
                document.getElementById("errorModal").classList.add("show");
            }

            document.getElementById("successOkBtn").addEventListener("click", () => {
                document.getElementById("successModal").classList.remove("show");
                window.location.href = 'login.html';
            });

            document.getElementById("errorOkBtn").addEventListener("click", () => {
                document.getElementById("errorModal").classList.remove("show");
            });

            // Send to the backend
            document.getElementById('registerForm').addEventListener('submit', async function (e) {
                e.preventDefault();

                if (password.value !== confirmPassword.value) {
                    checkMatch();
                    confirmPassword.focus();
                    return;
                }

                const payload = {
                    username: document.getElementById('username').value.trim(),
                    password: password.value,
                    confirmPassword: confirmPassword.value
                };

                const submitBtn = document.querySelector('.btn-primary');
                submitBtn.disabled = true;
                submitBtn.textContent = 'Creating account.';

                try {
                    console.log('Sending payload:', payload);

                    const response = await fetch('register.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify(payload)
                    });

                    console.log('Response status:', response.status);

                    const rawText = await response.text();
                    console.log('Raw response:', rawText);
                    const result = JSON.parse(rawText);
                    console.log('Response data:', result);

                    if (result.success) {
                        showSuccess('Account created successfully! Please log in.');
                    } else {
                        showError(result.error || 'Could not create account.');
                    }
                } catch (err) {
                    console.error('Fetch error:', err);
                    showError('Something went wrong while creating your account. Please check the console for details.');
                } finally {
                    submitBtn.disabled = false;
                    submitBtn.textContent = 'Create Account';
                }
            });
        });
    } catch (err) {
        console.error("[script.js] error in login/js/create-account.js:", err);
    }
})();
