<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| Display Debug backtrace
|--------------------------------------------------------------------------
|
| If set to TRUE, a backtrace will be displayed along with php errors. If
| error_reporting is disabled, the backtrace will not display, regardless
| of this setting
|
*/
defined('SHOW_DEBUG_BACKTRACE') OR define('SHOW_DEBUG_BACKTRACE', TRUE);

/*
|--------------------------------------------------------------------------
| File and Directory Modes
|--------------------------------------------------------------------------
|
| These prefs are used when checking and setting modes when working
| with the file system.  The defaults are fine on servers with proper
| security, but you may wish (or even need) to change the values in
| certain environments (Apache running a separate process for each
| user, PHP under CGI with Apache suEXEC, etc.).  Octal values should
| always be used to set the mode correctly.
|
*/
defined('FILE_READ_MODE') OR define('FILE_READ_MODE', 0644);
defined('FILE_WRITE_MODE') OR define('FILE_WRITE_MODE', 0666);
defined('DIR_READ_MODE') OR define('DIR_READ_MODE', 0755);
defined('DIR_WRITE_MODE') OR define('DIR_WRITE_MODE', 0755);

/*
|--------------------------------------------------------------------------
| File Stream Modes
|--------------------------------------------------------------------------
|
| These modes are used when working with fopen()/popen()
|
*/
defined('FOPEN_READ') OR define('FOPEN_READ', 'rb');
defined('FOPEN_READ_WRITE') OR define('FOPEN_READ_WRITE', 'r+b');
defined('FOPEN_WRITE_CREATE_DESTRUCTIVE') OR define('FOPEN_WRITE_CREATE_DESTRUCTIVE', 'wb'); // truncates existing file data, use with care
defined('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE') OR define('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE', 'w+b'); // truncates existing file data, use with care
defined('FOPEN_WRITE_CREATE') OR define('FOPEN_WRITE_CREATE', 'ab');
defined('FOPEN_READ_WRITE_CREATE') OR define('FOPEN_READ_WRITE_CREATE', 'a+b');
defined('FOPEN_WRITE_CREATE_STRICT') OR define('FOPEN_WRITE_CREATE_STRICT', 'xb');
defined('FOPEN_READ_WRITE_CREATE_STRICT') OR define('FOPEN_READ_WRITE_CREATE_STRICT', 'x+b');

/*
|--------------------------------------------------------------------------
| Exit Status Codes
|--------------------------------------------------------------------------
|
| Used to indicate the conditions under which the script is exit()ing.
| While there is no universal standard for error codes, there are some
| broad conventions.  Three such conventions are mentioned below, for
| those who wish to make use of them.  The CodeIgniter defaults were
| chosen for the least overlap with these conventions, while still
| leaving room for others to be defined in future versions and user
| applications.
|
| The three main conventions used for determining exit status codes
| are as follows:
|
|    Standard C/C++ Library (stdlibc):
|       http://www.gnu.org/software/libc/manual/html_node/Exit-Status.html
|       (This link also contains other GNU-specific conventions)
|    BSD sysexits.h:
|       http://www.gsp.com/cgi-bin/man.cgi?section=3&topic=sysexits
|    Bash scripting:
|       http://tldp.org/LDP/abs/html/exitcodes.html
|
*/
defined('EXIT_SUCCESS') OR define('EXIT_SUCCESS', 0); // no errors
defined('EXIT_ERROR') OR define('EXIT_ERROR', 1); // generic error
defined('EXIT_CONFIG') OR define('EXIT_CONFIG', 3); // configuration error
defined('EXIT_UNKNOWN_FILE') OR define('EXIT_UNKNOWN_FILE', 4); // file not found
defined('EXIT_UNKNOWN_CLASS') OR define('EXIT_UNKNOWN_CLASS', 5); // unknown class
defined('EXIT_UNKNOWN_METHOD') OR define('EXIT_UNKNOWN_METHOD', 6); // unknown class member
defined('EXIT_USER_INPUT') OR define('EXIT_USER_INPUT', 7); // invalid user input
defined('EXIT_DATABASE') OR define('EXIT_DATABASE', 8); // database error
defined('EXIT__AUTO_MIN') OR define('EXIT__AUTO_MIN', 9); // lowest automatically-assigned error code
defined('EXIT__AUTO_MAX') OR define('EXIT__AUTO_MAX', 125); // highest automatically-assigned error code

