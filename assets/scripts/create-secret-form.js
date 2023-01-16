import { nanoid } from 'nanoid';
import { generateKey, encrypt } from './lib/encryption';
import copy from 'copy-to-clipboard';

const widget = document.querySelector('[data-widget="create-secret-form"]');
if (widget) {
  const labels = JSON.parse(widget.dataset.labels ?? '{}');
  const formEl = widget.querySelector('[data-target="create-secret-form.form"]');
  const resultEl = widget.querySelector('[data-target="create-secret-form.result"]');
  const linkEl = widget.querySelector('[data-target="create-secret-form.link"]');
  const copyEl = widget.querySelector('[data-target="create-secret-form.copy"]');

  if (formEl) {
    formEl.addEventListener('submit', async (event) => {
      event.preventDefault();

      if (formEl.checkValidity()) {
        const id = nanoid();
        const key = generateKey();

        const requestBody = {
          id: id,
          secret: encrypt(formEl.querySelector('textarea').value, key),
        };

        const response = await fetch(APP_BASE_URI + '/api/secrets', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'X-Csrf-Token': APP_CSRF_TOKEN,
          },
          body: JSON.stringify(requestBody),
        });

        if (201 === response.status) {
          if (resultEl) {
            resultEl.hidden = false;
          }

          if (linkEl) {
            linkEl.value = response.headers.get('Location') + '#' + encodeURIComponent(key);
            linkEl.select();
          }
        } else if (429 === response.status) {
          if (resultEl) {
            resultEl.hidden = true;
          }

          alert(labels.rateLimitReached ?? 'Please wait, you have reached the rate limit');
        } else {
          if (resultEl) {
            resultEl.hidden = true;
          }

          alert(labels.error ?? 'Sorry, an error occurred');
        }
      }
    }, false);
  }

  if (linkEl) {
    linkEl.addEventListener('focus', () => {
      linkEl.select();
    }, false);

    if (copyEl) {
      copyEl.addEventListener('click', event => {
        event.preventDefault();

        copy(linkEl.value);
        alert(labels.copied ?? 'Copied');
      }, false);
    }
  }
}
