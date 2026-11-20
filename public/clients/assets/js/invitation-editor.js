(function () {
  var cfg = window.EDITOR;
  var state = cfg.data;
  var template = cfg.template;
  var csrf = document.querySelector('meta[name="csrf-token"]').content;
  var iframe = document.querySelector('.ed-preview iframe');
  var statusEl = document.querySelector('.ed-status');
  var saveTimer = null;
  var saving = false;
  var dirty = false;
  var needReload = false;

  /* ---------- Đọc / ghi theo đường dẫn "a.b.c" ---------- */
  function get(path) {
    return path.split('.').reduce(function (obj, key) { return obj == null ? undefined : obj[key]; }, state);
  }
  function set(path, value) {
    var keys = path.split('.');
    var obj = state;
    keys.slice(0, -1).forEach(function (key) {
      if (obj[key] == null || typeof obj[key] !== 'object') obj[key] = {};
      obj = obj[key];
    });
    obj[keys[keys.length - 1]] = value;
  }
  function photoUrl(path) {
    return path ? cfg.baseUrl + '/' + path : '';
  }

  /* ---------- Lưu ---------- */
  function setStatus(kind, text) {
    var icons = { saved: 'bi-cloud-check', saving: 'bi-arrow-repeat', error: 'bi-exclamation-triangle', pending: 'bi-pencil' };
    statusEl.className = 'ed-status is-' + kind;
    statusEl.innerHTML = '<i class="bi ' + icons[kind] + '"></i> ' + text;
  }

  function change(reload) {
    dirty = true;
    needReload = needReload || reload;
    setStatus('pending', 'Chưa lưu');
    clearTimeout(saveTimer);
    saveTimer = setTimeout(save, 800);
  }

  function save() {
    if (saving) { saveTimer = setTimeout(save, 400); return; }
    saving = true;
    dirty = false;
    var reload = needReload;
    needReload = false;
    setStatus('saving', 'Đang lưu...');
    fetch(cfg.saveUrl, {
      method: 'POST',
      headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': csrf },
      body: JSON.stringify({ template: template, data: state }),
    })
      .then(function (response) {
        if (!response.ok) throw new Error(response.status);
        return response.json();
      })
      .then(function () {
        if (!dirty) setStatus('saved', 'Đã lưu');
        if (reload) iframe.contentWindow.location.reload();
      })
      .catch(function () {
        dirty = true;
        needReload = needReload || reload;
        setStatus('error', 'Lỗi lưu, đang thử lại...');
        saveTimer = setTimeout(save, 3000);
      })
      .finally(function () { saving = false; });
  }

  window.addEventListener('beforeunload', function (e) {
    if (dirty || saving) { e.preventDefault(); e.returnValue = ''; }
  });

  /* ---------- Ô nhập liệu thường ---------- */
  document.querySelectorAll('[data-path]').forEach(function (input) {
    var path = input.dataset.path;
    var isCheckbox = input.type === 'checkbox';
    input.addEventListener(isCheckbox ? 'change' : 'input', function () {
      var value = isCheckbox ? input.checked : input.value;
      set(path, value);
      if (input.hasAttribute('data-live')) {
        iframe.contentWindow.postMessage({ type: 'iv:text', path: path, value: value }, window.location.origin);
      }
      // Ngày/giờ: chỉ lưu khi đã nhập đủ
      if ((input.type === 'date' || input.type === 'time') && !input.value) return;
      change(input.hasAttribute('data-reload'));
    });
  });

  /* ---------- Đổi mẫu ---------- */
  document.querySelectorAll('input[name="template"]').forEach(function (radio) {
    radio.addEventListener('change', function () {
      template = radio.value;
      change(true);
    });
  });

  /* ---------- Upload ảnh ---------- */
  function upload(file) {
    var body = new FormData();
    body.append('photo', file);
    return fetch(cfg.uploadUrl, {
      method: 'POST',
      headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': csrf },
      body: body,
    }).then(function (response) {
      return response.json().then(function (json) {
        if (!response.ok) throw new Error(json.errors ? Object.values(json.errors)[0][0] : 'Không tải được ảnh.');
        return json.path;
      });
    });
  }

  // Ảnh đơn: bìa, mã QR
  document.querySelectorAll('[data-photo]').forEach(function (box) {
    var path = box.dataset.photo;
    var preview = box.querySelector('.ed-photo__preview');
    var input = box.querySelector('input[type="file"]');
    var remove = box.querySelector('[data-remove]');
    function render() {
      var value = get(path);
      preview.innerHTML = value ? '<img src="' + photoUrl(value) + '" alt="">' : '<i class="bi bi-image"></i>';
      remove.hidden = !value;
    }
    input.addEventListener('change', function () {
      if (!input.files[0]) return;
      box.classList.add('is-loading');
      upload(input.files[0])
        .then(function (uploaded) { set(path, uploaded); render(); change(true); })
        .catch(function (err) { alert(err.message); })
        .finally(function () { box.classList.remove('is-loading'); input.value = ''; });
    });
    remove.addEventListener('click', function () { set(path, null); render(); change(true); });
    render();
  });

  // Album: nhiều ảnh, kéo thả để đổi thứ tự
  var albumBox = document.querySelector('.ed-album');
  var albumCount = document.querySelector('.ed-count');
  var albumInput = document.querySelector('.ed-upload--album input');
  var dragIndex = null;

  function renderAlbum() {
    var album = state.photos.album || [];
    albumCount.textContent = '(' + album.length + '/' + cfg.maxAlbum + ')';
    albumBox.innerHTML = '';
    album.forEach(function (photo, index) {
      var item = document.createElement('div');
      item.className = 'ed-album__item';
      item.draggable = true;
      item.innerHTML = '<img src="' + photoUrl(photo) + '" alt=""><button type="button" aria-label="Xoá ảnh"><i class="bi bi-x"></i></button>';
      item.querySelector('button').addEventListener('click', function () {
        state.photos.album.splice(index, 1);
        renderAlbum();
        change(true);
      });
      item.addEventListener('dragstart', function () { dragIndex = index; item.classList.add('is-dragging'); });
      item.addEventListener('dragend', function () { item.classList.remove('is-dragging'); });
      item.addEventListener('dragover', function (e) { e.preventDefault(); });
      item.addEventListener('drop', function (e) {
        e.preventDefault();
        if (dragIndex === null || dragIndex === index) return;
        var moved = state.photos.album.splice(dragIndex, 1)[0];
        state.photos.album.splice(index, 0, moved);
        dragIndex = null;
        renderAlbum();
        change(true);
      });
      albumBox.appendChild(item);
    });
  }

  albumInput.addEventListener('change', function () {
    var files = Array.prototype.slice.call(albumInput.files, 0, cfg.maxAlbum - state.photos.album.length);
    if (!files.length) { albumInput.value = ''; return; }
    var label = albumInput.parentElement;
    label.classList.add('is-loading');
    // Tải lần lượt để giữ đúng thứ tự đã chọn
    files.reduce(function (chain, file) {
      return chain.then(function () {
        return upload(file).then(function (uploaded) {
          state.photos.album.push(uploaded);
          renderAlbum();
        });
      });
    }, Promise.resolve())
      .catch(function (err) { alert(err.message); })
      .finally(function () {
        label.classList.remove('is-loading');
        albumInput.value = '';
        change(true);
      });
  });
  renderAlbum();

  /* ---------- Lịch trình ---------- */
  var scheduleBox = document.querySelector('.ed-schedule');
  function renderSchedule() {
    scheduleBox.innerHTML = '';
    state.schedule.forEach(function (row, index) {
      var el = document.createElement('div');
      el.className = 'ed-schedule__row';
      el.innerHTML = '<input type="time"><input type="text" maxlength="100" placeholder="Hoạt động"><button type="button" aria-label="Xoá"><i class="bi bi-trash"></i></button>';
      var inputs = el.querySelectorAll('input');
      inputs[0].value = row.time;
      inputs[1].value = row.title;
      inputs[0].addEventListener('input', function () { row.time = inputs[0].value; change(true); });
      inputs[1].addEventListener('input', function () { row.title = inputs[1].value; change(true); });
      el.querySelector('button').addEventListener('click', function () {
        state.schedule.splice(index, 1);
        renderSchedule();
        change(true);
      });
      scheduleBox.appendChild(el);
    });
  }
  document.querySelector('.ed-add-schedule').addEventListener('click', function () {
    state.schedule.push({ time: '', title: '' });
    renderSchedule();
    scheduleBox.lastElementChild.querySelector('input').focus();
  });
  renderSchedule();

  /* ---------- Màu dress code ---------- */
  var colorBox = document.querySelector('.ed-colors');
  function renderColors() {
    colorBox.innerHTML = '';
    state.dress_code.colors.forEach(function (color, index) {
      var el = document.createElement('div');
      el.className = 'ed-color';
      el.innerHTML = '<input type="color" aria-label="Màu ' + (index + 1) + '"><button type="button" aria-label="Xoá màu"><i class="bi bi-x"></i></button>';
      var input = el.querySelector('input');
      input.value = color;
      input.addEventListener('input', function () { state.dress_code.colors[index] = input.value; change(true); });
      el.querySelector('button').addEventListener('click', function () {
        state.dress_code.colors.splice(index, 1);
        renderColors();
        change(true);
      });
      colorBox.appendChild(el);
    });
    if (state.dress_code.colors.length < 8) {
      var add = document.createElement('button');
      add.type = 'button';
      add.className = 'ed-color ed-color--add';
      add.setAttribute('aria-label', 'Thêm màu');
      add.innerHTML = '<i class="bi bi-plus-lg"></i>';
      add.addEventListener('click', function () {
        state.dress_code.colors.push('#cccccc');
        renderColors();
        change(true);
      });
      colorBox.appendChild(add);
    }
  }
  renderColors();

  /* ---------- Bấm chữ trên thiệp => mở đúng ô nhập ---------- */
  window.addEventListener('message', function (e) {
    if (e.origin !== window.location.origin || !e.data || e.data.type !== 'iv:focus') return;
    var input = document.querySelector('[data-path="' + e.data.path + '"]');
    if (!input) return;
    var group = input.closest('details');
    if (group) group.open = true;
    document.querySelector('.ed-layout').dataset.view = 'form';
    setTab('form');
    input.scrollIntoView({ behavior: 'smooth', block: 'center' });
    input.focus({ preventScroll: true });
    input.classList.add('is-flash');
    setTimeout(function () { input.classList.remove('is-flash'); }, 1200);
  });

  /* ---------- Tab trên mobile ---------- */
  function setTab(tab) {
    document.querySelector('.ed-layout').dataset.view = tab;
    document.querySelectorAll('.ed-tabs button').forEach(function (button) {
      button.classList.toggle('is-active', button.dataset.tab === tab);
    });
  }
  document.querySelectorAll('.ed-tabs button').forEach(function (button) {
    button.addEventListener('click', function () { setTab(button.dataset.tab); });
  });

  /* ---------- Sao chép link thiệp ---------- */
  document.querySelectorAll('[data-copy]').forEach(function (button) {
    button.addEventListener('click', function () {
      navigator.clipboard.writeText(button.dataset.copy).then(function () {
        var span = button.querySelector('span');
        var old = span.textContent;
        span.textContent = 'Đã sao chép!';
        setTimeout(function () { span.textContent = old; }, 1500);
      });
    });
  });
})();