/*
|--------------------------------------------------------------------------
| Kirana Constants
|--------------------------------------------------------------------------
*/
defined('KIRANA_EMAIL_HOST') OR define('KIRANA_EMAIL_HOST', 'mail.kiranamegatara.com');
defined('KIRANA_EMAIL_USER') OR define('KIRANA_EMAIL_USER', 'no-reply@kiranamegatara.com');
defined('KIRANA_EMAIL_PASS') OR define('KIRANA_EMAIL_PASS', '1234567890');
defined('KIRANA_EMAIL_PORT') OR define('KIRANA_EMAIL_PORT', '465');
defined('KIRANA_PATH_ASSETS') OR define('KIRANA_PATH_ASSETS', realpath('./') . '/assets/');
defined('KIRANA_PATH_FILE') OR define('KIRANA_PATH_FILE', realpath('./') . '/assets/file/');
defined('KIRANA_PATH_APPS') OR define('KIRANA_PATH_APPS', realpath('./') . '/assets/apps/');
defined('KIRANA_PATH_LOGS') OR define('KIRANA_PATH_LOGS', realpath('./') . '/application/logs/');
defined('KIRANA_PATH_FILE_FOLDER') OR define('KIRANA_PATH_FILE_FOLDER', 'file/');
defined('KIRANA_PATH_APPS_IMAGE_FOLDER') OR define('KIRANA_PATH_APPS_IMAGE_FOLDER', 'img/');
defined('KIRANA_SERVER') OR define('KIRANA_SERVER', json_encode(array("10.0.0.106")));
defined('DB_DEFAULT') OR define('DB_DEFAULT', 'DashBoardDev');
defined('DB_PORTAL') OR define('DB_PORTAL', 'portal_dev');
defined('DB_SDO') OR define('DB_SDO', 'sdo_dev');
defined('FILE_KONEKSI_SAP') OR define('FILE_KONEKSI_SAP', APPPATH . 'config/koneksi.ini');

defined('SETTINGS_FOTO_PATH') OR define('SETTINGS_FOTO_PATH', 'foto/');

defined('PASSWORD_EXPIRED_MODE') OR define('PASSWORD_EXPIRED_MODE', false);
defined('PASSWORD_EXPIRED_MONTH') OR define('PASSWORD_EXPIRED_MONTH', 6);

/*
|--------------------------------------------------------------------------
| Kirana ESS Constants
|--------------------------------------------------------------------------
*/
defined('ESS_PATH_FILE') OR define('ESS_PATH_FILE', realpath('./') . '/assets/file/ess/');
defined('ESS_CUTI_UPLOAD_FOLDER') OR define('ESS_CUTI_UPLOAD_FOLDER', 'cuti/');

defined('ESS_CUTI_GROUP_AREA_KMTR') OR define('ESS_CUTI_GROUP_AREA_KMTR', 51);

defined('ESS_CUTI_STATUS_MENUNGGU') OR define('ESS_CUTI_STATUS_MENUNGGU', 1);
defined('ESS_CUTI_STATUS_DISETUJUI_ATASAN') OR define('ESS_CUTI_STATUS_DISETUJUI_ATASAN', 2);
defined('ESS_CUTI_STATUS_DISETUJUI_HR') OR define('ESS_CUTI_STATUS_DISETUJUI_HR', 3);
defined('ESS_CUTI_STATUS_DITOLAK') OR define('ESS_CUTI_STATUS_DITOLAK', 4);
defined('ESS_CUTI_STATUS_DIBATALKAN') OR define('ESS_CUTI_STATUS_DIBATALKAN', 5);

defined('ESS_CUTI_TAHUNAN') OR define('ESS_CUTI_TAHUNAN', 94);

defined('ESS_CUTI_JENIS_CUTI_BERSAMA') OR define('ESS_CUTI_JENIS_CUTI_BERSAMA', '0120');
defined('ESS_CUTI_JENIS_SAKIT_W_SURAT') OR define('ESS_CUTI_JENIS_SAKIT_W_SURAT', '0210');
defined('ESS_CUTI_JENIS_SAKIT_PANJANG') OR define('ESS_CUTI_JENIS_SAKIT_PANJANG', '0240');
defined('ESS_CUTI_JENIS_KEGUGURAN') OR define('ESS_CUTI_JENIS_KEGUGURAN', '0250');
defined('ESS_CUTI_JENIS_KECELAKAAN') OR define('ESS_CUTI_JENIS_KECELAKAAN', '0260');
defined('ESS_CUTI_JENIS_FORCE') OR define('ESS_CUTI_JENIS_FORCE', '0370');
defined('ESS_CUTI_JENIS_DITAHAN') OR define('ESS_CUTI_JENIS_DITAHAN', '400');
defined('ESS_CUTI_JENIS_SKORSING') OR define('ESS_CUTI_JENIS_SKORSING', '410');
defined('ESS_CUTI_JENIS_TDK_DIBAYAR') OR define('ESS_CUTI_JENIS_TDK_DIBAYAR', '0520');
defined('ESS_CUTI_SAKIT_T_SURAT') OR define('ESS_CUTI_SAKIT_T_SURAT', '0540');
defined('ESS_CUTI_JENIS_DINAS') OR define('ESS_CUTI_JENIS_DINAS', '0610');
defined('ESS_CUTI_JENIS_TRAINING') OR define('ESS_CUTI_JENIS_TRAINING', '0620');
defined('ESS_CUTI_JENIS_MEETING') OR define('ESS_CUTI_JENIS_MEETING', '0630');

