<?php
// router.php for PHP Built-in Server
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$file = __DIR__ . $uri;

// If it's a file and exists, serve it
if (file_exists($file) && !is_dir($file)) {
    // Security check: prevent serving sensitive files if needed, but for local dev it's fine.
    // Except strictly forbidden ones like .htaccess or .env
    if (preg_match('/\.(htaccess|env|ini|log|lock)$/', $uri)) {
        http_response_code(403);
        echo "Forbidden";
        exit;
    }
    return false; // serve standard file
}

// Handle DirectoryIndex (index.php)
if (is_dir($file) && file_exists($file . '/index.php')) {
    // If request uri is just /folder, we might need a slash, but usually parse_url handles it?
    // Actually php -S handles directory redirects for trailing slash if we return false, 
    // but if we handle it:
    include $file . '/index.php';
    exit;
}

// Rewrite Rules mapped from .htaccess
$rules = [
    "^install(|/)$" => "/client/layout/pages/install/",
    "^trang-chu$" => "/dash.php",
    "^xac-minh-email$" => "/client/layout/pages/global/menu/verify-email.php",
    "^thong-tin-thanh-toan$" => "/client/layout/pages/global/menu/tttt.php",
    "^fake-bill-chuyen-khoan$" => "/client/layout/pages/global/menu/fakebill.php",
    "^fake-bill-priority$" => "/client/layout/pages/global/menu/fake-bill-priority.php",
    "^fake-so-du$" => "/client/layout/pages/global/menu/fakesodu.php",
    "^fake-bdsd$" => "/client/layout/pages/global/menu/fakebiendong.php",
    "^thue-goi$" => "/client/layout/pages/global/menu/thue-goi.php",
    "^fake-bill/binance$" => "/client/layout/pages/global/menu/fakebill-binance.php",
    "^fake-bill/man-hinh-khoa$" => "/client/layout/pages/global/menu/fakebill-manhinhkhoa.php",
    "^thue-app-bank-ao$" => "/client/layout/pages/global/menu/thue-app-bank-ao.php",
    "^danh-muc/ma-nguon/?$" => "/client/layout/pages/global/menu/ma-nguon.php?page=1",
    "^danh-muc/ma-nguon/trang/([0-9]+)/?$" => "/client/layout/pages/global/menu/ma-nguon.php?page=$1",
    "^(view-code|details)/([^/]+)$" => "/client/layout/pages/global/menu/chi-tiet-ma-nguon.php",
    "^thong-tin-goi$" => "/client/layout/pages/global/menu/info-vip.php",
    "^nap-tien/([^/]+)/(?:send|transfer)$" => "/client/layout/pages/global/menu/nap-tien.php",
    "^nap-tien/the-cao$" => "/client/layout/pages/global/menu/the-cao.php",
    "^fake-bill/mb-bank$" => "/client/layout/pages/global/sub-page/bill/mb-bank.php",
    "^fake-bill/mb-bank-don-luu$" => "/client/layout/pages/global/sub-page/bill/mb-bank-don-luu.php",
    "^fake-bill/mb-bank-tet$" => "/client/layout/pages/global/sub-page/bill/mb-bank-tet.php",
    "^fake-bill/vietcombank$" => "/client/layout/pages/global/sub-page/bill/vietcombank.php",
    "^fake-bill/techcombank$" => "/client/layout/pages/global/sub-page/bill/techcombank.php",
    "^fake-bill/vietinbank$" => "/client/layout/pages/global/sub-page/bill/vietinbank.php",
    "^fake-bill/acb$" => "/client/layout/pages/global/sub-page/bill/acb.php",
    "^fake-bill/momo$" => "/client/layout/pages/global/sub-page/bill/momo.php",
    "^fake-bill/zalopay$" => "/client/layout/pages/global/sub-page/bill/zalopay.php",
    "^fake-bill/tp-bank$" => "/client/layout/pages/global/sub-page/bill/tpbank.php",
    "^fake-bill/vp-bank$" => "/client/layout/pages/global/sub-page/bill/vpbank.php",
    "^fake-bill/msb$" => "/client/layout/pages/global/sub-page/bill/msb.php",
    "^fake-bill/agribank$" => "/client/layout/pages/global/sub-page/bill/agribank.php",
    "^fake-bill/bacabank$" => "/client/layout/pages/global/sub-page/bill/bacabank.php",
    "^fake-bill/viettel-money$" => "/client/layout/pages/global/sub-page/bill/viettel-money.php",
    "^fake-bill/sell-binance$" => "/client/layout/pages/global/sub-page/bill/sell-binance.php",
    "^fake-bill/buy-binance$" => "/client/layout/pages/global/sub-page/bill/buy-binance.php",
    "^fake-bill/okx$" => "/client/layout/pages/global/sub-page/bill/okx.php",
    "^fake-bill/man-hinh-khoa/mb-bank$" => "/client/layout/pages/global/sub-page/man-hinh-khoa/mb-bank.php",
    "^fake-bill/man-hinh-khoa/acb$" => "/client/layout/pages/global/sub-page/man-hinh-khoa/acb.php",
    "^fake-bill/man-hinh-khoa/momo$" => "/client/layout/pages/global/sub-page/man-hinh-khoa/momo.php",
    "^fake-bill/man-hinh-khoa/vietcombank$" => "/client/layout/pages/global/sub-page/man-hinh-khoa/vietcombank.php",
    "^fake-bill/man-hinh-khoa/binance$" => "/client/layout/pages/global/sub-page/man-hinh-khoa/binance.php",
    "^fake-bill/man-hinh-khoa/zalopay$" => "/client/layout/pages/global/sub-page/man-hinh-khoa/zalopay.php",
    "^fake-bill/man-hinh-khoa/zlpay$" => "/client/layout/pages/global/sub-page/man-hinh-khoa/zlpay.php",
    "^fake-bill/man-hinh-khoa/bidv$" => "/client/layout/pages/global/sub-page/man-hinh-khoa/bidv.php",
    "^fake-bill/man-hinh-khoa/sacombankpay$" => "/client/layout/pages/global/sub-page/man-hinh-khoa/sacombankpay.php",
    "^fake-bill/man-hinh-khoa/vietinbank$" => "/client/layout/pages/global/sub-page/man-hinh-khoa/vietinbank.php",
    "^fake-bill/man-hinh-khoa/techcombank$" => "/client/layout/pages/global/sub-page/man-hinh-khoa/techcombank.php",
    "^fake-bill/man-hinh-khoa/vib$" => "/client/layout/pages/global/sub-page/man-hinh-khoa/vib.php",
    "^fake-bill/man-hinh-khoa/msb$" => "/client/layout/pages/global/sub-page/man-hinh-khoa/msb.php",
    "^fake-bill/man-hinh-khoa/tpbank$" => "/client/layout/pages/global/sub-page/man-hinh-khoa/tpbank.php",
    "^fake-bill/man-hinh-khoa/agribank$" => "/client/layout/pages/global/sub-page/man-hinh-khoa/agribank.php",
    "^fake-bill-chuyen-khoan/mb-bank-priority$" => "/client/layout/pages/global/sub-page/bill/mb-bank-priority.php",
    "^fake-bill-chuyen-khoan/seabank-premium$" => "/client/layout/pages/global/sub-page/bill/seabank-premium.php",
    "^fake-bill-chuyen-khoan/acb-privilege$" => "/client/layout/pages/global/sub-page/bill/acb-privilege.php",
    "^fake-bill/hdbank" => "/client/layout/pages/global/sub-page/bill/hdbank.php",
    "^fake-bill/seabank$" => "/client/layout/pages/global/sub-page/bill/seabank.php",
    "^fake-bill/vibbank$" => "/client/layout/pages/global/sub-page/bill/vibbank.php",
    "^fake-bill/vietbank$" => "/client/layout/pages/global/sub-page/bill/vietbank.php",
    "^fake-bill/bvbank$" => "/client/layout/pages/global/sub-page/bill/bvbank.php",
    "^fake-bill/pgbank$" => "/client/layout/pages/global/sub-page/bill/pgbank.php",
    "^fake-bill/bidv$" => "/client/layout/pages/global/sub-page/bill/bidv.php",
    "^fake-bill/cake$" => "/client/layout/pages/global/sub-page/bill/cake.php",
    "^fake-bill/liobank$" => "/client/layout/pages/global/sub-page/bill/liobank.php",
    "^fake-bill/shinhanbank$" => "/client/layout/pages/global/sub-page/bill/shinhanbank.php",
    "^fake-bill/sacombank$" => "/client/layout/pages/global/sub-page/bill/sacombank.php",
    "^fake-bill/worri$" => "/client/layout/pages/global/sub-page/bill/worribank.php",
    "^fake-bill-chuyen-khoan/vietcombank-priority$" => "/client/layout/pages/global/sub-page/bill/vietcombank-priority.php",
    "^fake-bill-chuyen-khoan/vietcombank-galaxy$" => "/client/layout/pages/global/sub-page/bill/vietcombank-galaxy.php",
    "^fake-bill-chuyen-khoan/bidv-premier$" => "/client/layout/pages/global/sub-page/bill/bidv-premier.php",
    "^fake-bill/tp-bank-new$" => "/client/layout/pages/global/sub-page/bill/tpbank-new.php",
    "^fake-bill/namabank$" => "/client/layout/pages/global/sub-page/bill/namabank.php",
    "^fake-bill/vietinbank-1$" => "/client/layout/pages/global/sub-page/bill/vietinbankred.php",
    "^fake-bill/pvbank$" => "/client/layout/pages/global/sub-page/bill/pvbank.php",
    "^fake-bill/ocb$" => "/client/layout/pages/global/sub-page/bill/ocb.php",
    "^fake-bill/chinhsachxahoi$" => "/client/layout/pages/global/sub-page/bill/chinhsachxahoi.php",
    "^fake-bill/eximbank$" => "/client/layout/pages/global/sub-page/bill/eximbank.php",
    "^fake-bill/saigonbank$" => "/client/layout/pages/global/sub-page/bill/saigonbank.php",
    "^fake-bill/abbank$" => "/client/layout/pages/global/sub-page/bill/abbank.php",
    "^fake-bill/ncb$" => "/client/layout/pages/global/sub-page/bill/ncb.php",
    "^fake-bill/lpbank$" => "/client/layout/pages/global/sub-page/bill/lpbank.php",
    "^fake-bill/kienlongbank$" => "/client/layout/pages/global/sub-page/bill/kienlongbank.php",
    "^fake-bill/shb$" => "/client/layout/pages/global/sub-page/bill/shb.php",
    "^fake-bill/vietcombank-new$" => "/client/layout/pages/global/sub-page/bill/vietcombank-new.php",
    "^fake-bill/acb-new$" => "/client/layout/pages/global/sub-page/bill/acb-new.php",
    "^fake-so-du/vietinbank$" => "/client/layout/pages/global/sub-page/so-du/vietinbank.php",
    "^fake-so-du/momo$" => "/client/layout/pages/global/sub-page/so-du/momo.php",
    "^fake-so-du/mb-bank$" => "/client/layout/pages/global/sub-page/so-du/mb-bank.php",
    "^fake-so-du/zalopay$" => "/client/layout/pages/global/sub-page/so-du/zalopay.php",
    "^fake-so-du/acb$" => "/client/layout/pages/global/sub-page/so-du/acb.php",
    "^fake-so-du/techcombank$" => "/client/layout/pages/global/sub-page/so-du/techcombank.php",
    "^fake-so-du/vietcombank$" => "/client/layout/pages/global/sub-page/so-du/vietcombank.php",
    "^fake-so-du/cake$" => "/client/layout/pages/global/sub-page/so-du/cake.php",
    "^fake-so-du/mb-bank-priority$" => "/client/layout/pages/global/sub-page/so-du/mb-priority.php",
    "^fake-so-du/agribank$" => "/client/layout/pages/global/sub-page/so-du/agribank.php",
    "^fake-so-du/tpbank$" => "/client/layout/pages/global/sub-page/so-du/tpbank.php",
    "^fake-so-du/vpbank$" => "/client/layout/pages/global/sub-page/so-du/vpbank.php",
    "^fake-bdsd/mb-bank$" => "/client/layout/pages/global/sub-page/bdsd/mb-bank.php",
    "^fake-bdsd/techcombank$" => "/client/layout/pages/global/sub-page/bdsd/techcombank.php",
    "^tao-qr/cccd$" => "/client/layout/pages/global/sub-page/giay-to/qr-cccd.php",
    "^fake-cccd$" => "/client/layout/pages/global/sub-page/giay-to/cccd.php",
    "^fake-velenmaybay$" => "/client/layout/pages/global/sub-page/giay-to/velenmaybay.php",
    "^fake-velenmaybay2$" => "/client/layout/pages/global/sub-page/giay-to/velenmaybay2.php",
    "^fake-velenmaybay3$" => "/client/layout/pages/global/sub-page/giay-to/velenmaybay3.php",
    "^fake-vedientu$" => "/client/layout/pages/global/sub-page/giay-to/vedientu.php",
    "^fake-giayphepkinhdoanh$" => "/client/layout/pages/global/sub-page/giay-to/giayphepkinhdoanh.php",
    "^tools/spam-sms$" => "/client/layout/pages/global/sub-page/tools/spam-sms.php",
    "^tools/spam-ngl-link$" => "/client/layout/pages/global/sub-page/tools/spam-ngl.php",
    "^bad-error$" => "/client/layout/pages/global/error/bad-error.php",
    "^download/([a-fA-F0-9]{32})/?$" => "/function/insert/protect/verify.php?code=$1",
    "^oauth/(dang-nhap|login|signIn)$" => "/client/layout/pages/auth/dang-nhap.php",
    "^oauth/(dang-ky|register|signUp)$" => "/client/layout/pages/auth/dang-ky.php",
    "^oauth/(dang-xuat|logout|kill-log)$" => "/client/layout/pages/auth/dang-xuat.php",
    "^oauth/(quen-mat-khau|forgot-password|recover-password)$" => "/client/layout/pages/auth/quen-mat-khau.php",
    "^user/([^/]+)/settings/?$" => "/client/layout/pages/user/profile.php",
    "^lich-su/nap-tien$" => "/client/layout/pages/global/menu/lich-su-nap-tien.php",
    "^lich-su/mua-goi$" => "/client/layout/pages/global/menu/lich-su-mua-goi.php",
    "^lich-su/tao-bill$" => "/client/layout/pages/global/menu/lich-su-tao-bill.php",
    "^lich-su/mua-hang$" => "/client/layout/pages/global/menu/lich-su-mua-hang.php",
    "^admin/auth/logout$" => "/admin/auth/logout.php",
    "^admin/dashboard$" => "/admin/index.php",
    "^admin/create/newfeeds$" => "/admin/add-newfeeds.php",
    "^admin/newfeeds/list$" => "/admin/newfeeds-list.php",
    "^admin/create/product$" => "/admin/add-product.php",
    "^admin/product/list$" => "/admin/product-list.php",
    "^admin/users/list$" => "/admin/user-list.php",
    "^admin/users/banned$" => "/admin/user-banned.php",
    "^admin/history/bank$" => "/admin/bank.php",
    "^admin/history/card$" => "/admin/card.php",
    "^admin/manager/payment$" => "/admin/payment.php",
    "^admin/activity/log$" => "/admin/activity.php",
    "^admin/manager/plan$" => "/admin/plan.php",
    "^admin/history/plan$" => "/admin/history-plan.php",
    "^admin/users/edit/([0-9a-zA-Z]+)$" => "/admin/edit-user.php",
    "^admin/history/bill$" => "/admin/bill.php",
    "^admin/cron-jobs$" => "/admin/cron-jobs.php",
    "^admin/settings(/.*)?$" => "/admin/settings.php",
    "^admin/tutorial-of-admin$" => "/admin/tutorial.php",
    "^call-back/api/auto-bank$" => "/function/call-back/api/auto-bank.php",
    "^call-back/api/n$" => "/function/call-back/api/notify.ini.php",
    "^call-back/api/card5s$" => "/function/call-back/api/card5s.php",
    "^call-back/api/thesieure$" => "/function/call-back/api/thesieure.php",
    "^call-back/api/spam-sms$" => "/function/call-back/api/spam-sms.php",
    "^call-back/api/spam-ngl$" => "/function/call-back/api/spam-ngl.php",
    "^log$" => "/function/call-back/controller/log.php",
    "^cron-jobs/global$" => "/function/cron/global.php",
    "^call-back/lavender$" => "/function/insert/alert/vip.ini.php",
    "^ajax/global/users$" => "/app/ajax/global/users/ajax-user.php",
    "^ajax/global/admin$" => "/app/ajax/global/admin/ajax-admin.php",
    "^ajax/global/default$" => "/app/ajax/global/default/ws-demo.php",
    "^ajax/global/default/stats$" => "/app/ajax/global/default/stats.php",
    "^ajax/global/installer$" => "/app/ajax/global/default/installer.php",
    "^ajax/global/default/download$" => "/app/ajax/global/default/download.php",
];

