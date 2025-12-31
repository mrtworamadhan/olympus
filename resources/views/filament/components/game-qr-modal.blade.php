<table width="100%" cellpadding="0" cellspacing="0">
    <tr>
        <td align="center">

            <div style="display:inline-block; text-align:center; justify-content: center;">

                {{-- QR --}}
                <div style="padding:10px; background:#fff; border:2px solid #e5e7eb; border-radius:16px; margin-bottom:12px;">
                    {{ 
                        \SimpleSoftwareIO\QrCode\Facades\QrCode::size(200)
                            ->color(15, 23, 42)
                            ->margin(1)
                            ->generate(route('public.match', $game->id)) 
                    }}
                </div>

                {{-- Title --}}
                <div style="font-weight:700; font-size:16px; color:#2563eb; margin-bottom:4px;">
                    PUBLIC LINK
                </div>

                <div style="font-size:12px; color:#6b7280; margin-bottom:12px;">
                    Scan atau copy link di bawah untuk membagikan skor.
                </div>

                {{-- Input --}}
                <input
                    type="text"
                    readonly
                    value="{{ route('public.match', $game->id) }}"
                    style="
                        width:100%;
                        max-width:420px;
                        font-size:11px;
                        text-align:center;
                        background:#f9fafb;
                        border:none;
                        padding:8px 12px;
                        border-radius:8px;
                        color:#6b7280;
                        cursor:text;
                    "
                    onclick="this.select(); navigator.clipboard.writeText(this.value)"
                >

            </div>

        </td>
    </tr>
</table>
