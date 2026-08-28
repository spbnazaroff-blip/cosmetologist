(() => {
  const header = document.querySelector('.site-header');
  const progress = document.querySelector('.scroll-progress span');
  const menuButton = document.querySelector('.menu-toggle');
  const nav = document.querySelector('.main-nav');
  const reduced = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

  document.body.classList.add('motion-ready');

  let scrollTicking = false;
  const renderScroll = () => {
    const max = Math.max(1, document.documentElement.scrollHeight - window.innerHeight);
    const p = Math.min(1, Math.max(0, window.scrollY / max));
    if (header) header.classList.toggle('scrolled', window.scrollY > 12);
    if (progress) progress.style.width = `${p * 100}%`;
    scrollTicking = false;
  };
  const onScroll = () => {
    if (scrollTicking) return;
    scrollTicking = true;
    requestAnimationFrame(renderScroll);
  };
  renderScroll();
  window.addEventListener('scroll', onScroll, { passive: true });

  if (menuButton && nav) {
    const closeMenu = () => {
      menuButton.setAttribute('aria-expanded', 'false');
      nav.classList.remove('is-open');
      document.body.classList.remove('menu-open');
    };
    menuButton.addEventListener('click', () => {
      const open = menuButton.getAttribute('aria-expanded') === 'true';
      menuButton.setAttribute('aria-expanded', String(!open));
      nav.classList.toggle('is-open', !open);
      document.body.classList.toggle('menu-open', !open);
    });
    nav.querySelectorAll('a').forEach(a => a.addEventListener('click', closeMenu));
    window.addEventListener('resize', () => {
      if (window.innerWidth > 900) closeMenu();
    }, { passive: true });
  }

  const reveals = document.querySelectorAll('.reveal');
  if (reduced || !('IntersectionObserver' in window)) {
    reveals.forEach(el => el.classList.add('is-visible'));
  } else {
    const observer = new IntersectionObserver(entries => {
      entries.forEach(entry => {
        if (!entry.isIntersecting) return;
        entry.target.classList.add('is-visible');
        observer.unobserve(entry.target);
      });
    }, { threshold: .06, rootMargin: '0px 0px -24px 0px' });
    reveals.forEach(el => observer.observe(el));
  }

  const panel = document.querySelector('[data-concern-selector]');
  if (panel) {
    const data = {
      comfort: ['Диагностика + восстановление барьера', 'Начинаем с разбора ухода и мягкого протокола на увлажнение и комфорт. Интенсивные процедуры — только после оценки реакции кожи.'],
      tone: ['Уход на сияние + работа с причиной тусклого тона', 'Сначала определяем, связан ли запрос с обезвоженностью, рельефом или пигментацией, и только потом выбираем формат обновления.'],
      texture: ['Постепенное обновление без гонки за интенсивностью', 'Текстуру корректируют системно: безопаснее последовательно оценивать переносимость, чем выбирать самый агрессивный протокол.'],
      tired: ['Комплексный уход на свежесть и тонус', 'Фокус — на качестве кожи и отдохнувшем виде. Конкретный протокол зависит от состояния кожи и индивидуальных ограничений.']
    };
    const title = panel.querySelector('[data-result-title]');
    const text = panel.querySelector('[data-result-text]');
    panel.querySelectorAll('[data-focus]').forEach(btn => btn.addEventListener('click', () => {
      panel.querySelectorAll('[data-focus]').forEach(b => b.classList.remove('is-active'));
      btn.classList.add('is-active');
      const value = data[btn.dataset.focus];
      if (!value || !title || !text) return;
      title.textContent = value[0];
      text.textContent = value[1];
    }));
  }

  document.querySelectorAll('[data-filter]').forEach(button => button.addEventListener('click', () => {
    const value = button.dataset.filter || 'all';
    const group = button.closest('[data-filter-group]');
    if (group) group.querySelectorAll('[data-filter]').forEach(b => b.classList.remove('is-active'));
    button.classList.add('is-active');
    document.querySelectorAll('[data-category]').forEach(item => {
      item.hidden = value !== 'all' && item.dataset.category !== value;
    });
  }));

  const form = document.querySelector('[data-demo-form]');
  if (form) form.addEventListener('submit', event => {
    event.preventDefault();
    const success = document.querySelector('[data-form-success]');
    if (!form.reportValidity()) return;
    form.hidden = true;
    if (success) success.hidden = false;
  });
  document.querySelectorAll('[data-form-reset]').forEach(btn => btn.addEventListener('click', () => {
    const success = document.querySelector('[data-form-success]');
    if (form) { form.reset(); form.hidden = false; }
    if (success) success.hidden = true;
  }));

  /* Stable accessible before/after slider for mouse, touch and keyboard. */
  document.querySelectorAll('.before-after').forEach(block => {
    const figures = block.querySelectorAll(':scope > figure');
    if (figures.length < 2 || block.classList.contains('ba-enhanced')) return;

    block.classList.add('ba-enhanced');
    block.style.setProperty('--split', '50%');

    const range = document.createElement('input');
    range.type = 'range';
    range.min = '5';
    range.max = '95';
    range.step = '0.1';
    range.value = '50';
    range.className = 'ba-range';
    range.setAttribute('aria-label', 'Сравнить фото до и после');

    const handle = document.createElement('span');
    handle.className = 'ba-handle';
    handle.setAttribute('aria-hidden', 'true');

    const setSplit = value => {
      const numeric = Math.max(5, Math.min(95, Number(value) || 50));
      range.value = String(numeric);
      block.style.setProperty('--split', `${numeric}%`);
    };
    const splitFromClientX = clientX => {
      const rect = block.getBoundingClientRect();
      if (!rect.width) return;
      setSplit(((clientX - rect.left) / rect.width) * 100);
    };

    range.addEventListener('input', () => setSplit(range.value));
    range.addEventListener('dblclick', () => setSplit(50));

    let activePointer = null;
    range.addEventListener('pointerdown', event => {
      activePointer = event.pointerId;
      document.body.classList.add('slider-dragging');
      try { range.setPointerCapture?.(event.pointerId); } catch (_) {}
      splitFromClientX(event.clientX);
    });
    range.addEventListener('pointermove', event => {
      if (activePointer !== event.pointerId) return;
      splitFromClientX(event.clientX);
    });
    const stopDrag = event => {
      if (activePointer !== null && event.pointerId !== undefined && event.pointerId !== activePointer) return;
      activePointer = null;
      document.body.classList.remove('slider-dragging');
    };
    range.addEventListener('pointerup', stopDrag);
    range.addEventListener('pointercancel', stopDrag);
    range.addEventListener('lostpointercapture', stopDrag);

    block.append(range, handle);
  });
})();
