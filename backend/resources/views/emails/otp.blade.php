    <!DOCTYPE html>
    <html lang="id">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Reset Password - Kode OTP</title>
    </head>
    <body style="margin: 0; padding: 0; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #f4f7fa;">
        <table width="100%" cellpadding="0" cellspacing="0" style="background-color: #f4f7fa; padding: 40px 20px;">
            <tr>
                <td align="center">
                    <table width="600" cellpadding="0" cellspacing="0" style="background-color: #ffffff; border-radius: 12px; box-shadow: 0 4px 12px rgba(0,0,0,0.08); overflow: hidden;">

                        <!-- Header Section -->
                        <tr>
                            <td style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); padding: 40px 30px; text-align: center;">
                                <h1 style="margin: 0; color: #ffffff; font-size: 28px; font-weight: 600; letter-spacing: 0.5px;">
                                    Verifikasi Keamanan Akun
                                </h1>
                                <p style="margin: 10px 0 0 0; color: #f0f0f0; font-size: 14px; font-weight: 300;">
                                    Permintaan Reset Password Terdeteksi
                                </p>
                            </td>
                        </tr>

                        <!-- Main Content -->
                        <tr>
                            <td style="padding: 40px 40px 30px 40px;">
                                <p style="margin: 0 0 20px 0; color: #333333; font-size: 16px; line-height: 1.6;">
                                    Halo,
                                </p>

                                <p style="margin: 0 0 25px 0; color: #555555; font-size: 15px; line-height: 1.7;">
                                    Kami telah menerima permintaan untuk mengatur ulang kata sandi yang terkait dengan akun Anda. Sebagai bagian dari prosedur keamanan kami, sistem telah menghasilkan kode verifikasi sekali pakai (OTP) yang diperlukan untuk menyelesaikan proses reset password. Kode ini dirancang khusus untuk memastikan bahwa hanya Anda yang dapat mengakses dan mengubah informasi keamanan akun Anda.
                                </p>

                                <p style="margin: 0 0 15px 0; color: #333333; font-size: 15px; font-weight: 600;">
                                    Kode Verifikasi OTP Anda:
                                </p>

                                <!-- OTP Box -->
                                <table width="100%" cellpadding="0" cellspacing="0" style="margin: 0 0 30px 0;">
                                    <tr>
                                        <td align="center" style="padding: 25px; background: linear-gradient(135deg, #f5f7fa 0%, #e8ecf1 100%); border-radius: 10px; border: 2px dashed #667eea;">
                                            <div style="font-size: 36px; font-weight: 700; color: #667eea; letter-spacing: 8px; font-family: 'Courier New', monospace;">
                                                {{ $otp }}
                                            </div>
                                        </td>
                                    </tr>
                                </table>

                                <p style="margin: 0 0 25px 0; color: #555555; font-size: 15px; line-height: 1.7;">
                                    Kode OTP ini memiliki masa berlaku selama <strong style="color: #667eea;">10 menit</strong> sejak email ini dikirimkan. Setelah waktu tersebut berakhir, kode akan otomatis tidak dapat digunakan dan Anda perlu melakukan permintaan reset password baru melalui platform kami. Mohon segera gunakan kode ini untuk melanjutkan proses pengaturan ulang kata sandi Anda.
                                </p>

                                <!-- Warning Box -->
                                <table width="100%" cellpadding="0" cellspacing="0" style="margin: 0 0 25px 0;">
                                    <tr>
                                        <td style="padding: 20px; background-color: #fff3cd; border-left: 4px solid #ffc107; border-radius: 6px;">
                                            <p style="margin: 0 0 10px 0; color: #856404; font-size: 14px; font-weight: 600;">
                                                ⚠️ Peringatan Keamanan
                                            </p>
                                            <p style="margin: 0; color: #856404; font-size: 14px; line-height: 1.6;">
                                                Demi menjaga keamanan akun Anda, jangan pernah membagikan kode OTP ini kepada siapa pun, termasuk kepada pihak yang mengaku sebagai tim dukungan atau layanan pelanggan kami. Tim resmi kami tidak akan pernah meminta kode verifikasi Anda melalui telepon, email, atau pesan pribadi.
                                            </p>
                                        </td>
                                    </tr>
                                </table>

                                <p style="margin: 0 0 20px 0; color: #555555; font-size: 15px; line-height: 1.7;">
                                    Jika Anda tidak pernah melakukan permintaan untuk reset password atau merasa ada aktivitas mencurigakan pada akun Anda, kami sangat menyarankan Anda untuk segera mengabaikan email ini. Akun Anda akan tetap aman dan tidak ada perubahan yang akan terjadi. Namun, sebagai tindakan pencegahan tambahan, kami merekomendasikan Anda untuk segera menghubungi tim keamanan kami atau mengubah kata sandi Anda melalui pengaturan akun untuk memastikan keamanan maksimal.
                                </p>

                                <p style="margin: 0; color: #555555; font-size: 15px; line-height: 1.7;">
                                    Terima kasih atas kepercayaan Anda menggunakan layanan kami. Kami berkomitmen untuk terus menjaga keamanan dan privasi data Anda dengan standar tertinggi.
                                </p>
                            </td>
                        </tr>

                        <!-- Footer -->
                        <tr>
                            <td style="padding: 30px 40px; background-color: #f8f9fa; border-top: 1px solid #e9ecef;">
                                <p style="margin: 0 0 10px 0; color: #6c757d; font-size: 13px; line-height: 1.6; text-align: center;">
                                    Email ini dikirim secara otomatis oleh sistem. Mohon untuk tidak membalas email ini.
                                </p>
                                <p style="margin: 0; color: #6c757d; font-size: 13px; line-height: 1.6; text-align: center;">
                                    © 2024 Nama Perusahaan Anda. Semua hak dilindungi.
                                </p>
                                <p style="margin: 15px 0 0 0; color: #6c757d; font-size: 12px; text-align: center;">
                                    <a href="#" style="color: #667eea; text-decoration: none; margin: 0 10px;">Pusat Bantuan</a> |
                                    <a href="#" style="color: #667eea; text-decoration: none; margin: 0 10px;">Kebijakan Privasi</a> |
                                    <a href="#" style="color: #667eea; text-decoration: none; margin: 0 10px;">Syarat & Ketentuan</a>
                                </p>
                            </td>
                        </tr>

                    </table>
                </td>
            </tr>
        </table>
    </body>
    </html>
