/**
 * Bastovan Tema — hero-mower.js
 * Three.js animacija kosilice — trava, panj, kamenčić, cvet, leptir
 */

(function () {
  "use strict";

  function easeOut(t) { return 1 - Math.pow(1 - t, 2); }
  function easeInOut(t) { return t < 0.5 ? 2*t*t : -1+(4-2*t)*t; }
  function rand(min, max) { return min + Math.random() * (max - min); }

  function init() {
    const canvas = document.getElementById("hero-canvas");
    if (!canvas || typeof THREE === "undefined") return;

    const hero = canvas.closest(".hero") || document.body;
    const W0   = hero.offsetWidth  || window.innerWidth;
    const H0   = hero.offsetHeight || window.innerHeight;
    canvas.width  = W0;
    canvas.height = H0;

    const renderer = new THREE.WebGLRenderer({ canvas, alpha: true, antialias: true, powerPreference: "low-power" });
    renderer.setPixelRatio(Math.min(window.devicePixelRatio, 1.5));
    renderer.setSize(W0, H0, false);

    const scene  = new THREE.Scene();
    const camera = new THREE.OrthographicCamera(-W0/2, W0/2, H0/2, -H0/2, 0.1, 100);
    camera.position.z = 10;

    // ─── KONSTANTE ─────────────────────────────────────────
    const GROUND_Y    = -H0 / 2 + 2;
    const BLADE_COUNT = Math.floor(W0 / 6);
    const BLADE_W     = 3.5;
    const BLADE_MAX_H = 48;
    const BLADE_MIN_H = 8;
    const MOWER_W     = 96;
    const MOWER_H     = 58;
    const MOWER_Y_BASE = GROUND_Y + MOWER_H / 2 - 4;

    // ─── TRAVA ─────────────────────────────────────────────
    const greens = [
      new THREE.Color("#1e4a18"), new THREE.Color("#2d5a27"),
      new THREE.Color("#3d7a32"), new THREE.Color("#4a8c42"),
      new THREE.Color("#5aaa50"),
    ];
    const blades = [];
    for (let i = 0; i < BLADE_COUNT; i++) {
      const x     = -W0/2 + i * (W0/BLADE_COUNT) + rand(-3, 3);
      const fullH = rand(BLADE_MIN_H, BLADE_MAX_H);
      const geo   = new THREE.PlaneGeometry(BLADE_W, fullH);
      geo.translate(0, fullH/2, 0);
      const mat  = new THREE.MeshBasicMaterial({ color: greens[Math.floor(Math.random()*greens.length)] });
      const mesh = new THREE.Mesh(geo, mat);
      mesh.position.set(x, GROUND_Y, 0);
      mesh.rotation.z = rand(-0.15, 0.15);
      scene.add(mesh);
      blades.push({ mesh, x, fullH, mowed: false, mowProgress: 0,
        swayOff: rand(0, Math.PI*2), swayAmp: rand(0.018, 0.043) });
    }

    // ─── CVETOVI ───────────────────────────────────────────
    function makeFlowerTexture(color) {
      const c = document.createElement("canvas"); c.width = 64; c.height = 64;
      const ctx = c.getContext("2d");
      // Latice
      for (let p = 0; p < 6; p++) {
        const a = (p/6)*Math.PI*2;
        ctx.fillStyle = color;
        ctx.beginPath();
        ctx.ellipse(32 + Math.cos(a)*10, 32 + Math.sin(a)*10, 7, 5, a, 0, Math.PI*2);
        ctx.fill();
      }
      // Sredina
      ctx.fillStyle = "#f4d020";
      ctx.beginPath(); ctx.arc(32, 32, 7, 0, Math.PI*2); ctx.fill();
      ctx.fillStyle = "#e0b010";
      ctx.beginPath(); ctx.arc(32, 32, 4, 0, Math.PI*2); ctx.fill();
      return new THREE.CanvasTexture(c);
    }
    const flowerColors = ["#ff6688", "#ff99aa", "#ffaacc", "#cc44ff", "#ffffff"];
    const flowers = [];
    const flowerCount = Math.floor(W0 / 180);
    for (let i = 0; i < flowerCount; i++) {
      const x   = rand(-W0/2 + 80, W0/2 - 80);
      const tex  = makeFlowerTexture(flowerColors[Math.floor(Math.random()*flowerColors.length)]);
      const geo  = new THREE.PlaneGeometry(18, 18);
      const mat  = new THREE.MeshBasicMaterial({ map: tex, transparent: true, depthWrite: false });
      const mesh = new THREE.Mesh(geo, mat);
      // Stabljika
      const stalkH = rand(20, 35);
      const sgeo   = new THREE.PlaneGeometry(2, stalkH);
      sgeo.translate(0, stalkH/2, 0);
      const smat   = new THREE.MeshBasicMaterial({ color: "#3d7a32" });
      const stalk  = new THREE.Mesh(sgeo, smat);
      stalk.position.set(x, GROUND_Y, 0.5);
      scene.add(stalk);
      mesh.position.set(x, GROUND_Y + stalkH + 9, 0.5);
      scene.add(mesh);
      flowers.push({ mesh, stalk, x, mowed: false, mowProgress: 0, stalkH });
    }

    // ─── PANJEVI ───────────────────────────────────────────
    function makeStumpTexture() {
      const c = document.createElement("canvas"); c.width = 80; c.height = 56;
      const ctx = c.getContext("2d");
      // Telo panja
      const g = ctx.createLinearGradient(0, 0, 80, 0);
      g.addColorStop(0, "#6b3a1f"); g.addColorStop(0.5, "#8b5a2b"); g.addColorStop(1, "#5a2d10");
      ctx.fillStyle = g;
      ctx.beginPath(); ctx.roundRect(4, 16, 72, 36, [4,4,2,2]); ctx.fill();
      // Godovi (gornja površina)
      const tg = ctx.createRadialGradient(40, 8, 2, 40, 8, 30);
      tg.addColorStop(0, "#c8a060"); tg.addColorStop(0.4, "#a07840"); tg.addColorStop(1, "#7a5420");
      ctx.fillStyle = tg;
      ctx.beginPath(); ctx.ellipse(40, 16, 36, 14, 0, 0, Math.PI*2); ctx.fill();
      // Godovi linije
      for (let r = 8; r <= 28; r += 8) {
        ctx.strokeStyle = "rgba(80,40,10,0.25)"; ctx.lineWidth = 1;
        ctx.beginPath(); ctx.ellipse(40, 16, r, r*0.38, 0, 0, Math.PI*2); ctx.stroke();
      }
      // Kora detalji
      ctx.strokeStyle = "#3d1a08"; ctx.lineWidth = 1.5;
      for (let l = 0; l < 4; l++) {
        ctx.beginPath(); ctx.moveTo(8+l*18, 20); ctx.lineTo(10+l*18, 50); ctx.stroke();
      }
      return new THREE.CanvasTexture(c);
    }
    const stumps = [];
    const stumpPositions = [];
    const stumpCount = Math.max(1, Math.floor(W0 / 500));
    for (let i = 0; i < stumpCount; i++) {
      const x   = rand(-W0/2 + 150, W0/2 - 150);
      stumpPositions.push(x);
      const tex  = makeStumpTexture();
      const geo  = new THREE.PlaneGeometry(72, 46);
      const mat  = new THREE.MeshBasicMaterial({ map: tex, transparent: true, depthWrite: false });
      const mesh = new THREE.Mesh(geo, mat);
      mesh.position.set(x, GROUND_Y + 20, 1.5);
      scene.add(mesh);
      stumps.push({ mesh, x, width: 36 });
    }

    // ─── KOSILICA TEKSTURA ─────────────────────────────────
    function makeMowerTexture(facingRight) {
      const cw = 192, ch = 116;
      const c = document.createElement("canvas"); c.width = cw; c.height = ch;
      const ctx = c.getContext("2d");
      ctx.save();
      if (!facingRight) { ctx.translate(cw, 0); ctx.scale(-1, 1); }
      // Senka
      ctx.fillStyle = "rgba(0,0,0,0.18)";
      ctx.beginPath(); ctx.ellipse(96, 108, 72, 8, 0, 0, Math.PI*2); ctx.fill();
      // Zadnji točak
      ctx.fillStyle = "#1a1a1a";
      ctx.beginPath(); ctx.ellipse(138, 88, 22, 22, 0, 0, Math.PI*2); ctx.fill();
      for (let a = 0; a < 8; a++) {
        const angle = (a/8)*Math.PI*2;
        ctx.fillStyle = "#333";
        ctx.beginPath(); ctx.arc(138+Math.cos(angle)*17, 88+Math.sin(angle)*17, 3, 0, Math.PI*2); ctx.fill();
      }
      ctx.fillStyle = "#555"; ctx.beginPath(); ctx.ellipse(138, 88, 10, 10, 0, 0, Math.PI*2); ctx.fill();
      ctx.fillStyle = "#888"; ctx.beginPath(); ctx.ellipse(138, 88, 4, 4, 0, 0, Math.PI*2); ctx.fill();
      // Prednji točak
      ctx.fillStyle = "#1a1a1a"; ctx.beginPath(); ctx.ellipse(42, 92, 14, 14, 0, 0, Math.PI*2); ctx.fill();
      ctx.fillStyle = "#555"; ctx.beginPath(); ctx.ellipse(42, 92, 6, 6, 0, 0, Math.PI*2); ctx.fill();
      // Šasija
      ctx.fillStyle = "#b8411a"; ctx.beginPath(); ctx.roundRect(18, 68, 140, 22, 4); ctx.fill();
      // Kućište
      const bg = ctx.createLinearGradient(22, 28, 22, 70);
      bg.addColorStop(0, "#f0a030"); bg.addColorStop(0.5, "#e08020"); bg.addColorStop(1, "#c06010");
      ctx.fillStyle = bg; ctx.beginPath(); ctx.roundRect(28, 30, 118, 44, [6,6,2,2]); ctx.fill();
      ctx.strokeStyle = "rgba(255,255,255,0.15)"; ctx.lineWidth = 1.5;
      ctx.beginPath(); ctx.moveTo(38, 36); ctx.lineTo(136, 36); ctx.stroke();
      for (let v = 0; v < 4; v++) {
        ctx.fillStyle = "rgba(0,0,0,0.25)"; ctx.beginPath(); ctx.roundRect(40+v*24, 42, 14, 6, 2); ctx.fill();
      }
      // Poklopac
      const hg = ctx.createLinearGradient(50, 10, 50, 32);
      hg.addColorStop(0, "#ffc040"); hg.addColorStop(1, "#e08020");
      ctx.fillStyle = hg; ctx.beginPath(); ctx.roundRect(50, 14, 80, 22, [8,8,2,2]); ctx.fill();
      ctx.fillStyle = "rgba(255,255,255,0.2)"; ctx.beginPath(); ctx.roundRect(56, 17, 50, 6, 3); ctx.fill();
      // Ručica
      ctx.strokeStyle = "#666"; ctx.lineWidth = 5; ctx.lineCap = "round";
      ctx.beginPath(); ctx.moveTo(148, 30); ctx.lineTo(178, 4); ctx.stroke();
      ctx.strokeStyle = "#888"; ctx.beginPath(); ctx.moveTo(148, 44); ctx.lineTo(180, 18); ctx.stroke();
      ctx.strokeStyle = "#444"; ctx.lineWidth = 8;
      ctx.beginPath(); ctx.moveTo(174, 6); ctx.lineTo(180, 16); ctx.stroke();
      ctx.restore();
      return new THREE.CanvasTexture(c);
    }
    const texRight = makeMowerTexture(true);
    const texLeft  = makeMowerTexture(false);
    const mowerGeo = new THREE.PlaneGeometry(MOWER_W, MOWER_H);
    const mowerMat = new THREE.MeshBasicMaterial({ map: texRight, transparent: true, depthWrite: false });
    const mower    = new THREE.Mesh(mowerGeo, mowerMat);
    mower.position.set(-W0/2 - MOWER_W, MOWER_Y_BASE, 2);
    scene.add(mower);

    // ─── KAMENČIĆI (particles) ──────────────────────────────
    const pebbles = [];
    function spawnPebble(x, y) {
      const geo  = new THREE.CircleGeometry(rand(2, 4), 6);
      const mat  = new THREE.MeshBasicMaterial({ color: new THREE.Color(rand(0.4,0.6), rand(0.35,0.5), rand(0.3,0.4)) });
      const mesh = new THREE.Mesh(geo, mat);
      mesh.position.set(x, y, 3);
      scene.add(mesh);
      pebbles.push({
        mesh,
        vx: rand(1.5, 3.5) * (Math.random() > 0.5 ? 1 : -1),
        vy: rand(3, 6),
        gravity: 0.18,
        done: false,
      });
    }

    // ─── DIM/PRAŠINA ───────────────────────────────────────
    const dust = [];
    function spawnDust(x, y) {
      const geo  = new THREE.CircleGeometry(1, 8);
      // Naizmenično zelenkasta zemlja i siva prašina
      const isGreen = Math.random() > 0.5;
      const color = isGreen
        ? new THREE.Color(rand(0.35, 0.5), rand(0.55, 0.7), rand(0.25, 0.4))
        : new THREE.Color(rand(0.55, 0.7), rand(0.52, 0.65), rand(0.4, 0.5));
      const mat  = new THREE.MeshBasicMaterial({
        color, transparent: true, opacity: rand(0.25, 0.45), depthWrite: false
      });
      const mesh = new THREE.Mesh(geo, mat);
      mesh.position.set(
        x + rand(-10, 10),
        y + rand(0, 8),
        1.8
      );
      scene.add(mesh);
      dust.push({
        mesh,
        vx:          rand(-0.4, 0.4),
        vy:          rand(0.3, 1.1),
        life:        0,
        maxLife:     rand(18, 32),
        startSize:   rand(0.8, 1.6),
        startOpacity: rand(0.25, 0.45),
        done:        false,
      });
    }

    // ─── LEPTIR ────────────────────────────────────────────
    function makeButterfly() {
      const c = document.createElement("canvas"); c.width = 64; c.height = 48;
      const ctx = c.getContext("2d");
      const colors = ["#ff8844","#ffaa00","#ff44aa","#aa44ff","#44aaff"];
      const col = colors[Math.floor(Math.random()*colors.length)];
      // Levo krilo
      ctx.fillStyle = col; ctx.globalAlpha = 0.85;
      ctx.beginPath(); ctx.ellipse(16, 22, 16, 10, -0.4, 0, Math.PI*2); ctx.fill();
      // Desno krilo
      ctx.beginPath(); ctx.ellipse(48, 22, 16, 10, 0.4, 0, Math.PI*2); ctx.fill();
      // Telo
      ctx.globalAlpha = 1; ctx.fillStyle = "#222";
      ctx.beginPath(); ctx.ellipse(32, 22, 3, 11, 0, 0, Math.PI*2); ctx.fill();
      // Antene
      ctx.strokeStyle = "#333"; ctx.lineWidth = 1.5;
      ctx.beginPath(); ctx.moveTo(30, 12); ctx.quadraticCurveTo(22, 2, 18, 0); ctx.stroke();
      ctx.beginPath(); ctx.moveTo(34, 12); ctx.quadraticCurveTo(42, 2, 46, 0); ctx.stroke();
      ctx.fillStyle = "#333";
      ctx.beginPath(); ctx.arc(18, 0, 2, 0, Math.PI*2); ctx.fill();
      ctx.beginPath(); ctx.arc(46, 0, 2, 0, Math.PI*2); ctx.fill();
      return new THREE.CanvasTexture(c);
    }

    const butterflies = [];
    const bfCount = Math.max(1, Math.floor(W0 / 400));
    for (let i = 0; i < bfCount; i++) {
      const tex  = makeButterfly();
      const geo  = new THREE.PlaneGeometry(28, 21);
      const mat  = new THREE.MeshBasicMaterial({ map: tex, transparent: true, depthWrite: false });
      const mesh = new THREE.Mesh(geo, mat);
      const startX = rand(-W0/2 + 60, W0/2 - 60);
      const startY = GROUND_Y + rand(30, 70);
      mesh.position.set(startX, startY, 3);
      scene.add(mesh);
      butterflies.push({
        mesh, tex,
        x: startX, y: startY,
        restX: startX, restY: startY,
        state: "idle",       // idle | fleeing | returning
        fleeVx: 0, fleeVy: 0,
        flapPhase: rand(0, Math.PI*2),
        flapSpeed: rand(3, 5),
        // Krila se animiraju kroz scaleX
        scared: false,
      });
    }

    // ─── STANJE KOSILICE ───────────────────────────────────
    let mowerDir    = 1;
    const SPEED     = W0 / 320;
    const MOW_RAD   = MOWER_W * 0.38;
    let mowerState  = "moving";   // moving | avoiding_stump
    let avoidTimer  = 0;
    let avoidPhase  = 0;          // 0=uspori, 1=gore, 2=pored, 3=dole, 4=nastavi
    let avoidStump  = null;
    let shakeTimer  = 0;
    const AVOID_DUR = [12, 18, 30, 18]; // trajanje svake faze u frame-ovima

    // ─── RESIZE ────────────────────────────────────────────
    let resizeTimer;
    window.addEventListener("resize", () => {
      clearTimeout(resizeTimer);
      resizeTimer = setTimeout(() => {
        const w = hero.offsetWidth, h = hero.offsetHeight;
        renderer.setSize(w, h, false);
        camera.left = -w/2; camera.right = w/2;
        camera.top = h/2; camera.bottom = -h/2;
        camera.updateProjectionMatrix();
      }, 150);
    }, { passive: true });

    // ─── ANIMACIJA ─────────────────────────────────────────
    let lastTime = performance.now();
    let frameCount = 0;

    function animate(now) {
      requestAnimationFrame(animate);
      const delta = Math.min((now - lastTime) / 16.67, 3);
      lastTime = now;
      const t = now * 0.001;
      frameCount++;

      const mx = mower.position.x;
      const my = mower.position.y;

      // ── KOSILICA KRETANJE ──
      if (mowerState === "moving") {
        // Provjeri panj ispred
        let stumped = null;
        for (const s of stumps) {
          const dist = Math.abs(s.x - mx);
          const ahead = mowerDir === 1 ? (s.x > mx && s.x < mx + MOWER_W*1.2)
                                       : (s.x < mx && s.x > mx - MOWER_W*1.2);
          if (ahead && dist < s.width + 20) { stumped = s; break; }
        }
        if (stumped) {
          mowerState = "avoiding_stump";
          avoidTimer = 0; avoidPhase = 0;
          avoidStump = stumped;
          shakeTimer = 20;
        } else {
          mower.position.x += SPEED * mowerDir * delta;
          // Bump
          mower.position.y = MOWER_Y_BASE + Math.sin(t * 18) * 0.6;
        }
        // Flip na krajevima
        if (mowerDir === 1 && mower.position.x > W0/2 + MOWER_W + 30) {
          mowerDir = -1; mowerMat.map = texLeft; mowerMat.needsUpdate = true;
        } else if (mowerDir === -1 && mower.position.x < -W0/2 - MOWER_W - 30) {
          mowerDir = 1; mowerMat.map = texRight; mowerMat.needsUpdate = true;
        }
      } else if (mowerState === "avoiding_stump") {
        avoidTimer += delta;
        const durations = AVOID_DUR;

        if (avoidPhase === 0) {
          // Tresenje — stoji u mestu
          if (shakeTimer > 0) {
            mower.position.x += Math.sin(t * 40) * 1.2;
            mower.position.y = MOWER_Y_BASE + Math.sin(t * 40) * 1.5;
            shakeTimer -= delta;
          } else {
            avoidPhase = 1; avoidTimer = 0;
          }
        } else if (avoidPhase === 1) {
          // Gore
          const progress = Math.min(avoidTimer / durations[1], 1);
          mower.position.y = MOWER_Y_BASE + easeInOut(progress) * 28;
          if (avoidTimer >= durations[1]) { avoidPhase = 2; avoidTimer = 0; }
        } else if (avoidPhase === 2) {
          // Pored panja
          mower.position.x += SPEED * mowerDir * delta;
          mower.position.y = MOWER_Y_BASE + 28;
          if (avoidTimer >= durations[2]) { avoidPhase = 3; avoidTimer = 0; }
        } else if (avoidPhase === 3) {
          // Dole
          const progress = Math.min(avoidTimer / durations[3], 1);
          mower.position.y = MOWER_Y_BASE + 28 - easeInOut(progress) * 28;
          if (avoidTimer >= durations[3]) { mowerState = "moving"; avoidStump = null; }
        }
      }

      // ── TRAVA ──
      blades.forEach((blade) => {
        if (!blade.mowed) {
          blade.mesh.rotation.z = Math.sin(t * 1.4 + blade.swayOff) * blade.swayAmp;
          if (Math.abs(blade.x - mx) < MOW_RAD && mower.position.y <= MOWER_Y_BASE + 8) {
            blade.mowed = true;
            // Kamenčić (5% šansa po travki)
            if (Math.random() < 0.05) spawnPebble(blade.x, GROUND_Y + 6);
          }
        }
        if (blade.mowed && blade.mowProgress < 1) {
          blade.mowProgress = Math.min(blade.mowProgress + 0.05 * delta, 1);
          const targetScale = BLADE_MIN_H / blade.fullH;
          blade.mesh.scale.y = 1 - (1 - targetScale) * easeOut(blade.mowProgress);
          blade.mesh.material.color.lerpColors(
            new THREE.Color("#3d7a32"), new THREE.Color("#9ab865"),
            blade.mowProgress * 0.7
          );
        }
      });

      // ── CVETOVI ──
      flowers.forEach((flower) => {
        if (!flower.mowed && Math.abs(flower.x - mx) < MOW_RAD && mower.position.y <= MOWER_Y_BASE + 8) {
          flower.mowed = true;
        }
        if (flower.mowed && flower.mowProgress < 1) {
          flower.mowProgress = Math.min(flower.mowProgress + 0.04 * delta, 1);
          const s = 1 - easeOut(flower.mowProgress);
          flower.mesh.scale.set(s, s, 1);
          flower.stalk.scale.y = Math.max(0.15, 1 - flower.mowProgress * 0.85);
          flower.mesh.material.opacity = 1 - flower.mowProgress;
        }
      });

      // ── DIM/PRAŠINA ──
      if (frameCount % 3 === 0 && mowerState === "moving") {
        spawnDust(mx - mowerDir * MOWER_W * 0.3, GROUND_Y + 6);
      }

      dust.forEach((d, idx) => {
        if (d.done) return;
        d.life += delta;
        const progress = d.life / d.maxLife;
        if (progress >= 1) { d.done = true; scene.remove(d.mesh); return; }
        d.mesh.position.x += d.vx * delta;
        d.mesh.position.y += d.vy * delta;
        d.vy *= 0.97;
        d.vx *= 0.96;
        const s = d.startSize * (1 + progress * 1.8);
        d.mesh.scale.set(s, s, 1);
        d.mesh.material.opacity = d.startOpacity * (1 - progress);
      });

      // ── KAMENČIĆI ──
      pebbles.forEach((p) => {
        if (p.done) return;
        p.mesh.position.x += p.vx * delta;
        p.mesh.position.y += p.vy * delta;
        p.vy -= p.gravity * delta;
        if (p.mesh.position.y < GROUND_Y - 2) {
          p.mesh.position.y = GROUND_Y - 2;
          p.vy *= -0.35;
          p.vx *= 0.6;
          if (Math.abs(p.vy) < 0.5) { p.done = true; p.mesh.position.y = GROUND_Y; }
        }
      });

      // ── LEPTIRI ──
      butterflies.forEach((bf) => {
        const distToMower = Math.abs(bf.x - mx);
        const fleeRadius  = 90;

        if (bf.state === "idle" && distToMower < fleeRadius) {
          bf.state = "fleeing";
          bf.fleeVx = (bf.x > mx ? 1 : -1) * rand(1.5, 2.8);
          bf.fleeVy = rand(2, 4);
        }

        if (bf.state === "fleeing") {
          bf.x += bf.fleeVx * delta;
          bf.y += bf.fleeVy * delta;
          bf.fleeVy -= 0.04 * delta;
          bf.fleeVx *= 0.99;
          // Kad se dovoljno udalji, počni da se vraćaš
          if (bf.y > GROUND_Y + 140 || distToMower > fleeRadius * 1.8) {
            bf.state = "returning";
          }
        } else if (bf.state === "returning") {
          bf.x += (bf.restX - bf.x) * 0.012 * delta;
          bf.y += (bf.restY - bf.y) * 0.012 * delta;
          if (Math.abs(bf.x - bf.restX) < 2 && Math.abs(bf.y - bf.restY) < 2) {
            bf.state = "idle";
          }
          // Ako kosilica ponovo priđe, bježi opet
          if (distToMower < fleeRadius) { bf.state = "fleeing"; }
        } else {
          // Idle — blago lebdenje
          bf.x = bf.restX + Math.sin(t * 0.7 + bf.flapPhase) * 8;
          bf.y = bf.restY + Math.sin(t * 0.5 + bf.flapPhase) * 5;
        }

        bf.mesh.position.set(bf.x, bf.y, 3);

        // Animacija krila — scaleX osciluje
        const flapScale = Math.sin(t * bf.flapSpeed + bf.flapPhase);
        bf.mesh.scale.x = 0.3 + Math.abs(flapScale) * 0.7;
        // Blago naginjanje u pravcu leta
        bf.mesh.rotation.z = bf.fleeVx ? -bf.fleeVx * 0.08 : Math.sin(t * 0.9) * 0.15;
      });

      renderer.render(scene, camera);
    }

    requestAnimationFrame(animate);
  }

  if (document.readyState === "complete") { init(); }
  else { window.addEventListener("load", init); }

})();
