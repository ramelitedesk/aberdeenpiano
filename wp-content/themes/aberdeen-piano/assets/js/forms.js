/**
 * Aberdeen Piano — form validation and AJAX submission.
 *
 * Live validation as the visitor types, then an AJAX submit that leaves the
 * page in place and prints the confirmation under the button.
 *
 * These rules mirror inc/forms.php exactly. The server is authoritative — this
 * is here so mistakes are caught before the visitor presses the button.
 */
(function () {
  'use strict';

  var config = window.aberdeenPianoForms;

  if (!config || !window.fetch) {
    return;
  }

  var LETTER = /\p{L}/u;

  /* ---------------------------------------------------------------------
   * Rules — one object per field type.
   *   filter(): strips characters that may never be typed.
   *   validate(): returns an error string, or '' when the value is valid.
   * ------------------------------------------------------------------- */
  var rules = {
    name: {
      filter: function (v) {
        return v
          .replace(/[^\p{L} ]/gu, '')  // letters and spaces only
          .replace(/^ +/, '')          // never start with a space
          .replace(/ {2,}/g, ' ')      // collapse runs of spaces to one
          .slice(0, 20);
      },
      validate: function (v) {
        v = v.trim();
        if (!v) { return config.i18n.nameRequired; }
        if (/\d/u.test(v)) { return config.i18n.nameNoNumbers; }
        if (!/^\p{L}+(?: \p{L}+)*$/u.test(v)) { return config.i18n.nameChars; }
        if (v.length < 3) { return config.i18n.nameShort; }
        if (v.length > 20) { return config.i18n.nameLong; }
        return '';
      }
    },

    email: {
      // Deliberately does NOT strip disallowed characters. Silently deleting a
      // character from an address turns john_doe@x.com into johndoe@x.com — a
      // plausible but wrong address that then passes validation, so the lead is
      // captured unreachable. Spaces are still blocked; anything else the rule
      // rejects is reported to the visitor instead.
      filter: function (v) {
        return v.replace(/\s+/g, '').slice(0, 254);
      },
      validate: function (v) {
        v = v.trim();
        if (!v) { return config.i18n.emailRequired; }
        if (!/^[A-Za-z0-9@.]+$/.test(v)) { return config.i18n.emailChars; }
        if (!/^[A-Za-z0-9]+(?:\.[A-Za-z0-9]+)*@[A-Za-z0-9]+(?:\.[A-Za-z0-9]+)*\.[A-Za-z]{2,}$/.test(v)) {
          return config.i18n.emailFormat;
        }
        return '';
      }
    },

    tel: {
      filter: function (v) {
        // A leading + is kept for international numbers; everything else is
        // digits and single spaces.
        var plus = v.charAt(0) === '+' ? '+' : '';
        return plus + v
          .replace(/[^\d ]/g, '')
          .replace(/^ +/, '')
          .replace(/ {2,}/g, ' ')
          .slice(0, 24);
      },
      validate: function (v) {
        v = v.trim();
        if (!v) { return config.i18n.phoneRequired; }
        if (LETTER.test(v)) { return config.i18n.phoneNoLetters; }
        if (!/^\+?\d+(?: \d+)*$/.test(v)) { return config.i18n.phoneChars; }
        var digits = v.replace(/\D/g, '').length;
        if (digits < 7) { return config.i18n.phoneShort; }
        if (digits > 15) { return config.i18n.phoneLong; }
        return '';
      }
    },

    subject: {
      filter: function (v) { return v.slice(0, 150); },
      validate: function () { return ''; }
    },

    textarea: {
      filter: function (v) { return v.slice(0, 2000); },
      validate: function () { return ''; }
    }
  };

  /** The rule set for a field, from its data-validate attribute. */
  function ruleFor(field) {
    return rules[field.dataset.validate] || null;
  }

  /** Show or clear the message under a field. */
  function setError(field, message) {
    var wrap = field.closest('.ap-field') || field.parentNode;
    var slot = wrap.querySelector('.ap-error');

    if (!slot) {
      slot = document.createElement('span');
      slot.className = 'ap-error';
      wrap.appendChild(slot);
    }

    slot.textContent = message || '';
    wrap.classList.toggle('has-error', !!message);
    field.setAttribute('aria-invalid', message ? 'true' : 'false');

    if (message) {
      if (!slot.id) { slot.id = field.id + '-error'; }
      field.setAttribute('aria-describedby', slot.id);
    } else {
      field.removeAttribute('aria-describedby');
    }
  }

  /** Validate one field, optionally staying quiet while it is still being typed. */
  function check(field, quiet) {
    var rule = ruleFor(field);
    if (!rule) { return true; }

    // An optional field left blank is valid. The rules themselves always
    // demand a value — correct for the home page, where every field is
    // required — so optionality is handled here, matching the server, which
    // passes `required` into aberdeen_piano_validate_field().
    if (!field.required && !field.value.trim()) {
      setError(field, '');
      return true;
    }

    var error = rule.validate(field.value);

    // The subject and message rules accept anything, so a required one that is
    // left blank has to be caught here. The server enforces the same thing.
    if (!error && field.required && !field.value.trim()) {
      error = config.i18n.fieldRequired;
    }

    // While typing, only show an error once the visitor has entered something.
    setError(field, quiet && !field.value ? '' : error);

    return !error;
  }

  /** Print the form-level message under the button. */
  function setFormMessage(form, text, kind) {
    var slot = form.querySelector('[data-form-message]');
    if (!slot) { return; }

    slot.textContent = text || '';
    slot.className = 'form-message' + (kind ? ' is-' + kind : '');
    slot.hidden = !text;
  }

  document.querySelectorAll('form[data-ap-form]').forEach(function (form) {
    var fields = [].slice.call(form.querySelectorAll('[data-validate]'));
    var button = form.querySelector('button[type="submit"]');
    var busy = false;

    fields.forEach(function (field) {
      // Block characters that may never appear, as they are typed.
      field.addEventListener('input', function () {
        var rule = ruleFor(field);
        if (!rule) { return; }

        var start = field.selectionStart;
        var before = field.value;
        var after = rule.filter(before);

        if (after !== before) {
          field.value = after;
          // Keep the caret where the visitor expects it.
          var delta = before.length - after.length;
          try { field.setSelectionRange(Math.max(0, start - delta), Math.max(0, start - delta)); } catch (e) {}
        }

        check(field, true);
      });

      // Stop a space from ever starting the value, or doubling up.
      field.addEventListener('keydown', function (e) {
        if (e.key !== ' ') { return; }

        var value = field.value;
        var pos = field.selectionStart;

        if (!value.trim() || pos === 0 || value.charAt(pos - 1) === ' ') {
          e.preventDefault();
        }
      });

      field.addEventListener('blur', function () {
        field.value = field.value.trim();
        check(field, false);
      });
    });

    form.addEventListener('submit', function (e) {
      e.preventDefault();

      if (busy) { return; }

      var valid = true;
      var first = null;

      fields.forEach(function (field) {
        if (!check(field, false) && !first) {
          first = field;
          valid = false;
        } else if (!check(field, false)) {
          valid = false;
        }
      });

      if (!valid) {
        // The per-field errors already say what is wrong, so no summary line
        // is shown under the button — just clear any stale message.
        setFormMessage(form, '', '');
        if (first) { first.focus(); }
        return;
      }

      busy = true;
      form.classList.add('is-sending');
      setFormMessage(form, config.i18n.sending, 'sending');

      var original = button ? button.textContent : '';
      if (button) {
        button.disabled = true;
        button.textContent = config.i18n.sending;
      }

      var body = new URLSearchParams(new FormData(form));
      body.set('action', config.action);
      body.set('nonce', config.nonce);

      fetch(config.ajaxUrl, {
        method: 'POST',
        credentials: 'same-origin',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8' },
        body: body.toString()
      })
        .then(function (r) { return r.json().then(function (j) { return { ok: r.ok, json: j }; }); })
        .then(function (res) {
          var data = res.json && res.json.data ? res.json.data : {};

          if (res.json && res.json.success) {
            form.reset();
            fields.forEach(function (f) { setError(f, ''); });
            setFormMessage(form, data.message || config.i18n.thanks, 'success');
            return;
          }

          if (data.errors) {
            Object.keys(data.errors).forEach(function (key) {
              var field = form.querySelector('[name="' + key + '"]');
              if (field) { setError(field, data.errors[key]); }
            });

            // Field-level errors are shown on the fields themselves; the
            // summary line stays hidden, as it does for client-side checks.
            setFormMessage(form, '', '');
            return;
          }

          setFormMessage(form, data.message || config.i18n.error, 'error');
        })
        .catch(function () {
          setFormMessage(form, config.i18n.error, 'error');
        })
        .then(function () {
          busy = false;
          form.classList.remove('is-sending');
          if (button) {
            button.disabled = false;
            button.textContent = original;
          }
        });
    });
  });
})();
