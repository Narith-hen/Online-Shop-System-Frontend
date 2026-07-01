import { chromium } from 'playwright';

const BASE = 'http://localhost:5174';
const TOKEN = process.argv[2];
const USER_JSON = process.argv[3];

async function run() {
  const browser = await chromium.launch();
  const context = await browser.newContext({ viewport: { width: 375, height: 812 } });
  const page = await context.newPage();
  await page.goto(`${BASE}/home`);
  await page.evaluate(({ token, userJson }) => {
    localStorage.setItem('token', token);
    localStorage.setItem('user', userJson);
  }, { token: TOKEN, userJson: USER_JSON });
  await page.goto(`${BASE}/home`, { waitUntil: 'networkidle' }).catch(() => {});
  await page.waitForTimeout(600);
  await page.click('button[aria-label="Menu"]');
  await page.waitForTimeout(500);

  const info = await page.evaluate(() => {
    const html = document.documentElement;
    const body = document.body;
    const overlay = document.querySelector('.mobile-overlay.active') || document.querySelector('[class*="fixed inset-0 bg-black"]');
    // Find the drawer: fixed, left:0, top:0, width ~288px (w-72)
    const fixedEls = [...document.querySelectorAll('div')].filter(el => {
      const cs = getComputedStyle(el);
      return cs.position === 'fixed' && el.classList.contains('flex-col');
    });
    const drawer = fixedEls[0];
    const drawerInfo = drawer ? {
      rect: drawer.getBoundingClientRect(),
      computedHeight: getComputedStyle(drawer).height,
      children: [...drawer.children].map(c => ({
        tag: c.tagName,
        cls: c.className,
        rect: c.getBoundingClientRect(),
      })),
    } : null;
    return {
      htmlHeight: getComputedStyle(html).height,
      bodyHeight: getComputedStyle(body).height,
      htmlClientHeight: html.clientHeight,
      bodyClientHeight: body.clientHeight,
      overlayFound: !!overlay,
      overlayRect: overlay ? overlay.getBoundingClientRect() : null,
      drawer: drawerInfo,
    };
  });
  console.log(JSON.stringify(info, null, 2));
  await browser.close();
}
run();
