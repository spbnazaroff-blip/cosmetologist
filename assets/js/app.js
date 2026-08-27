(() => {
  const header = document.querySelector('.site-header');
  const progress = document.querySelector('.scroll-progress span');
  const menuButton = document.querySelector('.menu-toggle');
  const nav = document.querySelector('.main-nav');
  const reduced = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

  const onScroll = () => {
    if (header) header.classList.toggle('scrolled', window.scrollY > 12);
    if (progress) {
      const max = document.documentElement.scrollHeight - window.innerHeight;
      progress.style.width = `${max > 0 ? Math.min(100, window.scrollY / max * 100) : 0}%`;
    }
  };
  onScroll();
  window.addEventListener('scroll', onScroll, { passive: true });

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
        title.animate([{opacity:.35, transform:'translateY(5px)'},{opacity:1, transform:'none'}], {duration:260});
        text.animate([{opacity:.35},{opacity:1}], {duration:260});
        title.textContent = value[0]; text.textContent = value[1];
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

  if (!reduced) {
    const parallax = document.querySelectorAll('[data-parallax]');
    window.addEventListener('pointermove', event => {
      const x = (event.clientX / window.innerWidth - .5);
      const y = (event.clientY / window.innerHeight - .5);
      parallax.forEach(el => {
        const strength = Number(el.dataset.parallax || 12);
        el.style.transform = `translate3d(${x * strength}px, ${y * strength}px, 0)`;
      });
    }, { passive: true });
  }
})();
