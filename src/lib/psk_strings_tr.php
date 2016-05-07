<?php

/**
 * PSK
 *
 * Açık kaynak kodlu bir web uygulama geliştirme kütüphanesi.
 *
 * @package       PSK (PHP Sınıf Kütüphanesi)
 * @author        Namık Kemal Karasu
 * @copyright     Copyright (C) Namık Kemal Karasu
 * @license       GPLv3
 * @since         Version 0.
 * @link          http://nkkarasu.net/psk/
 * @link          http://code.google.com/p/phpsk/
 */

/**
 * PSK metin sabitleri dosyası.
 * Bu dosya farklı diller için yerelleştirilebir.
 *
 * @package        PSK
 * @subpackage     Application
 * @category       String Constants
 * @author         Namık Kemal Karasu
 */

// Genel amaçlı metin sabitleri:
define ('PSK_STR_EXP_GENERALERROR', 'Şu anda isteğinizi gerçekleştiremiyoruz. Lütfen daha sonra tekrar deneyiniz.');

// Application strings:
if (@defined(DEBUG)) {
	define ('PSK_STR_APP_NOCONTROLLER', 'Belirtilen denetleyici oluşturulmamaış.</big><br/><strong>%s</strong>');
	define ('PSK_STR_APP_NOACTION', 'Belirtilen eylem tanımlanmamış.<br/><strong>%s</strong>');
} else {
	define ('PSK_STR_APP_NOCONTROLLER', '<big>404 Sayfa bulunamadı.</big><br/><strong>%s</strong>');
	define ('PSK_STR_APP_NOACTION', '<big>404 Sayfa bulunamadı.</big><br/><strong>%s</strong>');
}

// Authentication and authorization strings.
define ('PSK_STR_A_NOTAUTHORIZED', 'Bu sayfaya erişim yetkiniz yok. Lütfen %s');
define ('PSK_STR_A_LOGIN', '<strong>oturum açın.</strong>');

// Login control strings.
define ('PSK_STR_LC_TITLE', 'Lütfen giriş yapın...');
define ('PSK_STR_LC_TITLEOPEN', 'Hoşgeldiniz...');
define ('PSK_STR_LC_USER', 'Kullanıcı adı');
define ('PSK_STR_LC_PASSWORD', 'Parola');
define ('PSK_STR_LC_LOGINCOMMAND', 'Oturum aç');
define ('PSK_STR_LC_LOGOUTCOMMAND', 'Oturumu kapat');
define ('PSK_STR_LC_USERREQUIRED', 'Kullanıcı adı boş bırakılamaz.');
define ('PSK_STR_LC_PASSWORDREQUIRED', 'Parola boş bırakılamaz.');
define ('PSK_STR_LC_WRONGCREDENTIALS', 'Kullanıcı adınız yada parolanız yanlış.');
define ('PSK_STR_LC_LOGGEDINAS', '<em>%s</em> olarak oturum açılmış.');

// Database strings:
define ('PSK_STR_TBL_EMTYFIELDLIST', 'Bütün alan değerlerinide boş bırakamazsınız.');

// DBEditor strings.
define ('PSK_STR_DBE_ADD', 'Ekle');
define ('PSK_STR_DBE_EDIT', 'Değiştir');
define ('PSK_STR_DBE_DELETE', 'Sil');
define ('PSK_STR_DBE_CANCEL', 'İptal');
define ('PSK_STR_DBE_SELECT', 'Seç');
define ('PSK_STR_DBE_SAVE', 'Kaydet');
define ('PSK_STR_DBE_DELETEFILE', 'Kaldır');
define ('PSK_STR_DBE_SAVEFILE', 'Yükle');
define ('PSK_STR_DBE_CONFIRMDELETE', 'Bu kaydı silmek istediğinize emin misisniz?');
define ('PSK_STR_DBE_DELETECOMPLETE', 'İstediğiniz kayıt silindi.');
define ('PSK_STR_DBE_INSERTCOMPLETE', 'Yeni kayıt eklendi.');
define ('PSK_STR_DBE_UPDATECOMPLETE', 'Veriler güncellendi.');
define ('PSK_STR_DBE_NOFILE', '<strong>%s</strong> dosyası bulunamadı.');
define ('PSK_STR_DBE_COULDNOTDELETE', '<strong>%s</strong> dosyası silinemedi. Dosya izinlerini kontrole edin.');
define ('PSK_STR_DBE_FILEDELETED', '<strong>%s</strong> dosyası silindi.');
define ('PSK_STR_DBE_FILESAVED', 'Dosya kaydedildi.');
define ('PSK_STR_DBE_FILECOULDNOTSAVED', 'Dosya kaydedilemedi. Dosya izinlerini kontrol edin.');
define ('PSK_STR_DBE_IMAGEDELETED', 'Resim <strong>%s</strong> silindi.');
define ('PSK_STR_DBE_IMAGEUPLOADED', 'Resim yüklendi.');

?>
