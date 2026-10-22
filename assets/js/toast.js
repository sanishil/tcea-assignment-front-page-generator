/* Toast notifications for every page.
   showToast(message, type) shows one; type is "success" | "error" | "warning" | "info"
   (guessed from the message when left out). window.alert is routed here too, so existing
   alert(...) calls show a toast instead of a blocking browser popup. */
(function () {
   var STYLE = '' +
      '.toast-stack{position:fixed;top:1rem;right:1rem;z-index:2000;display:flex;flex-direction:column;gap:.6rem;' +
      'width:min(380px,calc(100vw - 2rem));pointer-events:none;font-family:system-ui,-apple-system,"Segoe UI",Roboto,Arial,sans-serif}' +
      '.toast-item{position:relative;display:flex;align-items:flex-start;gap:.75rem;padding:.85rem .9rem .95rem 1rem;' +
      'background:#fff;color:#1f2937;border:1px solid #e7eaf0;border-left:4px solid var(--toast-color);border-radius:12px;' +
      'box-shadow:0 12px 32px rgba(16,24,40,.14);overflow:hidden;pointer-events:auto;' +
      'animation:toast-in .25s ease-out both}' +
      '.toast-item.hide{animation:toast-out .2s ease-in both}' +
      '.toast-icon{flex:0 0 auto;display:inline-flex;align-items:center;justify-content:center;width:26px;height:26px;' +
      'border-radius:50%;background:var(--toast-soft);color:var(--toast-color)}' +
      '.toast-body{flex:1;min-width:0;padding-top:2px}' +
      '.toast-title{font-size:.9rem;font-weight:700;margin-bottom:.1rem}' +
      '.toast-msg{font-size:.875rem;line-height:1.4;color:#4b5563;word-wrap:break-word}' +
      '.toast-close{flex:0 0 auto;display:inline-flex;align-items:center;justify-content:center;width:26px;height:26px;' +
      'margin:-2px -2px 0 0;padding:0;border:0;border-radius:6px;background:none;color:#9ca3af;cursor:pointer}' +
      '.toast-close:hover{background:#f3f4f6;color:#1f2937}' +
      '.toast-bar{position:absolute;left:0;bottom:0;height:3px;width:100%;background:var(--toast-color);opacity:.35;' +
      'transform-origin:left;animation:toast-bar linear forwards}' +
      '.toast-item:hover .toast-bar{animation-play-state:paused}' +
      '.toast-success{--toast-color:#10b981;--toast-soft:#ecfdf5}' +
      '.toast-error{--toast-color:#ef4444;--toast-soft:#fef2f2}' +
      '.toast-warning{--toast-color:#f59e0b;--toast-soft:#fffbeb}' +
      '.toast-info{--toast-color:#4f46e5;--toast-soft:#eef2ff}' +
      '@keyframes toast-in{from{opacity:0;transform:translateX(24px)}to{opacity:1;transform:none}}' +
      '@keyframes toast-out{to{opacity:0;transform:translateX(24px)}}' +
      '@keyframes toast-bar{from{transform:scaleX(1)}to{transform:scaleX(0)}}' +
      '@media (max-width:575.98px){.toast-stack{top:.75rem;right:50%;transform:translateX(50%)}' +
      '@keyframes toast-in{from{opacity:0;transform:translateY(-16px)}to{opacity:1;transform:none}}' +
      '@keyframes toast-out{to{opacity:0;transform:translateY(-16px)}}}' +
      '@media (prefers-reduced-motion:reduce){.toast-item,.toast-item.hide{animation:none}}';

   var TITLES = { success: 'Success', error: 'Error', warning: 'Attention', info: 'Notice' };

   var ICONS = {
      success: '<path d="M20 6 9 17l-5-5"/>',
      error: '<path d="M18 6 6 18M6 6l12 12"/>',
      warning: '<path d="M12 8v5M12 16.5v.5"/>',
      info: '<path d="M12 11v6M12 7.5v.5"/>'
   };

   var DURATION = 4000;
   var stack;

   function svg(paths, size) {
      return '<svg width="' + size + '" height="' + size + '" viewBox="0 0 24 24" fill="none" stroke="currentColor" ' +
         'stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">' + paths + '</svg>';
   }

   function guessType(msg) {
      if (/success/i.test(msg)) return 'success';
      if (/please|required|missing|must be|do not match|select/i.test(msg)) return 'warning';
      if (/error|fail|problem|invalid|incorrect|mismatch|sorry|unable|denied|already|not |no \w*\s?found|no data/i.test(msg)) return 'error';
      return 'info';
   }

   function ensureStack() {
      if (stack && document.body.contains(stack)) return stack;
      if (!document.getElementById('toast-style')) {
         var style = document.createElement('style');
         style.id = 'toast-style';
         style.textContent = STYLE;
         document.head.appendChild(style);
      }
      stack = document.createElement('div');
      stack.className = 'toast-stack';
      stack.setAttribute('aria-live', 'polite');
      document.body.appendChild(stack);
      return stack;
   }

   function dismiss(item) {
      if (item.classList.contains('hide')) return;
      item.classList.add('hide');
      item.addEventListener('animationend', function () { item.remove(); });
      // reduced-motion has no animation, so animationend never fires
      setTimeout(function () { item.remove(); }, 300);
   }

   function showToast(message, type) {
      message = message == null ? '' : String(message);
      type = TITLES[type] ? type : guessType(message);

      // alert() can be called before <body> exists
      if (!document.body) {
         document.addEventListener('DOMContentLoaded', function () { showToast(message, type); });
         return;
      }

      var item = document.createElement('div');
      item.className = 'toast-item toast-' + type;
      item.setAttribute('role', type === 'error' ? 'alert' : 'status');
      item.innerHTML =
         '<span class="toast-icon">' + svg(ICONS[type], 16) + '</span>' +
         '<div class="toast-body"><div class="toast-title"></div><div class="toast-msg"></div></div>' +
         '<button type="button" class="toast-close" aria-label="Close">' + svg('<path d="M18 6 6 18M6 6l12 12"/>', 14) + '</button>' +
         '<span class="toast-bar" style="animation-duration:' + DURATION + 'ms"></span>';
      // textContent so server messages are never interpreted as HTML
      item.querySelector('.toast-title').textContent = TITLES[type];
      item.querySelector('.toast-msg').textContent = message;
      item.querySelector('.toast-close').addEventListener('click', function () { dismiss(item); });
      // the progress bar pauses on hover, and the toast closes when it runs out
      item.querySelector('.toast-bar').addEventListener('animationend', function () { dismiss(item); });

      ensureStack().appendChild(item);
   }

   window.showToast = showToast;
   window.alert = function (message) { showToast(message); };
})();
