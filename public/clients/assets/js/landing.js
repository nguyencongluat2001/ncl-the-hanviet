(function () {
  var reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

  /* ---------- Hiện dần khi cuộn tới ---------- */
  var revealItems = document.querySelectorAll('[data-reveal]');
  if ('IntersectionObserver' in window && !reduceMotion) {
    var revealObserver = new IntersectionObserver(function (entries) {
      entries.forEach(function (entry) {
        if (entry.isIntersecting) {
          entry.target.classList.add('is-visible');
          revealObserver.unobserve(entry.target);
        }
      });
    }, { threshold: 0.15, rootMargin: '0px 0px -40px 0px' });
    revealItems.forEach(function (el) { revealObserver.observe(el); });
  } else {
    revealItems.forEach(function (el) { el.classList.add('is-visible'); });
  }

  /* ---------- Header đổ bóng khi cuộn ---------- */
  var header = document.querySelector('.ld-header');
  if (header) {
    var onScroll = function () { header.classList.toggle('is-scrolled', window.scrollY > 8); };
    window.addEventListener('scroll', onScroll, { passive: true });
    onScroll();
  }

  /* ---------- Carousel 3D xoay vòng ---------- */
  document.querySelectorAll('[data-ring]').forEach(function (ring) {
    var items = Array.prototype.slice.call(ring.querySelectorAll('.ld-ring__item'));
    var total = items.length;
    if (!total) return;
    var current = Math.floor(total / 2);
    // Độ lệch ngang mỗi thẻ (%), thẻ ngang dùng giá trị nhỏ hơn thẻ dọc
    var step = parseFloat(getComputedStyle(ring).getPropertyValue('--ld-ring-step')) || 60;
    var dotsBox = ring.querySelector('.ld-ring__dots');
    var dots = [];
    var timer = null;
    var hovering = false;
    var inView = false;

    items.forEach(function (_, index) {
      var dot = document.createElement('button');
      dot.type = 'button';
      dot.setAttribute('aria-label', 'Mẫu ' + (index + 1));
      dot.addEventListener('click', function () { go(index); });
      dotsBox.appendChild(dot);
      dots.push(dot);
    });

    function render() {
      items.forEach(function (item, index) {
        // Khoảng cách vòng tròn tới thẻ ở giữa: -n/2 .. n/2
        var d = ((index - current) % total + total) % total;
        if (d > total / 2) d -= total;
        var abs = Math.abs(d);
        var scale = abs === 0 ? 1 : abs === 1 ? 0.85 : 0.7;
        var opacity = abs === 0 ? 1 : abs === 1 ? 0.75 : abs === 2 ? 0.5 : 0.3;
        item.style.transform = 'translateX(' + (step * d) + '%) translateZ(' + (-150 * abs) + 'px) rotateY(' + (45 * d) + 'deg) scale(' + scale + ')';
        item.style.opacity = abs > 6 ? 0 : opacity;
        item.style.zIndex = 100 - abs;
        item.classList.toggle('is-active', abs === 0);
        item.setAttribute('aria-hidden', abs === 0 ? 'false' : 'true');
      });
      dots.forEach(function (dot, index) { dot.classList.toggle('is-active', index === current); });
    }

    function go(index) {
      current = (index + total) % total;
      render();
      restart();
    }

    function restart() {
      clearInterval(timer);
      if (reduceMotion || total < 2) return;
      timer = setInterval(function () {
        if (inView && !hovering && !document.hidden) go(current + 1);
      }, 3500);
    }

    ring.querySelector('.ld-ring__nav--prev').addEventListener('click', function () { go(current - 1); });
    ring.querySelector('.ld-ring__nav--next').addEventListener('click', function () { go(current + 1); });
    ring.addEventListener('mouseenter', function () { hovering = true; });
    ring.addEventListener('mouseleave', function () { hovering = false; });
    ring.addEventListener('keydown', function (e) {
      if (e.key === 'ArrowLeft') go(current - 1);
      if (e.key === 'ArrowRight') go(current + 1);
    });

    // Vuốt / kéo để chuyển
    var startX = null;
    var dragged = false;
    var stage = ring.querySelector('.ld-ring__stage');
    stage.addEventListener('pointerdown', function (e) { startX = e.clientX; dragged = false; });
    stage.addEventListener('pointermove', function (e) {
      if (startX !== null && Math.abs(e.clientX - startX) > 10) dragged = true;
    });
    stage.addEventListener('pointerup', function (e) {
      if (startX === null) return;
      var dx = e.clientX - startX;
      startX = null;
      if (Math.abs(dx) > 40) go(current + (dx < 0 ? 1 : -1));
    });
    stage.addEventListener('pointerleave', function () { startX = null; });

    // Bấm thẻ bên cạnh thì đưa vào giữa; chỉ thẻ giữa mới mở ảnh lớn
    items.forEach(function (item, index) {
      item.addEventListener('click', function (e) {
        if (dragged || !item.classList.contains('is-active')) {
          e.preventDefault();
          e.stopImmediatePropagation();
          if (!dragged) go(index);
        }
      }, true);
    });

    if ('IntersectionObserver' in window) {
      new IntersectionObserver(function (entries) {
        inView = entries[0].isIntersecting;
      }, { threshold: 0.3 }).observe(ring);
    } else {
      inView = true;
    }

    render();
    restart();
  });

  /* ---------- Slider ảnh mờ dần (section "Trọn gói") ---------- */
  var fade = document.querySelector('.js-fade');
  if (fade && window.Swiper) {
    new Swiper(fade, {
      effect: 'fade',
      fadeEffect: { crossFade: true },
      loop: true,
      speed: 800,
      autoplay: reduceMotion ? false : { delay: 3000, disableOnInteraction: false },
      pagination: { el: fade.querySelector('.ld-carousel__dots'), clickable: true },
    });
  }

  if (window.GLightbox) GLightbox({ selector: '.glightbox' });
})();
