(() => {
  const header = document.querySelector('.site-header');
  const progress = document.querySelector('.scroll-progress span');
  const menuButton = document.querySelector('.menu-toggle');
  const nav = document.querySelector('.main-nav');
  const reduced = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
  const finePointer = window.matchMedia('(pointer:fine)').matches;
  const desktopMotion = !reduced && finePointer && window.matchMedia('(min-width:901px)').matches;
  const root = document.documentElement;
  document.body.classList.add('motion-ready');
  document.body.classList.toggle('rich-motion', desktopMotion);

  const motionSections = Array.from(document.querySelectorAll('main > section'));
  motionSections.forEach(section => section.classList.add('motion-section'));

  let scrollTicking = false;
  const renderScroll = () => {
    const max = Math.max(1, document.documentElement.scrollHeight - window.innerHeight);
    const p = Math.min(1, Math.max(0, window.scrollY / max));
    if (header) header.classList.toggle('scrolled', window.scrollY > 12);
    if (progress) progress.style.width = `${p * 100}%`;
    root.style.setProperty('--scroll-p', p.toFixed(4));

    if (desktopMotion) {
      const vh = window.innerHeight || 1;
      motionSections.forEach(section => {
        const r = section.getBoundingClientRect();
        const local = Math.min(1, Math.max(0, (vh - r.top) / (vh + r.height)));
        section.style.setProperty('--local-p', local.toFixed(3));
      });
      const heroFrame = document.querySelector('.hero-frame');
      if (heroFrame) heroFrame.style.setProperty('--hero-scale', String(1.02 + p * .025));
    }
    scrollTicking = false;
  };
  const onScroll = () => {
    if (scrollTicking) return;
    scrollTicking = true;
    requestAnimationFrame(renderScroll);
  };
  renderScroll();
  window.addEventListener('scroll', onScroll, { passive: true });
  window.addEventListener('resize', onScroll, { passive: true });

  if (menuButton && nav) {
    menuButton.addEventListener('click', () => {
      const open = menuButton.getAttribute('aria-expanded') === 'true';
      menuButton.setAttribute('aria-expanded', String(!open));
      nav.classList.toggle('is-open', !open);
      document.body.classList.toggle('menu-open', !open);
    });
    nav.querySelectorAll('a').forEach(a => a.addEventListener('click', () => {
      menuButton.setAttribute('aria-expanded', 'false');
      nav.classList.remove('is-open');
      document.body.classList.remove('menu-open');
    }));
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
    }, { threshold: .08, rootMargin: '0px 0px -35px 0px' });
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
      if (value && title && text) {
        if (!reduced) {
          title.animate([{opacity:.25, transform:'translateY(10px)'},{opacity:1, transform:'none'}], {duration:380, easing:'cubic-bezier(.2,.8,.2,1)'});
          text.animate([{opacity:.2, transform:'translateY(6px)'},{opacity:1, transform:'none'}], {duration:430, easing:'ease-out'});
        }
        title.textContent = value[0];
        text.textContent = value[1];
      }
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

  /* Accessible before/after slider with explicit touch dragging and pointer capture. */
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
      if (typeof range.setPointerCapture === 'function') {
        try { range.setPointerCapture(event.pointerId); } catch (_) {}
      }
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

  if (desktopMotion) {
    /* Soft cursor aura. It never replaces the system cursor and has no interaction role. */
    const aura = document.createElement('div');
    aura.className = 'cursor-aura';
    document.body.appendChild(aura);
    let pointerX = -100;
    let pointerY = -100;
    let auraX = -100;
    let auraY = -100;
    const drawAura = () => {
      auraX += (pointerX - auraX) * .18;
      auraY += (pointerY - auraY) * .18;
      aura.style.transform = `translate3d(${auraX}px,${auraY}px,0) translate(-50%,-50%)`;
      requestAnimationFrame(drawAura);
    };
    drawAura();
    window.addEventListener('pointermove', event => {
      pointerX = event.clientX;
      pointerY = event.clientY;
      aura.classList.add('is-active');
    }, { passive: true });
    document.addEventListener('pointerover', event => {
      aura.classList.toggle('is-hover', Boolean(event.target.closest('a,button,input,.card,.case-card,.video-card')));
    }, { passive: true });
    document.addEventListener('mouseleave', () => aura.classList.remove('is-active'));

    const hero = document.querySelector('.hero');
    const heroFrame = document.querySelector('.hero-frame');
    if (hero && heroFrame) {
      hero.addEventListener('pointermove', event => {
        const r = hero.getBoundingClientRect();
        const x = (event.clientX - r.left) / r.width - .5;
        const y = (event.clientY - r.top) / r.height - .5;
        heroFrame.style.setProperty('--hero-x', `${x * 14}px`);
        heroFrame.style.setProperty('--hero-y', `${y * 10}px`);
      }, { passive: true });
      hero.addEventListener('pointerleave', () => {
        heroFrame.style.setProperty('--hero-x', '0px');
        heroFrame.style.setProperty('--hero-y', '0px');
      });
    }

    const concern = document.querySelector('.concern-section');
    if (concern) concern.addEventListener('pointermove', event => {
      const r = concern.getBoundingClientRect();
      concern.style.setProperty('--orb-x', `${((event.clientX-r.left)/r.width*100).toFixed(1)}%`);
      concern.style.setProperty('--orb-y', `${((event.clientY-r.top)/r.height*100).toFixed(1)}%`);
    }, { passive:true });

    document.querySelectorAll('.card,.case-card,.video-card').forEach(card => {
      card.addEventListener('pointermove', event => {
        const r = card.getBoundingClientRect();
        const x = (event.clientX-r.left)/r.width-.5;
        const y = (event.clientY-r.top)/r.height-.5;
        card.style.transform = `perspective(900px) rotateX(${-y*2.2}deg) rotateY(${x*2.4}deg) translateY(-4px)`;
      }, { passive:true });
      card.addEventListener('pointerleave', () => { card.style.transform = ''; });
    });

    document.querySelectorAll('.button').forEach(button => {
      button.classList.add('magnetic');
      button.addEventListener('pointermove', event => {
        const r = button.getBoundingClientRect();
        const x = event.clientX - (r.left + r.width/2);
        const y = event.clientY - (r.top + r.height/2);
        button.style.transform = `translate3d(${x*.08}px,${y*.10}px,0)`;
      }, { passive:true });
      button.addEventListener('pointerleave', () => { button.style.transform = ''; });
    });
  }
})();
