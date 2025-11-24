<?php
$blogRepo = new Blog($portal);
$articles = $blogRepo->findForHomepage();
?>

<section class="diary">
  <article class="container" role="article">
    <div class="diary__top">
      <div class="diary__top-item">
        <h2 class="title">Deník makléře</h2>
        <p>Chcete dostávat naše novinky pravidelně do své e-mailové schránky a mít tak přehled o realitním trhu
          v České republice?</p>
      </div>

      <div class="diary__top-item">
        <form id="newsletter"
              class="subscribe async-form"
              method="post"
              action="/mlift/services/save_form.php"
              novalidate>
          <input type="hidden" name="__newsletter_api" value="1">
          <input name="form[email]" class="subscribe__input" type="email" placeholder="Váš email" required
                 inputmode="email" autocomplete="email" autocapitalize="off" spellcheck="false">
          <input type="hidden" name="form[form_type]" value="10">
          <input type="hidden" name="form[secure_code]" value="730405/2459">
          <button class="subscribe__submit" type="submit">Přihlásit odběr</button>
        </form>
      </div>
    </div>

    <div class="diary__grid">
      <?php foreach ($articles as $article) {
        echo $portal->render('diary-item.php', ['article' => $article]);
      } ?>
    </div>
  </article>
</section>

<script>
  (function () {
    function ready(cb){ document.readyState==='loading'?document.addEventListener('DOMContentLoaded',cb):cb(); }
    function ensureModal(){
      var m = document.getElementById('newsletter-modal');
      if (m) return m;
      m = document.createElement('div'); m.id = 'newsletter-modal';
      m.innerHTML =
        '<div class="nm-backdrop" role="presentation"></div>'+
        '<div class="nm-dialog" role="dialog" aria-modal="true" aria-labelledby="nm-title">'+
        '<button class="nm-close" aria-label="Zavřít">&times;</button>'+
        '<h3 id="nm-title" class="nm-title"></h3>'+
        '<p class="nm-msg"></p>'+
        '<div class="nm-actions"><button class="nm-ok">OK</button></div>'+
        '</div>';
      document.body.appendChild(m);
      var css = document.createElement('style'); css.textContent = `
#newsletter-modal{position:fixed;inset:0;display:none;z-index:9999}
#newsletter-modal.open{display:block}
#newsletter-modal .nm-backdrop{position:absolute;inset:0;background:rgba(0,0,0,.45)}
#newsletter-modal .nm-dialog{position:relative;max-width:520px;margin:10vh auto;background:#fff;border-radius:14px;padding:20px 22px 16px;box-shadow:0 10px 30px rgba(0,0,0,.2)}
#newsletter-modal .nm-title{margin:0 0 8px 0;font-size:20px}
#newsletter-modal .nm-msg{margin:0 0 16px 0;line-height:1.4}
#newsletter-modal .nm-actions{display:flex;justify-content:flex-end;gap:8px}
#newsletter-modal .nm-ok{padding:8px 14px;border:0;border-radius:10px;cursor:pointer}
#newsletter-modal .nm-close{position:absolute;right:8px;top:6px;border:0;background:transparent;font-size:28px;line-height:1;cursor:pointer}
#newsletter-modal.success .nm-title{color:#2e7d32}
#newsletter-modal.error .nm-title{color:#c62828}
.subscribe__input.is-invalid{outline:2px solid #c62828; border-color:#c62828}
    `; document.head.appendChild(css);
      m.querySelector('.nm-close').onclick =
        m.querySelector('.nm-ok').onclick =
          m.querySelector('.nm-backdrop').onclick = function(){ m.classList.remove('open'); };
      document.addEventListener('keydown', function(ev){ if(ev.key==='Escape') m.classList.remove('open'); });
      return m;
    }
    function openModal(title, msg, kind){
      var m = ensureModal();
      m.classList.remove('success','error');
      if (kind==='success') m.classList.add('success');
      if (kind==='error')   m.classList.add('error');
      m.querySelector('.nm-title').textContent = title || (kind==='success'?'Hotovo':'Upozornění');
      m.querySelector('.nm-msg').textContent   = msg || '';
      m.classList.add('open');
      setTimeout(function(){ m.querySelector('.nm-ok').focus(); }, 0);
    }
    function isValidEmail(v){
      if (!v) return false;
      v = String(v).trim();
      var re = /^[A-Za-z0-9._%+-]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,}$/;
      if (!re.test(v)) return false;
      var d = v.split('@')[1]||'';
      return !(d.startsWith('.')||d.endsWith('.')||d.includes('..'));
    }

    ready(function(){
      var form = document.getElementById('newsletter'); if (!form) return;
      var emailEl = form.querySelector('[name="form[email]"]');
      if (emailEl) emailEl.addEventListener('input', function(){ emailEl.classList.remove('is-invalid'); emailEl.removeAttribute('aria-invalid'); });

      document.addEventListener('submit', function(e){
        if (e.target !== form) return;
        var email = (emailEl && emailEl.value || '').trim();
        if (!isValidEmail(email)) {
          e.preventDefault(); e.stopPropagation(); if (e.stopImmediatePropagation) e.stopImmediatePropagation();
          if (emailEl){ emailEl.classList.add('is-invalid'); emailEl.setAttribute('aria-invalid','true'); emailEl.focus(); }
          openModal('Upozornění','Zadejte platný e-mail ve tvaru např. jana@domena.cz.','error'); return;
        }
        e.preventDefault(); e.stopPropagation(); if (e.stopImmediatePropagation) e.stopImmediatePropagation();
        submitAjax(form);
      }, true);

      function submitAjax(form){
        var btn = form.querySelector('button[type=submit]');
        if (btn){ btn.disabled = true; btn.dataset.__txt = btn.textContent; btn.textContent = 'Odesílám…'; }
        var fd = new FormData(form);
        fd.set('form[url]', location.href);
        fd.set('form[referer]', document.referrer || '');
        fd.set('form[user_agent]', navigator.userAgent || '');
        var usp = new URLSearchParams(); fd.forEach(function(v,k){ usp.append(k, v); });
        fetch(form.action, {
          method: 'POST',
          headers: { 'Accept':'application/json','X-Requested-With':'XMLHttpRequest','Content-Type':'application/x-www-form-urlencoded; charset=UTF-8' },
          body: usp.toString()
        })
          .then(function(res){ return res.text(); })
          .then(function(txt){
            var t = txt.trim(); if (!t || t[0]==='<'){ openModal('Chybná odpověď','Server vrátil HTML místo JSON.','error'); return; }
            var data; try{ data = JSON.parse(t);}catch(_){ openModal('Chybná odpověď','Neplatný JSON ze serveru.','error'); return; }
            var f = data.form || {}; var status = Number(f.status)||2; var msg = f.message || (status===1?'Hotovo.':'Došlo k chybě.');
            openModal(status===1?'Děkujeme':'Upozornění', msg, (status===1?'success':'error'));
            if (status===1) try{ form.reset(); }catch(e){}
          })
          .catch(function(){ openModal('Chyba','Chyba při odeslání formuláře.','error'); })
          .finally(function(){ if (btn){ btn.disabled=false; btn.textContent=btn.dataset.__txt||'Přihlásit odběr'; }});
      }
    });
  })();
</script>