defined('ESS_MEDICAL_UPLOAD_FOLDER') OR define('ESS_MEDICAL_UPLOAD_FOLDER', 'kwitansi/');

defined('ESS_MEDICAL_JUMLAH_TAHUN_JOIN') OR define('ESS_MEDICAL_JUMLAH_TAHUN_JOIN', 1);
defined('ESS_MEDICAL_JUMLAH_TAHUN_LENSA') OR define('ESS_MEDICAL_JUMLAH_TAHUN_LENSA', 1);
defined('ESS_MEDICAL_JUMLAH_TAHUN_FRAME') OR define('ESS_MEDICAL_JUMLAH_TAHUN_FRAME', 2);

defined('ESS_MEDICAL_STATUS_MENUNGGU') OR define('ESS_MEDICAL_STATUS_MENUNGGU', 1);
defined('ESS_MEDICAL_STATUS_TDK_LENGKAP') OR define('ESS_MEDICAL_STATUS_TDK_LENGKAP', 2);
defined('ESS_MEDICAL_STATUS_LENGKAP') OR define('ESS_MEDICAL_STATUS_LENGKAP', 3);
defined('ESS_MEDICAL_STATUS_DISETUJUI') OR define('ESS_MEDICAL_STATUS_DISETUJUI', 4);
defined('ESS_MEDICAL_JENIS') OR define('ESS_MEDICAL_JENIS', json_encode(array(
    'BRJL' => 'Rawat Jalan',
    'BRIN' => 'Rawat Inap',
    'BLNS' => 'Lensa',
    'BBKI' => 'Frame',
    'BBNR' => 'Persalinan (Normal)',
    'BBCS' => 'Persalinan (Cesar)',
)));

defined('ESS_BAK_UPLOAD_FOLDER') OR define('ESS_BAK_UPLOAD_FOLDER', 'bak/');

defined('ESS_BAK_STATUS_DEFAULT') OR define('ESS_BAK_STATUS_DEFAULT', 0);
defined('ESS_BAK_STATUS_TDK_ABSENT') OR define('ESS_BAK_STATUS_TDK_ABSENT', 1);
defined('ESS_BAK_STATUS_MENUNGGU') OR define('ESS_BAK_STATUS_MENUNGGU', 2);
defined('ESS_BAK_STATUS_DISETUJUI') OR define('ESS_BAK_STATUS_DISETUJUI', 3);
defined('ESS_BAK_STATUS_COMPLETE') OR define('ESS_BAK_STATUS_COMPLETE', 4);
defined('ESS_BAK_STATUS_DITOLAK') OR define('ESS_BAK_STATUS_DITOLAK', 5);
defined('ESS_BAK_STATUS_DISETUJUI_OLEH_HR') OR define('ESS_BAK_STATUS_DISETUJUI_OLEH_HR', 6);
defined('ESS_BAK_STATUS_DIBATALKAN') OR define('ESS_BAK_STATUS_DIBATALKAN', 7);

defined('ESS_BAK_ALASAN_KOSONG') OR define('ESS_BAK_ALASAN_KOSONG', 0);
defined('ESS_BAK_ALASAN_TERLAMBAT') OR define('ESS_BAK_ALASAN_TERLAMBAT', 2);
defined('ESS_BAK_ALASAN_PULANG_CEPAT') OR define('ESS_BAK_ALASAN_PULANG_CEPAT', 3);
defined('ESS_BAK_ALASAN_KOMBINASI_DTG_PLG') OR define('ESS_BAK_ALASAN_KOMBINASI_DTG_PLG', 7);
defined('ESS_BAK_ALASAN_HAPUS_BAK') OR define('ESS_BAK_ALASAN_HAPUS_BAK', 8);
defined('ESS_BAK_ALASAN_LAIN') OR define('ESS_BAK_ALASAN_LAIN', 99);

