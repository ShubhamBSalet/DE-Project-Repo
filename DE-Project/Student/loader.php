<!-- LOADER START -->

<div id="loader-wrapper"
    class="position-fixed top-0 start-0 w-100 vh-100 bg-white d-flex justify-content-center align-items-center"
    style="z-index:9999;">

    <div class="text-center w-75" style="max-width:400px;">
        <h5 class="mb-3">Loading...</h5>

        <div class="progress" role="progressbar">
            <div id="progress-bar"
                class="progress-bar progress-bar-striped progress-bar-animated bg-primary"
                style="width: 0%">
            </div>
        </div>
    </div>

</div>

<script>
    let progress = 0;

    // Animate progress
    let interval = setInterval(function() {
        progress += 30;
        document.getElementById("progress-bar").style.width = progress + "%";

        if (progress >= 90) {
            clearInterval(interval);
        }
    }, 100);

    // Hide loader after page load
    window.addEventListener("load", function() {
        setTimeout(function() {
            document.getElementById("progress-bar").style.width = "100%";
            document.getElementById("loader-wrapper").classList.add("d-none");
        }, 500);
    });
</script>

<!-- LOADER END -->