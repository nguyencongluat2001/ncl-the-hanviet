(function () {
  var config = window.IV || {};
  var csrf = document.querySelector('meta[name="csrf-token"]').content;

  /* ---------- Hộp thoại ---------- */
  document.querySelectorAll('[data-open]').forEach(function (button) {
    button.addEventListener('click', function () {
      var dialog = document.querySelector(button.dataset.open);
      if (dialog && dialog.showModal) dialog.showModal();
    });
  });
  document.querySelectorAll('.iv-modal').forEach(function (dialog) {
    dialog.addEventListener('click', function (e) {
      // Bấm ra ngoài khung thì đóng
      if (e.target === dialog || e.target.closest('[data-close]')) dialog.close();
    });
  });

  /* ---------- Gửi form (lời chúc, xác nhận tham dự) ---------- */
  function bindForm(form, onSuccess) {
    if (!form) return;
    var message = form.querySelector('.iv-form__msg');
    form.addEventListener('submit', function (e) {
      e.preventDefault();
      message.classList.remove('is-ok');
      if (config.readonly || !form.dataset.action) {
        message.textContent = 'Đây là bản xem trước, không gửi được.';
        return;
      }
      var button = form.querySelector('button[type="submit"]');
      button.disabled = true;
      message.textContent = '';
      fetch(form.dataset.action, {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': csrf, 'Accept': 'application/json' },
        body: new FormData(form),
      })
        .then(function (response) {
          return response.json().then(function (body) { return { ok: response.ok, body: body }; });
        })
        .then(function (result) {
          if (!result.ok) {
            var errors = result.body.errors ? Object.values(result.body.errors)[0][0] : (result.body.message || 'Có lỗi xảy ra, vui lòng thử lại.');
            message.textContent = errors;
            return;
          }
          form.reset();
          onSuccess(result.body, message);
        })
        .catch(function () { message.textContent = 'Không gửi được, vui lòng kiểm tra mạng.'; })
        .finally(function () { button.disabled = false; });
    });
  }

  bindForm(document.querySelector('.iv-wish-form'), function (body, message) {
    message.textContent = 'Cảm ơn lời chúc của bạn!';
    message.classList.add('is-ok');
    var card = document.createElement('article');
    card.className = 'iv-card iv-wish is-new';
    var header = document.createElement('header');
    var name = document.createElement('b');
    name.textContent = body.wish.name;
    var time = document.createElement('time');
    time.textContent = body.wish.time;
    var text = document.createElement('p');
    text.textContent = body.wish.message;
    header.append(name, time);
    card.append(header, text);
    document.querySelector('.iv-wishes').prepend(card);
  });

  bindForm(document.querySelector('.iv-rsvp-form'), function (body, message) {
    message.textContent = 'Đã gửi xác nhận. Cảm ơn bạn!';
    message.classList.add('is-ok');
    setTimeout(function () { document.getElementById('iv-rsvp').close(); message.textContent = ''; }, 1800);
  });

  /* ---------- Sao chép số tài khoản ---------- */
  document.querySelectorAll('[data-copy]').forEach(function (button) {
    button.addEventListener('click', function () {
      navigator.clipboard.writeText(button.dataset.copy).then(function () {
        var old = button.innerHTML;
        button.textContent = 'Đã sao chép';
        setTimeout(function () { button.innerHTML = old; }, 1500);
      });
    });
  });

  /* ---------- Nhạc nền: phát sau lần chạm đầu tiên ---------- */
  var music = document.getElementById('iv-music');
  var musicButton = document.querySelector('.iv-music');
  if (music && musicButton) {
    var toggle = function () {
      if (music.paused) music.play().catch(function () {}); else music.pause();
    };
    music.addEventListener('play', function () { musicButton.classList.add('is-playing'); });
    music.addEventListener('pause', function () { musicButton.classList.remove('is-playing'); });
    musicButton.addEventListener('click', function (e) { e.stopPropagation(); toggle(); });
    if (!config.editing) {
      document.addEventListener('click', function first() {
        document.removeEventListener('click', first);
        if (music.paused) music.play().catch(function () {});
      });
    }
  }

  /* ---------- Xem trước trong trình chỉnh sửa ---------- */
  if (config.editing) {
    // Giữ vị trí cuộn khi khung xem trước tải lại sau mỗi lần lưu
    var key = 'iv-preview-scroll';
    try {
      var saved = sessionStorage.getItem(key);
      if (saved) window.scrollTo(0, parseInt(saved, 10));
    } catch (e) {}
    window.addEventListener('scroll', function () {
      try { sessionStorage.setItem(key, String(window.scrollY)); } catch (e) {}
    }, { passive: true });

    // Cập nhật chữ ngay khi gõ, không cần chờ tải lại
    window.addEventListener('message', function (e) {
      if (e.origin !== window.location.origin || !e.data || e.data.type !== 'iv:text') return;
      document.querySelectorAll('[data-bind="' + e.data.path + '"]').forEach(function (el) {
        el.textContent = e.data.value;
      });
    });

    // Bấm vào một phần trên thiệp thì trình chỉnh sửa mở đúng mục đó
    document.addEventListener('click', function (e) {
      var bound = e.target.closest('[data-bind]');
      if (bound && window.parent !== window) {
        window.parent.postMessage({ type: 'iv:focus', path: bound.dataset.bind }, window.location.origin);
      }
    });
  }
})();