defined('ESS_EMAIL_DEBUG_MODE') OR define('ESS_EMAIL_DEBUG_MODE', true);
defined('ESS_EMAIL_SCHEDULER_DEBUG_MODE') OR define('ESS_EMAIL_SCHEDULER_DEBUG_MODE', true);
defined('ESS_EMAIL_TESTER') OR define('ESS_EMAIL_TESTER', json_encode(array('airiza.perdana@kiranamegatara.com', 'auliya.umami_off@kiranamegatara.com', 'ady.pudriansyah@kiranamegatara.com')));

/*
|--------------------------------------------------------------------------
| Kirana Mobile Reporting Internal API Constants
|--------------------------------------------------------------------------
*/
defined('FCM_API_KEY') OR define('FCM_API_KEY', 'AAAAd5GmBq4:APA91bFo5Pv9bSlZ40wmlN7tFZ9DHJaTBeV1u33fueDEvlyFHu4FluczBg8wxbYlWlDySima0p8NIXapuMhm1B9oV2XhzMi90dOEfwVZUcBPXuxwaGKeWPeFIzpsC_gIww46RtEnIc2-');
defined('FCM_CODE_ALERT') OR define('FCM_CODE_ALERT', '001');
defined('FCM_COLOR_ALERT') OR define('FCM_COLOR_ALERT', "#FF0000");

/*
|--------------------------------------------------------------------------
| Kirana SPK Constants
|--------------------------------------------------------------------------
*/
defined('SPK_EMAIL_DEBUG_MODE') OR define('SPK_EMAIL_DEBUG_MODE', true);
defined('SPK_EMAIL_TESTER') OR define('SPK_EMAIL_TESTER', json_encode(array('salma.arlis@kiranamegatara.com', 'frans.darmawan@kiranamegatara.com')));

defined('SPK_UPLOAD_FOLDER') OR define('SPK_UPLOAD_FOLDER', 'legal/');
defined('SPK_UPLOAD_TEMPLATE_FOLDER') OR define('SPK_UPLOAD_TEMPLATE_FOLDER', 'dokumen_draft_1/');
defined('SPK_UPLOAD_VENDOR_FOLDER') OR define('SPK_UPLOAD_VENDOR_FOLDER', 'dokumen_vendor/');
defined('SPK_UPLOAD_FINAL_DRAFT_FOLDER') OR define('SPK_UPLOAD_FINAL_DRAFT_FOLDER', 'draft_final/');
defined('SPK_UPLOAD_SPK_FOLDER') OR define('SPK_UPLOAD_SPK_FOLDER', 'spk/');

/*
|--------------------------------------------------------------------------
| Kirana PM Constants
|--------------------------------------------------------------------------
*/
defined('PM_EMAIL_DEBUG_MODE') OR define('PM_EMAIL_DEBUG_MODE', true);
defined('PM_EMAIL_TESTER') OR define('PM_EMAIL_TESTER', json_encode(array('ADY.PUDRIANSYAH@KIRANAMEGATARA.COM', 'frans.darmawan@kiranamegatara.com', 'lukman.hakim@kiranamegatara.com','DJAKA.PRASETYA@KIRANAMEGATARA.COM','AIRIZA.PERDANA@KIRANAMEGATARA.COM')));

/*
|--------------------------------------------------------------------------
| Kirana Mobile Ranger Constants
|--------------------------------------------------------------------------
*/
defined('MR_DEBUG_MODE') OR define('MR_DEBUG_MODE', true);

/*
|--------------------------------------------------------------------------
| Kirana Travel Constants
|--------------------------------------------------------------------------
*/
defined('TR_DEBUG_MODE') OR define('TR_DEBUG_MODE', true);
defined('TR_EMAIL_DEBUG') OR define('TR_EMAIL_DEBUG', true);

defined('TR_EMAIL_TESTER') OR define('TR_EMAIL_TESTER', json_encode(array('airiza.perdana@kiranamegatara.com', 'benazi.bahari@kiranamegatara.com', 'rainhard.rahakbauw@kiranamegatara.com')));
// defined('TR_EMAIL_TESTER') OR define('TR_EMAIL_TESTER', json_encode(array('octe.nugroho@kiranamegatara.com', 'auliya.umami@kiranamegatara.com', 'airiza.perdana@kiranamegatara.com')));

