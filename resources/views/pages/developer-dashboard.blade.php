<div class="space-y-8 sm:space-y-10">
        <div>
            <h1 class="mt-2 text-3xl font-semibold text-neutral-950 sm:text-4xl">Selamat datang, {{ $displayName }}</h1>
            <p class="mt-2 max-w-2xl text-sm leading-6 text-neutral-600">
                Anda masuk menggunakan akun developer. Informasi Runtime aplikasi tersedia di bawah.
            </p>
        </div>



    <section aria-labelledby="stack-heading" class="space-y-4">
        <h2 id="stack-heading" class="text-lg font-semibold text-neutral-900">Stack aplikasi</h2>

        <div class="grid gap-x-10 gap-y-6 border-y border-neutral-200 py-5 sm:grid-cols-2 lg:grid-cols-3">
            <article>
                <h3 class="text-sm font-semibold text-neutral-900">Backend</h3>
                <p class="mb-2 text-sm leading-6 text-neutral-600">PHP {{ PHP_VERSION }}, Laravel {{ app()->version() }}, Livewire, Volt, Blade</p>
            </article>
            <article>
                <h3 class="text-sm font-semibold text-neutral-900">Frontend dan build</h3>
                <p class="mb-2 text-sm leading-6 text-neutral-600">JavaScript, Tailwind CSS, Flowbite, Vite, Axios</p>
            </article>
            <article>
                <h3 class="text-sm font-semibold text-neutral-900">Database</h3>
                <p class="mb-2 text-sm leading-6 text-neutral-600">{{ ucfirst(config('database.default')) }}</p>
            </article>
            <article>
                <h3 class="text-sm font-semibold text-neutral-900">Dokumen dan QR</h3>
                <p class="mb-2 text-sm leading-6 text-neutral-600">Laravel DomPDF, TCPDF, Simple QRCode, html5-qrcode</p>
            </article>
            <article>
                <h3 class="text-sm font-semibold text-neutral-900">Notifikasi</h3>
                <p class="mb-2 text-sm leading-6 text-neutral-600">Toastify JS</p>
            </article>
            <article>
                <h3 class="text-sm font-semibold text-neutral-900">Environment</h3>
                <p class="mb-2 text-sm leading-6 text-neutral-600">{{ app()->environment() }}</p>
            </article>
        </div>
    </section>

</div>