// const puppeteer = require('puppeteer');

// async function getHTML(url) {
//     const browser = await puppeteer.launch({ headless: true });
//     const page = await browser.newPage();
    
//     // Thiết lập User-Agent của trình duyệt
//     await page.setUserAgent('Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36');
    
//     await page.goto(url, { waitUntil: 'networkidle2' });
//     const html = await page.content();
//     await browser.close();
//     return html;
// }

// const url = process.argv[2];

// getHTML(url).then(html => {
//     console.log(html);
// }).catch(error => {
//     console.error(error);
// });


// const puppeteer = require('puppeteer');

// (async () => {
//     const browser = await puppeteer.launch({ headless: true, args: ['--no-sandbox', '--disable-setuid-sandbox'] }); // headless: false để thấy trình duyệt
//     const page = await browser.newPage();
//     await page.goto('https://www.tripadvisor.com.vn/Hotel_Review-g1184679-d5953366-Reviews-Phu_Van_Resort_Spa-Duong_Dong_Phu_Quoc_Island_Kien_Giang_Province.html', { waitUntil: 'networkidle2' });
//     const html = await page.content();
//     console.log(html);
//     await browser.close();
// })();

const puppeteer = require('puppeteer');

(async () => {
    // const browser = await puppeteer.launch({ headless: true, args: ['--no-sandbox', '--disable-setuid-sandbox'] }); // headless: false để thấy trình duyệt
    // const page = await browser.newPage();
    // await page.goto('https://www.tripadvisor.com.vn/Hotel_Review-g1184679-d5953366-Reviews-Phu_Van_Resort_Spa-Duong_Dong_Phu_Quoc_Island_Kien_Giang_Province.html', { waitUntil: 'networkidle2' });
    // const html = await page.content();
    // console.log(html);
    // await browser.close();
    const url = 'https://www.tripadvisor.com.vn/Hotel_Review-g1184679-d5953366-Reviews-Phu_Van_Resort_Spa-Duong_Dong_Phu_Quoc_Island_Kien_Giang_Province.html';
    const browser = await puppeteer.launch({
        headless: true, // headless: false để thấy trình duyệt, có thể đổi thành true sau khi hoàn thành debug
        args: ['--no-sandbox', '--disable-setuid-sandbox']
    });
    const page = await browser.newPage();
    
    // Thiết lập User-Agent của trình duyệt
    await page.setUserAgent('Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36');

    // Thiết lập viewport để mô phỏng kích thước màn hình người dùng thật
    await page.setViewport({ width: 1280, height: 800 });
    
    // Điều hướng đến URL và chờ cho đến khi trang tải xong
    await page.goto(url, { waitUntil: 'networkidle2' });
    
    // // Chờ một thời gian nhất định để đảm bảo các script khác được tải
    // await page.waitForTimeout(5000); // chờ 5 giây, bạn có thể điều chỉnh thời gian này nếu cần
    
    // Lấy nội dung trang web sau khi đã chờ
    const html = await page.content();
    console.log(html);
    await browser.close();
    return html;
})();

// const puppeteer = require('puppeteer');

// async function getHTML(url) {
//     const browser = await puppeteer.launch({
//         headless: false, // headless: false để thấy trình duyệt, có thể đổi thành true sau khi hoàn thành debug
//         args: ['--no-sandbox', '--disable-setuid-sandbox']
//     });
//     const page = await browser.newPage();
    
//     // Thiết lập User-Agent của trình duyệt
//     await page.setUserAgent('Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36');

//     // Thiết lập viewport để mô phỏng kích thước màn hình người dùng thật
//     await page.setViewport({ width: 1280, height: 800 });
    
//     // Điều hướng đến URL và chờ cho đến khi trang tải xong
//     await page.goto(url, { waitUntil: 'networkidle2' });
    
//     // Chờ một thời gian nhất định để đảm bảo các script khác được tải
//     await page.waitForTimeout(5000); // chờ 5 giây, bạn có thể điều chỉnh thời gian này nếu cần
    
//     // Lấy nội dung trang web sau khi đã chờ
//     const html = await page.content();
//     await browser.close();
//     return html;
// }

// const url = process.argv[2];

// getHTML(url).then(html => {
//     console.log(html);
// }).catch(error => {
//     console.error(error);
// });