defined('TR_UPLOAD_ALLOWED') OR define('TR_UPLOAD_ALLOWED', 'jpeg|jpg|png|pdf');
defined('TR_UPLOAD_MAX') OR define('TR_UPLOAD_MAX', 2000);
defined('TR_PATH_FILE') OR define('TR_PATH_FILE', realpath('./') . '/assets/file/travel/');
defined('TR_APPROVAL_UPLOAD_FOLDER') OR define('TR_APPROVAL_UPLOAD_FOLDER', 'approval/');
defined('TR_CANCEL_UPLOAD_FOLDER') OR define('TR_CANCEL_UPLOAD_FOLDER', 'cancel/');
defined('TR_BOOKING_UPLOAD_FOLDER') OR define('TR_BOOKING_UPLOAD_FOLDER', 'booking/');
defined('TR_CHAT_UPLOAD_FOLDER') OR define('TR_CHAT_UPLOAD_FOLDER', 'chat/');
defined('TR_DEKLARASI_UPLOAD_FOLDER') OR define('TR_DEKLARASI_UPLOAD_FOLDER', 'deklarasi/');

defined('TR_SPD_TRANSPORT_LABELS') or define('TR_SPD_TRANSPORT_LABELS', json_encode(array(
    'pesawat' => 'Pesawat',
    'taxi' => 'Taxi',
    'kereta' => 'Kereta/Bus',
    'kendaraan_cop' => 'Kendaraan COP / Pribadi',
    'kendaraan_perusahaan' => 'Kendaraan Perusahaan',
    'kendaraan_sewa' => 'Kendaraan Sewa',
)));
defined('TR_STATUS_MENUNGGU') OR define('TR_STATUS_MENUNGGU', 0);
defined('TR_STATUS_DISETUJUI') OR define('TR_STATUS_DISETUJUI', 1);
defined('TR_STATUS_DITOLAK') OR define('TR_STATUS_DITOLAK', 2);
defined('TR_STATUS_REVISI') OR define('TR_STATUS_REVISI', 3);
defined('TR_STATUS_SIAP') OR define('TR_STATUS_SIAP', 4);
defined('TR_STATUS_SELESAI') OR define('TR_STATUS_SELESAI', 5);
defined('TR_STATUS_DIBATALKAN') OR define('TR_STATUS_DIBATALKAN', 6);
defined('TR_STATUS_DIHAPUS') OR define('TR_STATUS_DIHAPUS', 7);
defined('TR_LEVEL_1') OR define('TR_LEVEL_1', 1);
defined('TR_LEVEL_FINISH') OR define('TR_LEVEL_FINISH', 99);

defined('TR_INPUT_MAXLENGTH') OR define('TR_INPUT_MAXLENGTH', 55);
defined('TR_BACKDATED_DAYS_MAX') OR define('TR_BACKDATED_DAYS_MAX', 7);
defined('TR_DAYS_MAX') OR define('TR_DAYS_MAX', 30);

/*
|--------------------------------------------------------------------------
| Kirana Assessment Constants
|--------------------------------------------------------------------------
*/
defined('KIRANA_ASSESSMENT_PASS_UPLOAD') OR define('KIRANA_ASSESSMENT_PASS_UPLOAD', md5('kiranaadminmaster') ); //tRiputr4adm!nM4ster
defined('KIRANA_ASSESSMENT_ADMIN') OR define('KIRANA_ASSESSMENT_ADMIN', json_encode(array('7849', '7143', '8347','801111')));

defined('DEBUG_MODE') or define('DEBUG_MODE', false);
defined('SERVER_FCM_KEY') or define('SERVER_FCM_KEY', 'AAAAbQV7Rqg:APA91bESoTFHtpSDazuZxbNzEQdqOmAeaiMKzLSzhzb3bs83FsBS6hjDB9WhF9d5PmCAX3kDBDsl6ch_4mtNwT80D3mQJfH-xq3mmT_R7Mse1eytfAxq3jFISslrULPw1OFnj6W9YXoB');
defined('SERVER_WA_KEY') or define('SERVER_WA_KEY', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJuYW1lIjoiS2lyYW5hIE1lZ2F0YXJhIiwiYWRkcmVzcyI6IiIsInBpYyI6IiIsImlzX2RlbGV0ZSI6ZmFsc2UsImRhdGVfY3JlYXRlZCI6IjIwMjItMDctMTlUMDc6MTU6MzUuNjcxWiIsIl9pZCI6IjYyZDY1YTE3ZjVjYjQ2OWQ4MzhmZmVlMyJ9.tXIgo3tevlV1QA1k6aX6sRP9j8evsDQVHLHMpsnE_Hk');
