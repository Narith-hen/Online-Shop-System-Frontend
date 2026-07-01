import { chromium } from 'playwright';
import fs from 'node:fs';
import path from 'node:path';

const BASE = 'http://localhost:5174';
const SHOT_DIR = process.env.SCREENSHOT_DIR || 'C:\\Users\\sannb\\AppData\\Local\\Temp\\claude\\C--Users-sannb-OneDrive-Desktop\\a8da129f-8a1d-4dcc-abcd-d62a154ac9b0\\scratchpad\\shots';
fs.mkdirSync(SHOT_DIR, { recursive: true });

const TOKEN = process.argv[2];
const USER_JSON = process.argv[3];

const VIEWPORTS = [
  { name: 'mobile', width: 375, height: 812 },
  { name: 'tablet', width: 768, height: 1024 },
];

const PAGES = [
  { name: '00-welcome', path: '/', auth: false },
  { name: '01-login', path: '/login', auth: false },
  { name: '02-home', path: '/home', auth: true },
  { name: '03-products', path: '/products', auth: true },
  { name: '04-product-detail', path: '/products/1', auth: true },
  { name: '05-categories', path: '/categories', auth: true },
  { name: '06-cart', path: '/cart', auth: true },
  { name: '07-checkout', path: '/checkout', auth: true },
  { name: '08-wishlist', path: '/wishlist', auth: true },
  { name: '09-orders', path: '/orders', auth: true },
  { name: '10-profile', path: '/profile', auth: true },
  { name: '11-about', path: '/about', auth: true },
  { name: '12-contact', path: '/contact', auth: true },
];

const results = [];

async function run() {
  const browser = await chromium.launch();

  for (const vp of VIEWPORTS) {
    const context = await browser.newContext({ viewport: { width: vp.width, height: vp.height } });
    const page = await context.newPage();

    const consoleErrors = [];
    page.on('console', (msg) => {
      if (msg.type() === 'error') consoleErrors.push(msg.text());
    });

    for (const p of PAGES) {
      consoleErrors.length = 0;
      if (p.auth) {
        await page.goto(`${BASE}${p.path}`);
        await page.evaluate(({ token, userJson }) => {
          localStorage.setItem('token', token);
          localStorage.setItem('user', userJson);
        }, { token: TOKEN, userJson: USER_JSON });
        await page.goto(`${BASE}${p.path}`, { waitUntil: 'networkidle' }).catch(() => {});
      } else {
        await page.goto(`${BASE}${p.path}`, { waitUntil: 'networkidle' }).catch(() => {});
      }
      await page.waitForTimeout(700);

      const metrics = await page.evaluate(() => ({
        scrollWidth: document.documentElement.scrollWidth,
        clientWidth: document.documentElement.clientWidth,
        bodyScrollWidth: document.body.scrollWidth,
      }));
      const hasOverflow = metrics.scrollWidth > metrics.clientWidth + 2;

      const fname = path.join(SHOT_DIR, `${vp.name}-${p.name}.png`);
      await page.screenshot({ path: fname, fullPage: true });

      results.push({
        viewport: vp.name,
        page: p.name,
        path: p.path,
        url: page.url(),
        hasOverflow,
        scrollWidth: metrics.scrollWidth,
        clientWidth: metrics.clientWidth,
        consoleErrors: [...consoleErrors],
        screenshot: fname,
      });
      console.log(`[${vp.name}] ${p.path} -> ${fname} overflow=${hasOverflow} (${metrics.scrollWidth}/${metrics.clientWidth}) errors=${consoleErrors.length}`);
    }

    // Mobile hamburger menu check (only for mobile viewport, on an authed page)
    if (vp.name === 'mobile') {
      await page.goto(`${BASE}/home`, { waitUntil: 'networkidle' }).catch(() => {});
      await page.waitForTimeout(500);
      const hamburger = await page.$('button[aria-label="Menu"]');
      if (hamburger) {
        await hamburger.click();
        await page.waitForTimeout(400);
        const fname = path.join(SHOT_DIR, `${vp.name}-13-mobile-menu-open.png`);
        await page.screenshot({ path: fname, fullPage: true });
        console.log(`[${vp.name}] mobile menu -> ${fname}`);
      } else {
        console.log(`[${vp.name}] mobile menu button NOT FOUND`);
      }
    }

    await context.close();
  }

  await browser.close();
  fs.writeFileSync(path.join(SHOT_DIR, 'results.json'), JSON.stringify(results, null, 2));
  console.log('DONE');
}

run().catch((e) => { console.error('FATAL', e); process.exit(1); });
