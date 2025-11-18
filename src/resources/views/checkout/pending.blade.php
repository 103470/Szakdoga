@extends('layouts.main')

@section('content')
<h2>Fizetés feldolgozása…</h2>
<p>Kérjük, ne zárd be az oldalt.</p>

<script>
    const sessionId = "{{ request('session_id') }}";

    async function checkStatus() {
        const res = await fetch(`/checkout/payment-status?session_id=${sessionId}`);
        const data = await res.json();

        if (data.status === 'succeeded') {
            window.location.href = `/checkout/success?order=${data.order_id}`;
        } else if (data.status === 'failed') {
            document.body.innerHTML = "<h2>Hiba történt a fizetés feldolgozásakor.</h2>";
        } else {
            setTimeout(checkStatus, 2000);
        }
    }

    checkStatus();
</script>


@endsection
