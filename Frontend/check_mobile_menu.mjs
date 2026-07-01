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
  await page.screenshot({ path: 'mobile-menu-viewport.png', fullPage: false });
  console.log('saved mobile-menu-viewport.png');
  await browser.close();
}
run();
