import { chromium } from 'playwright';

const BASE = 'http://localhost:5174';
const TOKEN = process.argv[2];
const USER_JSON = process.argv[3];
const targetPath = process.argv[4] || '/home';

async function run() {
  const browser = await chromium.launch();
  const context = await browser.newContext({ viewport: { width: 768, height: 1024 } });
  const page = await context.newPage();
  await page.goto(`${BASE}${targetPath}`);
  await page.evaluate(({ token, userJson }) => {
    localStorage.setItem('token', token);
    localStorage.setItem('user', userJson);
  }, { token: TOKEN, userJson: USER_JSON });
  await page.goto(`${BASE}${targetPath}`, { waitUntil: 'networkidle' }).catch(() => {});
  await page.waitForTimeout(700);

  const offenders = await page.evaluate(() => {
    const vw = document.documentElement.clientWidth;
    const all = document.querySelectorAll('body *');
    const found = [];
    for (const el of all) {
      const rect = el.getBoundingClientRect();
      if (rect.right > vw + 2 || rect.width > vw + 2) {
        found.push({
          tag: el.tagName,
          cls: (el.className && el.className.toString) ? el.className.toString().slice(0, 120) : '',
          id: el.id,
          right: Math.round(rect.right),
          width: Math.round(rect.width),
          left: Math.round(rect.left),
        });
      }
    }
    return found.slice(0, 30);
  });
  console.log(JSON.stringify(offenders, null, 2));
  await browser.close();
}
run();
