<div {{ $attributes }}>
    <button data-drawer-target="logo-sidebar" data-drawer-toggle="logo-sidebar" aria-controls="logo-sidebar" type="button"
        class="text-heading bg-transparent box-border border border-transparent hover:bg-neutral-secondary-medium focus:ring-4 focus:ring-neutral-tertiary font-medium leading-5 rounded-base ms-3 mt-3 text-sm p-2 focus:outline-none inline-flex sm:hidden">
        <span class="sr-only">Open sidebar</span>
        <svg class="w-6 h-6" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
            fill="none" viewBox="0 0 24 24">
            <path stroke="currentColor" stroke-linecap="round" stroke-width="2" d="M5 7h14M5 12h14M5 17h10" />
        </svg>
    </button>

    <aside id="logo-sidebar"
        class="fixed sm:relative top-0 left-0 z-40 w-64 h-full transition-transform -translate-x-full sm:translate-x-0"
        aria-label="Sidebar">
        <div class="h-full px-3 py-4 overflow-y-auto bg-neutral-primary-soft border-e border-default">
            <a href="#" class="flex items-center ps-2.5 mb-5">
                <img src="{{ asset('img/logo.png') }}" class="h-6 me-3" alt="Hikari Logo" />
                <span class="self-center text-lg text-heading font-semibold whitespace-nowrap">Hikari Gakkou</span>
            </a>
            <ul class="space-y-2 font-medium">
                <li>
                    <a href="/dashboard" wire:navigate
                        class="data-current:font-bold data-current:text-red-900 data-current:border-l-2 flex items-center px-2 py-1.5 text-body-subtle hover:rounded-r-base hover:text-gray-800 group">
                        <span class="ms-3">Dashboard</span>
                    </a>
                </li>
                <li>
                    <a href="{{ 'staff' }}" wire:navigate
                        class="data-current:font-bold data-current:text-red-900 data-current:border-l-2 flex items-center px-2 py-1.5 text-body-subtle hover:rounded-r-base hover:text-gray-800 group">
                        <span class="ms-3">Staff</span>
                    </a>
                </li>
                <li>
                    <a href="/siswa" wire:navigate
                        class="data-current:font-bold data-current:text-red-900 data-current:border-l-2 flex items-center px-2 py-1.5 text-body-subtle hover:rounded-r-base   hover:text-gray-800 group">
                        <span class="ms-3">Siswa</span>
                    </a>
                    {{-- <a href="/tes" wire:navigate class="flex items-center px-2 py-1.5 text-body rounded-base hover:bg-neutral-tertiary hover:text-fg-brand group"> --}}
                    {{-- <svg class="shrink-0 w-5 h-5 transition duration-75 group-hover:text-fg-brand" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24"><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v14M9 5v14M4 5h16a1 1 0 0 1 1 1v12a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V6a1 1 0 0 1 1-1Z"/></svg> --}}
                    {{-- <span class="flex-1 ms-3 whitespace-nowrap">Kanban</span> --}}
                    {{-- <span class="bg-neutral-secondary-medium border border-default-medium text-heading text-xs font-medium px-1.5 py-0.5 rounded-sm">Pro</span> --}}
                    {{-- </a> --}}
                </li>
                <li>
                    <a href="{{ 'jobfair' }}" wire:navigate
                        class="data-current:font-bold data-current:text-red-900 data-current:border-l-2 flex items-center px-2 py-1.5 text-body-subtle hover:rounded-r-base hover:text-gray-800 group">
                        <span class="ms-3">Job Order</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('kelas') }}" wire:navigate
                        class="data-current:font-bold data-current:text-red-900 data-current:border-l-2 flex items-center px-2 py-1.5 text-body-subtle hover:rounded-r-base hover:text-gray-800 group">
                        <span class="ms-3">Kelas</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('so') }}" wire:navigate
                        class="data-current:font-bold data-current:text-red-900 data-current:border-l-2 flex items-center px-2 py-1.5 text-body-subtle hover:rounded-r-base hover:text-gray-800 group">
                        <span class="ms-3">SO</span>
                    </a>
                </li>
                <li>
                    <a href="{{ 'laporan' }}" wire:navigate
                        class="data-current:font-bold data-current:text-red-900 data-current:border-l-2 flex items-center px-2 py-1.5 text-body-subtle hover:rounded-r-base hover:text-gray-800 group">
                        <span class="ms-3">Laporan</span>
                    </a>
                </li>
                
                <li>
                    <a href="{{ 'dokumen' }}" wire:navigate
                        class="data-current:font-bold data-current:text-red-900 data-current:border-l-2 flex items-center px-2 py-1.5 text-body-subtle hover:rounded-r-base hover:text-gray-800 group">
                        <span class="ms-3">Dokumen</span>
                    </a>
                </li>
                <li>
                    <a href="{{ 'setting' }}" wire:navigate
                        class="data-current:font-bold data-current:text-red-900 data-current:border-l-2 flex items-center px-2 py-1.5 text-body-subtle hover:rounded-r-base hover:text-gray-800 group">
                        <span class="ms-3">Settings</span>
                    </a>
                </li>
                <li>
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button 
                            class="data-current:font-bold data-current:text-red-900 data-current:border-l-2 flex items-center px-2 py-1.5 text-body-subtle hover:rounded-r-base hover:text-gray-800 group cursor-pointer">
                            <span class="ms-3">Logout</span>
                        </button>
                    </form>
                </li>
                
            </ul>
        </div>
    </aside>






    {{-- <nav>
            <a href="/dashboard" wire:navigate>Das</a>
            <a href="/tes" wire:navigate>Tes</a>
        </nav> --}}
</div>
