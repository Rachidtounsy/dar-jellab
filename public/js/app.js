(function () {
  var $ = function (s, r) { return (r || document).querySelector(s); };
  document.addEventListener('click', function (e) {
    var o = e.target.closest('[data-open]'), c = e.target.closest('[data-close]');
    if (o) { var el = document.getElementById(o.dataset.open); if (el) { el.hidden = false; el.classList.add('open'); } }
    if (c) { var el2 = document.getElementById(c.dataset.close); if (el2) { el2.classList.remove('open'); if (el2.classList.contains('drawer')) el2.hidden = true; } }
  });
  // Page produit : variantes + message WhatsApp
  var pdp = $('#pdp'); if (!pdp) return;
  var colors = JSON.parse($('#cdata').textContent || '[]'), d = pdp.dataset;
  var cname = $('#cname'), sizes = $('#sizes'), order = $('#order'), mtm = $('#mtm'), cur = 0, size = '', fc = $('#f-color'), fs = $('#f-size'), fm = $('#f-mtm'), ab = $('#addbtn');
  function draw() {
    var c = colors[cur]; if (!c) { link(); return; }
    if (cname) cname.textContent = c.name;
    sizes.innerHTML = ''; size = ''; if (fc) fc.value = c.id; if (fs) fs.value = ''; if (ab && ab.dataset.need === '1') ab.disabled = true;
    c.sizes.forEach(function (s) {
      var b = document.createElement('button'); b.type = 'button'; b.textContent = s.size; b.disabled = s.stock <= 0;
      b.onclick = function () { size = s.size; if (fs) fs.value = s.size; if (ab) ab.disabled = false; [].forEach.call(sizes.children, function (x) { x.classList.remove('on'); }); b.classList.add('on'); link(); };
      sizes.appendChild(b);
    });
    link();
  }
  function link() {
    var c = colors[cur], lines = [d.lHello, '• ' + d.name];
    if (c) lines.push('• ' + d.lColor + ': ' + c.name);
    if (size) lines.push('• ' + d.lSize + ': ' + size);
    lines.push('• ' + d.lPrice + ': ' + d.price);
    if (mtm && mtm.checked) lines.push('• ' + d.lMtm);
    lines.push(d.url);
    order.href = 'https://wa.me/' + d.wa + '?text=' + encodeURIComponent(lines.join('\n'));
  }
  [].forEach.call(document.querySelectorAll('input[name=col]'), function (r) { r.onchange = function () { cur = +r.value; draw(); }; });
  if (mtm) mtm.onchange = function () { if (fm) fm.value = mtm.checked ? '1' : '0'; link(); };
  [].forEach.call(document.querySelectorAll('.gal-th button'), function (b) {
    b.onclick = function () { $('#gmain').src = b.dataset.img; [].forEach.call(document.querySelectorAll('.gal-th button'), function (x) { x.classList.remove('on'); }); b.classList.add('on'); };
  });
  draw();
})();
