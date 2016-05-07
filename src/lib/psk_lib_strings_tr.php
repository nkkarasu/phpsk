<?php

/**
 * PSK
 *
 * An open source PHP web application development framework.
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
 * PSK String Constants File.
 * This is a localizable file for different languages and includes only
 * production related strings. This means that if have completed your
 * application your users/clients/wisitors will not face of these strings.
 *
 * @package        PSK
 * @subpackage     Application
 * @category       String Constants for production.
 * @author         Namık Kemal Karasu
 */

// This defination has been added to check if localization file has get loaded
// or not.
define ('STRINGS', true);

// Generel purpose strings:
define ('PSK_STR_ERR_NOOWNERMETHOD', 'Ebeveyn denetleyicide <strong>%s</strong> adında bir yöntem tanımlanmamış.');
define ('PSK_STR_APP_NOCONTROLLERCLASS', 'Denetleyici sınıf tanımlanmamış.');
define ('PSK_STR_APP_NOVIEW', 'Belirtilen görünüm bulunamadı:<br/><strong>%s</strong>');
define ('PSK_STR_APP_NOMODEL', 'Belirtilen model bulunamadı:<br/><strong>%s</strong>');
define ('PSK_STR_APP_NOMODELCLASS', '<strong>%s</strong> adlı model sınıfı tanımlanmamış.');
define ('PSK_STR_APP_RESPONSE_ERROR', 'Sonuç tipi daha önce belirlendi.');

// Configuration strings:
define ('PSK_STR_CONF_INVALIDOPT', 'Geçersiz yapılandırma seçeneği.');
define ('PSK_STR_CONF_INVALIDSECT', 'Geçersiz yapılandırma bölümü.');
define ('PSK_STR_CONF_INVALIDCONFDATA', 'par_Config bir dizi ya da PSK_Config örneği değil.');

// Logging strings:
define ('PSK_STR_LOG_FILEERROR', 'Günlük dosyasına erişlemedi ya da dosya oluşturulamadı.');
define ('PSK_STR_LOG_INVALIDEXCEPTION', 'Geçersiz istisna nesnesi.');
define ('PSK_STR_LOG_EXCEPTION', 'İstisna: ');
define ('PSK_STR_LOG_EXCEPCODE', 'Kod: ');
define ('PSK_STR_LOG_EXCEPFILE', 'Dosya: ');
define ('PSK_STR_LOG_EXCEPLINE', 'Satır: ');
define ('PSK_STR_LOG_EXCEPTRACE', 'Adımlar: ');

// Layout strings:
define('PSK_STR_LYT_INVALIDTEMPLATEFILE', 'Şablon dosyası bulunamadı.');

// Controller strings:
define ('PSK_STR_CTL_USEDOBJECTNAME', 'Nesne adı zaten kullanılmakta.<br/><strong>%s</strong>');
define ('PSK_STR_CTL_UNDEFINEDMETHOD', '<strong>%2$s</strong> nesnesinin <strong>%1$s</strong> adında bir yöntemi yok.');
define ('PSK_STR_CTL_CONTROLNOTFOUND', 'Denetim bulunamadı.');

// Plugin loader strings:
define ('PSK_STR_PLG_NOLIBRARY', 'Eklenti kütüphanesi belirtilmemiş.');
define ('PSK_STR_PLG_NOCLASS', 'Eklenti sınıfı belirtilmemiş.');
define ('PSK_STR_PLG_CLASSNOTIMPLEMENTED', 'Eklenti sınıfı tanımlanmamış. Kaynak kod hatalı.<br/><strong>%s</strong>');
define ('PSK_STR_PLG_BASEFILENOTEXIST', 'Eklenti ana sınıf dosyası bulunamadı.<br/><strong>%s</strong>');
define ('PSK_STR_PLG_CLASSFILENOTEXIST', 'Eklenti sınıf dosyası bulunamadı.<br/><strong>%s</strong>');

// Database strings:
define ('PSK_STR_DB_NOOPENCONNECTION', 'Kurulmuş bir veritabanı bağlantısı yok.');
define ('PSK_STR_DB_NOSERVER', 'Veritabanı sunucusu ile bağlantı kurulamadı.<br/><strong>%s</strong><br/><em>%s</em>');
define ('PSK_STR_DB_ACCESSDENIED', 'Belirtilen kullanıcı adı ve parolası ile veritabanı sunucusunda oturum açılamadı. <br/>Muhtemelen kullanıcı adı veya parolası hatalı.<br/><strong>%s</strong><br/><em>%s</em>');
define ('PSK_STR_DB_NODATABASE', 'Sunucuda belirttiğinz adda bir veritabanı bulunamadı.<br/><strong>%s</strong><br/><em>%s</em>');
define ('PSK_STR_DB_NODATABASESELECTED', 'Bağlantı kurmak için lütfen bir veritabanı adı belirtin.<br/><em>%s</em>');
define ('PSK_STR_DB_CANTEXECQUERY', 'Sorgu çalıştırılamadı.<br/><strong>%s</strong><br/><em>%s</em>');
define ('PSK_STR_DB_NOFIELD', 'Sorguda belirttiğiniz isimde alan yok.<br/><strong>%s</strong><br/><em>%s</em>');
define ('PSK_STR_TBL_MISSINGTABLENAME', 'Tablo adı belirtilmemiş. <strong>setTable</strong> yöntemini kullanın.');
define ('PSK_STR_TBL_NOSUCHCOLUMN', '<strong>%s</strong> adında bir alan <strong>%s</strong> tablosunda bulunamadı.');

// Debug Messages.
define ('PSK_DM_MISSINGPIECE', 'Afedersiniz. Bu uyarı mesajını görmenizin neden PSK da eksik olan bir şeyi bulmanız. Lütfen bu hata mesajını <a href="mailto:psk@nkkarasu.net">psk@nkkarasu.net</a> adresine gönderiniz.<br/><strong><code>%s</code></strong><br/><small>Bu uyarılar DEBUG modunda görüntülenmez.</small>');

// Authentication and authorization strings.
define ('PSK_STR_A_CANTLOAD', 'Kimlik denetim sınıfı yüklenemedi.');
define ('PSK_STR_A_NOCP', 'Etkinleştirilmiş bir kimlik denetim sınıfı yok. Bu uygulamada kimlik denetimi yapılamaz.');
define ('PSK_STR_A_AUTHNEEDED', 'Yetkilendirme, etkin bir kimlik denetimin sınıfına ihtiyaç duyar. Kimlik denetim sınıflarından bir tanesini etkinleştirin.');
define ('PSK_STR_A_NODATA', 'Kimlik denetim verileri veritabanında bulunamadı.');

// Login control strings.
define ('PSK_STR_LC_NOAUTH', 'Oturum açma denetiminin otomatik doğrulama özelliğini kullanabilmeniz için bir kimlik denetim sınıfını etkinleştirmelisiniz.');
?>
