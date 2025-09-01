<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-3">
                <div
                    class="w-8 h-8 bg-gradient-to-br from-primary-500 to-accent-500 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6l4 2" />
                    </svg>
                </div>
                <div>
                    <h2 class="text-xl font-bold text-white">Imprint</h2>
                    <p class="text-sm text-slate-400">Legal notice</p>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="card p-8 animate-fade-in-up space-y-6 text-slate-300">
                <div>
                    <h3 class="text-white font-semibold mb-2">Company Details</h3>
                    <p>Mike Rauch<br />Im Turmswinkel 12<br />38122 Braunschweig<br />Germany</p>
                    <p class="mt-2">Email: <a class="text-primary-300"
                            href="mailto:info@issueforge.com">info@issueforge.com</a><br />Phone: +49 531 21939351</p>
                </div>

                <div>
                    <h3 class="text-white font-semibold mb-2">Responsible under Section 18 (2) MStV</h3>
                    <p>Mike Rauch, Im Turmswinkel 12, 38122 Braunschweig, Germany</p>
                </div>

                <div>
                    <h3 class="text-white font-semibold mb-2">Nature of Website</h3>
                    <p>This website is operated by a private individual and is non‑commercial. No trade register entry,
                        VAT ID or professional chamber membership applies.</p>
                </div>

                <div>
                    <h3 class="text-white font-semibold mb-2">Liability for Contents</h3>
                    <p>
                        As a service provider, we are responsible for our own content on these pages in accordance with
                        general laws. We are not obligated to monitor transmitted or stored third‑party information or
                        to investigate circumstances that indicate illegal activity. Obligations to remove or block the
                        use of information under general laws remain unaffected.
                    </p>
                </div>

                <div>
                    <h3 class="text-white font-semibold mb-2">Liability for Links</h3>
                    <p>
                        Our offer contains links to external third‑party websites, the contents of which we have no
                        influence over. Therefore, we cannot assume any liability for these external contents. The
                        respective provider or operator of the pages is always responsible for the contents of the
                        linked pages.
                    </p>
                </div>

                <div>
                    <h3 class="text-white font-semibold mb-2">Copyright</h3>
                    <p>
                        The content and works created by the site operators on these pages are subject to copyright law.
                        Duplication, processing, distribution, or any form of commercialization of such material beyond
                        the scope of copyright law requires the prior written consent of its respective author or
                        creator.
                    </p>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
