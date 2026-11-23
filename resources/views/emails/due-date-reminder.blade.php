<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Pengingat Jatuh Tempo</title>
</head>
<body style="margin:0;padding:0;background-color:#FAF8F5;font-family:Inter,'Segoe UI',Roboto,Helvetica,Arial,sans-serif;color:#3B362D;">
    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background-color:#FAF8F5;">
        <tr>
            <td align="center" style="padding:32px 16px;">
                <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="max-width:520px;background-color:#FFFFFF;border:1px solid #EDEAE4;border-radius:12px;overflow:hidden;">
                    <tr>
                        <td style="padding:28px 32px 8px;">
                            <img src="{{ asset('logo-pustaka.svg') }}" alt="Pustaka" width="132" style="display:block;width:132px;max-width:100%;height:auto;border:0;">
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:16px 32px 0;">
                            <h1 style="margin:0;font-size:20px;line-height:1.4;color:#221F19;font-weight:700;">
                                Hai {{ $borrowing->user->name }}, bukunya jatuh tempo besok
                            </h1>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:12px 32px 0;">
                            <p style="margin:0;font-size:15px;line-height:1.6;color:#5C564B;">
                                Ini pengingat kecil dari Pustaka. Buku yang kamu pinjam perlu dikembalikan besok,
                                <strong style="color:#221F19;">{{ $borrowing->due_at->isoFormat('dddd, D MMMM YYYY') }}</strong>.
                            </p>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:24px 32px 0;">
                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background-color:#F5F3F0;border:1px solid #EDEAE4;border-radius:10px;">
                                <tr>
                                    <td style="padding:18px 20px;">
                                        <div style="font-size:12px;letter-spacing:.08em;text-transform:uppercase;color:#A8A08D;margin-bottom:6px;">Buku</div>
                                        <div style="font-size:17px;font-weight:700;color:#221F19;line-height:1.4;">{{ $borrowing->book->title }}</div>
                                        <div style="font-size:14px;color:#7E776A;margin-top:2px;">{{ $borrowing->book->author }}</div>

                                        <div style="height:1px;background-color:#E0D9CC;margin:16px 0;"></div>

                                        <table role="presentation" width="100%" cellpadding="0" cellspacing="0">
                                            <tr>
                                                <td style="font-size:13px;color:#7E776A;padding:3px 0;">Peminjam</td>
                                                <td align="right" style="font-size:13px;color:#221F19;font-weight:600;padding:3px 0;">{{ $borrowing->user->name }}</td>
                                            </tr>
                                            <tr>
                                                <td style="font-size:13px;color:#7E776A;padding:3px 0;">Tanggal dipinjam</td>
                                                <td align="right" style="font-size:13px;color:#221F19;font-weight:600;padding:3px 0;">{{ $borrowing->borrowed_at->isoFormat('D MMM YYYY') }}</td>
                                            </tr>
                                            <tr>
                                                <td style="font-size:13px;color:#7E776A;padding:3px 0;">Jatuh tempo</td>
                                                <td align="right" style="font-size:13px;color:#221F19;font-weight:700;padding:3px 0;">{{ $borrowing->due_at->isoFormat('D MMM YYYY') }}</td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:24px 32px 0;">
                            <table role="presentation" cellpadding="0" cellspacing="0">
                                <tr>
                                    <td align="center" style="border-radius:8px;background-color:#3B82F6;">
                                        <a href="{{ url('/buku-saya') }}"
                                           style="display:inline-block;padding:12px 22px;font-size:14px;font-weight:600;color:#FFFFFF;text-decoration:none;border-radius:8px;">
                                            Lihat Buku Saya
                                        </a>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:20px 32px 0;">
                            <p style="margin:0;font-size:13px;line-height:1.6;color:#7E776A;">
                                @if($borrowing->extended)
                                    Buku ini sudah diperpanjang 1× dan tidak bisa diperpanjang lagi.
                                    Segera kembalikan untuk menghindari denda Rp1.000 per hari.
                                @else
                                    Butuh waktu lebih? Kamu masih bisa memperpanjang 1× (tambah 7 hari)
                                    selama buku belum jatuh tempo, langsung dari halaman Buku Saya.
                                    Terlambat mengembalikan dikenai denda Rp1.000 per hari.
                                @endif
                            </p>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:28px 32px 32px;">
                            <p style="margin:0;font-size:13px;line-height:1.6;color:#A8A08D;">
                                Email ini dikirim otomatis oleh Pustaka. Tidak perlu membalasnya.
                            </p>
                        </td>
                    </tr>
                </table>

                <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="max-width:520px;">
                    <tr>
                        <td align="center" style="padding-top:20px;font-size:12px;color:#A8A08D;">
                            &copy; {{ date('Y') }} Pustaka &mdash; Hangat, nyaman dibaca lama.
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>