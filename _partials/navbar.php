<header class="site-header">
    <nav class="site-nav" aria-label="Navigation principale">
        <a class="brand" href="home">
            <span class="brand-mark">Stok'it</span>
        </a>
        <button class="nav-toggle" aria-expanded="false" aria-controls="primary-nav">
            <span class="nav-toggle-bar" aria-hidden="true"></span>
            <span class="nav-toggle-bar" aria-hidden="true"></span>
            <span class="nav-toggle-bar" aria-hidden="true"></span>
        </button>
        <ul id="primary-nav" class="nav-links">
            <li><a href="home" class="active">Accueil</a></li>
            <li><a href="stock">Stock</a></li>
        </ul>
    </nav>
    <script>
    (function() {
      const toggle = document.querySelector('.nav-toggle');
      const nav = document.querySelector('#primary-nav');
      if (!toggle || !nav) return;
      toggle.addEventListener('click', function(){
        const isOpen = nav.classList.toggle('open');
        toggle.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
      });
      // Activer le lien courant si possible
      try {
        const path = (new URL(location.href)).pathname.replace(/\/?$/, '');
        document.querySelectorAll('#primary-nav a').forEach(a => {
          const href = a.getAttribute('href');
          if (!href) return;
          if (path.endsWith('/' + href) || (href === 'home' && (path === '' || path.endsWith('/')))) {
            a.classList.add('active');
          } else {
            a.classList.remove('active');
          }
        });
      } catch(e){}
    })();
    </script>
</header>