<?php

namespace App\View\Components;

class EmailLogo
{
    public static function render()
    {
        // Koristimo emoji kao fallback umjesto slike
        return '
        <div style="background: linear-gradient(135deg, #789ae4 0%, #576cb1 100%); padding: 16px;">
            <div style="text-align: center;">
                <span style="color: white; font-size: 24px; font-weight: bold;">✈️ ExpensaGO</span>
            </div>
        </div>';
    }
}
