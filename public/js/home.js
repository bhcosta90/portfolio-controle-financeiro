$((function(){var t=[];$(".requisicao").map((function(a,n){const o=$(n);if(void 0!==o.data("route")&&""!=o.data("route")){const a=new Promise((function(t,a){return $.get(o.data("route")).then((a=>t(a,o))).catch((t=>a(t)))}));t.push(a)}})),Promise.all(t).then((function(t){t.map((function(t){const a=$(`.${t.tipo}`);t.data.map((function(t){a.find(`.${t.key}`).html(t.value),a.find(".text-calculado").each((function(){const a=$(this).data("class");void 0!==a&&a==t.key&&(t.value<0?$(this).addClass("text-danger"):0==t.value?$(this).addClass("text-warning"):$(this).addClass("text-success"))}))}))}))})).catch((function(t){console.log(t)}))}));
