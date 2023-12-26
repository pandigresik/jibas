<?php
/**[N]**
 * JIBAS Education Community
 * Jaringan Informasi Bersama Antar Sekolah
 *
 * @version: 29.0 (Sept 20, 2023)
 * @notes: JIBAS Education Community will be managed by Yayasan Indonesia Membaca (http://www.indonesiamembaca.net)
 *
 * Copyright (C) 2009 Yayasan Indonesia Membaca (http://www.indonesiamembaca.net)
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 **[N]**/ ?>
<?php
class CbeState
{
    const Connect = 100;
    const Logout = 101;
    const Test = 104;

    const StartUjian = 200;
    const RequestSoal = 201;
    const SimpanJawaban = 202;
    const UpdateElapsedTime = 203;
    const FinishUjian = 204;
    const PingServer = 205;
    const RequestRuangan = 206;
    const JadwalUjian = 207;
    const PilihanUjianKhusus = 208;
    const DaftarUjianKhusus = 209;
    const PilihanUjianUmum = 210;
    const DaftarUjianUmum = 211;
    const PilihanUjianRemedial = 212;
    const DaftarUjianRemedial = 213;

    const RekapPelajaran = 214;
    const RekapDaftar = 215;
    const RekapDetail = 216;
    const GetElapsedTime = 217;

    const GetUserList = 500;
    const CloseUserConn = 501;

    const TestConn = 502;
    const CheckTestConn = 503;
    const Reserved1 = 504;
    const Reserved2 = 505;
    const Reserved3 = 506;

    const CheckAllowUjianOffline = 507;
    const InitUjianOffline = 508;
    const CheckAllowSubmitUjianOffline = 509;
    const SubmitUjianOffline = 510;

    const GetPrivateMessage = 511;
    const SendPrivateMessage = 512;
    const GetBroadcastMessage = 513;
    const SendBroadcastMessage = 514;

    const ConfirmDownload = 515;
    const CancelUjianOffline = 516;
    const SendLocalListenAddress = 517;
    const DaftarPesertaUjianOffline = 518;

    // 2020-11-18
    const ClearConn = 519;
}
?>