foreach ($rules as $pattern => $target) {
    $path = ltrim($uri, '/');
    if (preg_match('#' . $pattern . '#', $path, $matches)) {
        $targetUrl = preg_replace('#' . $pattern . '#', $target, $path);

        // Parse query params if any
        $parsed = parse_url($targetUrl);
        if (isset($parsed['query'])) {
            parse_str($parsed['query'], $query);
            $_GET = array_merge($_GET, $query);
            $_REQUEST = array_merge($_REQUEST, $query);
        }

        $fileToInclude = __DIR__ . $parsed['path'];
        if (file_exists($fileToInclude)) {
            // Update $_SERVER['SCRIPT_NAME'] and PHP_SELF to trick the script
            $_SERVER['SCRIPT_NAME'] = $parsed['path'];
            $_SERVER['PHP_SELF'] = $parsed['path'];
            include $fileToInclude;
            exit;
        } else {
            // Target file not found
            http_response_code(404);
            echo "Router: Target file not found: " . $parsed['path'];
            exit;
        }
    }
}

// Default 404
if (file_exists(__DIR__ . '/client/layout/pages/global/error/404.php')) {
    include __DIR__ . '/client/layout/pages/global/error/404.php';
} else {
    http_response_code(404);
    echo "404 Not Found";
}
