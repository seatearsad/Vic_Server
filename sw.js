importScripts("https://storage.googleapis.com/workbox-cdn/releases/3.1.0/workbox-sw.js");
var cacheStorageKey = 'Tutti-pwa-';
var version_num = '1.0.0';
var cacheList=[
    '/tpl/Static/blue/images/new/icon.png',
    '/tpl/Wap/pure/static/css/index.css'
]
self.addEventListener('install',e =>{
    // install 事件，它发生在浏览器安装并注册 Service Worker 时
    // e.waitUtil 用于在安装成功之前执行一些预装逻辑
    e.waitUntil(
        caches.open(cacheStorageKey+version_num)
            .then(cache => cache.addAll(cacheList))
            .then(() => self.skipWaiting())
    )
});
self.addEventListener('fetch',function(e){
    e.respondWith(
        caches.match(e.request).then(function(response){
            if(response != null){
                return response
            }
            return fetch(e.request)
        })
    )
});
self.addEventListener('activated',function(e){
    e.waitUntil(
        //获取所有cache名称
        caches.keys().then(cacheNames => {
            return Promise.all(
                // 获取所有不同于当前版本名称cache下的内容
                cacheNames.filter(cacheNames => {
                    return cacheNames !== cacheStorageKey+version_num
                }).map(cacheNames => {
                    return caches.delete(cacheNames)
                })
            )
        }).then(() => {
            return self.clients.claim()
        })
    )
});