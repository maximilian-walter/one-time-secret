import { APP_BASE_URI, APP_CSRF_TOKEN } from './constants';
import { decrypt } from './lib/encryption';

const widget = document.querySelector('[data-widget="show-secret"]');
if (widget) {
  const labels = JSON.parse(widget.dataset.labels ?? '{}');
  const showEl = widget.querySelector('[data-target="show-secret.show"]');
  const resultEl = widget.querySelector('[data-target="show-secret.result"]');
  const errorEl = widget.querySelector('[data-target="show-secret.error"]');

  if (showEl) {
    showEl.addEventListener('click', async (event) => {
      event.preventDefault();

      const response = await fetch(APP_BASE_URI + '/api/secrets/' + encodeURIComponent(widget.dataset.id), {
        headers: {
          'X-Csrf-Token': APP_CSRF_TOKEN,
        },
      });

      if (200 === response.status) {
        showEl.hidden = true;

        const body = await response.json();
        try {
          const key = window.location.hash.substring(1);
          const secret = decrypt(body.secret, key);

          if (resultEl) {
            resultEl.hidden = false;
            resultEl.value = secret;
          }
        } catch (error) {
          if (errorEl) {
            errorEl.hidden = false;
          }
        }
      } else if (429 === response.status) {
        if (resultEl) {
          resultEl.hidden = true;
        }

        alert(labels.rateLimitReached ?? 'Please wait, you have reached the rate limit');
      } else {
        alert(labels.error ?? 'Sorry, an error occurred');
      }
    });
  }

  if (resultEl) {
    resultEl.addEventListener('focus', () => {
      resultEl.select();
    }, false);
  }
}
