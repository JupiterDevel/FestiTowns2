@props(['clientId', 'slotId', 'type' => 'display', 'style' => 'display:block', 'format' => 'auto'])

@if($clientId && $slotId)
    <div class="adsense-container" style="min-height: 100px;">
        <ins class="adsbygoogle"
             style="{{ $style }}"
             data-ad-client="{{ $clientId }}"
             data-ad-slot="{{ $slotId }}"
             data-ad-format="{{ $format }}"
             data-full-width-responsive="true"></ins>
    </div>
    <script>
        (adsbygoogle = window.adsbygoogle || []).push({});
    </script>
@endif

