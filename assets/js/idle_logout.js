// Auto logout after 10 minutes of no mouse/keyboard/touch activity.
// Activity time is shared through localStorage so multiple open tabs
// don't log each other out while one of them is in use.
(function () {
  var TIMEOUT = 10 * 60 * 1000;   // 10 minutes
  var PING_EVERY = 60 * 1000;     // keep server session alive at most once a minute
  var KEY = 'tcea_last_activity';
  var lastPing = 0;
  var lastLocal = Date.now();

  function now() { return Date.now(); }

  function getLast() {
    try { return parseInt(localStorage.getItem(KEY), 10) || lastLocal; } catch (e) { return lastLocal; }
  }

  function setLast(t) {
    lastLocal = t;
    try { localStorage.setItem(KEY, t); } catch (e) {}
  }

  function logout() {
    window.location.href = '../logout.php?timeout=1';
  }

  function ping() {
    lastPing = now();
    fetch('../session_ping.php', { credentials: 'same-origin' })
      .then(function (r) { return r.json(); })
      .then(function (d) { if (d.status === 'expired') logout(); })
      .catch(function () {});
  }

  function onActivity() {
    setLast(now());
    if (now() - lastPing > PING_EVERY) ping();
  }

  ['mousemove', 'mousedown', 'keydown', 'scroll', 'touchstart', 'click'].forEach(function (ev) {
    document.addEventListener(ev, onActivity, { passive: true });
  });

  setLast(now());
  lastPing = now(); // the page load itself already refreshed the session

  setInterval(function () {
    if (now() - getLast() >= TIMEOUT) logout();
  }, 15 * 1000);
})();
