@once
<!-- Shared validation CSS & helper JS (guarded) -->
<style>
  .field-valid { border-color: #16a34a !important; box-shadow: 0 0 0 4px rgba(16,185,129,0.12) !important; transition: box-shadow 120ms ease, border-color 120ms ease; }
  .field-invalid { border-color: #ef4444 !important; box-shadow: 0 0 0 4px rgba(239,68,68,0.12) !important; transition: box-shadow 120ms ease, border-color 120ms ease; }
  .select2-container--default .select2-selection.field-valid { border-color: #16a34a !important; box-shadow: 0 0 0 4px rgba(16,185,129,0.12) !important; }
  .select2-container--default .select2-selection.field-invalid { border-color: #ef4444 !important; box-shadow: 0 0 0 4px rgba(239,68,68,0.12) !important; }
  .error-message { color: #dc2626; font-size: 0.875rem; margin-top: 0.25rem; margin-bottom: 0.5rem; display: block; }
  .error-message .icon { margin-right: 6px; vertical-align: text-bottom; }
</style>

<script>
if (!window._rsud_validation_loaded) {
  window._rsud_validation_loaded = true;
  // Core visual helper functions (exposed globally). Do not overwrite if already present.
  window.showFieldError = window.showFieldError || function(field, message) {
    try {
      if (window.jQuery && jQuery(field).data('select2')) {
        const container = jQuery(field).next('.select2-container').find('.select2-selection');
        container.removeClass('field-valid').addClass('field-invalid');
      } else {
        field.classList.remove('field-valid');
        field.classList.add('field-invalid');
      }
    } catch(e) { try { field.classList.add('field-invalid'); } catch(e){} }
    // nicer inline message with optional icon
    // For Select2 fields, insert error message after the Select2 container
    // For regular fields, insert after the field itself
    let insertAfter = field;
    try {
      if (window.jQuery && jQuery(field).data('select2')) {
        insertAfter = jQuery(field).next('.select2-container')[0];
      }
    } catch(e) {}
    
    if (!insertAfter) insertAfter = field;
    const next = insertAfter.nextSibling;
    if (!(next && next.classList && next.classList.contains('error-message'))) {
      const d = document.createElement('div');
      d.className = 'error-message';
      d.textContent = message || 'Field tidak valid';
      insertAfter.parentNode.insertBefore(d, insertAfter.nextSibling);
    } else if (next) {
      next.textContent = message || 'Field tidak valid';
    }
  };

  window.removeFieldError = window.removeFieldError || function(field) {
    try {
      if (window.jQuery && jQuery(field).data('select2')) {
        const container = jQuery(field).next('.select2-container').find('.select2-selection');
        container.removeClass('field-invalid');
      } else {
        field.classList.remove('field-invalid');
      }
    } catch(e) { try { field.classList.remove('field-invalid'); } catch(e){} }

    // For Select2 fields, look for error message after the Select2 container
    // For regular fields, look after the field itself
    let searchAfter = field;
    try {
      if (window.jQuery && jQuery(field).data('select2')) {
        searchAfter = jQuery(field).next('.select2-container')[0];
      }
    } catch(e) {}
    
    if (searchAfter) {
      const next = searchAfter.nextSibling;
      if (next && next.classList && next.classList.contains('error-message')) next.remove();
    }
  };

  window.markFieldValid = window.markFieldValid || function(field) {
    removeFieldError(field);
    try {
      if (window.jQuery && jQuery(field).data('select2')) {
        const container = jQuery(field).next('.select2-container').find('.select2-selection');
        container.removeClass('field-invalid').addClass('field-valid');
      } else {
        field.classList.remove('field-invalid');
        field.classList.add('field-valid');
      }
    } catch(e) { try { field.classList.add('field-valid'); } catch(e){} }
  };

  window.resetFieldState = window.resetFieldState || function(field) {
    removeFieldError(field);
    try {
      if (window.jQuery && jQuery(field).data('select2')) {
        const container = jQuery(field).next('.select2-container').find('.select2-selection');
        container.removeClass('field-invalid field-valid');
      } else {
        field.classList.remove('field-invalid');
        field.classList.add('field-valid');
      }
    } catch(e) { try { field.classList.add('field-valid'); } catch(e){} }
    field.classList.remove('field-invalid', 'field-valid');
    field.classList.add('border-gray-300', 'dark:border-gray-600');
  };

  // Backwards-compatible helpers used by dummy pages
  window._vp_showInvalid = window._vp_showInvalid || function(el, msg) { showFieldError(el, msg); };
  window._vp_showValid = window._vp_showValid || function(el) { markFieldValid(el); };

  // Attach minimal real-time required-field check for any form controls that do not have app-specific validation
  document.addEventListener('DOMContentLoaded', function() {
    const forms = document.querySelectorAll('form');
    forms.forEach(form => {
        // Ensure no client-side validation visuals are shown on initial load.
        // This prevents the red borders/messages from appearing before the user
        // clicks the per-step "Lanjut" button.
        const initialControls = form.querySelectorAll('input[required], select[required], textarea[required]');
        initialControls.forEach(ic => {
          try { resetFieldState(ic); } catch(e){}
          const next = ic.nextSibling; if (next && next.classList && next.classList.contains('error-message')) next.remove();
        });

      // Collect required controls. By default we DO NOT validate on blur;
      // validation is performed explicitly when the user clicks the "Lanjut"
      // button (which calls `validateStep(step)`) or when the form is submitted.
      // To enable the previous blur-behavior for a specific form, add the
      // attribute `data-validate-on-blur="true"` to the form element.
      const controls = form.querySelectorAll('input[required], select[required], textarea[required]');
      const formValidateOnBlur = form.hasAttribute('data-validate-on-blur');
      controls.forEach(c => {
        // per-field overrides: if an input has `data-validate-always` or
        // `data-validate-on-blur`, it will receive a blur handler even when the
        // form does not have `data-validate-on-blur`.
        const perFieldAlways = c.hasAttribute('data-validate-always');
        const perFieldOnBlur = c.hasAttribute('data-validate-on-blur');
        const shouldAttachBlur = formValidateOnBlur || perFieldAlways || perFieldOnBlur;

        if (shouldAttachBlur) {
          // Use a closure-captured flag because `c` will change in the loop.
          const always = perFieldAlways;
          const optOnBlur = perFieldOnBlur;
          c.addEventListener('blur', function() {
            const v = (this.value || '').toString().trim();
            if (always) {
              // This field must always be validated on blur — show error when empty
              if (!v) {
                _vp_showInvalid(this, 'Field wajib diisi');
              } else {
                _vp_showValid(this);
              }
            } else if (formValidateOnBlur || optOnBlur) {
              // For form-level blur or per-field on-blur without 'always', only
              // validate if the user has entered something; don't show "wajib diisi"
              // for untouched/empty fields.
              if (!v) {
                removeFieldError(this);
              } else {
                _vp_showValid(this);
              }
            }
          });
        }

        // keep input listener to clear previous errors while typing
        c.addEventListener('input', function() {
          const next = this.nextSibling; if (next && next.classList && next.classList.contains('error-message')) next.remove();
          this.classList.remove('field-invalid'); this.classList.remove('field-valid');
        });
      });
      
      // Handle radio groups: mark valid on change
      const radios = form.querySelectorAll('input[type="radio"]');
      const radioNames = new Set();
      radios.forEach(r => radioNames.add(r.name));
      radioNames.forEach(name => {
        if (!name) return;
        const group = form.querySelectorAll(`input[type="radio"][name="${name}"]`);
        group.forEach(r => {
          r.addEventListener('change', function() {
            // remove existing group error messages
            const any = Array.from(group).some(x => x.checked);
            if (any) {
              // mark first radio's parent as valid
              group.forEach(x => {
                // try to remove sibling error messages
                const parent = x.closest('div') || x.parentNode;
                try { removeFieldError(x); } catch(e){}
              });
            }
          });
        });
      });

      // Validate on submit: only attach this handler if the form explicitly
      // opts into submit-time validation by adding
      // `data-validate-on-submit="true"`. By default we DO NOT block submit
      // so frontend validation only happens when user clicks the per-step
      // `Lanjut`/`nextStep` buttons.
      if (form.hasAttribute('data-validate-on-submit')) {
        form.addEventListener('submit', function(ev) {
          let firstInvalid = null;
          // check text/select/textarea required
          controls.forEach(c => {
            const v = (c.value || '').toString().trim();
            if (!v) {
              _vp_showInvalid(c, 'Field wajib diisi');
              if (!firstInvalid) firstInvalid = c;
            } else {
              _vp_showValid(c);
            }
          });

          // check radio groups with required attribute on any member
          radioNames.forEach(name => {
            const group = form.querySelectorAll(`input[type="radio"][name="${name}"]`);
            if (!group || group.length === 0) return;
            // if any radio in group has required attribute, validate the group
            const requires = Array.from(group).some(r => r.hasAttribute('required'));
            if (!requires) return;
            const anyChecked = Array.from(group).some(r => r.checked);
            if (!anyChecked) {
              // mark the first radio's container as invalid by placing an error message after that input
              const first = group[0];
              _vp_showInvalid(first, 'Silakan pilih salah satu opsi');
              if (!firstInvalid) firstInvalid = first;
            }
          });

          if (firstInvalid) {
            ev.preventDefault();
            try { firstInvalid.scrollIntoView({ behavior: 'smooth', block: 'center' }); firstInvalid.focus(); } catch(e){}
          }
        });
      }
    });
  });
}
</script>

@endonce