/**
 * Bastovan Tema — hero-leaves.js
 * Three.js lebdeći listići na hero sekciji
 */

(function () {
  "use strict";

  function init() {
    const canvas = document.getElementById("hero-canvas");
    if (!canvas) return;
    if (typeof THREE === "undefined") return;

    // Postavi dimenzije eksplicitno pre WebGL init-a
    const hero = canvas.closest(".hero") || document.body;
    const W0   = hero.offsetWidth  || window.innerWidth;
    const H0   = hero.offsetHeight || window.innerHeight;
    canvas.width  = W0;
    canvas.height = H0;

    // ─── SCENE SETUP ───────────────────────────────────────
    const renderer = new THREE.WebGLRenderer({
      canvas,
      alpha:           true,
      antialias:       false,
      powerPreference: "low-power",
    });
    renderer.setPixelRatio(Math.min(window.devicePixelRatio, 1.5));
    renderer.setSize(W0, H0);

    const scene  = new THREE.Scene();
    const camera = new THREE.PerspectiveCamera(60, W0 / H0, 0.1, 100);
    camera.position.set(0, 0, 10);

    // ─── PROCEDURALNE TEKSTURE ─────────────────────────────
    function makeLeafTexture(color, shape) {
      const size = 128;
      const c    = document.createElement("canvas");
      c.width    = size;
      c.height   = size;
      const ctx  = c.getContext("2d");
      ctx.clearRect(0, 0, size, size);

      if (shape === 0) {
        // Ovalan list
        ctx.save();
        ctx.translate(size / 2, size / 2);
        ctx.rotate(Math.PI / 5);
        const g = ctx.createRadialGradient(0, -6, 2, 0, 0, size * 0.38);
        g.addColorStop(0, color.light);
        g.addColorStop(1, color.dark);
        ctx.fillStyle = g;
        ctx.beginPath();
        ctx.ellipse(0, 0, size * 0.22, size * 0.38, 0, 0, Math.PI * 2);
        ctx.fill();
        ctx.strokeStyle = "rgba(0,0,0,0.15)";
        ctx.lineWidth   = 1.5;
        ctx.beginPath();
        ctx.moveTo(0, -size * 0.35);
        ctx.lineTo(0, size * 0.35);
        ctx.stroke();
        ctx.restore();

      } else if (shape === 1) {
        // Srcoliki list
        ctx.save();
        ctx.translate(size / 2, size / 2);
        const g = ctx.createRadialGradient(-4, -8, 2, 0, 0, size * 0.4);
        g.addColorStop(0, color.light);
        g.addColorStop(1, color.dark);
        ctx.fillStyle = g;
        ctx.beginPath();
        ctx.moveTo(0, size * 0.28);
        ctx.bezierCurveTo( size*0.38,  size*0.05,  size*0.44, -size*0.22, 0, -size*0.16);
        ctx.bezierCurveTo(-size*0.44, -size*0.22, -size*0.38,  size*0.05, 0,  size*0.28);
        ctx.fill();
        ctx.strokeStyle = "rgba(0,0,0,0.12)";
        ctx.lineWidth   = 1.2;
        ctx.beginPath();
        ctx.moveTo(0, size * 0.28);
        ctx.lineTo(0, -size * 0.1);
        ctx.stroke();
        ctx.restore();

      } else {
        // Iglica
        ctx.save();
        ctx.translate(size / 2, size / 2);
        ctx.rotate(Math.PI / 8);
        ctx.fillStyle = color.dark;
        ctx.beginPath();
        ctx.ellipse(0, 0, size * 0.07, size * 0.44, 0, 0, Math.PI * 2);
        ctx.fill();
        ctx.restore();
      }

      return new THREE.CanvasTexture(c);
    }

    const paleta = [
      { light: "#7ec96b", dark: "#2d5a27" },
      { light: "#a8d878", dark: "#3d7a32" },
      { light: "#c8e888", dark: "#4a8c42" },
      { light: "#d4a843", dark: "#8a5a10" },
      { light: "#c86830", dark: "#7a3010" },
    ];

    // ─── KREIRANJE LISTIĆA ─────────────────────────────────
    const LEAF_COUNT = 140;
    const leaves     = [];
    const BW = 28, BH = 20, BD = 8;

    for (let i = 0; i < LEAF_COUNT; i++) {
      const color   = paleta[Math.floor(Math.random() * paleta.length)];
      const shape   = Math.floor(Math.random() * 3);
      const texture = makeLeafTexture(color, shape);
      const size    = 0.08 + Math.random() * 0.22;

      const geo = new THREE.PlaneGeometry(size, size * 1.4);
      const mat = new THREE.MeshBasicMaterial({
        map:         texture,
        transparent: true,
        opacity:     0.55 + Math.random() * 0.35,
        depthWrite:  false,
        side:        THREE.DoubleSide,
      });
      const mesh = new THREE.Mesh(geo, mat);

      mesh.position.set(
        (Math.random() - 0.5) * BW,
        (Math.random() - 0.5) * BH,
        (Math.random() - 0.5) * BD
      );
      mesh.rotation.set(
        Math.random() * Math.PI * 2,
        Math.random() * Math.PI * 2,
        Math.random() * Math.PI * 2
      );

      leaves.push({
        mesh,
        speedY:    -(0.004 + Math.random() * 0.008),
        speedX:    (Math.random() - 0.5) * 0.003,
        rotSpeedX: (Math.random() - 0.5) * 0.012,
        rotSpeedY: (Math.random() - 0.5) * 0.018,
        rotSpeedZ: (Math.random() - 0.5) * 0.008,
        swayAmp:   0.002 + Math.random() * 0.004,
        swayFreq:  0.3   + Math.random() * 0.8,
        swayOff:   Math.random() * Math.PI * 2,
        depth:     mesh.position.z,
      });
      scene.add(mesh);
    }

    // ─── MOUSE PARALLAX ────────────────────────────────────
    let mouseX = 0, mouseY = 0, targetX = 0, targetY = 0;

    hero.addEventListener("mousemove", (e) => {
      const rect = hero.getBoundingClientRect();
      mouseX = ((e.clientX - rect.left) / rect.width  - 0.5) * 2;
      mouseY = ((e.clientY - rect.top)  / rect.height - 0.5) * 2;
    }, { passive: true });

    // ─── RESIZE ────────────────────────────────────────────
    window.addEventListener("resize", () => {
      const w = hero.offsetWidth;
      const h = hero.offsetHeight;
      renderer.setSize(w, h);
      camera.aspect = w / h;
      camera.updateProjectionMatrix();
    }, { passive: true });

    // ─── LOOP ──────────────────────────────────────────────
    let lastTime = performance.now();

    function animate(now) {
      requestAnimationFrame(animate);
      const delta = Math.min((now - lastTime) / 16.67, 3);
      lastTime = now;

      targetX += (mouseX * 0.4 - targetX) * 0.04;
      targetY += (mouseY * 0.3 - targetY) * 0.04;
      camera.position.x = targetX;
      camera.position.y = targetY;

      const t = now * 0.001;

      leaves.forEach((leaf) => {
        const m = leaf.mesh;
        m.position.y += leaf.speedY * delta;
        m.position.x += leaf.speedX * delta + Math.sin(t * leaf.swayFreq + leaf.swayOff) * leaf.swayAmp * delta;
        m.rotation.x += leaf.rotSpeedX * delta;
        m.rotation.y += leaf.rotSpeedY * delta;
        m.rotation.z += leaf.rotSpeedZ * delta;

        if (m.position.y < -BH / 2 - 1) {
          m.position.y =  BH / 2 + 0.5;
          m.position.x = (Math.random() - 0.5) * BW;
          m.position.z = (Math.random() - 0.5) * BD;
        }
        if (m.position.x >  BW / 2 + 1) m.position.x = -BW / 2;
        if (m.position.x < -BW / 2 - 1) m.position.x =  BW / 2;
      });

      renderer.render(scene, camera);
    }

    requestAnimationFrame(animate);
  }

  // Čekaj da je DOM + layout spreman
  if (document.readyState === "complete") {
    init();
  } else {
    window.addEventListener("load", init);
  }

})();

