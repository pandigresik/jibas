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
    final public const Connect = 100;
    final public const Logout = 101;
    final public const Test = 104;

    final public const StartUjian = 200;
    final public const RequestSoal = 201;
    final public const SimpanJawaban = 202;
    final public const UpdateElapsedTime = 203;
    final public const FinishUjian = 204;
    final public const PingServer = 205;
    final public const RequestRuangan = 206;
    final public const JadwalUjian = 207;
    final public const PilihanUjianKhusus = 208;
    final public const DaftarUjianKhusus = 209;
    final public const PilihanUjianUmum = 210;
    final public const DaftarUjianUmum = 211;
    final public const PilihanUjianRemedial = 212;
    final public const DaftarUjianRemedial = 213;

    final public const RekapPelajaran = 214;
    final public const RekapDaftar = 215;
    final public const RekapDetail = 216;
    final public const GetElapsedTime = 217;

    final public const GetUserList = 500;
    final public const CloseUserConn = 501;

    final public const TestConn = 502;
    final public const CheckTestConn = 503;
    final public const Reserved1 = 504;
    final public const Reserved2 = 505;
    final public const Reserved3 = 506;

    final public const CheckAllowUjianOffline = 507;
    final public const InitUjianOffline = 508;
    final public const CheckAllowSubmitUjianOffline = 509;
    final public const SubmitUjianOffline = 510;

    final public const GetPrivateMessage = 511;
    final public const SendPrivateMessage = 512;
    final public const GetBroadcastMessage = 513;
    final public const SendBroadcastMessage = 514;

    final public const ConfirmDownload = 515;
    final public const CancelUjianOffline = 516;
    final public const SendLocalListenAddress = 517;
    final public const DaftarPesertaUjianOffline = 518;

    // 2020-11-18
    final public const ClearConn = 519;
}
?>