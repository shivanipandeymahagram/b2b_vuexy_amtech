(function(){function an(){var a=!1;a&&ap("keydown",ab),U.keyboardSupport&&!a&&V("keydown",ab)}function X(){if(document.body){var e=document.body,g=document.documentElement,h=window.innerHeight,f=e.scrollHeight;if(q=document.compatMode.indexOf("CSS")>=0?g:e,R=e,an(),P=!0,top!=self){O=!0}else{if(f>h&&(e.offsetHeight<=h||g.offsetHeight<=h)){var c=!1,d=function(){c||g.scrollHeight==document.height||(c=!0,setTimeout(function(){g.style.height=document.height+"px",c=!1},500))};if(g.style.height="auto",setTimeout(d,10),q.offsetHeight<=h){var b=document.createElement("div");b.style.clear="both",e.appendChild(b)}}}U.fixedBackground||aq||(e.style.backgroundAttachment="scroll",g.style.backgroundAttachment="scroll")}}function ae(h,p,c,d){if(d||(d=1000),ao(p,c),1!=U.accelerationMax){var b=+new Date,k=b-aa;if(k<U.accelerationDelta){var g=(1+30/k)/2;g>1&&(g=Math.min(g,U.accelerationMax),p*=g,c*=g)}aa=+new Date}if(F.push({x:p,y:c,lastX:0>p?0.99:-0.99,lastY:0>c?0.99:-0.99,start:+new Date}),!j){var f=h===document.body,m=function(){for(var e=+new Date,x=0,n=0,v=0;v<F.length;v++){var z=F[v],u=e-z.start,t=u>=U.animationTime,o=t?1:u/U.animationTime;U.pulseAlgorithm&&(o=ad(o));var l=z.x*o-z.lastX>>0,y=z.y*o-z.lastY>>0;x+=l,n+=y,z.lastX+=l,z.lastY+=y,t&&(F.splice(v,1),v--)}f?window.scrollBy(x,n):(x&&(h.scrollLeft+=x),n&&(h.scrollTop+=n)),p||c||(F=[]),F.length?W(m,h,d/U.frameRate+1):j=!1};W(m,h,0),j=!0}}function af(f){P||X();var g=f.target,d=ah(g);if(!d||f.defaultPrevented||Z(R,"embed")||Z(g,"embed")&&/\.pdf/i.test(g.src)){return !0}var b=f.wheelDeltaX||0,c=f.wheelDeltaY||0;return b||c||(c=f.wheelDelta||0),!U.touchpadSupport&&am(c)?!0:(Math.abs(b)>1.2&&(b*=U.stepSize/120),Math.abs(c)>1.2&&(c*=U.stepSize/120),ae(d,-b,-c),void f.preventDefault())}function ab(h){var p=h.target,f=h.ctrlKey||h.altKey||h.metaKey||h.shiftKey&&h.keyCode!==Q.spacebar;if(/input|textarea|select|embed/i.test(p.nodeName)||p.isContentEditable||h.defaultPrevented||f){return !0}if(Z(p,"button")&&h.keyCode===Q.spacebar){return !0}var b,m=0,g=0,o=ah(R),l=o.clientHeight;switch(o==document.body&&(l=window.innerHeight),h.keyCode){case Q.up:g=-U.arrowScroll;break;case Q.down:g=U.arrowScroll;break;case Q.spacebar:b=h.shiftKey?1:-1,g=-b*l*0.9;break;case Q.pageup:g=0.9*-l;break;case Q.pagedown:g=0.9*l;break;case Q.home:g=-o.scrollTop;break;case Q.end:var k=o.scrollHeight-o.scrollTop-l;g=k>0?k+10:0;break;case Q.left:m=-U.arrowScroll;break;case Q.right:m=U.arrowScroll;break;default:return !0}ae(o,m,g),h.preventDefault()}function ar(a){R=a.target}function aj(b,a){for(var c=b.length;c--;){J[B(b[c])]=a}return a}function ah(b){var a=[],c=q.scrollHeight;do{var d=J[B(b)];if(d){return aj(a,d)}if(a.push(b),c===b.scrollHeight){if(!O||q.clientHeight+10<c){return aj(a,document.body)}}else{if(b.clientHeight+10<b.scrollHeight&&(overflow=getComputedStyle(b,"").getPropertyValue("overflow-y"),"scroll"===overflow||"auto"===overflow)){return aj(a,b)}}}while(b=b.parentNode)}function V(b,a,c){window.addEventListener(b,a,c||!1)}function ap(b,a,c){window.removeEventListener(b,a,c||!1)}function Z(b,a){return(b.nodeName||"").toLowerCase()===a.toLowerCase()}function ao(b,a){b=b>0?1:-1,a=a>0?1:-1,(ai.x!==b||ai.y!==a)&&(ai.x=b,ai.y=a,F=[],aa=0)}function am(b){if(b){b=Math.abs(b),Y.push(b),Y.shift(),clearTimeout(ac);var a=Y[0]==Y[1]&&Y[1]==Y[2],c=ak(Y[0],120)&&ak(Y[1],120)&&ak(Y[2],120);return !(a||c)}}function ak(b,a){return Math.floor(b/a)==b/a}function ag(b){var a,c,d;return b*=U.pulseScale,1>b?a=b-(1-Math.exp(-b)):(c=Math.exp(-1),b-=1,d=1-Math.exp(-b),a=c+d*(1-c)),a*U.pulseNormalize}function ad(a){return a>=1?1:0>=a?0:(1==U.pulseNormalize&&(U.pulseNormalize/=ag(1)),ag(a))}var R,al={frameRate:150,animationTime:800,stepSize:120,pulseAlgorithm:!0,pulseScale:8,pulseNormalize:1,accelerationDelta:20,accelerationMax:1,keyboardSupport:!0,arrowScroll:50,touchpadSupport:!0,fixedBackground:!0,excluded:""},U=al,aq=!1,O=!1,ai={x:0,y:0},P=!1,q=document.documentElement,Y=[120,120,120],Q={left:37,up:38,right:39,down:40,spacebar:32,pageup:33,pagedown:34,end:35,home:36},U=al,F=[],j=!1,aa=+new Date,J={};setInterval(function(){J={}},10000);var ac,B=function(){var a=0;return function(b){return b.uniqueID||(b.uniqueID=a++)}}(),W=function(){return window.requestAnimationFrame||window.webkitRequestAnimationFrame||function(b,a,c){window.setTimeout(b,c||1000/60)}}(),I=/chrome/i.test(window.navigator.userAgent),G="onmousewheel" in document;G&&I&&(V("mousedown",ar,{passive:false}),V("mousewheel",af,{passive:false}),V("load",X,{passive:false}))})();