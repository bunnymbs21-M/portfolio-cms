document.addEventListener('DOMContentLoaded', () => {
  const form = document.getElementById('contact-form');
  const feedback = document.getElementById('form-feedback');
  const submitBtn = document.getElementById('submit-btn');

  if (!form) return;

  form.addEventListener('submit', async (e) => {
    e.preventDefault();

    feedback.className = 'feedback';
    feedback.textContent = '';
    submitBtn.disabled = true;
    submitBtn.textContent = 'Sending...';

    const payload = {
      name: form.name.value.trim(),
      email: form.email.value.trim(),
      subject: form.subject.value.trim(),
      body: form.body.value.trim()
    };

    try {
      const response = await fetch('api/contact.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(payload)
      });

      const result = await response.json();

      if (response.ok && result.success) {
        feedback.className = 'feedback success';
        feedback.textContent = result.message;
        form.reset();
      } else {
        feedback.className = 'feedback error';
        feedback.textContent = result.message || 'Error processing request.';
      }
    } catch (err) {
      feedback.className = 'feedback error';
      feedback.textContent = 'Network error. Please try again later.';
    } finally {
      submitBtn.disabled = false;
      submitBtn.textContent = 'Send Message';
    }
  });
});