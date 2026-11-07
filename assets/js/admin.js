(function(){
  var vt=document.getElementById('vt'); if(!vt) return;
  var tpl=document.getElementById('vrow'), n=Date.now()%100000;
  function add(color,size,stock){
    var h=tpl.innerHTML.replace(/__/g,'n'+(n++)), t=document.createElement('tbody'); t.innerHTML=h; var tr=t.firstElementChild;
    if(color) tr.querySelector('select').value=color; if(size!=null) tr.querySelector('input[name$="[size]"]').value=size; if(stock!=null) tr.querySelector('input[type=number]').value=stock;
    vt.appendChild(tr);
  }
  document.getElementById('addv').onclick=function(){add()};
  vt.addEventListener('click',function(e){if(e.target.hasAttribute('data-rm')) e.target.closest('tr').remove();});
  document.getElementById('gbtn').onclick=function(){
    var cols=[].map.call(document.querySelectorAll('#gcol input:checked'),function(i){return i.value}), sz=document.getElementById('gsz').value.split(',').map(function(s){return s.trim()}).filter(Boolean), st=document.getElementById('gst').value;
    if(!cols.length) cols=['']; if(!sz.length) sz=[''];
    var have={}; [].forEach.call(vt.querySelectorAll('tr'),function(tr){var s=tr.querySelector('select'),i=tr.querySelector('input[name$="[size]"]'); if(s&&i) have[s.value+'|'+i.value]=1;});
    cols.forEach(function(c){sz.forEach(function(s){ if(!have[c+'|'+s]) add(c,s,st); });});
  };
})();
