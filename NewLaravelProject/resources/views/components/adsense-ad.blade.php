@props(['clientId', 'slotId' => null, 'type' => 'display', 'style' => 'display:block', 'format' => 'auto', 'testMode' => false])

@if(!empty($clientId))
    @php
        $uniqueId = 'adsense-' . uniqid();
    @endphp
    <div class="adsense-container" id="container-{{ $uniqueId }}" style="width: 100%; min-height: 100px; display: block; position: relative; box-sizing: border-box; visibility: visible; opacity: 1;">
        <ins class="adsbygoogle"
             id="ad-{{ $uniqueId }}"
             style="display:block; width: 100%; min-width: 300px; box-sizing: border-box;"
             data-ad-client="{{ $clientId }}"
             @if(isset($slotId) && $slotId)data-ad-slot="{{ $slotId }}"@endif
             data-ad-format="{{ $format }}"
             @if($testMode === true || $testMode === 'true' || $testMode === 1)data-adtest="on"@endif
             data-full-width-responsive="true"></ins>
    </div>
    <script>
        (adsbygoogle = window.adsbygoogle || []).push({});
    </script>
@endif

