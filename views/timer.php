
<section class="container-fluid panel">
    <section class="row justify-content-center">
        <section class="mt-3 button-wrapper">
            <button type="button" class="btn btn-dark raised " id="timerbutton" onclick="toggleTimer()">Start Timer</button>
        </section>
    </section>
    <section class="row justify-content-center">
        <section>
            <div class="your-clock"></div>
        </section>
    </section>

    <section class="row justify-content-center">
        <section class="w-75 text-center">
            <label for="note" class="font-weight-bold">Note:</label>
            <textarea class="form-control" rows="5" id="note" title="Note"></textarea>
        </section>
    </section>
</section>

<script>
    initiateTimer();
</script>
